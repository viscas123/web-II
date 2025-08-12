<?php
// finalizar.php
// Substitua o arquivo atual por este se você só quer limpar o carrinho e mostrar a mensagem.
// Salve como UTF-8 sem BOM.

session_start();
require_once __DIR__ . '/conexao.php';

// Se carrinho vazio, volta para index com aviso
if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    $_SESSION['mensagem'] = 'Seu carrinho está vazio.';
    header('Location: index.php');
    exit;
}

// Se você tem lógica de salvar pedido no banco, insira aqui antes de limpar o carrinho.
// -> Exemplo: inserir pedido em pedidos, inserir itens em itens_pedido, etc.
// (Se quiser, eu adapto com base no seu schema do banco.)

// Depois de processar o pedido, limpar carrinho e mostrar mensagem
unset($_SESSION['carrinho']);
$_SESSION['mensagem'] = 'Pedido concluído e em preparo!!';

header('Location: index.php');
exit;
