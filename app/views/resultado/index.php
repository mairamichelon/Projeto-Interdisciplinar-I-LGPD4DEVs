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
                    <button onclick="salvarNoHistorico()" id="btnSave" class="btn-primary" style="background-color: var(--secondary); border: none;">
                        <i class="fas fa-save"></i> Salvar Resultado
                    </button>
                <?php endif; ?>
                <button onclick="window.print()" class="btn-secondary">
                    <i class="fas fa-print"></i> Exportar Relatório (PDF)
                </button>
            </div>
            <br>
            <a href="/" style="color: var(--text-muted); text-decoration: none; font-weight: 500;">Voltar ao Início</a>
        </div>
    <?php endif; ?>
</main>

<!-- Modal de Material (idêntico ao original) -->
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

<!-- Toast de confirmação (idêntico ao original) -->
<div id="toast" style="display:none; position: fixed; top: 30px; right: 30px; background: #00CC66; color: white; padding: 20px 35px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.25); z-index: 9999; font-size: 1.1rem; min-width: 300px; animation: slideInRight 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
    <div style="display: flex; align-items: center; gap: 15px;">
        <i class="fas fa-check-circle" style="font-size: 1.6rem;"></i>
        <span id="toast-msg" style="font-weight: 600;"></span>
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

function salvarNoHistorico() {
    const btn = document.getElementById('btnSave');
    btn.innerHTML = "<i class='fas fa-spinner fa-spin'></i> Gravando...";
    btn.disabled  = true;

    setTimeout(() => {
        showToast("Diagnóstico de <?php echo $percentual; ?>% salvo no seu perfil!");
        btn.innerHTML    = "<i class='fas fa-check'></i> Salvo com Sucesso";
        btn.style.opacity = "0.7";
    }, 1200);
}

function showToast(msg) {
    const toast = document.getElementById('toast');
    document.getElementById('toast-msg').innerText = msg;
    toast.style.display  = 'block';
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => { toast.style.display = 'none'; toast.style.opacity = '1'; }, 500);
    }, 4000);
}

window.onclick = function(event) {
    if (event.target.className === 'modal-overlay') fecharModal();
}
</script>

<style>
@keyframes slideInRight {
    from { transform: translateX(100%); opacity: 0; }
    to   { transform: translateX(0);    opacity: 1; }
}

.material-btn {
    cursor: pointer; border: 2px solid transparent; padding: 8px 15px; font-size: 0.75rem; transition: all 0.2s;
}
.material-btn:hover {
    background-color: var(--primary) !important; color: white !important; transform: translateY(-2px);
}

@media print {
    .no-print, .navbar, .main-footer, .hero-note, .btn-primary, .btn-secondary { display: none !important; }
    .card-relatorio {
        page-break-inside: avoid !important; break-inside: avoid !important;
        border: 1px solid #ddd !important; box-shadow: none !important;
        margin-bottom: 20px !important; display: block !important;
    }
    .badge-categoria {
        display: inline-block !important; background: #f1f5f9 !important;
        border: 1px solid #cbd5e1 !important; color: #0066FF !important;
        padding: 5px 10px !important; border-radius: 5px !important;
        font-size: 0.8rem !important; margin-right: 5px !important;
    }
    .container { width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
    body { background: white !important; color: black !important; }
    h1   { font-size: 2rem !important; }
}
</style>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
