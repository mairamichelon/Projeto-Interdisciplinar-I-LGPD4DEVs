<?php

/**
 * Controller: AuthController
 *
 * Gerencia login, cadastro e logout.
 */
class AuthController
{
    private Usuario $model;

    public function __construct()
    {
        $this->model = new Usuario();
    }

    // -------------------------------------------------------------------------
    // LOGIN
    // -------------------------------------------------------------------------

    public function login(): void
    {
        if (isset($_SESSION['user_id'])) {
            header("Location: /");
            exit;
        }

        $erro = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->csrfValido()) {
                $erro = "Requisição inválida. Tente novamente.";
            } else {
                $email = trim($_POST['email'] ?? '');
                $senha = $_POST['senha'] ?? '';

                if (empty($email) || empty($senha)) {
                    $erro = "Por favor, preencha todos os campos.";
                } else {
                    $user = $this->model->buscarPorEmail($email);

                    if ($user && password_verify($senha, $user['senha'])) {
                        session_regenerate_id(true);
                        $_SESSION['user_id']   = $user['id'];
                        $_SESSION['user_name'] = $user['nome'];
                        $_SESSION['perfil']    = $user['perfil']; // 'usuario' ou 'admin'

                        // Admin vai direto para o painel
                        if ($user['perfil'] === 'admin') {
                            header("Location: /admin");
                        } else {
                            header("Location: /");
                        }
                        exit;
                    } else {
                        $erro = "E-mail ou senha inválidos. Tente novamente.";
                    }
                }
            }
        }

        $this->gerarCsrf();
        require BASE_PATH . '/app/views/auth/login.php';
    }

    // -------------------------------------------------------------------------
    // CADASTRO
    // -------------------------------------------------------------------------

    public function cadastro(): void
    {
        $erro    = "";
        $sucesso = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->csrfValido()) {
                $erro = "Requisição inválida. Tente novamente.";
            } else {
                $nome  = trim($_POST['nome']  ?? '');
                $email = trim($_POST['email'] ?? '');
                $senha = $_POST['senha'] ?? '';

                if (empty($nome) || empty($email) || empty($senha)) {
                    $erro = "Por favor, preencha todos os campos do formulário.";
                } elseif (strlen($senha) < 8) {
                    $erro = "A senha deve ter no mínimo 8 caracteres.";
                } elseif ($this->model->emailExiste($email)) {
                    $erro = "Este e-mail já está cadastrado no sistema.";
                } else {
                    $this->model->criar($nome, $email, $senha);
                    $sucesso = "Conta criada com sucesso! <a href='/login' style='color: var(--primary); font-weight: bold;'>Clique aqui para entrar</a>.";
                }
            }
        }

        $this->gerarCsrf();
        require BASE_PATH . '/app/views/auth/cadastro.php';
    }

    // -------------------------------------------------------------------------
    // LOGOUT
    // -------------------------------------------------------------------------

    public function logout(): void
    {
        session_destroy();
        header("Location: /");
        exit;
    }

    // -------------------------------------------------------------------------
    // Helpers privados
    // -------------------------------------------------------------------------

    private function gerarCsrf(): void
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    private function csrfValido(): bool
    {
        $tokenSessao = $_SESSION['csrf_token'] ?? '';
        $tokenPost   = $_POST['csrf_token']    ?? '';
        return !empty($tokenSessao) && hash_equals($tokenSessao, $tokenPost);
    }
}