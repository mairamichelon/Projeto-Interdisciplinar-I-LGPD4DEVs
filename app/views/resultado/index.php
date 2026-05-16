<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<main class="container content-area">
    <?php if ($isGuest && $percentual !== null): ?>
        <div class="hero-note" style="margin-bottom: 30px; border-color: #FFEEBA; background: #FFF3CD; color: #856404; justify-content: center;">
            <span><strong>Aviso:</strong> Este diagnóstico é temporário. <a href="/cadastro">Crie uma conta</a> para salvar permanentemente.</span>
        </div>
    <?php endif; ?>

    <div class="checklist-header">
        <h1>Diagnóstico de Conformidade</h1>
        <p>Relatório técnico gerado para o projeto <strong>LGPD4DEVS</strong>.</p>
    </div>

    <?php if ($percentual !== null): ?>
        <div class="card-pergunta text-center" style="padding: 40px; margin-bottom: 50px;">
            <div style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 10px;">Nível de Adequação</div>
            <h2 style="font-size: 4.5rem; color: <?php echo ($percentual >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>; line-height: 1; margin-bottom: 20px;">
                <?php echo $percentual; ?>%
            </h2>
            <div style="background: #E2E8F0; height: 14px; border-radius: 10px; width: 100%; max-width: 500px; margin: 0 auto 20px; overflow: hidden;">
                <div style="width: <?php echo $percentual; ?>%; height: 100%; background: <?php echo ($percentual >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>; transition: width 1.5s ease-in-out;"></div>
            </div>
            <p style="font-size: 1.1rem; color: var(--text-main); font-weight: 500;">
                <?php
                    if ($percentual == 100)      echo "Conformidade Total detectada!";
                    elseif ($percentual >= 70)   echo "Nível satisfatório, mas existem melhorias pendentes.";
                    else                         echo "Atenção: Foram detectadas falhas críticas de privacidade.";
                ?>
            </p>
        </div>

        <?php if (count($falhas) > 0): ?>
            <div style="margin-top: 40px;">
                <h3 style="margin-bottom: 30px; color: var(--text-main); display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-clipboard-list" style="color: var(--error);"></i>
                    Plano de Ação Recomendado
                </h3>
                <?php foreach ($falhas as $f): ?>
                    <div class="card-pergunta card-relatorio" style="border-left: 5px solid var(--error); margin-bottom: 25px;">
                        <p style="font-weight: 600; font-size: 1.1rem; margin-bottom: 15px;">Item em Aberto: <?php echo htmlspecialchars($f['texto']); ?></p>
                        <div class="guia-tecnico-box" style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #F1F5F9;">
                            <small style="color: var(--text-muted); font-weight: 700; display: block; margin-bottom: 10px;">GUIA TÉCNICO DE CORREÇÃO:</small>
                            <?php if ($f['materiais_titulos']):
                                $titulos   = explode('||', $f['materiais_titulos']);
                                $urls      = explode('||', $f['materiais_urls']);
                                $conteudos = explode('||', $f['materiais_conteudos']);
                            ?>
                                <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                                    <?php foreach ($titulos as $key => $titulo): ?>
                                        <button type="button" class="badge-categoria material-btn"
                                                onclick='abrirModal(<?php echo json_encode($titulo); ?>, <?php echo json_encode($conteudos[$key]); ?>, <?php echo json_encode($urls[$key]); ?>)'>
                                            📖 <?php echo htmlspecialchars($titulo); ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p style="font-size: 0.9rem; color: var(--text-muted);">Consulte o encarregado de dados para orientações específicas.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div style="margin-top: 60px; text-align: center; border-top: 1px solid var(--border); padding-top: 40px;" class="no-print">
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <a href="/checklist" class="btn-secondary">Refazer Checklist</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button onclick="abrirModalSalvar()" id="btnSave" class="btn-primary" style="background-color: var(--secondary); border: none;">
                        <i class="fas fa-save"></i> Salvar Resultado
                    </button>
                    <button onclick="abrirModalProjetos()" class="btn-secondary">
                        <i class="fas fa-folder"></i> Meus Projetos
                    </button>
                <?php endif; ?>
                <button onclick="window.print()" class="btn-secondary">
                    <i class="fas fa-print"></i> Exportar (PDF)
                </button>
            </div>
            <br>
            <a href="/" style="color: var(--text-muted); text-decoration: none; font-weight: 500;">Voltar ao Início</a>
        </div>
    <?php endif; ?>
</main>

<?php if (isset($_SESSION['user_id'])): ?>
<?php
    $projetoModel    = new Projeto();
    $projetosUsuario = $projetoModel->buscarPorUsuario((int)$_SESSION['user_id']);
?>

<!-- Modal: Consultar Projetos -->
<div id="modalProjetos" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width: 600px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
            <h2 style="color: var(--primary); margin: 0;">Meus Projetos</h2>
            <button onclick="fecharModalProjetos()" style="background: transparent; border: none; font-size: 1.4rem; cursor: pointer; color: var(--text-muted);">✕</button>
        </div>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 20px;">
            Consulte seus projetos e decida se deseja salvar este diagnóstico.
        </p>

        <?php if (!empty($projetosUsuario)): ?>
            <div style="display: flex; flex-direction: column; gap: 12px; max-height: 350px; overflow-y: auto; padding-right: 5px;">
                <?php foreach ($projetosUsuario as $p): ?>
                    <div style="border: 1px solid var(--border); border-left: 4px solid <?php echo Projeto::corStatus($p['status']); ?>; border-radius: 10px; padding: 15px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                            <div>
                                <div style="font-weight: 600; color: var(--text-main); margin-bottom: 4px;">
                                    <?php echo htmlspecialchars($p['nome']); ?>
                                </div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">
                                    <?php echo Projeto::labelPublicoAlvo($p['publico_alvo']); ?>
                                    &nbsp;·&nbsp;
                                    <?php echo Projeto::labelStatus($p['status']); ?>
                                    &nbsp;·&nbsp;
                                    <?php echo $p['total_diagnosticos']; ?> diagnóstico(s)
                                </div>
                            </div>
                            <?php if ($p['ultimo_percentual'] !== null): ?>
                                <div style="text-align: center;">
                                    <div style="font-size: 1.3rem; font-weight: 700; color: <?php echo ($p['ultimo_percentual'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>;">
                                        <?php echo $p['ultimo_percentual']; ?>%
                                    </div>
                                    <div style="font-size: 0.72rem; color: var(--text-muted);">último</div>
                                </div>
                            <?php else: ?>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">Sem diagnósticos</div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 30px 0; color: var(--text-muted);">
                <i class="fas fa-folder-open" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                Nenhum projeto criado ainda.
            </div>
        <?php endif; ?>

        <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; padding-top: 15px; border-top: 1px solid var(--border);">
            <button class="btn-secondary" onclick="fecharModalProjetos()">Fechar</button>
            <button class="btn-primary" style="background-color: var(--secondary); border: none;"
                    onclick="fecharModalProjetos(); abrirModalSalvar();">
                <i class="fas fa-save"></i> Salvar Diagnóstico
            </button>
        </div>
    </div>
</div>

<!-- Modal: Salvar em Projeto -->
<div id="modalSalvar" class="modal-overlay" style="display:none;">
    <div class="modal-content" style="max-width: 520px;">
        <h2 style="color: var(--primary); margin-bottom: 5px;">Salvar Diagnóstico</h2>
        <p style="color: var(--text-muted); margin-bottom: 25px; font-size: 0.9rem;">Vincule este resultado a um projeto para acompanhar sua evolução.</p>

        <div style="display: flex; gap: 0; margin-bottom: 25px; border-bottom: 2px solid var(--border);">
            <button id="tab-existente" onclick="trocarTab('existente')"
                style="flex:1; padding:10px; border:none; background:transparent; font-weight:600; color:var(--primary); border-bottom:3px solid var(--primary); cursor:pointer; font-size:0.95rem; margin-bottom:-2px;">
                Projeto Existente
            </button>
            <button id="tab-novo" onclick="trocarTab('novo')"
                style="flex:1; padding:10px; border:none; background:transparent; font-weight:600; color:var(--text-muted); cursor:pointer; font-size:0.95rem;">
                Novo Projeto
            </button>
        </div>

        <div id="painel-existente">
            <?php if (!empty($projetosUsuario)): ?>
                <div class="form-group">
                    <label>Selecione o Projeto</label>
                    <select id="select-projeto" style="width:100%; padding:14px 16px; border:2px solid var(--border); border-radius:10px; font-size:1rem; font-family:inherit;">
                        <?php foreach ($projetosUsuario as $p): ?>
                            <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['nome']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php else: ?>
                <p style="color: var(--text-muted); text-align: center; padding: 20px 0;">
                    Nenhum projeto ainda. Crie um novo abaixo.
                </p>
            <?php endif; ?>
        </div>

        <div id="painel-novo" style="display:none;">
            <div class="form-group">
                <label>Nome do Projeto *</label>
                <input type="text" id="novo-nome" placeholder="Ex: App Escola Virtual" maxlength="200">
            </div>
            <div class="form-group">
                <label>Descrição</label>
                <textarea id="novo-descricao" rows="2"
                    style="width:100%; padding:14px 16px; border:2px solid var(--border); border-radius:10px; font-family:inherit; font-size:1rem; resize:vertical;"></textarea>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Público-alvo</label>
                    <select id="novo-publico" style="width:100%; padding:12px 14px; border:2px solid var(--border); border-radius:10px; font-size:0.95rem; font-family:inherit;">
                        <option value="criancas">Crianças</option>
                        <option value="adolescentes">Adolescentes</option>
                        <option value="ambos" selected>Ambos</option>
                        <option value="outros">Outros</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select id="novo-status" style="width:100%; padding:12px 14px; border:2px solid var(--border); border-radius:10px; font-size:0.95rem; font-family:inherit;">
                        <option value="em_desenvolvimento" selected>Em Desenvolvimento</option>
                        <option value="em_producao">Em Produção</option>
                        <option value="arquivado">Arquivado</option>
                    </select>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 10px;">
            <button class="btn-secondary" onclick="fecharModalSalvar()">Cancelar</button>
            <button class="btn-primary" onclick="confirmarSalvar()" id="btnConfirmarSalvar">
                <i class="fas fa-save"></i> Salvar
            </button>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Modal de Material -->
<div id="modalMaterial" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <h2 id="m-titulo" style="color: var(--primary);"></h2>
        <hr style="margin: 15px 0; border: 0; border-top: 1px solid var(--border);">
        <div id="m-corpo" style="margin-bottom: 25px; color: var(--text-muted); line-height: 1.6; max-height: 350px; overflow-y: auto;"></div>
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button class="btn-secondary" onclick="fecharModal()">Fechar</button>
            <a id="m-link" href="#" target="_blank" class="btn-primary">Ver PDF Completo</a>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast" style="display:none; position:fixed; top:30px; right:30px; background:#00CC66; color:white; padding:20px 35px; border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,0.25); z-index:9999; font-size:1.1rem; min-width:300px;">
    <div style="display:flex; align-items:center; gap:15px;">
        <i class="fas fa-check-circle" style="font-size:1.6rem;"></i>
        <span id="toast-msg" style="font-weight:600;"></span>
    </div>
</div>

<script>
const csrfToken = "<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>";
let tabAtual = 'existente';

function abrirModalProjetos() {
    document.getElementById('modalProjetos').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function fecharModalProjetos() {
    document.getElementById('modalProjetos').style.display = 'none';
    document.body.style.overflow = 'auto';
}
function abrirModalSalvar() {
    document.getElementById('modalSalvar').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function fecharModalSalvar() {
    document.getElementById('modalSalvar').style.display = 'none';
    document.body.style.overflow = 'auto';
}
function trocarTab(tab) {
    tabAtual = tab;
    document.getElementById('painel-existente').style.display  = tab === 'existente' ? 'block' : 'none';
    document.getElementById('painel-novo').style.display       = tab === 'novo'      ? 'block' : 'none';
    document.getElementById('tab-existente').style.color        = tab === 'existente' ? 'var(--primary)' : 'var(--text-muted)';
    document.getElementById('tab-existente').style.borderBottom = tab === 'existente' ? '3px solid var(--primary)' : 'none';
    document.getElementById('tab-novo').style.color             = tab === 'novo'      ? 'var(--primary)' : 'var(--text-muted)';
    document.getElementById('tab-novo').style.borderBottom      = tab === 'novo'      ? '3px solid var(--primary)' : 'none';
}
function confirmarSalvar() {
    const btn = document.getElementById('btnConfirmarSalvar');
    btn.innerHTML = "<i class='fas fa-spinner fa-spin'></i> Salvando...";
    btn.disabled  = true;

    let body = 'csrf_token=' + encodeURIComponent(csrfToken);
    if (tabAtual === 'existente') {
        const sel = document.getElementById('select-projeto');
        if (sel) body += '&projeto_id=' + sel.value;
    } else {
        const nome = document.getElementById('novo-nome').value.trim();
        if (!nome) {
            alert('O nome do projeto é obrigatório.');
            btn.innerHTML = "<i class='fas fa-save'></i> Salvar";
            btn.disabled  = false;
            return;
        }
        body += '&nome_projeto='  + encodeURIComponent(nome);
        body += '&descricao='     + encodeURIComponent(document.getElementById('novo-descricao').value);
        body += '&publico_alvo='  + encodeURIComponent(document.getElementById('novo-publico').value);
        body += '&status='        + encodeURIComponent(document.getElementById('novo-status').value);
    }

    fetch('/salvar-resultado', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body
    })
    .then(r => r.json())
    .then(data => {
        fecharModalSalvar();
        if (data.sucesso) {
            showToast(data.mensagem);
            document.getElementById('btnSave').innerHTML     = "<i class='fas fa-check'></i> Salvo com Sucesso";
            document.getElementById('btnSave').style.opacity = "0.7";
            document.getElementById('btnSave').disabled      = true;
        } else {
            showToast('Erro: ' + data.mensagem, true);
            btn.innerHTML = "<i class='fas fa-save'></i> Salvar";
            btn.disabled  = false;
        }
    })
    .catch(() => {
        showToast('Erro de conexão. Tente novamente.', true);
        btn.innerHTML = "<i class='fas fa-save'></i> Salvar";
        btn.disabled  = false;
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
function showToast(msg, erro = false) {
    const toast = document.getElementById('toast');
    toast.style.background = erro ? '#EF4444' : '#00CC66';
    document.getElementById('toast-msg').innerText = msg;
    toast.style.display = 'block';
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => { toast.style.display = 'none'; toast.style.opacity = '1'; }, 500);
    }, 4000);
}
window.onclick = function(e) {
    if (e.target.id === 'modalProjetos') fecharModalProjetos();
    if (e.target.id === 'modalSalvar')   fecharModalSalvar();
    if (e.target.id === 'modalMaterial') fecharModal();
}
</script>

<style>
.material-btn { cursor:pointer; border:2px solid transparent; padding:8px 15px; font-size:0.75rem; transition:all 0.2s; }
.material-btn:hover { background-color:var(--primary) !important; color:white !important; transform:translateY(-2px); }
@media print {
    .no-print, .navbar, .main-footer, .hero-note, .btn-primary, .btn-secondary { display:none !important; }
    .card-relatorio { page-break-inside:avoid !important; break-inside:avoid !important; border:1px solid #ddd !important; box-shadow:none !important; margin-bottom:20px !important; display:block !important; }
    .badge-categoria { display:inline-block !important; background:#f1f5f9 !important; border:1px solid #cbd5e1 !important; color:#0066FF !important; padding:5px 10px !important; border-radius:5px !important; font-size:0.8rem !important; }
    .container { width:100% !important; max-width:100% !important; margin:0 !important; padding:0 !important; }
    body { background:white !important; color:black !important; }
}
</style>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>