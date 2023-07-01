<?php
session_start();
include 'connection.php';

// Recuperar as salas disponíveis do banco de dados
$query = "SELECT * FROM spaces WHERE disponibilidade = 0";
$statement = $pdo->prepare($query);
$statement->execute();
$salasDisponiveis = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $spaceId = $_POST['space_id'];

    // Verificar se o espaço está disponível
    $query = "SELECT disponibilidade FROM spaces WHERE id = :space_id";
    $statement = $pdo->prepare($query);
    $statement->bindValue(':space_id', $spaceId);
    $statement->execute();
    $disponibilidade = $statement->fetchColumn();

    if ($disponibilidade == 0) {
        $dataAluguel = $_POST['data_aluguel'];
        $dataSaida = $_POST['data_saida'];

        // Adicionar reserva na tabela bookings
        $insertQuery = "INSERT INTO bookings (user_id, space_id, date, data_saida, status) VALUES (:user_id, :space_id, :date, :data_saida, :status)";
        $insertStatement = $pdo->prepare($insertQuery);
        $insertStatement->bindValue(':user_id', $_SESSION['user_id']);
        $insertStatement->bindValue(':space_id', $spaceId);
        $insertStatement->bindValue(':date', $dataAluguel);
        $insertStatement->bindValue(':data_saida', $dataSaida);
        $insertStatement->bindValue(':status', 'Alugada');
        $insertStatement->execute();
        ;

        // Atualizar disponibilidade do espaço para 1 (não disponível)
        $updateQuery = "UPDATE spaces SET disponibilidade = 1 WHERE id = :space_id";
        $updateStatement = $pdo->prepare($updateQuery);
        $updateStatement->bindValue(':space_id', $spaceId);
        $updateStatement->execute();

        // Redirecionar para a página "meus_alugueis.php" após o sucesso do aluguel
        header('Location: meus_alugueis.php');
        exit();
    } else {
        $mensagem = array('status' => 'error', 'texto' => 'Sala não alugada, tente novamente!');
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="comum.css">
    <title>Alugar Salas</title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index_comum.php">Sobre a ONG</a></li>
                <li><a href="alugar_salas.php">Alugar Salas</a></li>
                <li><a href="meus_alugueis.php">Meus Aluguéis</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="content">
            <h1>Salas Disponíveis</h1>
            <?php if ($salasDisponiveis): ?>
                <div class="salas-disponiveis">
                    <?php foreach ($salasDisponiveis as $sala): ?>
                        <div class="sala">
                            <h3>Sala <?php echo $sala['id']; ?></h3>
                            <p>Descrição: <?php echo $sala['descricao']; ?></p>
                            <p>Preço: <?php echo $sala['valor']; ?></p>
                            <p>Nome da Sala: <?php echo $sala['name']; ?></p>

                            <form method="post">
                                <input type="hidden" name="space_id" value="<?php echo $sala['id']; ?>">
                                <label for="data_aluguel">Data de Aluguel:</label>
                                <input type="date" name="data_aluguel" id="data_aluguel" required>
                                <br>
                                <label for="data_saida">Data de Saída:</label>
                                <input type="date" name="data_saida" id="data_saida" required>

                                <input type="submit" value="Alugar">
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Não há salas disponíveis no momento.</p>
            <?php endif; ?>
            
            <?php if(isset($mensagem)): ?>
                <div class="mensagem <?php echo $mensagem['status']; ?>">
                    <?php echo $mensagem['texto']; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
