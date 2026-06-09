<?php

/**
 * Controller: HistoricoController
 *
 * Gerencia o salvamento e consulta do histórico de diagnósticos.
 * Inclui filtros por projeto/status e paginação (Issue #26).
 */
class HistoricoController
{
    private Historico $model;
    private Pergunta  $perguntaModel;

    public function __construct()
    {
        $this->model         = new Historico();
        $this->perguntaModel = new Pergunta();
    }

    // -------------------------------------------------------------------------
    // GET /historico — lista diagnósticos com filtros e paginação
    // -------------------------------------------------------------------------

    public function index(): void
    {
        $this->exigirLogin();

        $usuarioId = (int) $_SESSION['user_id'];

        // Parâmetros de filtro e paginação via GET
        $filtros = [
            'projeto_id' => (int) ($_GET['projeto_id'] ?? 0) ?: null,
            'status'     => trim($_GET['status'] ?? ''),
        ];
        $paginaAtual = max(1, (int) ($_GET['page'] ?? 1));
        $porPagina   = 10;

        // Lista de TODOS os projetos do usuário para o select de filtro
        // (independente de terem diagnósticos ou não)
        $projetos = $this->buscarTodosProjetosDoUsuario($usuarioId);

        // Total de diagnósticos com filtros aplicados
        $totalDiagnosticos = $this->model->contarPorUsuario($usuarioId, $filtros);
        $totalPaginas      = (int) ceil($totalDiagnosticos / $porPagina);
        $paginaAtual       = min($paginaAtual, max(1, $totalPaginas));
        $offset            = ($paginaAtual - 1) * $porPagina;

        // Diagnósticos da página atual
        $historicos = $this->model->buscarPorUsuario($usuarioId, $filtros, $porPagina, $offset);

        // Resumo geral (sem filtros) para os cards de estatística
        $resumo = null;
        if ($totalDiagnosticos > 0 || (empty($filtros['projeto_id']) && empty($filtros['status']))) {
            $resumo = $this->model->resumoPorUsuario($usuarioId);
        }

        require BASE_PATH . '/app/views/historico/index.php';
    }

    // -------------------------------------------------------------------------
    // GET /historico/detalhe?id=X
    // -------------------------------------------------------------------------

    public function detalhe(): void
    {
        $this->exigirLogin();

        $historicoId = (int) ($_GET['id'] ?? 0);
        $usuarioId   = (int) $_SESSION['user_id'];

        if ($historicoId === 0) {
            header("Location: /projetos");
            exit;
        }

        $dados = $this->model->buscarDetalhePorId($historicoId, $usuarioId);

        if (!$dados) {
            http_response_code(404);
            require BASE_PATH . '/app/views/404.php';
            return;
        }

        $cabecalho = $dados['cabecalho'];
        $respostas = $dados['respostas'];

        require BASE_PATH . '/app/views/historico/detalhe.php';
    }

    // -------------------------------------------------------------------------
    // POST /salvar-resultado — salva o diagnóstico no histórico
    // -------------------------------------------------------------------------

    public function salvar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /projetos");
            exit;
        }

        $this->exigirLogin();

        // Valida CSRF
        $tokenSessao = $_SESSION['csrf_token'] ?? '';
        $tokenPost   = $_POST['csrf_token']    ?? '';
        if (empty($tokenSessao) || !hash_equals($tokenSessao, $tokenPost)) {
            $this->jsonErro("Requisição inválida.");
            return;
        }

        $usuarioId = (int) $_SESSION['user_id'];
        $projetoId = (int) ($_POST['projeto_id'] ?? 0);

        try {
            $dados = $this->perguntaModel->buscarComRespostasEMateriais($usuarioId);

            if (empty($dados)) {
                $this->jsonErro("Nenhuma resposta encontrada. Faça o checklist antes de salvar.");
                return;
            }

            $totalPontos      = 0;
            $pontosObtidos    = 0;
            $totalPerguntas   = count($dados);
            $perguntasOk      = 0;
            $perguntasFalha   = 0;
            $respostasDetalhe = [];

            foreach ($dados as $item) {
                $peso         = (int) $item['peso'];
                $totalPontos += $peso;

                if ($item['resposta'] == 1) {
                    $pontosObtidos += $peso;
                    $perguntasOk++;
                } else {
                    $perguntasFalha++;
                }

                $respostasDetalhe[] = [
                    'pergunta_id'    => (int) ($item['id']        ?? 0),
                    'pergunta_texto' =>        $item['texto']     ?? '',
                    'pergunta_peso'  => $peso,
                    'categoria_nome' =>        $item['categoria'] ?? 'Geral',
                    'resposta'       => (int)  $item['resposta'],
                ];
            }

            $percentual = ($totalPontos > 0)
                ? round(($pontosObtidos / $totalPontos) * 100)
                : 0;

            $historicoId = $this->model->salvar(
                $usuarioId,
                $percentual,
                $totalPontos,
                $pontosObtidos,
                $totalPerguntas,
                $perguntasOk,
                $perguntasFalha,
                $respostasDetalhe,
                $projetoId ?: null
            );

            header('Content-Type: application/json');
            echo json_encode([
                'sucesso'      => true,
                'mensagem'     => "Diagnóstico de {$percentual}% salvo com sucesso!",
                'historico_id' => $historicoId,
                'projeto_id'   => $projetoId ?: null,
            ]);

        } catch (\Exception $e) {
            error_log('[LGPD4DEVS] Erro ao salvar histórico: ' . $e->getMessage());
            $this->jsonErro("Erro ao salvar. Tente novamente.");
        }
    }

    // -------------------------------------------------------------------------
    // POST /historico/deletar
    // -------------------------------------------------------------------------

    public function deletar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /projetos");
            exit;
        }

        $this->exigirLogin();

        $tokenSessao = $_SESSION['csrf_token'] ?? '';
        $tokenPost   = $_POST['csrf_token']    ?? '';
        if (empty($tokenSessao) || !hash_equals($tokenSessao, $tokenPost)) {
            header("Location: /projetos");
            exit;
        }

        $historicoId = (int) ($_POST['historico_id'] ?? 0);
        $projetoId   = (int) ($_POST['projeto_id']   ?? 0);
        $usuarioId   = (int) $_SESSION['user_id'];

        $this->model->deletar($historicoId, $usuarioId);

        if ($projetoId) {
            header("Location: /projetos/detalhe?id={$projetoId}");
        } else {
            header("Location: /historico");
        }
        exit;
    }

    // -------------------------------------------------------------------------
    // Helper privado: busca TODOS os projetos do usuário (com ou sem diagnósticos)
    // -------------------------------------------------------------------------

    private function buscarTodosProjetosDoUsuario(int $usuarioId): array
    {
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT id, nome, status
            FROM projetos
            WHERE usuario_id = ?
            ORDER BY nome ASC
        ");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
    }

    // -------------------------------------------------------------------------
    // Helpers privados
    // -------------------------------------------------------------------------

    private function exigirLogin(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
    }

    private function jsonErro(string $mensagem): void
    {
        header('Content-Type: application/json');
        echo json_encode(['sucesso' => false, 'mensagem' => $mensagem]);
    }
}