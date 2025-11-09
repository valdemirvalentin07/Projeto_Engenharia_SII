-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 09/11/2025 às 16:01
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `rotina_diaria`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefas`
--

CREATE TABLE `tarefas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descricao` text DEFAULT NULL,
  `data` date NOT NULL,
  `hora` time DEFAULT NULL,
  `icone` varchar(10) DEFAULT '?',
  `cor` varchar(20) DEFAULT 'azul',
  `status` enum('pendente','concluida') DEFAULT 'pendente',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tarefas`
--

INSERT INTO `tarefas` (`id`, `usuario_id`, `titulo`, `descricao`, `data`, `hora`, `icone`, `cor`, `status`, `criado_em`, `atualizado_em`) VALUES
(1, 1, 'Ir ao mercado', 'Comprar frutas e verduras', '2025-09-17', '15:00:00', '🛒', 'verde', 'pendente', '2025-11-08 15:56:10', '2025-11-08 15:56:10'),
(2, 1, 'Reunião de trabalho', 'Call com equipe de projeto', '2025-09-18', '10:00:00', '💼', 'azul', 'pendente', '2025-11-08 15:56:10', '2025-11-08 15:56:10'),
(3, 1, 'Treino', 'Academia - peito e tríceps', '2025-09-18', '19:00:00', '💪', 'vermelho', 'concluida', '2025-11-08 15:56:10', '2025-11-08 15:56:10'),
(4, 2, 'Academia', NULL, '2025-11-08', NULL, '📌', 'vermelho', 'pendente', '2025-11-08 16:26:59', '2025-11-08 16:26:59'),
(5, 2, 'Mercado', NULL, '2025-11-08', NULL, '📌', 'azul', 'pendente', '2025-11-08 16:27:42', '2025-11-08 16:27:42'),
(6, 2, 'Trocar óleo do carro', NULL, '2025-11-12', NULL, '📌', 'vermelho', 'pendente', '2025-11-08 16:28:11', '2025-11-08 16:28:11'),
(7, 3, 'Shopping', NULL, '2025-11-08', NULL, '📌', 'vermelho', 'concluida', '2025-11-08 16:29:05', '2025-11-08 16:29:39'),
(8, 3, 'Trocar óleo do carro', NULL, '2025-11-08', NULL, '📌', 'vermelho', 'concluida', '2025-11-08 16:29:36', '2025-11-08 16:29:41');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('admin','tecnico','usuario') DEFAULT 'usuario',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `criado_em`) VALUES
(1, 'Administrador', 'admin@teste.com', '$2y$10$e0NRH54xKzMxGuF9yVfLWeWQYwC7yWZ6/I8g5vQPi5zKBJvL7oZaO', 'admin', '2025-11-08 15:56:10'),
(2, 'Layla Barros Araújo', 'lyla@gmail.com', '$2y$10$L.y2tmzq4SIn9FDw6.MNleYS1aGUUHr5eD8eVJJWRyLBhb4JAZRJS', 'usuario', '2025-11-08 16:01:02'),
(3, 'Vanessa graciela cardoso valentim', 'valdemirvalentim762@gmail.com', '$2y$10$aHdQkyP7QuoRISfYuzcJdexI5paR575zrA179q13Gc8wOr/nyHJGS', 'usuario', '2025-11-08 16:28:46');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tarefas`
--
ALTER TABLE `tarefas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuario` (`usuario_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tarefas`
--
ALTER TABLE `tarefas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tarefas`
--
ALTER TABLE `tarefas`
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
