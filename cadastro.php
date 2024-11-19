<?php
session_start();
include 'conexao/conexao.php'; // Incluindo o arquivo de conexão com o banco de dados

$mensagem = "";

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Coleta os dados do formulário
    $email = $_POST['email'];
    $confirm_email = $_POST['confirm'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $nome = $_POST['firstname']; // Corrigido o nome do campo
    $sobrenome = $_POST['lastname'];
    $data_nascimento = $_POST['date'];
    $genero = $_POST['gender'];
    $cpf = $_POST['cpf'];
    $receber_informativos = isset($_POST['informativos']) ? 1 : 0; // Receber informativos
    $termos_aceitos = isset($_POST['termos']) ? 1 : 0; // Aceitar termos

    // Aqui você pode definir o tipo de usuário de forma fixa
    $user_type = 'user';  // Definindo 'user' como valor fixo. Caso queira permitir escolher 'instructor', altere o valor de acordo.

    // Verifica se os e-mails e senhas coincidem
    if ($email !== $confirm_email) {
        $mensagem = "<p>Os e-mails não coincidem.</p>";
    } elseif ($password !== $confirm_password) {
        $mensagem = "<p>As senhas não coincidem.</p>";
    } else {
        // Criptografa a senha
        $senha_hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Verifica se o CPF ou e-mail já existe no banco
            $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = :email OR cpf = :cpf LIMIT 1");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                $mensagem = "<p>O e-mail ou CPF já está cadastrado.</p>";
            } else {
                // Inserir o novo usuário no banco, incluindo o campo 'user_type'
                $stmt = $conn->prepare("INSERT INTO usuarios (email, senha, nome, sobrenome, data_nascimento, genero, cpf, receber_informativos, termos_aceitos, user_type) 
                                        VALUES (:email, :senha, :nome, :sobrenome, :data_nascimento, :genero, :cpf, :receber_informativos, :termos_aceitos, :user_type)");

                // Vincula os parâmetros
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':senha', $senha_hash);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':sobrenome', $sobrenome);
                $stmt->bindParam(':data_nascimento', $data_nascimento);
                $stmt->bindParam(':genero', $genero);
                $stmt->bindParam(':cpf', $cpf);
                $stmt->bindParam(':receber_informativos', $receber_informativos, PDO::PARAM_BOOL);
                $stmt->bindParam(':termos_aceitos', $termos_aceitos, PDO::PARAM_BOOL);
                $stmt->bindParam(':user_type', $user_type);

                // Executa a inserção no banco de dados
                $stmt->execute();

                $mensagem = "<p>Cadastro realizado com sucesso!</p>";
                header("Location: index.php");
                exit();
            }
        } catch (PDOException $e) {
            $mensagem = "<p>Erro ao cadastrar: " . $e->getMessage() . "</p>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" type="text/css" href="css/style_logcad.css">
</head>
<body>
    <div class="form_reg">
        <form action="cadastro.php" method="POST"> <!-- Ação para enviar para o mesmo arquivo -->
            <div class="titulo">
                <h2>Informações para acesso ao site</h2>
                <div>
                    <a href="Login.html" class="botao">Logar</a>
                </div>
                <div id="subtitulo">
                    <p>Campos assinalados com <span>*</span> são de preenchimento obrigatório.</p><br>
                </div>
            </div>
            <?php if ($mensagem): ?>
                <div class="mensagem"><?php echo $mensagem; ?></div> <!-- Exibe mensagem de sucesso ou erro -->
            <?php endif; ?>
            <div class="input-group">
                <div class="input-box">
                    <label for="email">E-mail <span>*</span></label>
                    <input type="email" name="email" id="email" class="input" required>
                </div>

                <div class="input-box">
                    <label for="confirm">Confirmação de e-mail <span>*</span></label>
                    <input type="email" name="confirm" id="confirm" class="input" required>
                </div>

                <div class="input-box">
                    <label for="password">Senha <span>*</span></label>
                    <input type="password" name="password" id="password" class="input" required>
                </div>

                <div class="input-box">
                    <label>Confirmação de senha <span>*</span></label>
                    <input type="password" name="confirm_password" id="confirm_password" class="input" required>
                </div>
            </div>

            <div class="titulo">
                <h2>Informações pessoais</h2><br><br>
            </div>

            <div class="input-group">
                <div class="input-box">
                    <label for="firstname">Primeiro nome<span>*</span></label> <!-- Corrigido -->
                    <input type="text" name="firstname" id="firstname" class="input" required>
                </div>
                <div class="input-box">
                    <label for="lastname">Sobrenome <span>*</span></label>
                    <input type="text" name="lastname" id="lastname" class="input" required>
                </div>
                <div class="input-box">
                    <label for="date">Data de nascimento<span>*</span></label>
                    <input type="date" name="date" id="date" class="input" required>
                </div>
                <div class="input-box">
                    <label for="gender">Gênero <span>*</span></label>
                    <select name="gender" id="gender" required>
                        <option value="">Selecione</option>
                        <option value="m">Masculino</option>
                        <option value="f">Feminino</option>
                        <option value="n">Prefiro não informar</option>
                    </select>
                </div>
                <div class="input-box">
                    <label for="cpf">CPF <span>*</span></label>
                    <input type="text" name="cpf" id="cpf" class="input" required>
                </div>
            </div>
            <div class="input-checkbox">
                <div>
                    <input type="checkbox" name="informativos" id="informativos" checked> Desejo receber informativos por e-mail da Doc's Garage e de seus parceiros.
                </div>
                <div>
                    <input type="checkbox" name="termos" id="termos" required> Aceito os <strong>termos para criação da conta</strong> e estou de acordo com a <strong>Política de Privacidade</strong>.
                </div>
            </div>
            <br>
            <button type="submit" class="botao_log">Cadastrar</button>
        </form>
    </div>
</body>
</html>
