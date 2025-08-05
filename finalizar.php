<?php
session_start();
include 'conexao.php';

// Verifica se o carrinho está vazio antes de prosseguir
if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    echo "Carrinho vazio! Redirecionando para a página de produtos...";
    header("refresh:3;url=index.php"); // Redireciona após 3 segundos
    exit();
}

$total = 0;
foreach ($_SESSION['carrinho'] as $item) {
    // Garante que o total seja calculado corretamente, verificando se as chaves existem
    if (isset($item['preco']) && isset($item['quantidade'])) {
        $total += $item['preco'] * $item['quantidade'];
    }
}

// Insere o pedido principal na tabela 'pedido'
$stmt = $conn->prepare("INSERT INTO pedido (data_pedido, total) VALUES (NOW(), ?)");
if (!$stmt) {
    die("Erro na preparação do pedido: " . $conn->error);
}
$stmt->bind_param("d", $total);

if (!$stmt->execute()) {
    die("Erro ao inserir pedido: " . $stmt->error);
}
$pedido_id = $stmt->insert_id; // Obtém o ID do pedido recém-criado
$stmt->close();

// Insere os itens do pedido na tabela 'item_pedido'
$stmt_item = $conn->prepare("INSERT INTO item_pedido (id_pedido, id_produto, quantidade, preco) VALUES (?, ?, ?, ?)");
if (!$stmt_item) {
    die("Erro na preparação do item do pedido: " . $conn->error);
}

foreach ($_SESSION['carrinho'] as $item) {
    // Adicionada a verificação para garantir que as chaves 'id', 'quantidade' e 'preco' existam
    if (isset($item['id'], $item['quantidade'], $item['preco'])) {
        $produto_id = $item['id'];
        $quantidade = $item['quantidade'];
        $preco = $item['preco'];

        $stmt_item->bind_param("iiid", $pedido_id, $produto_id, $quantidade, $preco);

        if (!$stmt_item->execute()) {
            die("Erro ao inserir item do pedido: " . $stmt_item->error);
        }
    }
}
$stmt_item->close();

// Limpa o carrinho após a finalização do pedido
unset($_SESSION['carrinho']);

// Redireciona o usuário para a página inicial com uma mensagem de sucesso
header("Location: index.php?pedido=sucesso");
exit();
?>