<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<main class="container content-area" style="padding-top: 15px; padding-bottom: 60px;">

    <!-- Cabeçalho Unificado -->
    <div class="page-header-unified">
        <div class="page-header-content">
            <div class="page-header-title-row">
                <span class="page-header-badge">
                    <i class="fas fa-clipboard-check"></i> Diagnóstico Técnico
                </span>
                <h1>Checklist de Conformidade LGPD</h1>
            </div>
            <p class="page-header-subtitle">
                Avalie os requisitos de privacidade e conformidade com o <strong>Artigo 14 da LGPD</strong> (proteção de crianças e adolescentes) no seu sistema.
            </p>
        </div>
    </div>

    <?php if (count($perguntas) > 0): ?>

        <div class="checklist-layout-grid">
            
            <!-- Coluna Esquerda: Painel de Progresso e Ações Fixas -->
            <aside class="checklist-sidebar-sticky">
                <div class="checklist-progress-card">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <h3 style="font-size: 0.95rem; margin: 0; color: var(--text-main); font-weight: 700;">
                            <i class="fas fa-tasks" style="color: var(--primary); margin-right: 6px;"></i> Progresso
                        </h3>
                        <span id="porcentagemRespondidas" style="font-size: 0.78rem; font-weight: 700; color: var(--primary); background: #EFF6FF; padding: 2px 8px; border-radius: 12px;">
                            0%
                        </span>
                    </div>

                    <div style="font-size: 1.7rem; font-weight: 700; color: var(--text-main); margin: 10px 0 2px; line-height: 1;">
                        <span id="contadorRespondidas" style="color: var(--primary);">0</span>
                        <span style="font-size: 1rem; color: var(--text-muted); font-weight: 500;">/ <?php echo count($perguntas); ?></span>
                    </div>
                    <p style="font-size: 0.78rem; color: var(--text-muted); margin: 0 0 10px;">questões respondidas</p>

                    <div class="checklist-progress-bar-bg">
                        <div class="checklist-progress-bar-fill" id="barraProgresso"></div>
                    </div>

                    <div style="background: #F8FAFC; border-radius: 10px; padding: 12px; border: 1px solid #E2E8F0; font-size: 0.8rem; color: var(--text-muted); line-height: 1.5; margin-top: 14px;">
                        <i class="fas fa-lightbulb" style="color: #EAB308;"></i> 
                        Responda com precisão para calcular seu <strong>Score de Conformidade</strong> e receber recomendações técnicas sob medida.
                    </div>

                    <button type="submit" form="formChecklist" class="btn-save" style="margin-top: 18px; padding: 14px; font-size: 0.92rem; width: 100%;">
                        <i class="fas fa-check-circle"></i> Gerar Relatório
                    </button>
                </div>
            </aside>

            <!-- Coluna Direita: Lista de Perguntas -->
            <section class="checklist-questions-list">
                <form id="formChecklist" action="/checklist" method="POST" class="checklist-form">
                    <!-- Token CSRF -->
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">

                    <?php foreach ($perguntas as $idx => $p): ?>
                        <div class="card-pergunta" style="margin-bottom: 20px;">
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                                <span class="badge-categoria" style="margin-bottom: 0;">
                                    <?php echo htmlspecialchars($p['categoria']); ?>
                                </span>
                                <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">
                                    Item <?php echo str_pad($idx + 1, 2, '0', STR_PAD_LEFT); ?>
                                </span>
                            </div>

                            <p style="font-size: 1.08rem; font-weight: 600; margin: 14px 0 16px; color: var(--text-main); line-height: 1.45;">
                                <?php echo htmlspecialchars($p['texto']); ?>
                            </p>

                            <div class="opcoes-resposta" style="margin-top: 0;">
                                <label class="radio-label">
                                    <input type="radio" name="respostas[<?php echo $p['id']; ?>]" value="1" required onchange="atualizarProgressoChecklist()">
                                    <span class="radio-design"><i class="fas fa-check"></i> Sim</span>
                                </label>

                                <label class="radio-label">
                                    <input type="radio" name="respostas[<?php echo $p['id']; ?>]" value="0" required onchange="atualizarProgressoChecklist()">
                                    <span class="radio-design"><i class="fas fa-times"></i> Não</span>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div style="text-align: center; margin-top: 30px; padding: 25px; background: white; border-radius: 16px; border: 1px solid #E2E8F0;">
                        <p style="color: var(--text-muted); margin-bottom: 15px; font-size: 0.92rem;">
                            Certifique-se de responder todas as questões para gerar seu relatório completo.
                        </p>
                        <button type="submit" class="btn-save" style="max-width: 420px; margin: 0 auto;">
                            <i class="fas fa-check-circle"></i> Finalizar e Gerar Relatório de Conformidade
                        </button>
                    </div>

                </form>
            </section>

        </div>

        <script>
        const totalPerguntas = <?php echo count($perguntas); ?>;

        function atualizarProgressoChecklist() {
            const form = document.getElementById('formChecklist');
            if (!form) return;

            // Agrupa os radios marcados por nome
            const checkedRadios = form.querySelectorAll('input[type="radio"]:checked');
            const totalRespondidas = checkedRadios.length;
            const pct = Math.round((totalRespondidas / totalPerguntas) * 100);

            document.getElementById('contadorRespondidas').innerText = totalRespondidas;
            document.getElementById('porcentagemRespondidas').innerText = pct + '%';
            document.getElementById('barraProgresso').style.width = pct + '%';
        }

        document.addEventListener('DOMContentLoaded', atualizarProgressoChecklist);
        </script>

    <?php else: ?>
        <div class="card-pergunta" style="text-align: center; padding: 50px 30px;">
            <p style="font-size: 1.1rem; color: var(--text-main);">Nenhuma pergunta de conformidade foi encontrada no sistema.</p>
            <p style="font-size: 0.9rem; color: var(--text-muted);">Verifique se a tabela 'perguntas' foi populada no banco de dados.</p>
        </div>
    <?php endif; ?>

</main>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
