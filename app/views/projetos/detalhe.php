<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<main class="container content-area">

    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px; flex-wrap: wrap;">
        <a href="/projetos" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">
            <i class="fas fa-arrow-left"></i> Meus Projetos
        </a>
    </div>

    <!-- Header do projeto -->
    <div class="card-pergunta" style="border-top: 4px solid <?php echo Projeto::corStatus($projeto['status']); ?>; margin-bottom: 35px; padding: 30px;">
        <div style="display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
            <div>
                <span style="display: inline-block; background: <?php echo Projeto::corStatus($projeto['status']); ?>22; color: <?php echo Projeto::corStatus($projeto['status']); ?>; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; margin-bottom: 10px;">
                    <?php echo Projeto::labelStatus($projeto['status']); ?>
                </span>
                <h1 style="margin: 0 0 8px; font-size: 1.8rem;"><?php echo htmlspecialchars($projeto['nome']); ?></h1>
                <span class="badge-categoria">👥 <?php echo Projeto::labelPublicoAlvo($projeto['publico_alvo']); ?></span>
                <?php if ($projeto['descricao']): ?>
                    <p style="color: var(--text-muted); margin-top: 12px; line-height: 1.6;"><?php echo htmlspecialchars($projeto['descricao']); ?></p>
                <?php endif; ?>
            </div>
            <a href="/checklist" class="btn-primary" style="white-space: nowrap;">
                <i class="fas fa-plus"></i> Novo Diagnóstico
            </a>
        </div>
    </div>

    <!-- Estatísticas -->
    <?php if (!empty($historicos)): ?>
        <?php
            $percentuais = array_column($historicos, 'percentual');
            $media       = round(array_sum($percentuais) / count($percentuais));
            $melhor      = max($percentuais);
            $primeiro    = end($historicos)['percentual'];
            $ultimo      = $historicos[0]['percentual'];
            $evolucao    = $ultimo - $primeiro;
        ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-bottom: 35px;">
            <div class="card-material" style="text-align: center; padding: 20px;">
                <div style="font-size: 2rem; font-weight: 700; color: var(--primary);"><?php echo count($historicos); ?></div>
                <div style="color: var(--text-muted); font-size: 0.85rem;">Diagnósticos</div>
            </div>
            <div class="card-material" style="text-align: center; padding: 20px;">
                <div style="font-size: 2rem; font-weight: 700; color: <?php echo ($ultimo >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>;"><?php echo $ultimo; ?>%</div>
                <div style="color: var(--text-muted); font-size: 0.85rem;">Último</div>
            </div>
            <div class="card-material" style="text-align: center; padding: 20px;">
                <div style="font-size: 2rem; font-weight: 700; color: var(--primary);"><?php echo $media; ?>%</div>
                <div style="color: var(--text-muted); font-size: 0.85rem;">Média</div>
            </div>
            <div class="card-material" style="text-align: center; padding: 20px;">
                <div style="font-size: 2rem; font-weight: 700; color: var(--secondary);"><?php echo $melhor; ?>%</div>
                <div style="color: var(--text-muted); font-size: 0.85rem;">Melhor</div>
            </div>
            <div class="card-material" style="text-align: center; padding: 20px;">
                <div style="font-size: 2rem; font-weight: 700; color: <?php echo ($evolucao >= 0) ? 'var(--secondary)' : 'var(--error)'; ?>;">
                    <?php echo ($evolucao >= 0 ? '+' : '') . $evolucao; ?>%
                </div>
                <div style="color: var(--text-muted); font-size: 0.85rem;">Evolução</div>
            </div>
        </div>

        <!-- Lista de diagnósticos -->
        <h3 style="margin-bottom: 20px; color: var(--text-main);">
            <i class="fas fa-history" style="color: var(--primary);"></i> Histórico de Diagnósticos
        </h3>

        <div style="display: flex; flex-direction: column; gap: 15px;">
            <?php foreach ($historicos as $h): ?>
                <div class="card-pergunta" style="border-left: 5px solid <?php echo ($h['percentual'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>; padding: 20px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div style="text-align: center; min-width: 70px;">
                                <div style="font-size: 1.8rem; font-weight: 700; color: <?php echo ($h['percentual'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>; line-height: 1;">
                                    <?php echo $h['percentual']; ?>%
                                </div>
                            </div>
                            <div>
                                <div style="font-weight: 600; color: var(--text-main); margin-bottom: 3px;">
                                    <?php echo date('d/m/Y \à\s H:i', strtotime($h['data_salvo'])); ?>
                                </div>
                                <div style="font-size: 0.82rem; color: var(--text-muted);">
                                    <?php echo $h['perguntas_ok']; ?> de <?php echo $h['total_perguntas']; ?> conformes
                                    &nbsp;·&nbsp; <?php echo $h['pontos_obtidos']; ?>/<?php echo $h['total_pontos']; ?> pontos
                                </div>
                                <div style="background: #E2E8F0; height: 6px; border-radius: 10px; width: 200px; margin-top: 6px; overflow: hidden;">
                                    <div style="width: <?php echo $h['percentual']; ?>%; height: 100%; background: <?php echo ($h['percentual'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>;"></div>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <a href="/historico/detalhe?id=<?php echo $h['id']; ?>" class="btn-secondary" style="font-size: 0.85rem; padding: 8px 14px;">
                                <i class="fas fa-eye"></i> Detalhes
                            </a>
                            <form action="/historico/deletar" method="POST" onsubmit="return confirm('Remover este diagnóstico?');" style="margin:0;">
                                <input type="hidden" name="historico_id" value="<?php echo $h['id']; ?>">
                                <input type="hidden" name="projeto_id"   value="<?php echo $projeto['id']; ?>">
                                <button type="submit" style="background: transparent; border: 2px solid var(--error); color: var(--error); padding: 8px 12px; border-radius: 10px; font-size: 0.85rem; cursor: pointer;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <div class="card-pergunta" style="text-align: center; padding: 50px;">
            <i class="fas fa-clipboard-list" style="font-size: 2.5rem; color: var(--border); margin-bottom: 15px; display: block;"></i>
            <p style="color: var(--text-muted); margin-bottom: 20px;">Nenhum diagnóstico salvo para este projeto ainda.</p>
            <a href="/checklist" class="btn-primary">Fazer Primeiro Diagnóstico</a>
        </div>
    <?php endif; ?>

</main>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>