<?php

/**
 * Front Controller — public/index.php
 * 
 * ÚNICO ponto de entrada da aplicação.
 * O Apache/Nginx deve apontar o DocumentRoot para esta pasta (public/).
 * Todos os arquivos fora de public/ ficam inacessíveis via URL.
 */

// Caminho raiz do projeto (um nível acima de public/)
define('BASE_PATH', dirname(__DIR__));

// Inicia sessão uma única vez aqui (substitui o session_start() espalhado)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ─────────────────────────────────────────────
// Autoloader — carrega classes automaticamente
// Ordem: config → models → controllers
// ─────────────────────────────────────────────
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

// ─────────────────────────────────────────────
// Roteador simples baseado na URI
// ─────────────────────────────────────────────
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri    = rtrim($uri, '/') ?: '/';
$method = $_SERVER['REQUEST_METHOD'];

// Mapa de rotas: URI => [Classe, método]
// POST em /checklist vai para processar(); GET vai para index()
$rotas = [
    '/'          => ['HomeController',      'index'],
    '/checklist' => ['ChecklistController', $method === 'POST' ? 'processar' : 'index'],
    '/resultado' => ['ResultadoController', 'index'],
    '/login'     => ['AuthController',      'login'],
    '/cadastro'  => ['AuthController',      'cadastro'],
    '/logout'    => ['AuthController',      'logout'],
    '/materiais' => ['MateriaisController', 'index'],
    '/sobre'     => ['PaginaController',    'sobre'],
    '/contato'   => ['PaginaController',    'contato'],
    '/privacidade' => ['PaginaController',  'privacidade'],
];

if (array_key_exists($uri, $rotas)) {
    [$classe, $metodo] = $rotas[$uri];
    (new $classe())->$metodo();
} else {
    // Rota não encontrada
    http_response_code(404);
    require BASE_PATH . '/app/views/404.php';
}
