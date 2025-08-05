<?php
session_start();
require_once 'conexao.php'; // Inclui conexão para buscar preço dos produtos admin

// Tabela de preços base
$precos = [
    "Combo Solteiro" => 18.00,
    "Combo Larica" => 22.00,
    "Combo Casal" => 25.00,
    "Combo Morangada" => 28.00,
    "Suco de Laranja" => 8.00,
    "Suco de Maracujá" => 8.00,
    "Suco de Maçã" => 8.00,
    "Suco Detox" => 9.50,
    "Suco de Morango" => 10.00,
    "Água com Gás" => 4.00,
    "Água sem Gás" => 3.50,
    "Adicional - Nutella" => 6.00,
    "Adicional - Paçoca" => 0.00,
    "Adicional - Creme de Ninho" => 6.00,
    "Adicional - Creme de Morango" => 6.00,
    "Adicional - Abacaxi" => 0.00,
    "Adicional - Morango" => 6.00,
    "Adicional - Kiwi" => 6.00,
    "Cobertura - Banana" => 0.00,
    "Cobertura - Granulado" => 0.00,
    "Cobertura - Granola" => 0.00,
    "Cobertura - Leite em Pó" => 0.00,
    "Cobertura - Leite Condensado" => 0.00,
];

if (!isset($_SESSION["carrinho"])) {
    $_SESSION["carrinho"] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produtos = (array) ($_POST['produto'] ?? []);
    foreach ($produtos as $produto) {
        $produto = htmlspecialchars($produto);

        if ($produto === "Monte Seu Açaí") {
            $id = uniqid();
            $tamanho = $_POST['tamanho'] ?? "700";
            $adicionais = $_POST['adicional'] ?? [];
            $complementos = $_POST['complementos'] ?? [];

            $precosTamanho = [
                "300" => 18.00,
                "500" => 22.00,
                "700" => 25.00,
                "1000" => 28.00,
            ];

            $precoBase = $precosTamanho[$tamanho] ?? 25.00;
            $precoFinal = $precoBase + count($adicionais) * 6.00;

            $_SESSION["carrinho"][$id] = [
                "id" => $id,
                "nome" => "Monte Seu Açaí ({$tamanho}ml)",
                "quantidade" => 1,
                "preco" => $precoFinal,
                "adicionais" => $adicionais,
                "complementos" => $complementos
            ];
        } elseif (isset($precos[$produto])) {
            // Produto está no array de preços base
            $existe = false;
            foreach ($_SESSION["carrinho"] as $key => $item) {
                if (isset($item["nome"]) && $item["nome"] === $produto && empty($item["adicionais"]) && empty($item["complementos"])) {
                    $_SESSION["carrinho"][$key]["quantidade"] += 1;
                    $existe = true;
                    break;
                }
            }
            if (!$existe) {
                $id = uniqid();
                $_SESSION["carrinho"][$id] = [
                    "id" => $id,
                    "nome" => $produto,
                    "quantidade" => 1,
                    "preco" => $precos[$produto],
                    "adicionais" => [],
                    "complementos" => []
                ];
            }
        } else {
            // Produto não está no array de preços base, pode ser produto admin - buscar no banco
            $stmt = $conn->prepare("SELECT preco FROM produtos WHERE nome = ?");
            $stmt->bind_param("s", $produto);
            $stmt->execute();
            $stmt->bind_result($precoProduto);
            if ($stmt->fetch()) {
                // Produto encontrado no banco
                $existe = false;
                foreach ($_SESSION["carrinho"] as $key => $item) {
                    if (isset($item["nome"]) && $item["nome"] === $produto) {
                        $_SESSION["carrinho"][$key]["quantidade"] += 1;
                        $existe = true;
                        break;
                    }
                }
                if (!$existe) {
                    $id = uniqid();
                    $_SESSION["carrinho"][$id] = [
                        "id" => $id,
                        "nome" => $produto,
                        "quantidade" => 1,
                        "preco" => $precoProduto,
                        "adicionais" => [],
                        "complementos" => []
                    ];
                }
            }
            $stmt->close();
        }
    }
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Minuto & Sabor - Cardápio Digital</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header style="position: relative;">
    <img src="img/logo.png" alt="Logo Minuto & Sabor" class="logo">
    <h1>Minuto & Sabor</h1>
    <nav>
        <ul>
            <li><a href="#combos">Combos Prontos</a></li>
            <li><a href="#monte">Monte o Seu</a></li>
            <li><a href="#bebidas">Bebidas Geladas</a></li>
            <li><a href="carrinho.php">🛒 Carrinho (<?= isset($_SESSION['carrinho']) ? array_sum(array_column($_SESSION['carrinho'], 'quantidade')) : 0 ?>)</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="cadastro.php">Cadastro</a></li>
        </ul>
    </nav>

    <a href="admin/login.php" 
       style="
          position: absolute;
          top: 10px;
          right: 10px;
          background-color: #5D3A6A;
          color: white;
          padding: 6px 12px;
          border-radius: 5px;
          text-decoration: none;
          font-size: 0.85rem;
          font-weight: bold;
          box-shadow: 0 2px 6px rgba(0,0,0,0.2);
          transition: background-color 0.3s ease;
       "
       onmouseover="this.style.backgroundColor='#7a5598';"
       onmouseout="this.style.backgroundColor='#5D3A6A';"
    >Admin</a>
</header>


<main>
    <section id="combos" class="categoria">
        <h2>Combos Prontos</h2>
        <div class="itens-wrapper">
        <?php
        $combos = [
            "Combo Solteiro" => "Açaí 300ml com granola, banana e leite condensado",
            "Combo Larica" => "Açaí 500ml com morango e leite ninho",
            "Combo Casal" => "Açaí 700ml com cobertura mista e nutella",
            "Combo Morangada" => "Açaí 1L com creme de morango e morango"
        ];
        foreach ($combos as $nome => $desc): ?>
            <div class="item">
                <img src="img/acai.png" alt="<?= htmlspecialchars($nome) ?>">
                <h3><?= htmlspecialchars($nome) ?></h3>
                <p><?= htmlspecialchars($desc) ?></p>
                <span>R$ <?= number_format($precos[$nome], 2, ',', '.') ?></span>
                <form method="post" action="adicionar_carrinho.php">
    <input type="hidden" name="nome" value="<?= htmlspecialchars($nome) ?>">
    <input type="hidden" name="preco" value="<?= htmlspecialchars($precos[$nome]) ?>">
    <button type="submit">Adicionar ao Carrinho</button>
</form>


            </div>
        <?php endforeach; ?>
        </div>
    </section>

    <section id="monte" class="categoria">
        <h2>Monte o Seu Açaí</h2>
        <form class="monte-form" method="post">
            <label for="tamanho">Tamanho:</label>
            <select name="tamanho" required>
                <option value="300">300ml - R$ 18,00</option>
                <option value="500">500ml - R$ 22,00</option>
                <option value="700" selected>700ml - R$ 25,00</option>
                <option value="1000">1L - R$ 28,00</option>
            </select>

            <p>Adicionais Gratuitos:</p>
            <?php
            $gratuitos = ["Banana", "Granulado", "Granola", "Leite em Pó", "Abacaxi", "Paçoca", "Leite Condensado"];
            foreach ($gratuitos as $g): ?>
                <label><input type="checkbox" name="complementos[]" value="<?= $g ?>"> <?= $g ?></label>
            <?php endforeach; ?>

            <p>Adicionais (R$ 6,00 cada):</p>
            <?php
            $adicionais = ["Nutella", "Creme de Ninho", "Creme de Morango", "Morango", "Kiwi"];
            foreach ($adicionais as $a): ?>
                <label><input type="checkbox" name="adicional[]" value="<?= $a ?>"> <?= $a ?></label>
            <?php endforeach; ?>

            <input type="hidden" name="produto[]" value="Monte Seu Açaí">
            <button type="submit">Adicionar ao Carrinho</button>
        </form>
    </section>

    <section id="bebidas" class="categoria">
        <h2>Bebidas Geladas</h2>
        <div class="itens-wrapper">
            <div class="item">
                <img src="img/suco.png" alt="Sucos Naturais">
                <h3>Sucos Naturais</h3>
                <form method="post" class="monte-form">
                    <?php
                    $sucos = ["Suco de Laranja", "Suco de Maracujá", "Suco de Maçã", "Suco Detox", "Suco de Morango"];
                    foreach ($sucos as $suco): ?>
                        <label>
                            <input type="checkbox" name="produto[]" value="<?= $suco ?>">
                            <?= $suco ?> (R$ <?= number_format($precos[$suco], 2, ',', '.') ?>)
                        </label><br>
                    <?php endforeach; ?>
                    <button type="submit">Adicionar ao Carrinho</button>
                </form>
            </div>

            <div class="item">
                <img src="img/agua.png" alt="Águas">
                <h3>Águas Naturais</h3>
                <form method="post" class="monte-form">
                    <label>
                        <input type="checkbox" name="produto[]" value="Água com Gás">
                        Água com Gás (R$ <?= number_format($precos["Água com Gás"], 2, ',', '.') ?>)
                    </label><br>
                    <label>
                        <input type="checkbox" name="produto[]" value="Água sem Gás">
                        Água sem Gás (R$ <?= number_format($precos["Água sem Gás"], 2, ',', '.') ?>)
                    </label><br>
                    <button type="submit">Adicionar ao Carrinho</button>
                </form>
            </div>
        </div>
    </section>

    <section id="produtos-admin" class="categoria">
        <h2>Produtos Adicionados pela Loja</h2>
        <div class="itens-wrapper">
            <?php
            require_once 'conexao.php';
            $query = "SELECT id, nome, preco, descricao, imagem FROM produtos";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0):
                while ($produto = $result->fetch_assoc()):
            ?>
                    <div class="item">
                        <?php if (!empty($produto['imagem'])): ?>
                            <img src="imagens/<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                        <p><?= htmlspecialchars($produto['descricao']) ?></p>
                        <span>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></span>
                        <form method="post">
                            <input type="hidden" name="produto[]" value="<?= htmlspecialchars($produto['nome']) ?>">
                            <button type="submit">Adicionar ao Carrinho</button>
                        </form>
                    </div>
            <?php
                endwhile;
            else:
                echo "<p>Nenhum produto cadastrado pela loja ainda.</p>";
            endif;
            ?>
        </div>
    </section>




</main>

<footer>
    <p>&copy; 2025 Minuto & Sabor - Todos os direitos reservados.</p>
</footer>
</body>
</html>