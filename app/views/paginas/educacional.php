<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<?php
// Carrega os vídeos diretamente do banco de dados (com fallback automático e seguro)
$videoModel = new Video();
$videos = $videoModel->buscarTodos();
$totalVideos = count($videos);
?>

<div class="container educacional-page" style="padding-top: 35px; padding-bottom: 70px;">

    <!-- Contexto Inicial da Página (Simples, Direto e Focado) -->
    <div class="educacional-contexto" style="max-width: 820px; margin: 0 auto 35px; text-align: center;">
        <span class="badge-categoria" style="margin-bottom: 10px;">
            <i class="fas fa-play-circle"></i> Trilha Educacional
        </span>
        <h1 style="font-size: 2.2rem; color: var(--text-main); margin-bottom: 12px; font-weight: 700; line-height: 1.25;">
            LGPD na Prática para Desenvolvedores
        </h1>
        <p style="font-size: 1.05rem; color: var(--text-muted); line-height: 1.6; margin: 0 auto 16px;">
            Aprenda a aplicar o <strong>Privacy by Design</strong> e a atender aos requisitos do <strong>Artigo 14 da LGPD</strong> (proteção de dados de crianças e adolescentes) em sistemas de software por meio de vídeos curtos e objetivos.
        </p>

        <!-- Aviso Educativo Discreto -->
        <div class="aviso-educativo-discreto">
            <i class="fas fa-info-circle"></i>
            <span>Conteúdo estritamente educativo e orientativo. Não substitui consultoria ou assessoria jurídica.</span>
        </div>
    </div>

    <!-- Lista de Vídeos da Trilha -->
    <section class="trilha-videos-section" style="max-width: 820px; margin: 0 auto;">
        <div class="trilha-videos-list">
            <?php foreach ($videos as $index => $v): ?>
                <?php
                    $numeroModulo = $index + 1;
                    $moduloId = 'modulo-' . $numeroModulo;
                    $proximoModuloId = ($index + 1 < $totalVideos) ? 'modulo-' . ($numeroModulo + 1) : null;
                    $youtubeId = Video::extrairYoutubeId($v['link'] ?? '');
                ?>
                <article class="card-video-modulo" id="<?php echo $moduloId; ?>">
                    
                    <!-- Cabeçalho do Card -->
                    <div class="video-card-top">
                        <div class="video-meta-tag">
                            <span class="modulo-badge">Módulo <?php echo str_pad($numeroModulo, 2, '0', STR_PAD_LEFT); ?></span>
                            <span class="duracao-badge"><i class="far fa-clock"></i> <?php echo htmlspecialchars($v['duracao']); ?></span>
                        </div>
                        <h2 class="video-card-title"><?php echo htmlspecialchars($v['titulo']); ?></h2>
                        <?php if (!empty($v['descricao'])): ?>
                            <p class="video-card-desc"><?php echo htmlspecialchars($v['descricao']); ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Player de Vídeo Responsivo (YouTube no-cookie ou Placeholder "Em Breve") -->
                    <div class="video-player-wrapper">
                        <?php if ($youtubeId): ?>
                            <div class="video-responsive-container">
                                <iframe 
                                    src="https://www.youtube-nocookie.com/embed/<?php echo urlencode($youtubeId); ?>?rel=0&modestbranding=1" 
                                    title="<?php echo htmlspecialchars($v['titulo']); ?>" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                    allowfullscreen
                                    loading="lazy">
                                </iframe>
                            </div>
                        <?php else: ?>
                            <!-- Placeholder moderno 16:9 quando o link ainda não foi inserido -->
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
                                        <p>Gravação em andamento (duração estimada: <strong><?php echo htmlspecialchars($v['duracao']); ?></strong>).</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Ações Rápidas do Vídeo -->
                    <div class="video-card-actions">
                        <a href="/checklist" class="btn-checklist-link" title="Ir para o Checklist técnico">
                            <i class="fas fa-clipboard-check"></i> Aplicar no Checklist
                        </a>

                        <?php if ($proximoModuloId): ?>
                            <a href="#<?php echo $proximoModuloId; ?>" class="btn-proximo-video" title="Ir para o próximo vídeo">
                                Próximo Vídeo <i class="fas fa-arrow-down"></i>
                            </a>
                        <?php else: ?>
                            <a href="/checklist" class="btn-concluir-trilha" title="Concluir trilha e testar sistema">
                                Iniciar Checklist Completo <i class="fas fa-arrow-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>

                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Rodapé Simples da Página -->
    <div style="text-align: center; margin-top: 45px; display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
        <a href="/" class="btn-secondary" style="min-width: 200px;">
            <i class="fas fa-arrow-left"></i> Voltar ao Início
        </a>
        <a href="/checklist" class="btn-primary" style="min-width: 220px;">
            <i class="fas fa-clipboard-check"></i> Ir para o Checklist
        </a>
    </div>

</div>

<!-- Estilos dedicados e garantidos -->
<style>
html {
    scroll-behavior: smooth;
}

/* Aviso educativo discreto */
.aviso-educativo-discreto {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #F1F5F9;
    color: var(--text-muted);
    font-size: 0.85rem;
    padding: 8px 16px;
    border-radius: 20px;
    border: 1px solid #E2E8F0;
}

.aviso-educativo-discreto i {
    color: var(--primary);
}

/* Lista de Vídeos */
.trilha-videos-list {
    display: flex;
    flex-direction: column;
    gap: 35px;
}

.card-video-modulo {
    background: var(--white);
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    padding: 28px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
    display: flex;
    flex-direction: column;
    gap: 18px;
    scroll-margin-top: 110px;
    transition: border-color 0.25s;
}

.card-video-modulo:hover {
    border-color: #CBD5E1;
}

.video-card-top {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.video-meta-tag {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 2px;
}

.modulo-badge {
    background: var(--primary);
    color: white;
    font-weight: 700;
    font-size: 0.72rem;
    padding: 3px 10px;
    border-radius: 20px;
    text-transform: uppercase;
}

.duracao-badge {
    background: #F1F5F9;
    color: var(--text-muted);
    font-size: 0.76rem;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
}

.video-card-title {
    font-size: 1.35rem;
    color: var(--text-main);
    line-height: 1.3;
    margin: 2px 0 0;
}

.video-card-desc {
    color: var(--text-muted);
    font-size: 0.92rem;
    line-height: 1.55;
    margin: 4px 0 0;
}

/* Player Responsivo 16:9 */
.video-player-wrapper {
    width: 100%;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
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

/* Placeholder de Vídeo */
.video-placeholder-container {
    width: 100%;
    aspect-ratio: 16 / 9;
    background: linear-gradient(135deg, #0F172A 0%, #1E293B 70%, #0F2847 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    text-align: center;
    color: white;
}

.placeholder-overlay {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    max-width: 520px;
}

.placeholder-play-circle {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.12);
    border: 2px solid rgba(255, 255, 255, 0.35);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    color: #FFFFFF;
}

.placeholder-badge {
    background: rgba(239, 68, 68, 0.2);
    color: #FCA5A5;
    border: 1px solid rgba(239, 68, 68, 0.4);
    font-size: 0.75rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    display: inline-block;
}

.placeholder-text-group h3 {
    font-size: 1.15rem;
    margin: 4px 0 4px;
    color: #F8FAFC;
}

.placeholder-text-group p {
    font-size: 0.88rem;
    color: #CBD5E1;
    margin: 0;
}

/* Ações */
.video-card-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    flex-wrap: wrap;
    padding-top: 12px;
    border-top: 1px solid #F1F5F9;
}

.btn-checklist-link {
    background: #EFF6FF;
    color: var(--primary);
    border: 1.5px solid #BFDBFE;
    padding: 9px 18px;
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
    padding: 9px 18px;
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
    padding: 9px 20px;
    border-radius: 10px;
    font-size: 0.88rem;
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
        font-size: 1.15rem;
    }
    .video-card-actions {
        flex-direction: column;
        align-items: stretch;
    }
    .btn-checklist-link, .btn-proximo-video, .btn-concluir-trilha {
        width: 100%;
        justify-content: center;
    }
}
</style>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
