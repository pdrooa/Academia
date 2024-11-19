<?php
$host = '127.0.0.1:3306';
$dbname = 'docs_gym';
$username = 'root';
$password = 'aluno';

// Criação da conexão com PDO
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar: " . $e->getMessage());
}
?>
