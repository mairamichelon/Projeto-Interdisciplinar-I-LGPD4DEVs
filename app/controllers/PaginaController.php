<?php

/**
 * Controller: PaginaController
 * 
 * Serve as páginas institucionais e educacionais que não precisam de dados do banco:
 * Sobre, Contato, Privacidade, Educacional e Termos de Uso.
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

    public function educacional(): void
    {
        require BASE_PATH . '/app/views/paginas/educacional.php';
    }

    public function termos(): void
    {
        require BASE_PATH . '/app/views/paginas/termos.php';
    }
}
