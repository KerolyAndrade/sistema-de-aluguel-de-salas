<?php
session_start();
include 'connection.php'; // Nome do arquivo de conexão com o banco de dados

// Lógica para processar a redefinição de senha
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Verificar se as senhas coincidem
    if ($password !== $confirmPassword) {
        echo "As senhas não coincidem.";
        exit;
    }

    // Criptografar a nova senha
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Atualizar a senha no banco de dados
        $query = "UPDATE users SET password = ? WHERE email = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$hashedPassword, $email]);

        // Verificar se ocorreu algum erro ao executar a declaração
        if ($statement->errorCode() !== '00000') {
            $errorInfo = $statement->errorInfo();
            echo "Erro ao atualizar a senha: " . $errorInfo[2];
            exit;
        }

        // Redirecionar para a página de login após a atualização da senha
        header('Location: login.php');
        exit;

    } catch (PDOException $e) {
        echo "Erro ao atualizar a senha: " . $e->getMessage();
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Redefinir Senha</title>
    <link rel="stylesheet" type="text/css" href="./styles.css">
</head>
<body>
    <form action="reset_password.php" method="POST" class="reset-form">
        <h1>Redefinir Senha</h1>
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required class="form-input">
        <br>
        <label for="password">Nova Senha:</label>
        <input type="password" id="password" name="password" required class="form-input">
        <br>
        <label for="confirm-password">Confirme a Nova Senha:</label>
        <input type="password" id="confirm-password" name="confirm-password" required class="form-input">
        <br>
        <input type="submit" value="Redefinir Senha" class="form-button">
    </form>
</body>
</html>
