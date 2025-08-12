<?php
include 'conexao.php';

$tabela = isset($_GET['tabela']) ? $_GET['tabela'] : '';

if (empty($tabela)) {
    die("Tabela não especificada. Ex: listar_tabela.php?tabela=cliente");
}

$sql = "SELECT * FROM `$tabela`";
$result = $conn->query($sql);

if (!$result) {
    die("Erro ao consultar tabela '$tabela': " . $conn->error);
}

if ($result->num_rows === 0) {
    echo "Nenhum registro encontrado na tabela '$tabela'.";
    exit;
}

// Cabeçalho da tabela
echo "<h2>Tabela: $tabela</h2>";
echo "<table border='1' cellpadding='5' cellspacing='0'><tr>";
while ($field = $result->fetch_field()) {
    echo "<th>" . htmlspecialchars($field->name) . "</th>";
}
echo "</tr>";

// Dados da tabela
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    foreach ($row as $valor) {
        echo "<td>" . htmlspecialchars($valor) . "</td>";
    }
    echo "</tr>";
}

echo "</table>";
?>
