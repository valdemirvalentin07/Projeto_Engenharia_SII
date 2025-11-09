<?php
session_start();
require_once '../Classes/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../codigos/index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $novo_nome  = trim($_POST['nome']);
    $novo_email = trim($_POST['email']);

    try {
        $db = new DB(); 
        $pdo = $db->getConnection();

        // Verifica se o e-mail já está em uso por outro usuário
        $sqlCheck = "SELECT id FROM usuarios WHERE email = :email AND id != :id LIMIT 1";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute([':email' => $novo_email, ':id' => $usuario_id]);
        if ($stmtCheck->fetch()) {
            $_SESSION['msg_erro'] = "Este e-mail já está sendo usado por outro usuário.";
            header("Location: ../codigos/perfil.php");
            exit;
        }

        // Atualiza no banco
        $sql = "UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $novo_nome,
            ':email' => $novo_email,
            ':id' => $usuario_id
        ]);

        // Atualiza também a sessão
        $_SESSION['usuario_nome'] = $novo_nome;
        $_SESSION['usuario_email'] = $novo_email;

        $_SESSION['msg_sucesso'] = "Dados atualizados com sucesso!";
        header("Location: ../codigos/perfil.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['msg_erro'] = "Erro ao atualizar: " . $e->getMessage();
        header("Location: ../codigos/perfil.php");
        exit;
    }
} else {
    header("Location: ../codigos/perfil.php");
    exit;
}
?>
