<?php
session_start();
require_once '../Classes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar = $_POST['confirmar_senha'];

    if (!$nome || !$email || !$senha || !$confirmar) {
        $_SESSION['erro'] = "Preencha todos os campos.";
        header("Location: ../codigos/cadastro.php");
        exit;
    }

    if ($senha !== $confirmar) {
        $_SESSION['erro'] = "As senhas não conferem.";
        header("Location: ../codigos/cadastro.php");
        exit;
    }

    try {
        $db = new DB();
        $usuario = $db->usuario($email);

        if ($usuario) {
            $_SESSION['erro'] = "E-mail já cadastrado.";
            header("Location: ../codigos/cadastro.php");
            exit;
        }

        $db->cadastrarUsuario($nome, $email, $senha);
        $_SESSION['sucesso'] = "Cadastro realizado com sucesso! Faça login.";
        header("Location: ../codigos/index.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['erro'] = "Erro ao cadastrar: " . $e->getMessage();
        header("Location: ../codigos/cadastro.php");
        exit;
    }
}
