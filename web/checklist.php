<?php 
// 1. Conexão com o banco de dados
require_once 'db.php';

// 2. Busca as perguntas unindo com a tabela de categorias (JOIN)
try {
    // Aqui está o segredo: pegamos o 'nome' da tabela categorias e chamamos de 'categoria'
    $sql = "SELECT p.id, p.texto, c.nome as categoria 
            FROM perguntas p 
            JOIN categorias c ON p.categoria_id = c.id 
            ORDER BY p.id ASC";
            
    $stmt = $pdo->query($sql);
    $perguntas = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erro ao carregar perguntas: " . $e->getMessage());
}

// 3. Inclui o topo do site
include 'includes/header.php'; 
?>

<main class="container content-area">
    <div class="checklist-header">
        <h1>Checklist Interativo LGPD</h1>
        <p>Responda às questões abaixo para avaliar o nível de conformidade do seu projeto com o Art. 14 da LGPD.</p>
    </div>

    <form action="processa_checklist.php" method="POST" class="checklist-form">
        
        <?php if (count($perguntas) > 0): ?>
            <?php foreach ($perguntas as $p): ?>
                <div class="card-pergunta">
                    <span class="badge-categoria"><?php echo htmlspecialchars($p['categoria']); ?></span>
                    <p class="pergunta-texto"><?php echo htmlspecialchars($p['texto']); ?></p>
                    
                    <div class="opcoes-resposta">
                        <label class="radio-label">
                            <input type="radio" name="respostas[<?php echo $p['id']; ?>]" value="1" required>
                            Sim
                        </label>

                        <label class="radio-label">
                            <input type="radio" name="respostas[<?php echo $p['id']; ?>]" value="0" required>
                            Não
                        </label>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="form-actions">
                <button type="submit" class="btn-save">Finalizar e Gerar Relatório</button>
            </div>
        <?php else: ?>
            <p class="text-center">Nenhuma pergunta encontrada no banco de dados.</p>
        <?php endif; ?>
        
    </form>
</main>

<?php 
// 4. Inclui o rodapé do site
include 'includes/footer.php'; 
?>