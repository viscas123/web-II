<?php
session_start();
require_once("conexao.php");

// Verifica se o admin está logado (ajuste isso conforme seu sistema)
if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.php");
    exit;
}

// Tabela selecionada
$tabelaSelecionada = $_GET['tabela'] ?? 'cliente'; // cliente por padrão

// Buscar dados da tabela
$dados = [];
$colunas = [];

if (!empty($tabelaSelecionada)) {
    $query = "SELECT * FROM `$tabelaSelecionada`";
    $resultado = $conn->query($query);

    if ($resultado && $resultado->num_rows > 0) {
        $colunas = array_keys($resultado->fetch_assoc());
        $resultado->data_seek(0); // Volta para o início do resultado
        while ($linha = $resultado->fetch_assoc()) {
            $dados[] = $linha;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="style.css"> <!-- Se quiser estilizar -->
</head>
<body>
    <h1>Painel Administrativo</h1>
    
    <form method="get" action="">
        <label for="tabela">Escolha uma tabela:</label>
        <select name="tabela" id="tabela" onchange="this.form.submit()">
            <option value="cliente" <?= $tabelaSelecionada == 'cliente' ? 'selected' : '' ?>>Cliente</option>
            <option value="pedido" <?= $tabelaSelecionada == 'pedido' ? 'selected' : '' ?>>Pedido</option>
            <option value="item_pedido" <?= $tabelaSelecionada == 'item_pedido' ? 'selected' : '' ?>>Item Pedido</option>
            <option value="detalhes_item_pedido" <?= $tabelaSelecionada == 'detalhes_item_pedido' ? 'selected' : '' ?>>Detalhes Item</option>
            <option value="produtos" <?= $tabelaSelecionada == 'produtos' ? 'selected' : '' ?>>Produtos</option>
            <option value="usuarios" <?= $tabelaSelecionada == 'usuarios' ? 'selected' : '' ?>>Usuários</option>
        </select>
    </form>

    <h2>Tabela: <?= htmlspecialchars($tabelaSelecionada) ?></h2>

    <?php if (empty($dados)): ?>
        <p>Nenhum dado encontrado na tabela.</p>
    <?php else: ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <?php foreach ($colunas as $coluna): ?>
                    <th><?= htmlspecialchars($coluna) ?></th>
                <?php endforeach; ?>
            </tr>
            <?php foreach ($dados as $linha): ?>
                <tr>
                    <?php foreach ($colunas as $coluna): ?>
                        <td><?= htmlspecialchars($linha[$coluna]) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <br>
    <a href="logout.php">Sair</a>
</body>
</html>
