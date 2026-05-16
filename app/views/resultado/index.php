<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<main class="container content-area">

    <div class="checklist-header">
        <h1>Relatório de Conformidade</h1>
        <p>Diagnóstico baseado no Artigo 14 da LGPD — proteção de dados de crianças e adolescentes.</p>
    </div>

    <?php if ($percentual === null): ?>
        <!-- Nenhuma resposta encontrada -->
        <div class="card-pergunta" style="text-align: center; padding: 60px 40px;">
            <i class="fas fa-clipboard-list" style="font-size: 3rem; color: var(--border); margin-bottom: 20px; display: block;"></i>
            <h3 style="color: var(--text-muted); margin-bottom: 15px;">Nenhuma resposta encontrada</h3>
            <p style="color: var(--text-muted); margin-bottom: 30px;">
                Responda o checklist para gerar seu relatório de conformidade.
            </p>
            <a href="/checklist" class="btn-primary">Fazer Checklist</a>
        </div>

    <?php else: ?>

        <?php
            $cor        = ($percentual >= 70) ? 'var(--secondary)' : 'var(--error)';
            $totalItens = count($dadosParaCalculo);
            $okCount    = $totalItens - count($falhas);
        ?>

        <!-- Score principal -->
        <div class="card-pergunta" style="text-align: center; padding: 50px 40px; margin-bottom: 40px;">
            <div style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 12px;">
                Nível de Adequação
            </div>

            <h2 style="font-size: 5rem; color: <?php echo $cor; ?>; line-height: 1; margin-bottom: 25px;">
                <?php echo $percentual; ?>%
            </h2>

            <div style="background: #E2E8F0; height: 16px; border-radius: 10px; width: 100%; max-width: 500px; margin: 0 auto 25px; overflow: hidden;">
                <div style="width: <?php echo $percentual; ?>%; height: 100%; background: <?php echo $cor; ?>; transition: width 1s ease;"></div>
            </div>

            <p style="font-size: 1.15rem; color: var(--text-main); font-weight: 600; margin-bottom: 30px;">
                <?php
                    if ($percentual === 100)      echo "Conformidade Total — Parabéns!";
                    elseif ($percentual >= 70)    echo "Nível satisfatório, mas existem melhorias pendentes.";
                    elseif ($percentual >= 40)    echo "Conformidade parcial — atenção aos itens em aberto.";
                    else                          echo "Atenção: foram detectadas falhas críticas de privacidade.";
                ?>
            </p>

            <!-- Métricas -->
            <div style="display: flex; justify-content: center; gap: 50px; flex-wrap: wrap;">
                <div>
                    <div style="font-size: 1.8rem; font-weight: 700; color: var(--secondary);"><?php echo $okCount; ?></div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Itens Conformes</div>
                </div>
                <div>
                    <div style="font-size: 1.8rem; font-weight: 700; color: var(--error);"><?php echo count($falhas); ?></div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Itens em Aberto</div>
                </div>
                <div>
                    <div style="font-size: 1.8rem; font-weight: 700; color: var(--primary);"><?php echo $obtidos; ?>/<?php echo $totalPontos; ?></div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Pontos Obtidos</div>
                </div>
            </div>
        </div>

        <!-- Botões de ação -->
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; margin-bottom: 50px;" class="no-print">

            <?php if (isset($_SESSION['user_id'])): ?>
                <button onclick="abrirModalSalvar()" class="btn-primary">
                    <i class="fas fa-save"></i> Salvar Resultado
                </button>
            <?php else: ?>
                <a href="/cadastro" class="btn-primary">
                    <i class="fas fa-user-plus"></i> Criar Conta para Salvar
                </a>
            <?php endif; ?>

            <a href="/materiais" class="btn-secondary">
                <i class="fas fa-book"></i> Ver Materiais de Apoio
            </a>

            <button onclick="window.print()" class="btn-secondary">
                <i class="fas fa-print"></i> Exportar PDF
            </button>

            <a href="/checklist" class="btn-secondary">
                <i class="fas fa-redo"></i> Refazer Checklist
            </a>
        </div>

        <!-- Falhas detectadas -->
        <?php if (!empty($falhas)): ?>
            <div style="margin-bottom: 40px;">
                <h3 style="color: var(--text-main); margin-bottom: 20px;">
                    <i class="fas fa-exclamation-triangle" style="color: var(--error);"></i>
                    Itens que precisam de atenção
                </h3>

                <?php
                    // Agrupa falhas por categoria
                    $porCategoria = [];
                    foreach ($falhas as $item) {
                        $cat = $item['categoria'] ?? 'Geral';
                        $porCategoria[$cat][] = $item;
                    }
                ?>

                <?php foreach ($porCategoria as $categoria => $itens): ?>
                    <div style="margin-bottom: 25px;">
                        <span class="badge-categoria"><?php echo htmlspecialchars($categoria); ?></span>

                        <?php foreach ($itens as $item): ?>
                            <div class="card-pergunta" style="border-left: 5px solid var(--error); margin-bottom: 12px; padding: 20px;">
                                <p style="margin: 0 0 10px; color: var(--text-main); font-weight: 500;">
                                    ❌ <?php echo htmlspecialchars($item['texto']); ?>
                                </p>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">
                                    Peso: <?php echo $item['peso']; ?>
                                </div>

                                <?php if (!empty($item['materiais_titulos'])): ?>
                                    <?php
                                        $titulos   = explode('||', $item['materiais_titulos']);
                                        $urls      = explode('||', $item['materiais_urls'] ?? '');
                                    ?>
                                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border);">
                                        <div style="font-size: 0.8rem; font-weight: 600; color: var(--primary); margin-bottom: 8px;">
                                            <i class="fas fa-book-open"></i> Materiais recomendados:
                                        </div>
                                        <?php foreach ($titulos as $i => $titulo): ?>
                                            <?php if (trim($titulo)): ?>
                                                <a href="<?php echo htmlspecialchars($urls[$i] ?? '#'); ?>"
                                                   target="_blank"
                                                   style="display: inline-block; margin-right: 8px; margin-bottom: 4px; font-size: 0.82rem; color: var(--primary); text-decoration: underline;">
                                                    <?php echo htmlspecialchars(trim($titulo)); ?>
                                                </a>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Itens conformes (colapsável) -->
        <?php
            $conformes = array_filter($dadosParaCalculo, fn($i) => $i['resposta'] == 1);
        ?>
        <?php if (!empty($conformes)): ?>
            <div style="margin-bottom: 40px;" class="no-print">
                <button onclick="toggleConformes()" id="btnConformes"
                        style="background: transparent; border: 2px solid var(--secondary); color: var(--secondary); padding: 10px 20px; border-radius: 10px; cursor: pointer; font-weight: 600; font-size: 0.9rem; margin-bottom: 15px;">
                    <i class="fas fa-check-circle"></i> Ver <?php echo count($conformes); ?> itens conformes
                </button>

                <div id="listaConformes" style="display: none;">
                    <?php foreach ($conformes as $item): ?>
                        <div class="card-pergunta" style="border-left: 5px solid var(--secondary); margin-bottom: 12px; padding: 18px;">
                            <p style="margin: 0; color: var(--text-main);">
                                ✅ <?php echo htmlspecialchars($item['texto']); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    <?php endif; ?>

</main>

<!-- Modal: Salvar Resultado -->
<?php if (isset($_SESSION['user_id'])): ?>
<div id="modalSalvar" class="modal-overlay" style="display: none;">
    <div class="modal-content" style="max-width: 520px;">
        <h2 style="color: var(--primary); margin-bottom: 20px;">
            <i class="fas fa-save"></i> Salvar Diagnóstico
        </h2>
        <p style="color: var(--text-muted); margin-bottom: 25px;">
            Vincule este resultado a um projeto para acompanhar sua evolução ao longo do tempo.
        </p>

        <!-- Abas -->
        <div style="display: flex; gap: 0; margin-bottom: 25px; border-bottom: 2px solid var(--border);">
            <button onclick="trocarAba('existente')" id="aba-existente"
                    style="flex: 1; padding: 12px; background: transparent; border: none; border-bottom: 3px solid var(--primary); color: var(--primary); font-weight: 600; cursor: pointer; font-size: 0.9rem;">
                Projeto Existente
            </button>
            <button onclick="trocarAba('novo')" id="aba-novo"
                    style="flex: 1; padding: 12px; background: transparent; border: none; border-bottom: 3px solid transparent; color: var(--text-muted); font-weight: 600; cursor: pointer; font-size: 0.9rem;">
                Novo Projeto
            </button>
        </div>

        <!-- Aba: Projeto Existente -->
        <div id="painel-existente">
            <div class="form-group">
                <label>Selecione o Projeto</label>
                <select id="selectProjeto"
                        style="width: 100%; padding: 14px 16px; border: 2px solid var(--border); border-radius: 10px; font-size: 1rem; font-family: inherit;">
                    <option value="">Carregando projetos...</option>
                </select>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 10px;">
                <button class="btn-secondary" onclick="fecharModalSalvar()">Cancelar</button>
                <button class="btn-primary" onclick="salvarEmExistente()">
                    <i class="fas fa-save"></i> Salvar
                </button>
            </div>
        </div>

        <!-- Aba: Novo Projeto -->
        <div id="painel-novo" style="display: none;">
            <div class="form-group">
                <label>Nome do Projeto *</label>
                <input type="text" id="np-nome" placeholder="Ex: App Escola Virtual" maxlength="200">
            </div>
            <div class="form-group">
                <label>Descrição</label>
                <textarea id="np-descricao" rows="3"
                          style="width: 100%; padding: 14px 16px; border: 2px solid var(--border); border-radius: 10px; font-family: inherit; font-size: 1rem; resize: vertical;"
                          placeholder="Descreva brevemente o projeto..."></textarea>
            </div>
            <div class="form-group">
                <label>Público-alvo</label>
                <select id="np-publico"
                        style="width: 100%; padding: 14px 16px; border: 2px solid var(--border); border-radius: 10px; font-size: 1rem; font-family: inherit;">
                    <option value="criancas">Crianças</option>
                    <option value="adolescentes">Adolescentes</option>
                    <option value="ambos" selected>Ambos</option>
                    <option value="outros">Outros</option>
                </select>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 10px;">
                <button class="btn-secondary" onclick="fecharModalSalvar()">Cancelar</button>
                <button class="btn-primary" onclick="salvarEmNovo()">
                    <i class="fas fa-plus"></i> Criar e Salvar
                </button>
            </div>
        </div>

    </div>
</div>

<div id="toastSucesso"
     style="display: none; position: fixed; bottom: 30px; right: 30px; background: var(--secondary); color: white; padding: 16px 24px; border-radius: 12px; font-weight: 600; z-index: 3000; box-shadow: 0 4px 20px rgba(0,0,0,0.2);">
    ✅ Resultado salvo com sucesso!
</div>
<?php endif; ?>

<style>
@media print {
    .no-print, .navbar, .main-footer { display: none !important; }
    .card-pergunta { page-break-inside: avoid !important; }
    body { background: white !important; }
}
</style>

<script>
const csrfToken = "<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>";

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

window.onclick = function(e) {
    if (e.target.id === 'modalSalvar') fecharModalSalvar();
}

// ── Abas ─────────────────────────────────────────────────────────────────────

function trocarAba(aba) {
    const painelExistente = document.getElementById('painel-existente');
    const painelNovo      = document.getElementById('painel-novo');
    const abaExistente    = document.getElementById('aba-existente');
    const abaNovo         = document.getElementById('aba-novo');

    if (aba === 'existente') {
        painelExistente.style.display = 'block';
        painelNovo.style.display      = 'none';
        abaExistente.style.borderBottomColor = 'var(--primary)';
        abaExistente.style.color             = 'var(--primary)';
        abaNovo.style.borderBottomColor      = 'transparent';
        abaNovo.style.color                  = 'var(--text-muted)';
    } else {
        painelExistente.style.display = 'none';
        painelNovo.style.display      = 'block';
        abaNovo.style.borderBottomColor      = 'var(--primary)';
        abaNovo.style.color                  = 'var(--primary)';
        abaExistente.style.borderBottomColor = 'transparent';
        abaExistente.style.color             = 'var(--text-muted)';
    }
}

// ── Carregar projetos no select ───────────────────────────────────────────────

function carregarProjetos() {
    const select = document.getElementById('selectProjeto');
    select.innerHTML = '<option value="">Carregando...</option>';

    fetch('/api/projetos')
        .then(r => r.json())
        .then(data => {
            if (data.length === 0) {
                select.innerHTML = '<option value="">Nenhum projeto encontrado</option>';
                return;
            }
            select.innerHTML = '<option value="">Selecione um projeto</option>';
            data.forEach(p => {
                const opt = document.createElement('option');
                opt.value       = p.id;
                opt.textContent = p.nome;
                select.appendChild(opt);
            });
        })
        .catch(() => {
            select.innerHTML = '<option value="">Erro ao carregar projetos</option>';
        });
}

// ── Salvar em projeto existente ───────────────────────────────────────────────

function salvarEmExistente() {
    const projetoId = document.getElementById('selectProjeto').value;
    if (!projetoId) { alert('Selecione um projeto.'); return; }

    fetch('/salvar-resultado', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ csrf_token: csrfToken, projeto_id: projetoId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.sucesso) {
            fecharModalSalvar();
            mostrarToast();
            setTimeout(() => window.location.href = '/projetos', 1800);
        } else {
            alert('Erro: ' + data.mensagem);
        }
    })
    .catch(() => alert('Erro de comunicação. Tente novamente.'));
}

// ── Salvar criando novo projeto ───────────────────────────────────────────────

function salvarEmNovo() {
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
            status:       'em_desenvolvimento',
        })
    })
    .then(r => r.json())
    .then(projeto => {
        if (!projeto.sucesso) throw new Error(projeto.mensagem || 'Erro ao criar projeto.');

        return fetch('/salvar-resultado', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ csrf_token: csrfToken, projeto_id: projeto.projeto_id })
        });
    })
    .then(r => r.json())
    .then(data => {
        if (data.sucesso) {
            fecharModalSalvar();
            mostrarToast();
            setTimeout(() => window.location.href = '/projetos', 1800);
        } else {
            alert('Erro: ' + data.mensagem);
        }
    })
    .catch(err => alert(err.message));
}

// ── Toast de sucesso ──────────────────────────────────────────────────────────

function mostrarToast() {
    const toast = document.getElementById('toastSucesso');
    toast.style.display = 'block';
    setTimeout(() => toast.style.display = 'none', 3000);
}

// ── Toggle itens conformes ────────────────────────────────────────────────────

function toggleConformes() {
    const lista = document.getElementById('listaConformes');
    const btn   = document.getElementById('btnConformes');
    const visivel = lista.style.display !== 'none';
    lista.style.display = visivel ? 'none' : 'block';
    btn.innerHTML = visivel
        ? '<i class="fas fa-check-circle"></i> Ver <?php echo count(array_filter($dadosParaCalculo ?? [], fn($i) => $i["resposta"] == 1)); ?> itens conformes'
        : '<i class="fas fa-chevron-up"></i> Ocultar itens conformes';
}
</script>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>