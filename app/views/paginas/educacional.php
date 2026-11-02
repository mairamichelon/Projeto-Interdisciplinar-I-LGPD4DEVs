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

<div class="container educacional-page" style="padding-top: 35px; padding-bottom: 70px;">

    <!-- Cabeçalho Institucional / Educativo -->
    <div class="educacional-header" style="max-width: 900px; margin: 0 auto 30px; text-align: center;">
        <span class="badge-categoria" style="margin-bottom: 12px;">
            <i class="fas fa-graduation-cap"></i> Conteúdo Educacional &amp; Extensão
        </span>
        <h1 style="font-size: 2.3rem; color: var(--text-main); margin-bottom: 15px; line-height: 1.25;">
            Trilha de Aprendizagem LGPD4DEVS
        </h1>
        <p style="font-size: 1.15rem; color: var(--text-muted); line-height: 1.6;">
            Disseminando conhecimento prático e acessível à comunidade de desenvolvimento e tecnologia.
            Aprenda a aplicar o <strong>Privacy by Design</strong> e os requisitos do <strong>Artigo 14 da LGPD</strong>
            através de vídeos curtos e objetivos preparados para quem faz software.
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
                    e <strong>não constituem nem substituem consultoria jurídica, parecer legal ou auditoria oficial</strong>.
                    Consulte sempre profissionais jurídicos especializados e o DPO (Encarregado) da sua organização.
                </span>
            </div>
        </div>
    </div>

    <!-- Índice Rápido da Trilha -->
    <div class="trilha-indice-card" style="max-width: 950px; margin: 0 auto 45px;">
        <h3 style="font-size: 1.1rem; color: var(--text-main); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-route" style="color: var(--primary);"></i> Etapas da Trilha (2 a 4 minutos cada):
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

    <!-- Navegação Final Inferior -->
    <div style="text-align: center; margin-top: 40px; margin-bottom: 20px; display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
        <a href="/" class="btn-secondary" style="min-width: 200px;">
            <i class="fas fa-arrow-left"></i> Voltar ao Início
        </a>
        <a href="/checklist" class="btn-primary" style="min-width: 220px;">
            <i class="fas fa-clipboard-check"></i> Iniciar Checklist Técnico
        </a>
    </div>

</div>

<!-- Estilos dedicados e garantidos da página educacional -->
<style>
html {
    scroll-behavior: smooth;
}

/* Aviso Educativo / Isenção Jurídica */
.aviso-educativo {
    margin: 20px 0;
}

.aviso-card {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    background: #EFF6FF;
    border-left: 5px solid var(--primary);
    padding: 18px 22px;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
}

.aviso-icon {
    background: #DBEAFE;
    color: var(--primary);
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
    color: var(--text-main);
}

.aviso-text strong {
    color: #1E3A8A;
    font-size: 0.98rem;
}

/* Índice da Trilha */
.trilha-indice-card {
    background: var(--white);
    padding: 24px 28px;
    border-radius: 16px;
    border: 1px solid #E2E8F0;
    box-shadow: var(--shadow);
}

.trilha-passos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 16px;
}

.trilha-passo-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 18px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    text-decoration: none;
    color: var(--text-main);
    transition: all 0.25s ease;
}

.trilha-passo-item:hover {
    border-color: var(--primary);
    background: #EFF6FF;
    transform: translateY(-2px);
}

.passo-numero {
    background: var(--primary);
    color: white;
    font-weight: 700;
    font-size: 0.85rem;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.passo-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.passo-info strong {
    font-size: 0.88rem;
    line-height: 1.35;
    color: var(--text-main);
}

.passo-duracao {
    font-size: 0.76rem;
    color: var(--text-muted);
}

/* Cards dos Módulos de Vídeo */
.trilha-videos-list {
    display: flex;
    flex-direction: column;
    gap: 40px;
}

.card-video-modulo {
    background: var(--white);
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    padding: 32px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    display: flex;
    flex-direction: column;
    gap: 22px;
    scroll-margin-top: 110px; /* Compensa a navbar sticky */
    transition: border-color 0.3s;
}

.card-video-modulo:hover {
    border-color: #CBD5E1;
}

.video-card-top {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.video-meta-tag {
    display: flex;
    align-items: center;
    gap: 10px;
}

.modulo-badge {
    background: var(--primary);
    color: white;
    font-weight: 700;
    font-size: 0.75rem;
    padding: 4px 12px;
    border-radius: 20px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.duracao-badge {
    background: #F1F5F9;
    color: var(--text-muted);
    font-size: 0.78rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
}

.video-card-title {
    font-size: 1.45rem;
    color: var(--text-main);
    line-height: 1.35;
    margin: 0;
}

/* Box Objetivo */
.video-objetivo-box {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    background: #F0FDF4;
    border-left: 4px solid var(--secondary);
    padding: 14px 18px;
    border-radius: 8px;
    font-size: 0.92rem;
    color: #14532D;
    line-height: 1.55;
}

.video-objetivo-box .objetivo-icon {
    color: var(--secondary);
    font-size: 1.15rem;
    margin-top: 2px;
}

.video-objetivo-box p {
    margin: 2px 0 0;
    color: #166534;
}

/* Player Responsivo 16:9 */
.video-player-wrapper {
    width: 100%;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    background: #0F172A;
}

.video-responsive-container {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 9;
}

.video-responsive-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: none;
}

/* Placeholder Moderno de Vídeo */
.video-placeholder-container {
    width: 100%;
    aspect-ratio: 16 / 9;
    background: linear-gradient(135deg, #0F172A 0%, #1E293B 60%, #0F2847 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    padding: 24px;
    text-align: center;
    color: white;
}

.placeholder-overlay {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 14px;
    max-width: 580px;
}

.placeholder-play-circle {
    width: 68px;
    height: 68px;
    background: rgba(255, 255, 255, 0.12);
    border: 2px solid rgba(255, 255, 255, 0.35);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    color: #FFFFFF;
    backdrop-filter: blur(8px);
    transition: transform 0.3s ease, background 0.3s ease;
}

.placeholder-play-circle:hover {
    transform: scale(1.08);
    background: rgba(0, 102, 255, 0.6);
}

.placeholder-badge {
    background: rgba(239, 68, 68, 0.2);
    color: #FCA5A5;
    border: 1px solid rgba(239, 68, 68, 0.4);
    font-size: 0.78rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    display: inline-block;
    margin-bottom: 4px;
}

.placeholder-text-group h3 {
    font-size: 1.25rem;
    margin-bottom: 6px;
    color: #F8FAFC;
}

.placeholder-text-group p {
    font-size: 0.92rem;
    color: #CBD5E1;
    margin-bottom: 6px;
}

.placeholder-hint {
    font-size: 0.78rem;
    color: #94A3B8;
}

.placeholder-hint code {
    background: rgba(255, 255, 255, 0.1);
    padding: 2px 6px;
    border-radius: 4px;
    color: #93C5FD;
}

/* Resumo */
.video-resumo-box {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 16px 20px;
}

/* Transcrição Accordion */
.transcricao-accordion {
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    background: #FFFFFF;
    overflow: hidden;
    transition: border-color 0.2s;
}

.transcricao-accordion[open] {
    border-color: #CBD5E1;
}

.transcricao-summary {
    padding: 14px 18px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.92rem;
    color: var(--text-main);
    display: flex;
    justify-content: space-between;
    align-items: center;
    user-select: none;
    background: #FAFAFA;
}

.transcricao-summary:hover {
    background: #F1F5F9;
    color: var(--primary);
}

.transcricao-summary::-webkit-details-marker {
    display: none;
}

.summary-arrow {
    transition: transform 0.25s ease;
    font-size: 0.85rem;
    color: var(--text-muted);
}

.transcricao-accordion[open] .summary-arrow {
    transform: rotate(180deg);
}

.transcricao-conteudo {
    padding: 18px;
    font-size: 0.93rem;
    line-height: 1.7;
    color: var(--text-muted);
    border-top: 1px solid #E2E8F0;
    background: #FFFFFF;
}

/* Ações do Card de Vídeo */
.video-card-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    margin-top: 6px;
    padding-top: 16px;
    border-top: 1px solid #F1F5F9;
}

.btn-checklist-link {
    background: #EFF6FF;
    color: var(--primary);
    border: 1.5px solid #BFDBFE;
    padding: 10px 18px;
    border-radius: 10px;
    font-size: 0.88rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
}

.btn-checklist-link:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.btn-proximo-video {
    background: var(--primary);
    color: white;
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 0.88rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.2s ease;
}

.btn-proximo-video:hover {
    background: var(--primary-dark);
}

.btn-concluir-trilha {
    background: var(--secondary);
    color: white;
    padding: 10px 22px;
    border-radius: 10px;
    font-size: 0.9rem;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.2s ease;
}

.btn-concluir-trilha:hover {
    background: #00B359;
}

@media (max-width: 768px) {
    .card-video-modulo {
        padding: 20px 16px;
    }
    .video-card-title {
        font-size: 1.2rem;
    }
    .video-card-actions {
        flex-direction: column;
        align-items: stretch;
    }
    .btn-checklist-link, .btn-proximo-video, .btn-concluir-trilha {
        width: 100%;
        justify-content: center;
    }
    .aviso-card {
        flex-direction: column;
        gap: 10px;
    }
}
</style>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
