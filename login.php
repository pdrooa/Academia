<?php
session_start();
include 'conexao/conexao.php'; // Incluindo o arquivo de conexão com o banco de dados

$mensagem = "";

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Coleta os dados do formulário
    $email = $_POST['email'];
    $senha = $_POST['password'];

    try {
        // Preparar consulta para buscar o usuário pelo email, incluindo o tipo de usuário
        $stmt = $conn->prepare("SELECT id, senha, user_type FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Verifica se a senha fornecida corresponde à senha armazenada
            if (password_verify($senha, $usuario['senha'])) {
                // Inicia a sessão
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_email'] = $email;
                $_SESSION['user_type'] = $usuario['user_type']; // Armazena o tipo de usuário na sessão

                // Redireciona com base no tipo de usuário
                if ($_SESSION['user_type'] === 'user') {
                    header("Location: index_user.php"); // Redireciona para o painel do usuário comum
                } elseif ($_SESSION['user_type'] === 'instrutor') {
                    header("Location: index_instrutor.php"); // Redireciona para o painel do instrutor
                }   
                exit();
            } else {
                $mensagem = "<p>Senha incorreta. Tente novamente.</p>";
            }
        } else {
            $mensagem = "<p>Usuário não encontrado.</p>";
        }

    } catch (PDOException $e) {
        $mensagem = "<p>Erro ao realizar login: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/style_logcad.css">
</head>
<body>
    <div class="f">
        <form class="form_log" action="login.php" method="post">
            <?php if ($mensagem): ?>
                <div class="mensagem"><?php echo $mensagem; ?></div> <!-- Exibe mensagem de erro -->
            <?php endif; ?>
            
            <div class="input-group">
                <div class="input-box">
                    <label for="email">E-mail <span>*</span></label>
                    <input type="email" name="email" id="email" class="input" placeholder="Digite seu E-mail" autocomplete="off" required>
                </div>

                <div class="input-box">
                    <label>Senha <span>*</span></label>
                    <input type="password" name="password" id="password" class="input" placeholder="Digite sua senha" autocomplete="off" required>
                </div>
            </div>

            <br><br>
            <button type="submit" class="botao_log">Entrar</button>

            <div class="register">
                <p>Não tem uma conta? <a href="cadastro.php">Registre-se</a></p>
            </div>
        </form> 
    </div>
</body>
</html>
