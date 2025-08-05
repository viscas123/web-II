<?php
session_start();

$admin_usuario = 'admin';      // usuário fixo do admin
$admin_senha = '123456';       // senha fixa do admin (em texto plano)

// Se o formulário foi enviado
if (isset($_POST['usuario'], $_POST['senha'])) {
    $user = $_POST['usuario'];
    $pass = $_POST['senha'];

    // Verifica se o usuário e senha batem com os fixos
    if ($user === $admin_usuario && $pass === $admin_senha) {
        $_SESSION['logado'] = true;
        header('Location: painel.php');
        exit;
    } else {
        $erro = "Usuário ou senha inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Login Administrativo - Minuto & Sabor</title>
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<header>
    <h1>Login Administrativo</h1>
</header>
<main>
    <?php if (isset($erro)): ?>
        <p style="color:red;text-align:center;"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>
    <form method="post" style="max-width:300px;margin: 20px auto; background:#fff;padding:20px;border-radius:12px;">
        <label for="usuario">Usuário:</label>
        <input type="text" name="usuario" id="usuario" required />
        <br><br>
        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required />
        <br><br>
        <button type="submit">Entrar</button>
        <a href="../index.php"> Voltar </a>
    </form>
</main>
</body>
</html>
