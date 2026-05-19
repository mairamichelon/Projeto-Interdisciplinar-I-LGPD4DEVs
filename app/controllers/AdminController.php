<?php

/**
 * Controller: AdminController
 *
 * Gerencia o painel administrativo.
 * Todas as rotas exigem perfil 'admin'.
 */
class AdminController
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    // -------------------------------------------------------------------------
    // GET /admin — dashboard
    // -------------------------------------------------------------------------

    public function index(): void
    {
        $this->exigirAdmin();

        $stats               = $this->buscarStats();
        $ultimosUsuarios     = $this->buscarUltimosUsuarios();
        $ultimosDiagnosticos = $this->buscarUltimosDiagnosticos();

        require BASE_PATH . '/app/views/admin/dashboard.php';
    }

    // -------------------------------------------------------------------------
    // GET /api/admin/dashboard — polling de atualização em tempo real
    // -------------------------------------------------------------------------

    public function apiDashboard(): void
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id']) || ($_SESSION['perfil'] ?? '') !== 'admin') {
            echo json_encode(['erro' => 'Não autorizado']);
            return;
        }

        echo json_encode([
            'stats'               => $this->buscarStats(),
            'ultimosUsuarios'     => $this->buscarUltimosUsuarios(),
            'ultimosDiagnosticos' => $this->buscarUltimosDiagnosticos(),
        ]);
    }

    // -------------------------------------------------------------------------
    // GET /admin/materiais — lista materiais
    // -------------------------------------------------------------------------

    public function materiais(): void
    {
        $this->exigirAdmin();

        $materiais = $this->pdo->query("
            SELECT * FROM materiais ORDER BY id DESC
        ")->fetchAll();

        $perguntas = $this->pdo->query("
            SELECT p.id, p.texto, c.nome AS categoria
            FROM perguntas p
            JOIN categorias c ON p.categoria_id = c.id
            ORDER BY c.nome, p.id
        ")->fetchAll();

        require BASE_PATH . '/app/views/admin/materiais.php';
    }

    // -------------------------------------------------------------------------
    // POST /admin/materiais/salvar — cria ou edita material
    // -------------------------------------------------------------------------

    public function salvarMaterial(): void
    {
        $this->exigirAdmin();
        $this->exigirPost();

        if (!$this->csrfValido()) {
            $this->jsonErro("Requisição inválida.");
            return;
        }

        $id                = (int)   ($_POST['id']                 ?? 0);
        $titulo            = trim($_POST['titulo']             ?? '');
        $categoria         = trim($_POST['categoria']          ?? '');
        $descricaoCurta    = trim($_POST['descricao_curta']    ?? '');
        $conteudoDetalhado = trim($_POST['conteudo_detalhado'] ?? '');
        $urlReferencia     = trim($_POST['url_referencia']     ?? '');
        $perguntaIds       = $_POST['pergunta_ids'] ?? [];

        if (empty($titulo) || empty($categoria)) {
            $this->jsonErro("Título e categoria são obrigatórios.");
            return;
        }

        if ($id > 0) {
            $stmt = $this->pdo->prepare("
                UPDATE materiais
                SET titulo = ?, categoria = ?, descricao_curta = ?,
                    conteudo_detalhado = ?, url_referencia = ?
                WHERE id = ?
            ");
            $stmt->execute([$titulo, $categoria, $descricaoCurta, $conteudoDetalhado, $urlReferencia, $id]);
            $materialId = $id;
        } else {
            $stmt = $this->pdo->prepare("
                INSERT INTO materiais (titulo, categoria, descricao_curta, conteudo_detalhado, url_referencia)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$titulo, $categoria, $descricaoCurta, $conteudoDetalhado, $urlReferencia]);
            $materialId = (int) $this->pdo->lastInsertId();
        }

        $this->pdo->prepare("DELETE FROM pergunta_material WHERE material_id = ?")->execute([$materialId]);
        if (!empty($perguntaIds)) {
            $stmtVinculo = $this->pdo->prepare("
                INSERT IGNORE INTO pergunta_material (pergunta_id, material_id) VALUES (?, ?)
            ");
            foreach ($perguntaIds as $pid) {
                $stmtVinculo->execute([(int)$pid, $materialId]);
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['sucesso' => true, 'material_id' => $materialId]);
    }

    // -------------------------------------------------------------------------
    // POST /admin/materiais/deletar — remove material
    // -------------------------------------------------------------------------

    public function deletarMaterial(): void
    {
        $this->exigirAdmin();
        $this->exigirPost();

        if (!$this->csrfValido()) { $this->jsonErro("Requisição inválida."); return; }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id === 0) { $this->jsonErro("ID inválido."); return; }

        $this->pdo->prepare("DELETE FROM materiais WHERE id = ?")->execute([$id]);

        header('Content-Type: application/json');
        echo json_encode(['sucesso' => true]);
    }

    // -------------------------------------------------------------------------
    // GET /admin/usuarios — lista usuários
    // -------------------------------------------------------------------------

    public function usuarios(): void
    {
        $this->exigirAdmin();

        $usuarios = $this->pdo->query("
            SELECT
                u.id, u.nome, u.email, u.perfil, u.data_cadastro,
                COUNT(DISTINCT p.id)   AS total_projetos,
                COUNT(DISTINCT hd.id)  AS total_diagnosticos
            FROM usuarios u
            LEFT JOIN projetos p               ON p.usuario_id  = u.id
            LEFT JOIN historico_diagnosticos hd ON hd.usuario_id = u.id
            GROUP BY u.id, u.nome, u.email, u.perfil, u.data_cadastro
            ORDER BY u.data_cadastro DESC
        ")->fetchAll();

        require BASE_PATH . '/app/views/admin/usuarios.php';
    }

    // -------------------------------------------------------------------------
    // POST /admin/usuarios/perfil — altera perfil
    // -------------------------------------------------------------------------

    public function alterarPerfil(): void
    {
        $this->exigirAdmin();
        $this->exigirPost();

        if (!$this->csrfValido()) { $this->jsonErro("Requisição inválida."); return; }

        $id     = (int)  ($_POST['id']     ?? 0);
        $perfil = trim($_POST['perfil'] ?? '');

        if ($id === 0 || !in_array($perfil, ['usuario', 'admin'])) {
            $this->jsonErro("Dados inválidos.");
            return;
        }

        if ($id === (int) $_SESSION['user_id'] && $perfil === 'usuario') {
            $this->jsonErro("Você não pode remover seu próprio acesso de administrador.");
            return;
        }

        $this->pdo->prepare("UPDATE usuarios SET perfil = ? WHERE id = ?")->execute([$perfil, $id]);

        header('Content-Type: application/json');
        echo json_encode(['sucesso' => true]);
    }

    // -------------------------------------------------------------------------
    // POST /admin/usuarios/deletar — remove conta
    // -------------------------------------------------------------------------

    public function deletarUsuario(): void
    {
        $this->exigirAdmin();
        $this->exigirPost();

        if (!$this->csrfValido()) { $this->jsonErro("Requisição inválida."); return; }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id === 0) { $this->jsonErro("ID inválido."); return; }

        if ($id === (int) $_SESSION['user_id']) {
            $this->jsonErro("Você não pode deletar sua própria conta pelo painel.");
            return;
        }

        $this->pdo->prepare("DELETE FROM usuarios WHERE id = ?")->execute([$id]);

        header('Content-Type: application/json');
        echo json_encode(['sucesso' => true]);
    }

    // -------------------------------------------------------------------------
    // Helpers de dados (reutilizados pelo index e pela API)
    // -------------------------------------------------------------------------

    private function buscarStats(): array
    {
        return [
            'total_usuarios'     => $this->contar("SELECT COUNT(*) FROM usuarios WHERE perfil = 'usuario'"),
            'total_admins'       => $this->contar("SELECT COUNT(*) FROM usuarios WHERE perfil = 'admin'"),
            'total_projetos'     => $this->contar("SELECT COUNT(*) FROM projetos"),
            'total_diagnosticos' => $this->contar("SELECT COUNT(*) FROM historico_diagnosticos"),
            'total_materiais'    => $this->contar("SELECT COUNT(*) FROM materiais"),
            'media_conformidade' => (int) ($this->pdo->query("SELECT COALESCE(ROUND(AVG(percentual)), 0) FROM historico_diagnosticos")->fetchColumn()),
        ];
    }

    private function buscarUltimosUsuarios(): array
    {
        return $this->pdo->query("
            SELECT id, nome, email, perfil, data_cadastro
            FROM usuarios
            ORDER BY data_cadastro DESC
            LIMIT 5
        ")->fetchAll();
    }

    private function buscarUltimosDiagnosticos(): array
    {
        return $this->pdo->query("
            SELECT hd.percentual, hd.data_salvo,
                   u.nome AS usuario_nome,
                   p.nome AS projeto_nome
            FROM historico_diagnosticos hd
            JOIN usuarios u ON hd.usuario_id = u.id
            LEFT JOIN projetos p ON hd.projeto_id = p.id
            ORDER BY hd.data_salvo DESC
            LIMIT 5
        ")->fetchAll();
    }

    // -------------------------------------------------------------------------
    // Helpers privados
    // -------------------------------------------------------------------------

    private function exigirAdmin(): void
    {
        if (!isset($_SESSION['user_id']) || ($_SESSION['perfil'] ?? '') !== 'admin') {
            http_response_code(403);
            header("Location: /");
            exit;
        }
    }

    private function exigirPost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /admin");
            exit;
        }
    }

    private function csrfValido(): bool
    {
        $tokenSessao = $_SESSION['csrf_token'] ?? '';
        $tokenPost   = $_POST['csrf_token']    ?? '';
        return !empty($tokenSessao) && hash_equals($tokenSessao, $tokenPost);
    }

    private function jsonErro(string $mensagem): void
    {
        header('Content-Type: application/json');
        echo json_encode(['sucesso' => false, 'mensagem' => $mensagem]);
    }

    private function contar(string $sql): int
    {
        return (int) $this->pdo->query($sql)->fetchColumn();
    }
}