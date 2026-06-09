<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<main class="container content-area">

    <!-- Cabeçalho -->
    <div class="checklist-header" style="margin-bottom: 30px;">
        <h1>Meu Perfil</h1>
        <p>Suas informações pessoais e histórico de uso da plataforma.</p>
    </div>

    <!-- Card de informações do usuário -->
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px; margin-bottom: 40px;">

        <!-- Avatar + dados básicos -->
        <div class="card-material" style="text-align: center; padding: 35px 25px;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), #0052CC); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 2rem; color: white; font-weight: 700;">
                <?php echo strtoupper(mb_substr($_SESSION['user_name'], 0, 1)); ?>
            </div>

            <h2 style="margin: 0 0 6px; font-size: 1.3rem; color: var(--text-main);">
                <?php echo htmlspecialchars($usuario['nome']); ?>
            </h2>

            <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0 0 15px; word-break: break-all;">
                <?php echo htmlspecialchars($usuario['email']); ?>
            </p>

            <span style="background: <?php echo ($usuario['perfil'] === 'admin') ? 'linear-gradient(135deg, #667eea, #764ba2)' : '#EFF6FF'; ?>; color: <?php echo ($usuario['perfil'] === 'admin') ? 'white' : 'var(--primary)'; ?>; padding: 5px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; display: inline-block; margin-bottom: 20px;">
                <?php echo ($usuario['perfil'] === 'admin') ? '⚙️ Administrador' : '👤 Usuário'; ?>
            </span>

            <div style="border-top: 1px solid var(--border); padding-top: 18px; text-align: left;">
                <div style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 8px;">
                    <i class="fas fa-calendar-alt" style="color: var(--primary); width: 16px;"></i>
                    &nbsp;Membro desde
                </div>
                <div style="font-weight: 600; color: var(--text-main); font-size: 0.92rem;">
                    <?php echo date('d/m/Y', strtotime($usuario['data_cadastro'])); ?>
                </div>
            </div>
        </div>

        <!-- Estatísticas -->
        <div style="display: flex; flex-direction: column; gap: 20px;">

            <!-- Cards de métricas -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
                <div class="card-material" style="text-align: center; padding: 22px 15px;">
                    <div style="font-size: 2rem; font-weight: 700; color: var(--primary); line-height: 1; margin-bottom: 6px;">
                        <?php echo $stats['total_projetos']; ?>
                    </div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">
                        <i class="fas fa-folder"></i> Projetos
                    </div>
                </div>

                <div class="card-material" style="text-align: center; padding: 22px 15px;">
                    <div style="font-size: 2rem; font-weight: 700; color: var(--secondary); line-height: 1; margin-bottom: 6px;">
                        <?php echo $stats['total_diagnosticos']; ?>
                    </div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">
                        <i class="fas fa-clipboard-check"></i> Diagnósticos
                    </div>
                </div>

                <div class="card-material" style="text-align: center; padding: 22px 15px;">
                    <div style="font-size: 2rem; font-weight: 700; color: <?php echo ($stats['media_conformidade'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>; line-height: 1; margin-bottom: 6px;">
                        <?php echo $stats['media_conformidade'] !== null ? $stats['media_conformidade'] . '%' : '—'; ?>
                    </div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">
                        <i class="fas fa-chart-line"></i> Média Geral
                    </div>
                </div>
            </div>

            <!-- Melhor resultado -->
            <?php if ($stats['melhor_resultado'] !== null): ?>
            <div class="card-material" style="padding: 20px 25px;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <div style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 4px;">
                            <i class="fas fa-trophy" style="color: #F59E0B;"></i> Melhor Resultado
                        </div>
                        <div style="font-size: 1.6rem; font-weight: 700; color: var(--secondary);">
                            <?php echo $stats['melhor_resultado']; ?>%
                        </div>
                    </div>
                    <?php if ($stats['ultimo_resultado'] !== null): ?>
                    <div style="text-align: right;">
                        <div style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 4px;">
                            <i class="fas fa-clock"></i> Último Diagnóstico
                        </div>
                        <div style="font-size: 1.6rem; font-weight: 700; color: <?php echo ($stats['ultimo_resultado'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>;">
                            <?php echo $stats['ultimo_resultado']; ?>%
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">
                            <?php echo $stats['ultima_data'] ? date('d/m/Y', strtotime($stats['ultima_data'])) : ''; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if ($stats['total_diagnosticos'] > 0): ?>
                <div style="margin-top: 15px;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.78rem; color: var(--text-muted); margin-bottom: 5px;">
                        <span>Progresso médio</span>
                        <span><?php echo $stats['media_conformidade']; ?>%</span>
                    </div>
                    <div style="background: #E2E8F0; height: 8px; border-radius: 10px; overflow: hidden;">
                        <div style="width: <?php echo $stats['media_conformidade']; ?>%; height: 100%; background: <?php echo ($stats['media_conformidade'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>;"></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php else: ?>
            <div class="card-material" style="padding: 20px 25px; text-align: center;">
                <p style="color: var(--text-muted); margin: 0; font-size: 0.9rem;">
                    Nenhum diagnóstico realizado ainda.
                    <a href="/checklist" style="color: var(--primary); font-weight: 600; text-decoration: none;">Fazer checklist →</a>
                </p>
            </div>
            <?php endif; ?>

            <!-- Ações rápidas -->
            <div class="card-material" style="padding: 20px 25px;">
                <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-main); margin-bottom: 14px;">
                    <i class="fas fa-bolt" style="color: var(--primary);"></i> Ações Rápidas
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="/checklist" class="btn-primary" style="font-size: 0.85rem; padding: 8px 16px; min-width: unset;">
                        <i class="fas fa-clipboard-check"></i> Novo Diagnóstico
                    </a>
                    <a href="/projetos" class="btn-secondary" style="font-size: 0.85rem; padding: 8px 16px; min-width: unset;">
                        <i class="fas fa-folder"></i> Meus Projetos
                    </a>
                    <a href="/historico" class="btn-secondary" style="font-size: 0.85rem; padding: 8px 16px; min-width: unset;">
                        <i class="fas fa-history"></i> Histórico
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos diagnósticos -->
    <?php if (!empty($ultimosDiagnosticos)): ?>
    <div style="margin-bottom: 40px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
            <h2 style="margin: 0; font-size: 1.2rem;">
                <i class="fas fa-history" style="color: var(--primary);"></i> Diagnósticos Recentes
            </h2>
            <a href="/historico" style="font-size: 0.85rem; color: var(--primary); text-decoration: none; font-weight: 600;">
                Ver todos →
            </a>
        </div>

        <div style="display: flex; flex-direction: column; gap: 12px;">
            <?php foreach ($ultimosDiagnosticos as $h): ?>
                <div class="card-pergunta" style="border-left: 5px solid <?php echo ($h['percentual'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>; padding: 18px 22px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">

                        <div style="display: flex; align-items: center; gap: 18px;">
                            <div style="text-align: center; min-width: 65px;">
                                <div style="font-size: 1.6rem; font-weight: 700; color: <?php echo ($h['percentual'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>; line-height: 1;">
                                    <?php echo $h['percentual']; ?>%
                                </div>
                                <div style="font-size: 0.7rem; color: var(--text-muted);">conformidade</div>
                            </div>

                            <div>
                                <?php if (!empty($h['projeto_nome'])): ?>
                                    <div style="font-size: 0.78rem; color: var(--primary); font-weight: 600; margin-bottom: 3px;">
                                        <i class="fas fa-folder"></i> <?php echo htmlspecialchars($h['projeto_nome']); ?>
                                    </div>
                                <?php endif; ?>
                                <div style="font-weight: 600; color: var(--text-main); font-size: 0.88rem; margin-bottom: 3px;">
                                    <?php echo date('d/m/Y \à\s H:i', strtotime($h['data_salvo'])); ?>
                                </div>
                                <div style="font-size: 0.78rem; color: var(--text-muted);">
                                    <?php echo $h['perguntas_ok']; ?> de <?php echo $h['total_perguntas']; ?> itens conformes
                                    &nbsp;·&nbsp; <?php echo $h['pontos_obtidos']; ?>/<?php echo $h['total_pontos']; ?> pontos
                                </div>
                            </div>
                        </div>

                        <a href="/historico/detalhe?id=<?php echo $h['id']; ?>" class="btn-secondary" style="font-size: 0.82rem; padding: 7px 14px; min-width: unset;">
                            <i class="fas fa-eye"></i> Ver Detalhes
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</main>

<style>
@media (max-width: 768px) {
    .perfil-grid {
        grid-template-columns: 1fr !important;
    }
}

@media (max-width: 600px) {
    .stats-grid-3 {
        grid-template-columns: 1fr 1fr !important;
    }
}
</style>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>