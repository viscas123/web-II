<?php
// cadastro_processa.php
// Substitua o arquivo atual por este. Salve como UTF-8 sem BOM.

session_start();
require_once __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cadastro.php');
    exit;
}

$nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$senha_raw = isset($_POST['senha']) ? $_POST['senha'] : '';

if ($nome === '' || $email === '' || $senha_raw === '') {
    $_SESSION['erro'] = 'Preencha todos os campos.';
    header('Location: cadastro.php');
    exit;
}

$senha_hash = password_hash($senha_raw, PASSWORD_DEFAULT);

// verifica se email já existe
if ($stmt = $conn->prepare('SELECT id FROM cliente WHERE email = ?')) {
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        $_SESSION['erro'] = 'E-mail já cadastrado.';
        header('Location: cadastro.php');
        exit;
    }
    $stmt->close();
}

$stmt = $conn->prepare('INSERT INTO cliente (nome, email, senha) VALUES (?, ?, ?)');
if (!$stmt) {
    $_SESSION['erro'] = 'Erro interno: ' . $conn->error;
    header('Location: cadastro.php');
    exit;
}
$stmt->bind_param('sss', $nome, $email, $senha_hash);

if ($stmt->execute()) {
    // cadastrado com sucesso -> loga usuário, limpa o carrinho e mostra mensagem
    $_SESSION['usuario'] = $nome;
    $_SESSION['usuario_id'] = $conn->insert_id ?? null;
    $_SESSION['mensagem'] = 'Pedido concluído e em preparo!!';
    unset($_SESSION['carrinho']);

    $stmt->close();
    $conn->close();
    header('Location: index.php');
    exit;
} else {
    $_SESSION['erro'] = 'Erro ao cadastrar: ' . $stmt->error;
    $stmt->close();
    $conn->close();
    header('Location: cadastro.php');
    exit;
}
