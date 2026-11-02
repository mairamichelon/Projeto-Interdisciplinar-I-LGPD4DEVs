<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<main class="container content-area" style="padding-top: 15px; padding-bottom: 60px;">

    <!-- Cabeçalho Unificado -->
    <div class="page-header-unified">
        <div class="page-header-content">
            <div class="page-header-title-row">
                <span class="page-header-badge">
                    <i class="fas fa-envelope"></i> Fale Conosco
                </span>
                <h1>Contato e Suporte Técnico</h1>
            </div>
            <p class="page-header-subtitle">
                Dúvidas, sugestões, feedbacks ou propostas de melhoria? Fale diretamente com a equipe do projeto.
            </p>
        </div>
    </div>

    <div class="institucional-layout-grid">
        
        <!-- Sidebar de Navegação Institucional -->
        <aside class="institucional-sidebar-sticky">
            <div class="institucional-nav-card">
                <a href="/sobre" class="institucional-nav-item">
                    <i class="fas fa-info-circle"></i> Sobre o Projeto
                </a>
                <a href="/contato" class="institucional-nav-item active">
                    <i class="fas fa-envelope" style="color: var(--primary);"></i> Contato e Suporte
                </a>
                <a href="/termos" class="institucional-nav-item">
                    <i class="fas fa-file-contract"></i> Termos de Uso
                </a>
                <a href="/privacidade" class="institucional-nav-item">
                    <i class="fas fa-user-shield"></i> Política de Privacidade
                </a>
            </div>

            <!-- Card Dica -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 16px; padding: 20px;">
                <div style="font-size: 0.8rem; font-weight: 700; color: var(--primary); margin-bottom: 6px; text-transform: uppercase;">
                    <i class="fas fa-clock"></i> Tempo de Resposta
                </div>
                <p style="font-size: 0.82rem; color: var(--text-muted); margin: 0; line-height: 1.5;">
                    Respondemos habitualmente em até <strong>24 a 48 horas úteis</strong>.
                </p>
            </div>
        </aside>

        <!-- Conteúdo Principal -->
        <article class="institucional-content-card">
            
            <h2 style="font-size: 1.35rem; color: var(--text-main); margin-bottom: 12px; font-weight: 700;">
                Canais de Atendimento Direto
            </h2>

            <p style="color: var(--text-muted); font-size: 0.98rem; margin-bottom: 25px; line-height: 1.6;">
                Nossa comunicação é centralizada por correio eletrônico direto. Escolha um dos responsáveis ou envie uma cópia conjunta para agilizar o atendimento:
            </p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-bottom: 25px;">

                <div style="background-color: var(--white); border: 1px solid #E2E8F0; padding: 25px; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="width: 44px; height: 44px; border-radius: 50%; background: #EFF6FF; color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; margin-bottom: 15px;">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h3 style="color: var(--text-main); margin-bottom: 4px; font-size: 1.1rem;">Leonardo Henrique</h3>
                        <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 15px;">Desenvolvedor & Gestão de TI</p>
                    </div>

                    <a href="mailto:leonardo23hm@gmail.com" class="btn-primary" style="font-size: 0.85rem; padding: 8px 14px; min-width: unset; text-decoration: none; justify-content: center;">
                        <i class="fas fa-envelope"></i> leonardo23hm@gmail.com
                    </a>
                </div>

                <div style="background-color: var(--white); border: 1px solid #E2E8F0; padding: 25px; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="width: 44px; height: 44px; border-radius: 50%; background: #F0FDF4; color: var(--secondary); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; margin-bottom: 15px;">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <h3 style="color: var(--text-main); margin-bottom: 4px; font-size: 1.1rem;">Maira Michelon</h3>
                        <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 15px;">Desenvolvedora & Gestão de TI</p>
                    </div>

                    <a href="mailto:mairamichelon@gmail.com" class="btn-primary" style="font-size: 0.85rem; padding: 8px 14px; min-width: unset; text-decoration: none; justify-content: center;">
                        <i class="fas fa-envelope"></i> mairamichelon@gmail.com
                    </a>
                </div>

            </div>

            <div style="background-color: #FEF3C7; border: 1px solid #FCD34D; padding: 18px 22px; border-radius: 12px; margin-top: 25px; display: flex; align-items: flex-start; gap: 14px;">
                <i class="fas fa-info-circle" style="color: #D97706; font-size: 1.2rem; margin-top: 2px;"></i>
                <div style="font-size: 0.9rem; color: #92400E; line-height: 1.5;">
                    <strong>Dica de contato:</strong> Para garantir uma resposta mais rápida, recomendamos incluir ambos os e-mails como destinatários da sua mensagem.
                </div>
            </div>

        </article>

    </div>

</main>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
