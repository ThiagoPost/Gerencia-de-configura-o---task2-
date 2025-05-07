<?php
namespace App\Controller;
use App\Banco;
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ControllerListar {
    private $banco;
    public $tarefas;

    public function __construct() {
        $this->banco = new Banco();
        $this->tarefas = $this->banco->getTarefa();
    }

    public function getTarefas() {
        return $this->tarefas;
    }

     // CREATE
     public function adicionarTarefa($dados) {
        $resultado = $this->banco->inserirTarefa($dados);
        if ($resultado) {
            $this->enviarEmail("Nova Tarefa Criada", print_r($dados, true));
        }
        return $resultado;
    }

    public function atualizarTarefa($id, $dados) {
        $resultado = $this->banco->atualizarTarefa($id, $dados);
        if ($resultado) {
            $this->enviarEmail("Tarefa Atualizada", "ID: $id\n" . print_r($dados, true));
        }
        return $resultado;
    }

    // DELETE
    public function deletarTarefa($id) {
        return $this->banco->deletarTarefa($id);
    }

    // GET BY ID (Opcional, mas útil)
    public function getTarefaPorId($id) {
        return $this->banco->getTarefaPorId($id);
    }

    private function enviarEmail($assunto, $mensagem) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'thiago.post@universo.univates.br';
            $mail->Password = 'egtq llqf ugad cddv';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('seuemail@gmail.com', 'Sistema de Tarefas');
            $mail->addAddress('seuemail@gmail.com');

            $mail->isHTML(true);
            $mail->Subject = $assunto;
            $mail->Body    = nl2br($mensagem);
            $mail->AltBody = $mensagem;

            $mail->send();
        } catch (Exception $e) {
            error_log("Erro ao enviar e-mail: {$mail->ErrorInfo}");
        }
    }
}