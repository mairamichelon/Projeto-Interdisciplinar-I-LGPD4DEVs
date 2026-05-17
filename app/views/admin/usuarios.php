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
            <h1 style="margin: 0; font-size: 1.8rem;">Gerenciar Usuários</h1>
            <p style="color: var(--text-muted); margin: 4px 0 0;"><?php echo count($usuarios); ?> conta(s) cadastrada(s)</p>
        </div>
    </div>

    <!-- Campo de busca -->
    <div style="margin-bottom: 25px;">
        <input type="text" id="buscaUsuario" placeholder="🔍 Buscar por nome ou e-mail..."
               oninput="filtrarUsuarios()"
               style="width: 100%; max-width: 450px; padding: 12px 18px; border: 2px solid var(--border); border-radius: 10px; font-size: 1rem; font-family: inherit;">
    </div>

    <!-- Lista de usuários -->
    <div style="display: flex; flex-direction: column; gap: 12px;" id="listaUsuarios">
        <?php foreach ($usuarios as $u): ?>
            <div class="card-pergunta usuario-item"
                 data-nome="<?php echo strtolower($u['nome']); ?>"
                 data-email="<?php echo strtolower($u['email']); ?>"
                 style="padding: 20px 25px; border-left: 5px solid <?php echo $u['perfil'] === 'admin' ? '#764ba2' : 'var(--primary)'; ?>;">

                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">

                    <!-- Info do usuário -->
                    <div style="display: flex; align-items: center; gap: 18px;">
                        <div style="width: 46px; height: 46px; border-radius: 50%; background: <?php echo $u['perfil'] === 'admin' ? 'linear-gradient(135deg, #667eea, #764ba2)' : '#EFF6FF'; ?>; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
                            <?php echo $u['perfil'] === 'admin' ? '⚙️' : '👤'; ?>
                        </div>
                        <div>
                            <div style="font-weight: 700; color: var(--text-main); font-size: 1rem;">
                                <?php echo htmlspecialchars($u['nome']); ?>
                                <?php if ($u['id'] === (int)$_SESSION['user_id']): ?>
                                    <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 400;">(você)</span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size: 0.85rem; color: var(--text-muted);">
                                <?php echo htmlspecialchars($u['email']); ?>
                            </div>
                            <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 3px;">
                                Cadastro: <?php echo date('d/m/Y', strtotime($u['data_cadastro'])); ?>
                                &nbsp;·&nbsp;
                                <?php echo $u['total_projetos']; ?> projeto(s)
                                &nbsp;·&nbsp;
                                <?php echo $u['total_diagnosticos']; ?> diagnóstico(s)
                            </div>
                        </div>
                    </div>

                    <!-- Ações -->
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <span style="background: <?php echo $u['perfil'] === 'admin' ? '#764ba222' : '#EFF6FF'; ?>; color: <?php echo $u['perfil'] === 'admin' ? '#764ba2' : 'var(--primary)'; ?>; padding: 5px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 700;">
                            <?php echo $u['perfil'] === 'admin' ? '⚙️ Admin' : '👤 Usuário'; ?>
                        </span>

                        <?php if ($u['id'] !== (int)$_SESSION['user_id']): ?>
                            <?php if ($u['perfil'] === 'usuario'): ?>
                                <button onclick="alterarPerfil(<?php echo $u['id']; ?>, 'admin', '<?php echo htmlspecialchars(addslashes($u['nome'])); ?>')"
                                        style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 8px 14px; border-radius: 10px; cursor: pointer; font-size: 0.82rem; font-weight: 600;">
                                    <i class="fas fa-arrow-up"></i> Tornar Admin
                                </button>
                            <?php else: ?>
                                <button onclick="alterarPerfil(<?php echo $u['id']; ?>, 'usuario', '<?php echo htmlspecialchars(addslashes($u['nome'])); ?>')"
                                        style="background: transparent; border: 2px solid #764ba2; color: #764ba2; padding: 8px 14px; border-radius: 10px; cursor: pointer; font-size: 0.82rem; font-weight: 600;">
                                    <i class="fas fa-arrow-down"></i> Revogar Admin
                                </button>
                            <?php endif; ?>

                            <button onclick="confirmarDeletar(<?php echo $u['id']; ?>, '<?php echo htmlspecialchars(addslashes($u['nome'])); ?>')"
                                    style="background: transparent; border: 2px solid var(--error); color: var(--error); padding: 8px 12px; border-radius: 10px; cursor: pointer; font-size: 0.82rem;">
                                <i class="fas fa-trash"></i>
                            </button>
                        <?php else: ?>
                            <span style="font-size: 0.8rem; color: var(--text-muted); font-style: italic;">sua conta</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</main>

<!-- Modal: Confirmar alteração de perfil -->
<div id="modalPerfil" class="modal-overlay" style="display: none;">
    <div class="modal-content" style="max-width: 420px; text-align: center;">
        <div id="modalPerfilIcone" style="font-size: 3rem; margin-bottom: 15px;"></div>
        <h3 id="modalPerfilTitulo" style="color: var(--text-main); margin-bottom: 10px;"></h3>
        <p id="modalPerfilTexto" style="color: var(--text-muted); margin-bottom: 30px;"></p>
        <div style="display: flex; gap: 10px; justify-content: center;">
            <button class="btn-secondary" onclick="fecharModalPerfil()">Cancelar</button>
            <button id="btnConfirmarPerfil"
                    style="color: white; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 0.95rem;">
                Confirmar
            </button>
        </div>
    </div>
</div>

<!-- Modal: Confirmar deleção -->
<div id="modalDeletar" class="modal-overlay" style="display: none;">
    <div class="modal-content" style="max-width: 420px; text-align: center;">
        <div style="font-size: 3rem; margin-bottom: 15px;">🗑️</div>
        <h3 style="color: var(--text-main); margin-bottom: 10px;">Deletar conta?</h3>
        <p style="color: var(--text-muted); margin-bottom: 8px;">
            Você está prestes a deletar a conta de <strong id="deletar-nome"></strong>.
        </p>
        <p style="color: var(--error); font-size: 0.88rem; margin-bottom: 30px;">
            Todos os projetos e diagnósticos desta conta também serão removidos. Esta ação não pode ser desfeita.
        </p>
        <div style="display: flex; gap: 10px; justify-content: center;">
            <button class="btn-secondary" onclick="fecharModalDeletar()">Cancelar</button>
            <button id="btnConfirmarDeletar"
                    style="background: var(--error); color: white; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-trash"></i> Sim, deletar
            </button>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast" style="display:none; position:fixed; bottom:30px; right:30px; padding:16px 24px; border-radius:12px; font-weight:600; z-index:3000; box-shadow:0 4px 20px rgba(0,0,0,0.2); color:white;"></div>

<script>
const csrfToken = "<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>";

// ── Filtro de busca ───────────────────────────────────────────────────────────

function filtrarUsuarios() {
    const termo = document.getElementById('buscaUsuario').value.toLowerCase();
    document.querySelectorAll('.usuario-item').forEach(el => {
        const nome  = el.dataset.nome;
        const email = el.dataset.email;
        el.style.display = (nome.includes(termo) || email.includes(termo)) ? 'block' : 'none';
    });
}

// ── Alterar perfil ────────────────────────────────────────────────────────────

function alterarPerfil(id, novoPerfil, nome) {
    const promovendo = novoPerfil === 'admin';
    document.getElementById('modalPerfilIcone').textContent = promovendo ? '⬆️' : '⬇️';
    document.getElementById('modalPerfilTitulo').textContent = promovendo ? 'Tornar administrador?' : 'Revogar acesso admin?';
    document.getElementById('modalPerfilTexto').textContent  = promovendo
        ? `${nome} terá acesso total ao painel administrativo.`
        : `${nome} voltará a ser um usuário comum, sem acesso ao painel.`;

    const btn = document.getElementById('btnConfirmarPerfil');
    btn.style.background = promovendo ? 'linear-gradient(135deg, #667eea, #764ba2)' : '#F59E0B';
    btn.textContent = promovendo ? 'Sim, tornar admin' : 'Sim, revogar';
    btn.onclick = () => enviarAlteracaoPerfil(id, novoPerfil);

    document.getElementById('modalPerfil').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function fecharModalPerfil() {
    document.getElementById('modalPerfil').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function enviarAlteracaoPerfil(id, perfil) {
    fetch('/admin/usuarios/perfil', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ csrf_token: csrfToken, id, perfil })
    })
    .then(r => r.json())
    .then(data => {
        if (data.sucesso) { mostrarToast('Perfil atualizado com sucesso!', true); setTimeout(() => location.reload(), 1200); }
        else mostrarToast(data.mensagem, false);
    });
}

// ── Deletar usuário ───────────────────────────────────────────────────────────

function confirmarDeletar(id, nome) {
    document.getElementById('deletar-nome').textContent = nome;
    document.getElementById('btnConfirmarDeletar').onclick = () => deletarUsuario(id);
    document.getElementById('modalDeletar').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function fecharModalDeletar() {
    document.getElementById('modalDeletar').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function deletarUsuario(id) {
    fetch('/admin/usuarios/deletar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ csrf_token: csrfToken, id })
    })
    .then(r => r.json())
    .then(data => {
        if (data.sucesso) { mostrarToast('Conta removida.', true); setTimeout(() => location.reload(), 1200); }
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
    if (e.target.id === 'modalPerfil')  fecharModalPerfil();
    if (e.target.id === 'modalDeletar') fecharModalDeletar();
});
</script>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>