<?php
require_once '../Classes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $prioridade = $_POST['prioridade'];
    $prazo = $_POST['prazo'];

    $cor = match ($prioridade) {
        'Alta' => 'vermelho',
        'Média' => 'azul',
        default => 'cinza'
    };

    try {
        $db = new DB();
        $db->update($id, [
            ':titulo' => $titulo,
            ':cor' => $cor,
            ':data' => $prazo
        ]);
        header("Location: ../codigos/tarefa.php");
        exit();
    } catch (Exception $e) {
        die("Erro ao atualizar tarefa: " . $e->getMessage());
    }
}
