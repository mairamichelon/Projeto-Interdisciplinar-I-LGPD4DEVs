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

        <!-- Score compacto -->
        <div class="card-pergunta print-score" style="padding: 30px 40px; margin-bottom: 25px; border-top: 5px solid <?php echo $cor; ?>;">
            <div style="display: flex; align-items: center; gap: 40px; flex-wrap: wrap; justify-content: center;">

                <!-- Percentual -->
                <div style="text-align: center; min-width: 120px;">
                    <div style="font-size: 3.8rem; font-weight: 800; color: <?php echo $cor; ?>; line-height: 1;">
                        <?php echo $percentual; ?>%
                    </div>
                    <div style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-top: 4px;">
                        Adequação
                    </div>
                </div>

                <!-- Barra + label -->
                <div style="flex: 1; min-width: 200px;">
                    <div style="font-size: 1.05rem; font-weight: 700; color: <?php echo $cor; ?>; margin-bottom: 10px;">
                        <?php echo $labelNivel; ?>
                    </div>
                    <div style="background: #E2E8F0; height: 12px; border-radius: 10px; overflow: hidden; margin-bottom: 12px;">
                        <div style="width: <?php echo $percentual; ?>%; height: 100%; background: <?php echo $cor; ?>;"></div>
                    </div>
                    <div style="display: flex; gap: 25px; flex-wrap: wrap;">
                        <span style="font-size: 0.85rem; color: var(--secondary); font-weight: 600;">
                            ✅ <?php echo $okCount; ?> conformes
                        </span>
                        <span style="font-size: 0.85rem; color: var(--error); font-weight: 600;">
                            ❌ <?php echo count($falhas); ?> em aberto
                        </span>
                        <span style="font-size: 0.85rem; color: var(--primary); font-weight: 600;">
                            🏆 <?php echo $obtidos; ?>/<?php echo $totalPontos; ?> pontos
                        </span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Itens com falha -->
        <?php if (!empty($falhas)): ?>
            <?php
                $porCategoria = [];
                foreach ($falhas as $item) {
                    $porCategoria[$item['categoria'] ?? 'Geral'][] = $item;
                }
            ?>

            <h3 style="margin-bottom: 18px; color: var(--text-main); font-size: 1.05rem;" class="no-print">
                <i class="fas fa-exclamation-triangle" style="color: var(--error);"></i>
                Itens que precisam de atenção — clique para ver detalhes e materiais
            </h3>
            <h3 style="margin-bottom: 18px; color: var(--text-main);" class="print-only">
                Itens que precisam de atenção
            </h3>

            <?php foreach ($porCategoria as $categoria => $itens): ?>
                <div style="margin-bottom: 20px;">
                    <span class="badge-categoria"><?php echo htmlspecialchars($categoria); ?></span>

                    <?php foreach ($itens as $idx => $item): ?>
                        <?php
                            $temMateriais = !empty($item['materiais_titulos']);
                            $titulos  = $temMateriais ? explode('||', $item['materiais_titulos']) : [];
                            $urls     = $temMateriais ? explode('||', $item['materiais_urls'] ?? '') : [];
                            $conteudos= $temMateriais ? explode('||', $item['materiais_conteudos'] ?? '') : [];
                            $itemId   = 'item-' . $item['id'];
                        ?>

                        <!-- Card clicável (tela) -->
                        <div class="card-falha no-print"
                             onclick="abrirModalItem(<?php echo htmlspecialchars(json_encode([
                                 'texto'    => $item['texto'],
                                 'peso'     => $item['peso'],
                                 'categoria'=> $item['categoria'] ?? 'Geral',
                                 'titulos'  => $titulos,
                                 'urls'     => $urls,
                                 'conteudos'=> $conteudos,
                             ])); ?>)"
                             style="display: flex; align-items: center; justify-content: space-between; gap: 15px; padding: 16px 20px; background: white; border: 1px solid #E2E8F0; border-left: 5px solid var(--error); border-radius: 12px; margin-bottom: 10px; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
                            <div style="display: flex; align-items: flex-start; gap: 12px; flex: 1;">
                                <span style="font-size: 1.1rem; margin-top: 1px;">❌</span>
                                <div>
                                    <p style="margin: 0 0 4px; color: var(--text-main); font-weight: 500; font-size: 0.95rem; line-height: 1.4;">
                                        <?php echo htmlspecialchars($item['texto']); ?>
                                    </p>
                                    <div style="display: flex; gap: 12px; align-items: center;">
                                        <span style="font-size: 0.78rem; color: var(--text-muted);">Peso: <?php echo $item['peso']; ?></span>
                                        <?php if ($temMateriais): ?>
                                            <span style="font-size: 0.78rem; color: var(--primary); font-weight: 600;">
                                                <i class="fas fa-book-open"></i> <?php echo count($titulos); ?> material(is)
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right" style="color: var(--text-muted); font-size: 0.85rem; flex-shrink: 0;"></i>
                        </div>

                        <!-- Versão para impressão (sem interação) -->
                        <div class="print-only" style="padding: 12px 16px; border-left: 4px solid var(--error); margin-bottom: 8px; background: #fff5f5; border-radius: 6px;">
                            <p style="margin: 0 0 4px; font-weight: 500; font-size: 0.9rem;">❌ <?php echo htmlspecialchars($item['texto']); ?></p>
                            <span style="font-size: 0.78rem; color: #666;">Peso: <?php echo $item['peso']; ?></span>
                            <?php if ($temMateriais): ?>
                                <div style="margin-top: 6px;">
                                    <?php foreach ($titulos as $i => $t): ?>
                                        <?php if (trim($t)): ?>
                                            <div style="font-size: 0.78rem; color: #444;">📄 <?php echo htmlspecialchars(trim($t)); ?></div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Itens conformes (accordion) -->
        <?php
            $conformes = array_filter($dadosParaCalculo, fn($i) => $i['resposta'] == 1);
        ?>
        <?php if (!empty($conformes)): ?>
            <div style="margin-top: 10px;">
                <!-- Tela -->
                <button onclick="toggleConformes(this)" class="no-print"
                        style="display: flex; align-items: center; justify-content: space-between; width: 100%; background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 12px; padding: 14px 20px; cursor: pointer; font-size: 0.92rem; font-weight: 600; color: var(--secondary);">
                    <span><i class="fas fa-check-circle"></i> <?php echo count($conformes); ?> itens em conformidade</span>
                    <i class="fas fa-chevron-down" style="transition: transform 0.3s;"></i>
                </button>

                <div id="listaConformes" style="display: none; margin-top: 10px;" class="no-print">
                    <?php foreach ($conformes as $item): ?>
                        <div style="display: flex; align-items: flex-start; gap: 12px; padding: 14px 20px; background: white; border: 1px solid #E2E8F0; border-left: 5px solid var(--secondary); border-radius: 12px; margin-bottom: 8px;">
                            <span style="font-size: 1.1rem;">✅</span>
                            <p style="margin: 0; color: var(--text-main); font-size: 0.92rem; line-height: 1.4;">
                                <?php echo htmlspecialchars($item['texto']); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Impressão -->
                <div class="print-only" style="margin-top: 20px;">
                    <h3 style="font-size: 1rem; color: #15803D; margin-bottom: 12px;">✅ Itens em Conformidade</h3>
                    <?php foreach ($conformes as $item): ?>
                        <div style="padding: 10px 14px; border-left: 4px solid var(--secondary); background: #f0fdf4; border-radius: 6px; margin-bottom: 8px;">
                            <p style="margin: 0; font-size: 0.88rem;">✅ <?php echo htmlspecialchars($item['texto']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    <?php endif; ?>
</main>

<!-- Modal: Detalhe do item -->
<div id="modalItem" class="modal-overlay no-print" style="display: none;">
    <div class="modal-content" style="max-width: 580px; max-height: 85vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
            <span id="modal-categoria" class="badge-categoria" style="margin-bottom: 0;"></span>
            <button onclick="fecharModalItem()"
                    style="background: none; border: none; font-size: 1.3rem; cursor: pointer; color: var(--text-muted); padding: 0; line-height: 1;">×</button>
        </div>

        <p id="modal-texto" style="font-size: 1rem; font-weight: 600; color: var(--text-main); line-height: 1.5; margin-bottom: 15px;"></p>

        <div style="display: flex; gap: 15px; margin-bottom: 20px;">
            <span style="background: #FEF2F2; color: var(--error); padding: 5px 12px; border-radius: 20px; font-size: 0.82rem; font-weight: 700;">
                ❌ Não implementado
            </span>
            <span id="modal-peso" style="background: #EFF6FF; color: var(--primary); padding: 5px 12px; border-radius: 20px; font-size: 0.82rem; font-weight: 700;"></span>
        </div>

        <div id="modal-materiais" style="display: none;">
            <div style="border-top: 1px solid var(--border); padding-top: 18px; margin-top: 5px;">
                <div style="font-size: 0.85rem; font-weight: 700; color: var(--primary); margin-bottom: 14px;">
                    <i class="fas fa-book-open"></i> Materiais recomendados para este item:
                </div>
                <div id="modal-lista-materiais" style="display: flex; flex-direction: column; gap: 10px;"></div>
            </div>
        </div>

        <div id="modal-sem-materiais" style="display: none; margin-top: 15px; padding: 15px; background: #F8FAFC; border-radius: 10px; text-align: center;">
            <p style="color: var(--text-muted); font-size: 0.88rem; margin: 0;">
                Nenhum material específico vinculado a este item ainda.
            </p>
        </div>

        <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
            <button onclick="fecharModalItem()" class="btn-secondary" style="min-width: unset; padding: 10px 20px;">Fechar</button>
        </div>
    </div>
</div>

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
                    <option value="">Carregando...</option>
                </select>
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
/* Barra sticky */
.barra-acoes {
    position: sticky;
    top: 0;
    z-index: 900;
    background: white;
    border-bottom: 1px solid #E2E8F0;
    padding: 12px 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

/* Hover nos cards de falha */
.card-falha:hover {
    transform: translateX(4px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.08) !important;
    border-left-color: #DC2626 !important;
}

/* ── Impressão ── */
@media print {
    @page {
        margin: 1.5cm;
    }

    /* Remove URL dos links na impressão */
    a::after { content: none !important; }

    .no-print,
    .navbar,
    .main-footer,
    .barra-acoes { display: none !important; }

    .print-only { display: block !important; }

    body { background: white !important; font-size: 12px; }

    .card-pergunta, .print-score {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
        page-break-inside: avoid !important;
    }

    /* Cabeçalho de impressão */
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

.print-only { display: none; }
</style>

<script>
const csrfToken = "<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>";

// ── Modal Item ────────────────────────────────────────────────────────────────

function abrirModalItem(item) {
    document.getElementById('modal-categoria').textContent = item.categoria;
    document.getElementById('modal-texto').textContent     = item.texto;
    document.getElementById('modal-peso').textContent      = '⚖️ Peso: ' + item.peso;

    const listaMateriais = document.getElementById('modal-lista-materiais');
    listaMateriais.innerHTML = '';

    const temMateriais = item.titulos && item.titulos.some(t => t.trim());

    if (temMateriais) {
        document.getElementById('modal-materiais').style.display     = 'block';
        document.getElementById('modal-sem-materiais').style.display = 'none';

        item.titulos.forEach((titulo, i) => {
            if (!titulo.trim()) return;
            const url      = item.urls[i]      || '#';
            const conteudo = item.conteudos[i] || '';

            listaMateriais.innerHTML += `
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 14px 16px;">
                    <div style="font-weight: 700; color: var(--text-main); font-size: 0.9rem; margin-bottom: 6px;">
                        📄 ${titulo.trim()}
                    </div>
                    ${conteudo.trim() ? `<p style="font-size: 0.82rem; color: var(--text-muted); margin: 0 0 10px; line-height: 1.5;">${conteudo.trim().substring(0, 180)}${conteudo.length > 180 ? '...' : ''}</p>` : ''}
                    ${url !== '#' ? `<a href="${url}" target="_blank" class="btn-primary" style="font-size: 0.8rem; padding: 7px 14px; min-width: unset; display: inline-flex;">
                        <i class="fas fa-external-link-alt"></i> Acessar material
                    </a>` : ''}
                </div>
            `;
        });
    } else {
        document.getElementById('modal-materiais').style.display     = 'none';
        document.getElementById('modal-sem-materiais').style.display = 'block';
    }

    document.getElementById('modalItem').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function fecharModalItem() {
    document.getElementById('modalItem').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// ── Toggle conformes ──────────────────────────────────────────────────────────

function toggleConformes(btn) {
    const lista  = document.getElementById('listaConformes');
    const icon   = btn.querySelector('.fa-chevron-down, .fa-chevron-up');
    const aberto = lista.style.display !== 'none';
    lista.style.display = aberto ? 'none' : 'block';
    icon.className = aberto ? 'fas fa-chevron-down' : 'fas fa-chevron-up';
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
    const select = document.getElementById('selectProjeto');
    if (!select) return;
    select.innerHTML = '<option value="">Carregando...</option>';
    fetch('/api/projetos')
        .then(r => r.json())
        .then(data => {
            select.innerHTML = '<option value="">Selecione um projeto</option>';
            if (data.length === 0) {
                select.innerHTML = '<option value="">Nenhum projeto encontrado</option>';
                return;
            }
            data.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id; opt.textContent = p.nome;
                select.appendChild(opt);
            });
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
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            csrf_token: csrfToken, nome,
            descricao:    document.getElementById('np-descricao').value,
            publico_alvo: document.getElementById('np-publico').value,
            status: 'em_desenvolvimento'
        })
    })
    .then(r => r.json())
    .then(p => {
        if (!p.sucesso) throw new Error(p.mensagem);
        enviarSalvar({ csrf_token: csrfToken, projeto_id: p.projeto_id });
    })
    .catch(e => mostrarToast(e.message, false));
}

function enviarSalvar(params) {
    fetch('/salvar-resultado', {
        method: 'POST',
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
            mostrarToast(data.mensagem, false);
        }
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

// ── Fecha modais ao clicar fora ───────────────────────────────────────────────

window.addEventListener('click', e => {
    if (e.target.id === 'modalItem')   fecharModalItem();
    if (e.target.id === 'modalSalvar') fecharModalSalvar();
});
</script>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>