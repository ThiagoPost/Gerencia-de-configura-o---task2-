<?php
class Banco {
    private $pdo;

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
}
?>
