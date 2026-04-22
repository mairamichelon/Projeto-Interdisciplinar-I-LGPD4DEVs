<?php

/**
 * Database
 *
 * Singleton de conexão PDO com suporte a dois ambientes:
 *
 *  - LOCAL (Laragon): lê credenciais do arquivo .env na raiz do projeto.
 *                     SSL desligado pois o MySQL local não usa.
 *
 *  - PRODUÇÃO (Render + Aiven): as variáveis de ambiente já vêm
 *                     configuradas no painel do Render.
 *                     SSL ligado pois o Aiven exige conexão segura.
 *
 * O arquivo .env NUNCA deve ser enviado ao GitHub (.gitignore).
 */
class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            // Em local, carrega o .env. Em produção (Render), as vars
            // já estão no ambiente e este método simplesmente não faz nada.
            self::carregarEnv();

            $host = getenv('DB_HOST') ?: '127.0.0.1';
            $port = getenv('DB_PORT') ?: '3306';
            $db   = getenv('DB_NAME') ?: 'db_lgpd4devs';
            $user = getenv('DB_USER') ?: 'root';
            $pass = getenv('DB_PASS') ?: '';

            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            // SSL ativo apenas em produção — Aiven exige; Laragon local não usa
            $ambiente = getenv('APP_ENV') ?: 'local';
            if ($ambiente === 'production') {
                $options[PDO::MYSQL_ATTR_SSL_CA]                 = true;
                $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
            }

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (\PDOException $e) {
                error_log('[LGPD4DEVS] Falha na conexão: ' . $e->getMessage());
                die("Erro interno ao conectar com o banco de dados. Tente novamente mais tarde.");
            }
        }

        return self::$instance;
    }

    /**
     * Lê o .env da raiz do projeto e registra as variáveis via putenv().
     * Só executa se o arquivo existir (desenvolvimento local).
     * Não sobrescreve variáveis que o Render já injetou no ambiente.
     */
    private static function carregarEnv(): void
    {
        $arquivo = BASE_PATH . '/.env';

        if (!file_exists($arquivo)) {
            return;
        }

        $linhas = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($linhas as $linha) {
            if (str_starts_with(trim($linha), '#')) {
                continue;
            }

            if (!str_contains($linha, '=')) {
                continue;
            }

            [$chave, $valor] = explode('=', $linha, 2);
            $chave = trim($chave);
            $valor = trim($valor);

            // Só define se a variável ainda não existir no ambiente
            if ($chave !== '' && getenv($chave) === false) {
                putenv("$chave=$valor");
            }
        }
    }

    private function __construct() {}
    private function __clone() {}
}
