<?php
session_start();
include 'connection.php'; // Nome do arquivo de conexão com o banco de dados

// Lógica para processar o formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Verificar se o usuário existe no banco de dados
        $query = "SELECT * FROM users WHERE email = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$email]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verificar a senha descriptografando e comparando com a senha armazenada no banco de dados
            $hashedPassword = $user['password']; // Senha criptografada armazenada no banco de dados
            if (password_verify($password, $hashedPassword)) {
                // Senha correta, definir as informações do usuário na sessão
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = $user['user_type'];

                // Redirecionar com base no tipo de usuário
                if ($user['user_type'] === 'admin') {
                    header('Location: index_admin.php'); // Página do admin da ONG
                    exit;
                } else {
                    header('Location: index_comum.php'); // Página do usuário comum
                    exit;
                }
            } else {
                // Senha incorreta
                $errorMessage = "Senha incorreta.";
            }
        } else {
            // Usuário não encontrado
            $errorMessage = "Usuário não encontrado.";
        }
    } catch (PDOException $e) {
        echo "Erro ao realizar o login: " . $e->getMessage();
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

    <form action="login.php" method="POST" class="login-form">
        <h1>Login</h1>
        <?php if (isset($errorMessage)) { ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php } ?>
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required class="form-input">
        <br>
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required class="form-input">
        <br>
        <input type="submit" value="Entrar" class="form-button align-right">
        <a href="reset_password.php" class="forgot-password-link">Esqueci minha senha</a>
        <p class="register-message">Ainda não tenho um cadastro</p>
        <a href="register.php" class="register-link">Cadastre-se aqui</a>

    </form>
</body>
</html>
