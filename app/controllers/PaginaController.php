<?php

/**
 * Controller: PaginaController
 * 
 * Serve as páginas institucionais que não precisam de dados do banco:
 * Sobre, Contato e Privacidade.
 * 
 * Evita criar um controller por página para conteúdo estático.
 */
class PaginaController
{
    public function sobre(): void
    {
        require BASE_PATH . '/app/views/paginas/sobre.php';
    }

    public function contato(): void
    {
        require BASE_PATH . '/app/views/paginas/contato.php';
    }

    public function privacidade(): void
    {
        require BASE_PATH . '/app/views/paginas/privacidade.php';
    }
}
