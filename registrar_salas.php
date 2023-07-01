<?php
session_start();
include 'connection.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $tipo = $_POST['tipo'];
    $valor = $_POST['valor_hora'];
    $capacidade = $_POST['capacidade'];

    // Inserir sala na tabela spaces
    $insertQuery = "INSERT INTO spaces (name, descricao, tipo, valor, capacidade_maxima, disponibilidade) VALUES (:name, :descricao, :tipo, :valor, :capacidade, 0)";
    $insertStatement = $pdo->prepare($insertQuery);
    $insertStatement->bindValue(':name', $name);
    $insertStatement->bindValue(':descricao', $descricao);
    $insertStatement->bindValue(':tipo', $tipo);
    $insertStatement->bindValue(':valor', $valor);
    $insertStatement->bindValue(':capacidade', $capacidade);
    $insertStatement->execute();

    $mensagem = "Sala registrada com sucesso!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar Salas</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .mensagem {
            color: green;
            margin-top: 10px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50px;
        }
    </style>
</head>
<body>

    <div class="container">

        <?php if (!empty($mensagem)): ?>
            <p class="mensagem"><?php echo $mensagem; ?></p>
        <?php endif; ?>

        <form action="registrar_sala.php" method="POST" class="salas-form">
            <h1>Registrar Salas</h1>
            <label for="nome">Nome da Sala:</label>
            <input type="text" id="nome" name="nome" required class="form-input">
            <br>
            <label for="descricao">Descrição da Sala:</label>
            <input type="text" id="descricao" name="descricao" required class="form-input">
            <br>
            <label for="tipo">Tipo de Sala:</label>
            <select id="tipo" name="tipo" class="form-input">
                <option value="salao_festa">Salão de Festas</option>
                <option value="auditorio">Auditório</option>
            </select>
            <br>
            <label for="valor_hora">Valor Diário:</label>
            <input type="number" id="valor_hora" name="valor_hora" required class="form-input">
            <br>
            <label for="capacidade">Capacidade Máxima de Pessoas:</label>
            <input type="number" id="capacidade" name="capacidade" required class="form-input">
            <br>

            <input type="submit" value="Registrar Sala" class="form-button">
        </form>
    </div>

</body>
</html>
