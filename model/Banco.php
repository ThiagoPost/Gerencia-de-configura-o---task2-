<?php
namespace App;
use \PDO;
class Banco {
    public $pdo;

    public function __construct() {
        // Configurações do banco de dados
        $host = 'localhost'; // endereço do servidor
        $port = '5432'; // porta do servidor
        $dbname = 'postgres'; // nome do banco de dados
        $user = 'postgres'; // nome de usuário
        $password = 'postgres'; // senha

        try {
            // Tenta criar uma nova instância PDO
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
            $this->pdo = new PDO($dsn, $user, $password);

            // Configura o modo de erro do PDO para exceções
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Caso ocorra um erro, exibe a mensagem de erro
            die("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }

    public function getTarefa() {
        $stmt = $this->pdo->query("SELECT * FROM tarefa ORDER BY data_prevista ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTarefaPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM tarefa WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function inserirTarefa($dados) {
        $stmt = $this->pdo->prepare("INSERT INTO tarefa (descricao, data_criacao, data_prevista, data_encerramento, situacao) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([
            $dados['descricao'],
            $dados['data_criacao'],
            $dados['data_prevista'],
            $dados['data_encerramento'],
            $dados['situacao']
        ])) {
            return $this->pdo->lastInsertId();
        } else {
            return false;
        }
    }

    public function atualizarTarefa($id, $dados) {
        $stmt = $this->pdo->prepare("UPDATE tarefa SET descricao = ?, data_criacao = ?, data_prevista = ?, data_encerramento = ?, situacao = ? WHERE id = ?");
        return $stmt->execute([
            $dados['descricao'],
            $dados['data_criacao'],
            $dados['data_prevista'],
            $dados['data_encerramento'],
            $dados['situacao'],
            $id
        ]);
    }

    public function deletarTarefa($id) {
        $stmt = $this->pdo->prepare("DELETE FROM tarefa WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function buscarUsuarioPorEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function executarSQL($sql, $parametros = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($parametros);
            return $stmt;
        } catch (PDOException $e) {
            // Caso ocorra um erro, exibe a mensagem de erro
            die("Erro ao executar SQL: " . $e->getMessage());
        }
    }
}
?>
