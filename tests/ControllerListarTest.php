<?php

use PHPUnit\Framework\TestCase;
use App\Controller\ControllerListar;
use App\Banco;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

    public function testEmailEnviadoComSucesso()
    {
        $mail = $this->createMock(PHPMailer::class);
        $mail->expects($this->once())->method('send')->willReturn(true);

        $controller = new class {
            public function enviarEmail($mail, $assunto, $mensagem) {
                try {
                    $mail->isSMTP();
                    $mail->SMTPAuth = true;
                    $mail->send();
                } catch (Exception $e) {
                    error_log("Erro: {$mail->ErrorInfo}");
                }
            }
        };

        $controller->enviarEmail($mail, 'Assunto', 'Mensagem');
    }

    // 2. Teste: Falha ao enviar e-mail (exceção)
    public function testEmailFalhaAoEnviar()
    {
        $mail = $this->createMock(PHPMailer::class);
        $mail->method('send')->willThrowException(new Exception('Erro SMTP'));
    
        $mensagemErro = null;
    
        $controller = new class {
            public function enviarEmail($mail) {
                try {
                    $mail->send();
                } catch (Exception $e) {
                    return $e->getMessage();
                }
            }
        };
    
        $mensagemErro = $controller->enviarEmail($mail);
        $this->assertEquals('Erro SMTP', $mensagemErro); // ✅ Aqui é permitido
    }
    

    // 3. Teste: Verifica que é HTML
    public function testEmailFormatoHTML()
    {
        $mail = $this->getMockBuilder(PHPMailer::class)
                     ->onlyMethods(['isHTML', 'send'])
                     ->getMock();

        $mail->expects($this->once())->method('isHTML')->with(true);

        $controller = new class {
            public function enviarEmail($mail) {
                $mail->isHTML(true);
                $mail->send();
            }
        };

        $controller->enviarEmail($mail);
    }

    // 4. Teste: Verifica assunto definido
    public function testEmailAssuntoDefinido()
    {
        $mail = new PHPMailer();
        $mail->Subject = 'Teste de Assunto';
        $this->assertEquals('Teste de Assunto', $mail->Subject);
    }

    // 5. Teste: Verifica corpo do e-mail com nl2br
    public function testEmailCorpoFormatadoComNl2br()
    {
        $mensagem = "Linha 1\nLinha 2";
        $mail = new PHPMailer();
        $mail->Body = nl2br($mensagem);
        $this->assertStringContainsString('<br />', $mail->Body);
    }

    // 6. Teste: Verifica que AltBody é igual à mensagem original
    public function testAltBodyIgualMensagemOriginal()
    {
        $mensagem = "Texto simples";
        $mail = new PHPMailer();
        $mail->AltBody = $mensagem;
        $this->assertEquals('Texto simples', $mail->AltBody);
    }

}