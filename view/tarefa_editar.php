<?php
use App\Banco;
use App\Controller\ControllerListar;

require_once('../model/Banco.php');
require_once('../controller/ControllerListar.php');

$banco = new Banco();
$controller = new ControllerListar();
$tarefa = null;
$editando = false;

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'descricao' => $_POST['descricao'],
        'data_criacao' => $_POST['data_criacao'],
        'data_prevista' => $_POST['data_prevista'],
        'data_encerramento' => $_POST['data_encerramento'],
        'situacao' => $_POST['situacao']
    ];

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Atualizar tarefa existente
        $controller->atualizarTarefa($_POST['id'], $dados);
    } else {
        // Criar nova tarefa
        $controller->adicionarTarefa($dados);
    }

    // Redireciona para a lista de tarefas após salvar
    header('Location: index.php');
    exit;
}

// Verifica se está editando uma tarefa existente
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $tarefa = $banco->getTarefaPorId($id);
    $editando = true;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $editando ? "Editar Tarefa" : "Criar Tarefa" ?></title>
</head>
<body>
    <h1><?= $editando ? "Editar Tarefa" : "Criar Nova Tarefa" ?></h1>

    <form method="POST" action="tarefa_editar.php">
        <?php if ($editando): ?>
            <input type="hidden" name="id" value="<?= $tarefa['id'] ?>">
        <?php endif; ?>

        <label>Descrição:</label><br>
        <textarea name="descricao" required><?= $tarefa['descricao'] ?? '' ?></textarea><br><br>

        <label>Data de Criação:</label><br>
        <input type="date" name="data_criacao" value="<?= $tarefa['data_criacao'] ?? '' ?>" required><br><br>

        <label>Data Prevista:</label><br>
        <input type="date" name="data_prevista" value="<?= $tarefa['data_prevista'] ?? '' ?>"><br><br>

        <label>Data de Encerramento:</label><br>
        <input type="date" name="data_encerramento" value="<?= $tarefa['data_encerramento'] ?? '' ?>"><br><br>

        <label>Situação:</label><br>
        <select name="situacao" required>
            <option value="pendente" <?= (isset($tarefa['situacao']) && $tarefa['situacao'] === 'pendente') ? 'selected' : '' ?>>Pendente</option>
            <option value="em andamento" <?= (isset($tarefa['situacao']) && $tarefa['situacao'] === 'em andamento') ? 'selected' : '' ?>>Em Andamento</option>
            <option value="concluída" <?= (isset($tarefa['situacao']) && $tarefa['situacao'] === 'concluída') ? 'selected' : '' ?>>Concluída</option>
        </select><br><br>

        <button type="submit"><?= $editando ? "Atualizar" : "Criar" ?> Tarefa</button>
    </form>

    <br>
    <a href="index.php">Voltar à Lista</a>
</body>
</html>
