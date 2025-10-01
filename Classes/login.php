<?php
/**
 * Classe responsável por gerenciar o login do usuário via banco de dados.
 */
class LoginDB {

    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Verifica credenciais fornecidas.
     * @param string $email
     * @param string $senha
     * @return string|false Retorna tipo do usuário ou false se inválido
     */
    public function verificarCredenciais(string $email, string $senha) {
        $sql = "SELECT id, nome, senha, tipo FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION["logged_in"] = true;
            $_SESSION["usuario_id"] = $usuario['id'];
            $_SESSION["usuario_nome"] = $usuario['nome'];
            $_SESSION["usuario_tipo"] = $usuario['tipo'];
            return $usuario['tipo'];
        }
        return false;
    }

    /**
     * Verifica se o usuário está logado.
     * @return bool
     */
    public function estaLogado(): bool {
        return isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true;
    }

    /**
     * Encerra a sessão e redireciona para a página de login.
     */
    public function logout(): void {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header("Location: login.php");
        exit();
    }
}
?>
