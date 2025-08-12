<?php
session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
  header("Location: login.php");
  exit;
}

require_once 'conexao.php'; // Inclui a conexão com o banco de dados

$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = $_POST['nome'];
  $descricao = $_POST['descricao'];
  $preco = floatval($_POST['preco']);
  $categoria = $_POST['categoria'];

  if (!empty($_FILES['imagem']['name'])) {
    $imagem = $_FILES['imagem']['name'];
    move_uploaded_file($_FILES['imagem']['tmp_name'], "../imagens/" . $imagem);
    
    $stmt = $conn->prepare("UPDATE produtos SET nome=?, descricao=?, preco=?, imagem=?, categoria=? WHERE id=?");
    $stmt->bind_param("sdsdsi", $nome, $descricao, $preco, $imagem, $categoria, $id);
    $stmt->execute();
    $stmt->close();

  } else {
    $stmt = $conn->prepare("UPDATE produtos SET nome=?, descricao=?, preco=?, categoria=? WHERE id=?");
    $stmt->bind_param("sdsdi", $nome, $descricao, $preco, $categoria, $id);
    $stmt->execute();
    $stmt->close();
  }

  header("Location: painel.php");
  exit;
}

$stmt = $conn->prepare("SELECT * FROM produtos WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$produto = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<form method="post" enctype="multipart/form-data">
  <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required><br>
  <textarea name="descricao"><?= htmlspecialchars($produto['descricao']) ?></textarea><br>
  <input type="number" step="0.01" name="preco" value="<?= htmlspecialchars($produto['preco']) ?>" required><br>
  <input type="text" name="categoria" value="<?= htmlspecialchars($produto['categoria']) ?>"><br>
  <img src="../imagens/<?= htmlspecialchars($produto['imagem']) ?>" width="100"><br>
  <input type="file" name="imagem"><br>
  <button type="submit">Salvar Alterações</button>
</form>