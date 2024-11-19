<?php
session_start();
include 'conexao/conexao.php'; // Conectar ao banco de dados

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); // Redirecionar para a página de login se não estiver logado
    exit();
}

$usuario_id = $_SESSION['usuario_id']; // Usar o ID do usuário logado

try {
    // Buscar as fichas de treino do usuário logado
    $stmt = $conn->prepare("SELECT exercicio, series, repeticoes, data_cadastro FROM ficha_treino WHERE usuario_id = :usuario_id");
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->execute();
    $fichas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erro ao recuperar as fichas: " . $e->getMessage()); // Log de erro
    $error_message = "Ocorreu um erro ao tentar carregar suas fichas de treino.";
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Fichas de Treino</title>
    <link rel="stylesheet" href="css/style_ficha.css">
</head>
<body>
    <h1>Minhas Fichas de Treino</h1>

    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <!-- Exibir a lista de fichas -->
    <div class="fichas-lista">
        <?php if (count($fichas) > 0): ?>
            <ul>
                <?php foreach ($fichas as $ficha): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($ficha['exercicio']); ?></strong>: <br>
                        Séries: <?php echo htmlspecialchars($ficha['series']); ?><br>
                        Repetições: <?php echo htmlspecialchars($ficha['repeticoes']); ?> <br>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Você ainda não tem fichas de treino cadastradas.</p>
        <?php endif; ?>
    </div>

    <a href="index_user.php">Voltar ao Painel</a>
</body>
</html>
