<?php

/**
 * Model: Historico
 *
 * Gerencia o salvamento e consulta do histórico de diagnósticos.
 */
class Historico
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Salva um diagnóstico completo no histórico.
     * Insere o cabeçalho e depois cada resposta detalhada.
     * Tudo dentro de uma transação — ou salva tudo ou nada.
     */
    public function salvar(
        int   $usuarioId,
        int   $percentual,
        int   $totalPontos,
        int   $pontosObtidos,
        int   $totalPerguntas,
        int   $perguntasOk,
        int   $perguntasFalha,
        array $respostas
    ): int {
        $this->pdo->beginTransaction();

        try {
            // 1 — Insere o cabeçalho do diagnóstico
            $stmt = $this->pdo->prepare("
                INSERT INTO historico_diagnosticos
                    (usuario_id, percentual, total_pontos, pontos_obtidos,
                     total_perguntas, perguntas_ok, perguntas_falha)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $usuarioId,
                $percentual,
                $totalPontos,
                $pontosObtidos,
                $totalPerguntas,
                $perguntasOk,
                $perguntasFalha,
            ]);

            $historicoId = (int) $this->pdo->lastInsertId();

            // 2 — Insere cada resposta detalhada
            $stmtResp = $this->pdo->prepare("
                INSERT INTO historico_respostas
                    (historico_id, pergunta_id, pergunta_texto, pergunta_peso, categoria_nome, resposta)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            foreach ($respostas as $item) {
                $stmtResp->execute([
                    $historicoId,
                    $item['pergunta_id'],
                    $item['pergunta_texto'],
                    $item['pergunta_peso'],
                    $item['categoria_nome'],
                    $item['resposta'],
                ]);
            }

            $this->pdo->commit();
            return $historicoId;

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            error_log('[LGPD4DEVS] Erro ao salvar histórico: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Retorna todos os diagnósticos salvos de um usuário,
     * ordenados do mais recente para o mais antigo.
     */
    public function buscarPorUsuario(int $usuarioId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, percentual, total_pontos, pontos_obtidos,
                   total_perguntas, perguntas_ok, perguntas_falha, data_salvo
            FROM historico_diagnosticos
            WHERE usuario_id = ?
            ORDER BY data_salvo DESC
        ");

        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
    }

    /**
     * Retorna o detalhe completo de um diagnóstico específico,
     * verificando se pertence ao usuário (segurança).
     */
    public function buscarDetalhePorId(int $historicoId, int $usuarioId): array|false
    {
        // Verifica se o diagnóstico pertence ao usuário
        $stmt = $this->pdo->prepare("
            SELECT * FROM historico_diagnosticos
            WHERE id = ? AND usuario_id = ?
        ");
        $stmt->execute([$historicoId, $usuarioId]);
        $cabecalho = $stmt->fetch();

        if (!$cabecalho) {
            return false;
        }

        // Busca as respostas detalhadas
        $stmtResp = $this->pdo->prepare("
            SELECT * FROM historico_respostas
            WHERE historico_id = ?
            ORDER BY pergunta_id ASC
        ");
        $stmtResp->execute([$historicoId]);
        $respostas = $stmtResp->fetchAll();

        return [
            'cabecalho' => $cabecalho,
            'respostas' => $respostas,
        ];
    }

    /**
     * Remove um diagnóstico do histórico.
     * Verifica se pertence ao usuário antes de deletar.
     */
    public function deletar(int $historicoId, int $usuarioId): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM historico_diagnosticos
            WHERE id = ? AND usuario_id = ?
        ");
        $stmt->execute([$historicoId, $usuarioId]);
        return $stmt->rowCount() > 0;
    }
}