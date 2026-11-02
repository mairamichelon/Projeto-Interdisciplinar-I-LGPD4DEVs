<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<div class="container">
    <div class="checklist-section" style="max-width: 850px; margin-top: 40px; margin-bottom: 50px; padding: 40px;">
        
        <div style="text-align: center; margin-bottom: 30px;">
            <span class="badge-categoria" style="margin-bottom: 10px;">
                <i class="fas fa-user-shield"></i> Proteção de Dados Pessoais
            </span>
            <h1 style="color: var(--primary); font-size: 2.2rem; margin-bottom: 12px;">Política de Privacidade</h1>
            <p style="color: var(--text-muted); font-size: 0.95rem;">
                Última atualização: <?php echo date('d/m/Y'); ?> — Versão 2.0
            </p>
        </div>

        <p style="margin-bottom: 25px; font-size: 1.05rem; color: var(--text-muted); text-align: justify; line-height: 1.7;">
            O <strong>LGPD4DEVS</strong> assume o compromisso de proteger a privacidade e os dados pessoais de seus usuários, em estrita observância à Lei Geral de Proteção de Dados Pessoais (Lei nº 13.709/2018 - LGPD), aplicando os princípios da finalidade, necessidade, transparência e segurança.
        </p>

        <div style="text-align: justify; color: var(--text-muted); line-height: 1.8; font-size: 1rem;">

            <h3 style="color: var(--primary); margin-top: 25px; margin-bottom: 10px;">1. Escopo e Coleta de Dados</h3>
            <p>
                Coletamos apenas as informações estritamente necessárias para a prestação dos serviços e funcionalidades da plataforma:
            </p>
            <ul style="margin-left: 20px; margin-top: 10px; margin-bottom: 10px;">
                <li><strong>Uso sem Cadastro (Visitante):</strong> O usuário pode navegar pelos conteúdos educativos, materiais e realizar o diagnóstico no Checklist de forma totalmente anônima, sem fornecer qualquer dado de identificação pessoal.</li>
                <li><strong>Cadastro Básico de Conta:</strong> Para criar uma conta, salvar projetos e armazenar o histórico de diagnósticos, solicitamos apenas <strong>nome completo, endereço de e-mail e senha</strong>. As senhas são gravadas exclusivamente de forma criptografada por meio de algoritmos de hash seguros (Bcrypt).</li>
                <li><strong>Dados de Projetos e Diagnósticos:</strong> Quando autenticado, o usuário pode cadastrar nomes e descrições de projetos e armazenar os diagnósticos preenchidos para geração de scores e gráficos evolutivos de conformidade.</li>
            </ul>

            <h3 style="color: var(--primary); margin-top: 25px; margin-bottom: 10px;">2. Reprodução de Conteúdos em Vídeo e Terceiros</h3>
            <p>
                A seção de conteúdos educacionais disponibiliza vídeos didáticos incorporados por meio de player externo. Para resguardar ao máximo a sua privacidade:
            </p>
            <ul style="margin-left: 20px; margin-top: 10px; margin-bottom: 10px;">
                <li><strong>Modo de Privacidade Aprimorada (YouTube No-Cookie):</strong> Integramos os vídeos através do domínio <code>youtube-nocookie.com</code>. Esse recurso impede que o YouTube/Google armazene cookies de personalização ou rastreamento no seu navegador enquanto você apenas visualiza a página da trilha.</li>
                <li><strong>Interação com o Player:</strong> Ao clicar no botão de reprodução (play), uma conexão técnica direta é estabelecida com os servidores do reprodutor do YouTube, momento no qual o provedor de vídeo pode coletar dados de tráfego (como endereço IP e telemetria de visualização), sob as diretrizes da Política de Privacidade do Google.</li>
            </ul>

            <h3 style="color: var(--primary); margin-top: 25px; margin-bottom: 10px;">3. Finalidade e Base Legal do Tratamento</h3>
            <p>
                Os dados pessoais coletados destinam-se única e exclusivamente a:
            </p>
            <ul style="margin-left: 20px; margin-top: 10px; margin-bottom: 10px;">
                <li>Autenticação e controle de acesso individualizado à plataforma;</li>
                <li>Armazenamento e exibição do histórico de conformidade dos projetos pertencentes ao próprio usuário;</li>
                <li>Geração de relatórios técnicos de diagnóstico em formato PDF.</li>
            </ul>
            <p>
                O tratamento está fundamentado no <strong>consentimento expresso</strong> do titular e na <strong>execução de procedimentos relativos aos serviços do site</strong>, conforme o Artigo 7º, incisos I e V da LGPD.
            </p>

            <h3 style="color: var(--primary); margin-top: 25px; margin-bottom: 10px;">4. Armazenamento, Segurança e Não Compartilhamento</h3>
            <p>
                Adotamos medidas técnicas e organizacionais rígidas (incluindo HTTPS com certificado SSL/TLS, proteção CSRF e segregação de credenciais via variáveis de ambiente) para resguardar a integridade e sigilo dos dados. 
                <strong>Não comercializamos, não compartilhamos e não cedemos dados pessoais</strong> para terceiros, exceto em virtude de requisição judicial ou determinação legal expressa da ANPD.
            </p>

            <h3 style="color: var(--primary); margin-top: 25px; margin-bottom: 10px;">5. Direitos dos Titulares de Dados</h3>
            <p>
                Em conformidade com o Artigo 18 da LGPD, você tem o direito garantido de, a qualquer momento:
            </p>
            <ul style="margin-left: 20px; margin-top: 10px; margin-bottom: 10px;">
                <li>Confirmar a existência de tratamento e acessar seus dados cadastrais;</li>
                <li>Editar ou atualizar informações incorretas diretamente pelo painel do seu perfil;</li>
                <li>Solicitar a exclusão definitiva da sua conta e de todo o histórico de diagnósticos armazenados;</li>
                <li>Revogar seu consentimento e exportar seus dados.</li>
            </ul>

            <h3 style="color: var(--primary); margin-top: 25px; margin-bottom: 10px;">6. Caráter Educativo e Isenção de Responsabilidade</h3>
            <p>
                Reiteramos que esta plataforma possui caráter estritamente educativo e técnico. As recomendações geradas não constituem consultoria jurídica formal. Para detalhes completos sobre as limitações legais, consulte os nossos <a href="/termos" style="color: var(--primary); font-weight: 600;">Termos de Uso</a>.
            </p>

            <h3 style="color: var(--primary); margin-top: 25px; margin-bottom: 10px;">7. Contato e Atendimento</h3>
            <p>
                Para exercer seus direitos como titular de dados ou esclarecer dúvidas sobre esta Política, entre em contato conosco através dos canais disponibilizados na página de <a href="/contato" style="color: var(--primary); font-weight: 600;">Contato</a>.
            </p>
        </div>

        <div style="background-color: #F1F5F9; padding: 20px; border-radius: 8px; margin-top: 40px; text-align: center; border: 1px solid #E2E8F0;">
            <p style="font-weight: 600; color: var(--primary); margin: 0;">
                Ao utilizar a plataforma e criar uma conta, o usuário declara ciência e aceitação integral desta Política de Privacidade.
            </p>
        </div>

        <div style="text-align: center; margin-top: 30px; display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
            <a href="/" class="btn-secondary">Voltar ao Início</a>
            <a href="/termos" class="btn-secondary">Ver Termos de Uso</a>
        </div>
    </div>
</div>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
