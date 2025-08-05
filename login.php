<?php
session_start();
require_once(__DIR__ . '/conexao.php');

if (!$conn) {
    die("Erro: conexão com o banco não estabelecida.");
}

$erro_login = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT * FROM cliente WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $cliente = $resultado->fetch_assoc();
        if (password_verify($senha, $cliente['senha'])) {
            $_SESSION['cliente_id'] = $cliente['id'];
            $_SESSION['usuario'] = $cliente['nome'];
            header("Location: index.php");
            exit;
        } else {
            $erro_login = "Senha incorreta.";
        }
    } else {
        $erro_login = "E-mail não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Minuto & Sabor</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #FDF6EC;
            margin: 0;
            padding: 30px;
            color: #3A2B47;
        }
        form {
            background: white;
            max-width: 400px;
            margin: auto;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 12px #ccc;
        }
        h2 {
            color: #5D3A6A;
            text-align: center;
        }
        label {
            display: block;
            margin-top: 15px;
            color: #3A2B47;
        }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 4px;
            border: 1px solid #aaa;
            box-sizing: border-box;
        }
        button {
            margin-top: 25px;
            width: 100%;
            background: #5D3A6A;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background: #3A2B47;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
        .register-link {
            text-align: center;
            margin-top: 15px;
        }
        .register-link p {
            margin-bottom: 0;
        }
        .register-link a {
            color: #5D3A6A;
            text-decoration: none;
            font-weight: bold;
        }
        .register-link a:hover {
            color: #3A2B47;
        }
    </style>
</head>
<body>
    <form method="POST" action="login.php">
        <h2>Login do Cliente</h2>
        <p class="error-message"><?= htmlspecialchars($erro_login) ?></p>

        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" required>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required>

        <button type="submit">Entrar</button>

        <div class="register-link">
            <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se aqui</a></p>
        </div>
    </form>
</body>
</html>