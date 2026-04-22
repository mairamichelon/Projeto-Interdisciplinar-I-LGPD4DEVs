<?php

/**
 * Controller: MateriaisController
 * 
 * Gerencia a listagem dos materiais de apoio.
 */
class MateriaisController
{
    private Material $model;

    public function __construct()
    {
        $this->model = new Material();
    }

    public function index(): void
    {
        try {
            $materiais = $this->model->buscarTodos();
        } catch (\PDOException $e) {
            error_log('[LGPD4DEVS] Erro ao carregar materiais: ' . $e->getMessage());
            $materiais = [];
        }

        require BASE_PATH . '/app/views/materiais/index.php';
    }
}
