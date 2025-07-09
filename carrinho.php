<?php
session_start();

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Atualizar quantidades
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['quantidades'])) {
        foreach ($_POST['quantidades'] as $id => $qtd) {
            $qtd = intval($qtd);
            if ($qtd <= 0) {
                unset($_SESSION['carrinho'][$id]);
            } else {
                if (isset($_SESSION['carrinho'][$id])) {
                    $_SESSION['carrinho'][$id]['quantidade'] = $qtd;
                }
            }
        }
        header('Location: carrinho.php');
        exit;
    }
    if (isset($_POST['excluir'])) {
        $excluirId = $_POST['excluir'];
        unset($_SESSION['carrinho'][$excluirId]);
        header('Location: carrinho.php');
        exit;
    }
    if (isset($_POST['cancelar'])) {
        $_SESSION['carrinho'] = [];
        header('Location: carrinho.php');
        exit;
    }
}
$total = 0;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Carrinho de Compras</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
<header>
    <img src="img/logo.png" alt="Logo Minuto & Sabor" class="logo" />
    <h1>Seu Carrinho</h1>
    <nav>
        <ul>
            <li><a href="index.php">Voltar ao Cardápio</a></li>
        </ul>
    </nav>
</header>

<main>
<?php if (!empty($_SESSION['carrinho'])): ?>
    <form method="post" action="carrinho.php">
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Adicionais / Coberturas</th>
                    <th>Preço Unitário</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['carrinho'] as $id => $item):
                    $nome = $item['nome'] ?? 'Produto Desconhecido';
                    $preco = $item['preco'] ?? 0;
                    $quantidade = $item['quantidade'] ?? 1;
                    $adicionais = $item['adicionais'] ?? [];
                    $coberturas = $item['coberturas'] ?? [];
                    $subtotal = $preco * $quantidade;
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($nome) ?></td>
                    <td>
                        <?php
                            if ($adicionais) {
                                echo "<strong>Adicionais:</strong> " . implode(", ", array_map('htmlspecialchars', $adicionais)) . "<br>";
                            }
                            if ($coberturas) {
                                echo "<strong>Coberturas:</strong> " . implode(", ", array_map('htmlspecialchars', $coberturas));
                            }
                        ?>
                    </td>
                    <td>R$ <?= number_format($preco, 2, ',', '.') ?></td>
                    <td>
                        <input type="number" name="quantidades[<?= htmlspecialchars($id) ?>]" value="<?= $quantidade ?>" min="0" style="width: 60px;" />
                    </td>
                    <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                    <td>
                        <button type="submit" name="excluir" value="<?= htmlspecialchars($id) ?>" style="background:#c85c5a;">Excluir</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p><strong>Total: R$ <?= number_format($total, 2, ',', '.') ?></strong></p>

        <button type="submit" name="atualizar">Atualizar Quantidades</button>
    </form>

    <form method="post" action="carrinho.php" style="margin-top: 15px;">
        <button type="submit" name="cancelar" style="background:#c85c5a;">Cancelar Compra</button>
        <a href="finalizar.php" style="display: inline-block; margin-left: 15px; padding: 10px 20px; background:#5D3A6A; color:white; border-radius: 8px; text-decoration: none; font-weight: bold;">Finalizar Compra</a>
    </form>
<?php else: ?>
    <p>Seu carrinho está vazio.</p>
    <a href="index.php">Voltar ao Cardápio</a>
<?php endif; ?>
</main>
<footer>
    <p>&copy; 2025 Minuto & Sabor - Todos os direitos reservados.</p>
</footer>
</body>
</html>
