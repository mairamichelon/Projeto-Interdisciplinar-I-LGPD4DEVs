<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<main class="container content-area" style="padding-top: 15px; padding-bottom: 60px;">

    <!-- Cabeçalho Unificado -->
    <div class="page-header-unified">
        <div class="page-header-content">
            <div class="page-header-title-row">
                <span class="page-header-badge">
                    <i class="fas fa-user-shield"></i> Privacidade e Dados
                </span>
                <h1>Política de Privacidade</h1>
            </div>
            <p class="page-header-subtitle">
                Transparência total sobre como tratamos seus dados pessoais em estrita conformidade com a Lei Geral de Proteção de Dados (LGPD).
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
                <a href="/contato" class="institucional-nav-item">
                    <i class="fas fa-envelope"></i> Contato e Suporte
                </a>
                <a href="/termos" class="institucional-nav-item">
                    <i class="fas fa-file-contract"></i> Termos de Uso
                </a>
                <a href="/privacidade" class="institucional-nav-item active">
                    <i class="fas fa-user-shield" style="color: var(--primary);"></i> Política de Privacidade
                </a>
            </div>

            <!-- Card Destaque -->
            <div style="background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 16px; padding: 18px;">
                <div style="font-size: 0.78rem; font-weight: 700; color: #166534; margin-bottom: 6px; text-transform: uppercase;">
                    <i class="fas fa-lock"></i> Seus Direitos
                </div>
                <p style="font-size: 0.8rem; color: #15803D; margin: 0; line-height: 1.5;">
                    Seus dados nunca são vendidos ou compartilhados com terceiros para fins comerciais.
                </p>
            </div>
        </aside>

        <!-- Conteúdo Principal -->
        <article class="institucional-content-card">
            
            <p style="margin-bottom: 22px; font-size: 1.05rem; color: var(--text-main); line-height: 1.7;">
                O <strong>LGPD4DEVS</strong> assume o compromisso de proteger a privacidade e os dados pessoais de seus usuários, em estrita observância à Lei Geral de Proteção de Dados Pessoais (Lei nº 13.709/2018 - LGPD), aplicando os princípios da finalidade, necessidade, transparência e segurança.
            </p>

            <div style="color: var(--text-muted); line-height: 1.8; font-size: 0.96rem;">

                <h3 style="color: var(--text-main); font-size: 1.15rem; margin-top: 25px; margin-bottom: 8px; font-weight: 700;">1. Escopo e Coleta de Dados</h3>
                <p>
                    Coletamos apenas as informações estritamente necessárias para a prestação dos serviços e funcionalidades da plataforma:
                </p>
                <ul style="margin-left: 22px; margin-top: 8px; margin-bottom: 12px;">
                    <li><strong>Uso sem Cadastro (Visitante):</strong> O usuário pode navegar pelos conteúdos educativos, materiais e realizar o diagnóstico no Checklist de forma totalmente anônima, sem fornecer qualquer dado de identificação pessoal.</li>
                    <li><strong>Cadastro Básico de Conta:</strong> Para criar uma conta, salvar projetos e armazenar o histórico de diagnósticos, solicitamos apenas <strong>nome completo, endereço de e-mail e senha</strong>. As senhas são gravadas exclusivamente de forma criptografada por meio de algoritmos de hash seguros (Bcrypt).</li>
                    <li><strong>Dados de Projetos e Diagnósticos:</strong> Quando autenticado, o usuário pode cadastrar nomes e descrições de projetos e armazenar os diagnósticos preenchidos para geração de scores e relatórios evolutivos.</li>
                </ul>

                <h3 style="color: var(--text-main); font-size: 1.15rem; margin-top: 25px; margin-bottom: 8px; font-weight: 700;">2. Reprodução de Conteúdos em Vídeo e Terceiros</h3>
                <p>
                    A seção de conteúdos educacionais disponibiliza vídeos didáticos incorporados por meio de player externo. Para resguardar ao máximo a sua privacidade:
                </p>
                <ul style="margin-left: 22px; margin-top: 8px; margin-bottom: 12px;">
                    <li><strong>Modo de Privacidade Aprimorada (YouTube No-Cookie):</strong> Integramos os vídeos através do domínio <code>youtube-nocookie.com</code>. Esse recurso impede que o YouTube/Google armazene cookies de personalização ou rastreamento no seu navegador enquanto você apenas visualiza a página da trilha.</li>
                    <li><strong>Interação com o Player:</strong> Ao clicar no botão de reprodução (play), uma conexão técnica direta é estabelecida com os servidores do reprodutor do YouTube, momento no qual o provedor de vídeo pode coletar dados de tráfego (como endereço IP e telemetria de visualização), sob as diretrizes da Política de Privacidade do Google.</li>
                </ul>

                <h3 style="color: var(--text-main); font-size: 1.15rem; margin-top: 25px; margin-bottom: 8px; font-weight: 700;">3. Finalidade e Base Legal do Tratamento</h3>
                <p>
                    Os dados pessoais coletados destinam-se única e exclusivamente a:
                </p>
                <ul style="margin-left: 22px; margin-top: 8px; margin-bottom: 12px;">
                    <li>Autenticação e controle de acesso individualizado à plataforma;</li>
                    <li>Armazenamento e exibição do histórico de conformidade dos projetos pertencentes ao próprio usuário;</li>
                    <li>Geração de relatórios técnicos de diagnóstico em formato PDF.</li>
                </ul>
                <p>
                    O tratamento está fundamentado no <strong>consentimento expresso</strong> do titular e na <strong>execução de procedimentos relativos aos serviços do site</strong>, conforme o Artigo 7º, incisos I e V da LGPD.
                </p>

                <h3 style="color: var(--text-main); font-size: 1.15rem; margin-top: 25px; margin-bottom: 8px; font-weight: 700;">4. Armazenamento e Segurança</h3>
                <p>
                    Adotamos medidas técnicas e organizacionais rígidas (incluindo HTTPS com certificado SSL/TLS, proteção CSRF e segregação de credenciais via variáveis de ambiente) para resguardar a integridade e sigilo dos dados. 
                    <strong>Não comercializamos, não compartilhamos e não cedemos dados pessoais</strong> para terceiros, exceto em virtude de requisição judicial ou determinação legal expressa da ANPD.
                </p>

                <h3 style="color: var(--text-main); font-size: 1.15rem; margin-top: 25px; margin-bottom: 8px; font-weight: 700;">5. Direitos dos Titulares de Dados</h3>
                <p>
                    Em consonância com o Artigo 18 da LGPD, você pode solicitar a qualquer momento a confirmação de tratamento, acesso, correção ou exclusão definitiva de sua conta e dados através dos canais de suporte em <a href="/contato" style="color: var(--primary); font-weight: 600;">/contato</a>.
                </p>

            </div>

        </article>

    </div>

</main>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
