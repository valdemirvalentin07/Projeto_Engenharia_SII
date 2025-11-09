<?php
class DB {
    private $pdo;
    private $table;

    public function __construct($table = 'tarefas') {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=rotina_diaria;charset=utf8", "root", "");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->table = $table;
        } catch (PDOException $e) {
            throw new Exception("Erro ao conectar: " . $e->getMessage());
        }
    }

    /* ✅ Retorna a conexão PDO (necessário para LoginDB) */
    public function getConnection(): PDO {
        return $this->pdo;
    }

    /* -------------------- USUÁRIOS -------------------- */
    public function usuario($email) {
        $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cadastrarUsuario($nome, $email, $senha) {
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => password_hash($senha, PASSWORD_DEFAULT)
        ]);
    }

    /* -------------------- TAREFAS -------------------- */
    public function create($data) {
        $sql = "INSERT INTO tarefas (usuario_id, titulo, cor, data, status)
                VALUES (:usuario_id, :titulo, :cor, :data, 'pendente')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function readByUser($usuario_id) {
        $sql = "SELECT * FROM tarefas WHERE usuario_id = :usuario_id ORDER BY data ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':usuario_id' => $usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $sql = "UPDATE tarefas SET titulo = :titulo, cor = :cor, data = :data WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $data[':id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id) {
        $sql = "DELETE FROM tarefas WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function concluir($id) {
        $sql = "UPDATE tarefas SET status = 'concluida' WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>
