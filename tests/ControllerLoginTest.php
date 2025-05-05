<?php

use PHPUnit\Framework\TestCase;
use App\Controller\ControllerLogin;
use App\Banco;

class ControllerLoginTest extends TestCase
{
    private $controller;
    private $banco;
    private $usuarioTeste;

    protected function setUp(): void
    {
        $this->banco = new Banco();
        $this->controller = new ControllerLogin();

        // Cria usuário de teste
        $this->usuarioTeste = [
            'email' => 'teste@example.com',
            'senha' => password_hash('senha123', PASSWORD_DEFAULT),
            'nome' => 'Usuário de Teste'
        ];

        // Insere diretamente no banco
        $sql = "INSERT INTO usuarios (email, senha, nome) VALUES (:email, :senha, :nome)";
        $this->banco->executarSQL($sql, [
            ':email' => $this->usuarioTeste['email'],
            ':senha' => $this->usuarioTeste['senha'],
            ':nome'  => $this->usuarioTeste['nome']
        ]);
    }

    protected function tearDown(): void
    {
        // Remove usuário de teste do banco
        $sql = "DELETE FROM usuarios WHERE email = :email";
        $this->banco->executarSQL($sql, [':email' => $this->usuarioTeste['email']]);
    }

    public function testAutenticacaoComDadosCorretos()
    {
        $resultado = $this->controller->autenticar('teste@example.com', 'senha123');
        $this->assertTrue($resultado);
        $this->assertArrayHasKey('usuario', $_SESSION);
    }

    public function testAutenticacaoComDadosIncorretos() {
        session_start();
        session_unset(); // Limpa todas as variáveis da sessão
        session_destroy(); // Destrói a sessão
    
        $resultado = $this->controller->autenticar('teste@example.com', 'senhaErrada');
        $this->assertFalse($resultado);
        $this->assertArrayNotHasKey('usuario', $_SESSION);
    }

    public function testVerificarLogin()
    {
        $_SESSION = [];
        $_SESSION['usuario'] = $this->usuarioTeste;
        $this->assertTrue($this->controller->verificarLogin());
    }

    public function testLogout() {
        $_SESSION['usuario'] = $this->usuarioTeste;
        $this->controller->logout();
        $this->assertFalse(isset($_SESSION['usuario']));
    }
}
