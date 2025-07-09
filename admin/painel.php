<?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: login.php');
    exit;
}

// Para simplicidade, os produtos ficarão em arquivo JSON (produtos.json) na pasta adm
$produtos_file = __DIR__ . "/produtos.json";

// Carrega produtos
$produtos = [];
if (file_exists($produtos_file)) {
    $json = file_get_contents($produtos_file);
    $produtos = json_decode($json, true);
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Painel Administrativo - Minuto & Sabor</title>
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<header>
    <h1>Painel Administrativo</h1>
    <nav>
        <a href="cadastrar.php">Cadastrar Produto</a> |
        <a href="editar.php">Editar Produto</a> |
        <a href="logout.php">Sair</a>
    </nav>
</header>
<main>
    <h2>Produtos Cadastrados</h2>
    <?php if (count($produtos) === 0): ?>
        <p>Nenhum produto cadastrado ainda.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td><?= htmlspecialchars($produto['nome']) ?></td>
                        <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($produto['descricao']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>
</body>
</html>
