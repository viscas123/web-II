<?php
session_start();

// mesma função auxiliar (para garantir consistência)
function cliente_logado() {
    if (!empty($_SESSION['cliente_id'])) return true;
    if (!empty($_SESSION['logado']) && $_SESSION['logado'] === true) return true;
    if (!empty($_SESSION['usuario_id'])) return true;
    if (!empty($_SESSION['user_id'])) return true;
    if (!empty($_SESSION['cliente']) && !empty($_SESSION['cliente']['id'])) return true;
    if (!empty($_SESSION['usuario']) && !empty($_SESSION['usuario']['id'])) return true;
    return false;
}

// Se não estiver logado, manda pro cadastro (mesmo comportamento desejado)
if (!cliente_logado()) {
    header("Location: cadastro.php");
    exit;
}

// Processa apenas se vier por POST (evita limpar carrinho com GET direto)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // aqui você pode inserir o pedido no banco (incluir conexao.php e fazer inserts)
    // Exemplo (opcional):
    // require_once 'conexao.php';
    // inserir na tabela pedido -> pegar id_pedido -> inserir itens em item_pedido...
    //
    // Para simplificar agora: vamos limpar o carrinho e mostrar a mensagem.
    $_SESSION['carrinho'] = [];
    $mensagem = "✅ Pedido aceito! Seu pedido foi recebido e sairá para entrega em breve.";
} else {
    // acesso via GET (não esperado) — redireciona pro cardápio ou mostra instrução
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pedido Confirmado - Minuto & Sabor</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <img src="img/logo.png" alt="Logo Minuto & Sabor" class="logo">
    <h1>Pedido Confirmado</h1>
</header>
<main>
    <h2><?= htmlspecialchars($mensagem) ?></h2>
    <p>Obrigado! Em breve nosso entregador estará a caminho.</p>

    <a href="index.php" style="background:#5D3A6A; color:white; padding:10px 15px; text-decoration:none; border-radius:5px;">Voltar ao Cardápio</a>
</main>
<footer>
    <p>&copy; 2025 Minuto & Sabor - Todos os direitos reservados.</p>
</footer>
</body>
</html>
