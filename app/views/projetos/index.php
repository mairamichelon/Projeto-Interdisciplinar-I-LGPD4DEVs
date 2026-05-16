<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<main class="container content-area">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1 style="margin-bottom: 5px;">Meus Projetos</h1>
            <p style="color: var(--text-muted);">Gerencie seus projetos e acompanhe a evolução de conformidade.</p>
        </div>
        <button onclick="abrirModalNovoProjeto()" class="btn-primary">
            <i class="fas fa-plus"></i> Novo Projeto
        </button>
    </div>

    <?php if (empty($projetos)): ?>
        <div class="card-pergunta" style="text-align: center; padding: 60px 40px;">
            <i class="fas fa-folder-open" style="font-size: 3rem; color: var(--border); margin-bottom: 20px; display: block;"></i>
            <h3 style="color: var(--text-muted); margin-bottom: 15px;">Nenhum projeto criado ainda</h3>
            <p style="color: var(--text-muted); margin-bottom: 30px;">
                Faça o checklist e salve o resultado vinculando a um projeto para começar a rastrear seu progresso.
            </p>
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <button onclick="abrirModalNovoProjeto()" class="btn-primary">
                    <i class="fas fa-plus"></i> Criar Primeiro Projeto
                </button>
                <a href="/checklist" class="btn-secondary">Fazer Checklist</a>
            </div>
        </div>

    <?php else: ?>
        <div class="materiais-grid">
            <?php foreach ($projetos as $p): ?>
                <div class="card-material" style="border-top: 4px solid <?php echo Projeto::corStatus($p['status']); ?>;">

                    <!-- Header do card -->
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 12px;">
                        <div style="flex: 1;">
                            <span style="display: inline-block; background: <?php echo Projeto::corStatus($p['status']); ?>22; color: <?php echo Projeto::corStatus($p['status']); ?>; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; margin-bottom: 8px;">
                                <?php echo Projeto::labelStatus($p['status']); ?>
                            </span>
                            <h3 style="margin: 0 0 5px; font-size: 1.1rem;"><?php echo htmlspecialchars($p['nome']); ?></h3>
                            <span class="badge-categoria" style="margin-bottom: 0;">
                                👥 <?php echo Projeto::labelPublicoAlvo($p['publico_alvo']); ?>
                            </span>
                        </div>
                    </div>

                    <!-- Descrição -->
                    <?php if ($p['descricao']): ?>
                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 15px; line-height: 1.5;">
                            <?php echo htmlspecialchars($p['descricao']); ?>
                        </p>
                    <?php endif; ?>

                    <!-- Último diagnóstico -->
                    <?php if ($p['ultimo_percentual'] !== null): ?>
                        <div style="background: #F8FAFC; border-radius: 10px; padding: 12px; margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                <span style="font-size: 0.8rem; color: var(--text-muted);">Último diagnóstico</span>
                                <span style="font-weight: 700; color: <?php echo ($p['ultimo_percentual'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>;">
                                    <?php echo $p['ultimo_percentual']; ?>%
                                </span>
                            </div>
                            <div style="background: #E2E8F0; height: 6px; border-radius: 10px; overflow: hidden;">
                                <div style="width: <?php echo $p['ultimo_percentual']; ?>%; height: 100%; background: <?php echo ($p['ultimo_percentual'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>;"></div>
                            </div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 5px;">
                                <?php echo date('d/m/Y', strtotime($p['ultima_data'])); ?>
                                &nbsp;·&nbsp;
                                <?php echo $p['total_diagnosticos']; ?> diagnóstico(s) no total
                            </div>
                        </div>
                    <?php else: ?>
                        <div style="background: #F8FAFC; border-radius: 10px; padding: 12px; margin-bottom: 15px; text-align: center;">
                            <span style="font-size: 0.85rem; color: var(--text-muted);">Nenhum diagnóstico ainda</span>
                        </div>
                    <?php endif; ?>

                    <!-- Ações -->
                    <div style="display: flex; gap: 8px; margin-top: auto; flex-wrap: wrap;">
                        <a href="/projetos/detalhe?id=<?php echo $p['id']; ?>" class="btn-primary" style="flex: 1; font-size: 0.85rem; padding: 8px 12px; text-align: center;">
                            <i class="fas fa-chart-line"></i> Diagnósticos
                        </a>
                        <a href="/checklist" class="btn-secondary" style="font-size: 0.85rem; padding: 8px 12px;">
                            <i class="fas fa-plus"></i>
                        </a>
                        <button onclick='abrirModalEditar(<?php echo json_encode($p); ?>)' style="background: transparent; border: 2px solid var(--border); color: var(--text-muted); padding: 8px 12px; border-radius: 10px; cursor: pointer; font-size: 0.85rem;">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="/projetos/deletar" method="POST" onsubmit="return confirm('Remover este projeto e todos os seus diagnósticos?');" style="margin: 0;">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                            <input type="hidden" name="projeto_id" value="<?php echo $p['id']; ?>">
                            <button type="submit" style="background: transparent; border: 2px solid var(--error); color: var(--error); padding: 8px 12px; border-radius: 10px; cursor: pointer; font-size: 0.85rem;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<!-- Modal: Novo Projeto -->
<div id="modalNovoProjeto" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width: 520px;">
        <h2 style="color: var(--primary); margin-bottom: 20px;">Novo Projeto</h2>

        <div class="form-group">
            <label>Nome do Projeto *</label>
            <input type="text" id="np-nome" placeholder="Ex: App Escola Virtual" maxlength="200">
        </div>
        <div class="form-group">
            <label>Descrição</label>
            <textarea id="np-descricao" placeholder="Descreva brevemente o projeto..." rows="3" style="width:100%; padding:14px 16px; border:2px solid var(--border); border-radius:10px; font-family:inherit; font-size:1rem; resize:vertical;"></textarea>
        </div>
        <div class="form-group">
            <label>Público-alvo</label>
            <select id="np-publico" style="width:100%; padding:14px 16px; border:2px solid var(--border); border-radius:10px; font-size:1rem; font-family:inherit;">
                <option value="criancas">Crianças</option>
                <option value="adolescentes">Adolescentes</option>
                <option value="ambos" selected>Ambos</option>
                <option value="outros">Outros</option>
            </select>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select id="np-status" style="width:100%; padding:14px 16px; border:2px solid var(--border); border-radius:10px; font-size:1rem; font-family:inherit;">
                <option value="em_desenvolvimento" selected>Em Desenvolvimento</option>
                <option value="em_producao">Em Produção</option>
                <option value="arquivado">Arquivado</option>
            </select>
        </div>

        <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 10px;">
            <button class="btn-secondary" onclick="fecharModalNovoProjeto()">Cancelar</button>
            <button class="btn-primary" onclick="criarProjeto()">Criar Projeto</button>
        </div>
    </div>
</div>

<!-- Modal: Editar Projeto -->
<div id="modalEditar" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width: 520px;">
        <h2 style="color: var(--primary); margin-bottom: 20px;">Editar Projeto</h2>
        <form action="/projetos/editar" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <input type="hidden" name="projeto_id" id="edit-id">
            <div class="form-group">
                <label>Nome do Projeto *</label>
                <input type="text" name="nome" id="edit-nome" maxlength="200" required>
            </div>
            <div class="form-group">
                <label>Descrição</label>
                <textarea name="descricao" id="edit-descricao" rows="3" style="width:100%; padding:14px 16px; border:2px solid var(--border); border-radius:10px; font-family:inherit; font-size:1rem; resize:vertical;"></textarea>
            </div>
            <div class="form-group">
                <label>Público-alvo</label>
                <select name="publico_alvo" id="edit-publico" style="width:100%; padding:14px 16px; border:2px solid var(--border); border-radius:10px; font-size:1rem; font-family:inherit;">
                    <option value="criancas">Crianças</option>
                    <option value="adolescentes">Adolescentes</option>
                    <option value="ambos">Ambos</option>
                    <option value="outros">Outros</option>
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" id="edit-status" style="width:100%; padding:14px 16px; border:2px solid var(--border); border-radius:10px; font-size:1rem; font-family:inherit;">
                    <option value="em_desenvolvimento">Em Desenvolvimento</option>
                    <option value="em_producao">Em Produção</option>
                    <option value="arquivado">Arquivado</option>
                </select>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 10px;">
                <button type="button" class="btn-secondary" onclick="fecharModalEditar()">Cancelar</button>
                <button type="submit" class="btn-primary">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>

<script>
const csrfToken = "<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>";

function abrirModalNovoProjeto() {
    document.getElementById('modalNovoProjeto').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function fecharModalNovoProjeto() {
    document.getElementById('modalNovoProjeto').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function abrirModalEditar(projeto) {
    document.getElementById('edit-id').value       = projeto.id;
    document.getElementById('edit-nome').value     = projeto.nome;
    document.getElementById('edit-descricao').value = projeto.descricao || '';
    document.getElementById('edit-publico').value  = projeto.publico_alvo;
    document.getElementById('edit-status').value   = projeto.status;
    document.getElementById('modalEditar').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function fecharModalEditar() {
    document.getElementById('modalEditar').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function criarProjeto() {
    const nome = document.getElementById('np-nome').value.trim();
    if (!nome) { alert('O nome do projeto é obrigatório.'); return; }

    fetch('/projetos/criar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            csrf_token:   csrfToken,
            nome:         nome,
            descricao:    document.getElementById('np-descricao').value,
            publico_alvo: document.getElementById('np-publico').value,
            status:       document.getElementById('np-status').value,
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.sucesso) window.location.reload();
        else alert('Erro: ' + data.mensagem);
    });
}

window.onclick = function(e) {
    if (e.target.id === 'modalNovoProjeto') fecharModalNovoProjeto();
    if (e.target.id === 'modalEditar')      fecharModalEditar();
}
</script>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>