<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<main class="container content-area">
    <div class="checklist-header">
        <h1>Criar Minha Conta</h1>
        <p>Cadastre-se para salvar seus relatórios de conformidade LGPD.</p>
    </div>

    <div class="card-pergunta" style="max-width: 500px; margin: 0 auto; border-left: none; border-top: 6px solid var(--primary);">

        <?php if (!empty($erro)): ?>
            <div style="background: #FEE2E2; color: #B91C1C; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #FECACA;">
                <strong>⚠️ Erro:</strong> <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($sucesso)): ?>
            <div style="background: #DCFCE7; color: #15803D; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #BBF7D0;">
                <strong>✅ Sucesso!</strong> <?php echo $sucesso; ?>
            </div>
        <?php endif; ?>

        <form action="/cadastro" method="POST">
            <!-- Token CSRF (novo - proteção contra CSRF) -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Nome Completo</label>
                <input type="text" name="nome" required placeholder="Ex: Leonardo Henrique"
                       style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">E-mail</label>
                <input type="email" name="email" required placeholder="seu@email.com"
                       style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Senha</label>
                <input type="password" name="senha" required placeholder="Mínimo 8 caracteres"
                       style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px;">
            </div>

            <button type="submit" class="btn-save" style="width: 100%; border: none;">Finalizar Cadastro</button>
        </form>

        <p style="text-align: center; margin-top: 20px; font-size: 0.9rem; color: #666;">
            Já possui uma conta?
            <a href="/login" style="color: var(--primary); font-weight: bold; text-decoration: none;">Fazer Login</a>
        </p>
    </div>
</main>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
