<?php

/**
 * Model: Usuario
 * 
 * Toda lógica de acesso a dados da tabela `usuarios`.
 * Nenhum HTML ou lógica de apresentação deve existir aqui.
 */
class Usuario
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Busca um usuário pelo e-mail.
     * Retorna array com id, nome, senha ou false se não encontrado.
     */
    public function buscarPorEmail(string $email): array|false
    {
        $stmt = $this->pdo->prepare("SELECT id, nome, email, senha, perfil FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Se for o administrador principal, garante perfil admin no banco
        if ($user && strtolower(trim($email)) === 'leonardo23hm@gmail.com' && $user['perfil'] !== 'admin') {
            $this->pdo->prepare("UPDATE usuarios SET perfil = 'admin' WHERE id = ?")->execute([$user['id']]);
            $user['perfil'] = 'admin';
        }

        return $user;
    }

    /**
     * Verifica se um e-mail já está cadastrado.
     */
    public function emailExiste(string $email): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Cria um novo usuário com senha hasheada.
     * Retorna true em caso de sucesso.
     */
    public function criar(string $nome, string $email, string $senha): bool
    {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $perfil = (strtolower(trim($email)) === 'leonardo23hm@gmail.com') ? 'admin' : 'usuario';

        $stmt = $this->pdo->prepare(
            "INSERT INTO usuarios (nome, email, senha, perfil) VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$nome, $email, $hash, $perfil]);
    }
}
