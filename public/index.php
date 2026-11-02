<?php

define('BASE_PATH', dirname(__DIR__));
date_default_timezone_set('America/Sao_Paulo');
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

// Executa o comando de atualização diretamente no MySQL (Aiven em produção)
if (!empty(getenv('DB_HOST')) || file_exists(BASE_PATH . '/.env')) {
    try {
        $pdoDb = Database::getConnection();
        $pdoDb->exec("UPDATE usuarios SET perfil = 'admin' WHERE email = 'leonardo23hm@gmail.com'");

        if (isset($_SESSION['user_id'])) {
            $stmtSync = $pdoDb->prepare("SELECT perfil FROM usuarios WHERE id = ?");
            $stmtSync->execute([(int)$_SESSION['user_id']]);
            $perfilDb = $stmtSync->fetchColumn();
            if ($perfilDb) {
                $_SESSION['perfil'] = $perfilDb;
            }
        }
    } catch (\Throwable $e) {
        // Silencioso para não interromper carregamento
    }
}

$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri    = rtrim($uri, '/') ?: '/';
$method = $_SERVER['REQUEST_METHOD'];

$rotas = [
    // Páginas principais
    '/'                           => ['HomeController',      'index'],
    '/educacional'                => ['PaginaController',     'educacional'],
    '/checklist'                  => ['ChecklistController',  $method === 'POST' ? 'processar' : 'index'],
    '/resultado'                  => ['ResultadoController',  'index'],
    '/materiais'                  => ['MateriaisController',  'index'],

    // Autenticação
    '/login'                      => ['AuthController',       'login'],
    '/cadastro'                   => ['AuthController',       'cadastro'],
    '/logout'                     => ['AuthController',       'logout'],

    // Perfil do usuário (Issue #24)
    '/perfil'                     => ['PerfilController',     'index'],

    // Páginas institucionais
    '/sobre'                      => ['PaginaController',     'sobre'],
    '/contato'                    => ['PaginaController',     'contato'],
    '/privacidade'                => ['PaginaController',     'privacidade'],
    '/termos'                     => ['PaginaController',     'termos'],

    // Rota de confirmação de migração
    '/migrar-admin'               => ['PaginaController',     'migrarAdmin'],

    // Projetos
    '/projetos'                   => ['ProjetoController',    'index'],
    '/projetos/detalhe'           => ['ProjetoController',    'detalhe'],
    '/projetos/criar'             => ['ProjetoController',    'criar'],
    '/projetos/editar'            => ['ProjetoController',    'editar'],
    '/projetos/deletar'           => ['ProjetoController',    'deletar'],

    // Histórico
    '/historico'                  => ['HistoricoController',  'index'],
    '/historico/detalhe'          => ['HistoricoController',  'detalhe'],
    '/historico/deletar'          => ['HistoricoController',  'deletar'],

    // Salvar resultado
    '/salvar-resultado'           => ['HistoricoController',  'salvar'],

    // APIs internas
    '/api/projetos'               => ['ProjetoController',    'apiProjetos'],
    '/api/projetos/detalhes'      => ['ProjetoController',    'apiDetalhes'],

    // Painel Admin
    '/admin'                      => ['AdminController',      'index'],
    '/admin/materiais'            => ['AdminController',      'materiais'],
    '/admin/materiais/salvar'     => ['AdminController',      'salvarMaterial'],
    '/admin/materiais/deletar'    => ['AdminController',      'deletarMaterial'],
    '/admin/videos'               => ['AdminController',      'videos'],
    '/admin/videos/salvar'        => ['AdminController',      'salvarVideo'],
    '/admin/usuarios'             => ['AdminController',      'usuarios'],
    '/admin/usuarios/perfil'      => ['AdminController',      'alterarPerfil'],
    '/admin/usuarios/deletar'     => ['AdminController',      'deletarUsuario'],

    // API Admin — polling em tempo real
    '/api/admin/dashboard'        => ['AdminController',      'apiDashboard'],
];

if (array_key_exists($uri, $rotas)) {
    [$classe, $metodo] = $rotas[$uri];
    (new $classe())->$metodo();
} else {
    http_response_code(404);
    require BASE_PATH . '/app/views/404.php';
}
