<?php
namespace App\Controller;
use App\Banco;

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
        return $this->banco->inserirTarefa($dados);
    }

    // UPDATE
    public function atualizarTarefa($id, $dados) {
        return $this->banco->atualizarTarefa($id, $dados);
    }

    // DELETE
    public function deletarTarefa($id) {
        return $this->banco->deletarTarefa($id);
    }

    // GET BY ID (Opcional, mas útil)
    public function getTarefaPorId($id) {
        return $this->banco->getTarefaPorId($id);
    }
}