<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<!-- Barra de ações sticky -->
<div class="barra-acoes no-print">
    <div class="container barra-inner">
        <span class="barra-titulo">
            <i class="fas fa-clipboard-check" style="color: var(--primary);"></i>
            Relatório de Conformidade
        </span>
        <div class="barra-botoes">
            <?php if (isset($_SESSION['user_id'])): ?>
                <button onclick="abrirModalSalvar()" class="btn-primary btn-sm">
                    <i class="fas fa-save"></i> Salvar
                </button>
            <?php else: ?>
                <a href="/cadastro" class="btn-primary btn-sm">
                    <i class="fas fa-user-plus"></i> Criar conta
                </a>
            <?php endif; ?>
            <a href="/materiais" class="btn-secondary btn-sm">
                <i class="fas fa-book"></i> Materiais
            </a>
            <button onclick="window.print()" class="btn-secondary btn-sm">
                <i class="fas fa-file-pdf"></i> PDF
            </button>
            <a href="/checklist" class="btn-secondary btn-sm">
                <i class="fas fa-redo"></i> Refazer
            </a>
        </div>
    </div>
</div>

<main class="container resultado-main">

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

            $conformes = array_filter($dadosParaCalculo, fn($i) => $i['resposta'] == 1);
        ?>

        <!-- Score -->
        <div class="card-pergunta score-card" style="border-top: 5px solid <?php echo $cor; ?>;">
            <div class="score-inner">
                <div class="score-numero">
                    <div style="font-size: 3.5rem; font-weight: 800; color: <?php echo $cor; ?>; line-height: 1;">
                        <?php echo $percentual; ?>%
                    </div>
                    <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-top: 4px;">
                        Adequação
                    </div>
                </div>
                <div class="score-detalhe">
                    <div style="font-size: 1rem; font-weight: 700; color: <?php echo $cor; ?>; margin-bottom: 8px;">
                        <?php echo $labelNivel; ?>
                    </div>
                    <div style="background: #E2E8F0; height: 10px; border-radius: 10px; overflow: hidden; margin-bottom: 10px;">
                        <div style="width: <?php echo $percentual; ?>%; height: 100%; background: <?php echo $cor; ?>;"></div>
                    </div>
                    <div class="score-metricas">
                        <span style="color: var(--secondary); font-weight: 600; font-size: 0.85rem;">✅ <?php echo $okCount; ?> conformes</span>
                        <span style="color: var(--error); font-weight: 600; font-size: 0.85rem;">❌ <?php echo count($falhas); ?> em aberto</span>
                        <span style="color: var(--primary); font-weight: 600; font-size: 0.85rem;">🏆 <?php echo $obtidos; ?>/<?php echo $totalPontos; ?> pts</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ ITENS COM FALHA ══ -->
        <?php if (!empty($falhas)): ?>
            <?php
                $porCategoria = [];
                foreach ($falhas as $item) {
                    $porCategoria[$item['categoria'] ?? 'Geral'][] = $item;
                }
            ?>

            <button onclick="toggleGrupoFalhas(this)" class="grupo-header grupo-falha">
                <span><i class="fas fa-exclamation-circle"></i> <?php echo count($falhas); ?> itens em não conformidade</span>
                <i class="fas fa-chevron-up chevron-grupo"></i>
            </button>

            <div id="grupoFalhas">
                <?php foreach ($porCategoria as $categoria => $itens): ?>
                    <div style="margin-bottom: 18px;">
                        <span class="badge-categoria"><?php echo htmlspecialchars($categoria); ?></span>
                        <?php foreach ($itens as $item): ?>
                            <?php
                                $itemId    = (int) ($item['id'] ?? 0);
                                $uid       = 'f-' . ($itemId ?: uniqid());
                                $temMat    = !empty($item['materiais_titulos']);
                                $titulos   = $temMat ? explode('||', $item['materiais_titulos']) : [];
                                $urls      = $temMat ? explode('||', $item['materiais_urls']      ?? '') : [];
                                $conteudos = $temMat ? explode('||', $item['materiais_conteudos'] ?? '') : [];
                                $qtdMat    = count(array_filter($titulos, fn($t) => trim($t)));
                            ?>
                            <div class="accordion-card" style="border-left: 5px solid var(--error);">
                                <div class="accordion-header" onclick="toggleAccordion('<?php echo $uid; ?>')">
                                    <div style="display: flex; align-items: flex-start; gap: 10px; flex: 1;">
                                        <span style="font-size: 1rem; flex-shrink: 0; margin-top: 2px;">❌</span>
                                        <div>
                                            <p style="margin: 0 0 4px; color: var(--text-main); font-weight: 500; font-size: 0.93rem; line-height: 1.4;">
                                                <?php echo htmlspecialchars($item['texto']); ?>
                                            </p>
                                            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                                <span style="font-size: 0.76rem; color: var(--text-muted);">Peso: <?php echo $item['peso']; ?></span>
                                                <?php if ($temMat && $qtdMat > 0): ?>
                                                    <span style="font-size: 0.76rem; color: var(--primary); font-weight: 600;">
                                                        <i class="fas fa-book-open"></i> <?php echo $qtdMat; ?> material(is)
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <i id="icon-<?php echo $uid; ?>" class="fas fa-chevron-down accordion-icon"></i>
                                </div>
                                <div id="<?php echo $uid; ?>" class="accordion-body" style="display: none;">
                                    <div style="padding: 14px 18px 18px; border-top: 1px solid #F1F5F9;">
                                        <?php if ($temMat && $qtdMat > 0): ?>
                                            <p style="font-size: 0.8rem; font-weight: 700; color: var(--primary); margin-bottom: 10px;">
                                                <i class="fas fa-book-open"></i> Materiais recomendados:
                                            </p>
                                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                                <?php foreach ($titulos as $i => $titulo): ?>
                                                    <?php if (!trim($titulo)) continue; ?>
                                                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 12px 14px;">
                                                        <div style="font-weight: 700; color: var(--text-main); font-size: 0.85rem; margin-bottom: 4px;">
                                                            📄 <?php echo htmlspecialchars(trim($titulo)); ?>
                                                        </div>
                                                        <?php $c = trim($conteudos[$i] ?? ''); if ($c): ?>
                                                            <p style="font-size: 0.78rem; color: var(--text-muted); margin: 0 0 8px; line-height: 1.5;">
                                                                <?php echo htmlspecialchars(mb_substr($c, 0, 180)) . (mb_strlen($c) > 180 ? '...' : ''); ?>
                                                            </p>
                                                        <?php endif; ?>
                                                        <?php $u = trim($urls[$i] ?? ''); if ($u && $u !== '#'): ?>
                                                            <a href="<?php echo htmlspecialchars($u); ?>" target="_blank"
                                                               class="btn-primary" style="font-size: 0.78rem; padding: 6px 12px; min-width: unset; display: inline-flex; gap: 5px;">
                                                                <i class="fas fa-external-link-alt"></i> Acessar
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0; text-align: center;">
                                                Nenhum material vinculado.
                                                <a href="/materiais" style="color: var(--primary); font-weight: 600; text-decoration: none;">Ver biblioteca →</a>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- ══ ITENS CONFORMES ══ -->
        <?php if (!empty($conformes)): ?>
            <div style="margin-top: 12px;">
                <button onclick="toggleGrupoConformes(this)" class="grupo-header grupo-conforme">
                    <span><i class="fas fa-check-circle"></i> <?php echo count($conformes); ?> itens em conformidade</span>
                    <i class="fas fa-chevron-down chevron-grupo"></i>
                </button>
                <div id="grupoConformes" style="display: none;">
                    <?php foreach ($conformes as $item): ?>
                        <?php
                            $itemId    = (int) ($item['id'] ?? 0);
                            $uid       = 'c-' . ($itemId ?: uniqid());
                            $temMat    = !empty($item['materiais_titulos']);
                            $titulos   = $temMat ? explode('||', $item['materiais_titulos']) : [];
                            $urls      = $temMat ? explode('||', $item['materiais_urls']      ?? '') : [];
                            $conteudos = $temMat ? explode('||', $item['materiais_conteudos'] ?? '') : [];
                            $qtdMat    = count(array_filter($titulos, fn($t) => trim($t)));
                        ?>
                        <div class="accordion-card" style="border-left: 5px solid var(--secondary);">
                            <div class="accordion-header" onclick="toggleAccordion('<?php echo $uid; ?>')">
                                <div style="display: flex; align-items: flex-start; gap: 10px; flex: 1;">
                                    <span style="font-size: 1rem; flex-shrink: 0; margin-top: 2px;">✅</span>
                                    <div>
                                        <p style="margin: 0 0 4px; color: var(--text-main); font-weight: 500; font-size: 0.93rem; line-height: 1.4;">
                                            <?php echo htmlspecialchars($item['texto']); ?>
                                        </p>
                                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                            <span style="font-size: 0.76rem; color: var(--text-muted);">Peso: <?php echo $item['peso']; ?></span>
                                            <?php if ($temMat && $qtdMat > 0): ?>
                                                <span style="font-size: 0.76rem; color: var(--secondary); font-weight: 600;">
                                                    <i class="fas fa-book-open"></i> <?php echo $qtdMat; ?> material(is)
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <i id="icon-<?php echo $uid; ?>" class="fas fa-chevron-down accordion-icon"></i>
                            </div>
                            <div id="<?php echo $uid; ?>" class="accordion-body" style="display: none;">
                                <div style="padding: 14px 18px 18px; border-top: 1px solid #F1F5F9;">
                                    <?php if ($temMat && $qtdMat > 0): ?>
                                        <p style="font-size: 0.8rem; font-weight: 700; color: var(--secondary); margin-bottom: 10px;">
                                            <i class="fas fa-book-open"></i> Materiais de referência:
                                        </p>
                                        <div style="display: flex; flex-direction: column; gap: 8px;">
                                            <?php foreach ($titulos as $i => $titulo): ?>
                                                <?php if (!trim($titulo)) continue; ?>
                                                <div style="background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 10px; padding: 12px 14px;">
                                                    <div style="font-weight: 700; color: var(--text-main); font-size: 0.85rem; margin-bottom: 4px;">
                                                        📄 <?php echo htmlspecialchars(trim($titulo)); ?>
                                                    </div>
                                                    <?php $c = trim($conteudos[$i] ?? ''); if ($c): ?>
                                                        <p style="font-size: 0.78rem; color: var(--text-muted); margin: 0 0 8px; line-height: 1.5;">
                                                            <?php echo htmlspecialchars(mb_substr($c, 0, 180)) . (mb_strlen($c) > 180 ? '...' : ''); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                    <?php $u = trim($urls[$i] ?? ''); if ($u && $u !== '#'): ?>
                                                        <a href="<?php echo htmlspecialchars($u); ?>" target="_blank"
                                                           class="btn-secondary" style="font-size: 0.78rem; padding: 6px 12px; min-width: unset; display: inline-flex; gap: 5px;">
                                                            <i class="fas fa-external-link-alt"></i> Acessar
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0; text-align: center;">
                                            Nenhum material vinculado a este item.
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- ══ SEÇÃO EXCLUSIVA DE IMPRESSÃO ══ -->
        <div class="print-doc">
            <!-- Cabeçalho do documento -->
            <div class="print-cabecalho">
                <div>
                    <div style="font-size: 1.3rem; font-weight: 800; color: #0066FF;">🛡 LGPD4DEVS</div>
                    <div style="font-size: 0.75rem; color: #555; margin-top: 2px;">Inteligência em Conformidade</div>
                </div>
                <div style="text-align: right; font-size: 0.78rem; color: #555;">
                    Gerado em <?php echo date('d/m/Y \à\s H:i'); ?><br>
                    Relatório de Conformidade LGPD
                </div>
            </div>

            <div class="print-linha"></div>

            <!-- Score -->
            <div class="print-score-box" style="border-color: <?php echo ($percentual >= 70) ? '#00CC66' : '#EF4444'; ?>;">
                <div style="font-size: 3rem; font-weight: 800; color: <?php echo ($percentual >= 70) ? '#00CC66' : '#EF4444'; ?>; line-height: 1;">
                    <?php echo $percentual; ?>%
                </div>
                <div style="font-size: 1rem; font-weight: 700; color: <?php echo ($percentual >= 70) ? '#00CC66' : '#EF4444'; ?>; margin: 6px 0;">
                    <?php echo $labelNivel; ?>
                </div>
                <div style="background: #E2E8F0; height: 10px; border-radius: 6px; overflow: hidden; margin: 10px 0;">
                    <div style="width: <?php echo $percentual; ?>%; height: 100%; background: <?php echo ($percentual >= 70) ? '#00CC66' : '#EF4444'; ?>;"></div>
                </div>
                <div style="display: flex; gap: 20px; justify-content: center; font-size: 0.85rem; flex-wrap: wrap;">
                    <span style="color: #00CC66; font-weight: 600;">✅ <?php echo $okCount; ?> conformes</span>
                    <span style="color: #EF4444; font-weight: 600;">❌ <?php echo count($falhas); ?> em aberto</span>
                    <span style="color: #0066FF; font-weight: 600;">🏆 <?php echo $obtidos; ?>/<?php echo $totalPontos; ?> pontos</span>
                </div>
            </div>

            <!-- Itens em não conformidade -->
            <?php if (!empty($falhas)): ?>
                <div class="print-secao-titulo print-titulo-falha">
                    ❌ Itens em Não Conformidade (<?php echo count($falhas); ?>)
                </div>
                <?php foreach ($falhas as $item): ?>
                    <?php
                        $temMat    = !empty($item['materiais_titulos']);
                        $titulos   = $temMat ? explode('||', $item['materiais_titulos']) : [];
                        $urls      = $temMat ? explode('||', $item['materiais_urls']      ?? '') : [];
                        $conteudos = $temMat ? explode('||', $item['materiais_conteudos'] ?? '') : [];
                    ?>
                    <div class="print-item print-item-falha">
                        <div class="print-item-header">
                            <span style="flex: 1; font-weight: 600; font-size: 0.88rem; line-height: 1.4;">
                                <?php echo htmlspecialchars($item['texto']); ?>
                            </span>
                            <span style="font-size: 0.75rem; color: #555; white-space: nowrap; margin-left: 10px;">
                                Categoria: <?php echo htmlspecialchars($item['categoria'] ?? 'Geral'); ?> | Peso: <?php echo $item['peso']; ?>
                            </span>
                        </div>
                        <?php if ($temMat): ?>
                            <div class="print-materiais">
                                <div style="font-size: 0.75rem; font-weight: 700; color: #0066FF; margin-bottom: 4px;">
                                    Materiais recomendados:
                                </div>
                                <?php foreach ($titulos as $i => $titulo): ?>
                                    <?php if (!trim($titulo)) continue; ?>
                                    <div class="print-material-item">
                                        <span style="font-weight: 600; font-size: 0.78rem;">📄 <?php echo htmlspecialchars(trim($titulo)); ?></span>
                                        <?php $c = trim($conteudos[$i] ?? ''); if ($c): ?>
                                            <span style="font-size: 0.74rem; color: #444; display: block; margin-top: 2px; line-height: 1.4;">
                                                <?php echo htmlspecialchars(mb_substr($c, 0, 150)) . (mb_strlen($c) > 150 ? '...' : ''); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- Itens em conformidade -->
            <?php if (!empty($conformes)): ?>
                <div class="print-secao-titulo print-titulo-conforme" style="margin-top: 18px;">
                    ✅ Itens em Conformidade (<?php echo count($conformes); ?>)
                </div>
                <?php foreach ($conformes as $item): ?>
                    <?php
                        $temMat    = !empty($item['materiais_titulos']);
                        $titulos   = $temMat ? explode('||', $item['materiais_titulos']) : [];
                        $conteudos = $temMat ? explode('||', $item['materiais_conteudos'] ?? '') : [];
                    ?>
                    <div class="print-item print-item-conforme">
                        <div class="print-item-header">
                            <span style="flex: 1; font-weight: 600; font-size: 0.88rem; line-height: 1.4;">
                                <?php echo htmlspecialchars($item['texto']); ?>
                            </span>
                            <span style="font-size: 0.75rem; color: #555; white-space: nowrap; margin-left: 10px;">
                                Categoria: <?php echo htmlspecialchars($item['categoria'] ?? 'Geral'); ?> | Peso: <?php echo $item['peso']; ?>
                            </span>
                        </div>
                        <?php if ($temMat): ?>
                            <div class="print-materiais">
                                <div style="font-size: 0.75rem; font-weight: 700; color: #00CC66; margin-bottom: 4px;">
                                    Materiais de referência:
                                </div>
                                <?php foreach ($titulos as $i => $titulo): ?>
                                    <?php if (!trim($titulo)) continue; ?>
                                    <div class="print-material-item" style="border-left-color: #00CC66; background: #f0fdf4;">
                                        <span style="font-weight: 600; font-size: 0.78rem;">📄 <?php echo htmlspecialchars(trim($titulo)); ?></span>
                                        <?php $c = trim($conteudos[$i] ?? ''); if ($c): ?>
                                            <span style="font-size: 0.74rem; color: #444; display: block; margin-top: 2px; line-height: 1.4;">
                                                <?php echo htmlspecialchars(mb_substr($c, 0, 150)) . (mb_strlen($c) > 150 ? '...' : ''); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="print-linha" style="margin-top: 20px;"></div>
            <div style="font-size: 0.7rem; color: #888; text-align: center; margin-top: 8px;">
                LGPD4DEVS — Ferramenta de conformidade com a Lei Geral de Proteção de Dados (Lei nº 13.709/2018)
            </div>
        </div>

    <?php endif; ?>
</main>

<!-- Modal: Salvar -->
<?php if (isset($_SESSION['user_id'])): ?>
<div id="modalSalvar" class="modal-overlay no-print" style="display: none;">
    <div class="modal-content" style="max-width: 520px;">
        <h2 style="color: var(--primary); margin-bottom: 20px;"><i class="fas fa-save"></i> Salvar Diagnóstico</h2>
        <p style="color: var(--text-muted); margin-bottom: 25px;">Vincule a um projeto para acompanhar sua evolução.</p>

        <div style="display: flex; border-bottom: 2px solid var(--border); margin-bottom: 25px;">
            <button onclick="trocarAba('existente')" id="aba-existente"
                    style="flex:1; padding:12px; background:transparent; border:none; border-bottom:3px solid var(--primary); color:var(--primary); font-weight:600; cursor:pointer;">
                Projeto Existente
            </button>
            <button onclick="trocarAba('novo')" id="aba-novo"
                    style="flex:1; padding:12px; background:transparent; border:none; border-bottom:3px solid transparent; color:var(--text-muted); font-weight:600; cursor:pointer;">
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
                    Nenhum projeto. Use a aba "Novo Projeto".
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
                          style="width:100%; padding:14px 16px; border:2px solid var(--border); border-radius:10px; font-family:inherit; font-size:1rem; resize:vertical;"></textarea>
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

<div id="toast" style="display:none; position:fixed; bottom:20px; right:16px; left:16px; padding:14px 20px; border-radius:12px; font-weight:600; z-index:3000; box-shadow:0 4px 20px rgba(0,0,0,0.2); color:white; text-align:center; max-width:400px; margin:0 auto;"></div>

<style>
/* ── Barra sticky ── */
.barra-acoes {
    position: sticky;
    top: 0;
    z-index: 900;
    background: white;
    border-bottom: 1px solid #E2E8F0;
    padding: 10px 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.barra-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
}
.barra-titulo {
    font-weight: 700;
    color: var(--text-main);
    font-size: 0.9rem;
}
.barra-botoes {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.btn-sm {
    padding: 7px 14px !important;
    font-size: 0.82rem !important;
    min-width: unset !important;
}

/* ── Score ── */
.resultado-main { padding-top: 25px; padding-bottom: 60px; }
.score-card { padding: 25px 30px; margin-bottom: 25px; }
.score-inner { display: flex; align-items: center; gap: 30px; flex-wrap: wrap; justify-content: center; }
.score-numero { text-align: center; min-width: 110px; }
.score-detalhe { flex: 1; min-width: 180px; }
.score-metricas { display: flex; gap: 15px; flex-wrap: wrap; }

/* ── Grupo header ── */
.grupo-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    border-radius: 12px;
    padding: 12px 18px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 10px;
    border: 1px solid;
}
.grupo-falha { background: #FEF2F2; border-color: #FECACA; color: var(--error); }
.grupo-conforme { background: #F0FDF4; border-color: #BBF7D0; color: var(--secondary); }
.chevron-grupo { transition: transform 0.3s; font-size: 0.85rem; }

/* ── Accordion ── */
.accordion-card {
    background: white;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    margin-bottom: 8px;
    overflow: hidden;
    transition: box-shadow 0.2s;
}
.accordion-card:hover { box-shadow: 0 3px 12px rgba(0,0,0,0.07); }
.accordion-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 14px 18px;
    cursor: pointer;
    user-select: none;
}
.accordion-icon { color: var(--text-muted); font-size: 0.82rem; flex-shrink: 0; transition: transform 0.3s; }
.accordion-icon.aberto { transform: rotate(180deg); }

/* ── Print doc (invisível na tela) ── */
.print-doc { display: none; }

/* ── Mobile ── */
@media (max-width: 600px) {
    .barra-titulo { display: none; }
    .barra-botoes { width: 100%; justify-content: stretch; }
    .barra-botoes .btn-sm { flex: 1; text-align: center; justify-content: center; font-size: 0.78rem !important; padding: 7px 6px !important; }
    .score-card { padding: 20px 18px; }
    .score-inner { gap: 18px; }
    .score-numero { min-width: auto; }
    .score-metricas { gap: 10px; }
    .resultado-main { padding-top: 15px; }
    .accordion-header { padding: 12px 14px; }
}

/* ── Impressão ── */
@media print {
    @page { margin: 1.2cm; size: A4; }
    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    a::after { content: none !important; }

    .no-print,
    .navbar,
    .main-footer,
    .barra-acoes,
    .score-card,
    .grupo-header,
    #grupoFalhas,
    #grupoConformes,
    .accordion-card { display: none !important; }

    .print-doc { display: block !important; }

    body { background: white !important; font-family: 'Inter', sans-serif; }
}

/* ── Estilos print ── */
.print-cabecalho {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}
.print-linha {
    border: none;
    border-top: 2px solid #0066FF;
    margin: 10px 0;
}
.print-score-box {
    border: 2px solid;
    border-radius: 10px;
    padding: 16px 20px;
    text-align: center;
    margin: 14px 0;
}
.print-secao-titulo {
    font-size: 0.9rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 8px 12px;
    border-radius: 6px;
    margin: 14px 0 8px;
}
.print-titulo-falha { background: #FEF2F2; color: #EF4444; }
.print-titulo-conforme { background: #F0FDF4; color: #00CC66; }
.print-item {
    border-left: 4px solid;
    border-radius: 6px;
    padding: 10px 12px;
    margin-bottom: 8px;
    page-break-inside: avoid;
}
.print-item-falha { border-color: #EF4444; background: #fff5f5; }
.print-item-conforme { border-color: #00CC66; background: #f0fdf4; }
.print-item-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px; }
.print-materiais { margin-top: 8px; padding-top: 8px; border-top: 1px solid #e5e7eb; }
.print-material-item {
    border-left: 3px solid #0066FF;
    background: #f8fafc;
    padding: 6px 10px;
    margin-bottom: 5px;
    border-radius: 4px;
    font-size: 0.78rem;
}
</style>

<script>
const csrfToken = "<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>";

function toggleAccordion(uid) {
    const body = document.getElementById(uid);
    const icon = document.getElementById('icon-' + uid);
    const aberto = body.style.display !== 'none';
    body.style.display = aberto ? 'none' : 'block';
    icon.classList.toggle('aberto', !aberto);
}

function toggleGrupoFalhas(btn) {
    const grupo = document.getElementById('grupoFalhas');
    const icon  = btn.querySelector('.chevron-grupo');
    const aberto = grupo.style.display !== 'none';
    grupo.style.display = aberto ? 'none' : 'block';
    icon.style.transform = aberto ? 'rotate(180deg)' : '';
}

function toggleGrupoConformes(btn) {
    const grupo = document.getElementById('grupoConformes');
    const icon  = btn.querySelector('.chevron-grupo');
    const aberto = grupo.style.display !== 'none';
    grupo.style.display = aberto ? 'none' : 'block';
    icon.style.transform = aberto ? '' : 'rotate(180deg)';
}

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
        .then(r => { if (!r.ok) throw new Error('Erro ' + r.status); return r.json(); })
        .then(data => {
            select.disabled  = false;
            select.innerHTML = '<option value="">-- selecione --</option>';
            if (!Array.isArray(data) || data.length === 0) { msgVazio.style.display = 'block'; return; }
            msgVazio.style.display = 'none';
            data.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id; opt.textContent = p.nome;
                select.appendChild(opt);
            });
        })
        .catch(() => { select.disabled = false; select.innerHTML = '<option value="">Erro ao carregar</option>'; });
}

function salvarEmExistente() {
    const id = document.getElementById('selectProjeto').value;
    if (!id) { mostrarToast('Selecione um projeto.', false); return; }
    enviarSalvar({ csrf_token: csrfToken, projeto_id: id });
}

function salvarEmNovo() {
    const nome = document.getElementById('np-nome').value.trim();
    if (!nome) { mostrarToast('Nome é obrigatório.', false); return; }
    fetch('/projetos/criar', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            csrf_token: csrfToken, nome,
            descricao: document.getElementById('np-descricao').value,
            publico_alvo: document.getElementById('np-publico').value,
            status: 'em_desenvolvimento'
        })
    })
    .then(r => r.json())
    .then(p => { if (!p.sucesso) throw new Error(p.mensagem); enviarSalvar({ csrf_token: csrfToken, projeto_id: p.projeto_id }); })
    .catch(e => mostrarToast(e.message, false));
}

function enviarSalvar(params) {
    fetch('/salvar-resultado', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(params)
    })
    .then(r => r.json())
    .then(data => {
        if (data.sucesso) { fecharModalSalvar(); mostrarToast('Salvo com sucesso!', true); setTimeout(() => location.href = '/projetos', 1800); }
        else mostrarToast(data.mensagem || 'Erro ao salvar.', false);
    })
    .catch(() => mostrarToast('Erro de comunicação.', false));
}

function mostrarToast(msg, sucesso) {
    const t = document.getElementById('toast');
    t.textContent = (sucesso ? '✅ ' : '❌ ') + msg;
    t.style.background = sucesso ? 'var(--secondary)' : 'var(--error)';
    t.style.display = 'block';
    setTimeout(() => t.style.display = 'none', 3000);
}

window.addEventListener('click', e => { if (e.target.id === 'modalSalvar') fecharModalSalvar(); });
</script>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>