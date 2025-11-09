<?php
require_once '../Classes/db.php';
require_once '../Classes/login.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    try {
        $db = new DB(); // sua classe de conexão
        $login = new LoginDB($db->getConnection());
        $tipo = $login->verificarCredenciais($email, $senha);

        if ($tipo !== false) {
            header("Location: tarefa.php");
            exit;
        } else {
            $erro = "E-mail ou senha inválidos.";
        }
    } catch (Exception $e) {
        $erro = "Erro: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="CSS/inicial.css">
</head>
<body>

    <div class="container">
        <div class="formulario">
            <h2>Login</h2>
            
            <?php if (isset($_SESSION['erro'])): ?>
                <p style="color: red; border: 1px solid red; padding: 10px; border-radius: 5px;"><?php echo $_SESSION['erro']; ?></p>
                <?php unset($_SESSION['erro']); // Limpa a mensagem após exibir ?>
            <?php endif; ?>
            
            <form action="index.php" method="post">
                <input type="email" name="email" placeholder="Digite seu e-mail" required>
                <input type="password" name="senha" placeholder="Digite sua senha" required>
                <button type="submit">Entrar</button>
                <br>
                <br>
                  <a href="cadastro.php">Cadastre-se aqui!</a>
            </form>
          
        </div>

        <div class="imagem">
            <img src="imagens/nuvem.png" alt="Imagem lateral">
        </div>
    </div>

</body>
</html>