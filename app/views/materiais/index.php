<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<main class="container">
    <div class="checklist-header" style="margin-top: 50px;">
        <h1>Biblioteca de Materiais</h1>
        <p>Documentação técnica e guias práticos para apoiar o seu desenvolvimento seguro e em conformidade.</p>
    </div>

    <div class="materiais-grid">
        <?php if (!empty($materiais)): ?>
            <?php foreach ($materiais as $row):
                $tituloJS  = json_encode($row['titulo']);
                $corpoJS   = json_encode($row['conteudo_detalhado'] ?? 'Sem detalhes adicionais disponíveis.');
                $linkJS    = json_encode($row['url_referencia']);
                $categoria = htmlspecialchars($row['categoria'] ?? 'Geral');
            ?>
                <div class="card-material">
                    <span class="badge-categoria"><?php echo $categoria; ?></span>
                    <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
                    <p><?php echo htmlspecialchars($row['descricao_curta'] ?? 'Clique em saiba mais para detalhes.'); ?></p>

                    <button class="btn-secondary"
                            onclick='abrirModal(<?php echo $tituloJS; ?>, <?php echo $corpoJS; ?>, <?php echo $linkJS; ?>)'
                            style="width: 100%; margin-top: 15px;">
                        Saiba Mais
                    </button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                <p>Nenhum material foi encontrado no banco de dados no momento.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Modal (idêntico ao original) -->
<div id="modalMaterial" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <h2 id="m-titulo" style="color: var(--primary);"></h2>
        <hr style="margin: 15px 0; border: 0; border-top: 1px solid var(--border);">

        <div id="m-corpo" style="margin-bottom: 25px; color: var(--text-muted); line-height: 1.6; max-height: 300px; overflow-y: auto;"></div>

        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button class="btn-secondary" onclick="fecharModal()">Fechar</button>
            <a id="m-link" href="#" target="_blank" class="btn-primary">Acessar Documento</a>
        </div>
    </div>
</div>

<script>
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
