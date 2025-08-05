<?php
session_start();

require_once __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

$email = trim($_POST['email']);
$senha_digitada = $_POST['senha'];

$stmt = $conn->prepare("SELECT id, nome, senha FROM cliente WHERE email = ?");
if ($stmt === false) {
    header("Location: login.php?erro=" . urlencode("Erro interno no servidor. Tente novamente mais tarde."));
    exit();
}

$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $cliente = $resultado->fetch_assoc();
    if (password_verify($senha_digitada, $cliente['senha'])) {
        $_SESSION['cliente_id'] = $cliente['id'];
        $_SESSION['usuario'] = $cliente['nome'];
        $stmt->close();
        $conn->close();

        // Redireciona para o carrinho após o login
        header("Location: carrinho.php");
        exit();
    }
}

$erro = "Usuário ou senha inválidos.";
if (isset($stmt)) {
    $stmt->close();
}
if (isset($conn)) {
    $conn->close();
}

header("Location: login.php?erro=" . urlencode($erro));
exit();
?>