<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<main class="container content-area" style="display: flex; align-items: center; justify-content: center; min-height: 60vh; text-align: center;">
    <div>
        <h1 style="font-size: 6rem; color: var(--primary); line-height: 1; margin-bottom: 10px;">404</h1>
        <h2 style="color: var(--text-main); margin-bottom: 15px;">Página não encontrada</h2>
        <p style="color: var(--text-muted); margin-bottom: 35px;">
            O endereço que você tentou acessar não existe ou foi movido.
        </p>
        <a href="/" class="btn-primary">Voltar ao Início</a>
    </div>
</main>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
