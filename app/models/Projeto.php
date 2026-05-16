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
     */
    public function buscarPorUsuario(int $usuarioId): array
    {
        $sql = "SELECT p.*,
                       hd.percentual      AS ultimo_percentual,
                       hd.data_salvo      AS ultima_data,
                       COUNT(hd2.id)      AS total_diagnosticos
                FROM projetos p
                LEFT JOIN historico_diagnosticos hd ON hd.id = (
                    SELECT id FROM historico_diagnosticos
                    WHERE projeto_id = p.id
                    ORDER BY data_salvo DESC
                    LIMIT 1
                )
                LEFT JOIN historico_diagnosticos hd2 ON hd2.projeto_id = p.id
                WHERE p.usuario_id = ?
                GROUP BY p.id
                ORDER BY p.data_criacao DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
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
     * Cria um novo projeto.
     * Retorna o ID do projeto criado.
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
     * Remove um projeto e todos os seus diagnósticos.
     */
    public function deletar(int $projetoId, int $usuarioId): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM projetos WHERE id = ? AND usuario_id = ?
        ");
        $stmt->execute([$projetoId, $usuarioId]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Retorna labels amigáveis para público-alvo e status.
     */
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