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
        // Busca os projetos
        $stmt = $this->pdo->prepare("
            SELECT p.*,
                   COUNT(hd.id) AS total_diagnosticos
            FROM projetos p
            LEFT JOIN historico_diagnosticos hd ON hd.projeto_id = p.id
            WHERE p.usuario_id = ?
            GROUP BY p.id, p.usuario_id, p.nome, p.descricao, p.publico_alvo, p.status, p.data_criacao
            ORDER BY p.data_criacao DESC
        ");
        $stmt->execute([$usuarioId]);
        $projetos = $stmt->fetchAll();

        // Para cada projeto, busca o último diagnóstico separadamente
        foreach ($projetos as &$projeto) {
            $stmtUltimo = $this->pdo->prepare("
                SELECT percentual, data_salvo
                FROM historico_diagnosticos
                WHERE projeto_id = ?
                ORDER BY data_salvo DESC
                LIMIT 1
            ");
            $stmtUltimo->execute([$projeto['id']]);
            $ultimo = $stmtUltimo->fetch();

            $projeto['ultimo_percentual'] = $ultimo ? $ultimo['percentual'] : null;
            $projeto['ultima_data']       = $ultimo ? $ultimo['data_salvo']  : null;
        }

        return $projetos;
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
     * Labels e cores auxiliares.
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