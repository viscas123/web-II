<?php
session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: login.php');
    exit;
}

$produtos_file = __DIR__ . "/produtos.json";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $preco = floatval($_POST['preco'] ?? 0);
    $descricao = trim($_POST['descricao'] ?? '');

    if ($nome !== '' && $preco > 0) {
        $produtos = [];
        if (file_exists($produtos_file)) {
            $json = file_get_contents($produtos_file);
            $produtos = json_decode($json, true);
        }

        $produtos[] = [
            'nome' => $nome,
            'preco' => $preco,
            'descricao' => $descricao
        ];

        file_put_contents($produtos_file, json_encode($produtos, JSON_PRETTY_PRINT));
        $msg = "Produto cadastrado com sucesso!";
    } else {
        $erro = "Nome e preço são obrigatórios.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Cadastrar Produto - Minuto & Sabor</title>
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<header>
    <h1>Cadastrar Produto</h1>
    <nav>
        <a href="painel.php">Voltar ao Painel</a>
    </nav>
</header>
<main>
    <?php if (isset($msg)): ?>
        <p style="color:green; text-align:center;"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>
    <?php if (isset($erro)): ?>
        <p style="color:red; text-align:center;"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <form method="post" style="max-width:400px;margin: 20px auto; background:#fff;padding:20px;border-radius:12px;">
        <label for="nome">Nome do Produto:</label>
        <input type="text" name="nome" id="nome" required />
        <br><br>
        <label for="preco">Preço (ex: 18.00):</label>
        <input type="number" name="preco" id="preco" step="0.01" min="0" required />
        <br><br>
        <label for="descricao">Descrição:</label>
        <textarea name="descricao" id="descricao" rows="4"></textarea>
        <br><br>
        <button type="submit">Cadastrar</button>
    </form>
</main>
</body>
</html>
