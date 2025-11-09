<?php
session_start();
require_once '../Classes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $titulo = trim($_POST['titulo']);
    $prioridade = $_POST['prioridade'];
    $prazo = $_POST['prazo'];

    // Define a cor de prioridade
    $cor = match ($prioridade) {
        'Alta' => 'vermelho',
        'Média' => 'azul',
        default => 'cinza'
    };

    try {
        $db = new DB();
        $db->create([
            ':usuario_id' => $usuario_id,
            ':titulo' => $titulo,
            ':cor' => $cor,
            ':data' => $prazo
        ]);
        header("Location: ../codigos/tarefa.php");
        exit();
    } catch (Exception $e) {
        die("Erro ao adicionar tarefa: " . $e->getMessage());
    }
}
