<?php
session_start();

$precos = [
    "Combo Açaí 1" => 18.00,
    "Combo Açaí 2" => 22.00,
    "Combo Açaí 3" => 25.00,
    "Combo Açaí 4" => 28.00,
    "Monte Seu Açaí" => 25.00,
    "Suco de Laranja" => 8.00,
    "Suco de Maracujá" => 8.00,
    "Suco de Maçã" => 8.00,
    "Suco Detox" => 9.50,
    "Suco de Morango" => 10.00,
    "Adicional - Nutella" => 2.00,
    "Adicional - Paçoca" => 2.00,
    "Adicional - Creme de Ninho" => 2.00,
    "Adicional - Creme de Morango" => 2.00,
    "Adicional - Abacaxi" => 2.00,
    "Adicional - Morango" => 2.00,
    "Adicional - Kiwi" => 2.00,
    "Cobertura - Banana" => 0.00,
    "Cobertura - Granulado" => 0.00,
    "Cobertura - Granola" => 0.00,
    "Cobertura - Leite em Pó" => 0.00,
    "Cobertura - Leite Condensado" => 0.00,
];

if (!isset($_SESSION["carrinho"])) {
    $_SESSION["carrinho"] = [];
}

// Adicionar produtos ao carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['produto'])) {
        $produto = htmlspecialchars($_POST['produto']);
        if ($produto === "Monte Seu Açaí") {
            // Monte Seu Açaí é um item complexo, armazenamos com detalhes
            $id = uniqid();
            $adicionais = $_POST['adicional'] ?? [];
            $coberturas = $_POST['cobertura'] ?? [];
            $_SESSION['carrinho'][$id] = [
                'nome' => $produto,
                'quantidade' => 1,
                'preco' => $precos[$produto] + count($adicionais)*2,
                'adicionais' => $adicionais,
                'coberturas' => $coberturas
            ];
        } elseif (isset($precos[$produto])) {
            // Produto simples (combo ou suco)
            // Se já existe, soma 1 na quantidade
            $existe = false;
            foreach ($_SESSION['carrinho'] as $key => $item) {
                if ($item['nome'] === $produto && empty($item['adicionais']) && empty($item['coberturas'])) {
                    $_SESSION['carrinho'][$key]['quantidade'] += 1;
                    $existe = true;
                    break;
                }
            }
            if (!$existe) {
                $id = uniqid();
                $_SESSION['carrinho'][$id] = [
                    'nome' => $produto,
                    'quantidade' => 1,
                    'preco' => $precos[$produto],
                    'adicionais' => [],
                    'coberturas' => []
                ];
            }
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Minuto & Sabor - Cardápio Digital</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
<header>
    <img src="img/logo.png" alt="Logo Minuto & Sabor" class="logo" />
    <h1>Minuto & Sabor</h1>
    <nav>
        <ul>
            <li><a href="#combos">Combos Prontos</a></li>
            <li><a href="#monte">Monte o Seu</a></li>
            <li><a href="#bebidas">Bebidas Geladas</a></li>
            <li><a href="carrinho.php">🛒 Carrinho (<?= array_sum(array_column($_SESSION['carrinho'], 'quantidade')) ?>)</a></li>
        </ul>
    </nav>
</header>

<main>
    <section id="combos" class="categoria">
        <h2>Combos Prontos</h2>
        <div class="itens-wrapper">
        <?php
        $combos = [
            "Combo Açaí 1" => "Açaí 500ml com granola, banana e leite condensado",
            "Combo Açaí 2" => "Açaí 700ml com morango, leite ninho e paçoca",
            "Combo Açaí 3" => "Açaí 1L com cobertura mista e nutella",
            "Combo Açaí 4" => "Açaí 1L com creme de morango e morango"
        ];
        foreach ($combos as $nome => $desc): ?>
            <div class="item">
                <img src="img/acai.png" alt="<?= htmlspecialchars($nome) ?>" />
                <h3><?= htmlspecialchars($nome) ?></h3>
                <p><?= htmlspecialchars($desc) ?></p>
                <span>R$ <?= number_format($precos[$nome], 2, ',', '.') ?></span>
                <form method="post">
                    <input type="hidden" name="produto" value="<?= htmlspecialchars($nome) ?>" />
                    <button type="submit">Adicionar ao Carrinho</button>
                </form>
            </div>
        <?php endforeach; ?>
        </div>
    </section>

    <section id="monte" class="categoria">
        <h2>Monte o Seu Açaí</h2>
        <p><strong>A partir de R$ <?= number_format($precos["Monte Seu Açaí"], 2, ',', '.') ?></strong></p>
        <form class="monte-form" method="post">
            <div class="form-group">
                <label for="base-acai">Base:</label>
                <select name="base" id="base-acai" disabled>
                    <option>Tradicional</option>
                    <option>Cremoso</option>
                    <option>Com Banana</option>
                </select>
                <small style="color:gray;">(Base não altera preço por enquanto)</small>
            </div>

            <div class="form-group">
                <label>Adicionais Gratuitos:</label>
                <div class="checkbox-group">
                    <?php
                    $gratuitos = ["Banana", "Granulado", "Granola", "Leite em Pó", "Leite Condensado"];
                    foreach ($gratuitos as $g): ?>
                        <label><input type="checkbox" name="cobertura[]" value="<?= $g ?>" /> <?= $g ?></label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-group">
                <label>Adicionais (R$ 2,00 cada):</label>
                <div class="checkbox-group">
                    <?php
                    $adicionais = ["Nutella", "Paçoca", "Creme de Ninho", "Creme de Morango", "Abacaxi", "Morango", "Kiwi"];
                    foreach ($adicionais as $a): ?>
                        <label><input type="checkbox" name="adicional[]" value="<?= $a ?>" /> <?= $a ?></label>
                    <?php endforeach; ?>
                </div>
            </div>

            <input type="hidden" name="produto" value="Monte Seu Açaí" />
            <button type="submit">Adicionar ao Carrinho</button>
        </form>
    </section>

    <section id="bebidas" class="categoria">
        <h2>Bebidas Geladas</h2>
        <div class="item">
            <img src="img/suco.png" alt="Sucos Naturais" />
            <h3>Sucos Naturais</h3>
            <p>Escolha seus sabores preferidos e adicione ao carrinho:</p>
            <form method="post" class="monte-form">
                <div class="checkbox-group">
                <?php
                $sucos = ["Suco de Laranja", "Suco de Maracujá", "Suco de Maçã", "Suco Detox", "Suco de Morango"];
                foreach ($sucos as $suco): ?>
                    <label><input type="checkbox" name="produto[]" value="<?= $suco ?>" /> <?= $suco ?> (R$ <?= number_format($precos[$suco], 2, ',', '.') ?>)</label>
                <?php endforeach; ?>
                </div>
                <button type="submit">Adicionar ao Carrinho</button>
            </form>
        </div>
    </section>
</main>

<footer>
    <p>&copy; 2025 Minuto & Sabor - Todos os direitos reservados.</p>
</footer>
</body>
</html>
