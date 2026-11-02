<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<?php
/**
 * Trilha Educacional LGPD4DEVS
 * 
 * IDs dos vídeos do YouTube:
 * Quando os vídeos estiverem gravados e hospedados no YouTube,
 * basta inserir os IDs correspondentes nas variáveis abaixo (ex: 'dQw4w9WgXcQ').
 * Enquanto estiverem como null ou vazios, a página exibe um placeholder profissional responsivo.
 */
$videos = [
    1 => [
        'id'               => 'video-1',
        'numero'           => '01',
        'titulo'           => 'O que é a LGPD e por que importa para quem faz software',
        'duracao'          => '3 min',
        'youtube_id'       => null, // Insira aqui o ID do YouTube quando gravar (ex: 'ABC123xyz')
        'objetivo'         => 'Compreender os fundamentos da Lei Geral de Proteção de Dados (Lei 13.709/2018), os princípios de Privacy by Design na engenharia de software e por que a segurança jurídica e técnica de um sistema começa na linha de código.',
        'resumo'           => 'A LGPD não é apenas um conjunto de regras jurídicas: ela define requisitos funcionais e não-funcionais que impactam diretamente a modelagem do banco de dados, os endpoints de API e a gestão de logs. Desenvolvedores precisam integrar a privacidade desde a concepção (Privacy by Design e Privacy by Default), garantindo minimização da coleta, finalidade clara e transparência.',
        'transcricao'      => 'A Lei Geral de Proteção de Dados (LGPD - Lei nº 13.709/2018) estabelece parâmetros claros sobre como informações de indivíduos devem ser tratadas em ambientes digitais. Para quem desenvolve software, a conformidade não é um checklist burocrático posterior ao deploy, mas sim uma decisão arquitetural. Ao projetar schemas de banco de dados, rotas de autenticação e fluxos de usuário, o desenvolvedor deve adotar o princípio de minimização de dados: coletar apenas o estritamente necessário para a funcionalidade contratada. Falhas de privacidade não decorrem apenas de vazamentos externos, mas de arquiteturas negligentes que armazenam dados desnecessários ou compartilham informações sem base legal adequada.',
        'proximo_id'       => 'video-2',
        'proximo_titulo'   => 'Crianças e adolescentes: o que o art. 14 muda',
    ],
    2 => [
        'id'               => 'video-2',
        'numero'           => '02',
        'titulo'           => 'Crianças e adolescentes: o que o art. 14 muda',
        'duracao'          => '4 min',
        'youtube_id'       => null, // Insira aqui o ID do YouTube quando gravar
        'objetivo'         => 'Identificar as exigências específicas do Artigo 14 da LGPD e do Estatuto Digital (Lei 15.211/2025), dominando as regras de melhor interesse da criança, consentimento parental verificável e a proibição de condicionamento de cadastro.',
        'resumo'           => 'O tratamento de dados de menores exige padrões especiais e rigorosos: o melhor interesse do menor deve nortear todo o sistema. Para crianças (até 12 anos incompletos), é obrigatório o consentimento específico em destaque de ao menos um dos pais ou responsável legal. Além disso, sistemas não podem condicionar a participação em jogos ou serviços à coleta excessiva de dados pessoais.',
        'transcricao'      => 'O Artigo 14 da LGPD inaugura um regime especial de salvaguardas para o público infantojuvenil. O parágrafo primeiro exige que o consentimento seja específico e em destaque, fornecido por pelo menos um dos pais ou pelo responsável legal. Do ponto de vista de desenvolvimento, isso significa desenhar fluxos de verificação de idade e mecanismos seguros de validação parental. Adicionalmente, o parágrafo quarto veda expressamente condicionar a participação da criança em jogos, aplicações de internet ou outras atividades ao fornecimento de dados pessoais além dos estritamente necessários para a atividade. Padrões persuasivos (dark patterns) e publicidade comportamental direcionada a menores violam frontalmente o princípio do melhor interesse.',
        'proximo_id'       => 'video-3',
        'proximo_titulo'   => 'Como usar o checklist do LGPD4DEVS e ler o resultado',
    ],
    3 => [
        'id'               => 'video-3',
        'numero'           => '03',
        'titulo'           => 'Como usar o checklist do LGPD4DEVS e ler o resultado',
        'duracao'          => '3 min',
        'youtube_id'       => null, // Insira aqui o ID do YouTube quando gravar
        'objetivo'         => 'Aprender a executar o checklist técnico do LGPD4DEVS, interpretar as métricas e o score percentual de conformidade, e utilizar as recomendações práticas da plataforma para corrigir vulnerabilidades no código.',
        'resumo'           => 'O LGPD4DEVS traduz a lei em perguntas técnicas objetivas com pesos ponderados. Ao concluir o diagnóstico, a plataforma calcula seu índice de conformidade, aponta falhas críticas prioritárias e conecta diretamente com guias e materiais de implementação prática para o seu time.',
        'transcricao'      => 'A ferramenta LGPD4DEVS foi desenvolvida especificamente para preencher a lacuna entre o jargão jurídico e a execução técnica. No checklist interativo, cada pergunta aborda um aspecto essencial: base legal, transparência da interface, consentimento parental, política de retenção de dados e segurança da informação. Ao responder às perguntas com a realidade do seu sistema, o motor de cálculo pondera o peso de cada item e gera um relatório detalhado. Você obtém uma classificação por nível de conformidade, visualiza exatamente onde estão as fragilidades e recebe recomendações de materiais e documentações oficiais da ANPD para adequar seu projeto de forma estruturada.',
        'proximo_id'       => null,
        'proximo_titulo'   => null,
    ],
];
?>

<div class="container educacional-page" style="padding-top: 30px; padding-bottom: 60px;">

    <!-- Cabeçalho Institucional / Educativo -->
    <div class="educacional-header text-center" style="max-width: 900px; margin: 0 auto 30px; text-align: center;">
        <span class="badge-categoria" style="margin-bottom: 12px;">
            <i class="fas fa-graduation-cap"></i> Conteúdo Educacional &amp; Extensão
        </span>
        <h1 style="font-size: 2.4rem; color: var(--text-main); margin-bottom: 15px; line-height: 1.25;">
            Trilha de Aprendizagem LGPD4DEVS
        </h1>
        <p style="font-size: 1.15rem; color: var(--text-muted); line-height: 1.6;">
            Disseminando conhecimento prático e acessível à comunidade de desenvolvimento e tecnologia.
            Aprenda a aplicar o <strong>Privacy by Design</strong> e os requisitos do <strong>Artigo 14 da LGPD</strong>
            através de vídeos curtos e objetivos preparados para desenvolvedores de software.
        </p>
    </div>

    <!-- Aviso Educativo Visível / Isenção Jurídica -->
    <div class="aviso-educativo" style="max-width: 950px; margin: 0 auto 40px;">
        <div class="aviso-card">
            <div class="aviso-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="aviso-text">
                <strong>Aviso de Natureza Educativa e Isenção de Responsabilidade:</strong>
                <span>
                    Os conteúdos, vídeos e checklists desta plataforma são estritamente educativos, produzidos no âmbito
                    acadêmico do Instituto Federal do Paraná (IFPR). Eles têm como objetivo orientar boas práticas técnicas de engenharia
                    e não constituem nem substituem consultoria jurídica, parecer legal ou auditoria oficial.
                    Consulte sempre profissionais jurídicos e o DPO (Encarregado) da sua organização.
                </span>
            </div>
        </div>
    </div>

    <!-- Índice Rápido da Trilha -->
    <div class="trilha-indice-card" style="max-width: 950px; margin: 0 auto 45px;">
        <h3 style="font-size: 1.1rem; color: var(--text-main); margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-route" style="color: var(--primary);"></i> Etapas da Trilha Rápida (2 a 4 minutos cada):
        </h3>
        <div class="trilha-passos-grid">
            <?php foreach ($videos as $num => $v): ?>
                <a href="#<?php echo $v['id']; ?>" class="trilha-passo-item">
                    <span class="passo-numero"><?php echo $v['numero']; ?></span>
                    <div class="passo-info">
                        <strong><?php echo htmlspecialchars($v['titulo']); ?></strong>
                        <span class="passo-duracao"><i class="far fa-clock"></i> ~<?php echo $v['duracao']; ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Seção da Trilha de Vídeos -->
    <section class="trilha-videos-section" style="max-width: 950px; margin: 0 auto 50px;">
        <div class="checklist-header" style="text-align: left; margin-bottom: 25px;">
            <h2 style="font-size: 1.6rem; color: var(--text-main);">Vídeos da Trilha</h2>
            <p style="color: var(--text-muted); font-size: 0.95rem;">Assista na sequência recomendada ou pule diretamente para o tema desejado.</p>
        </div>

        <div class="trilha-videos-list">
            <?php foreach ($videos as $num => $v): ?>
                <article class="card-video-modulo" id="<?php echo $v['id']; ?>">
                    
                    <!-- Topo do Card: Número, Duração e Título -->
                    <div class="video-card-top">
                        <div class="video-meta-tag">
                            <span class="modulo-badge">Módulo <?php echo $v['numero']; ?></span>
                            <span class="duracao-badge"><i class="far fa-clock"></i> <?php echo $v['duracao']; ?></span>
                        </div>
                        <h2 class="video-card-title"><?php echo htmlspecialchars($v['titulo']); ?></h2>
                    </div>

                    <!-- Objetivo de Aprendizagem -->
                    <div class="video-objetivo-box">
                        <i class="fas fa-bullseye objetivo-icon"></i>
                        <div>
                            <strong>Objetivo de aprendizagem:</strong>
                            <p><?php echo htmlspecialchars($v['objetivo']); ?></p>
                        </div>
                    </div>

                    <!-- Player de Vídeo Responsivo (YouTube no-cookie ou Placeholder amigável) -->
                    <div class="video-player-wrapper">
                        <?php if (!empty($v['youtube_id'])): ?>
                            <div class="video-responsive-container">
                                <iframe 
                                    src="https://www.youtube-nocookie.com/embed/<?php echo urlencode($v['youtube_id']); ?>?rel=0&modestbranding=1" 
                                    title="<?php echo htmlspecialchars($v['titulo']); ?>" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                    allowfullscreen
                                    loading="lazy">
                                </iframe>
                            </div>
                        <?php else: ?>
                            <!-- Placeholder profissional enquanto o vídeo está em gravação -->
                            <div class="video-placeholder-container">
                                <div class="placeholder-overlay">
                                    <div class="placeholder-play-circle">
                                        <i class="fas fa-play"></i>
                                    </div>
                                    <div class="placeholder-text-group">
                                        <span class="placeholder-badge">
                                            <i class="fas fa-video"></i> Vídeo em Produção
                                        </span>
                                        <h3><?php echo htmlspecialchars($v['titulo']); ?></h3>
                                        <p>Gravação em andamento com duração estimada de <strong><?php echo $v['duracao']; ?></strong>.</p>
                                        <span class="placeholder-hint">
                                            Player integrado via <code>youtube-nocookie.com</code> com foco em privacidade.
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Resumo Didático -->
                    <div class="video-resumo-box">
                        <h4 style="font-size: 1rem; color: var(--text-main); margin-bottom: 8px;">
                            <i class="fas fa-file-alt" style="color: var(--primary);"></i> Resumo do Conteúdo:
                        </h4>
                        <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; margin: 0;">
                            <?php echo htmlspecialchars($v['resumo']); ?>
                        </p>
                    </div>

                    <!-- Transcrição Expansível -->
                    <details class="transcricao-accordion">
                        <summary class="transcricao-summary">
                            <span><i class="fas fa-align-left"></i> Ver transcrição / texto completo da aula</span>
                            <i class="fas fa-chevron-down summary-arrow"></i>
                        </summary>
                        <div class="transcricao-conteudo">
                            <p><?php echo nl2br(htmlspecialchars($v['transcricao'])); ?></p>
                        </div>
                    </details>

                    <!-- Ações e Navegação Interna -->
                    <div class="video-card-actions">
                        <a href="/checklist" class="btn-checklist-link" title="Ir para o Checklist de conformidade">
                            <i class="fas fa-clipboard-check"></i> Aplicar no Checklist
                        </a>

                        <?php if (!empty($v['proximo_id'])): ?>
                            <a href="#<?php echo $v['proximo_id']; ?>" class="btn-proximo-video" title="Ir para o próximo vídeo da trilha">
                                Próximo Vídeo (<?php echo htmlspecialchars($v['proximo_titulo']); ?>) <i class="fas fa-arrow-down"></i>
                            </a>
                        <?php else: ?>
                            <a href="/checklist" class="btn-concluir-trilha" title="Checklist completo">
                                Concluir Trilha e Iniciar Checklist Completo <i class="fas fa-arrow-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>

                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Ficha Técnica Acadêmica e Referências -->
    <section class="ficha-tecnica-section" style="max-width: 950px; margin: 0 auto;">
        <div class="ficha-tecnica-card">
            
            <div class="ficha-header">
                <i class="fas fa-university ficha-main-icon"></i>
                <div>
                    <h2 style="font-size: 1.4rem; color: var(--primary); margin: 0 0 5px;">Ficha Técnica do Conteúdo</h2>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">
                        Projeto de Extensão e Pesquisa Aplicada — Instituto Federal do Paraná (IFPR)
                    </p>
                </div>
            </div>

            <div class="ficha-dados-grid">
                <div class="ficha-item">
                    <span class="ficha-label"><i class="fas fa-book"></i> Título da Obra:</span>
                    <span class="ficha-valor">LGPD4DEVS: LGPD na prática para desenvolvedores de software</span>
                </div>

                <div class="ficha-item">
                    <span class="ficha-label"><i class="fas fa-user-edit"></i> Autores:</span>
                    <span class="ficha-valor">Leonardo Henrique Máximo e Maira Aparecida Michelon</span>
                </div>

                <div class="ficha-item">
                    <span class="ficha-label"><i class="fas fa-user-tie"></i> Orientadoras:</span>
                    <span class="ficha-valor">Prof.ª Lauriana Paludo e Prof.ª Eliana Santos</span>
                </div>

                <div class="ficha-item">
                    <span class="ficha-label"><i class="fas fa-school"></i> Instituição &amp; Curso:</span>
                    <span class="ficha-valor">IFPR (Campus Pinhais) — Curso Superior de Tecnologia em Gestão da TI</span>
                </div>

                <div class="ficha-item">
                    <span class="ficha-label"><i class="fas fa-users"></i> Público-Alvo:</span>
                    <span class="ficha-valor">Desenvolvedores de software, engenheiros de sistemas, estudantes de computação e gestores de TI</span>
                </div>

                <div class="ficha-item">
                    <span class="ficha-label"><i class="fas fa-bullseye"></i> Objetivos Gerais:</span>
                    <span class="ficha-valor">
                        Capacitar profissionais técnicos na tradução da legislação de dados pessoais em requisitos funcionais de software, 
                        mitigando vulnerabilidades e fomentando a proteção da privacidade infantojuvenil através do <em>Privacy by Design</em>.
                    </span>
                </div>

                <div class="ficha-item">
                    <span class="ficha-label"><i class="fas fa-calendar-alt"></i> Publicação / Revisão:</span>
                    <span class="ficha-valor">2026</span>
                </div>
            </div>

            <!-- Referências Acadêmicas (Padrão ABNT) -->
            <div class="ficha-referencias" style="margin-top: 30px; border-top: 1px solid #E2E8F0; padding-top: 25px;">
                <h3 style="font-size: 1.1rem; color: var(--text-main); margin-bottom: 15px;">
                    <i class="fas fa-quote-right" style="color: var(--primary);"></i> Referências Bibliográficas (Padrão ABNT):
                </h3>
                <ul class="lista-referencias">
                    <li>
                        BRASIL. <strong>Lei nº 13.709, de 14 de agosto de 2018</strong>. Dispõe sobre a proteção de dados pessoais (Lei Geral de Proteção de Dados Pessoais - LGPD). Diário Oficial da União, Brasília, DF, 15 ago. 2018.
                    </li>
                    <li>
                        BRASIL. <strong>Lei nº 8.069, de 13 de julho de 1990</strong>. Dispõe sobre o Estatuto da Criança e do Adolescente (ECA) e dá outras providências. Diário Oficial da União, Brasília, DF, 16 jul. 1990.
                    </li>
                    <li>
                        BRASIL. <strong>Lei nº 15.211, de 16 de setembro de 2025</strong>. Dispõe sobre o Estatuto Digital da Criança e do Adolescente. Brasília, DF, 2025.
                    </li>
                    <li>
                        AUTORIDADE NACIONAL DE PROTEÇÃO DE DADOS (ANPD). <strong>Guia Orientativo: Tratamento de Dados Pessoais de Crianças e Adolescentes</strong>. Brasília: ANPD, 2023. Disponível em: &lt;https://www.gov.br/anpd&gt;.
                    </li>
                    <li>
                        CAVOUKIAN, Ann. <strong>Privacy by Design: The 7 Foundational Principles</strong>. Information and Privacy Commissioner of Ontario, 2009.
                    </li>
                </ul>
            </div>

            <div style="text-align: center; margin-top: 35px;">
                <a href="/" class="btn-secondary" style="display: inline-flex;">Voltar ao Início</a>
                <a href="/checklist" class="btn-primary" style="display: inline-flex; margin-left: 12px;">Iniciar Checklist Técnico</a>
            </div>

        </div>
    </section>

</div>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
