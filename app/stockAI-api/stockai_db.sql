-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Tempo de geração: 20-Set-2025 às 23:33
-- Versão do servidor: 8.0.43
-- versão do PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de dados: `stockai_db`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Extraindo dados da tabela `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250920074522', '2025-09-20 22:15:00', 138),
('DoctrineMigrations\\Version20250920141959', '2025-09-20 22:15:00', 10),
('DoctrineMigrations\\Version20250920204115', '2025-09-20 22:15:00', 5),
('DoctrineMigrations\\Version20250920215852', '2025-09-20 22:15:00', 5),
('DoctrineMigrations\\Version20250920230317', '2025-09-20 23:06:47', 7),
('DoctrineMigrations\\Version20250920231256', '2025-09-20 23:13:01', 19),
('DoctrineMigrations\\Version20250920231634', '2025-09-20 23:16:39', 8);

-- --------------------------------------------------------

--
-- Estrutura da tabela `ingredient`
--

CREATE TABLE `ingredient` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `stock_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` double NOT NULL,
  `unit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `ingredient`
--

INSERT INTO `ingredient` (`id`, `product_id`, `stock_id`, `name`, `quantity`, `unit`) VALUES
(1, 1, 1, 'Salt', 5, 'grams'),
(2, 1, 2, 'Pepper', 3, 'grams'),
(3, 1, 3, 'Lettuce', 100, 'grams'),
(4, 1, 4, 'Tomato', 50, 'grams'),
(5, 2, 21, 'Beef Patty', 150, 'grams'),
(6, 2, 8, 'Cheese Slice', 1, 'unit'),
(7, 4, 9, 'Potato', 200, 'grams'),
(8, 4, 10, 'Cooking Oil', 15, 'ml'),
(9, 1, 11, 'Chicken Breast', 120, 'grams'),
(10, 5, 12, 'Croutons', 30, 'grams'),
(11, 5, 13, 'Caesar Dressing', 25, 'ml'),
(12, 7, 14, 'Pizza Dough', 1, 'unit'),
(13, 7, 15, 'Mozzarella Cheese', 100, 'grams'),
(14, 7, 16, 'Tomato Sauce', 50, 'ml'),
(15, 8, 17, 'Bread', 2, 'slices');

-- --------------------------------------------------------

--
-- Estrutura da tabela `invoice`
--

CREATE TABLE `invoice` (
  `id` int NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `link_image_invoice` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `invoice`
--

INSERT INTO `invoice` (`id`, `created_at`, `link_image_invoice`, `supplier_name`) VALUES
(1, '2025-09-20 21:40:26', 'https://meuservidor.com/notas/nf001.png', 'Fornecedor XPTO');

-- --------------------------------------------------------

--
-- Estrutura da tabela `messages_sent`
--

CREATE TABLE `messages_sent` (
  `id` int NOT NULL,
  `html` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `supplier_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `messages_sent`
--

INSERT INTO `messages_sent` (`id`, `html`, `recipient`, `title`, `created_at`, `supplier_name`, `supplier_id`) VALUES
(1, '<h1>Obrigado pela sua compra!</h1><p>Seu pedido foi confirmado.</p>', 'cliente@exemplo.com', 'Confirmação de Pedido', '2025-09-20 22:57:24', 'Mercado Central', 1),
(2, '<ul><li>Arroz - 10 un</li><li>Feijão - 50 un</li><li>Óleo - 30 un</li></ul>', 'contato@supermercadobompreco.com', 'Lista de Compras - Semana 38', '2025-09-20 23:28:29', 'Supermercado Bom Preço', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `order`
--

CREATE TABLE `order` (
  `id` int NOT NULL,
  `total_price` double NOT NULL,
  `order_date` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `order`
--

INSERT INTO `order` (`id`, `total_price`, `order_date`) VALUES
(1, 25, '2023-10-01 12:00:00'),
(2, 22, '2023-10-02 15:30:00'),
(3, 35, '2023-10-03 18:45:00'),
(4, 42, '2023-10-04 13:15:00'),
(5, 55, '2023-10-05 19:30:00'),
(6, 54, '2023-10-06 12:45:00'),
(7, 56, '2023-10-07 20:00:00'),
(8, 75, '2025-09-20 16:36:44'),
(9, 75, '2025-09-20 16:43:24'),
(10, 75, '2025-09-20 16:56:39'),
(11, 75, '2025-09-20 16:56:51'),
(12, 75, '2025-09-20 17:01:38'),
(13, 75, '2025-09-20 17:02:02'),
(14, 10, '2025-09-20 18:47:56'),
(15, 10, '2025-09-20 18:47:59'),
(16, 10, '2025-09-20 18:48:01'),
(17, 10, '2025-09-20 18:48:04'),
(18, 10, '2025-09-20 18:48:06'),
(19, 10, '2025-09-20 18:48:08'),
(20, 10, '2025-09-20 18:48:11'),
(21, 10, '2025-09-20 18:48:16'),
(22, 10, '2025-09-20 18:48:18'),
(23, 10, '2025-09-20 18:48:23'),
(24, 15, '2025-09-20 22:45:14');

-- --------------------------------------------------------

--
-- Estrutura da tabela `order_item`
--

CREATE TABLE `order_item` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `order_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `order_item`
--

INSERT INTO `order_item` (`id`, `product_id`, `order_id`, `quantity`, `price`) VALUES
(1, 1, 1, 2, 10),
(2, 3, 1, 1, 5),
(3, 2, 2, 1, 15),
(4, 4, 2, 1, 7),
(5, 7, 3, 1, 18),
(6, 6, 3, 2, 4.5),
(7, 10, 3, 1, 8),
(8, 5, 4, 2, 12),
(9, 9, 4, 3, 6),
(10, 13, 5, 2, 16),
(11, 14, 5, 4, 3),
(12, 12, 5, 2, 6.5),
(13, 8, 6, 3, 13),
(14, 15, 6, 2, 7.5),
(15, 11, 7, 4, 9),
(16, 3, 7, 4, 5),
(17, 1, 8, 2, 10),
(18, 3, 8, 1, 5),
(19, 1, 9, 2, 10),
(20, 3, 9, 1, 5),
(21, 1, 10, 2, 10),
(22, 3, 10, 1, 5),
(23, 1, 11, 2, 10),
(24, 3, 11, 1, 5),
(25, 1, 12, 2, 10),
(26, 3, 12, 1, 5),
(27, 1, 13, 2, 10),
(28, 3, 13, 1, 5),
(29, 1, 14, 1, 10),
(30, 1, 15, 1, 10),
(31, 1, 16, 1, 10),
(32, 1, 17, 1, 10),
(33, 1, 18, 1, 10),
(34, 1, 19, 1, 10),
(35, 1, 20, 1, 10),
(36, 1, 21, 1, 10),
(37, 1, 22, 1, 10),
(38, 1, 23, 1, 10),
(39, 2, 24, 1, 15);

-- --------------------------------------------------------

--
-- Estrutura da tabela `product`
--

CREATE TABLE `product` (
  `id` int NOT NULL,
  `supplier_id` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `has_ingredients` tinyint(1) DEFAULT NULL,
  `description` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `product`
--

INSERT INTO `product` (`id`, `supplier_id`, `name`, `price`, `has_ingredients`, `description`, `link_image`) VALUES
(1, NULL, 'Chicken Salad', 10, 1, 'A healthy chicken salad with fresh vegetables.', 'https://www.allrecipes.com/thmb/aPy_MqN4GgyO27vh98ns1pXaIWg=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/8499-Best-Chicken-Salad-DDMFS-3X4-11915-d36a5b98b1e041b3b48b62ecdfa6029e.jpg'),
(2, NULL, 'Beef Burger', 15, 1, 'A juicy beef burger with cheese and lettuce.', 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=1899&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'),
(3, 2, 'Coca-Cola', 5, 0, 'A refreshing Coca-Cola beverage.', 'https://gostobastante.pt/wp-content/uploads/2023/03/Prancheta-1.png'),
(4, NULL, 'French Fries', 7, 1, 'Crispy golden french fries.', 'https://plus.unsplash.com/premium_photo-1672774750509-bc9ff226f3e8?q=80&w=987&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'),
(5, NULL, 'Caesar Salad', 12, 1, 'A classic Caesar salad with romaine lettuce and croutons.', 'https://images.unsplash.com/photo-1725030660031-585ea459d55f?q=80&w=987&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'),
(6, 4, 'Pepsi', 4.5, 0, 'Refreshing cola drink.', 'https://images.unsplash.com/photo-1553456558-aff63285bdd1?q=80&w=987&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'),
(7, NULL, 'Cheese Pizza', 18, 1, 'Delicious cheese pizza with tomato sauce.', 'https://images.unsplash.com/photo-1712652080841-9e480a2c43ec?q=80&w=987&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'),
(8, NULL, 'Grilled Chicken Sandwich', 13, 1, 'Sandwich with grilled chicken and fresh vegetables.', 'https://images.unsplash.com/photo-1757961048411-73703e333d25?q=80&w=987&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'),
(9, 7, 'Orange Juice', 6, 0, 'Freshly squeezed orange juice.', 'https://plus.unsplash.com/premium_photo-1667543228378-ec4478ab2845?q=80&w=2072&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'),
(10, 6, 'Chocolate Cake', 8, 0, 'Rich and moist chocolate cake.', 'https://plus.unsplash.com/premium_photo-1715015440855-7d95cf92608a?q=80&w=988&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'),
(11, NULL, 'Vegetable Soup', 9, 1, 'Hearty soup with fresh vegetables.', 'https://images.unsplash.com/photo-1665594051407-7385d281ad76?q=80&w=986&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'),
(12, 7, 'Ice Cream', 6.5, 0, 'Creamy vanilla ice cream.', 'https://plus.unsplash.com/premium_photo-1690440686714-c06a56a1511c?q=80&w=1364&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'),
(13, NULL, 'Fish and Chips', 16, 1, 'Crispy fish with french fries.', 'https://plus.unsplash.com/premium_photo-1694108747175-889fdc786003?q=80&w=987&auto-format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'),
(14, 4, 'Mineral Water', 3, 0, 'Pure natural mineral water.', 'https://images.unsplash.com/photo-1638688569176-5b6db19f9d2a?q=80&w=987&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'),
(15, 3, 'Fruit Salad', 7.5, 0, 'Fresh mix of seasonal fruits.', 'https://plus.unsplash.com/premium_photo-1664478279991-832059d65835?q=80&w=1480&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');

-- --------------------------------------------------------

--
-- Estrutura da tabela `stock`
--

CREATE TABLE `stock` (
  `id` int NOT NULL,
  `supplier_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` double NOT NULL,
  `unit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `stock`
--

INSERT INTO `stock` (`id`, `supplier_id`, `product_id`, `name`, `quantity`, `unit`) VALUES
(1, 1, NULL, 'Salt', 0, 'grams'),
(2, 1, NULL, 'Pepper', 934, 'grams'),
(3, 3, NULL, 'Lettuce', 2800, 'grams'),
(4, 3, NULL, 'Tomato', 1900, 'grams'),
(5, 2, 3, 'Coca-Cola', 19, 'units'),
(6, 4, 6, 'Pepsi', 80, 'units'),
(7, 7, 9, 'Orange Juice', 50, 'units'),
(8, 7, NULL, 'Cheese Slices', 199, 'units'),
(9, 3, NULL, 'Potatoes', 10000, 'grams'),
(10, 1, NULL, 'Cooking Oil', 5000, 'ml'),
(11, 5, NULL, 'Chicken Breast', 5360, 'grams'),
(12, 6, NULL, 'Croutons', 2000, 'grams'),
(13, 1, NULL, 'Caesar Dressing', 3000, 'ml'),
(14, 6, NULL, 'Pizza Dough', 100, 'units'),
(15, 7, NULL, 'Mozzarella Cheese', 5000, 'grams'),
(16, 1, NULL, 'Tomato Sauce', 4000, 'ml'),
(17, 6, NULL, 'Bread', 200, 'units'),
(18, 6, 10, 'Chocolate Cake', 30, 'units'),
(19, 7, 12, 'Ice Cream', 40, 'units'),
(20, 4, 14, 'Mineral Water', 120, 'units'),
(21, 5, 2, 'Beef Patty', 4850, 'grams');

-- --------------------------------------------------------

--
-- Estrutura da tabela `supplier`
--

CREATE TABLE `supplier` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nif` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_api` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `supplier`
--

INSERT INTO `supplier` (`id`, `name`, `fone`, `cel`, `email`, `address`, `nif`, `url_api`, `token`, `request_type`) VALUES
(1, 'Mercado Central', '(11) 3333-4444', '(11) 99999-8888', 'contato@mercadocentral.com', 'Rua Principal, 123, Centro, São Paulo, SP', '123456789', 'https://api.mercadocentral.com', 'abc123xyz-efsdfsfss-123456', 'GET'),
(2, 'Supermercado Bom Preço', '(11) 5555-6666', '(11) 99999-8888', 'contato@supermercadobompreco.com', 'Rua Secundária, 456, Centro, São Paulo, SP', '987654321', 'https://api.supermercadobompreco.com', 'def456uvw-efsdfsfss-654321', 'POST'),
(3, 'Hortifruti Natural', '(11) 7777-8888', '(11) 99999-8888', 'contato@hortifrutnatural.com', 'Rua Terceira, 789, Centro, São Paulo, SP', '456789123', 'https://api.hortifrutnatural.com', 'ghi789rst-efsdfsfss-789123', 'GET'),
(4, 'Distribuidora de Bebidas S.A.', '(11) 2222-3333', '(11) 98888-7777', 'vendas@distribuidorabebidas.com', 'Av. das Nações, 1000, Industrial, São Paulo, SP', '654321987', 'https://api.distribuidorabebidas.com', 'jkl012mno-efsdfsfss-321987', 'POST'),
(5, 'Frigorífico Prime Carne', '(11) 4444-5555', '(11) 97777-6666', 'compras@primecarne.com', 'Rua dos Abatedouros, 250, Zona Rural, São Paulo, SP', '789123456', 'https://api.primecarne.com', 'pqr345stu-efsdfsfss-789456', 'GET'),
(6, 'Panificadora Pão Quente', '(11) 6666-7777', '(11) 96666-5555', 'contato@paoquente.com', 'Rua dos Padeiros, 75, Centro, São Paulo, SP', '321654987', 'https://api.paoquente.com', 'vwx678yza-efsdfsfss-654987', 'POST'),
(7, 'Laticínios Leite Bom', '(11) 8888-9999', '(11) 95555-4444', 'sac@leitebom.com', 'Fazenda Santa Maria, KM 50, Rodovia SP-300, São Paulo, SP', '987123654', 'https://api.leitebom.com', 'bcd901efg-efsdfsfss-987654', 'GET'),
(8, 'Fornecedor Exemplo', '(11) 3333-4444', '(11) 99999-8888', 'fornecedor@exemplo.com', 'Rua Exemplo, 123, São Paulo - SP', '123.456.789-00', 'https://api.fornecedor.com', 'abc123token456', 'POST');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Índices para tabela `ingredient`
--
ALTER TABLE `ingredient`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6BAF78704584665A` (`product_id`),
  ADD KEY `IDX_6BAF7870DCD6110` (`stock_id`);

--
-- Índices para tabela `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `messages_sent`
--
ALTER TABLE `messages_sent`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Índices para tabela `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_52EA1F094584665A` (`product_id`),
  ADD KEY `IDX_52EA1F098D9F6D38` (`order_id`);

--
-- Índices para tabela `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D34A04AD2ADD6D8C` (`supplier_id`);

--
-- Índices para tabela `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_4B3656602ADD6D8C` (`supplier_id`),
  ADD KEY `IDX_4B3656604584665A` (`product_id`);

--
-- Índices para tabela `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `ingredient`
--
ALTER TABLE `ingredient`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `messages_sent`
--
ALTER TABLE `messages_sent`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `order`
--
ALTER TABLE `order`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `order_item`
--
ALTER TABLE `order_item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de tabela `product`
--
ALTER TABLE `product`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `ingredient`
--
ALTER TABLE `ingredient`
  ADD CONSTRAINT `FK_6BAF78704584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_6BAF7870DCD6110` FOREIGN KEY (`stock_id`) REFERENCES `stock` (`id`);

--
-- Limitadores para a tabela `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `FK_52EA1F094584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_52EA1F098D9F6D38` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`);

--
-- Limitadores para a tabela `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_D34A04AD2ADD6D8C` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`);

--
-- Limitadores para a tabela `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `FK_4B3656602ADD6D8C` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`),
  ADD CONSTRAINT `FK_4B3656604584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
