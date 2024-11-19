<?php
session_start();
include 'conexao/conexao.php'; // Conectar ao banco de dados

// Habilitar exibição de erros para facilitar o debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do formulário
    $usuario_id = $_POST['usuario_id'];
    $exercicio = $_POST['exercicio'];
    $series = $_POST['series'];
    $repeticoes = $_POST['repeticoes'];

    // Depuração para verificar o conteúdo do POST

    // Verifica se todos os campos estão preenchidos
    if (empty($usuario_id) || empty($exercicio) || empty($series) || empty($repeticoes)) {
        echo "Por favor, preencha todos os campos!";
    } else {
        try {
            // Inserir a ficha de treino no banco de dados
            $stmt = $conn->prepare("INSERT INTO ficha_treino (usuario_id, exercicio, series, repeticoes) 
                                    VALUES (:usuario_id, :exercicio, :series, :repeticoes)");
            
            // Associar os parâmetros
            $stmt->bindParam(':usuario_id', $usuario_id);
            $stmt->bindParam(':exercicio', $exercicio);
            $stmt->bindParam(':series', $series);
            $stmt->bindParam(':repeticoes', $repeticoes);
            
            // Executar o comando
            $stmt->execute();
            
            // Mensagem de sucesso
            echo "Ficha de treino cadastrada com sucesso!";
        } catch (PDOException $e) {
            // Em caso de erro, exibe a mensagem
            echo "Erro ao cadastrar a ficha: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Ficha de Exercícios</title>
    <link rel="stylesheet" href="css/style_cadastro_ficha.css">
</head>
<body>
    <h1>Cadastrar Ficha de Exercícios</h1>
    <form action="cadastro_ficha.php" method="POST">
        <label for="usuario">Escolha o Usuário:</label>
        <select name="usuario_id" id="usuario" required>
            <?php
            // Conectar ao banco de dados
            include 'php/conexao.php';

            // Buscar todos os usuários para exibir no select
            $stmt = $conn->prepare("SELECT id, email FROM usuarios");
            $stmt->execute();
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Exibir os usuários no dropdown
            foreach ($usuarios as $usuario) {
                echo "<option value='{$usuario['id']}'>{$usuario['email']}</option>";
            }
            ?>
        </select>
        
        <label for="exercicio">Exercício:</label>
        <input type="text" name="exercicio" id="exercicio" required>
        
        <label for="series">Séries:</label>
        <input type="number" name="series" id="series" required>
        
        <label for="repeticoes">Repetições:</label>
        <input type="number" name="repeticoes" id="repeticoes" required>

        <button type="submit">Cadastrar Ficha</button>
    </form>
</body>
</html>
