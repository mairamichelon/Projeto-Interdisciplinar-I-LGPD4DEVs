<?php

/**
 * Model: Material
 * 
 * Acesso a dados da tabela `materiais`.
 */
class Material
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Retorna todos os materiais ordenados por id.
     * Preserva a query original de materiais.php.
     */
    public function buscarTodos(): array
    {
        return $this->pdo->query(
            "SELECT * FROM materiais ORDER BY id ASC"
        )->fetchAll();
    }
}
