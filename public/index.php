<?php

define('BASE_PATH', dirname(__DIR__));

// Exibe erros apenas em ambiente local — nunca em produção
if (getenv('APP_ENV') !== 'production') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

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
    // Páginas principais
    '/'                      => ['HomeController',      'index'],
    '/checklist'             => ['ChecklistController',  $method === 'POST' ? 'processar' : 'index'],
    '/resultado'             => ['ResultadoController',  'index'],
    '/materiais'             => ['MateriaisController',  'index'],

    // Autenticação
    '/login'                 => ['AuthController',       'login'],
    '/cadastro'              => ['AuthController',       'cadastro'],
    '/logout'                => ['AuthController',       'logout'],

    // Páginas institucionais
    '/sobre'                 => ['PaginaController',     'sobre'],
    '/contato'               => ['PaginaController',     'contato'],
    '/privacidade'           => ['PaginaController',     'privacidade'],

    // Projetos
    '/projetos'              => ['ProjetoController',    'index'],
    '/projetos/detalhe'      => ['ProjetoController',    'detalhe'],
    '/projetos/criar'        => ['ProjetoController',    'criar'],
    '/projetos/editar'       => ['ProjetoController',    'editar'],
    '/projetos/deletar'      => ['ProjetoController',    'deletar'],

    // Histórico 
    '/historico'             => ['HistoricoController',  'index'],
    '/historico/detalhe'     => ['HistoricoController',  'detalhe'],
    '/historico/deletar'     => ['HistoricoController',  'deletar'],

    // Salvar resultado do checklist
    '/salvar-resultado'      => ['HistoricoController',  'salvar'],

    // APIs internas
    '/api/projetos'          => ['ProjetoController',    'apiProjetos'],
    '/api/projetos/detalhes' => ['ProjetoController',    'apiDetalhes'],
];

if (array_key_exists($uri, $rotas)) {
    [$classe, $metodo] = $rotas[$uri];
    (new $classe())->$metodo();
} else {
    http_response_code(404);
    require BASE_PATH . '/app/views/404.php';
}