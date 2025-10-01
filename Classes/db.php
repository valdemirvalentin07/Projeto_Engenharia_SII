<?php
/**
 * Classe responsável por gerenciar usuários e tarefas via PDO.
 */
class DB
{
    protected $host = 'localhost';
    protected $dbname = 'rotina_diaria';
    protected $username = 'root';
    protected $password = '';
    protected $pdo;
    protected $table = 'tarefas'; // tabela padrão para tarefas

    public function __construct()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8";
            $this->pdo = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            die("Erro ao conectar: " . $e->getMessage());
        }
    }

    public function __destruct()
    {
        $this->pdo = null;
    }

    // Método interno para executar queries
    private function execute($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

   
    // USUÁRIOS
    

    /**
     * Busca usuário pelo e-mail (para login)
     * @param string $email
     * @return array|null
     */
    public function getUsuarioByEmail(string $email): ?array
    {
        $sql = "SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $this->execute($sql, [':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        return $usuario ?: null;
    }

    /**
     * Cadastra um novo usuário
     */
    public function cadastrarUsuario(string $nome, string $email, string $senha, string $tipo = 'usuario'): bool
    {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)";
        return (bool)$this->execute($sql, [
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senhaHash,
            ':tipo' => $tipo
        ]);
    }

    
    // TAREFAS
    

    // Criar nova tarefa
    public function create($usuario_id, $titulo, $descricao, $data, $hora = null, $icone = '📌', $cor = 'azul', $status = 'pendente')
    {
        $sql = "INSERT INTO {$this->table} 
                (usuario_id, titulo, descricao, data, hora, icone, cor, status)
                VALUES (:usuario_id, :titulo, :descricao, :data, :hora, :icone, :cor, :status)";
        return $this->execute($sql, [
            ':usuario_id' => $usuario_id,
            ':titulo' => $titulo,
            ':descricao' => $descricao,
            ':data' => $data,
            ':hora' => $hora,
            ':icone' => $icone,
            ':cor' => $cor,
            ':status' => $status
        ]);
    }

    // Ler todas as tarefas
    public function readAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY data ASC, hora ASC";
        return $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ler tarefa por ID
    public function read($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        return $this->execute($sql, [':id' => $id])->fetch(PDO::FETCH_ASSOC);
    }

    // Ler todas as tarefas de um usuário
    public function readByUser($usuario_id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE usuario_id = :usuario_id ORDER BY data ASC, hora ASC";
        return $this->execute($sql, [':usuario_id' => $usuario_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    // Atualizar tarefa
    public function update($id, $titulo, $descricao, $data, $hora = null, $icone = '📌', $cor = 'azul', $status = 'pendente')
    {
        $sql = "UPDATE {$this->table} SET
                    titulo = :titulo,
                    descricao = :descricao,
                    data = :data,
                    hora = :hora,
                    icone = :icone,
                    cor = :cor,
                    status = :status
                WHERE id = :id";
        return $this->execute($sql, [
            ':id' => $id,
            ':titulo' => $titulo,
            ':descricao' => $descricao,
            ':data' => $data,
            ':hora' => $hora,
            ':icone' => $icone,
            ':cor' => $cor,
            ':status' => $status
        ]);
    }

    // Deletar tarefa
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->execute($sql, [':id' => $id]);
    }

    // Alterar status da tarefa (pendente <-> concluida)
    public function toggleStatus($id)
    {
        $tarefa = $this->read($id);
        if ($tarefa) {
            $novoStatus = $tarefa['status'] === 'pendente' ? 'concluida' : 'pendente';
            return $this->update(
                $id,
                $tarefa['titulo'],
                $tarefa['descricao'],
                $tarefa['data'],
                $tarefa['hora'],
                $tarefa['icone'],
                $tarefa['cor'],
                $novoStatus
            );
        }
        return false;
    }
}
