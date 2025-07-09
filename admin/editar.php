<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

$conn = new mysqli("localhost", "root", "", "minuto_sabor");
$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = $_POST['nome'];
  $descricao = $_POST['descricao'];
  $preco = floatval($_POST['preco']);
  $categoria = $_POST['categoria'];

  if (!empty($_FILES['imagem']['name'])) {
    $imagem = $_FILES['imagem']['name'];
    move_uploaded_file($_FILES['imagem']['tmp_name'], "../imagens/" . $imagem);
    $conn->query("UPDATE produtos SET nome='$nome', descricao='$descricao', preco=$preco, imagem='$imagem', categoria='$categoria' WHERE id=$id");
  } else {
    $conn->query("UPDATE produtos SET nome='$nome', descricao='$descricao', preco=$preco, categoria='$categoria' WHERE id=$id");
  }

  header("Location: painel.php");
  exit;
}

$produto = $conn->query("SELECT * FROM produtos WHERE id=$id")->fetch_assoc();
?>

<form method="post" enctype="multipart/form-data">
  <input type="text" name="nome" value="<?= $produto['nome'] ?>" required><br>
  <textarea name="descricao"><?= $produto['descricao'] ?></textarea><br>
  <input type="number" step="0.01" name="preco" value="<?= $produto['preco'] ?>" required><br>
  <input type="text" name="categoria" value="<?= $produto['categoria'] ?>"><br>
  <img src="../imagens/<?= $produto['imagem'] ?>" width="100"><br>
  <input type="file" name="imagem"><br>
  <button type="submit">Salvar Alterações</button>
</form>
