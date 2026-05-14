<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<main class="container content-area">
    <div class="checklist-header">
        <h1>Meu Histórico de Diagnósticos</h1>
        <p>Acompanhe sua evolução de conformidade ao longo do tempo.</p>
    </div>

    <?php if (empty($historicos)): ?>
        <div class="card-pergunta" style="text-align: center; padding: 60px 40px;">
            <i class="fas fa-clipboard-list" style="font-size: 3rem; color: var(--border); margin-bottom: 20px; display: block;"></i>
            <h3 style="color: var(--text-muted); margin-bottom: 15px;">Nenhum diagnóstico salvo ainda</h3>
            <p style="color: var(--text-muted); margin-bottom: 30px;">
                Faça o checklist e clique em "Salvar Resultado" para começar a rastrear seu progresso.
            </p>
            <a href="/checklist" class="btn-primary">Fazer Checklist Agora</a>
        </div>

    <?php else: ?>

        <!-- Cards de resumo -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px;">
            <div class="card-material" style="text-align: center; padding: 25px;">
                <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary);"><?php echo count($historicos); ?></div>
                <div style="color: var(--text-muted); font-size: 0.9rem;">Diagnósticos Salvos</div>
            </div>
            <div class="card-material" style="text-align: center; padding: 25px;">
                <div style="font-size: 2.5rem; font-weight: 700; color: var(--secondary);">
                    <?php echo $historicos[0]['percentual']; ?>%
                </div>
                <div style="color: var(--text-muted); font-size: 0.9rem;">Último Resultado</div>
            </div>
            <div class="card-material" style="text-align: center; padding: 25px;">
                <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary);">
                    <?php
                        $media = round(array_sum(array_column($historicos, 'percentual')) / count($historicos));
                        echo $media;
                    ?>%
                </div>
                <div style="color: var(--text-muted); font-size: 0.9rem;">Média Geral</div>
            </div>
            <div class="card-material" style="text-align: center; padding: 25px;">
                <div style="font-size: 2.5rem; font-weight: 700; color: <?php echo (max(array_column($historicos, 'percentual')) >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>;">
                    <?php echo max(array_column($historicos, 'percentual')); ?>%
                </div>
                <div style="color: var(--text-muted); font-size: 0.9rem;">Melhor Resultado</div>
            </div>
        </div>

        <!-- Lista de diagnósticos -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <?php foreach ($historicos as $h): ?>
                <div class="card-pergunta" style="border-left: 5px solid <?php echo ($h['percentual'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>; padding: 25px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">

                        <!-- Info principal -->
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div style="text-align: center; min-width: 80px;">
                                <div style="font-size: 2rem; font-weight: 700; color: <?php echo ($h['percentual'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>; line-height: 1;">
                                    <?php echo $h['percentual']; ?>%
                                </div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">conformidade</div>
                            </div>

                            <div>
                                <div style="font-weight: 600; color: var(--text-main); margin-bottom: 4px;">
                                    <?php echo date('d/m/Y \à\s H:i', strtotime($h['data_salvo'])); ?>
                                </div>
                                <div style="font-size: 0.85rem; color: var(--text-muted);">
                                    <?php echo $h['perguntas_ok']; ?> de <?php echo $h['total_perguntas']; ?> itens conformes
                                    &nbsp;·&nbsp;
                                    <?php echo $h['pontos_obtidos']; ?>/<?php echo $h['total_pontos']; ?> pontos
                                </div>

                                <!-- Barra de progresso -->
                                <div style="background: #E2E8F0; height: 8px; border-radius: 10px; width: 250px; margin-top: 8px; overflow: hidden;">
                                    <div style="width: <?php echo $h['percentual']; ?>%; height: 100%; background: <?php echo ($h['percentual'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Ações -->
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="/historico/detalhe?id=<?php echo $h['id']; ?>" class="btn-secondary" style="font-size: 0.85rem; padding: 8px 16px;">
                                <i class="fas fa-eye"></i> Ver Detalhes
                            </a>
                            <form action="/historico/deletar" method="POST" onsubmit="return confirm('Remover este diagnóstico do histórico?');">
                                <input type="hidden" name="historico_id" value="<?php echo $h['id']; ?>">
                                <button type="submit" style="background: transparent; border: 2px solid var(--error); color: var(--error); padding: 8px 16px; border-radius: 10px; font-size: 0.85rem; cursor: pointer; font-weight: 600;">
                                    <i class="fas fa-trash"></i> Remover
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="/checklist" class="btn-primary">Fazer Novo Diagnóstico</a>
        </div>

    <?php endif; ?>
</main>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>