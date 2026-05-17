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
            <h1 style="margin: 0; font-size: 1.8rem;">Gerenciar Materiais</h1>
            <p style="color: var(--text-muted); margin: 4px 0 0;"><?php echo count($materiais); ?> material(is) cadastrado(s)</p>
        </div>
        <button onclick="abrirModalMaterial()" class="btn-primary">
            <i class="fas fa-plus"></i> Novo Material
        </button>
    </div>

    <!-- Lista de materiais -->
    <?php if (empty($materiais)): ?>
        <div class="card-pergunta" style="text-align: center; padding: 60px;">
            <i class="fas fa-book" style="font-size: 3rem; color: var(--border); margin-bottom: 20px; display: block;"></i>
            <h3 style="color: var(--text-muted);">Nenhum material cadastrado</h3>
            <p style="color: var(--text-muted); margin-bottom: 25px;">Adicione o primeiro material para a biblioteca.</p>
            <button onclick="abrirModalMaterial()" class="btn-primary">Adicionar Material</button>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 15px;">
            <?php foreach ($materiais as $m): ?>
                <div class="card-pergunta" style="padding: 20px 25px; border-left: 5px solid var(--primary);">
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
                        <div style="flex: 1;">
                            <span class="badge-categoria"><?php echo htmlspecialchars($m['categoria']); ?></span>
                            <h3 style="margin: 8px 0 6px; font-size: 1.05rem;"><?php echo htmlspecialchars($m['titulo']); ?></h3>
                            <p style="color: var(--text-muted); font-size: 0.88rem; margin: 0 0 8px;">
                                <?php echo htmlspecialchars($m['descricao_curta'] ?? '—'); ?>
                            </p>
                            <?php if ($m['url_referencia']): ?>
                                <a href="<?php echo htmlspecialchars($m['url_referencia']); ?>" target="_blank"
                                   style="font-size: 0.8rem; color: var(--primary); text-decoration: none;">
                                    <i class="fas fa-external-link-alt"></i> Ver referência
                                </a>
                            <?php endif; ?>
                        </div>
                        <div style="display: flex; gap: 8px; flex-shrink: 0;">
                            <button onclick='abrirModalMaterial(<?php echo json_encode($m); ?>)'
                                    style="background: transparent; border: 2px solid var(--primary); color: var(--primary); padding: 8px 14px; border-radius: 10px; cursor: pointer; font-size: 0.85rem; font-weight: 600;">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button onclick="confirmarDeletar(<?php echo $m['id']; ?>, '<?php echo htmlspecialchars(addslashes($m['titulo'])); ?>')"
                                    style="background: transparent; border: 2px solid var(--error); color: var(--error); padding: 8px 14px; border-radius: 10px; cursor: pointer; font-size: 0.85rem; font-weight: 600;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</main>

<!-- Modal: Criar / Editar Material -->
<div id="modalMaterial" class="modal-overlay" style="display: none;">
    <div class="modal-content" style="max-width: 620px; max-height: 90vh; overflow-y: auto;">
        <h2 id="modalTitulo" style="color: var(--primary); margin-bottom: 25px;">Novo Material</h2>

        <input type="hidden" id="mat-id">

        <div class="form-group">
            <label>Título *</label>
            <input type="text" id="mat-titulo" placeholder="Ex: Guia de Privacy by Design" maxlength="200">
        </div>
        <div class="form-group">
            <label>Categoria *</label>
            <input type="text" id="mat-categoria" placeholder="Ex: LGPD, Segurança, Boas Práticas" maxlength="100">
        </div>
        <div class="form-group">
            <label>Descrição Curta</label>
            <textarea id="mat-descricao-curta" rows="2"
                      style="width:100%; padding:14px 16px; border:2px solid var(--border); border-radius:10px; font-family:inherit; font-size:1rem; resize:vertical;"
                      placeholder="Resumo exibido no card da biblioteca..."></textarea>
        </div>
        <div class="form-group">
            <label>Conteúdo Detalhado</label>
            <textarea id="mat-conteudo" rows="4"
                      style="width:100%; padding:14px 16px; border:2px solid var(--border); border-radius:10px; font-family:inherit; font-size:1rem; resize:vertical;"
                      placeholder="Texto completo exibido ao clicar em 'Saiba Mais'..."></textarea>
        </div>
        <div class="form-group">
            <label>URL de Referência</label>
            <input type="url" id="mat-url" placeholder="https://...">
        </div>

        <!-- Vínculos com perguntas -->
        <div class="form-group">
            <label>Vincular a Perguntas do Checklist</label>
            <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 10px;">
                Este material será sugerido quando o usuário errar as perguntas selecionadas.
            </p>
            <div style="max-height: 180px; overflow-y: auto; border: 2px solid var(--border); border-radius: 10px; padding: 12px; display: flex; flex-direction: column; gap: 8px;">
                <?php foreach ($perguntas as $p): ?>
                    <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-weight: normal;">
                        <input type="checkbox" class="pergunta-check" value="<?php echo $p['id']; ?>"
                               style="margin-top: 3px; width: auto; accent-color: var(--primary);">
                        <span style="font-size: 0.85rem; color: var(--text-main); line-height: 1.4;">
                            <strong style="color: var(--primary);"><?php echo htmlspecialchars($p['categoria']); ?></strong>
                            — <?php echo htmlspecialchars($p['texto']); ?>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 15px;">
            <button class="btn-secondary" onclick="fecharModalMaterial()">Cancelar</button>
            <button class="btn-primary" onclick="salvarMaterial()">
                <i class="fas fa-save"></i> Salvar Material
            </button>
        </div>
    </div>
</div>

<!-- Modal: Confirmar deleção -->
<div id="modalDeletar" class="modal-overlay" style="display: none;">
    <div class="modal-content" style="max-width: 420px; text-align: center;">
        <div style="font-size: 3rem; margin-bottom: 15px;">🗑️</div>
        <h3 style="color: var(--text-main); margin-bottom: 10px;">Remover material?</h3>
        <p style="color: var(--text-muted); margin-bottom: 8px;">
            Você está prestes a remover <strong id="deletar-titulo"></strong>.
        </p>
        <p style="color: var(--error); font-size: 0.88rem; margin-bottom: 30px;">
            Os vínculos com perguntas também serão removidos. Esta ação não pode ser desfeita.
        </p>
        <div style="display: flex; gap: 10px; justify-content: center;">
            <button class="btn-secondary" onclick="fecharModalDeletar()">Cancelar</button>
            <button id="btnConfirmarDeletar"
                    style="background: var(--error); color: white; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-trash"></i> Sim, remover
            </button>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast" style="display:none; position:fixed; bottom:30px; right:30px; padding:16px 24px; border-radius:12px; font-weight:600; z-index:3000; box-shadow:0 4px 20px rgba(0,0,0,0.2); color:white;"></div>

<script>
const csrfToken = "<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>";

// Vínculos existentes por material (carregados do banco via PHP)
const vinculosPorMaterial = <?php
    $vinculos = [];
    $rows = (new PDO(
        'mysql:host=' . (getenv('DB_HOST') ?: '127.0.0.1') . ';port=' . (getenv('DB_PORT') ?: '3306') . ';dbname=' . (getenv('DB_NAME') ?: 'db_lgpd4devs') . ';charset=utf8mb4',
        getenv('DB_USER') ?: 'root',
        getenv('DB_PASS') ?: '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    ))->query("SELECT material_id, pergunta_id FROM pergunta_material")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) {
        $vinculos[$r['material_id']][] = (int)$r['pergunta_id'];
    }
    echo json_encode($vinculos);
?>;

// ── Modal Material ────────────────────────────────────────────────────────────

function abrirModalMaterial(material = null) {
    document.getElementById('modalTitulo').textContent = material ? 'Editar Material' : 'Novo Material';
    document.getElementById('mat-id').value             = material?.id             ?? '';
    document.getElementById('mat-titulo').value         = material?.titulo         ?? '';
    document.getElementById('mat-categoria').value      = material?.categoria      ?? '';
    document.getElementById('mat-descricao-curta').value= material?.descricao_curta ?? '';
    document.getElementById('mat-conteudo').value       = material?.conteudo_detalhado ?? '';
    document.getElementById('mat-url').value            = material?.url_referencia  ?? '';

    // Marca checkboxes dos vínculos existentes
    const checks = document.querySelectorAll('.pergunta-check');
    const vinculados = material ? (vinculosPorMaterial[material.id] || []) : [];
    checks.forEach(c => c.checked = vinculados.includes(parseInt(c.value)));

    document.getElementById('modalMaterial').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function fecharModalMaterial() {
    document.getElementById('modalMaterial').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function salvarMaterial() {
    const titulo    = document.getElementById('mat-titulo').value.trim();
    const categoria = document.getElementById('mat-categoria').value.trim();
    if (!titulo || !categoria) { mostrarToast('Título e categoria são obrigatórios.', false); return; }

    const checks = document.querySelectorAll('.pergunta-check:checked');
    const params = new URLSearchParams({
        csrf_token:          csrfToken,
        id:                  document.getElementById('mat-id').value,
        titulo,
        categoria,
        descricao_curta:     document.getElementById('mat-descricao-curta').value,
        conteudo_detalhado:  document.getElementById('mat-conteudo').value,
        url_referencia:      document.getElementById('mat-url').value,
    });
    checks.forEach(c => params.append('pergunta_ids[]', c.value));

    fetch('/admin/materiais/salvar', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: params })
        .then(r => r.json())
        .then(data => {
            if (data.sucesso) { mostrarToast('Material salvo com sucesso!', true); setTimeout(() => location.reload(), 1200); }
            else mostrarToast(data.mensagem, false);
        });
}

// ── Modal Deletar ─────────────────────────────────────────────────────────────

function confirmarDeletar(id, titulo) {
    document.getElementById('deletar-titulo').textContent = titulo;
    document.getElementById('btnConfirmarDeletar').onclick = () => deletarMaterial(id);
    document.getElementById('modalDeletar').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function fecharModalDeletar() {
    document.getElementById('modalDeletar').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function deletarMaterial(id) {
    fetch('/admin/materiais/deletar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ csrf_token: csrfToken, id })
    })
    .then(r => r.json())
    .then(data => {
        if (data.sucesso) { mostrarToast('Material removido.', true); setTimeout(() => location.reload(), 1200); }
        else mostrarToast(data.mensagem, false);
    });
}

// ── Toast ─────────────────────────────────────────────────────────────────────

function mostrarToast(msg, sucesso) {
    const t = document.getElementById('toast');
    t.textContent = (sucesso ? '✅ ' : '❌ ') + msg;
    t.style.background = sucesso ? 'var(--secondary)' : 'var(--error)';
    t.style.display = 'block';
    setTimeout(() => t.style.display = 'none', 3000);
}

window.addEventListener('click', e => {
    if (e.target.id === 'modalMaterial') fecharModalMaterial();
    if (e.target.id === 'modalDeletar')  fecharModalDeletar();
});
</script>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>