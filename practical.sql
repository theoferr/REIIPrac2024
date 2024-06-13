-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2024 at 11:24 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00"; 


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `practical`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','shipped','delivered') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total`, `status`, `created_at`) VALUES
(1, 9, 870.20, 'delivered', '2024-05-28 11:55:12'),
(2, 9, 555.36, 'shipped', '2024-05-28 11:55:31'),
(3, 12, 409.68, 'delivered', '2024-05-28 12:05:42'),
(4, 12, 1318.98, 'shipped', '2024-05-28 12:09:18'),
(5, 12, 460.52, 'delivered', '2024-05-28 13:47:03'),
(6, 12, 208.26, 'shipped', '2024-05-28 13:51:39'),
(7, 12, 208.26, 'delivered', '2024-05-28 13:52:33'),
(8, 12, 208.26, 'pending', '2024-05-28 13:53:00'),
(9, 12, 138.84, 'pending', '2024-05-28 13:56:32'),
(10, 12, 6512.00, 'pending', '2024-05-28 14:04:44'),
(11, 12, 256.00, 'pending', '2024-05-28 14:20:07'),
(12, 12, 2000.00, 'pending', '2024-05-28 14:24:05'),
(13, 12, 1548.00, 'pending', '2024-05-28 14:26:54'),
(14, 12, 2000.00, 'pending', '2024-05-28 14:53:01'),
(15, 12, 256.00, 'pending', '2024-05-28 15:17:15'),
(16, 12, 1935.00, 'pending', '2024-05-28 15:17:50'),
(17, 12, 1935.00, 'pending', '2024-05-28 15:18:29'),
(18, 12, 12000.00, 'pending', '2024-05-29 11:42:57');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 10, 69.42),
(2, 1, 2, 4, 44.00),
(3, 2, 1, 8, 69.42),
(4, 3, 1, 4, 69.42),
(5, 3, 2, 3, 44.00),
(6, 4, 1, 19, 69.42),
(7, 5, 1, 6, 69.42),
(8, 5, 2, 1, 44.00),
(9, 6, 1, 3, 69.42),
(10, 7, 1, 3, 69.42),
(11, 8, 1, 3, 69.42),
(12, 9, 1, 2, 69.42),
(13, 10, 3, 2, 256.00),
(14, 10, 4, 2, 3000.00),
(15, 11, 3, 1, 256.00),
(16, 12, 6, 5, 400.00),
(17, 13, 5, 4, 387.00),
(18, 14, 6, 5, 400.00),
(19, 15, 3, 1, 256.00),
(20, 16, 5, 5, 387.00),
(21, 17, 5, 5, 387.00),
(22, 18, 6, 30, 400.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `merchant_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `merchant_id`, `name`, `description`, `price`, `stock`, `image_url`) VALUES
(1, 8, 'Vis', 'Vis wat baie lekker is om te eet.', 69.42, 7, 'https://www.animalesrarosdelmundo.com/wp-content/uploads/2020/04/pez-mancha.jpg'),
(2, 8, 'Piesang', 'Bietjie Gross piesang', 44.00, 45, 'https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjLELuACSf-R3vzdl5zC-8eUbA8ODaluIB2gD-PTMpvdd24SHHoiU2UJcuI2siy1B-2J9LMGrDPSjaMT7rJKDLHj74lPQcHz8TZovlpZSP-P9OasIkrr2tqB6MKbN47KgVd2IqOprwCjL5c/s400/August+2008+025.jpg'),
(3, 8, 'Barbie', 'Cute barbie with hair', 256.00, 1, 'https://cdn.thisiswhyimbroke.com/buying-guides/54/weird-amazon-products.jpg'),
(4, 8, 'Puppy', 'Very cute puppy', 3000.00, 1, 'https://hips.hearstapps.com/hmg-prod/images/dog-puppy-on-garden-royalty-free-image-1586966191.jpg?crop=0.752xw:1.00xh;0.175xw,0&resize=1200:*'),
(5, 14, 'Dog Basket', 'Keep your dog save with this very human design for keep dogs OKAY', 387.00, 19, 'https://stayweird.com/wp-content/uploads/2023/03/weird_dog_products_dog_dryer.jpg'),
(6, 8, 'Vleis', 'Lekker Vleis', 400.00, 15, 'https://preview.redd.it/z0g6dy2utpl21.jpg?width=640&crop=smart&auto=webp&s=7dda4aa207a563db5b94bc7e86ebbee3a87f4495'),
(7, 15, 'Visstok', 'Goeie vistok', 999.00, 34, 'https://media.cnn.com/api/v1/images/stellar/prod/daiwa-samurai-spinning-combo-x.jpg?c=16x9&q=h_720,w_1280,c_fill');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `session_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `wallet_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('deposit','payment') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','merchant','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `created_at`) VALUES
(3, '', '', 'customer', '2024-05-28 10:45:34'),
(4, 'Piet', '', 'customer', '2024-05-28 10:46:13'),
(6, 'Gert', '$2y$10$WASQKYpfOiotuJrKXl3xOeETsj68FwZdYLKAlj3CJn6k8PjZbCGbe', 'customer', '2024-05-28 10:56:37'),
(7, 'Theo', '$2y$10$8J2OZw7QTr0QXvfOiOJTkupYwYbtZhDDowJ5N9MK6JzlkgBe8GQ4W', 'customer', '2024-05-28 11:09:38'),
(8, 'Elline', '$2y$10$C5S3qitff3rp9WmB7Ec4OOr2UZ64dgufkZhU.dcrF9Ftd9fbbaYoa', 'merchant', '2024-05-28 11:09:48'),
(9, 'Beer', '$2y$10$Xco9Ya0h/3o6WQ/uiBZXx.xy8HJHbeYWS.OLQJUBI9/nSwzaVydzy', 'customer', '2024-05-28 11:21:42'),
(12, 'jan', '$2y$10$VLW4b44i5SCHC1skLUVYve0hkvJSTCyxYaPuv/uZVhX0kBErGhGhS', 'customer', '2024-05-28 12:00:11'),
(13, 'Stefan', '$2y$10$Tmmuwggx5hnhzU2.PssJu.Fw5BWaUh1w8nlAbnabSaRIhrDcyY/de', 'merchant', '2024-05-28 12:16:38'),
(14, 'Appel', '$2y$10$gmiCJP6WKiwsJTVVX9uvoeDBSuvIj55/nqxpZ9qX109gg53cL/5IG', 'merchant', '2024-05-28 14:15:22'),
(15, 'root', '$2y$10$27FK.v2d.ftP2wp5fsP7h.azyvKTQ9HqXsbkHvxbg7TXYhpQFUhVa', 'merchant', '2024-05-29 14:50:29');

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `wallet_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`wallet_id`, `user_id`, `balance`) VALUES
(9, 9, 88910.44),
(10, 12, 4087605.20);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `merchant_id` (`merchant_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD UNIQUE KEY `session_token` (`session_token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `wallet_id` (`wallet_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`wallet_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `wallet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`merchant_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`wallet_id`);

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
