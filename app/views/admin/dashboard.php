<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<?php
// Funções de renderização das listas — usadas no HTML inicial
function renderCardUsuario(array $u): string {
    $cor   = $u['perfil'] === 'admin' ? '#764ba2' : '#0066FF';
    $bg    = $u['perfil'] === 'admin' ? '#764ba222' : '#EFF6FF';
    $label = $u['perfil'] === 'admin' ? '⚙️ Admin' : '👤 Usuário';
    $data  = date('d/m/Y', strtotime($u['data_cadastro']));
    $nome  = htmlspecialchars($u['nome']);
    $email = htmlspecialchars($u['email']);
    return "
        <div class='card-lista' style='border-left-color:{$cor};'>
            <div style='display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;'>
                <div style='min-width:0;'>
                    <div style='font-weight:600;color:var(--text-main);font-size:0.92rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;'>{$nome}</div>
                    <div style='font-size:0.78rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;'>{$email}</div>
                </div>
                <div style='text-align:right;flex-shrink:0;'>
                    <span style='background:{$bg};color:{$cor};padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:700;display:inline-block;'>{$label}</span>
                    <div style='font-size:0.72rem;color:var(--text-muted);margin-top:3px;'>{$data}</div>
                </div>
            </div>
        </div>";
}

function renderCardDiagnostico(array $d): string {
    $cor     = $d['percentual'] >= 70 ? 'var(--secondary)' : 'var(--error)';
    $projeto = htmlspecialchars($d['projeto_nome'] ?? 'Sem projeto vinculado');
    $nome    = htmlspecialchars($d['usuario_nome']);
    $data    = date('d/m/Y', strtotime($d['data_salvo']));
    $pct     = $d['percentual'];
    return "
        <div class='card-lista' style='border-left-color:{$cor};'>
            <div style='display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;'>
                <div style='min-width:0;'>
                    <div style='font-weight:600;color:var(--text-main);font-size:0.92rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;'>{$nome}</div>
                    <div style='font-size:0.78rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;'>{$projeto}</div>
                </div>
                <div style='text-align:right;flex-shrink:0;'>
                    <div style='font-size:1.3rem;font-weight:700;color:{$cor};line-height:1;'>{$pct}%</div>
                    <div style='font-size:0.72rem;color:var(--text-muted);margin-top:3px;'>{$data}</div>
                </div>
            </div>
        </div>";
}
?>

<main class="container content-area">

    <!-- Cabeçalho -->
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:35px; flex-wrap:wrap; gap:15px;">
        <div>
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:6px;">
                <span style="background:linear-gradient(135deg,#667eea,#764ba2); color:white; padding:6px 14px; border-radius:8px; font-size:0.8rem; font-weight:700;">
                    <i class="fas fa-shield-alt"></i> PAINEL ADMIN
                </span>
                <!-- Indicador de polling -->
                <span style="font-size:0.78rem; color:var(--text-muted); display:flex; align-items:center; gap:5px;">
                    <span id="pontinho" style="width:8px; height:8px; border-radius:50%; background:var(--secondary); display:inline-block;"></span>
                    <span id="textoPolling">ao vivo</span>
                </span>
            </div>
            <h1 style="margin:0; font-size:1.8rem;">Dashboard</h1>
            <p style="color:var(--text-muted); margin:4px 0 0;">Visão geral do sistema LGPD4DEVS</p>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="/admin/materiais" class="btn-primary" style="font-size:0.9rem; min-width:unset; padding:8px 16px;">
                <i class="fas fa-book"></i> Materiais
            </a>
            <a href="/admin/usuarios" class="btn-secondary" style="font-size:0.9rem; min-width:unset; padding:8px 16px;">
                <i class="fas fa-users"></i> Usuários
            </a>
        </div>
    </div>

    <!-- Cards de estatísticas -->
    <div class="admin-stats-grid">

        <div class="card-material admin-stat-card" style="border-top:4px solid var(--primary);">
            <div class="admin-stat-numero" id="stat-usuarios" style="color:var(--primary);"><?php echo $stats['total_usuarios']; ?></div>
            <div class="admin-stat-label"><i class="fas fa-users"></i> Usuários</div>
        </div>

        <div class="card-material admin-stat-card" style="border-top:4px solid #764ba2;">
            <div class="admin-stat-numero" id="stat-admins" style="color:#764ba2;"><?php echo $stats['total_admins']; ?></div>
            <div class="admin-stat-label"><i class="fas fa-shield-alt"></i> Admins</div>
        </div>

        <div class="card-material admin-stat-card" style="border-top:4px solid var(--secondary);">
            <div class="admin-stat-numero" id="stat-projetos" style="color:var(--secondary);"><?php echo $stats['total_projetos']; ?></div>
            <div class="admin-stat-label"><i class="fas fa-folder"></i> Projetos</div>
        </div>

        <div class="card-material admin-stat-card" style="border-top:4px solid #F59E0B;">
            <div class="admin-stat-numero" id="stat-diagnosticos" style="color:#F59E0B;"><?php echo $stats['total_diagnosticos']; ?></div>
            <div class="admin-stat-label"><i class="fas fa-clipboard-check"></i> Diagnósticos</div>
        </div>

        <div class="card-material admin-stat-card" style="border-top:4px solid #06B6D4;">
            <div class="admin-stat-numero" id="stat-materiais" style="color:#06B6D4;"><?php echo $stats['total_materiais']; ?></div>
            <div class="admin-stat-label"><i class="fas fa-book"></i> Materiais</div>
        </div>

        <div class="card-material admin-stat-card" style="border-top:4px solid <?php echo ($stats['media_conformidade'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>;">
            <div class="admin-stat-numero" id="stat-media" style="color:<?php echo ($stats['media_conformidade'] >= 70) ? 'var(--secondary)' : 'var(--error)'; ?>;">
                <?php echo $stats['media_conformidade']; ?>%
            </div>
            <div class="admin-stat-label"><i class="fas fa-chart-line"></i> Média Geral</div>
        </div>

    </div>

    <!-- Listas -->
    <div class="admin-listas-grid">

        <div>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                <h3 style="margin:0; font-size:1.05rem;">Últimos Cadastros</h3>
                <a href="/admin/usuarios" style="font-size:0.85rem; color:var(--primary); text-decoration:none; font-weight:600;">Ver todos →</a>
            </div>
            <div id="listaUsuarios">
                <?php foreach ($ultimosUsuarios as $u): ?>
                    <?php echo renderCardUsuario($u); ?>
                <?php endforeach; ?>
            </div>
        </div>

        <div>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                <h3 style="margin:0; font-size:1.05rem;">Últimos Diagnósticos</h3>
            </div>
            <div id="listaDiagnosticos">
                <?php foreach ($ultimosDiagnosticos as $d): ?>
                    <?php echo renderCardDiagnostico($d); ?>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

</main>

<style>
.admin-stats-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 16px;
    margin-bottom: 40px;
}

.admin-stat-card { text-align:center; padding:20px 15px; }

.admin-stat-numero {
    font-size: 2.2rem;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 6px;
    transition: transform 0.3s;
}

.admin-stat-label { color:var(--text-muted); font-size:0.82rem; }

.numero-atualizado { animation: pulse 0.5s ease; }

@keyframes pulse {
    0%   { transform: scale(1); }
    50%  { transform: scale(1.15); }
    100% { transform: scale(1); }
}

.admin-listas-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.card-lista {
    padding: 14px 18px;
    margin-bottom: 10px;
    border-radius: 12px;
    border: 1px solid #E2E8F0;
    border-left: 4px solid;
    background: white;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
}

.item-novo { animation: slideIn 0.4s ease; }

@keyframes slideIn {
    from { opacity:0; transform:translateY(-8px); }
    to   { opacity:1; transform:translateY(0); }
}

@media (max-width: 900px) {
    .admin-stats-grid { grid-template-columns: repeat(3, 1fr); }
}

@media (max-width: 600px) {
    .admin-stats-grid { grid-template-columns: repeat(2, 1fr); gap:12px; }
    .admin-stat-numero { font-size:1.8rem; }
    .admin-stat-card { padding:16px 10px; }
    .admin-listas-grid { grid-template-columns:1fr; gap:20px; }
}
</style>

<script>
// ── Renderização JS (espelha o PHP acima) ─────────────────────────────────────

function esc(str) {
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function renderUsuarioJS(u, isNovo) {
    const cor   = u.perfil === 'admin' ? '#764ba2' : '#0066FF';
    const bg    = u.perfil === 'admin' ? '#764ba222' : '#EFF6FF';
    const label = u.perfil === 'admin' ? '⚙️ Admin' : '👤 Usuário';
    const data  = u.data_cadastro ? u.data_cadastro.substring(0,10).split('-').reverse().join('/') : '';
    const novo  = isNovo ? 'item-novo' : '';
    return `<div class="card-lista ${novo}" style="border-left-color:${cor};">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;">
            <div style="min-width:0;">
                <div style="font-weight:600;color:var(--text-main);font-size:0.92rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;">${esc(u.nome)}</div>
                <div style="font-size:0.78rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;">${esc(u.email)}</div>
            </div>
            <div style="text-align:right;flex-shrink:0;">
                <span style="background:${bg};color:${cor};padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:700;display:inline-block;">${label}</span>
                <div style="font-size:0.72rem;color:var(--text-muted);margin-top:3px;">${data}</div>
            </div>
        </div>
    </div>`;
}

function renderDiagnosticoJS(d, isNovo) {
    const cor    = parseInt(d.percentual) >= 70 ? 'var(--secondary)' : 'var(--error)';
    const projeto = d.projeto_nome || 'Sem projeto vinculado';
    const data   = d.data_salvo ? d.data_salvo.substring(0,10).split('-').reverse().join('/') : '';
    const novo   = isNovo ? 'item-novo' : '';
    return `<div class="card-lista ${novo}" style="border-left-color:${cor};">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;">
            <div style="min-width:0;">
                <div style="font-weight:600;color:var(--text-main);font-size:0.92rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;">${esc(d.usuario_nome)}</div>
                <div style="font-size:0.78rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;">${esc(projeto)}</div>
            </div>
            <div style="text-align:right;flex-shrink:0;">
                <div style="font-size:1.3rem;font-weight:700;color:${cor};line-height:1;">${d.percentual}%</div>
                <div style="font-size:0.72rem;color:var(--text-muted);margin-top:3px;">${data}</div>
            </div>
        </div>
    </div>`;
}

// ── Estado para comparação ────────────────────────────────────────────────────

let primeiroUsuarioId     = <?php echo !empty($ultimosUsuarios)     ? (int)$ultimosUsuarios[0]['id']     : 0; ?>;
let primeiroDiagnosticoTs = "<?php echo !empty($ultimosDiagnosticos) ? addslashes($ultimosDiagnosticos[0]['data_salvo']) : ''; ?>";

// ── Indicador de tempo ────────────────────────────────────────────────────────

let segundos = 0;
let timer;

function iniciarContador() {
    clearInterval(timer);
    segundos = 0;
    atualizarIndicador();
    timer = setInterval(() => { segundos++; atualizarIndicador(); }, 1000);
}

function atualizarIndicador() {
    const texto = document.getElementById('textoPolling');
    const ponto = document.getElementById('pontinho');
    if (!texto) return;
    if (segundos === 0) {
        texto.textContent = 'atualizado agora';
        ponto.style.background = 'var(--secondary)';
    } else if (segundos < 60) {
        texto.textContent = `há ${segundos}s`;
        ponto.style.background = segundos < 25 ? 'var(--secondary)' : '#F59E0B';
    } else {
        texto.textContent = 'verificando...';
        ponto.style.background = 'var(--text-muted)';
    }
}

// ── Atualiza número com animação ──────────────────────────────────────────────

function atualizarNumero(id, novo, sufixo = '') {
    const el = document.getElementById(id);
    if (!el) return;
    const atual = el.textContent.replace('%','').trim();
    if (String(atual) !== String(novo)) {
        el.textContent = novo + sufixo;
        el.classList.remove('numero-atualizado');
        void el.offsetWidth;
        el.classList.add('numero-atualizado');
    }
}

// ── Polling ───────────────────────────────────────────────────────────────────

function fazerPolling() {
    fetch('/api/admin/dashboard', { credentials: 'same-origin' })
        .then(r => { if (!r.ok) throw new Error(); return r.json(); })
        .then(data => {
            if (data.erro) return;

            atualizarNumero('stat-usuarios',     data.stats.total_usuarios);
            atualizarNumero('stat-admins',       data.stats.total_admins);
            atualizarNumero('stat-projetos',     data.stats.total_projetos);
            atualizarNumero('stat-diagnosticos', data.stats.total_diagnosticos);
            atualizarNumero('stat-materiais',    data.stats.total_materiais);
            atualizarNumero('stat-media',        data.stats.media_conformidade, '%');

            // Atualiza lista de usuários se houver novo
            const novoId = data.ultimosUsuarios.length > 0 ? data.ultimosUsuarios[0].id : 0;
            if (novoId !== primeiroUsuarioId) {
                primeiroUsuarioId = novoId;
                const el = document.getElementById('listaUsuarios');
                if (el) el.innerHTML = data.ultimosUsuarios.map((u, i) => renderUsuarioJS(u, i === 0)).join('');
            }

            // Atualiza lista de diagnósticos se houver novo
            const novoTs = data.ultimosDiagnosticos.length > 0 ? data.ultimosDiagnosticos[0].data_salvo : '';
            if (novoTs !== primeiroDiagnosticoTs) {
                primeiroDiagnosticoTs = novoTs;
                const el = document.getElementById('listaDiagnosticos');
                if (el) el.innerHTML = data.ultimosDiagnosticos.map((d, i) => renderDiagnosticoJS(d, i === 0)).join('');
            }

            iniciarContador();
        })
        .catch(() => {
            const ponto = document.getElementById('pontinho');
            if (ponto) ponto.style.background = 'var(--error)';
        });
}

iniciarContador();
fazerPolling();
setInterval(fazerPolling, 30000);
</script>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>