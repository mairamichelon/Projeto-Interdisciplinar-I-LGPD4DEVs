<?php

/**
 * Controller: ProjetoController
 *
 * Gerencia a listagem, criação, edição e remoção de projetos.
 */
class ProjetoController
{
    private Projeto   $model;
    private Historico $historicoModel;

    public function __construct()
    {
        $this->model          = new Projeto();
        $this->historicoModel = new Historico();
    }

    // -------------------------------------------------------------------------
    // GET /projetos — lista todos os projetos do usuário
    // -------------------------------------------------------------------------

    public function index(): void
    {
        $this->exigirLogin();
        $usuarioId = (int) $_SESSION['user_id'];
        $projetos  = $this->model->buscarPorUsuario($usuarioId);

        require BASE_PATH . '/app/views/projetos/index.php';
    }

    // -------------------------------------------------------------------------
    // GET /api/projetos — API para o select de projetos
    // Retorna JSON mesmo quando não autenticado (nunca redireciona)
    // -------------------------------------------------------------------------

    public function apiProjetos(): void
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode([]);
            return;
        }

        $usuarioId = (int) $_SESSION['user_id'];
        $projetos  = $this->model->buscarPorUsuario($usuarioId);
        echo json_encode($projetos);
    }

    // -------------------------------------------------------------------------
    // GET /api/projetos/detalhes — API para o modal de projetos
    // -------------------------------------------------------------------------

    public function apiDetalhes(): void
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode([]);
            return;
        }

        $usuarioId = (int) $_SESSION['user_id'];
        $detalhes  = $this->model->buscarDetalhesPorUsuario($usuarioId);
        echo json_encode($detalhes);
    }

    // -------------------------------------------------------------------------
    // GET /projetos/detalhe?id=X — diagnósticos de um projeto
    // -------------------------------------------------------------------------

    public function detalhe(): void
    {
        $this->exigirLogin();

        $projetoId = (int) ($_GET['id'] ?? 0);
        $usuarioId = (int) $_SESSION['user_id'];

        if ($projetoId === 0) {
            header("Location: /projetos");
            exit;
        }

        $projeto = $this->model->buscarPorId($projetoId, $usuarioId);

        if (!$projeto) {
            http_response_code(404);
            require BASE_PATH . '/app/views/404.php';
            return;
        }

        $historicos = $this->historicoModel->buscarPorProjeto($projetoId, $usuarioId);

        require BASE_PATH . '/app/views/projetos/detalhe.php';
    }

    // -------------------------------------------------------------------------
    // POST /projetos/criar — cria um novo projeto
    // -------------------------------------------------------------------------

    public function criar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /projetos");
            exit;
        }

        $this->exigirLogin();

        if (!$this->csrfValido()) {
            $this->jsonErro("Requisição inválida.");
            return;
        }

        $usuarioId   = (int) $_SESSION['user_id'];
        $nome        = trim($_POST['nome']         ?? '');
        $descricao   = trim($_POST['descricao']    ?? '');
        $publicoAlvo = trim($_POST['publico_alvo'] ?? 'ambos');
        $status      = trim($_POST['status']       ?? 'em_desenvolvimento');

        if (empty($nome)) {
            $this->jsonErro("O nome do projeto é obrigatório.");
            return;
        }

        $projetoId = $this->model->criar($usuarioId, $nome, $descricao, $publicoAlvo, $status);

        header('Content-Type: application/json');
        echo json_encode([
            'sucesso'    => true,
            'projeto_id' => $projetoId,
            'nome'       => $nome,
        ]);
    }

    // -------------------------------------------------------------------------
    // POST /projetos/editar — edita um projeto existente
    // -------------------------------------------------------------------------

    public function editar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /projetos");
            exit;
        }

        $this->exigirLogin();

        if (!$this->csrfValido()) {
            header("Location: /projetos");
            exit;
        }

        $usuarioId   = (int) $_SESSION['user_id'];
        $projetoId   = (int) ($_POST['projeto_id']  ?? 0);
        $nome        = trim($_POST['nome']           ?? '');
        $descricao   = trim($_POST['descricao']      ?? '');
        $publicoAlvo = trim($_POST['publico_alvo']   ?? 'ambos');
        $status      = trim($_POST['status']         ?? 'em_desenvolvimento');

        if ($projetoId && !empty($nome)) {
            $this->model->atualizar($projetoId, $usuarioId, $nome, $descricao, $publicoAlvo, $status);
        }

        header("Location: /projetos");
        exit;
    }

    // -------------------------------------------------------------------------
    // POST /projetos/deletar — remove um projeto
    // -------------------------------------------------------------------------

    public function deletar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /projetos");
            exit;
        }

        $this->exigirLogin();

        $projetoId = (int) ($_POST['projeto_id'] ?? 0);
        $usuarioId = (int) $_SESSION['user_id'];

        $this->model->deletar($projetoId, $usuarioId);

        header("Location: /projetos");
        exit;
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
}