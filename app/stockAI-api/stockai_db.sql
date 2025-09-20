-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Tempo de geração: 20-Set-2025 às 16:08
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
-- Estrutura da tabela `product`
--

CREATE TABLE `product` (
  `id` int NOT NULL,
  `supplier_id` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `has_ingredients` tinyint(1) DEFAULT NULL,
  `description` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
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
(13, NULL, 'Fish and Chips', 16, 1, 'Crispy fish with french fries.', 'https://plus.unsplash.com/premium_photo-1694108747175-889fdc786003?q=80&w=987&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'),
(14, 4, 'Mineral Water', 3, 0, 'Pure natural mineral water.', 'https://images.unsplash.com/photo-1638688569176-5b6db19f9d2a?q=80&w=987&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'),
(15, 3, 'Fruit Salad', 7.5, 0, 'Fresh mix of seasonal fruits.', 'https://plus.unsplash.com/premium_photo-1664478279991-832059d65835?q=80&w=1480&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D34A04AD2ADD6D8C` (`supplier_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `product`
--
ALTER TABLE `product`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_D34A04AD2ADD6D8C` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
