<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<main class="container content-area">
    <div class="checklist-header">
        <h1>Meu Histórico de Diagnósticos</h1>
        <p>Acompanhe sua evolução de conformidade ao longo do tempo.</p>
    </div>

    <?php if ($totalDiagnosticos === 0 && empty($filtros['projeto_id']) && empty($filtros['status'])): ?>
        <!-- Estado vazio sem filtros -->
        <div class="card-pergunta" style="text-align: center; padding: 60px 40px;">
            <i class="fas fa-clipboard-list" style="font-size: 3rem; color: var(--border); margin-bottom: 20px; display: block;"></i>
            <h3 style="color: var(--text-muted); margin-bottom: 15px;">Nenhum diagnóstico salvo ainda</h3>
            <p style="color: var(--text-muted); margin-bottom: 30px;">
                Faça o checklist e clique em "Salvar Resultado" para começar a rastrear seu progresso.
            </p>
            <a href="/checklist" class="btn-primary">Fazer Checklist Agora</a>
        </div>

    <?php else: ?>

        <!-- Cards de resumo (apenas sem filtros ativos) -->
        <?php if (empty($filtros['projeto_id']) && empty($filtros['status']) && !empty($resumo)): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div class="card-material" style="text-align: center; padding: 25px;">
                <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary);"><?php echo $resumo['total']; ?></div>
                <div style="color: var(--text-muted); font-size: 0.9rem;">Diagnósticos Salvos</div>
            </div>
            <div class="card-material" style="text-align: center; padding: 25px;">
                <div style="font-size: 2.5rem; font-weight: 700; color: var(--secondary);">
                    <?php echo $resumo['ultimo_percentual']; ?>%
                </div>
                <div style="color: var(--text-muted); font-size: 0.9rem;">Último Resultado</div>
            </div>
            <div class="card-material" style="text-align: center; padding: 25px;">
                <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary);">
                    <?php echo $resumo['media']; ?>%
                </div>
                <div style="color: var(--text-muted); font-size: 0.9rem;">Média Geral</div>
            </div>
            <div class="card-material" style="text-align: center; padding: 25px;">
                <div style="font-size: 2.5rem; font-weight: 700; color: <?php echo ($resumo['melhor'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>;">
                    <?php echo $resumo['melhor']; ?>%
                </div>
                <div style="color: var(--text-muted); font-size: 0.9rem;">Melhor Resultado</div>
            </div>
        </div>
        <?php endif; ?>

        <!-- ══ PAINEL DE FILTROS ══ -->
        <div class="card-material" style="padding: 20px 25px; margin-bottom: 25px;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                <i class="fas fa-filter" style="color: var(--primary);"></i>
                <span style="font-weight: 700; font-size: 0.95rem; color: var(--text-main);">Filtrar Diagnósticos</span>
                <?php if (!empty($filtros['projeto_id']) || !empty($filtros['status'])): ?>
                    <span style="background: var(--primary); color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.72rem; font-weight: 700;">
                        Filtros ativos
                    </span>
                <?php endif; ?>
            </div>

            <form method="GET" action="/historico" id="formFiltros">
                <div style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">

                    <!-- Filtro por projeto -->
                    <div style="flex: 1; min-width: 200px;">
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">
                            <i class="fas fa-folder"></i> Projeto
                        </label>
                        <select name="projeto_id"
                                style="width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 0.88rem; font-family: inherit; color: var(--text-main);">
                            <option value="">Todos os projetos</option>
                            <?php foreach ($projetos as $p): ?>
                                <option value="<?php echo $p['id']; ?>" <?php echo ($filtros['projeto_id'] == $p['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($p['nome']); ?>
                                    (<?php echo Projeto::labelStatus($p['status']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filtro por status -->
                    <div style="flex: 1; min-width: 200px;">
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">
                            <i class="fas fa-tag"></i> Status do Projeto
                        </label>
                        <select name="status"
                                style="width: 100%; padding: 10px 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 0.88rem; font-family: inherit; color: var(--text-main);">
                            <option value="">Todos os status</option>
                            <option value="em_desenvolvimento" <?php echo ($filtros['status'] === 'em_desenvolvimento') ? 'selected' : ''; ?>>Em Desenvolvimento</option>
                            <option value="em_producao" <?php echo ($filtros['status'] === 'em_producao') ? 'selected' : ''; ?>>Em Produção</option>
                            <option value="arquivado" <?php echo ($filtros['status'] === 'arquivado') ? 'selected' : ''; ?>>Arquivado</option>
                        </select>
                    </div>

                    <!-- Botão filtrar -->
                    <div>
                        <button type="submit" class="btn-primary" style="padding: 10px 20px; min-width: unset; font-size: 0.88rem; height: 42px;">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                    </div>

                    <!-- Botão limpar -->
                    <?php if (!empty($filtros['projeto_id']) || !empty($filtros['status'])): ?>
                    <div>
                        <a href="/historico" class="btn-secondary" style="padding: 10px 14px; min-width: unset; font-size: 0.85rem; height: 42px; display: inline-flex; align-items: center; gap: 5px;">
                            <i class="fas fa-times"></i> Limpar
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Contador de resultados -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
            <p style="color: var(--text-muted); font-size: 0.88rem; margin: 0;">
                <?php if ($totalDiagnosticos > 0): ?>
                    Exibindo <strong><?php echo count($historicos); ?></strong> de <strong><?php echo $totalDiagnosticos; ?></strong> diagnóstico(s)
                    <?php if (!empty($filtros['projeto_id']) || !empty($filtros['status'])): ?>
                        <span style="color: var(--primary);">(filtrado)</span>
                    <?php endif; ?>
                <?php else: ?>
                    Nenhum diagnóstico encontrado com os filtros aplicados.
                <?php endif; ?>
            </p>
            <a href="/checklist" class="btn-primary" style="font-size: 0.85rem; padding: 8px 16px; min-width: unset;">
                <i class="fas fa-plus"></i> Novo Diagnóstico
            </a>
        </div>

        <?php if (!empty($historicos)): ?>
        <!-- Lista de diagnósticos -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <?php foreach ($historicos as $h): ?>
                <div class="card-pergunta" style="border-left: 5px solid <?php echo ($h['percentual'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>; padding: 25px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">

                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div style="text-align: center; min-width: 80px;">
                                <div style="font-size: 2rem; font-weight: 700; color: <?php echo ($h['percentual'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>; line-height: 1;">
                                    <?php echo $h['percentual']; ?>%
                                </div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">conformidade</div>
                            </div>

                            <div>
                                <?php if (!empty($h['projeto_nome'])): ?>
                                    <div style="font-size: 0.78rem; color: var(--primary); font-weight: 600; margin-bottom: 3px;">
                                        <i class="fas fa-folder"></i> <?php echo htmlspecialchars($h['projeto_nome']); ?>
                                        <?php if (!empty($h['projeto_status'])): ?>
                                            <span style="background: <?php echo Projeto::corStatus($h['projeto_status']); ?>22; color: <?php echo Projeto::corStatus($h['projeto_status']); ?>; padding: 1px 6px; border-radius: 10px; font-size: 0.7rem; margin-left: 4px;">
                                                <?php echo Projeto::labelStatus($h['projeto_status']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <div style="font-weight: 600; color: var(--text-main); margin-bottom: 4px;">
                                    <?php echo date('d/m/Y \à\s H:i', strtotime($h['data_salvo'])); ?>
                                </div>
                                <div style="font-size: 0.85rem; color: var(--text-muted);">
                                    <?php echo $h['perguntas_ok']; ?> de <?php echo $h['total_perguntas']; ?> itens conformes
                                    &nbsp;·&nbsp;
                                    <?php echo $h['pontos_obtidos']; ?>/<?php echo $h['total_pontos']; ?> pontos
                                </div>
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
                            <button
                                onclick="abrirModalDeletar(<?php echo $h['id']; ?>, 0)"
                                style="background: transparent; border: 2px solid var(--error); color: var(--error); padding: 8px 16px; border-radius: 10px; font-size: 0.85rem; cursor: pointer; font-weight: 600;">
                                <i class="fas fa-trash"></i> Remover
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- ══ PAGINAÇÃO ══ -->
        <?php if ($totalPaginas > 1): ?>
        <nav style="display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 40px; flex-wrap: wrap;">

            <?php
                // Monta a query string preservando os filtros
                $queryBase = http_build_query(array_filter([
                    'projeto_id' => $filtros['projeto_id'] ?? '',
                    'status'     => $filtros['status']     ?? '',
                ]));
                $sep = $queryBase ? '&' : '';
            ?>

            <!-- Anterior -->
            <?php if ($paginaAtual > 1): ?>
                <a href="/historico?<?php echo $queryBase . $sep; ?>page=<?php echo $paginaAtual - 1; ?>"
                   style="display: flex; align-items: center; justify-content: center; width: 38px; height: 38px; border: 2px solid var(--border); border-radius: 8px; color: var(--text-muted); text-decoration: none; font-size: 0.85rem; transition: all 0.2s;"
                   onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)';"
                   onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-muted)';">
                    <i class="fas fa-chevron-left"></i>
                </a>
            <?php endif; ?>

            <!-- Páginas -->
            <?php
                $inicio = max(1, $paginaAtual - 2);
                $fim    = min($totalPaginas, $paginaAtual + 2);
            ?>

            <?php if ($inicio > 1): ?>
                <a href="/historico?<?php echo $queryBase . $sep; ?>page=1"
                   style="display: flex; align-items: center; justify-content: center; width: 38px; height: 38px; border: 2px solid var(--border); border-radius: 8px; color: var(--text-muted); text-decoration: none; font-size: 0.85rem;">1</a>
                <?php if ($inicio > 2): ?>
                    <span style="color: var(--text-muted); padding: 0 4px;">...</span>
                <?php endif; ?>
            <?php endif; ?>

            <?php for ($i = $inicio; $i <= $fim; $i++): ?>
                <?php if ($i === $paginaAtual): ?>
                    <span style="display: flex; align-items: center; justify-content: center; width: 38px; height: 38px; background: var(--primary); color: white; border-radius: 8px; font-size: 0.85rem; font-weight: 700;">
                        <?php echo $i; ?>
                    </span>
                <?php else: ?>
                    <a href="/historico?<?php echo $queryBase . $sep; ?>page=<?php echo $i; ?>"
                       style="display: flex; align-items: center; justify-content: center; width: 38px; height: 38px; border: 2px solid var(--border); border-radius: 8px; color: var(--text-muted); text-decoration: none; font-size: 0.85rem; transition: all 0.2s;"
                       onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)';"
                       onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-muted)';">
                        <?php echo $i; ?>
                    </a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($fim < $totalPaginas): ?>
                <?php if ($fim < $totalPaginas - 1): ?>
                    <span style="color: var(--text-muted); padding: 0 4px;">...</span>
                <?php endif; ?>
                <a href="/historico?<?php echo $queryBase . $sep; ?>page=<?php echo $totalPaginas; ?>"
                   style="display: flex; align-items: center; justify-content: center; width: 38px; height: 38px; border: 2px solid var(--border); border-radius: 8px; color: var(--text-muted); text-decoration: none; font-size: 0.85rem;">
                    <?php echo $totalPaginas; ?>
                </a>
            <?php endif; ?>

            <!-- Próximo -->
            <?php if ($paginaAtual < $totalPaginas): ?>
                <a href="/historico?<?php echo $queryBase . $sep; ?>page=<?php echo $paginaAtual + 1; ?>"
                   style="display: flex; align-items: center; justify-content: center; width: 38px; height: 38px; border: 2px solid var(--border); border-radius: 8px; color: var(--text-muted); text-decoration: none; font-size: 0.85rem; transition: all 0.2s;"
                   onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)';"
                   onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-muted)';">
                    <i class="fas fa-chevron-right"></i>
                </a>
            <?php endif; ?>

        </nav>

        <div style="text-align: center; margin-top: 12px;">
            <span style="font-size: 0.8rem; color: var(--text-muted);">
                Página <?php echo $paginaAtual; ?> de <?php echo $totalPaginas; ?>
            </span>
        </div>
        <?php endif; ?>

        <?php else: ?>
            <!-- Nenhum resultado com filtros -->
            <div class="card-pergunta" style="text-align: center; padding: 50px 40px;">
                <i class="fas fa-search" style="font-size: 2.5rem; color: var(--border); margin-bottom: 15px; display: block;"></i>
                <h3 style="color: var(--text-muted); margin-bottom: 10px;">Nenhum diagnóstico encontrado</h3>
                <p style="color: var(--text-muted); margin-bottom: 25px;">Tente ajustar os filtros ou <a href="/historico" style="color: var(--primary); font-weight: 600; text-decoration: none;">limpar os filtros</a>.</p>
            </div>
        <?php endif; ?>

    <?php endif; ?>
</main>

<!-- Modal de confirmação de deleção -->
<div id="modalDeletar" class="modal-overlay" style="display: none;">
    <div class="modal-content" style="max-width: 420px; text-align: center;">
        <div style="font-size: 3rem; margin-bottom: 15px;">🗑️</div>
        <h3 style="color: var(--text-main); margin-bottom: 10px;">Remover diagnóstico?</h3>
        <p style="color: var(--text-muted); margin-bottom: 30px;">
            Esta ação não pode ser desfeita. O diagnóstico será removido permanentemente do histórico.
        </p>
        <form id="formDeletar" action="/historico/deletar" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <input type="hidden" name="historico_id" id="deletar-historico-id">
            <input type="hidden" name="projeto_id"   id="deletar-projeto-id">
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button type="button" class="btn-secondary" onclick="fecharModalDeletar()">Cancelar</button>
                <button type="submit"
                        style="background: var(--error); color: white; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 0.95rem;">
                    <i class="fas fa-trash"></i> Sim, remover
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@media (max-width: 768px) {
    #formFiltros > div {
        flex-direction: column !important;
    }
    #formFiltros > div > div {
        min-width: unset !important;
        width: 100% !important;
    }
}
</style>

<script>
function abrirModalDeletar(historicoId, projetoId) {
    document.getElementById('deletar-historico-id').value = historicoId;
    document.getElementById('deletar-projeto-id').value   = projetoId;
    document.getElementById('modalDeletar').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function fecharModalDeletar() {
    document.getElementById('modalDeletar').style.display = 'none';
    document.body.style.overflow = 'auto';
}

window.addEventListener('click', function(e) {
    if (e.target.id === 'modalDeletar') fecharModalDeletar();
});
</script>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>