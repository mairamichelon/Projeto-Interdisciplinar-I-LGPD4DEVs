<?php

class Pergunta
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function buscarTodas(): array
    {
        $sql = "SELECT p.id, p.texto, c.nome AS categoria
                FROM perguntas p
                JOIN categorias c ON p.categoria_id = c.id
                ORDER BY p.id ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function buscarIdsValidos(): array
    {
        $rows = $this->pdo->query("SELECT id FROM perguntas")->fetchAll();
        return array_column($rows, 'id');
    }

    /**
     * Busca respostas do usuário com dados completos da pergunta.
     * Inclui id, texto e categoria para uso no histórico.
     */
    public function buscarComRespostasEMateriais(int $usuarioId): array
    {
        $sql = "SELECT p.id, p.texto, p.peso, c.nome AS categoria, r.resposta,
                       GROUP_CONCAT(m.titulo SEPARATOR '||')             AS materiais_titulos,
                       GROUP_CONCAT(m.url_referencia SEPARATOR '||')     AS materiais_urls,
                       GROUP_CONCAT(m.conteudo_detalhado SEPARATOR '||') AS materiais_conteudos
                FROM respostas r
                JOIN perguntas p ON r.pergunta_id = p.id
                JOIN categorias c ON p.categoria_id = c.id
                LEFT JOIN pergunta_material pm ON p.id = pm.pergunta_id
                LEFT JOIN materiais m ON pm.material_id = m.id
                WHERE r.usuario_id = ?
                GROUP BY p.id, p.texto, p.peso, c.nome, r.resposta";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
    }

    public function buscarPorIdComMateriais(int $perguntaId): array|false
    {
        $sql = "SELECT p.texto, p.peso,
                       GROUP_CONCAT(m.titulo SEPARATOR '||')             AS materiais_titulos,
                       GROUP_CONCAT(m.url_referencia SEPARATOR '||')     AS materiais_urls,
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