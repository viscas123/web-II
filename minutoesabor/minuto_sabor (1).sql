-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05/08/2025 às 15:04
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `minuto_sabor`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `endereco` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`id`, `nome`, `cpf`, `telefone`, `endereco`, `email`, `senha`, `data_cadastro`) VALUES
(11, 'raquel', '66666666666', '981457777', 'contabilista', 'raquel@email.com', '$2y$10$c5nszbQS0cUZkPhgw6SAhuAZEMzxJZlLW5hn9f778uOXdNSmMJoCK', '2025-08-01 13:46:19'),
(12, 'raquel', '55555555555', '981457777', 'contabilista', 'email@email.com', '$2y$10$8XkSKgIZEWc3ZDtqnwPl3eGkaKvs1WlXBX/6lDhP6WLEMybuOYwLC', '2025-08-01 13:55:15'),
(13, 'jurandir', '33333333333', '5596896521', 'contabilista', 'jurandir@gmail.com', '$2y$10$Vwr5gHy/cNpQnPc1iilvl.MaxUT1Ufdj4nx0yF/Vc9IBywXRq77.a', '2025-08-05 10:04:27');

-- --------------------------------------------------------

--
-- Estrutura para tabela `detalhes_item_pedido`
--

CREATE TABLE `detalhes_item_pedido` (
  `id` int(11) NOT NULL,
  `id_item` int(11) DEFAULT NULL,
  `adicionais` text DEFAULT NULL,
  `complementos` text DEFAULT NULL,
  `preco_unitario` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `item_pedido`
--

CREATE TABLE `item_pedido` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `nome_produto` varchar(255) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `id_produto` int(11) NOT NULL,
  `preco` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `item_pedido`
--

INSERT INTO `item_pedido` (`id`, `id_pedido`, `nome_produto`, `quantidade`, `id_produto`, `preco`) VALUES
(1, 26, NULL, 1, 688, 25.00),
(2, 27, NULL, 1, 688, 25.00),
(3, 28, NULL, 1, 688, 25.00),
(4, 29, NULL, 1, 688, 25.00),
(5, 30, NULL, 1, 688, 18.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido`
--

CREATE TABLE `pedido` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `data_pedido` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedido`
--

INSERT INTO `pedido` (`id`, `id_cliente`, `data_pedido`, `total`) VALUES
(1, NULL, '2025-07-31 10:03:55', 22.00),
(2, NULL, '2025-07-31 10:07:49', 22.00),
(3, NULL, '2025-07-31 10:07:50', 22.00),
(4, NULL, '2025-07-31 10:08:01', 22.00),
(5, NULL, '2025-07-31 10:10:24', 22.00),
(6, NULL, '2025-07-31 10:10:33', 22.00),
(7, NULL, '2025-07-31 11:31:56', 22.00),
(8, NULL, '2025-07-31 11:55:30', 47.00),
(9, NULL, '2025-08-01 07:39:47', 25.00),
(10, NULL, '2025-08-01 10:46:19', 47.00),
(11, NULL, '2025-08-01 10:51:18', 47.00),
(12, NULL, '2025-08-01 10:51:25', 47.00),
(13, NULL, '2025-08-01 10:51:43', 47.00),
(14, NULL, '2025-08-01 10:54:14', 47.00),
(15, NULL, '2025-08-01 10:54:15', 47.00),
(16, NULL, '2025-08-01 10:54:17', 47.00),
(17, NULL, '2025-08-01 10:54:18', 47.00),
(18, NULL, '2025-08-01 10:54:23', 47.00),
(19, NULL, '2025-08-01 10:55:15', 47.00),
(20, NULL, '2025-08-01 11:02:59', 47.00),
(21, NULL, '2025-08-01 11:03:09', 47.00),
(22, NULL, '2025-08-01 11:04:05', 47.00),
(23, NULL, '2025-08-01 11:06:40', 47.00),
(24, NULL, '2025-08-01 11:08:06', 47.00),
(25, NULL, '2025-08-01 11:08:13', 25.00),
(26, NULL, '2025-08-01 11:17:08', 25.00),
(27, NULL, '2025-08-01 11:20:18', 25.00),
(28, NULL, '2025-08-01 11:36:50', 25.00),
(29, NULL, '2025-08-01 11:56:14', 25.00),
(30, NULL, '2025-08-01 12:12:54', 18.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `imagem` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `criado_em`, `imagem`) VALUES
(1, 'Açai com banana', '1L de açai batido com banana', 15.00, '2025-08-05 12:26:51', 'img_6891f88b2fe1e.webp'),
(3, 'Açai com guarana', 'Açaí batido com guaraná.', 12.00, '2025-08-05 13:03:15', 'img_689201136b8a0.webp');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- Índices de tabela `detalhes_item_pedido`
--
ALTER TABLE `detalhes_item_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_item` (`id_item`);

--
-- Índices de tabela `item_pedido`
--
ALTER TABLE `item_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Índices de tabela `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `detalhes_item_pedido`
--
ALTER TABLE `detalhes_item_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `item_pedido`
--
ALTER TABLE `item_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `detalhes_item_pedido`
--
ALTER TABLE `detalhes_item_pedido`
  ADD CONSTRAINT `detalhes_item_pedido_ibfk_1` FOREIGN KEY (`id_item`) REFERENCES `item_pedido` (`id`);

--
-- Restrições para tabelas `item_pedido`
--
ALTER TABLE `item_pedido`
  ADD CONSTRAINT `item_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id`);

--
-- Restrições para tabelas `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
