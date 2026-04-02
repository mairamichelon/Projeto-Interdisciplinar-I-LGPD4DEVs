<?php
// SCRUM-2: Início da lógica do Core
// Aqui no futuro faremos o include da conexão com o banco MySQL 8.4
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LGPD4DEVS - Início</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        header { background: #f4f4f4; padding: 20px; text-align: center; border-bottom: 2px solid #ddd; }
        .container { width: 80%; margin: auto; overflow: hidden; padding: 20px; }
        .hero { display: flex; align-items: center; justify-content: space-between; padding: 50px 0; }
        .btn { display: inline-block; padding: 10px 20px; color: #fff; text-decoration: none; border-radius: 5px; margin-right: 10px; }
        .btn-blue { background: #007bff; }
        .btn-green { background: #28a745; }
    </style>
</head>
<body>

<header>
    <h1>LGPD 4 DEVS</h1>
</header>

<div class="container">
    <section class="hero">
        <div class="texto">
            <h2>Esse projeto busca divulgar conhecimento sobre a LGPD</h2>
            <p>Aplique um checklist para trazer um olhar técnico à sua conformidade, com foco no público infantojuvenil.</p>
            <a href="checklist.php" class="btn btn-blue">Começar Checklist</a>
            <a href="materiais.php" class="btn btn-green">Explorar Materiais</a>
        </div>
        <div class="imagem">
            <img src="logo_lgpd.png" alt="Logo LGPD" style="max-width: 300px;">
        </div>
    </section>
</div>

<footer>
    <p style="text-align: center;">&copy; 2025 LGPD4DEVS - Todos os direitos reservados.</p>
</footer>

</body>
</html>