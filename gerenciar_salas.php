<?php
session_start();
include 'connection.php'; // Nome do arquivo de conexão com o banco de dados

// Verificar se o usuário está logado como admin
if ($_SESSION['user_type'] !== 'admin') {
    header('Location: index.php'); // Redirecionar para a página inicial, caso não seja admin
    exit;
}

// Excluir sala
if (isset($_POST['excluir'])) {
    $id = $_POST['sala_id'];

    try {
        // Excluir a sala do banco de dados
        $query = "DELETE FROM spaces WHERE id = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$id]);
    } catch (PDOException $e) {
        echo "Erro ao excluir sala: " . $e->getMessage();
    }
}

// Consultar salas do banco de dados
$query = "SELECT * FROM spaces";
$statement = $pdo->query($query);
$salas = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gerenciar Salas</title>
    <link rel="stylesheet" type="text/css" href="tabela.css">
</head>
<body class="container">
    <h1>Gerenciar Salas</h1>

    <a href="index_admin.php" class="btn-voltar">Voltar</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Tipo</th>
                <th>Valor</th>
                <th>Capacidade Máxima</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loop para exibir as salas -->
            <?php foreach ($salas as $sala) : ?>
            <tr>
                <td><?php echo $sala['id']; ?></td>
                <td><?php echo $sala['name']; ?></td>
                <td><?php echo $sala['descricao']; ?></td>
                <td><?php echo $sala['tipo']; ?></td>
                <td><?php echo $sala['valor']; ?></td>
                <td><?php echo $sala['capacidade_maxima']; ?></td>
                <td class="actions">
                    <form action="excluir_sala.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $sala['id']; ?>">
                        <button type="submit" title="Excluir"><span>&#128465;</span></button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <!-- Fim do loop -->
        </tbody>
    </table>

</body>
</html>
