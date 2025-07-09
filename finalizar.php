<?php
session_start();

if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    header('Location: index.php');
    exit;
}

$total = 0;
foreach ($_SESSION['carrinho'] as $item => $qtd) {
    // Você pode calcular preço aqui se quiser (para exibir)
    // Para simplificação, vou ignorar precificação
}

// Aqui você implementaria salvar pedido no banco, enviar email, etc.

// Após finalizar, limpa carrinho
$_SESSION['carrinho'] = [];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Pedido Finalizado - Minuto & Sabor</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
<header>
    <h1>Pedido Finalizado</h1>
</header>
<main>
    <p>Obrigado pela sua compra! Seu pedido foi recebido e está sendo processado.</p>
    <a href="index.php">Voltar ao Cardápio</a>
</main>
</body>
</html>
