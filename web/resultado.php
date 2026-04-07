<?php
require_once 'db.php';
include 'includes/header.php';

$usuario_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$dados_para_calculo = [];
$is_guest = false;

try {
    if ($usuario_id) {
        // BUSCA DO BANCO (Logado)
        $sql = "SELECT r.resposta, p.texto, p.peso, m.titulo as material_titulo, m.url_referencia 
                FROM respostas r
                JOIN perguntas p ON r.pergunta_id = p.id
                LEFT JOIN materiais m ON m.pergunta_id = p.id
                WHERE r.usuario_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$usuario_id]);
        $dados_para_calculo = $stmt->fetchAll();
    } elseif (isset($_SESSION['temp_respostas'])) {
        // BUSCA DA SESSÃO (Visitante)
        $is_guest = true;
        foreach ($_SESSION['temp_respostas'] as $p_id => $res) {
            $stmt = $pdo->prepare("SELECT p.texto, p.peso, m.titulo as material_titulo, m.url_referencia 
                                   FROM perguntas p 
                                   LEFT JOIN materiais m ON m.pergunta_id = p.id 
                                   WHERE p.id = ?");
            $stmt->execute([$p_id]);
            $info = $stmt->fetch();
            if ($info) {
                $dados_para_calculo[] = array_merge($info, ['resposta' => $res]);
            }
        }
    }

    // Cálculos
    $total_pontos = 0; $obtidos = 0; $falhas = [];
    foreach ($dados_para_calculo as $item) {
        $total_pontos += (int)$item['peso'];
        if ($item['resposta'] == 1) {
            $obtidos += (int)$item['peso'];
        } else {
            $falhas[] = $item;
        }
    }
    $percentual = ($total_pontos > 0) ? round(($obtidos / $total_pontos) * 100) : null;

} catch (PDOException $e) {
    die("Erro no processamento: " . $e->getMessage());
}
?>

<main class="container content-area">
    <?php if ($is_guest && $percentual !== null): ?>
        <div style="background: #FFF3CD; color: #856404; padding: 15px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #FFEEBA; text-align: center;">
            <strong>Aviso de Visitante:</strong> Este resultado é temporário. 
            <a href="cadastro.php" style="color: #856404; font-weight: bold;">Crie sua conta</a> para salvar o histórico no seu perfil.
        </div>
    <?php endif; ?>

    <div class="checklist-header">
        <h1>Resultado do Diagnóstico</h1>
    </div>

    <?php if ($percentual !== null): ?>
        <div class="card-pergunta text-center">
            <h2 style="font-size: 3.5rem; color: <?php echo ($percentual >= 70) ? 'var(--secondary-color)' : 'var(--error-color)'; ?>">
                <?php echo $percentual; ?>%
            </h2>
            <p>Conformidade Geral detectada</p>
        </div>

        <?php if (count($falhas) > 0): ?>
            <div style="margin-top: 40px;">
                <h3 style="color: var(--error-color); margin-bottom: 20px;">⚠️ Recomendações Técnicas</h3>
                <?php foreach ($falhas as $f): ?>
                    <div class="card-pergunta" style="border-left-color: var(--error-color);">
                        <p><strong>Não em conformidade:</strong> <?php echo htmlspecialchars($f['texto']); ?></p>
                        <?php if($f['material_titulo']): ?>
                            <p style="margin-top: 10px;">
                                <strong>Material Sugerido:</strong> 
                                <a href="<?php echo $f['url_referencia']; ?>" target="_blank" style="color: var(--primary-color);">
                                    <?php echo htmlspecialchars($f['material_titulo']); ?>
                                </a>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="card-pergunta text-center">
            <p>Nenhuma resposta encontrada. Por favor, volte ao checklist.</p>
            <a href="checklist.php" class="btn-primary" style="display:inline-block; margin-top:20px;">Ir para Checklist</a>
        </div>
    <?php endif; ?>

    <div class="form-actions" style="margin-top: 40px; text-align: center;">
        <a href="checklist.php" class="btn-secondary">Refazer Teste</a>
        <a href="index.php" class="btn-primary">Voltar ao Início</a>
    </div>
</main>

<?php include 'includes/footer.php'; ?>