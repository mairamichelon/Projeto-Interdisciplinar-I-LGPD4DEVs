<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<main class="container content-area">

    <!-- Cabeçalho -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 35px; flex-wrap: wrap; gap: 15px;">
        <div>
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 6px;">
                <a href="/admin" style="font-size: 0.85rem; color: var(--text-muted); text-decoration: none;">
                    <i class="fas fa-arrow-left"></i> Dashboard
                </a>
                <span style="color: var(--text-muted);">/</span>
                <span style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 4px 12px; border-radius: 8px; font-size: 0.8rem; font-weight: 700;">
                    <i class="fas fa-shield-alt"></i> ADMIN
                </span>
            </div>
            <h1 style="margin: 0; font-size: 1.8rem;">Vídeos da Trilha Educacional</h1>
            <p style="color: var(--text-muted); margin: 4px 0 0;">
                Gerencie os links e informações dos vídeos exibidos na página <code>/educacional</code>.
            </p>
        </div>
        <a href="/educacional" target="_blank" class="btn-secondary" style="font-size: 0.9rem; min-width: unset; padding: 10px 18px;">
            <i class="fas fa-external-link-alt"></i> Ver Página Educacional
        </a>
    </div>

    <!-- Lista de vídeos -->
    <div style="display: flex; flex-direction: column; gap: 20px;">
        <?php foreach ($videos as $v): ?>
            <?php
                $ytId = Video::extrairYoutubeId($v['link'] ?? '');
            ?>
            <div class="card-pergunta" style="padding: 24px; border-left: 5px solid var(--primary);">
                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 280px;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                            <span class="badge-categoria" style="margin-bottom: 0;">Módulo <?php echo str_pad($v['ordem'], 2, '0', STR_PAD_LEFT); ?></span>
                            <span style="font-size: 0.8rem; color: var(--text-muted); background: #F1F5F9; padding: 4px 10px; border-radius: 20px;">
                                <i class="far fa-clock"></i> <?php echo htmlspecialchars($v['duracao']); ?>
                            </span>
                        </div>
                        <h2 style="margin: 6px 0 8px; font-size: 1.2rem; color: var(--text-main);">
                            <?php echo htmlspecialchars($v['titulo']); ?>
                        </h2>
                        <?php if (!empty($v['descricao'])): ?>
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 12px;">
                                <?php echo htmlspecialchars($v['descricao']); ?>
                            </p>
                        <?php endif; ?>

                        <!-- Status do Link -->
                        <div style="margin-top: 10px;">
                            <?php if (!empty($v['link'])): ?>
                                <span style="font-size: 0.85rem; color: var(--secondary); font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-check-circle"></i> Link configurado:
                                </span>
                                <a href="<?php echo htmlspecialchars($v['link']); ?>" target="_blank" style="font-size: 0.85rem; color: var(--primary); text-decoration: underline; margin-left: 5px;">
                                    <?php echo htmlspecialchars($v['link']); ?>
                                </a>
                                <?php if ($ytId): ?>
                                    <span style="font-size: 0.75rem; color: var(--text-muted); margin-left: 8px;">(YouTube ID: <code><?php echo htmlspecialchars($ytId); ?></code>)</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span style="font-size: 0.85rem; color: #EAB308; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; background: #FEF9C3; padding: 4px 10px; border-radius: 6px;">
                                    <i class="fas fa-exclamation-triangle"></i> Sem link cadastrado (exibindo placeholder "Em Breve")
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div>
                        <button onclick='abrirModalVideo(<?php echo json_encode($v, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)'
                                class="btn-primary" style="min-width: unset; padding: 10px 18px; font-size: 0.88rem;">
                            <i class="fas fa-edit"></i> Alterar Link / Dados
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</main>

<!-- Modal de Edição de Vídeo -->
<div id="modalVideo" class="modal-overlay" style="display: none;">
    <div class="modal-content" style="max-width: 580px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 id="modalTitulo" style="margin: 0; font-size: 1.3rem;">Editar Vídeo</h2>
            <button onclick="fecharModalVideo()" style="background: none; border: none; font-size: 1.4rem; cursor: pointer; color: var(--text-muted);">
                &times;
            </button>
        </div>

        <form id="formVideo" onsubmit="salvarVideo(event)">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
            <input type="hidden" id="video_id" name="id" value="0">
            <input type="hidden" id="video_ordem" name="ordem" value="1">

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="titulo" style="font-weight: 600; font-size: 0.9rem;">Título do Vídeo *</label>
                <input type="text" id="video_titulo" name="titulo" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="duracao" style="font-weight: 600; font-size: 0.9rem;">Duração Estimada</label>
                <input type="text" id="video_duracao" name="duracao" placeholder="Ex: 3 min" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="link" style="font-weight: 600; font-size: 0.9rem;">
                    Link do Vídeo no YouTube
                </label>
                <input type="text" id="video_link" name="link" placeholder="Ex: https://www.youtube.com/watch?v=XXXXX ou o ID do vídeo" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px;">
                <small style="color: var(--text-muted); font-size: 0.78rem; display: block; margin-top: 4px;">
                    Aceita link completo do YouTube, link curto (youtu.be) ou o código ID. Deixe em branco para exibir o placeholder "Em Breve".
                </small>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="descricao" style="font-weight: 600; font-size: 0.9rem;">Breve Descrição / Objetivo</label>
                <textarea id="video_descricao" name="descricao" rows="3" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; font-family: inherit;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="fecharModalVideo()" class="btn-secondary" style="min-width: unset; padding: 10px 18px;">
                    Cancelar
                </button>
                <button type="submit" id="btnSalvarVideo" class="btn-primary" style="min-width: unset; padding: 10px 22px;">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirModalVideo(video) {
    document.getElementById('video_id').value = video.id;
    document.getElementById('video_ordem').value = video.ordem || 1;
    document.getElementById('video_titulo').value = video.titulo || '';
    document.getElementById('video_duracao').value = video.duracao || '3 min';
    document.getElementById('video_link').value = video.link || '';
    document.getElementById('video_descricao').value = video.descricao || '';
    document.getElementById('modalVideo').style.display = 'flex';
}

function fecharModalVideo() {
    document.getElementById('modalVideo').style.display = 'none';
}

function salvarVideo(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSalvarVideo');
    btn.disabled = true;
    btn.innerText = 'Salvando...';

    const formData = new FormData(document.getElementById('formVideo'));

    fetch('/admin/videos/salvar', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerText = 'Salvar Alterações';
        if (data.sucesso) {
            alert('Vídeo atualizado com sucesso no banco de dados!');
            location.reload();
        } else {
            alert(data.mensagem || 'Erro ao salvar vídeo.');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerText = 'Salvar Alterações';
        alert('Erro de comunicação com o servidor.');
    });
}
</script>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
