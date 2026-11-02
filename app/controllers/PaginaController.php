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

    /**
     * Executa o comando de promoção do usuário no banco de dados de produção (Aiven).
     */
    public function migrarAdmin(): void
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("UPDATE usuarios SET perfil = 'admin' WHERE email = 'leonardo23hm@gmail.com'");
            $stmt->execute();

            $check = $pdo->query("SELECT id, nome, email, perfil FROM usuarios WHERE email = 'leonardo23hm@gmail.com'")->fetch(PDO::FETCH_ASSOC);

            echo "<div style='font-family: sans-serif; text-align: center; margin-top: 60px; max-width: 600px; margin-left: auto; margin-right: auto; padding: 30px; border: 1px solid #E2E8F0; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);'>";
            if ($check && $check['perfil'] === 'admin') {
                if (isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] === (int)$check['id']) {
                    $_SESSION['perfil'] = 'admin';
                }
                echo "<h1 style='color: #00CC66; font-size: 1.8rem; margin-bottom: 15px;'>✓ Conta atualizada para ADMIN no banco de dados!</h1>";
                echo "<p style='color: #64748B; font-size: 1.05rem; margin-bottom: 25px;'>E-mail: <strong>{$check['email']}</strong> | Perfil gravado no MySQL: <strong style='color: #0066FF;'>{$check['perfil']}</strong></p>";
                echo "<div style='display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;'>";
                echo "<a href='/admin' style='background: #0066FF; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600;'>Ir para o Painel Admin</a>";
                echo "<a href='/logout' style='background: #F1F5F9; color: #1E293B; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; border: 1px solid #CBD5E1;'>Sair e Entrar Novamente</a>";
                echo "</div>";
            } else {
                echo "<h1 style='color: #EF4444;'>Usuário não localizado</h1>";
                echo "<p style='color: #64748B;'>O e-mail <strong>leonardo23hm@gmail.com</strong> ainda não consta cadastrado na tabela de usuários deste banco.</p>";
                echo "<p><a href='/cadastro'>Criar conta com este e-mail</a></p>";
            }
            echo "</div>";
        } catch (\Throwable $e) {
            echo "Erro ao conectar no banco de dados: " . htmlspecialchars($e->getMessage());
        }
    }
}
