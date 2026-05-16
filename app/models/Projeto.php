<?php

/**
 * Model: Projeto
 *
 * Gerencia os projetos dos usuários.
 */
class Projeto
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Retorna todos os projetos de um usuário com o último diagnóstico.
     *
     * Versão corrigida: uma única query com subquery correlacionada
     * no lugar do loop N+1 que existia antes.
     */
    public function buscarPorUsuario(int $usuarioId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                p.*,
                COUNT(hd.id)                                        AS total_diagnosticos,
                MAX(hd.data_salvo)                                  AS ultima_data,
                (
                    SELECT hd2.percentual
                    FROM historico_diagnosticos hd2
                    WHERE hd2.projeto_id = p.id
                    ORDER BY hd2.data_salvo DESC
                    LIMIT 1
                )                                                   AS ultimo_percentual
            FROM projetos p
            LEFT JOIN historico_diagnosticos hd ON hd.projeto_id = p.id
            WHERE p.usuario_id = ?
            GROUP BY
                p.id, p.usuario_id, p.nome, p.descricao,
                p.publico_alvo, p.status, p.data_criacao
            ORDER BY p.data_criacao DESC
        ");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
    }

    /**
     * Retorna uma lista de projetos com detalhes agregados para a API
     * usada no modal "Meus Projetos" da tela de resultado.
     */
    public function buscarDetalhesPorUsuario(int $usuarioId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                p.id,
                p.nome,
                COUNT(hd.id)                        AS diagnosticos_feitos,
                CAST(AVG(hd.percentual) AS UNSIGNED) AS media_percentual,
                MAX(hd.data_salvo)                   AS ultimo_diagnostico_data
            FROM projetos p
            LEFT JOIN historico_diagnosticos hd ON p.id = hd.projeto_id
            WHERE p.usuario_id = ?
            GROUP BY p.id, p.nome
            ORDER BY p.data_criacao DESC
        ");
        $stmt->execute([$usuarioId]);
        $resultados = $stmt->fetchAll();

        foreach ($resultados as &$r) {
            if ($r['ultimo_diagnostico_data']) {
                $r['ultimo_diagnostico_data'] = date('d/m/Y', strtotime($r['ultimo_diagnostico_data']));
            }
        }

        return $resultados;
    }

    /**
     * Busca um projeto pelo ID verificando se pertence ao usuário.
     */
    public function buscarPorId(int $projetoId, int $usuarioId): array|false
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM projetos
            WHERE id = ? AND usuario_id = ?
        ");
        $stmt->execute([$projetoId, $usuarioId]);
        return $stmt->fetch();
    }

    /**
     * Cria um novo projeto e retorna o ID gerado.
     */
    public function criar(
        int    $usuarioId,
        string $nome,
        string $descricao,
        string $publicoAlvo,
        string $status
    ): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO projetos (usuario_id, nome, descricao, publico_alvo, status)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$usuarioId, $nome, $descricao, $publicoAlvo, $status]);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Atualiza um projeto existente.
     */
    public function atualizar(
        int    $projetoId,
        int    $usuarioId,
        string $nome,
        string $descricao,
        string $publicoAlvo,
        string $status
    ): bool {
        $stmt = $this->pdo->prepare("
            UPDATE projetos
            SET nome = ?, descricao = ?, publico_alvo = ?, status = ?
            WHERE id = ? AND usuario_id = ?
        ");
        return $stmt->execute([$nome, $descricao, $publicoAlvo, $status, $projetoId, $usuarioId]);
    }

    /**
     * Remove um projeto e todos os seus diagnósticos vinculados.
     */
    public function deletar(int $projetoId, int $usuarioId): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM projetos WHERE id = ? AND usuario_id = ?
        ");
        $stmt->execute([$projetoId, $usuarioId]);
        return $stmt->rowCount() > 0;
    }

    // -------------------------------------------------------------------------
    // Helpers estáticos de apresentação
    // -------------------------------------------------------------------------

    public static function labelPublicoAlvo(string $valor): string
    {
        return match($valor) {
            'criancas'     => 'Crianças',
            'adolescentes' => 'Adolescentes',
            'ambos'        => 'Ambos',
            'outros'       => 'Outros',
            default        => $valor,
        };
    }

    public static function labelStatus(string $valor): string
    {
        return match($valor) {
            'em_desenvolvimento' => 'Em Desenvolvimento',
            'em_producao'        => 'Em Produção',
            'arquivado'          => 'Arquivado',
            default              => $valor,
        };
    }

    public static function corStatus(string $valor): string
    {
        return match($valor) {
            'em_desenvolvimento' => '#F59E0B',
            'em_producao'        => '#00CC66',
            'arquivado'          => '#94A3B8',
            default              => '#94A3B8',
        };
    }
}