<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<?php
// Carrega os vídeos do banco de dados (ou fallback padrão)
$videoModel = new Video();
$videos = $videoModel->buscarTodos();

// Prepara array com os dados e IDs do YouTube extraídos
$videosFormatados = [];
foreach ($videos as $idx => $v) {
    $ytId = Video::extrairYoutubeId($v['link'] ?? '');
    $videosFormatados[] = [
        'index'       => $idx,
        'numero'      => str_pad($idx + 1, 2, '0', STR_PAD_LEFT),
        'titulo'      => $v['titulo'],
        'duracao'     => $v['duracao'] ?? '3 min',
        'link'        => $v['link'] ?? '',
        'youtube_id'  => $ytId,
        'descricao'   => $v['descricao'] ?? '',
    ];
}
?>

<div class="container educacional-page" style="padding-top: 16px; padding-bottom: 40px;">

    <!-- Cabeçalho Unificado -->
    <div class="page-header-unified">
        <div class="page-header-content">
            <div class="page-header-title-row">
                <span class="page-header-badge">
                    <i class="fas fa-graduation-cap"></i> Trilha Educacional
                </span>
                <h1>LGPD na Prática para Desenvolvedores</h1>
            </div>
            <p class="page-header-subtitle">
                Aprenda a aplicar <strong>Privacy by Design</strong> e os requisitos do <strong>Artigo 14 da LGPD</strong> (proteção de menores) no código.
            </p>
        </div>
    </div>

    <!-- Layout Moderno de 2 Colunas: Player Principal (68%) + Playlist Lateral (32%) -->
    <div class="curso-layout-grid">
        
        <!-- Coluna Esquerda: Palco do Player e Detalhes do Vídeo Ativo -->
        <div class="curso-main-stage">
            <div class="player-card">
                
                <!-- Container do Vídeo (16:9 responsivo) -->
                <div class="player-aspect-wrapper">
                    <div id="playerIframeContainer" class="video-responsive-container" style="display: none;">
                        <iframe 
                            id="videoIframe"
                            src="" 
                            title="Player de Vídeo" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            allowfullscreen>
                        </iframe>
                    </div>

                    <!-- Placeholder moderno caso o vídeo ainda não tenha link cadastrado -->
                    <div id="playerPlaceholderContainer" class="video-placeholder-container">
                        <div class="placeholder-overlay">
                            <div class="placeholder-play-circle">
                                <i class="fas fa-play"></i>
                            </div>
                            <div class="placeholder-text-group">
                                <span class="placeholder-badge">
                                    <i class="fas fa-video"></i> Vídeo em Produção
                                </span>
                                <h3 id="placeholderTitulo">Carregando...</h3>
                                <p>Gravação em andamento (duração estimada: <strong id="placeholderDuracao">3 min</strong>).</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalhes do Vídeo Selecionado -->
                <div class="player-info-footer">
                    <div class="player-info-text">
                        <div class="video-meta-tag">
                            <span class="modulo-badge" id="ativoModuloBadge">Módulo 01</span>
                            <span class="duracao-badge"><i class="far fa-clock"></i> <span id="ativoDuracao">3 min</span></span>
                        </div>
                        <h2 id="ativoTitulo" class="video-active-title">Carregando...</h2>
                        <p id="ativoDescricao" class="video-active-desc"></p>
                    </div>

                    <!-- Ações Rápidas -->
                    <div class="player-actions-row">
                        <a href="/checklist" class="btn-checklist-link" title="Testar seu sistema agora">
                            <i class="fas fa-clipboard-check"></i> Aplicar no Checklist
                        </a>

                        <button type="button" id="btnAvancar" onclick="proximoVideo()" class="btn-proximo-modulo">
                            <span id="btnAvancarTexto">Próximo Módulo</span>
                            <i id="btnAvancarIcon" class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Coluna Direita: Playlist da Trilha (Aulas) -->
        <div class="curso-playlist-sidebar">
            <div class="playlist-card">
                
                <div class="playlist-header">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <h3 style="font-size: 1rem; margin: 0; color: var(--text-main); font-weight: 700;">
                            <i class="fas fa-list-ul" style="color: var(--primary); margin-right: 6px;"></i> Aulas da Trilha
                        </h3>
                        <span style="font-size: 0.74rem; background: #F1F5F9; color: var(--text-muted); padding: 3px 8px; border-radius: 12px; font-weight: 600;">
                            <?php echo count($videosFormatados); ?> módulos
                        </span>
                    </div>
                    <p style="font-size: 0.78rem; color: var(--text-muted); margin: 3px 0 0;">
                        Clique para alternar instantaneamente entre os vídeos.
                    </p>
                </div>

                <!-- Lista de Aulas -->
                <div class="playlist-items-list" id="playlistContainer">
                    <?php foreach ($videosFormatados as $v): ?>
                        <div class="playlist-item <?php echo $v['index'] === 0 ? 'active' : ''; ?>" 
                             id="playlist-item-<?php echo $v['index']; ?>"
                             onclick="selecionarVideo(<?php echo $v['index']; ?>)">
                            
                            <div class="item-numero-badge">
                                <?php echo $v['numero']; ?>
                            </div>

                            <div class="item-info">
                                <h4 class="item-title"><?php echo htmlspecialchars($v['titulo']); ?></h4>
                                <div class="item-meta">
                                    <span class="item-duracao"><i class="far fa-clock"></i> <?php echo htmlspecialchars($v['duracao']); ?></span>
                                    <span class="item-status-tag">
                                        <?php echo $v['index'] === 0 ? '<i class="fas fa-play" style="font-size:0.65rem;"></i> Tocando agora' : ''; ?>
                                    </span>
                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Rodapé da Playlist -->
                <div class="playlist-footer-box">
                    <p style="font-size: 0.78rem; color: var(--text-muted); margin: 0 0 8px;">
                        Pronto para validar o seu software com a legislação?
                    </p>
                    <a href="/checklist" class="btn-primary" style="width: 100%; font-size: 0.82rem; padding: 9px; min-width: unset; text-align: center; justify-content: center;">
                        <i class="fas fa-clipboard-check"></i> Fazer Checklist Completo
                    </a>
                </div>

            </div>
        </div>

    </div>

</div>

<!-- Estilos Dedicados para o Layout Cinema / Playlist -->
<style>
/* Cabeçalho compacto da página */
.educacional-header-full {
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid #E2E8F0;
}

.educacional-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

/* Grid do Curso: 2 Colunas no Desktop */
.curso-layout-grid {
    display: grid;
    grid-template-columns: 1fr 370px;
    gap: 22px;
    align-items: start;
    width: 100%;
}

/* Palco Principal */
.player-card {
    background: var(--white);
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
}

.player-aspect-wrapper {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 9;
    background: #0F172A;
}

.video-responsive-container {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
}

.video-responsive-container iframe {
    width: 100%;
    height: 100%;
    border: none;
}

/* Placeholder */
.video-placeholder-container {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: linear-gradient(135deg, #0F172A 0%, #1E293B 70%, #0F2847 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
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
    width: 64px;
    height: 64px;
    background: rgba(255, 255, 255, 0.12);
    border: 2px solid rgba(255, 255, 255, 0.35);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
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
    font-size: 1.25rem;
    margin: 4px 0 6px;
    color: #F8FAFC;
    line-height: 1.3;
}

.placeholder-text-group p {
    font-size: 0.88rem;
    color: #CBD5E1;
    margin: 0;
}

/* Informações da Aula Ativa */
.player-info-footer {
    padding: 16px 22px;
}

.video-meta-tag {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 6px;
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

.video-active-title {
    font-size: 1.22rem;
    color: var(--text-main);
    line-height: 1.3;
    margin: 0 0 6px;
}

.video-active-desc {
    color: var(--text-muted);
    font-size: 0.88rem;
    line-height: 1.5;
    margin: 0 0 14px;
}

/* Linha de botões do player */
.player-actions-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding-top: 14px;
    border-top: 1px solid #F1F5F9;
    flex-wrap: wrap;
}

.btn-checklist-link {
    background: #EFF6FF;
    color: var(--primary);
    border: 1.5px solid #BFDBFE;
    padding: 8px 18px;
    border-radius: 10px;
    font-size: 0.85rem;
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

.btn-proximo-modulo {
    background: var(--primary);
    color: white;
    border: none;
    padding: 9px 20px;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.2s ease;
}

.btn-proximo-modulo:hover {
    background: var(--primary-dark);
}

/* Sidebar Playlist */
.playlist-card {
    background: var(--white);
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    display: flex;
    flex-direction: column;
}

.playlist-header {
    padding: 14px 18px;
    background: #FAFAFA;
    border-bottom: 1px solid #E2E8F0;
}

.playlist-items-list {
    display: flex;
    flex-direction: column;
    max-height: 440px;
    overflow-y: auto;
}

.playlist-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 13px 16px;
    border-bottom: 1px solid #F1F5F9;
    cursor: pointer;
    transition: all 0.2s ease;
    background: var(--white);
    user-select: none;
}

.playlist-item:hover {
    background: #F8FAFC;
}

.playlist-item.active {
    background: #EFF6FF;
    border-left: 4px solid var(--primary);
}

.item-numero-badge {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #F1F5F9;
    color: var(--text-muted);
    font-weight: 700;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.2s ease;
}

.playlist-item.active .item-numero-badge {
    background: var(--primary);
    color: white;
}

.item-info {
    flex: 1;
    min-width: 0;
}

.item-title {
    font-size: 0.88rem;
    line-height: 1.35;
    color: var(--text-main);
    margin: 0 0 4px;
    font-weight: 600;
}

.playlist-item.active .item-title {
    color: var(--primary);
}

.item-meta {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.74rem;
    color: var(--text-muted);
}

.item-status-tag {
    color: var(--primary);
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.playlist-footer-box {
    padding: 14px 18px;
    background: #FAFAFA;
    border-top: 1px solid #E2E8F0;
    text-align: center;
}

/* Responsividade */
@media (max-width: 950px) {
    .curso-layout-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    .educacional-header-full {
        flex-direction: column;
        align-items: flex-start;
    }
    .player-actions-row {
        flex-direction: column;
        align-items: stretch;
    }
    .btn-checklist-link, .btn-proximo-modulo {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
// Dados dinâmicos dos vídeos vindos do PHP/MySQL
const videosData = <?php echo json_encode($videosFormatados, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
let videoAtualIndex = 0;

function selecionarVideo(index) {
    if (index < 0 || index >= videosData.length) return;
    videoAtualIndex = index;

    const v = videosData[index];

    // Atualiza Textos
    document.getElementById('ativoModuloBadge').innerText = 'Módulo ' + v.numero;
    document.getElementById('ativoDuracao').innerText = v.duracao;
    document.getElementById('ativoTitulo').innerText = v.titulo;
    document.getElementById('ativoDescricao').innerText = v.descricao || '';

    // Atualiza Player (Iframe ou Placeholder)
    const iframeContainer = document.getElementById('playerIframeContainer');
    const placeholderContainer = document.getElementById('playerPlaceholderContainer');
    const iframe = document.getElementById('videoIframe');

    if (v.youtube_id) {
        iframe.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent(v.youtube_id) + '?rel=0&modestbranding=1&autoplay=1';
        iframeContainer.style.display = 'block';
        placeholderContainer.style.display = 'none';
    } else {
        iframe.src = '';
        iframeContainer.style.display = 'none';
        placeholderContainer.style.display = 'flex';
        document.getElementById('placeholderTitulo').innerText = v.titulo;
        document.getElementById('placeholderDuracao').innerText = v.duracao;
    }

    // Atualiza visual da lista (playlist)
    videosData.forEach((_, i) => {
        const item = document.getElementById('playlist-item-' + i);
        if (item) {
            const statusTag = item.querySelector('.item-status-tag');
            if (i === index) {
                item.classList.add('active');
                if (statusTag) statusTag.innerHTML = '<i class="fas fa-play" style="font-size:0.65rem;"></i> Tocando agora';
            } else {
                item.classList.remove('active');
                if (statusTag) statusTag.innerHTML = '';
            }
        }
    });

    // Atualiza botão Avançar
    const btnTexto = document.getElementById('btnAvancarTexto');
    const btnIcon = document.getElementById('btnAvancarIcon');
    if (index + 1 < videosData.length) {
        btnTexto.innerText = 'Próximo Módulo (' + videosData[index + 1].numero + ')';
        btnIcon.className = 'fas fa-arrow-right';
    } else {
        btnTexto.innerText = 'Concluir Trilha e Ir ao Checklist';
        btnIcon.className = 'fas fa-check-circle';
    }
}

function proximoVideo() {
    if (videoAtualIndex + 1 < videosData.length) {
        selecionarVideo(videoAtualIndex + 1);
    } else {
        window.location.href = '/checklist';
    }
}

// Inicializa no primeiro vídeo assim que a página carregar
document.addEventListener('DOMContentLoaded', () => {
    selecionarVideo(0);
});
</script>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
