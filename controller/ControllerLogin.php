<?php

namespace App\Controller;
use App\Banco;

require_once __DIR__ . '/../vendor/autoload.php';
class ControllerLogin {
    private $banco;

    public function __construct() {
        $this->banco = new Banco();
    }

    public function autenticar($email, $senha) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Inicia a sessão apenas se ainda não estiver iniciada
        }
   
        $usuario = $this->banco->buscarUsuarioPorEmail($email);
   
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario'] = $usuario;
            return true;
        }
   
        return false;
    }

    public function verificarLogin() {
        session_start();
        return isset($_SESSION['usuario']);
    }

    public function logout() {
        session_start();
        session_unset(); // Remove todas as variáveis da sessão
        session_destroy(); // Destrói a sessão
    }
}
?>