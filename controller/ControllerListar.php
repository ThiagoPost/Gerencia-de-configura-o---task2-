<?php
require_once('../model/Banco.php') ;

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
}