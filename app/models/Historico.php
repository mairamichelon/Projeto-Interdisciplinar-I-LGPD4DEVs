<?php

/**
 * Model: Historico
 *
 * Gerencia o salvamento e consulta do histórico de diagnósticos.
 * Inclui suporte a filtros por projeto/data e paginação (Issue #26).
 */
class Historico
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Salva um diagnóstico completo no histórico vinculado a um projeto.
     */
    public function salvar(
        int   $usuarioId,
        int   $percentual,
        int   $totalPontos,
        int   $pontosObtidos,
        int   $totalPerguntas,
        int   $perguntasOk,
        int   $perguntasFalha,
        array $respostas,
        ?int  $projetoId = null
    ): int {
        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO historico_diagnosticos
                    (usuario_id, projeto_id, percentual, total_pontos, pontos_obtidos,
                     total_perguntas, perguntas_ok, perguntas_falha)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $usuarioId,
                $projetoId,
                $percentual,
                $totalPontos,
                $pontosObtidos,
                $totalPerguntas,
                $perguntasOk,
                $perguntasFalha,
            ]);

            $historicoId = (int) $this->pdo->lastInsertId();

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
     * Conta diagnósticos de um usuário aplicando filtros.
     * Usado para calcular a paginação (Issue #26).
     */
    public function contarPorUsuario(int $usuarioId, array $filtros = []): int
    {
        [$where, $params] = $this->montarWhere($usuarioId, $filtros);

        $sql = "
            SELECT COUNT(hd.id)
            FROM historico_diagnosticos hd
            LEFT JOIN projetos p ON hd.projeto_id = p.id
            WHERE {$where}
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Retorna diagnósticos paginados e filtrados de um usuário.
     * Substitui buscarPorUsuario() para suportar filtros e paginação (Issue #26).
     */
    public function buscarPorUsuario(
        int   $usuarioId,
        array $filtros  = [],
        int   $limite   = 10,
        int   $offset   = 0
    ): array {
        [$where, $params] = $this->montarWhere($usuarioId, $filtros);

        $sql = "
            SELECT hd.*, p.nome AS projeto_nome
            FROM historico_diagnosticos hd
            LEFT JOIN projetos p ON hd.projeto_id = p.id
            WHERE {$where}
            ORDER BY hd.data_salvo DESC
            LIMIT {$limite} OFFSET {$offset}
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Retorna resumo estatístico geral (sem filtros) para os cards do topo.
     */
    public function resumoPorUsuario(int $usuarioId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                COUNT(id)              AS total,
                ROUND(AVG(percentual)) AS media,
                MAX(percentual)        AS melhor,
                (
                    SELECT hd2.percentual
                    FROM historico_diagnosticos hd2
                    WHERE hd2.usuario_id = ?
                    ORDER BY hd2.data_salvo DESC
                    LIMIT 1
                )                      AS ultimo_percentual
            FROM historico_diagnosticos
            WHERE usuario_id = ?
        ");
        $stmt->execute([$usuarioId, $usuarioId]);
        $row = $stmt->fetch();

        if (!$row || $row['total'] == 0) {
            return null;
        }

        return [
            'total'            => (int) $row['total'],
            'media'            => (int) round($row['media']),
            'melhor'           => (int) $row['melhor'],
            'ultimo_percentual'=> (int) $row['ultimo_percentual'],
        ];
    }

    /**
     * Retorna diagnósticos de um projeto específico.
     * Mantida para compatibilidade com ProjetoController e detalhe.php.
     */
    public function buscarPorProjeto(int $projetoId, int $usuarioId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT hd.*
            FROM historico_diagnosticos hd
            WHERE hd.projeto_id = ? AND hd.usuario_id = ?
            ORDER BY hd.data_salvo DESC
        ");
        $stmt->execute([$projetoId, $usuarioId]);
        return $stmt->fetchAll();
    }

    /**
     * Retorna o detalhe completo de um diagnóstico.
     */
    public function buscarDetalhePorId(int $historicoId, int $usuarioId): array|false
    {
        $stmt = $this->pdo->prepare("
            SELECT hd.*, p.nome AS projeto_nome
            FROM historico_diagnosticos hd
            LEFT JOIN projetos p ON hd.projeto_id = p.id
            WHERE hd.id = ? AND hd.usuario_id = ?
        ");
        $stmt->execute([$historicoId, $usuarioId]);
        $cabecalho = $stmt->fetch();

        if (!$cabecalho) return false;

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

    // -------------------------------------------------------------------------
    // Helpers privados
    // -------------------------------------------------------------------------

    /**
     * Monta a cláusula WHERE dinâmica para filtros (Issue #26).
     * Retorna [$whereSql, $params].
     */
    private function montarWhere(int $usuarioId, array $filtros): array
    {
        $conditions = ['hd.usuario_id = ?'];
        $params     = [$usuarioId];

        if (!empty($filtros['projeto_id'])) {
            $conditions[] = 'hd.projeto_id = ?';
            $params[]     = (int) $filtros['projeto_id'];
        }

        if (!empty($filtros['data_inicio'])) {
            $conditions[] = 'DATE(hd.data_salvo) >= ?';
            $params[]     = $filtros['data_inicio'];
        }

        if (!empty($filtros['data_fim'])) {
            $conditions[] = 'DATE(hd.data_salvo) <= ?';
            $params[]     = $filtros['data_fim'];
        }

        return [implode(' AND ', $conditions), $params];
    }
}