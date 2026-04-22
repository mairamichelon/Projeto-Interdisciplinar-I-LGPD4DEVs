<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<main class="container content-area" style="display: flex; align-items: center; justify-content: center; min-height: 70vh;">
    <div class="card-auth">
        <div style="margin-bottom: 30px;">
            <h2 style="color: var(--text-main); font-size: 1.8rem; margin-bottom: 10px;">Acessar Conta</h2>
            <p style="color: var(--text-muted); font-size: 0.95rem;">Entre com suas credenciais de desenvolvedor.</p>
        </div>

        <?php if (!empty($erro)): ?>
            <div style="background: #FEE2E2; color: #B91C1C; padding: 15px; border-radius: 10px; margin-bottom: 25px; border: 1px solid #FECACA; font-size: 0.9rem; text-align: left;">
                <strong>⚠️ Atenção:</strong> <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST">
            <!-- Token CSRF (novo - proteção contra CSRF) -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">

            <div class="form-group">
                <label for="email">E-mail Corporativo</label>
                <input type="email" id="email" name="email" required placeholder="seu@email.com"
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>

            <div class="form-group" style="margin-bottom: 10px;">
                <label for="senha">Senha de Acesso</label>
                <input type="password" id="senha" name="senha" required placeholder="Digite sua senha">
            </div>

            <div style="text-align: right; margin-bottom: 25px;">
                <a href="#" style="font-size: 0.85rem; color: var(--primary); text-decoration: none;">Esqueceu a senha?</a>
            </div>

            <button type="submit" class="btn-save">Entrar no Sistema</button>
        </form>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #F1F5F9;">
            <p style="font-size: 0.95rem; color: var(--text-muted);">
                Ainda não tem acesso?
                <a href="/cadastro" style="color: var(--primary); font-weight: 700; text-decoration: none;">Crie sua conta agora</a>
            </p>
        </div>
    </div>
</main>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
