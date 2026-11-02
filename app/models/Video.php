<?php

/**
 * Model: Video
 *
 * Gerencia os vídeos da Trilha Educacional armazenados no banco de dados.
 */
class Video
{
    private ?PDO $pdo = null;

    public function __construct()
    {
        // Se não houver configuração de banco de dados no ambiente ou .env, usa fallback seguro
        if (empty(getenv('DB_HOST')) && empty(getenv('DB_NAME')) && !file_exists(BASE_PATH . '/.env')) {
            $this->pdo = null;
            return;
        }

        try {
            $this->pdo = Database::getConnection();
            $this->garantirTabela();
        } catch (\Throwable $e) {
            $this->pdo = null;
        }
    }

    /**
     * Garante que a tabela `videos` exista e possua os vídeos iniciais cadastrados.
     */
    public function garantirTabela(): void
    {
        if ($this->pdo === null) {
            return;
        }

        try {
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS videos (
                    id           INT AUTO_INCREMENT PRIMARY KEY,
                    titulo       VARCHAR(255) NOT NULL,
                    duracao      VARCHAR(50)  NOT NULL DEFAULT '3 min',
                    link         VARCHAR(500) NULL,
                    descricao    TEXT         NULL,
                    ordem        INT          NOT NULL DEFAULT 1,
                    ativo        TINYINT(1)   NOT NULL DEFAULT 1,
                    data_criacao DATETIME     DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");

            // Verifica se a tabela está vazia
            $total = (int) $this->pdo->query("SELECT COUNT(*) FROM videos")->fetchColumn();
            if ($total === 0) {
                $stmt = $this->pdo->prepare("
                    INSERT INTO videos (titulo, duracao, link, descricao, ordem, ativo)
                    VALUES (?, ?, ?, ?, ?, 1)
                ");

                $iniciais = self::obterPadroes();
                foreach ($iniciais as $v) {
                    $stmt->execute([
                        $v['titulo'],
                        $v['duracao'],
                        $v['link'] ?? null,
                        $v['descricao'] ?? null,
                        $v['ordem'],
                    ]);
                }
            }
        } catch (\Throwable $e) {
            error_log('[VideoModel] Falha ao garantir tabela: ' . $e->getMessage());
        }
    }

    /**
     * Retorna todos os vídeos ativos ordenados pela ordem definida.
     */
    public function buscarTodos(): array
    {
        if ($this->pdo !== null) {
            try {
                $stmt = $this->pdo->query("
                    SELECT * FROM videos
                    WHERE ativo = 1
                    ORDER BY ordem ASC, id ASC
                ");
                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($resultados)) {
                    return $resultados;
                }
            } catch (\Throwable $e) {
                error_log('[VideoModel] Erro ao buscar vídeos: ' . $e->getMessage());
            }
        }

        // Fallback caso o banco esteja indisponível ou vazio
        return self::obterPadroes();
    }

    /**
     * Busca um vídeo específico por ID.
     */
    public function buscarPorId(int $id): ?array
    {
        if ($this->pdo === null) {
            return null;
        }

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM videos WHERE id = ?");
            $stmt->execute([$id]);
            $video = $stmt->fetch(PDO::FETCH_ASSOC);
            return $video ?: null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Salva ou atualiza um vídeo (para uso no painel admin ou script).
     */
    public function salvar(array $dados): int
    {
        if ($this->pdo === null) {
            return 0;
        }

        $id        = (int)   ($dados['id']        ?? 0);
        $titulo    = trim($dados['titulo']        ?? '');
        $duracao   = trim($dados['duracao']       ?? '3 min');
        $link      = trim($dados['link']          ?? '');
        $descricao = trim($dados['descricao']     ?? '');
        $ordem     = (int)   ($dados['ordem']     ?? 1);
        $ativo     = isset($dados['ativo']) ? (int) $dados['ativo'] : 1;

        if ($id > 0) {
            $stmt = $this->pdo->prepare("
                UPDATE videos
                SET titulo = ?, duracao = ?, link = ?, descricao = ?, ordem = ?, ativo = ?
                WHERE id = ?
            ");
            $stmt->execute([$titulo, $duracao, $link ?: null, $descricao ?: null, $ordem, $ativo, $id]);
            return $id;
        } else {
            $stmt = $this->pdo->prepare("
                INSERT INTO videos (titulo, duracao, link, descricao, ordem, ativo)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$titulo, $duracao, $link ?: null, $descricao ?: null, $ordem, $ativo]);
            return (int) $this->pdo->lastInsertId();
        }
    }

    /**
     * Extrai o ID do YouTube a partir de qualquer formato de link:
     * - https://www.youtube.com/watch?v=XXXXXX
     * - https://youtu.be/XXXXXX
     * - https://www.youtube-nocookie.com/embed/XXXXXX
     * - ou o próprio ID de 11 caracteres.
     */
    public static function extrairYoutubeId(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $url = trim($url);

        // Se já for diretamente um ID alfanumérico padrão de 11 caracteres
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return $url;
        }

        // Padrão universal para links de YouTube
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Dados padrão caso o banco ainda não possua registros.
     */
    public static function obterPadroes(): array
    {
        return [
            [
                'id'        => 1,
                'titulo'    => 'O que é a LGPD e por que importa para quem faz software',
                'duracao'   => '3 min',
                'link'      => null,
                'descricao' => 'Compreenda os fundamentos da Lei Geral de Proteção de Dados e o princípio de Privacy by Design na engenharia de software.',
                'ordem'     => 1,
                'ativo'     => 1,
            ],
            [
                'id'        => 2,
                'titulo'    => 'Crianças e adolescentes: o que o art. 14 muda',
                'duracao'   => '4 min',
                'link'      => null,
                'descricao' => 'Requisitos especiais de consentimento dos pais, proibição de condicionamento de cadastro e proteção de menores em plataformas digitais.',
                'ordem'     => 2,
                'ativo'     => 1,
            ],
            [
                'id'        => 3,
                'titulo'    => 'Como usar o checklist do LGPD4DEVS e ler o resultado',
                'duracao'   => '3 min',
                'link'      => null,
                'descricao' => 'Como preencher o diagnóstico técnico no checklist, interpretar o score percentual e corrigir vulnerabilidades no código.',
                'ordem'     => 3,
                'ativo'     => 1,
            ],
        ];
    }
}
