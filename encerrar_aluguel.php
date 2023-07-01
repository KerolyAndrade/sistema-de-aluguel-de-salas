<?php
session_start();
include 'connection.php';

// Verifica se foi recebido o ID do aluguel via GET
if (isset($_GET['id'])) {
    $aluguelId = $_GET['id'];
    try {
        // Utiliza a conexão com o banco de dados do arquivo connection.php
        $pdo = new PDO("mysql:host=$host;dbname=$bancoDeDados", $usuario, $senha);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Atualiza o status do aluguel para encerrado
        $sql = "UPDATE bookings SET status = 'Encerrado' WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $aluguelId, PDO::PARAM_INT);
        $stmt->execute();

        // Redireciona de volta para a página de Meus Aluguéis
        header('Location: meus_alugueis.php');
        exit();
    } catch (PDOException $e) {
        echo "Erro ao encerrar o aluguel: " . $e->getMessage();
    }
} else {
    echo "ID do aluguel não fornecido.";
}
?>

