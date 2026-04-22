<?php

/**
 * Controller: ChecklistController
 * 
 * Gerencia a exibição do checklist e o processamento das respostas.
 * Unifica o que antes estava em checklist.php e processa_checklist.php.
 */
class ChecklistController
{
    private Pergunta $perguntaModel;
    private Resposta $respostaModel;

    public function __construct()
    {
        $this->perguntaModel = new Pergunta();
        $this->respostaModel = new Resposta();
    }

    // -------------------------------------------------------------------------
    // GET /checklist — exibe o formulário
    // -------------------------------------------------------------------------

    public function index(): void
    {
        try {
            $perguntas = $this->perguntaModel->buscarTodas();
        } catch (\PDOException $e) {
            error_log('[LGPD4DEVS] Erro ao carregar checklist: ' . $e->getMessage());
            die("Erro crítico ao carregar o checklist. Tente novamente mais tarde.");
        }

        // Gera CSRF para o formulário de envio
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        require BASE_PATH . '/app/views/checklist/index.php';
    }

    // -------------------------------------------------------------------------
    // POST /checklist — processa as respostas
    // -------------------------------------------------------------------------

    public function processar(): void
    {
        // Só aceita POST com respostas presentes
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['respostas'])) {
            header("Location: /checklist");
            exit;
        }

        // Valida CSRF
        $tokenSessao = $_SESSION['csrf_token'] ?? '';
        $tokenPost   = $_POST['csrf_token']    ?? '';
        if (empty($tokenSessao) || !hash_equals($tokenSessao, $tokenPost)) {
            header("Location: /checklist");
            exit;
        }

        $respostas  = $_POST['respostas'];
        $usuarioId  = $_SESSION['user_id'] ?? null;

        if ($usuarioId) {
            // --- MODO LOGADO: salva no banco ---
            try {
                $idsValidos = $this->perguntaModel->buscarIdsValidos();
                $this->respostaModel->salvarParaUsuario((int)$usuarioId, $respostas, $idsValidos);
            } catch (\Exception $e) {
                die("Erro ao salvar no banco: tente novamente mais tarde.");
            }
        } else {
            // --- MODO VISITANTE: salva na sessão ---
            $_SESSION['temp_respostas'] = $respostas;
        }

        header("Location: /resultado");
        exit;
    }
}
