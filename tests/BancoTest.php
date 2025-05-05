<?php

use PHPUnit\Framework\TestCase;
use App\Banco;

class BancoTest extends TestCase
{
    private $id;
    private $banco;

    protected function setUp(): void
    {
        $this->banco = new Banco();

        // Cria uma tarefa de teste antes de cada teste
        $dados = [
            'descricao' => 'Tarefa de teste banco',
            'data_criacao' => '2025-05-04',
            'data_prevista' => '2025-05-10',
            'data_encerramento' => null,
            'situacao' => 'pendente'
        ];

        $this->id = $this->banco->inserirTarefa($dados);
    }

    public function testCriacaoDaClasse()
    {
        $banco = new Banco();
        $this->assertInstanceOf(Banco::class, $banco);
        $this->banco->deletarTarefa($this->id);
    }

    public function testInserirTarefa()
    {   
        $this->assertIsNumeric($this->id, "ID inserido deve ser numérico");
        $this->banco->deletarTarefa($this->id); // Limpa a tarefa após o teste
        
    }

    public function testGetTarefaPorId()
    {
        $tarefa = $this->banco->getTarefaPorId($this->id);
        $this->assertNotEmpty($tarefa, "A tarefa deve existir no banco de dados");
        $this->assertEquals($this->id, $tarefa['id'], "O ID da tarefa deve corresponder ao ID inserido");
        $this->banco->deletarTarefa($this->id);
    }

    public function testAtualizarTarefa()
    {
        $dados = [
            'descricao' => 'Tarefa atualizada',
            'data_criacao' => '2025-05-04',
            'data_prevista' => '2025-05-10',
            'data_encerramento' => null,
            'situacao' => 'concluída'
        ];

        $resultado = $this->banco->atualizarTarefa($this->id, $dados);
        $this->assertTrue($resultado, "A tarefa deve ser atualizada com sucesso");

        // Verifica se a tarefa foi realmente atualizada
        $tarefaAtualizada = $this->banco->getTarefaPorId($this->id);
        $this->assertEquals('Tarefa atualizada', $tarefaAtualizada['descricao'], "A descrição da tarefa deve ser atualizada");
        $this->banco->deletarTarefa($this->id);
    }

    public function testDeletarTarefa()
    {
        $resultado = $this->banco->deletarTarefa($this->id);
        $this->assertTrue($resultado, "A tarefa deve ser deletada com sucesso");

        // Verifica se a tarefa foi realmente deletada
        $tarefaDeletada = $this->banco->getTarefaPorId($this->id);
        $this->assertEmpty($tarefaDeletada, "A tarefa não deve existir no banco de dados após a deleção");

        
    }

    public function testBuscarUsuarioPorEmail()
    {
        $this->banco->deletarTarefa($this->id);
        // Insere um usuário de teste diretamente no banco
        $email = 'teste@exemplo.com';
        $senha = password_hash('senha123', PASSWORD_DEFAULT);
        $nome = 'Usuário Teste';

        $this->banco->executarSQL(
            "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)",
            [$nome, $email, $senha]
        );

        // Busca o usuário pelo e-mail
        $usuario = $this->banco->buscarUsuarioPorEmail($email);

        // Verifica se os dados retornados estão corretos
        $this->assertNotEmpty($usuario, "O usuário deve ser encontrado no banco de dados.");
        $this->assertEquals($email, $usuario['email'], "O e-mail do usuário deve corresponder.");
        $this->assertEquals($nome, $usuario['nome'], "O nome do usuário deve corresponder.");
        $this->assertTrue(password_verify('senha123', $usuario['senha']), "A senha deve ser válida.");

        // Exclui o usuário após o teste
        $this->banco->executarSQL("DELETE FROM usuarios WHERE email = ?", [$email]);
    }
}
