<?php

use PHPUnit\Framework\TestCase;
use App\Controller\ControllerListar;
use App\Banco;

class ControllerListarTest extends TestCase
{
    private $controllerListar;
    private $banco;

    protected function setUp(): void
    {
        $this->banco = new Banco();
        $this->controllerListar = new ControllerListar();
    }

    public function testCriacaoDaClasse()
    {
        $this->assertInstanceOf(ControllerListar::class, $this->controllerListar);
    }

    public function testGetTarefas()
    {
        $tarefas = $this->controllerListar->getTarefas();
        $this->assertIsArray($tarefas, "As tarefas devem ser retornadas como um array");
        $this->assertNotEmpty($tarefas, "As tarefas não devem estar vazias");
    }

    public function testAdicionarTarefa()
    {
        $dados = [
            'descricao' => 'Tarefa de teste 5',
            'data_criacao' => '2025-05-04',
            'data_prevista' => '2025-05-10',
            'data_encerramento' => null,
            'situacao' => 'pendente'
        ];

        $id = $this->controllerListar->adicionarTarefa($dados);
        $this->assertIsNumeric($id, "ID inserido deve ser numérico");

        // Limpa a tarefa após o teste
        $this->banco->deletarTarefa($id);
    }

    public function testAtualizarTarefa()
    {
        // Cria uma tarefa de teste
        $dados = [
            'descricao' => 'Tarefa de teste 3',
            'data_criacao' => '2025-05-04',
            'data_prevista' => '2025-05-10',
            'data_encerramento' => null,
            'situacao' => 'pendente'
        ];
        
        $id = $this->banco->inserirTarefa($dados);

        // Atualiza a tarefa
        $novosDados = [
            'descricao' => 'Tarefa atualizada 2',
            'data_criacao' => '2025-05-04',
            'data_prevista' => '2025-05-10',
            'data_encerramento' => null,
            'situacao' => 'concluída'
        ];

        $resultado = $this->controllerListar->atualizarTarefa($id, $novosDados);
        $this->assertTrue($resultado, "A tarefa deve ser atualizada com sucesso");

        $this->banco->deletarTarefa($id);
    }

}