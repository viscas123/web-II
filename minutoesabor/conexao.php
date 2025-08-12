<?php
// Configurações de conexão com o banco de dados
$host = "localhost";
$usuario = "root"; // Altere para o seu usuário do banco de dados
$senha = "";     // Altere para a sua senha do banco de dados
$banco = "minuto_sabor2";

// Cria conexão
$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}
?>