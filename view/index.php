<?php
require ('../controller/ControllerListar.php');

$controller = new ControllerListar();
$tarefas = $controller->getTarefas();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Tarefas</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Lista de Tarefas</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Data Criação</th>
            <th>Data Prevista</th>
            <th>Data Encerramento</th>
            <th>Situação</th>
        </tr>
        <?php foreach ($tarefas as $tarefa): ?>
        <tr>
            <td><?php echo htmlspecialchars($tarefa['id']); ?></td>
            <td><?php echo htmlspecialchars($tarefa['descricao']); ?></td>
            <td><?php echo htmlspecialchars($tarefa['data_criacao']); ?></td>
            <td><?php echo htmlspecialchars($tarefa['data_prevista']); ?></td>
            <td><?php echo htmlspecialchars($tarefa['data_encerramento'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($tarefa['situacao']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
