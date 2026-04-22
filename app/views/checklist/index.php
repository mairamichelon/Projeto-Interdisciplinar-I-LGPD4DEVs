<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<main class="container content-area">
    <div class="checklist-header">
        <h1>Checklist Interativo LGPD</h1>
        <p>Responda às questões abaixo para avaliar o nível de conformidade do seu sistema com o Artigo 14 da LGPD.</p>
    </div>

    <form action="/checklist" method="POST" class="checklist-form">
        <!-- Token CSRF -->
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">

        <?php if (count($perguntas) > 0): ?>

            <?php foreach ($perguntas as $p): ?>
                <div class="card-pergunta">
                    <span class="badge-categoria">
                        <?php echo htmlspecialchars($p['categoria']); ?>
                    </span>

                    <p style="font-size: 1.15rem; font-weight: 600; margin: 15px 0; color: var(--text-main);">
                        <?php echo htmlspecialchars($p['texto']); ?>
                    </p>

                    <div class="opcoes-resposta">
                        <label class="radio-label">
                            <input type="radio" name="respostas[<?php echo $p['id']; ?>]" value="1" required>
                            <span class="radio-design">Sim</span>
                        </label>

                        <label class="radio-label">
                            <input type="radio" name="respostas[<?php echo $p['id']; ?>]" value="0" required>
                            <span class="radio-design">Não</span>
                        </label>
                    </div>
                </div>
            <?php endforeach; ?>

            <div style="text-align: center; margin-top: 60px; padding-bottom: 40px;">
                <p style="color: var(--text-muted); margin-bottom: 20px; font-size: 0.95rem;">
                    Certifique-se de responder todas as questões para um resultado preciso.
                </p>
                <button type="submit" class="btn-save" style="max-width: 450px;">
                    Finalizar e Gerar Relatório de Conformidade
                </button>
            </div>

        <?php else: ?>
            <div class="card-pergunta" style="text-align: center;">
                <p>Nenhuma pergunta de conformidade foi encontrada no sistema.</p>
                <p style="font-size: 0.9rem; color: var(--text-muted);">Verifique se a tabela 'perguntas' foi populada corretamente no banco de dados.</p>
            </div>
        <?php endif; ?>

    </form>
</main>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
