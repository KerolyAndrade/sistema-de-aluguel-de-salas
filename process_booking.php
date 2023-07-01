<?php
// Estabelecendo a conexão com o banco de dados
$host = 'localhost'; // Host do banco de dados
$db = 'projetodw'; // Nome do banco de dados
$user = 'root'; // Usuário do banco de dados
$password = ''; // Senha do banco de dados

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erro ao conectar com o banco de dados: ' . $e->getMessage());
}

// Lógica para processar o formulário de agendamento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $space = $_POST['space'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Realize aqui as validações necessárias

    // Salvando os dados do agendamento no banco de dados
    $query = "INSERT INTO bookings (user_id, space_id, date, time, status) VALUES (?, ?, ?, ?, 'agendado')";
    $statement = $pdo->prepare($query);
    $statement->execute([$user_id, $space, $date, $time]);

    // Realize aqui outras operações necessárias

    // Redirecionando o usuário para uma página de sucesso ou outra ação necessária
    header('Location: booking_success.php');
    exit();
}
?>
