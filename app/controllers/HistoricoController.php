<?php

/**
 * Controller: HistoricoController
 *
 * Gerencia o salvamento e consulta do histórico de diagnósticos.
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
    // GET /historico — lista todos os diagnósticos salvos do usuário
    // -------------------------------------------------------------------------

    public function index(): void
    {
        $this->exigirLogin();

        $usuarioId  = (int) $_SESSION['user_id'];
        $historicos = $this->model->buscarPorUsuario($usuarioId);

        require BASE_PATH . '/app/views/historico/index.php';
    }

    // -------------------------------------------------------------------------
    // GET /historico/detalhe?id=X — exibe o detalhe de um diagnóstico
    // -------------------------------------------------------------------------

    public function detalhe(): void
    {
        $this->exigirLogin();

        $historicoId = (int) ($_GET['id'] ?? 0);
        $usuarioId   = (int) $_SESSION['user_id'];

        if ($historicoId === 0) {
            header("Location: /historico");
            exit;
        }

        $dados = $this->model->buscarDetalhePorId($historicoId, $usuarioId);

        if (!$dados) {
            // Diagnóstico não encontrado ou não pertence ao usuário
            http_response_code(404);
            require BASE_PATH . '/app/views/404.php';
            return;
        }

        $cabecalho = $dados['cabecalho'];
        $respostas = $dados['respostas'];

        require BASE_PATH . '/app/views/historico/detalhe.php';
    }

    // -------------------------------------------------------------------------
    // POST /salvar-resultado — salva o diagnóstico atual no histórico
    // -------------------------------------------------------------------------

    public function salvar(): void
    {
        // Só aceita POST e usuário logado
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /historico");
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

        try {
            // Busca as respostas e dados do banco para este usuário
            $dados = $this->perguntaModel->buscarComRespostasEMateriais($usuarioId);

            if (empty($dados)) {
                $this->jsonErro("Nenhuma resposta encontrada para salvar.");
                return;
            }

            // Calcula métricas
            $totalPontos    = 0;
            $pontosObtidos  = 0;
            $totalPerguntas = count($dados);
            $perguntasOk    = 0;
            $perguntasFalha = 0;
            $respostasDetalhe = [];

            foreach ($dados as $item) {
                $totalPontos += (int) $item['peso'];

                if ($item['resposta'] == 1) {
                    $pontosObtidos += (int) $item['peso'];
                    $perguntasOk++;
                } else {
                    $perguntasFalha++;
                }

                // Busca o ID e categoria da pergunta para salvar no histórico
                $respostasDetalhe[] = [
                    'pergunta_id'    => $item['id']        ?? 0,
                    'pergunta_texto' => $item['texto']     ?? $item['texto'] ?? '',
                    'pergunta_peso'  => (int) $item['peso'],
                    'categoria_nome' => $item['categoria'] ?? 'Geral',
                    'resposta'       => (int) $item['resposta'],
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
                $respostasDetalhe
            );

            // Retorna JSON de sucesso para o JavaScript da página
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso'     => true,
                'mensagem'    => "Diagnóstico de {$percentual}% salvo com sucesso!",
                'historico_id' => $historicoId,
            ]);

        } catch (\Exception $e) {
            $this->jsonErro("Erro ao salvar. Tente novamente.");
        }
    }

    // -------------------------------------------------------------------------
    // POST /historico/deletar — remove um diagnóstico do histórico
    // -------------------------------------------------------------------------

    public function deletar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /historico");
            exit;
        }

        $this->exigirLogin();

        $historicoId = (int) ($_POST['historico_id'] ?? 0);
        $usuarioId   = (int) $_SESSION['user_id'];

        $this->model->deletar($historicoId, $usuarioId);

        header("Location: /historico");
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

    private function jsonErro(string $mensagem): void
    {
        header('Content-Type: application/json');
        echo json_encode(['sucesso' => false, 'mensagem' => $mensagem]);
    }
}