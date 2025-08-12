<?php
session_start();
require_once __DIR__ . '/../conexao.php';

// Verifica se está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: login.php');
    exit;
}

// Verifica se o ID foi passado
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Deleta o produto do banco
    $stmt = $conn->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Fecha a conexão
    $stmt->close();
    $conn->close();

    // Redireciona de volta para o painel
    header("Location: painel.php");
    exit;
} else {
    echo "ID inválido.";
}
?>
