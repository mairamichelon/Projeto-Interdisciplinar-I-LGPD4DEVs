<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<main class="container content-area" style="padding-top: 15px; padding-bottom: 60px;">

    <!-- Cabeçalho Unificado -->
    <div class="page-header-unified">
        <div class="page-header-content">
            <div class="page-header-title-row">
                <span class="page-header-badge">
                    <i class="fas fa-file-contract"></i> Termos Legais
                </span>
                <h1>Termos de Uso da Plataforma</h1>
            </div>
            <p class="page-header-subtitle">
                Diretrizes de uso, isenções de responsabilidade jurídica e condições de utilização dos serviços do LGPD4DEVS.
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
                <a href="/termos" class="institucional-nav-item active">
                    <i class="fas fa-file-contract" style="color: var(--primary);"></i> Termos de Uso
                </a>
                <a href="/privacidade" class="institucional-nav-item">
                    <i class="fas fa-user-shield"></i> Política de Privacidade
                </a>
            </div>

            <!-- Card Destaque -->
            <div style="background: #FEF2F2; border: 1px solid #FECACA; border-radius: 16px; padding: 18px;">
                <div style="font-size: 0.78rem; font-weight: 700; color: #DC2626; margin-bottom: 6px; text-transform: uppercase;">
                    <i class="fas fa-balance-scale"></i> Isenção Jurídica
                </div>
                <p style="font-size: 0.8rem; color: #991B1B; margin: 0; line-height: 1.5;">
                    O LGPD4DEVS é uma ferramenta estritamente técnica e orientativa. Não substitui assessoria jurídica formal.
                </p>
            </div>
        </aside>

        <!-- Conteúdo Principal -->
        <article class="institucional-content-card">
            
            <!-- Aviso em destaque de isenção de responsabilidade jurídica -->
            <div class="aviso-educativo" style="margin-bottom: 25px;">
                <div class="aviso-card" style="border-left: 4px solid #EF4444; background: #FEF2F2; padding: 16px 20px; border-radius: 12px; display: flex; align-items: flex-start; gap: 14px;">
                    <div style="background: #FEE2E2; color: #DC2626; width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0;">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <div style="font-size: 0.9rem; line-height: 1.6;">
                        <strong style="color: #991B1B; display: block; margin-bottom: 3px;">Isenção Expressa de Responsabilidade Jurídica:</strong>
                        <span style="color: #7F1D1D;">
                            O <strong>LGPD4DEVS</strong> é uma ferramenta estritamente educativa e técnica. O site e seus mantenedores <strong>não prestam consultoria jurídica, advocatícia, assessoria regulatória ou auditoria legal oficial</strong>. O uso do sistema não substitui a contratação de profissionais especializados em Direito Digital ou a atuação de um DPO formal.
                        </span>
                    </div>
                </div>
            </div>

            <div style="color: var(--text-muted); line-height: 1.8; font-size: 0.96rem;">

                <h3 style="color: var(--text-main); font-size: 1.15rem; margin-top: 25px; margin-bottom: 8px; font-weight: 700;">1. Aceitação dos Termos</h3>
                <p>
                    Ao acessar, navegar ou utilizar qualquer recurso do site <strong>LGPD4DEVS</strong> (incluindo o checklist interativo, conteúdos educacionais, biblioteca de materiais e relatórios de projeto), você declara que leu, compreendeu e concorda integralmente com estes Termos de Uso e com a nossa <a href="/privacidade" style="color: var(--primary); font-weight: 600;">Política de Privacidade</a>. Caso não concorde com qualquer disposição aqui estabelecida, solicitamos que não utilize a plataforma.
                </p>

                <h3 style="color: var(--text-main); font-size: 1.15rem; margin-top: 25px; margin-bottom: 8px; font-weight: 700;">2. Natureza e Finalidade do Serviço</h3>
                <p>
                    O LGPD4DEVS é um projeto voltado ao apoio técnico à comunidade de tecnologia e software. Sua finalidade é estritamente educativa e de fomento técnico, visando disseminar os princípios de <em>Privacy by Design</em> e boas práticas de engenharia de software voltadas à proteção de dados de crianças e adolescentes (Lei nº 13.709/2018 - LGPD e legislação correlata).
                </p>

                <h3 style="color: var(--text-main); font-size: 1.15rem; margin-top: 25px; margin-bottom: 8px; font-weight: 700;">3. Limitação de Responsabilidade e Não Vinculação</h3>
                <p>
                    As pontuações, percentuais, relatórios de diagnóstico e materiais sugeridos pela plataforma são de caráter meramente orientativo e referencial. Por isso:
                </p>
                <ul style="margin-left: 22px; margin-top: 8px; margin-bottom: 12px;">
                    <li><strong>Inexistência de Certificação Oficial:</strong> A realização do checklist ou obtenção de pontuação de 100% não representa selo de homologação, auditoria ou atestado oficial de conformidade perante a Autoridade Nacional de Proteção de Dados (ANPD) ou qualquer outro órgão fiscalizador.</li>
                    <li><strong>Dependência de Dados Inseridos:</strong> As análises geradas dependem exclusivamente das respostas auto-declaradas pelo próprio usuário. O sistema não analisa código-fonte, tráfego de rede ou bancos de dados reais.</li>
                    <li><strong>Isenção por Decisões Comerciais ou Litígios:</strong> Os mantenedores do LGPD4DEVS eximem-se de qualquer responsabilidade civil, administrativa ou judicial por sanções, multas, vazamentos de dados, danos diretos ou indiretos sofridos por projetos ou empresas que utilizem este site como referência.</li>
                </ul>

                <h3 style="color: var(--text-main); font-size: 1.15rem; margin-top: 25px; margin-bottom: 8px; font-weight: 700;">4. Responsabilidade do Usuário e Desenvolvedor</h3>
                <p>
                    O usuário compromete-se a utilizar a plataforma de forma ética, manter o sigilo de suas credenciais de login e assumir a inteira responsabilidade técnica e legal pelas decisões tomadas na arquitetura e desenvolvimento de seus próprios sistemas.
                </p>

                <h3 style="color: var(--text-main); font-size: 1.15rem; margin-top: 25px; margin-bottom: 8px; font-weight: 700;">5. Reprodutores de Terceiros (YouTube)</h3>
                <p>
                    A plataforma disponibiliza vídeos educacionais incorporados sob o domínio de privacidade aprimorada <code>youtube-nocookie.com</code>, de modo que nenhum cookie de personalização é gravado pelo YouTube antes do usuário iniciar a reprodução.
                </p>

                <h3 style="color: var(--text-main); font-size: 1.15rem; margin-top: 25px; margin-bottom: 8px; font-weight: 700;">6. Legislação Aplicável e Foro</h3>
                <p>
                    Estes Termos são regidos e interpretados de acordo com a legislação da República Federativa do Brasil, em especial o Marco Civil da Internet (Lei nº 12.965/2014) e a LGPD (Lei nº 13.709/2018).
                </p>

            </div>

        </article>

    </div>

</main>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
