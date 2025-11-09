<?php
session_start();


$erro = $_SESSION['erro'] ?? '';
$sucesso = $_SESSION['sucesso'] ?? '';
unset($_SESSION['erro'], $_SESSION['sucesso']);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Cadastro</title>
<link rel="stylesheet" href="CSS/cadastro.css">
</head>
<body>

<div class="container">
    <div class="formulario">
        <h2>Cadastro</h2>

        <?php if($erro): ?>
            <p class="erro"><?= htmlspecialchars($erro) ?></p>
        <?php endif; ?>

        <?php if($sucesso): ?>
            <p class="sucesso"><?= htmlspecialchars($sucesso) ?></p>
        <?php endif; ?>

        <form action="../CRUD/processa_cadastro.php" method="post">
            <input type="text" name="nome" placeholder="Digite seu nome completo" required>
            <input type="email" name="email" placeholder="Digite seu e-mail" required>
            <input type="password" name="senha" placeholder="Digite sua senha" required>
            <input type="password" name="confirmar_senha" placeholder="Confirme sua senha" required>
            <button type="submit">Cadastrar</button>
            <br>
            <br>
             <a href="index.php">Já tem conta? Faça login</a>
        </form>
        
           
        
        
        
    </div>

    <div class="imagem">
        <img src="imagens/nuvem.png" alt="Imagem lateral">
    </div>
</div>

</body>
</html>
