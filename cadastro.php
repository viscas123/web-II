<?php

session_start();

require_once(__DIR__ . '/conexao.php');

if (!$conn) {
    die("Erro: conexão com o banco não estabelecida.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $cpf = trim($_POST['cpf']);
    $telefone = trim($_POST['telefone']);
    $endereco = trim($_POST['endereco']);

    // Verificar se já existe um cliente com o mesmo CPF ou e-mail
    $stmt = $conn->prepare("SELECT * FROM cliente WHERE cpf = ? OR email = ?");
    $stmt->bind_param("ss", $cpf, $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo "<p style='color:red; text-align:center;'>CPF ou e-mail já cadastrados. Por favor, tente novamente ou <a href='login.php' style='color:#5D3A6A; text-decoration:underline;'>faça login</a>.</p>";
    } else {
        // Inserir cliente
        $stmt = $conn->prepare("INSERT INTO cliente (nome, email, senha, cpf, telefone, endereco) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nome, $email, $senha, $cpf, $telefone, $endereco);

        if ($stmt->execute()) {
            $_SESSION['cliente_id'] = $conn->insert_id;
            $_SESSION['usuario'] = $nome;
            header("Location: login.php");
            exit;
        } else {
            echo "<p style='color:red; text-align:center;'>Erro ao cadastrar cliente: " . $stmt->error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Minuto & Sabor</title>
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
            max-width: 500px;
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
            margin-top: 12px;
            color: #3A2B47;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 4px;
            border: 1px solid #aaa;
            box-sizing: border-box;
        }
        button {
            margin-top: 20px;
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
        .login-link {
            text-align: center;
            margin-top: 15px;
        }
        .login-link p {
            margin-bottom: 0;
        }
        .login-link a {
            color: #5D3A6A;
            text-decoration: none;
            font-weight: bold;
        }
        .login-link a:hover {
            color: #3A2B47;
        }
    </style>
</head>
<body>
    <form method="POST" action="cadastro.php">
        <h2>Cadastro do Cliente</h2>
        <label for="nome">Nome Completo:</label>
        <input type="text" name="nome" id="nome" required>

        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" required>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required>

        <label for="cpf">CPF:</label>
        <input type="text" name="cpf" id="cpf" required>

        <label for="telefone">Telefone:</label>
        <input type="text" name="telefone" id="telefone" required>

        <label for="endereco">Endereço de Entrega:</label>
        <input type="text" name="endereco" id="endereco" required>

        <button type="submit">Cadastrar</button>

        <div class="login-link">
            <p>Já tem uma conta? <a href="login.php">Faça login aqui</a></p>
        </div>
    </form>
</body>
</html>