<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LGPD4DEVS - Inteligência em Conformidade</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome (estava faltando no original) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="/css/estilo.css">
</head>
<body>
    <header class="navbar">
        <div class="container">
            <a href="/">
                <img src="/img/logo.png" alt="LGPD4DEVS" class="logo">
            </a>

            <nav>
                <ul>
                    <li><a href="/">Início</a></li>
                    <li><a href="/checklist">Checklist</a></li>
                    <li><a href="/materiais">Materiais</a></li>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li>
                            <div class="user-badge">
                                👤 Olá, <?php echo htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]); ?>!
                            </div>
                        </li>
                        <li>
                            <a href="/logout" class="btn-logout">Sair</a>
                        </li>
                    <?php else: ?>
                        <li><a href="/login">Entrar</a></li>
                        <li><a href="/cadastro" class="btn-login">Cadastrar</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
