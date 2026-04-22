<?php

/**
 * Model: Pergunta
 * 
 * Acesso a dados das tabelas `perguntas`, `categorias` e relacionadas.
 */
class Pergunta
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Retorna todas as perguntas com o nome da categoria.
     * Preserva o ORDER BY id ASC original do checklist.php.
     */
    public function buscarTodas(): array
    {
        $sql = "SELECT p.id, p.texto, c.nome AS categoria
                FROM perguntas p
                JOIN categorias c ON p.categoria_id = c.id
                ORDER BY p.id ASC";

        return $this->pdo->query($sql)->fetchAll();
    }

    /**
     * Retorna os IDs de todas as perguntas válidas.
     * Usado para validar os IDs recebidos via POST antes de inserir respostas.
     */
    public function buscarIdsValidos(): array
    {
        $rows = $this->pdo->query("SELECT id FROM perguntas")->fetchAll();
        return array_column($rows, 'id');
    }

    /**
     * Retorna perguntas com respostas e materiais de um usuário logado.
     * Mantém exatamente o SQL original de resultado.php (modo logado).
     */
    public function buscarComRespostasEMateriais(int $usuarioId): array
    {
        $sql = "SELECT r.resposta, p.texto, p.peso,
                       GROUP_CONCAT(m.titulo SEPARATOR '||')            AS materiais_titulos,
                       GROUP_CONCAT(m.url_referencia SEPARATOR '||')    AS materiais_urls,
                       GROUP_CONCAT(m.conteudo_detalhado SEPARATOR '||') AS materiais_conteudos
                FROM respostas r
                JOIN perguntas p ON r.pergunta_id = p.id
                LEFT JOIN pergunta_material pm ON p.id = pm.pergunta_id
                LEFT JOIN materiais m ON pm.material_id = m.id
                WHERE r.usuario_id = ?
                GROUP BY p.id, r.resposta, p.texto, p.peso";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
    }

    /**
     * Retorna dados de uma pergunta específica com seus materiais.
     * Usado no modo visitante (sessão temporária) em resultado.php.
     */
    public function buscarPorIdComMateriais(int $perguntaId): array|false
    {
        $sql = "SELECT p.texto, p.peso,
                       GROUP_CONCAT(m.titulo SEPARATOR '||')            AS materiais_titulos,
                       GROUP_CONCAT(m.url_referencia SEPARATOR '||')    AS materiais_urls,
                       GROUP_CONCAT(m.conteudo_detalhado SEPARATOR '||') AS materiais_conteudos
                FROM perguntas p
                LEFT JOIN pergunta_material pm ON p.id = pm.pergunta_id
                LEFT JOIN materiais m ON pm.material_id = m.id
                WHERE p.id = ?
                GROUP BY p.id, p.texto, p.peso";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$perguntaId]);
        return $stmt->fetch();
    }
}
