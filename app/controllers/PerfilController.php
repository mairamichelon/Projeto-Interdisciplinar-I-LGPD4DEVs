<?php

/**
 * Controller: PerfilController
 *
 * Exibe a página de perfil do usuário logado com suas informações
 * e histórico de uso da plataforma.
 */
class PerfilController
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function index(): void
    {
        $this->exigirLogin();

        $usuarioId = (int) $_SESSION['user_id'];

        // Busca dados do usuário
        $stmt = $this->pdo->prepare("
            SELECT id, nome, email, perfil, data_cadastro
            FROM usuarios
            WHERE id = ?
        ");
        $stmt->execute([$usuarioId]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            header("Location: /logout");
            exit;
        }

        // Estatísticas agregadas
        $stmt = $this->pdo->prepare("
            SELECT
                COUNT(DISTINCT p.id)                              AS total_projetos,
                COUNT(DISTINCT hd.id)                             AS total_diagnosticos,
                CAST(ROUND(AVG(hd.percentual)) AS UNSIGNED)       AS media_conformidade,
                MAX(hd.percentual)                                AS melhor_resultado,
                (
                    SELECT hd2.percentual
                    FROM historico_diagnosticos hd2
                    WHERE hd2.usuario_id = ?
                    ORDER BY hd2.data_salvo DESC
                    LIMIT 1
                )                                                 AS ultimo_resultado,
                (
                    SELECT hd3.data_salvo
                    FROM historico_diagnosticos hd3
                    WHERE hd3.usuario_id = ?
                    ORDER BY hd3.data_salvo DESC
                    LIMIT 1
                )                                                 AS ultima_data
            FROM usuarios u
            LEFT JOIN projetos p               ON p.usuario_id  = u.id
            LEFT JOIN historico_diagnosticos hd ON hd.usuario_id = u.id
            WHERE u.id = ?
        ");
        $stmt->execute([$usuarioId, $usuarioId, $usuarioId]);
        $stats = $stmt->fetch();

        // Últimos 5 diagnósticos
        $stmt = $this->pdo->prepare("
            SELECT hd.id, hd.percentual, hd.total_pontos, hd.pontos_obtidos,
                   hd.total_perguntas, hd.perguntas_ok, hd.data_salvo,
                   p.nome AS projeto_nome
            FROM historico_diagnosticos hd
            LEFT JOIN projetos p ON hd.projeto_id = p.id
            WHERE hd.usuario_id = ?
            ORDER BY hd.data_salvo DESC
            LIMIT 5
        ");
        $stmt->execute([$usuarioId]);
        $ultimosDiagnosticos = $stmt->fetchAll();

        // Caminho correto da view de perfil
        require BASE_PATH . '/app/views/perfil/index.php';
    }

    private function exigirLogin(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
    }
}