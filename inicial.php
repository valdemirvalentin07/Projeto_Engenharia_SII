<?php
session_start();
require_once 'Classes/db.php';

$db = new DB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {
        $_SESSION['erro'] = "Preencha todos os campos.";
        header("Location: inicial.php");
        exit;
    }

    $usuario = $db->getUsuarioByEmail($email);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_tipo'] = $usuario['tipo'];

        header("Location: painel.php");
        exit;
    } else {
        $_SESSION['erro'] = "E-mail ou senha incorretos.";
        header("Location: inicial.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
<link rel="stylesheet" href="inicial.css">
</head>
<body>

  <div class="container">
    <!-- Coluna esquerda: formulário -->
    <div class="formulario">
      <h2>Login</h2>
      <form action="inicial.php" method="post">
        <input type="email" name="email" placeholder="Digite seu e-mail" required>
        <input type="password" name="senha" placeholder="Digite sua senha" required>
        <button type="submit">Entrar</button>
      </form>
      <a href="cadastro.php">Cadastre-se aqui!</a>
    </div>

    <!-- Coluna direita: imagem -->
    <div class="imagem">
      <img src="imagens/nuvem.png" alt="Imagem lateral">
    </div>
  </div>

</body>
</html>
