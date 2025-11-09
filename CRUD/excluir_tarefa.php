<?php
require_once '../Classes/db.php';
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $db = new DB();
        $db->delete($id);
        header("Location: ../codigos/tarefa.php");
        exit();
    } catch (Exception $e) {
        die("Erro ao excluir tarefa: " . $e->getMessage());
    }
}
