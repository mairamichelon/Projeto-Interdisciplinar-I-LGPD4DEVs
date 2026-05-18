<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<?php if (isset($_SESSION['user_id'])): ?>
    <?php
        $projetoModel = new Projeto();
        $projetosDash = $projetoModel->buscarPorUsuario((int)$_SESSION['user_id']);
        $primeiroNome = htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]);
    ?>

    <!-- Dashboard logado -->
    <section class="hero" style="padding: 40px 0 30px;">
        <div class="container">
            <div class="hero-content">
                <h1 style="font-size: 2.2rem;">Olá, <?php echo $primeiroNome; ?>! 👋</h1>
                <p style="margin-bottom: 1.5rem;">
                    Gerencie seus projetos e acompanhe a evolução de conformidade com a LGPD.
                </p>
                <div class="cta-buttons">
                    <a href="/checklist" class="btn-primary">
                        <i class="fas fa-clipboard-check"></i> Fazer Novo Diagnóstico
                    </a>
                    <a href="/projetos" class="btn-secondary">
                        <i class="fas fa-folder"></i> Meus Projetos
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content-area" style="padding-top: 20px;">
        <div class="container">

            <?php if (!empty($projetosDash)): ?>
                <div class="checklist-header" style="text-align: left; margin-bottom: 25px;">
                    <h2 style="font-size: 1.4rem;">Seus Projetos</h2>
                </div>

                <!-- Grid responsivo para mobile -->
                <div class="home-projetos-grid" style="margin-bottom: 50px;">
                    <?php foreach (array_slice($projetosDash, 0, 3) as $p): ?>
                        <div class="card-material" style="border-top: 4px solid <?php echo Projeto::corStatus($p['status']); ?>;">
                            <div style="margin-bottom: 10px;">
                                <span style="display:inline-block; background:<?php echo Projeto::corStatus($p['status']); ?>22; color:<?php echo Projeto::corStatus($p['status']); ?>; padding:2px 8px; border-radius:20px; font-size:0.72rem; font-weight:700; margin-bottom:6px;">
                                    <?php echo Projeto::labelStatus($p['status']); ?>
                                </span>
                                <h3 style="margin:0 0 4px; font-size:1rem;"><?php echo htmlspecialchars($p['nome']); ?></h3>
                                <span style="font-size:0.78rem; color:var(--text-muted);">
                                    👥 <?php echo Projeto::labelPublicoAlvo($p['publico_alvo']); ?>
                                </span>
                            </div>

                            <?php if ($p['ultimo_percentual'] !== null): ?>
                                <div style="background:#F8FAFC; border-radius:8px; padding:10px; margin-bottom:12px;">
                                    <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                                        <span style="font-size:0.78rem; color:var(--text-muted);">Último diagnóstico</span>
                                        <span style="font-weight:700; font-size:0.9rem; color:<?php echo ($p['ultimo_percentual'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>;">
                                            <?php echo $p['ultimo_percentual']; ?>%
                                        </span>
                                    </div>
                                    <div style="background:#E2E8F0; height:5px; border-radius:10px; overflow:hidden;">
                                        <div style="width:<?php echo $p['ultimo_percentual']; ?>%; height:100%; background:<?php echo ($p['ultimo_percentual'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>;"></div>
                                    </div>
                                    <div style="font-size:0.72rem; color:var(--text-muted); margin-top:4px;">
                                        <?php echo $p['total_diagnosticos']; ?> diagnóstico(s)
                                    </div>
                                </div>
                            <?php else: ?>
                                <div style="background:#F8FAFC; border-radius:8px; padding:10px; margin-bottom:12px; text-align:center;">
                                    <span style="font-size:0.8rem; color:var(--text-muted);">Nenhum diagnóstico ainda</span>
                                </div>
                            <?php endif; ?>

                            <a href="/projetos/detalhe?id=<?php echo $p['id']; ?>" class="btn-secondary" style="width:100%; text-align:center; font-size:0.85rem; padding:8px; min-width:unset;">
                                Ver Projeto
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (count($projetosDash) > 3): ?>
                    <div style="text-align:center; margin-top:-30px; margin-bottom:40px;">
                        <a href="/projetos" style="color:var(--primary); font-weight:600; text-decoration:none; font-size:0.9rem;">
                            Ver todos os <?php echo count($projetosDash); ?> projetos →
                        </a>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="card-pergunta" style="text-align:center; padding:50px 40px; margin-bottom:40px;">
                    <i class="fas fa-folder-open" style="font-size:2.5rem; color:var(--border); margin-bottom:15px; display:block;"></i>
                    <h3 style="color:var(--text-muted); margin-bottom:10px;">Nenhum projeto ainda</h3>
                    <p style="color:var(--text-muted); margin-bottom:25px;">
                        Faça seu primeiro diagnóstico e salve o resultado vinculando a um projeto.
                    </p>
                    <a href="/checklist" class="btn-primary">Começar Agora</a>
                </div>
            <?php endif; ?>

            <!-- Cards informativos -->
            <div class="checklist-header" style="text-align:left; margin-bottom:25px;">
                <h2 style="font-size:1.4rem;">Como o LGPD4DEVS ajuda você?</h2>
            </div>
            <div class="materiais-grid">
                <div class="card-material">
                    <span class="badge-categoria">01. Diagnóstico</span>
                    <h3>Checklist Técnico</h3>
                    <p>Responda perguntas objetivas sobre o tratamento de dados e receba um relatório instantâneo de conformidade.</p>
                </div>
                <div class="card-material">
                    <span class="badge-categoria">02. Inteligência</span>
                    <h3>Score de Conformidade</h3>
                    <p>Saiba exatamente qual o percentual de adequação e identifique os pontos críticos por projeto.</p>
                </div>
                <div class="card-material">
                    <span class="badge-categoria">03. Apoio</span>
                    <h3>Materiais Sugeridos</h3>
                    <p>Receba guias e artigos baseados nas falhas detectadas para corrigir seu código rapidamente.</p>
                </div>
            </div>
        </div>
    </section>

<?php else: ?>
    <!-- Home visitante -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>LGPD na Prática para Desenvolvedores</h1>
                <p>
                    O <strong>LGPD4DEVS</strong> é uma ferramenta prática para desenvolvedores implementarem o <strong>Privacy by Design</strong> em projetos voltados a crianças e adolescentes.
                </p>
                <div class="cta-wrapper">
                    <div class="cta-buttons">
                        <a href="/cadastro" class="btn-primary">Criar Minha Conta</a>
                        <a href="/checklist" class="btn-secondary">Fazer Checklist Rápido</a>
                    </div>
                    <div class="hero-note">
                        <i class="fas fa-info-circle"></i>
                        <span><strong>Nota:</strong> Salve o histórico e acesse relatórios detalhados criando sua conta gratuita.</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content-area">
        <div class="container">
            <div class="checklist-header">
                <h2>Como o LGPD4DEVS ajuda você?</h2>
                <p>Simplificamos a conformidade jurídica para o fluxo de desenvolvimento técnico.</p>
            </div>
            <div class="materiais-grid">
                <div class="card-material">
                    <span class="badge-categoria">01. Diagnóstico</span>
                    <h3>Checklist Técnico</h3>
                    <p>Responda perguntas objetivas sobre o tratamento de dados no seu sistema e receba um relatório instantâneo de conformidade.</p>
                </div>
                <div class="card-material">
                    <span class="badge-categoria">02. Inteligência</span>
                    <h3>Score de Conformidade</h3>
                    <p>Saiba exatamente qual o seu percentual de adequação à lei e identifique quais pontos são críticos para a segurança de menores.</p>
                </div>
                <div class="card-material">
                    <span class="badge-categoria">03. Apoio</span>
                    <h3>Materiais Sugeridos</h3>
                    <p>Receba guias e artigos baseados nas falhas detectadas para corrigir seu código rapidamente.</p>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<style>
/* Grid de projetos na home — 3 colunas desktop, 1 coluna mobile */
.home-projetos-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

@media (max-width: 900px) {
    .home-projetos-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 600px) {
    .home-projetos-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
}
</style>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>