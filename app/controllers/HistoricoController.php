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
    private Projeto   $projetoModel;

    public function __construct()
    {
        $this->model         = new Historico();
        $this->perguntaModel = new Pergunta();
        $this->projetoModel  = new Projeto();
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

        $tokenSessao = $_SESSION['csrf_token'] ?? '';
        $tokenPost   = $_POST['csrf_token']    ?? '';
        if (empty($tokenSessao) || !hash_equals($tokenSessao, $tokenPost)) {
            $this->jsonErro("Requisição inválida.");
            return;
        }

        $usuarioId = (int) $_SESSION['user_id'];

        // Projeto: existente ou novo
        $projetoId  = (int) ($_POST['projeto_id'] ?? 0);
        $nomeProjeto = trim($_POST['nome_projeto']    ?? '');
        $descricao   = trim($_POST['descricao']       ?? '');
        $publicoAlvo = trim($_POST['publico_alvo']    ?? 'ambos');
        $status      = trim($_POST['status']          ?? 'em_desenvolvimento');

        try {
            // Se não veio projeto_id, cria um novo projeto
            if ($projetoId === 0 && !empty($nomeProjeto)) {
                $projetoId = $this->projetoModel->criar(
                    $usuarioId, $nomeProjeto, $descricao, $publicoAlvo, $status
                );
            }

            // Busca respostas do banco para este usuário
            $dados = $this->perguntaModel->buscarComRespostasEMateriais($usuarioId);

            if (empty($dados)) {
                $this->jsonErro("Nenhuma resposta encontrada para salvar.");
                return;
            }

            // Calcula métricas
            $totalPontos     = 0;
            $pontosObtidos   = 0;
            $totalPerguntas  = count($dados);
            $perguntasOk     = 0;
            $perguntasFalha  = 0;
            $respostasDetalhe = [];

            foreach ($dados as $item) {
                $totalPontos += (int) $item['peso'];

                if ($item['resposta'] == 1) {
                    $pontosObtidos += (int) $item['peso'];
                    $perguntasOk++;
                } else {
                    $perguntasFalha++;
                }

                $respostasDetalhe[] = [
                    'pergunta_id'    => $item['id']        ?? 0,
                    'pergunta_texto' => $item['texto']     ?? '',
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
                $respostasDetalhe,
                $projetoId ?: null
            );

            header('Content-Type: application/json');
            echo json_encode([
                'sucesso'      => true,
                'mensagem'     => "Diagnóstico de {$percentual}% salvo com sucesso!",
                'historico_id' => $historicoId,
                'projeto_id'   => $projetoId,
            ]);

        } catch (\Exception $e) {
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

        $historicoId = (int) ($_POST['historico_id'] ?? 0);
        $projetoId   = (int) ($_POST['projeto_id']   ?? 0);
        $usuarioId   = (int) $_SESSION['user_id'];

        $this->model->deletar($historicoId, $usuarioId);

        // Volta para o detalhe do projeto se veio de lá
        if ($projetoId) {
            header("Location: /projetos/detalhe?id={$projetoId}");
        } else {
            header("Location: /projetos");
        }
        exit;
    }

    // -------------------------------------------------------------------------
    // Helpers
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