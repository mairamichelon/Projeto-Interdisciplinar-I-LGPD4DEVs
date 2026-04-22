<?php

/**
 * Controller: ResultadoController
 * 
 * Calcula o score de conformidade e prepara os dados para a View.
 * Toda a lógica que estava no topo de resultado.php vem para cá.
 */
class ResultadoController
{
    private Pergunta $perguntaModel;

    public function __construct()
    {
        $this->perguntaModel = new Pergunta();
    }

    public function index(): void
    {
        $usuarioId         = $_SESSION['user_id'] ?? null;
        $dadosParaCalculo  = [];
        $isGuest           = false;

        try {
            if ($usuarioId) {
                // Usuário logado → busca do banco
                $dadosParaCalculo = $this->perguntaModel->buscarComRespostasEMateriais((int)$usuarioId);
            } elseif (isset($_SESSION['temp_respostas'])) {
                // Visitante → busca da sessão temporária
                $isGuest = true;
                foreach ($_SESSION['temp_respostas'] as $pId => $res) {
                    $info = $this->perguntaModel->buscarPorIdComMateriais((int)$pId);
                    if ($info) {
                        $dadosParaCalculo[] = array_merge($info, ['resposta' => $res]);
                    }
                }
            }

            // Cálculo do score — lógica idêntica ao resultado.php original
            $totalPontos = 0;
            $obtidos     = 0;
            $falhas      = [];

            foreach ($dadosParaCalculo as $item) {
                $totalPontos += (int)$item['peso'];
                if ($item['resposta'] == 1) {
                    $obtidos += (int)$item['peso'];
                } else {
                    $falhas[] = $item;
                }
            }

            $percentual = ($totalPontos > 0)
                ? round(($obtidos / $totalPontos) * 100)
                : null;

        } catch (\PDOException $e) {
            error_log('[LGPD4DEVS] Erro no resultado: ' . $e->getMessage());
            die("<div class='container' style='padding:50px;'>Erro ao processar o diagnóstico. Tente novamente mais tarde.</div>");
        }

        // Passa todas as variáveis necessárias para a View
        require BASE_PATH . '/app/views/resultado/index.php';
    }
}
