<?php
session_start();
include 'connection.php';

// Recuperar os aluguéis do usuário atual
$query = "SELECT * FROM bookings WHERE user_id = :user_id";
$statement = $pdo->prepare($query);
$statement->bindValue(':user_id', $_SESSION['user_id']);
$statement->execute();
$alugueis = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="comum.css">
    <title>Meus Aluguéis</title>
    <style>
    .btn-encerrar,
     .btn-ocorrencia {
    display: inline-block;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    text-decoration: none !important;
    }

    .btn-encerrar {
        background-color: #ff0000; /* Cor de fundo do botão Encerrar */
        color: #ffffff; /* Cor do texto do botão Encerrar */
    }

    .btn-ocorrencia {
        background-color: #0066ff; /* Cor de fundo do botão Abrir Ocorrência */
        color: #ffffff; /* Cor do texto do botão Abrir Ocorrência */
    }
    </style>
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
            <h1>Meus Aluguéis</h1>
            <?php if ($alugueis): ?>
                <table>
        <thead>
            <tr>
                <th>Sala</th>
                <th>Data de Aluguel</th>
                <th>Data de Saída</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alugueis as $aluguel): ?>
                <tr>
                    <td><?php echo $aluguel['space_id']; ?></td>
                    <td><?php echo $aluguel['date']; ?></td>
                    <td><?php echo $aluguel['data_saida']; ?></td>
                    <td>
                        <a class="btn-encerrar" href="encerrar_aluguel.php?id=<?php echo $aluguel['id']; ?>">Encerrar</a>
                        <a class="btn-ocorrencia" href="abrir_ocorrencia.php?id=<?php echo $aluguel['id']; ?>">Abrir Ocorrência</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
            <?php else: ?>
                <p>Você não possui aluguéis no momento.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
