<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['respostas'])) {
    $respostas_enviadas = $_POST['respostas'];
    
    // Verifica se o usuário está logado
    $usuario_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if ($usuario_id) {
        // --- MODO LOGADO: Salva no Banco ---
        try {
            $pdo->beginTransaction();
            $delete = $pdo->prepare("DELETE FROM respostas WHERE usuario_id = ?");
            $delete->execute([$usuario_id]);

            $sql = "INSERT INTO respostas (usuario_id, pergunta_id, resposta) VALUES (:user, :pergunta, :res)";
            $stmt = $pdo->prepare($sql);

            foreach ($respostas_enviadas as $pergunta_id => $valor) {
                $stmt->execute([
                    ':user'     => $usuario_id,
                    ':pergunta' => $pergunta_id,
                    ':res'      => (int)$valor
                ]);
            }
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erro ao salvar no banco: " . $e->getMessage());
        }
    } else {
        // --- MODO VISITANTE: Salva na Sessão ---
        $_SESSION['temp_respostas'] = $respostas_enviadas;
    }

    header("Location: resultado.php");
    exit;
} else {
    header("Location: checklist.php");
    exit;
}