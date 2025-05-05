<?php
require_once('../controller/ControllerLogin.php');
require_once('../model/Banco.php'); // também necessário
require_once('../controller/ControllerListar.php');
use App\Controller\ControllerLogin;
use App\Controller\ControllerListar;

$controllerLogin = new ControllerLogin();

// Verifica se o usuário solicitou logout
if (isset($_GET['logout'])) {
    $controllerLogin->logout();
    header('Location: login.php');
    exit;
}

if (!$controllerLogin->verificarLogin()) {
    header('Location: login.php');
    exit;
}



$controller = new ControllerListar();

// Verifica se o ID foi enviado para exclusão
if (isset($_GET['delete_id']) && !empty($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $controller->deletarTarefa($id);

    // Redireciona para evitar múltiplas exclusões ao recarregar a página
    header('Location: index.php');
    exit;
}

// Obtém os filtros enviados via GET
$filtroData = $_GET['data_criacao'] ?? '';
$filtroSituacao = $_GET['situacao'] ?? '';

// Filtra as tarefas com base nos critérios
$tarefas = array_filter($controller->getTarefas(), function ($tarefa) use ($filtroData, $filtroSituacao) {
    $dataValida = empty($filtroData) || $tarefa['data_criacao'] === $filtroData;
    $situacaoValida = empty($filtroSituacao) || $tarefa['situacao'] === $filtroSituacao;
    return $dataValida && $situacaoValida;
});
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Tarefas</title>
    <style>
        table { 
width: 100%; 
border-collapse: collapse; 
            margin-top: 20px;
}
        th, td { 
border: 1px solid black; 
padding: 8px; 
text-align: left; 
}
        th { 
background-color: #f2f2f2; 
}
.create-button {
            margin-bottom: 15px;
  padding: 10px 15px;
background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .create-button:hover {
            background-color: #45a049;
        }
.filter-form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>Lista de Tarefas</h2>

    <!-- Formulário de filtro -->
    <form method="GET" class="filter-form">
        <label for="data_criacao">Filtrar por Data de Criação:</label>
        <input type="date" name="data_criacao" id="data_criacao" value="<?php echo htmlspecialchars($filtroData); ?>">

        <label for="situacao">Filtrar por Situação:</label>
        <select name="situacao" id="situacao">
            <option value="">Todas</option>
            <option value="pendente" <?php echo $filtroSituacao === 'pendente' ? 'selected' : ''; ?>>Pendente</option>
            <option value="em andamento" <?php echo $filtroSituacao === 'em andamento' ? 'selected' : ''; ?>>Em Andamento</option>
            <option value="concluída" <?php echo $filtroSituacao === 'concluída' ? 'selected' : ''; ?>>Concluída</option>
        </select>

        <button type="submit">Filtrar</button>
        <a href="index.php">Limpar Filtros</a>
    </form>

    <!-- Botão para criar nova tarefa -->
    <a href="tarefa_editar.php" class="create-button">Criar Nova Tarefa</a>

    <!-- Botão para exportar para PDF -->
    <a href="exportar_pdf.php" class="create-button" style="background-color: #007BFF;">Exportar para PDF</a>

    <!-- Botão de logout -->
    <a href="index.php?logout=true" class="create-button" style="background-color: #FF0000;">Logout</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Data Criação</th>
            <th>Data Prevista</th>
            <th>Data Encerramento</th>
            <th>Situação</th>
            <th>Editar</th>
            <th>Deletar</th>
        </tr>
        <?php foreach ($tarefas as $tarefa): ?>
        <tr>
            <td><?php echo htmlspecialchars($tarefa['id']); ?></td>
            <td><?php echo htmlspecialchars($tarefa['descricao']); ?></td>
            <td><?php echo htmlspecialchars($tarefa['data_criacao']); ?></td>
            <td><?php echo htmlspecialchars($tarefa['data_prevista']); ?></td>
            <td><?php echo htmlspecialchars($tarefa['data_encerramento'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($tarefa['situacao']); ?></td>
            <td><a href="tarefa_editar.php?id=<?php echo htmlspecialchars($tarefa['id']); ?>">Editar</a></td>
            <td>
<a href="index.php?delete_id=<?php echo htmlspecialchars($tarefa['id']); ?>" 
onclick="return confirm('Tem certeza que deseja deletar esta tarefa?');">
Deletar
</a>
</td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
