<?php
session_start();

// Usuário fixo para demo
$usuario_correto = "admin";
$senha_correta = "123456";

if (isset($_POST['usuario'], $_POST['senha'])) {
    $user = $_POST['usuario'];
    $pass = $_POST['senha'];
    if ($user === $usuario_correto && $pass === $senha_correta) {
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
    </form>
</main>
</body>
</html>
