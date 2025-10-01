<?php
session_start();
require_once "Classes/db.php";

$db = new DB();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: cadastro.php");
    exit;
}

$nome = trim($_POST["nome"] ?? '');
$email = trim($_POST["email"] ?? '');
$senha = $_POST["senha"] ?? '';
$confirma = $_POST["confirmar_senha"] ?? '';

// Validações
if (!$nome || !$email || !$senha || !$confirma) {
    $_SESSION['erro'] = "Preencha todos os campos.";
    header("Location: cadastro.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erro'] = "E-mail inválido.";
    header("Location: cadastro.php");
    exit;
}

if ($senha !== $confirma) {
    $_SESSION['erro'] = "As senhas não conferem.";
    header("Location: cadastro.php");
    exit;
}

// Verificar se e-mail já existe
$usuarioExistente = $db->getUsuarioByEmail($email);

if ($usuarioExistente) {
    $_SESSION['erro'] = "E-mail já cadastrado.";
    header("Location: cadastro.php");
    exit;
}

// Cadastrar usuário
$cadastrado = $db->cadastrarUsuario($nome, $email, $senha);

if ($cadastrado) {
    $_SESSION['sucesso'] = "Cadastro realizado com sucesso! Faça login.";
    header("Location: inicial.php");
    exit;
} else {
    $_SESSION['erro'] = "Erro ao cadastrar. Tente novamente.";
    header("Location: cadastro.php");
    exit;
}
