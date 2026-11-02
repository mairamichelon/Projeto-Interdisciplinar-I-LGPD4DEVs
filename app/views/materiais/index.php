<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<main class="container content-area" style="padding-top: 15px; padding-bottom: 60px;">

    <!-- Cabeçalho Unificado -->
    <div class="page-header-unified">
        <div class="page-header-content">
            <div class="page-header-title-row">
                <span class="page-header-badge">
                    <i class="fas fa-book-open"></i> Biblioteca de Materiais
                </span>
                <h1>Documentação Técnica e Guias</h1>
            </div>
            <p class="page-header-subtitle">
                Guias práticos, regulamentações da ANPD e referências para apoiar o seu desenvolvimento seguro e em conformidade.
            </p>
        </div>
    </div>

    <!-- Barra de Filtros Interativa -->
    <div class="filter-pills-bar">
        <button type="button" class="filter-pill-btn active" onclick="filtrarCategoria('todos', this)">
            <i class="fas fa-th-large"></i> Todos
        </button>
        <button type="button" class="filter-pill-btn" onclick="filtrarCategoria('ECA', this)">
            <i class="fas fa-child"></i> ECA Digital
        </button>
        <button type="button" class="filter-pill-btn" onclick="filtrarCategoria('ANPD', this)">
            <i class="fas fa-shield-alt"></i> ANPD & Cookies
        </button>
        <button type="button" class="filter-pill-btn" onclick="filtrarCategoria('TECNOLOGIA', this)">
            <i class="fas fa-microchip"></i> Tecnologia & IA
        </button>
        <button type="button" class="filter-pill-btn" onclick="filtrarCategoria('GUIA', this)">
            <i class="fas fa-file-alt"></i> Guias Práticos
        </button>
    </div>

    <div class="materiais-grid">
        <?php if (!empty($materiais)): ?>
            <?php foreach ($materiais as $row):
                $tituloJS  = json_encode($row['titulo']);
                $corpoJS   = json_encode($row['conteudo_detalhado'] ?? 'Sem detalhes adicionais disponíveis.');
                $linkJS    = json_encode($row['url_referencia']);
                $categoria = htmlspecialchars($row['categoria'] ?? 'Geral');
            ?>
                <div class="card-material" data-categoria="<?php echo $categoria; ?>">
                    <div>
                        <span class="badge-categoria"><?php echo $categoria; ?></span>
                        <h3 style="font-size: 1.12rem; margin: 0 0 10px; color: var(--text-main); line-height: 1.35;">
                            <?php echo htmlspecialchars($row['titulo']); ?>
                        </h3>
                        <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 20px;">
                            <?php echo htmlspecialchars($row['descricao_curta'] ?? 'Clique em saiba mais para detalhes.'); ?>
                        </p>
                    </div>

                    <button class="btn-secondary"
                            onclick='abrirModal(<?php echo $tituloJS; ?>, <?php echo $corpoJS; ?>, <?php echo $linkJS; ?>)'
                            style="width: 100%; min-width: unset; padding: 10px; font-size: 0.88rem;">
                        <i class="fas fa-info-circle"></i> Saiba Mais
                    </button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 50px 20px; background: white; border-radius: 16px; border: 1px solid #E2E8F0;">
                <p style="color: var(--text-muted); margin: 0;">Nenhum material foi encontrado no banco de dados no momento.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Modal de Detalhes do Material -->
<div id="modalMaterial" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <h2 id="m-titulo" style="color: var(--primary); font-size: 1.35rem; line-height: 1.3;"></h2>
        <hr style="margin: 15px 0; border: 0; border-top: 1px solid var(--border);">

        <div id="m-corpo" style="margin-bottom: 25px; color: var(--text-muted); line-height: 1.6; max-height: 320px; overflow-y: auto; font-size: 0.95rem;"></div>

        <div style="display: flex; gap: 10px; justify-content: flex-end; flex-wrap: wrap;">
            <button class="btn-secondary" onclick="fecharModal()" style="min-width: unset; padding: 8px 16px;">Fechar</button>
            <a id="m-link" href="#" target="_blank" class="btn-primary" style="min-width: unset; padding: 8px 18px;">
                <i class="fas fa-external-link-alt"></i> Acessar Documento
            </a>
        </div>
    </div>
</div>

<script>
function filtrarCategoria(cat, btn) {
    document.querySelectorAll('.filter-pill-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const cards = document.querySelectorAll('.card-material[data-categoria]');
    cards.forEach(card => {
        const c = (card.getAttribute('data-categoria') || '').toUpperCase();
        if (cat === 'todos' || c.includes(cat.toUpperCase())) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

function abrirModal(titulo, conteudo, link) {
    document.getElementById('m-titulo').innerText = titulo;
    document.getElementById('m-corpo').innerText  = conteudo;
    document.getElementById('m-link').href        = link;
    document.getElementById('modalMaterial').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function fecharModal() {
    document.getElementById('modalMaterial').style.display = 'none';
    document.body.style.overflow = 'auto';
}

window.onclick = function(event) {
    if (event.target == document.getElementById('modalMaterial')) fecharModal();
}

document.addEventListener('keydown', function(event) {
    if (event.key === "Escape") fecharModal();
});
</script>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
