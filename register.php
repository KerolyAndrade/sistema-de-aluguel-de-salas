<?php
session_start();
include 'connection.php'; // Nome do arquivo de conexão com o banco de dados

// Lógica para processar o formulário de cadastro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $userType = $_POST['user-type']; // Novo campo: Tipo de Usuário

    // Verificar se as senhas coincidem
    if ($password !== $confirmPassword) {
        echo "As senhas não coincidem.";
        exit;
    }

    // Criptografar a senha
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Verificar se o email já está em uso
        $query = "SELECT COUNT(*) FROM users WHERE email = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$email]);
        $result = $statement->fetchColumn();

        if ($result > 0) {
            echo "O email já está em uso.";
            exit;
        }

        // Preparar a declaração SQL para inserir o novo usuário
        $query = "INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)";
        $statement = $pdo->prepare($query);

        // Executar a declaração com os valores fornecidos
        $statement->execute([$name, $email, $hashedPassword, $userType]);

        // Verificar se ocorreu algum erro ao executar a declaração
        if ($statement->errorCode() !== '00000') {
            $errorInfo = $statement->errorInfo();
            echo "Erro ao adicionar usuário: " . $errorInfo[2];
            exit;
        }

        header('Location: login.php'); // Redirecionar para a página de login
        exit;

    } catch (PDOException $e) {
        echo "Erro ao adicionar usuário: " . $e->getMessage();
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
    </style>
</head>
<body>

    <form action="register.php" method="POST" class="register-form">
        <h1>Cadastro</h1>
        <label for="name">Nome:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="confirm-password">Confirme a senha:</label>
        <input type="password" id="confirm-password" name="confirm-password" required>
        <br>
        <label for="user-type">Tipo de Usuário:</label>
        <select id="user-type" name="user-type">
            <option value="admin">Admin da ONG</option>
            <option value="comum">Usuário Comum</option>
        </select>
        <br>
        <a href="login.php" class="login-link">Já tem uma conta? Faça login</a>
        <input type="submit" value="Cadastrar">
    </form>
</body>
</html>
