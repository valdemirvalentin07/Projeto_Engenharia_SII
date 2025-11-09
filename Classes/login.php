<?php
/**
 * Classe responsável por gerenciar o login e sessão do usuário via banco de dados.
 * Compatível com a tabela `usuarios` do banco rotina_diaria.
 */
class LoginDB {

    private PDO $pdo;

    /**
     * Construtor que recebe a conexão PDO.
     */
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;

        // Inicia sessão se ainda não estiver iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Verifica as credenciais do usuário e inicia a sessão se forem válidas.
     * 
     * @param string $email
     * @param string $senha
     * @return string|false Retorna o tipo do usuário (admin, tecnico, usuario) ou false se inválido.
     */
    public function verificarCredenciais(string $email, string $senha) {
        $sql = "SELECT id, nome, email, senha, tipo, criado_em 
                FROM usuarios 
                WHERE email = :email 
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // 🔐 Armazena dados do usuário na sessão
            $_SESSION["logged_in"] = true;
            $_SESSION["usuario_id"] = $usuario['id'];
            $_SESSION["usuario_nome"] = $usuario['nome'];
            $_SESSION["usuario_email"] = $usuario['email'];
            $_SESSION["usuario_tipo"] = $usuario['tipo'];
            $_SESSION["usuario_data_cadastro"] = $usuario['criado_em'];
            return $usuario['tipo'];
        }
        return false;
    }

    /**
     * Verifica se o usuário está logado.
     * 
     * @return bool
     */
    public function estaLogado(): bool {
        return isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true;
    }

    /**
     * Retorna os dados do usuário logado (a partir da sessão).
     * 
     * @return array|null
     */
    public function getUsuarioLogado(): ?array {
        if ($this->estaLogado()) {
            return [
                'id' => $_SESSION["usuario_id"] ?? null,
                'nome' => $_SESSION["usuario_nome"] ?? null,
                'email' => $_SESSION["usuario_email"] ?? null,
                'tipo' => $_SESSION["usuario_tipo"] ?? null,
                'data_cadastro' => $_SESSION["usuario_data_cadastro"] ?? null,
            ];
        }
        return null;
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
        header("Location: ../codigos/index.php");
        exit();
    }
}
?>
