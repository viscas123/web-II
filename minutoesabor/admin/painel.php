<?php
session_start();

// Verifica se está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../conexao.php'; // Caminho correto para conexão

// Carrega produtos do banco de dados
$result = $conn->query("SELECT id, nome, preco, descricao FROM produtos");
$produtos = $result->fetch_all(MYSQLI_ASSOC);
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
        <a href="../index.php">Voltar ao menu principal</a>
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
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td><?= htmlspecialchars($produto['nome']) ?></td>
                        <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($produto['descricao']) ?></td>
                        <td>
                            <a href="editar.php?id=<?= $produto['id'] ?>">Editar</a> |
                            <a href="excluir.php?id=<?= $produto['id'] ?>" 
                               onclick="return confirm('Tem certeza que deseja excluir este produto?');">
                               Excluir
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>
</body>
</html>
