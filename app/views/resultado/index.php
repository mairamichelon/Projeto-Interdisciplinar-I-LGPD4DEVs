<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<!-- Barra de ações sticky -->
<div class="barra-acoes no-print">
    <div class="container" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
        <span style="font-weight: 700; color: var(--text-main); font-size: 0.95rem;">
            <i class="fas fa-clipboard-check" style="color: var(--primary);"></i>
            Relatório de Conformidade
        </span>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <?php if (isset($_SESSION['user_id'])): ?>
                <button onclick="abrirModalSalvar()" class="btn-primary" style="padding: 8px 18px; font-size: 0.88rem; min-width: unset;">
                    <i class="fas fa-save"></i> Salvar
                </button>
            <?php else: ?>
                <a href="/cadastro" class="btn-primary" style="padding: 8px 18px; font-size: 0.88rem; min-width: unset;">
                    <i class="fas fa-user-plus"></i> Criar conta para salvar
                </a>
            <?php endif; ?>
            <a href="/materiais" class="btn-secondary" style="padding: 8px 18px; font-size: 0.88rem; min-width: unset;">
                <i class="fas fa-book"></i> Materiais
            </a>
            <button onclick="window.print()" class="btn-secondary" style="padding: 8px 18px; font-size: 0.88rem; min-width: unset;">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </button>
            <a href="/checklist" class="btn-secondary" style="padding: 8px 18px; font-size: 0.88rem; min-width: unset;">
                <i class="fas fa-redo"></i> Refazer
            </a>
        </div>
    </div>
</div>

<main class="container" style="padding-top: 30px; padding-bottom: 60px;">

    <?php if ($percentual === null): ?>
        <div class="card-pergunta" style="text-align: center; padding: 60px 40px; margin-top: 40px;">
            <i class="fas fa-clipboard-list" style="font-size: 3rem; color: var(--border); margin-bottom: 20px; display: block;"></i>
            <h3 style="color: var(--text-muted); margin-bottom: 15px;">Nenhuma resposta encontrada</h3>
            <p style="color: var(--text-muted); margin-bottom: 30px;">Responda o checklist para gerar seu relatório.</p>
            <a href="/checklist" class="btn-primary">Fazer Checklist</a>
        </div>

    <?php else: ?>

        <?php
            $cor        = ($percentual >= 70) ? 'var(--secondary)' : 'var(--error)';
            $totalItens = count($dadosParaCalculo);
            $okCount    = $totalItens - count($falhas);

            if ($percentual === 100)   $labelNivel = "Conformidade Total";
            elseif ($percentual >= 70) $labelNivel = "Nível Satisfatório";
            elseif ($percentual >= 40) $labelNivel = "Conformidade Parcial";
            else                       $labelNivel = "Falhas Críticas Detectadas";
        ?>

        <!-- Score -->
        <div class="card-pergunta" style="padding: 30px 40px; margin-bottom: 30px; border-top: 5px solid <?php echo $cor; ?>;">
            <div style="display: flex; align-items: center; gap: 40px; flex-wrap: wrap; justify-content: center;">
                <div style="text-align: center; min-width: 120px;">
                    <div style="font-size: 3.8rem; font-weight: 800; color: <?php echo $cor; ?>; line-height: 1;">
                        <?php echo $percentual; ?>%
                    </div>
                    <div style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-top: 4px;">
                        Adequação
                    </div>
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <div style="font-size: 1.05rem; font-weight: 700; color: <?php echo $cor; ?>; margin-bottom: 10px;">
                        <?php echo $labelNivel; ?>
                    </div>
                    <div style="background: #E2E8F0; height: 12px; border-radius: 10px; overflow: hidden; margin-bottom: 12px;">
                        <div style="width: <?php echo $percentual; ?>%; height: 100%; background: <?php echo $cor; ?>;"></div>
                    </div>
                    <div style="display: flex; gap: 25px; flex-wrap: wrap;">
                        <span style="font-size: 0.85rem; color: var(--secondary); font-weight: 600;">✅ <?php echo $okCount; ?> conformes</span>
                        <span style="font-size: 0.85rem; color: var(--error); font-weight: 600;">❌ <?php echo count($falhas); ?> em aberto</span>
                        <span style="font-size: 0.85rem; color: var(--primary); font-weight: 600;">🏆 <?php echo $obtidos; ?>/<?php echo $totalPontos; ?> pontos</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── ITENS COM FALHA ── -->
        <?php if (!empty($falhas)): ?>
            <?php
                $porCategoria = [];
                foreach ($falhas as $item) {
                    $porCategoria[$item['categoria'] ?? 'Geral'][] = $item;
                }
            ?>

            <!-- Cabeçalho igual ao de conformidade -->
            <button onclick="toggleGrupoFalhas(this)"
                    style="display: flex; align-items: center; justify-content: space-between; width: 100%; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 12px; padding: 14px 20px; cursor: pointer; font-size: 0.92rem; font-weight: 600; color: var(--error); margin-bottom: 10px;">
                <span><i class="fas fa-exclamation-circle"></i> <?php echo count($falhas); ?> itens em não conformidade</span>
                <i class="fas fa-chevron-up" style="transition: transform 0.3s;"></i>
            </button>

            <div id="grupoFalhas">
                <?php foreach ($porCategoria as $categoria => $itens): ?>
                    <div style="margin-bottom: 22px;">
                        <span class="badge-categoria"><?php echo htmlspecialchars($categoria); ?></span>

                        <?php foreach ($itens as $item): ?>
                            <?php
                                $temMateriais = !empty($item['materiais_titulos']);
                                $titulos   = $temMateriais ? explode('||', $item['materiais_titulos']) : [];
                                $urls      = $temMateriais ? explode('||', $item['materiais_urls']      ?? '') : [];
                                $conteudos = $temMateriais ? explode('||', $item['materiais_conteudos'] ?? '') : [];
                                $uid = 'f-' . $item['id'];
                            ?>
                            <div class="accordion-card" style="border-left: 5px solid var(--error);">
                                <div class="accordion-header" onclick="toggleAccordion('<?php echo $uid; ?>')">
                                    <div style="display: flex; align-items: flex-start; gap: 12px; flex: 1;">
                                        <span style="font-size: 1.1rem; margin-top: 1px; flex-shrink: 0;">❌</span>
                                        <div>
                                            <p style="margin: 0 0 4px; color: var(--text-main); font-weight: 500; font-size: 0.95rem; line-height: 1.4;">
                                                <?php echo htmlspecialchars($item['texto']); ?>
                                            </p>
                                            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                                <span style="font-size: 0.78rem; color: var(--text-muted);">Peso: <?php echo $item['peso']; ?></span>
                                                <?php if ($temMateriais): ?>
                                                    <span style="font-size: 0.78rem; color: var(--primary); font-weight: 600;">
                                                        <i class="fas fa-book-open"></i> <?php echo count(array_filter($titulos, fn($t) => trim($t))); ?> material(is) disponível(is)
                                                    </span>
                                                <?php else: ?>
                                                    <span style="font-size: 0.78rem; color: var(--text-muted);">Sem materiais vinculados</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <i id="icon-<?php echo $uid; ?>" class="fas fa-chevron-down accordion-icon"></i>
                                </div>

                                <div id="<?php echo $uid; ?>" class="accordion-body" style="display: none;">
                                    <?php if ($temMateriais): ?>
                                        <div style="padding: 16px 20px 20px; border-top: 1px solid #F1F5F9;">
                                            <p style="font-size: 0.82rem; font-weight: 700; color: var(--primary); margin-bottom: 12px;">
                                                <i class="fas fa-book-open"></i> Materiais recomendados:
                                            </p>
                                            <div style="display: flex; flex-direction: column; gap: 10px;">
                                                <?php foreach ($titulos as $i => $titulo): ?>
                                                    <?php if (!trim($titulo)) continue; ?>
                                                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 14px 16px;">
                                                        <div style="font-weight: 700; color: var(--text-main); font-size: 0.88rem; margin-bottom: 5px;">
                                                            📄 <?php echo htmlspecialchars(trim($titulo)); ?>
                                                        </div>
                                                        <?php if (!empty(trim($conteudos[$i] ?? ''))): ?>
                                                            <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0 0 10px; line-height: 1.5;">
                                                                <?php echo htmlspecialchars(mb_substr(trim($conteudos[$i]), 0, 200)) . (mb_strlen(trim($conteudos[$i])) > 200 ? '...' : ''); ?>
                                                            </p>
                                                        <?php endif; ?>
                                                        <?php if (!empty(trim($urls[$i] ?? '')) && trim($urls[$i]) !== '#'): ?>
                                                            <a href="<?php echo htmlspecialchars(trim($urls[$i])); ?>" target="_blank"
                                                               class="btn-primary" style="font-size: 0.8rem; padding: 7px 14px; min-width: unset; display: inline-flex; gap: 6px;">
                                                                <i class="fas fa-external-link-alt"></i> Acessar material
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div style="padding: 16px 20px 20px; border-top: 1px solid #F1F5F9; text-align: center;">
                                            <p style="color: var(--text-muted); font-size: 0.88rem; margin: 0;">
                                                Nenhum material vinculado a este item.
                                                <a href="/materiais" style="color: var(--primary); font-weight: 600; text-decoration: none;">Ver biblioteca →</a>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- ── ITENS CONFORMES ── -->
        <?php
            $conformes = array_filter($dadosParaCalculo, fn($i) => $i['resposta'] == 1);
        ?>
        <?php if (!empty($conformes)): ?>
            <div style="margin-top: 15px;">
                <button onclick="toggleGrupoConformes(this)"
                        style="display: flex; align-items: center; justify-content: space-between; width: 100%; background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 12px; padding: 14px 20px; cursor: pointer; font-size: 0.92rem; font-weight: 600; color: var(--secondary); margin-bottom: 10px;">
                    <span><i class="fas fa-check-circle"></i> <?php echo count($conformes); ?> itens em conformidade</span>
                    <i class="fas fa-chevron-down" style="transition: transform 0.3s;"></i>
                </button>

                <div id="grupoConformes" style="display: none;">
                    <?php foreach ($conformes as $item): ?>
                        <?php
                            $temMateriais = !empty($item['materiais_titulos']);
                            $titulos   = $temMateriais ? explode('||', $item['materiais_titulos']) : [];
                            $urls      = $temMateriais ? explode('||', $item['materiais_urls']      ?? '') : [];
                            $conteudos = $temMateriais ? explode('||', $item['materiais_conteudos'] ?? '') : [];
                            $uid = 'c-' . $item['id'];
                        ?>
                        <div class="accordion-card" style="border-left: 5px solid var(--secondary);">
                            <div class="accordion-header" onclick="toggleAccordion('<?php echo $uid; ?>')">
                                <div style="display: flex; align-items: flex-start; gap: 12px; flex: 1;">
                                    <span style="font-size: 1.1rem; margin-top: 1px; flex-shrink: 0;">✅</span>
                                    <div>
                                        <p style="margin: 0 0 4px; color: var(--text-main); font-weight: 500; font-size: 0.95rem; line-height: 1.4;">
                                            <?php echo htmlspecialchars($item['texto']); ?>
                                        </p>
                                        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                            <span style="font-size: 0.78rem; color: var(--text-muted);">Peso: <?php echo $item['peso']; ?></span>
                                            <?php if ($temMateriais): ?>
                                                <span style="font-size: 0.78rem; color: var(--secondary); font-weight: 600;">
                                                    <i class="fas fa-book-open"></i> <?php echo count(array_filter($titulos, fn($t) => trim($t))); ?> material(is) disponível(is)
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <i id="icon-<?php echo $uid; ?>" class="fas fa-chevron-down accordion-icon"></i>
                            </div>

                            <div id="<?php echo $uid; ?>" class="accordion-body" style="display: none;">
                                <?php if ($temMateriais): ?>
                                    <div style="padding: 16px 20px 20px; border-top: 1px solid #F1F5F9;">
                                        <p style="font-size: 0.82rem; font-weight: 700; color: var(--secondary); margin-bottom: 12px;">
                                            <i class="fas fa-book-open"></i> Materiais de referência:
                                        </p>
                                        <div style="display: flex; flex-direction: column; gap: 10px;">
                                            <?php foreach ($titulos as $i => $titulo): ?>
                                                <?php if (!trim($titulo)) continue; ?>
                                                <div style="background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 10px; padding: 14px 16px;">
                                                    <div style="font-weight: 700; color: var(--text-main); font-size: 0.88rem; margin-bottom: 5px;">
                                                        📄 <?php echo htmlspecialchars(trim($titulo)); ?>
                                                    </div>
                                                    <?php if (!empty(trim($conteudos[$i] ?? ''))): ?>
                                                        <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0 0 10px; line-height: 1.5;">
                                                            <?php echo htmlspecialchars(mb_substr(trim($conteudos[$i]), 0, 200)) . (mb_strlen(trim($conteudos[$i])) > 200 ? '...' : ''); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                    <?php if (!empty(trim($urls[$i] ?? '')) && trim($urls[$i]) !== '#'): ?>
                                                        <a href="<?php echo htmlspecialchars(trim($urls[$i])); ?>" target="_blank"
                                                           class="btn-secondary" style="font-size: 0.8rem; padding: 7px 14px; min-width: unset; display: inline-flex; gap: 6px;">
                                                            <i class="fas fa-external-link-alt"></i> Acessar material
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div style="padding: 16px 20px 20px; border-top: 1px solid #F1F5F9; text-align: center;">
                                        <p style="color: var(--text-muted); font-size: 0.88rem; margin: 0;">
                                            Nenhum material vinculado a este item.
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    <?php endif; ?>
</main>

<!-- Modal: Salvar Resultado -->
<?php if (isset($_SESSION['user_id'])): ?>
<div id="modalSalvar" class="modal-overlay no-print" style="display: none;">
    <div class="modal-content" style="max-width: 520px;">
        <h2 style="color: var(--primary); margin-bottom: 20px;"><i class="fas fa-save"></i> Salvar Diagnóstico</h2>
        <p style="color: var(--text-muted); margin-bottom: 25px;">Vincule a um projeto para acompanhar sua evolução.</p>

        <div style="display: flex; gap: 0; margin-bottom: 25px; border-bottom: 2px solid var(--border);">
            <button onclick="trocarAba('existente')" id="aba-existente"
                    style="flex:1; padding:12px; background:transparent; border:none; border-bottom:3px solid var(--primary); color:var(--primary); font-weight:600; cursor:pointer; font-size:0.9rem;">
                Projeto Existente
            </button>
            <button onclick="trocarAba('novo')" id="aba-novo"
                    style="flex:1; padding:12px; background:transparent; border:none; border-bottom:3px solid transparent; color:var(--text-muted); font-weight:600; cursor:pointer; font-size:0.9rem;">
                Novo Projeto
            </button>
        </div>

        <div id="painel-existente">
            <div class="form-group">
                <label>Selecione o Projeto</label>
                <select id="selectProjeto" style="width:100%; padding:14px 16px; border:2px solid var(--border); border-radius:10px; font-size:1rem; font-family:inherit;">
                    <option value="">-- selecione --</option>
                </select>
                <p id="msg-sem-projetos" style="display:none; font-size:0.85rem; color:var(--text-muted); margin-top:8px;">
                    Nenhum projeto encontrado. Use a aba "Novo Projeto".
                </p>
            </div>
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button class="btn-secondary" onclick="fecharModalSalvar()">Cancelar</button>
                <button class="btn-primary" onclick="salvarEmExistente()"><i class="fas fa-save"></i> Salvar</button>
            </div>
        </div>

        <div id="painel-novo" style="display:none;">
            <div class="form-group">
                <label>Nome do Projeto *</label>
                <input type="text" id="np-nome" placeholder="Ex: App Escola Virtual" maxlength="200">
            </div>
            <div class="form-group">
                <label>Descrição</label>
                <textarea id="np-descricao" rows="3"
                          style="width:100%; padding:14px 16px; border:2px solid var(--border); border-radius:10px; font-family:inherit; font-size:1rem; resize:vertical;"
                          placeholder="Descreva brevemente o projeto..."></textarea>
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
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button class="btn-secondary" onclick="fecharModalSalvar()">Cancelar</button>
                <button class="btn-primary" onclick="salvarEmNovo()"><i class="fas fa-plus"></i> Criar e Salvar</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Toast -->
<div id="toast" style="display:none; position:fixed; bottom:30px; right:30px; padding:16px 24px; border-radius:12px; font-weight:600; z-index:3000; box-shadow:0 4px 20px rgba(0,0,0,0.2); color:white;"></div>

<style>
.barra-acoes {
    position: sticky;
    top: 0;
    z-index: 900;
    background: white;
    border-bottom: 1px solid #E2E8F0;
    padding: 12px 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.accordion-card {
    background: white;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    margin-bottom: 10px;
    overflow: hidden;
    transition: box-shadow 0.2s;
}

.accordion-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,0.07); }

.accordion-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 16px 20px;
    cursor: pointer;
    user-select: none;
}

.accordion-icon {
    color: var(--text-muted);
    font-size: 0.85rem;
    flex-shrink: 0;
    transition: transform 0.3s;
}

.accordion-icon.aberto { transform: rotate(180deg); }

@media print {
    @page { margin: 1.5cm; }
    a::after { content: none !important; }
    .no-print, .navbar, .main-footer, .barra-acoes { display: none !important; }
    body { background: white !important; font-size: 12px; }
    .accordion-card { box-shadow: none !important; page-break-inside: avoid; }
    .accordion-body { display: block !important; }
    .accordion-icon { display: none !important; }
    main::before {
        content: "LGPD4DEVS — Relatório de Conformidade";
        display: block;
        font-size: 1.3rem;
        font-weight: 800;
        color: #0066FF;
        margin-bottom: 6px;
    }
    main::after {
        content: "Gerado em <?php echo date('d/m/Y \à\s H:i'); ?>";
        display: block;
        font-size: 0.75rem;
        color: #666;
        margin-top: 30px;
        border-top: 1px solid #ddd;
        padding-top: 10px;
    }
}
</style>

<script>
const csrfToken = "<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>";

// ── Accordion individual ──────────────────────────────────────────────────────

function toggleAccordion(uid) {
    const body = document.getElementById(uid);
    const icon = document.getElementById('icon-' + uid);
    const aberto = body.style.display !== 'none';
    body.style.display = aberto ? 'none' : 'block';
    icon.classList.toggle('aberto', !aberto);
}

// ── Grupo falhas (aberto por padrão) ─────────────────────────────────────────

function toggleGrupoFalhas(btn) {
    const grupo = document.getElementById('grupoFalhas');
    const icon  = btn.querySelector('i.fas:last-child');
    const aberto = grupo.style.display !== 'none';
    grupo.style.display = aberto ? 'none' : 'block';
    icon.style.transform = aberto ? 'rotate(180deg)' : '';
}

// ── Grupo conformes (fechado por padrão) ──────────────────────────────────────

function toggleGrupoConformes(btn) {
    const grupo = document.getElementById('grupoConformes');
    const icon  = btn.querySelector('i.fas:last-child');
    const aberto = grupo.style.display !== 'none';
    grupo.style.display = aberto ? 'none' : 'block';
    icon.style.transform = aberto ? '' : 'rotate(180deg)';
}

// ── Modal Salvar ──────────────────────────────────────────────────────────────

function abrirModalSalvar() {
    carregarProjetos();
    document.getElementById('modalSalvar').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function fecharModalSalvar() {
    document.getElementById('modalSalvar').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function trocarAba(aba) {
    const pe = document.getElementById('painel-existente');
    const pn = document.getElementById('painel-novo');
    const ae = document.getElementById('aba-existente');
    const an = document.getElementById('aba-novo');
    if (aba === 'existente') {
        pe.style.display = 'block'; pn.style.display = 'none';
        ae.style.borderBottomColor = 'var(--primary)'; ae.style.color = 'var(--primary)';
        an.style.borderBottomColor = 'transparent';    an.style.color = 'var(--text-muted)';
    } else {
        pe.style.display = 'none'; pn.style.display = 'block';
        an.style.borderBottomColor = 'var(--primary)'; an.style.color = 'var(--primary)';
        ae.style.borderBottomColor = 'transparent';    ae.style.color = 'var(--text-muted)';
    }
}

function carregarProjetos() {
    const select   = document.getElementById('selectProjeto');
    const msgVazio = document.getElementById('msg-sem-projetos');
    if (!select) return;

    select.innerHTML = '<option value="">Carregando...</option>';
    select.disabled  = true;

    fetch('/api/projetos', { credentials: 'same-origin' })
        .then(r => {
            if (!r.ok) throw new Error('Erro ' + r.status);
            return r.json();
        })
        .then(data => {
            select.disabled  = false;
            select.innerHTML = '<option value="">-- selecione --</option>';

            if (!Array.isArray(data) || data.length === 0) {
                msgVazio.style.display = 'block';
                return;
            }
            msgVazio.style.display = 'none';
            data.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = p.nome;
                select.appendChild(opt);
            });
        })
        .catch(err => {
            select.disabled  = false;
            select.innerHTML = '<option value="">Erro ao carregar</option>';
            console.error('Erro ao buscar projetos:', err);
        });
}

function salvarEmExistente() {
    const projetoId = document.getElementById('selectProjeto').value;
    if (!projetoId) { mostrarToast('Selecione um projeto.', false); return; }
    enviarSalvar({ csrf_token: csrfToken, projeto_id: projetoId });
}

function salvarEmNovo() {
    const nome = document.getElementById('np-nome').value.trim();
    if (!nome) { mostrarToast('O nome do projeto é obrigatório.', false); return; }

    fetch('/projetos/criar', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            csrf_token:   csrfToken,
            nome,
            descricao:    document.getElementById('np-descricao').value,
            publico_alvo: document.getElementById('np-publico').value,
            status:       'em_desenvolvimento'
        })
    })
    .then(r => r.json())
    .then(p => {
        if (!p.sucesso) throw new Error(p.mensagem || 'Erro ao criar projeto.');
        enviarSalvar({ csrf_token: csrfToken, projeto_id: p.projeto_id });
    })
    .catch(e => mostrarToast(e.message, false));
}

function enviarSalvar(params) {
    fetch('/salvar-resultado', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(params)
    })
    .then(r => r.json())
    .then(data => {
        if (data.sucesso) {
            fecharModalSalvar();
            mostrarToast('Resultado salvo com sucesso!', true);
            setTimeout(() => window.location.href = '/projetos', 1800);
        } else {
            mostrarToast(data.mensagem || 'Erro ao salvar.', false);
        }
    })
    .catch(() => mostrarToast('Erro de comunicação. Tente novamente.', false));
}

function mostrarToast(msg, sucesso) {
    const t = document.getElementById('toast');
    t.textContent  = (sucesso ? '✅ ' : '❌ ') + msg;
    t.style.background = sucesso ? 'var(--secondary)' : 'var(--error)';
    t.style.display = 'block';
    setTimeout(() => t.style.display = 'none', 3000);
}

window.addEventListener('click', e => {
    if (e.target.id === 'modalSalvar') fecharModalSalvar();
});
</script>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>