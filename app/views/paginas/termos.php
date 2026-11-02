<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<div class="container">
    <div class="checklist-section" style="max-width: 850px; margin-top: 40px; margin-bottom: 50px; padding: 40px;">
        
        <div style="text-align: center; margin-bottom: 30px;">
            <span class="badge-categoria" style="margin-bottom: 10px;">
                <i class="fas fa-file-contract"></i> Termos e Condições
            </span>
            <h1 style="color: var(--primary); font-size: 2.2rem; margin-bottom: 12px;">Termos de Uso</h1>
            <p style="color: var(--text-muted); font-size: 0.95rem;">
                Última atualização: <?php echo date('d/m/Y'); ?> — Versão 1.0
            </p>
        </div>

        <!-- Aviso em destaque de isenção de responsabilidade jurídica -->
        <div class="aviso-educativo" style="margin-bottom: 30px;">
            <div class="aviso-card" style="border-left-color: #EF4444; background: #FEF2F2;">
                <div class="aviso-icon" style="background: #FEE2E2; color: #DC2626;">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <div class="aviso-text">
                    <strong style="color: #991B1B;">Isenção Expressa de Responsabilidade Jurídica:</strong>
                    <span style="color: #7F1D1D;">
                        O <strong>LGPD4DEVS</strong> é uma ferramenta acadêmica e educativa. O site, seus autores e a instituição de ensino (IFPR)
                        <strong>não prestam consultoria jurídica, advocatícia, assessoria regulatória ou auditoria legal oficial</strong>.
                        O uso do sistema não substitui a contratação de profissionais especializados em Direito Digital ou a atuação formal de um DPO (Encarregado pelo Tratamento de Dados Pessoais).
                    </span>
                </div>
            </div>
        </div>

        <div style="text-align: justify; color: var(--text-muted); line-height: 1.8; font-size: 1rem;">

            <h3 style="color: var(--primary); margin-top: 25px; margin-bottom: 10px;">1. Aceitação dos Termos</h3>
            <p>
                Ao acessar, navegar ou utilizar qualquer recurso do site <strong>LGPD4DEVS</strong> (incluindo o checklist interativo, conteúdos educacionais, biblioteca de materiais e relatórios de projeto), você declara que leu, compreendeu e concorda integralmente com estes Termos de Uso e com a nossa <a href="/privacidade" style="color: var(--primary); font-weight: 600;">Política de Privacidade</a>. Caso não concorde com qualquer disposição aqui estabelecida, solicitamos que não utilize a plataforma.
            </p>

            <h3 style="color: var(--primary); margin-top: 25px; margin-bottom: 10px;">2. Natureza e Finalidade do Serviço</h3>
            <p>
                O LGPD4DEVS é um projeto de pesquisa aplicada e extensão universitária desenvolvido no âmbito do <strong>Instituto Federal do Paraná (IFPR - Campus Pinhais)</strong>. Sua finalidade é estritamente educativa e de fomento técnico, visando disseminar os princípios de <em>Privacy by Design</em> e boas práticas de engenharia de software voltadas à proteção de dados de crianças e adolescentes (Lei nº 13.709/2018 - LGPD e legislação correlata).
            </p>

            <h3 style="color: var(--primary); margin-top: 25px; margin-bottom: 10px;">3. Limitação de Responsabilidade e Não Vinculação</h3>
            <p>
                As pontuações, percentuais, relatórios de diagnóstico e materiais sugeridos pela plataforma são de caráter meramente orientativo e referencial. Por isso:
            </p>
            <ul style="margin-left: 20px; margin-top: 10px; margin-bottom: 10px;">
                <li><strong>Inexistência de Certificação Oficial:</strong> A realização do checklist ou obtenção de pontuação de 100% não representa selo de homologação, auditoria ou atestado oficial de conformidade perante a Autoridade Nacional de Proteção de Dados (ANPD) ou qualquer outro órgão fiscalizador.</li>
                <li><strong>Dependência de Dados Inseridos:</strong> As análises geradas dependem exclusivamente das respostas auto-declaradas e fornecidas pelo próprio usuário. O sistema não analisa código-fonte, tráfego de rede ou bancos de dados reais.</li>
                <li><strong>Isenção por Decisões Comerciais ou Litígios:</strong> Os mantenedores do LGPD4DEVS e o IFPR eximem-se de qualquer responsabilidade civil, administrativa ou judicial por sanções, multas, vazamentos de dados, danos diretos ou indiretos sofridos por projetos, empresas ou softwares que utilizem este site como referência.</li>
            </ul>

            <h3 style="color: var(--primary); margin-top: 25px; margin-bottom: 10px;">4. Responsabilidade do Usuário e Desenvolvedor</h3>
            <p>
                O usuário compromete-se a:
            </p>
            <ul style="margin-left: 20px; margin-top: 10px; margin-bottom: 10px;">
                <li>Utilizar a plataforma de forma ética, legítima e sem violar direitos de terceiros;</li>
                <li>Manter a segurança e confidencialidade de suas credenciais de acesso (caso opte por criar conta);</li>
                <li>Assumir a inteira responsabilidade técnica e legal pelas decisões tomadas na arquitetura e desenvolvimento de seus próprios sistemas de software.</li>
            </ul>

            <h3 style="color: var(--primary); margin-top: 25px; margin-bottom: 10px;">5. Conteúdos Multimídia e Recursos de Terceiros</h3>
            <p>
                A plataforma disponibiliza vídeos educacionais incorporados por meio de reprodutores de terceiros (como YouTube no domínio de privacidade aprimorada <code>youtube-nocookie.com</code>) e referências a guias oficiais públicos da ANPD. A disponibilidade de servidores externos e serviços de terceiros independe da nossa infraestrutura.
            </p>

            <h3 style="color: var(--primary); margin-top: 25px; margin-bottom: 10px;">6. Propriedade Intelectual e Código Aberto</h3>
            <p>
                O código-fonte e a metodologia do LGPD4DEVS são desenvolvidos com fins acadêmicos e educacionais. As marcas, logotipos e textos didáticos pertencem a seus respectivos autores e ao IFPR, sendo vedada a comercialização não autorizada da plataforma ou apropriação indevida da autoria.
            </p>

            <h3 style="color: var(--primary); margin-top: 25px; margin-bottom: 10px;">7. Alterações destes Termos</h3>
            <p>
                Reservamo-nos o direito de modificar estes Termos de Uso periodicamente para refletir melhorias do projeto ou atualizações legislativas. As versões atualizadas serão publicadas nesta página com a respectiva data de revisão.
            </p>

            <h3 style="color: var(--primary); margin-top: 25px; margin-bottom: 10px;">8. Legislação Aplicável e Foro</h3>
            <p>
                Estes Termos são regidos e interpretados de acordo com a legislação da República Federativa do Brasil, em especial o Marco Civil da Internet (Lei nº 12.965/2014) e a LGPD (Lei nº 13.709/2018).
            </p>

        </div>

        <div style="background-color: #F1F5F9; padding: 20px; border-radius: 8px; margin-top: 40px; text-align: center; border: 1px solid #E2E8F0;">
            <p style="font-weight: 600; color: var(--primary); margin: 0;">
                O uso contínuo do LGPD4DEVS constitui plena ciência e concordância com todos os pontos destes Termos.
            </p>
        </div>

        <div style="text-align: center; margin-top: 30px; display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
            <a href="/" class="btn-secondary">Voltar ao Início</a>
            <a href="/checklist" class="btn-primary">Acessar Checklist</a>
        </div>

    </div>
</div>

<style>
.aviso-educativo {
    margin: 20px 0;
}
.aviso-card {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 18px 22px;
    border-radius: 12px;
    border-left: 5px solid #EF4444;
    background: #FEF2F2;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
}
.aviso-icon {
    background: #FEE2E2;
    color: #DC2626;
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}
.aviso-text {
    display: flex;
    flex-direction: column;
    gap: 4px;
    font-size: 0.92rem;
    line-height: 1.6;
}
@media (max-width: 768px) {
    .aviso-card {
        flex-direction: column;
        gap: 10px;
    }
}
</style>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
