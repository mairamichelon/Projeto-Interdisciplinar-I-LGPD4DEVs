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
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="/checklist" class="btn-primary" style="white-space: nowrap;">
                    <i class="fas fa-plus"></i> Novo Diagnóstico
                </a>
                <!-- Issue #30: Botão Exportar PDF do projeto -->
                <?php if (!empty($historicos)): ?>
                <button onclick="window.print()" class="btn-secondary no-print" style="white-space: nowrap;">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </button>
                <?php endif; ?>
            </div>
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

        <!-- ══ ISSUE #29: GRÁFICO DE EVOLUÇÃO ══ -->
        <?php if (count($historicos) >= 2): ?>
        <?php
            // Ordena cronologicamente para o gráfico (mais antigo → mais recente)
            $historicosCronologicos = array_reverse($historicos);
            $labelsDatas    = array_map(fn($h) => date('d/m/Y', strtotime($h['data_salvo'])), $historicosCronologicos);
            $valoresPercent = array_map(fn($h) => (int)$h['percentual'], $historicosCronologicos);
            $corGrafico     = ($ultimo >= 70) ? '#00CC66' : '#EF4444';
        ?>
        <div class="card-material" style="padding: 25px; margin-bottom: 35px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
                <h3 style="margin: 0; font-size: 1.05rem; color: var(--text-main);">
                    <i class="fas fa-chart-line" style="color: var(--primary);"></i> Evolução de Conformidade
                </h3>
                <div style="display: flex; gap: 15px; font-size: 0.78rem; color: var(--text-muted);">
                    <span><i class="fas fa-circle" style="color: #00CC66; font-size: 0.65rem;"></i> ≥ 70% (satisfatório)</span>
                    <span><i class="fas fa-circle" style="color: #EF4444; font-size: 0.65rem;"></i> &lt; 70% (atenção)</span>
                </div>
            </div>
            <div style="position: relative; height: 200px; width: 100%;">
                <canvas id="graficoEvolucao" style="width: 100%; height: 200px;"></canvas>
            </div>
        </div>
        <?php endif; ?>

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
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
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

    <!-- ══ ISSUE #30: SEÇÃO DE IMPRESSÃO DO PROJETO (oculta na tela) ══ -->
    <?php if (!empty($historicos)): ?>
    <div class="print-projeto-doc">
        <!-- Cabeçalho do documento -->
        <div class="print-cabecalho">
            <div>
                <div style="font-size: 1.4rem; font-weight: 800; color: #0066FF;">🛡 LGPD4DEVS</div>
                <div style="font-size: 0.75rem; color: #555; margin-top: 2px;">Inteligência em Conformidade</div>
            </div>
            <div style="text-align: right; font-size: 0.78rem; color: #555;">
                Exportado em <?php echo date('d/m/Y \à\s H:i'); ?><br>
                Relatório Completo de Projeto
            </div>
        </div>

        <div class="print-linha-azul"></div>

        <!-- Dados do projeto -->
        <div class="print-projeto-header">
            <h1 style="margin: 0 0 8px; font-size: 1.4rem; color: #0F172A;"><?php echo htmlspecialchars($projeto['nome']); ?></h1>
            <div style="display: flex; gap: 20px; flex-wrap: wrap; font-size: 0.82rem; color: #555; margin-bottom: 10px;">
                <span><strong>Público-alvo:</strong> <?php echo Projeto::labelPublicoAlvo($projeto['publico_alvo']); ?></span>
                <span><strong>Status:</strong> <?php echo Projeto::labelStatus($projeto['status']); ?></span>
                <?php if ($projeto['descricao']): ?>
                    <span><strong>Descrição:</strong> <?php echo htmlspecialchars($projeto['descricao']); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Resumo estatístico -->
        <div class="print-resumo-box">
            <div style="display: flex; gap: 30px; flex-wrap: wrap; justify-content: center; text-align: center;">
                <div>
                    <div style="font-size: 1.8rem; font-weight: 800; color: #0066FF;"><?php echo count($historicos); ?></div>
                    <div style="font-size: 0.75rem; color: #555;">Diagnósticos</div>
                </div>
                <div>
                    <div style="font-size: 1.8rem; font-weight: 800; color: <?php echo ($ultimo >= 70) ? '#00CC66' : '#EF4444'; ?>;"><?php echo $ultimo; ?>%</div>
                    <div style="font-size: 0.75rem; color: #555;">Último</div>
                </div>
                <div>
                    <div style="font-size: 1.8rem; font-weight: 800; color: #0066FF;"><?php echo $media; ?>%</div>
                    <div style="font-size: 0.75rem; color: #555;">Média Geral</div>
                </div>
                <div>
                    <div style="font-size: 1.8rem; font-weight: 800; color: #00CC66;"><?php echo $melhor; ?>%</div>
                    <div style="font-size: 0.75rem; color: #555;">Melhor Resultado</div>
                </div>
                <div>
                    <div style="font-size: 1.8rem; font-weight: 800; color: <?php echo ($evolucao >= 0) ? '#00CC66' : '#EF4444'; ?>;">
                        <?php echo ($evolucao >= 0 ? '+' : '') . $evolucao; ?>%
                    </div>
                    <div style="font-size: 0.75rem; color: #555;">Evolução</div>
                </div>
            </div>
        </div>

        <!-- Todos os diagnósticos detalhados -->
        <?php foreach (array_reverse($historicos) as $idx => $h): ?>
            <?php
                $dadosDiag = null;
                try {
                    $pdo = Database::getConnection();
                    $stmtDiag = $pdo->prepare("
                        SELECT * FROM historico_respostas
                        WHERE historico_id = ?
                        ORDER BY pergunta_id ASC
                    ");
                    $stmtDiag->execute([$h['id']]);
                    $dadosDiag = $stmtDiag->fetchAll();
                } catch (\Exception $e) {
                    $dadosDiag = [];
                }
                $corDiag = ($h['percentual'] >= 70) ? '#00CC66' : '#EF4444';
                $porCategPrint = [];
                foreach (($dadosDiag ?? []) as $r) {
                    $porCategPrint[$r['categoria_nome']][] = $r;
                }
            ?>
            <div class="print-diagnostico-bloco" style="border-color: <?php echo $corDiag; ?>;">
                <div class="print-diagnostico-header" style="border-left-color: <?php echo $corDiag; ?>;">
                    <div>
                        <div style="font-weight: 700; font-size: 0.9rem; color: #0F172A; margin-bottom: 3px;">
                            Diagnóstico #<?php echo $idx + 1; ?> — <?php echo date('d/m/Y \à\s H:i', strtotime($h['data_salvo'])); ?>
                        </div>
                        <div style="font-size: 0.75rem; color: #555;">
                            <?php echo $h['perguntas_ok']; ?> de <?php echo $h['total_perguntas']; ?> conformes
                            &nbsp;·&nbsp; <?php echo $h['pontos_obtidos']; ?>/<?php echo $h['total_pontos']; ?> pontos
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 1.6rem; font-weight: 800; color: <?php echo $corDiag; ?>; line-height: 1;">
                            <?php echo $h['percentual']; ?>%
                        </div>
                        <div style="font-size: 0.68rem; color: #555;">conformidade</div>
                    </div>
                </div>

                <!-- Barra de progresso -->
                <div style="background: #E2E8F0; height: 6px; border-radius: 4px; overflow: hidden; margin: 8px 0;">
                    <div style="width: <?php echo $h['percentual']; ?>%; height: 100%; background: <?php echo $corDiag; ?>;"></div>
                </div>

                <!-- Respostas agrupadas por categoria -->
                <?php if (!empty($porCategPrint)): ?>
                    <?php foreach ($porCategPrint as $categ => $itens): ?>
                        <div style="margin-top: 10px;">
                            <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: #0066FF; letter-spacing: 0.5px; margin-bottom: 5px;">
                                <?php echo htmlspecialchars($categ); ?>
                            </div>
                            <?php foreach ($itens as $item): ?>
                                <div style="display: flex; align-items: flex-start; gap: 8px; padding: 5px 0; border-bottom: 1px solid #F1F5F9; font-size: 0.78rem;">
                                    <span style="flex-shrink: 0; margin-top: 1px;"><?php echo ($item['resposta'] == 1) ? '✅' : '❌'; ?></span>
                                    <span style="flex: 1; color: #1E293B; line-height: 1.4;"><?php echo htmlspecialchars($item['pergunta_texto']); ?></span>
                                    <span style="color: #94A3B8; white-space: nowrap; font-size: 0.72rem;">Peso: <?php echo $item['pergunta_peso']; ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <div class="print-linha-azul" style="margin-top: 20px;"></div>
        <div style="font-size: 0.7rem; color: #888; text-align: center; margin-top: 8px;">
            LGPD4DEVS — Ferramenta de conformidade com a Lei Geral de Proteção de Dados (Lei nº 13.709/2018)
        </div>
    </div>
    <?php endif; ?>

</main>

<?php if (count($historicos) >= 2): ?>
<!-- Chart.js via CDN (sem dependências externas novas — Issue #29) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
// Dados serializados do PHP — consumidos diretamente da view
const dadosGrafico = {
    labels: <?php echo json_encode($labelsDatas); ?>,
    valores: <?php echo json_encode($valoresPercent); ?>,
    corFinal: <?php echo json_encode($corGrafico); ?>
};

(function() {
    const canvas = document.getElementById('graficoEvolucao');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    // Cor dinâmica por ponto (verde ≥ 70%, vermelho < 70%)
    const coresPontos = dadosGrafico.valores.map(v => v >= 70 ? '#00CC66' : '#EF4444');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dadosGrafico.labels,
            datasets: [{
                label: 'Conformidade (%)',
                data: dadosGrafico.valores,
                borderColor: dadosGrafico.corFinal,
                backgroundColor: dadosGrafico.corFinal + '18',
                borderWidth: 2.5,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: coresPontos,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0F172A',
                    titleColor: '#94A3B8',
                    bodyColor: '#fff',
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const v = context.parsed.y;
                            return `  ${v}% de conformidade`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: '#F1F5F9' },
                    ticks: {
                        color: '#94A3B8',
                        font: { size: 11 },
                        maxRotation: 45,
                    }
                },
                y: {
                    min: 0,
                    max: 100,
                    grid: { color: '#F1F5F9' },
                    ticks: {
                        color: '#94A3B8',
                        font: { size: 11 },
                        callback: v => v + '%',
                        stepSize: 20,
                    }
                }
            }
        }
    });
})();
</script>
<?php endif; ?>

<style>
/* ── Seção de impressão do projeto (oculta na tela) ── */
.print-projeto-doc { display: none; }

/* ── Impressão ── */
@media print {
    @page { margin: 1.2cm; size: A4; }
    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    a::after { content: none !important; }

    .no-print, .navbar, .main-footer,
    .card-pergunta, .card-material,
    h3, .btn-primary, .btn-secondary,
    .content-area > div:not(.print-projeto-doc) { display: none !important; }

    .print-projeto-doc { display: block !important; }

    body { background: white !important; font-family: 'Inter', sans-serif; }
}

.print-cabecalho {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.print-linha-azul {
    border: none;
    border-top: 2px solid #0066FF;
    margin: 10px 0;
}

.print-projeto-header {
    margin: 14px 0;
    padding: 12px 16px;
    background: #F8FAFC;
    border-radius: 8px;
    border: 1px solid #E2E8F0;
}

.print-resumo-box {
    border: 2px solid #0066FF;
    border-radius: 10px;
    padding: 16px 20px;
    margin: 14px 0;
}

.print-diagnostico-bloco {
    border: 1px solid;
    border-radius: 8px;
    padding: 14px 16px;
    margin: 14px 0;
    page-break-inside: avoid;
}

.print-diagnostico-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding-left: 10px;
    border-left: 4px solid;
    margin-bottom: 4px;
}
</style>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>