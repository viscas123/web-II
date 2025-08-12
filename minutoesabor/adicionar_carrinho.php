<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $preco = floatval($_POST['preco'] ?? 0);

    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }

    // Verifica se o produto já está no carrinho
    $idExistente = null;
    foreach ($_SESSION['carrinho'] as $id => $item) {
        if ($item['nome'] === $nome && empty($item['adicionais']) && empty($item['complementos'])) {
            $idExistente = $id;
            break;
        }
    }

    if ($idExistente !== null) {
        $_SESSION['carrinho'][$idExistente]['quantidade'] += 1;
    } else {
        $novoId = uniqid();
        $_SESSION['carrinho'][$novoId] = [
            'nome' => $nome,
            'preco' => $preco,
            'quantidade' => 1,
            'adicionais' => [],
            'complementos' => []
        ];
    }
}

header('Location: index.php');
exit;
