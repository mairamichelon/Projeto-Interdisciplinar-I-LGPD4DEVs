<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<main class="container content-area">

    <!-- Cabeçalho -->
    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 30px; flex-wrap: wrap;">
        <a href="/historico" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">
            <i class="fas fa-arrow-left"></i> Voltar ao Histórico
        </a>
    </div>

    <div class="checklist-header">
        <h1>Detalhes do Diagnóstico</h1>
        <p>Salvo em <strong><?php echo date('d/m/Y \à\s H:i', strtotime($cabecalho['data_salvo'])); ?></strong></p>
    </div>

    <!-- Score -->
    <div class="card-pergunta text-center" style="padding: 40px; margin-bottom: 40px;">
        <div style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 10px;">Nível de Adequação</div>
        <h2 style="font-size: 4.5rem; color: <?php echo ($cabecalho['percentual'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>; line-height: 1; margin-bottom: 20px;">
            <?php echo $cabecalho['percentual']; ?>%
        </h2>

        <div style="background: #E2E8F0; height: 14px; border-radius: 10px; width: 100%; max-width: 500px; margin: 0 auto 20px; overflow: hidden;">
            <div style="width: <?php echo $cabecalho['percentual']; ?>%; height: 100%; background: <?php echo ($cabecalho['percentual'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>;"></div>
        </div>

        <p style="font-size: 1.1rem; color: var(--text-main); font-weight: 500;">
            <?php
                if ($cabecalho['percentual'] == 100)    echo "Conformidade Total detectada!";
                elseif ($cabecalho['percentual'] >= 70) echo "Nível satisfatório, mas existem melhorias pendentes.";
                else                                    echo "Atenção: Foram detectadas falhas críticas de privacidade.";
            ?>
        </p>

        <!-- Métricas -->
        <div style="display: flex; justify-content: center; gap: 40px; margin-top: 25px; flex-wrap: wrap;">
            <div style="text-align: center;">
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--secondary);"><?php echo $cabecalho['perguntas_ok']; ?></div>
                <div style="font-size: 0.8rem; color: var(--text-muted);">Itens Conformes</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--error);"><?php echo $cabecalho['perguntas_falha']; ?></div>
                <div style="font-size: 0.8rem; color: var(--text-muted);">Itens em Aberto</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary);"><?php echo $cabecalho['pontos_obtidos']; ?>/<?php echo $cabecalho['total_pontos']; ?></div>
                <div style="font-size: 0.8rem; color: var(--text-muted);">Pontos Obtidos</div>
            </div>
        </div>
    </div>

    <!-- Respostas detalhadas agrupadas por categoria -->
    <?php
        $porCategoria = [];
        foreach ($respostas as $r) {
            $porCategoria[$r['categoria_nome']][] = $r;
        }
    ?>

    <?php foreach ($porCategoria as $categoria => $itens): ?>
        <div style="margin-bottom: 30px;">
            <h3 style="color: var(--text-main); margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                <span class="badge-categoria"><?php echo htmlspecialchars($categoria); ?></span>
            </h3>

            <?php foreach ($itens as $item): ?>
                <div class="card-pergunta" style="border-left: 5px solid <?php echo ($item['resposta'] == 1) ? 'var(--secondary)' : 'var(--error)'; ?>; margin-bottom: 15px; padding: 20px;">
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 15px;">
                        <p style="margin: 0; color: var(--text-main); font-weight: 500; flex: 1;">
                            <?php echo htmlspecialchars($item['pergunta_texto']); ?>
                        </p>
                        <div style="display: flex; flex-direction: column; align-items: center; min-width: 80px;">
                            <span style="font-size: 1.2rem;">
                                <?php echo ($item['resposta'] == 1) ? '✅' : '❌'; ?>
                            </span>
                            <span style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">
                                Peso: <?php echo $item['pergunta_peso']; ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <!-- Ações -->
    <div style="text-align: center; margin-top: 40px; padding-top: 30px; border-top: 1px solid var(--border);" class="no-print">
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="/historico" class="btn-secondary">
                <i class="fas fa-history"></i> Ver Todo o Histórico
            </a>
            <a href="/checklist" class="btn-primary">
                <i class="fas fa-redo"></i> Fazer Novo Diagnóstico
            </a>
            <button onclick="window.print()" class="btn-secondary">
                <i class="fas fa-print"></i> Exportar em PDF
            </button>
        </div>
    </div>

</main>

<style>
@media print {
    .no-print, .navbar, .main-footer { display: none !important; }
    .card-pergunta { page-break-inside: avoid !important; break-inside: avoid !important; }
    body { background: white !important; }
}
</style>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>