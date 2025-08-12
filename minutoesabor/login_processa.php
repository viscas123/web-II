<?php
// login_processa.php
// Substitua o arquivo atual por este. Salve como UTF-8 sem BOM.

session_start();
require_once __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$senha_digitada = isset($_POST['senha']) ? $_POST['senha'] : '';

if ($email === '' || $senha_digitada === '') {
    $_SESSION['erro'] = 'Preencha e-mail e senha.';
    header('Location: login.php');
    exit;
}

$stmt = $conn->prepare('SELECT id, nome, senha FROM cliente WHERE email = ? LIMIT 1');
if (!$stmt) {
    $_SESSION['erro'] = 'Erro interno: ' . $conn->error;
    header('Location: login.php');
    exit;
}
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $usuario = $result->fetch_assoc();
    if (password_verify($senha_digitada, $usuario['senha'])) {
        // login ok
        $_SESSION['usuario'] = $usuario['nome'];
        $_SESSION['usuario_id'] = $usuario['id'];

        // mensagem e limpar carrinho
        $_SESSION['mensagem'] = 'Pedido concluído e em preparo!!';
        unset($_SESSION['carrinho']);

        $stmt->close();
        $conn->close();
        header('Location: index.php');
        exit;
    }
}

// login inválido
$_SESSION['erro'] = 'E-mail ou senha inválidos.';
if (isset($stmt)) { $stmt->close(); }
if (isset($conn)) { $conn->close(); }
header('Location: login.php?erro=1');
exit;
