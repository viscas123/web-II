<?php
session_start();
// Simula cadastro e já loga o usuário
$_SESSION['usuario'] = $_POST['nome'];
header("Location: finalizar.php");
exit();