<?php
session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../conexao.php';
$uploadDir = __DIR__ . '/../imagens/';

$msg = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $preco = floatval($_POST['preco'] ?? 0);
    $descricao = trim($_POST['descricao'] ?? '');

    // Processamento da imagem
    $imagem = '';
    if (!empty($_FILES['imagem']['name'])) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        // Gera nome único para evitar conflito e problemas
        $imagem = uniqid('img_') . '.' . $ext;

        $uploadFile = $uploadDir . $imagem;

        // Cria a pasta caso não exista
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                $erro = "Não foi possível criar a pasta de imagens.";
            }
        }

        if ($erro === '') {
            if (!is_writable($uploadDir)) {
                $erro = "Sem permissão para gravar na pasta de imagens.";
            } elseif (!move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadFile)) {
                $erro = "Falha ao enviar a imagem.";
            }
        }
    } else {
        $erro = "Imagem é obrigatória.";
    }

    if ($erro === '') {
        if ($nome !== '' && $preco > 0) {
            $stmt = $conn->prepare("INSERT INTO produtos (nome, preco, descricao, imagem) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                $erro = "Erro na preparação da query: " . $conn->error;
            } else {
                $stmt->bind_param("sdss", $nome, $preco, $descricao, $imagem);
                if ($stmt->execute()) {
                    $msg = "Produto cadastrado com sucesso!";
                } else {
                    $erro = "Erro ao cadastrar produto: " . $stmt->error;
                }
                $stmt->close();
            }
        } else {
            $erro = "Nome e preço são obrigatórios e preço deve ser maior que zero.";
        }
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
    <?php if ($msg): ?>
        <p style="color:green; text-align:center;"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>
    <?php if ($erro): ?>
        <p style="color:red; text-align:center;"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" style="max-width:400px;margin: 20px auto; background:#fff;padding:20px;border-radius:12px;">
        <label for="nome">Nome do Produto:</label>
        <input type="text" name="nome" id="nome" required />
        <br><br>
        <label for="preco">Preço (ex: 18.00):</label>
        <input type="number" name="preco" id="preco" step="0.01" min="0" required />
        <br><br>
        <label for="descricao">Descrição:</label>
        <textarea name="descricao" id="descricao" rows="4"></textarea>
        <br><br>
        <label for="imagem">Imagem:</label>
        <input type="file" name="imagem" id="imagem" required />
        <br><br>
        <button type="submit">Cadastrar</button>
    </form>
</main>
</body>
</html>
