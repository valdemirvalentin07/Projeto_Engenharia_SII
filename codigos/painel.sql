-- Criar banco de dados (se ainda não existir)
CREATE DATABASE IF NOT EXISTS rotina_diaria
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE rotina_diaria;

-- ===============================
-- TABELA DE USUÁRIOS (Login/Cadastro)
-- ===============================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('admin','tecnico','usuario') DEFAULT 'usuario',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===============================
-- TABELA DE TAREFAS (Painel)
-- ===============================
CREATE TABLE IF NOT EXISTS tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT,
    data DATE NOT NULL,
    hora TIME NULL,
    icone VARCHAR(10) DEFAULT '📌',
    cor VARCHAR(20) DEFAULT 'azul',
    status ENUM('pendente','concluida') DEFAULT 'pendente',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- ===============================
-- USUÁRIO PADRÃO PARA LOGIN (somente se não existir)
-- ===============================
INSERT IGNORE INTO usuarios (id, nome, email, senha, tipo)
VALUES (1, 'Administrador', 'admin@teste.com', '$2y$10$e0NRH54xKzMxGuF9yVfLWeWQYwC7yWZ6/I8g5vQPi5zKBJvL7oZaO', 'admin');

-- ===============================
-- TAREFAS EXEMPLO (somente se não existirem)
-- ===============================
INSERT IGNORE INTO tarefas (id, usuario_id, titulo, descricao, data, hora, icone, cor, status)
VALUES
(1, 1, 'Ir ao mercado', 'Comprar frutas e verduras', '2025-09-17', '15:00', '🛒', 'verde', 'pendente'),
(2, 1, 'Reunião de trabalho', 'Call com equipe de projeto', '2025-09-18', '10:00', '💼', 'azul', 'pendente'),
(3, 1, 'Treino', 'Academia - peito e tríceps', '2025-09-18', '19:00', '💪', 'vermelho', 'concluida');
