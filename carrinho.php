<?php
session_start();

// --- Tabelas de preços ---
$precosBase = [
    "Monte Seu Açaí (300ml)" => 18.00,
    "Monte Seu Açaí (500ml)" => 22.00,
    "Monte Seu Açaí (700ml)" => 25.00,
    "Monte Seu Açaí (1L)"    => 28.00,
];

$precosAdicional = [
    "Nutella"        => 6.00,
    "Creme de Ninho" => 6.00,
    "Creme de Morango" => 6.00,
    "Morango"        => 6.00,
    "Kiwi"           => 6.00,
];

$precosComplementoGratuito = [
    "Banana", "Granulado", "Granola", "Leite em Pó", "Abacaxi", "Paçoca", "Leite Condensado"
];

// --- Inicializar carrinho ---
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// --- Processa edição de um item ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_id'])) {
    $id = $_POST['editar_id'];
    if (isset($_SESSION['carrinho'][$id])) {
        $item = &$_SESSION['carrinho'][$id];

        $item['adicionais']   = $_POST['adicional'] ?? [];
        $item['complementos'] = $_POST['complementos'] ?? [];

        $precoBase = $precosBase[$item['nome']] ?? 25.00;
        $precoAdicionais = 0;

        foreach ($item['adicionais'] as $adicional) {
            if (isset($precosAdicional[$adicional])) {
                $precoAdicionais += $precosAdicional[$adicional];
            }
        }

        $item['preco'] = $precoBase + $precoAdicionais;
    }

    header('Location: carrinho.php');
    exit;
}

// --- Ações: atualizar quantidades, excluir item, cancelar compra ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['quantidades'])) {
        foreach ($_POST['quantidades'] as $id => $qtd) {
            $qtd = intval($qtd);
            if ($qtd <= 0) {
                unset($_SESSION['carrinho'][$id]);
            } else {
                $_SESSION['carrinho'][$id]['quantidade'] = $qtd;
            }
        }
    }

    if (isset($_POST['excluir'])) {
        $id = $_POST['excluir'];
        unset($_SESSION['carrinho'][$id]);
    }

    if (isset($_POST['cancelar'])) {
        $_SESSION['carrinho'] = [];
    }

    header('Location: carrinho.php');
    exit;
}

// --- Variáveis da página ---
$editarId = $_GET['editar'] ?? null;
$total = 0;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Seu Carrinho - Minuto & Sabor</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .form-editar { background: #f7f7f7; padding: 15px; margin-top: 15px; border-radius: 8px; }
        .checkbox-group label { display: inline-block; margin-right: 10px; }
        button { cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
    </style>
</head>
<body>
<header>
    <img src="img/logo.png" alt="Logo Minuto & Sabor" class="logo">
    <h1>Seu Carrinho</h1>
    <nav><ul><li><a href="index.php">Voltar ao Cardápio</a></li></ul></nav>
</header>

<main>
<?php if (!empty($_SESSION['carrinho'])): ?>

    <?php if ($editarId && isset($_SESSION['carrinho'][$editarId])):
        $itemEditar = $_SESSION['carrinho'][$editarId];
    ?>
        <h2>Editar Pedido: <?= htmlspecialchars($itemEditar['nome']) ?></h2>
        <form method="post" class="form-editar" action="carrinho.php">
            <input type="hidden" name="editar_id" value="<?= htmlspecialchars($editarId) ?>">

            <div>
                <strong>Adicionais (R$ 6,00 cada):</strong><br>
                <div class="checkbox-group">
                    <?php foreach (array_keys($precosAdicional) as $adicional): ?>
                        <label>
                            <input type="checkbox" name="adicional[]" value="<?= htmlspecialchars($adicional) ?>"
                            <?= in_array($adicional, $itemEditar['adicionais']) ? 'checked' : '' ?>>
                            <?= $adicional ?> (R$ <?= number_format($precosAdicional[$adicional], 2, ',', '.') ?>)
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div style="margin-top:10px;">
                <strong>Complementos Gratuitos:</strong><br>
                <div class="checkbox-group">
                    <?php foreach ($precosComplementoGratuito as $comp): ?>
                        <label>
                            <input type="checkbox" name="complementos[]" value="<?= htmlspecialchars($comp) ?>"
                            <?= in_array($comp, $itemEditar['complementos']) ? 'checked' : '' ?>>
                            <?= htmlspecialchars($comp) ?> (R$ 0,00)
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div style="margin-top: 15px;">
                <button type="submit" style="background:#5D3A6A; color:white; padding:10px 15px; border:none; border-radius:5px;">Salvar Alterações</button>
                <a href="carrinho.php" style="margin-left:15px; text-decoration:none; color:#c85c5a; font-weight:bold;">Cancelar</a>
            </div>
        </form>

    <?php else: ?>
        <form method="post" action="carrinho.php">
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Adicionais / Complementos</th>
                        <th>Preço Unitário</th>
                        <th>Qtd</th>
                        <th>Subtotal</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($_SESSION['carrinho'] as $id => $item):
                    // Verifica se é "Monte Seu Açaí" para calcular preço base + adicionais
                    if (strpos($item['nome'], 'Monte Seu Açaí') === 0) {
                        $precoBase = $precosBase[$item['nome']] ?? 25.00;
                        $precoAdicionais = 0;
                        foreach ($item['adicionais'] ?? [] as $adicional) {
                            if (isset($precosAdicional[$adicional])) {
                                $precoAdicionais += $precosAdicional[$adicional];
                            }
                        }
                        $precoUnitario = $precoBase + $precoAdicionais;
                    } else {
                        // Produto do admin: usa preço salvo
                        $precoUnitario = $item['preco'];
                    }

                    $subtotal = $precoUnitario * $item['quantidade'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nome']) ?></td>
                        <td>
                            <?php if (!empty($item['adicionais'])): ?>
                                <strong>Adicionais:</strong>
                                <ul>
                                <?php foreach ($item['adicionais'] as $a): ?>
                                    <li><?= htmlspecialchars($a) ?> (R$ <?= number_format($precosAdicional[$a] ?? 0, 2, ',', '.') ?>)</li>
                                <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <?php if (!empty($item['complementos'])): ?>
                                <strong>Complementos:</strong>
                                <ul>
                                <?php foreach ($item['complementos'] as $c): ?>
                                    <li><?= htmlspecialchars($c) ?> (R$ 0,00)</li>
                                <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </td>
                        <td>R$ <?= number_format($precoUnitario, 2, ',', '.') ?></td>
                        <td><input type="number" name="quantidades[<?= $id ?>]" value="<?= $item['quantidade'] ?>" min="1" style="width:60px;"></td>
                        <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                        <td>
                            <button type="submit" name="excluir" value="<?= $id ?>" style="background:#c85c5a;">Excluir</button>
                            <?php if (strpos($item['nome'], 'Monte Seu Açaí') === 0): ?>
                                <a href="carrinho.php?editar=<?= $id ?>" style="background:#5D3A6A; color:#fff; padding:5px 10px; text-decoration:none; border-radius:5px;">Editar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <p><strong>Total: R$ <?= number_format($total, 2, ',', '.') ?></strong></p>

            <button type="submit" name="atualizar" style="margin-top:10px;">Atualizar Quantidades</button>
        </form>

        <form method="post" action="carrinho.php" style="margin-top: 15px;">
            <button type="submit" name="cancelar" style="background:#c85c5a;">Cancelar Compra</button>
            <a href="cadastro.php" style="display:inline-block; margin-left:10px; padding:10px 20px; background-color:#5D3A6A; color:white; border-radius:8px; text-decoration:none; font-weight:bold; transition: background-color 0.3s ease;">Finalizar Compra</a>
        </form>
    <?php endif; ?>

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
