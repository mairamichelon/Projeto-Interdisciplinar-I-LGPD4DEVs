<?php

/**
 * Model: Resposta
 * 
 * Acesso a dados da tabela `respostas`.
 * Centraliza as operações de insert/delete que antes estavam em processa_checklist.php.
 */
class Resposta
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Salva as respostas de um usuário logado.
     * 
     * Remove respostas anteriores e insere as novas dentro de uma
     * transação — comportamento idêntico ao processa_checklist.php original.
     * 
     * @param int   $usuarioId        ID do usuário logado
     * @param array $respostas        Array [pergunta_id => valor (0|1)]
     * @param array $idsValidos       IDs permitidos (validação server-side)
     */
    public function salvarParaUsuario(int $usuarioId, array $respostas, array $idsValidos): void
    {
        $this->pdo->beginTransaction();

        try {
            // Remove ciclo anterior do mesmo usuário
            $delete = $this->pdo->prepare("DELETE FROM respostas WHERE usuario_id = ?");
            $delete->execute([$usuarioId]);

            $stmt = $this->pdo->prepare(
                "INSERT INTO respostas (usuario_id, pergunta_id, resposta) VALUES (:user, :pergunta, :res)"
            );

            foreach ($respostas as $perguntaId => $valor) {
                // Valida que o ID existe no banco (evita inserção de IDs arbitrários)
                if (!in_array((int)$perguntaId, $idsValidos)) {
                    continue;
                }

                $stmt->execute([
                    ':user'     => $usuarioId,
                    ':pergunta' => (int)$perguntaId,
                    ':res'      => (int)$valor,
                ]);
            }

            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            error_log('[LGPD4DEVS] Erro ao salvar respostas: ' . $e->getMessage());
            throw $e; // Relança para o Controller tratar
        }
    }
}
