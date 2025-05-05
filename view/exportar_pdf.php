<?php
require('../controller/ControllerListar.php');
require('../model/Banco.php'); // se ControllerListar depende da classe Banco
require('../fpdf/fpdf.php');

use App\Controller\ControllerListar;

$controller = new ControllerListar();
$tarefas = $controller->getTarefas();

// Cria uma instância do FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// Cabeçalho da tabela
$pdf->Cell(10, 10, 'ID', 1);
$pdf->Cell(60, 10, 'Descricao', 1);
$pdf->Cell(30, 10, 'Data Criacao', 1);
$pdf->Cell(30, 10, 'Data Prevista', 1);
$pdf->Cell(30, 10, 'Data Encerramento', 1);
$pdf->Cell(30, 10, 'Situacao', 1);
$pdf->Ln();

// Adiciona os dados da tabela
foreach ($tarefas as $tarefa) {
    $pdf->Cell(10, 10, $tarefa['id'], 1);
    $pdf->Cell(60, 10, utf8_decode($tarefa['descricao']), 1);
    $pdf->Cell(30, 10, $tarefa['data_criacao'], 1);
    $pdf->Cell(30, 10, $tarefa['data_prevista'], 1);
    $pdf->Cell(30, 10, $tarefa['data_encerramento'] ?? 'N/A', 1);
    $pdf->Cell(30, 10, utf8_decode($tarefa['situacao']), 1);
    $pdf->Ln();
}

// Gera o PDF
$pdf->Output('D', 'tarefas.pdf');