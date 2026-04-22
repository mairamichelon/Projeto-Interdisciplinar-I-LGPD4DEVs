<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>LGPD na Prática para Desenvolvedores</h1>

            <p>
                O <strong>LGPD4DEVS</strong> é uma ferramenta prática para desenvolvedores implementarem o <strong>Privacy by Design</strong> em projetos voltados a crianças e adolescentes. Através de <strong>checklists estruturados</strong>, materiais especializados e orientações técnicas, simplificamos cada etapa da adequação à legislação, garantindo a proteção de dados desde a concepção da aplicação e auxiliando na construção de sistemas mais <strong>seguros, éticos e responsáveis</strong>.
            </p>

            <div class="cta-wrapper">
                <div class="cta-buttons">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <a href="/cadastro" class="btn-primary">Criar Minha Conta</a>
                        <a href="/checklist" class="btn-secondary">Fazer Checklist Rápido</a>
                    <?php else: ?>
                        <a href="/checklist" class="btn-primary">Ir para o Checklist</a>
                        <a href="/materiais" class="btn-secondary">Ver Materiais</a>
                    <?php endif; ?>
                </div>

                <?php if (!isset($_SESSION['user_id'])): ?>
                    <div class="hero-note">
                        <i class="fas fa-info-circle"></i>
                        <span><strong>Nota:</strong> Salve o histórico e acesse relatórios detalhados criando sua conta gratuita.</span>
                    </div>
                <?php endif; ?>
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
                <p>Receba guias e artigos baseados nas falhas detectadas para corrigir seu código rapidamente e garantir a privacidade dos usuários.</p>
            </div>
        </div>
    </div>
</section>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
