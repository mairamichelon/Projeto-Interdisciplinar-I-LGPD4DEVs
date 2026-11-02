<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<main class="container content-area" style="padding-top: 15px; padding-bottom: 60px;">

    <!-- Cabeçalho Unificado -->
    <div class="page-header-unified">
        <div class="page-header-content">
            <div class="page-header-title-row">
                <span class="page-header-badge">
                    <i class="fas fa-info-circle"></i> Institucional
                </span>
                <h1>Sobre o Projeto LGPD4DEVS</h1>
            </div>
            <p class="page-header-subtitle">
                Conheça a iniciativa criada para apoiar desenvolvedores na aplicação prática de Privacy by Design e do Artigo 14 da LGPD.
            </p>
        </div>
    </div>

    <div class="institucional-layout-grid">
        
        <!-- Sidebar de Navegação Institucional -->
        <aside class="institucional-sidebar-sticky">
            <div class="institucional-nav-card">
                <a href="/sobre" class="institucional-nav-item active">
                    <i class="fas fa-info-circle" style="color: var(--primary);"></i> Sobre o Projeto
                </a>
                <a href="/contato" class="institucional-nav-item">
                    <i class="fas fa-envelope"></i> Contato e Suporte
                </a>
                <a href="/termos" class="institucional-nav-item">
                    <i class="fas fa-file-contract"></i> Termos de Uso
                </a>
                <a href="/privacidade" class="institucional-nav-item">
                    <i class="fas fa-user-shield"></i> Política de Privacidade
                </a>
            </div>

            <!-- Card Rápido de Ação -->
            <div style="background: linear-gradient(135deg, #EFF6FF 0%, #FFFFFF 100%); border: 1.5px solid #BFDBFE; border-radius: 16px; padding: 20px; box-shadow: 0 4px 12px rgba(0, 102, 255, 0.05);">
                <div style="font-size: 0.82rem; font-weight: 700; color: var(--primary); margin-bottom: 6px; text-transform: uppercase;">
                    <i class="fas fa-bolt"></i> Ação Rápida
                </div>
                <h4 style="font-size: 0.98rem; margin: 0 0 8px; color: var(--text-main);">Avalie seu software agora</h4>
                <p style="font-size: 0.82rem; color: var(--text-muted); margin: 0 0 14px; line-height: 1.5;">
                    Utilize nosso checklist interativo e receba um diagnóstico com score e orientações em menos de 3 minutos.
                </p>
                <a href="/checklist" class="btn-primary" style="font-size: 0.82rem; padding: 8px 14px; min-width: unset; width: 100%; text-align: center; justify-content: center;">
                    <i class="fas fa-clipboard-check"></i> Fazer Checklist
                </a>
            </div>
        </aside>

        <!-- Conteúdo Principal -->
        <article class="institucional-content-card">
            
            <div style="line-height: 1.8; color: var(--text-muted); font-size: 1.02rem;">
                
                <h2 style="font-size: 1.35rem; color: var(--text-main); margin-bottom: 16px; font-weight: 700;">
                    Propósito e Missão
                </h2>

                <p style="font-size: 1.1rem; color: var(--text-main); margin-bottom: 22px; line-height: 1.7;">
                    Somos <strong>Maira Michelon e Leonardo</strong>, estudantes de Gestão da Tecnologia da Informação e desenvolvedores de software, unidos pelo propósito de tornar o ambiente digital mais seguro para crianças e adolescentes.
                </p>

                <p style="margin-bottom: 20px;">
                    O <strong>LGPD4DEVS</strong> foi concebido com o objetivo de apoiar desenvolvedores de software na aplicação prática da LGPD em projetos voltados ao público infantojuvenil. Acreditamos que a proteção de dados deve começar desde a concepção da arquitetura e das interfaces, fundamentando-se no princípio de <strong>Privacy by Design</strong>.
                </p>

                <p style="margin-bottom: 20px;">
                    Por meio de checklists estruturados, trilhas educacionais, materiais de apoio e orientações técnicas, buscamos simplificar a adequação à legislação e auxiliar na construção de sistemas mais seguros, éticos e responsáveis.
                </p>

                <p style="margin-bottom: 25px;">
                    Nosso compromisso é transformar requisitos legais complexos em soluções práticas de engenharia de software, contribuindo para que desenvolvedores criem tecnologias que respeitem a privacidade e protejam integralmente os dados de crianças e adolescentes.
                </p>

                <!-- Destaque -->
                <div style="background-color: #F8FAFC; padding: 22px 25px; border-radius: 12px; border-left: 4px solid var(--primary); margin: 30px 0; border: 1px solid #E2E8F0; border-left: 4px solid var(--primary);">
                    <p style="font-style: italic; margin-bottom: 0; color: var(--text-main); font-weight: 500;">
                        "A proteção de dados é um direito fundamental e uma responsabilidade técnica de todo desenvolvedor."
                    </p>
                </div>

                <h3 style="font-size: 1.15rem; color: var(--text-main); margin: 25px 0 12px; font-weight: 700;">
                    Pilares da Plataforma
                </h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; margin-top: 15px;">
                    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; padding: 16px; border-radius: 12px;">
                        <div style="color: var(--primary); font-size: 1.2rem; margin-bottom: 6px;"><i class="fas fa-check-circle"></i></div>
                        <strong style="color: var(--text-main); font-size: 0.92rem; display: block; margin-bottom: 4px;">Checklist Objetivo</strong>
                        <span style="font-size: 0.82rem; color: var(--text-muted); line-height: 1.4; display: block;">Perguntas práticas e diretas para avaliar o código e o fluxo de dados.</span>
                    </div>

                    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; padding: 16px; border-radius: 12px;">
                        <div style="color: var(--secondary); font-size: 1.2rem; margin-bottom: 6px;"><i class="fas fa-chart-line"></i></div>
                        <strong style="color: var(--text-main); font-size: 0.92rem; display: block; margin-bottom: 4px;">Score Inteligente</strong>
                        <span style="font-size: 0.82rem; color: var(--text-muted); line-height: 1.4; display: block;">Diagnóstico percentual imediato identificando pontos de vulnerabilidade legal.</span>
                    </div>

                    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; padding: 16px; border-radius: 12px;">
                        <div style="color: #764ba2; font-size: 1.2rem; margin-bottom: 6px;"><i class="fas fa-graduation-cap"></i></div>
                        <strong style="color: var(--text-main); font-size: 0.92rem; display: block; margin-bottom: 4px;">Conteúdo Prático</strong>
                        <span style="font-size: 0.82rem; color: var(--text-muted); line-height: 1.4; display: block;">Vídeos curtos e guias oficiais para orientar a implementação rápida.</span>
                    </div>
                </div>

            </div>

        </article>

    </div>

</main>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
