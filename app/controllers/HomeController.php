<?php

/**
 * Controller: HomeController
 * 
 * Página inicial — não precisa de dados do banco,
 * apenas renderiza a View com o estado da sessão disponível.
 */
class HomeController
{
    public function index(): void
    {
        require BASE_PATH . '/app/views/home/index.php';
    }
}
