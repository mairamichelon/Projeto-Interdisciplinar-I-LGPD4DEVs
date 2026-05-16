<?php

define('BASE_PATH', dirname(__DIR__));

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

spl_autoload_register(function (string $class): void {
    $caminhos = [
        BASE_PATH . "/config/{$class}.php",
        BASE_PATH . "/app/models/{$class}.php",
        BASE_PATH . "/app/controllers/{$class}.php",
    ];
    foreach ($caminhos as $caminho) {
        if (file_exists($caminho)) {
            require_once $caminho;
            return;
        }
    }
});

$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri    = rtrim($uri, '/') ?: '/';
$method = $_SERVER['REQUEST_METHOD'];

$rotas = [
    '/'                   => ['HomeController',     'index'],
    '/checklist'          => ['ChecklistController', $method === 'POST' ? 'processar' : 'index'],
    '/resultado'          => ['ResultadoController', 'index'],
    '/login'              => ['AuthController',      'login'],
    '/cadastro'           => ['AuthController',      'cadastro'],
    '/logout'             => ['AuthController',      'logout'],
    '/materiais'          => ['MateriaisController', 'index'],
    '/sobre'              => ['PaginaController',    'sobre'],
    '/contato'            => ['PaginaController',    'contato'],
    '/privacidade'        => ['PaginaController',    'privacidade'],
    '/projetos'           => ['ProjetoController',   'index'],
    '/projetos/detalhe'   => ['ProjetoController',   'detalhe'],
    '/projetos/criar'     => ['ProjetoController',   'criar'],
    '/projetos/editar'    => ['ProjetoController',   'editar'],
    '/projetos/deletar'   => ['ProjetoController',   'deletar'],
    '/historico/detalhe'  => ['HistoricoController', 'detalhe'],
    '/historico/deletar'  => ['HistoricoController', 'deletar'],
    '/salvar-resultado'   => ['HistoricoController', 'salvar'],
];

if (array_key_exists($uri, $rotas)) {
    [$classe, $metodo] = $rotas[$uri];
    (new $classe())->$metodo();
} else {
    http_response_code(404);
    require BASE_PATH . '/app/views/404.php';
}