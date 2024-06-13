-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.27-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.7.0.6850
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for practical
CREATE DATABASE IF NOT EXISTS `practical` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `practical`;

-- Dumping structure for table practical.departments
CREATE TABLE IF NOT EXISTS `departments` (
  `department_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table practical.departments: ~15 rows (approximately)
INSERT INTO `departments` (`department_id`, `name`) VALUES
	(1, 'Electronics'),
	(2, 'Home Appliances'),
	(3, 'Fashion'),
	(4, 'Beauty and Personal Care'),
	(5, 'Sports and Outdoors'),
	(6, 'Toys and Games'),
	(7, 'Books and Stationery'),
	(8, 'Furniture and Home Decor'),
	(9, 'Garden and Patio'),
	(10, 'Automotive'),
	(11, 'Health and Wellness'),
	(12, 'Groceries and Gourmet Foods'),
	(13, 'Pet Supplies'),
	(14, 'Arts and Crafts'),
	(15, 'Jewelry and Watches'),
	(16, 'Other');

-- Dumping structure for table practical.merchant_messages
CREATE TABLE IF NOT EXISTS `merchant_messages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `product_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`message_id`),
  KEY `merchant_id` (`merchant_id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `merchant_messages_ibfk_2` FOREIGN KEY (`merchant_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `merchant_messages_ibfk_3` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table practical.merchant_messages: ~0 rows (approximately)
INSERT INTO `merchant_messages` (`message_id`, `merchant_id`, `admin_id`, `message`, `product_id`, `created_at`, `product_name`) VALUES
	(7, 18, 20, 'nO PROFILE PHOTO NOT LEGIT', 10, '2024-06-12 15:43:12', 'PISANGS');

-- Dumping structure for table practical.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','shipped','delivered') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table practical.orders: ~21 rows (approximately)
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
	(18, 12, 12000.00, 'pending', '2024-05-29 11:42:57'),
	(20, 19, 1862.84, 'pending', '2024-06-12 11:29:48'),
	(21, 19, 348484.80, 'pending', '2024-06-12 14:42:12'),
	(22, 19, 9990.00, 'shipped', '2024-06-13 08:13:56');

-- Dumping structure for table practical.order_items
CREATE TABLE IF NOT EXISTS `order_items` (
  `order_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table practical.order_items: ~25 rows (approximately)
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
	(22, 18, 6, 30, 400.00),
	(24, 20, 1, 2, 69.42),
	(25, 20, 2, 4, 44.00),
	(28, 22, 11, 10, 999.00);

-- Dumping structure for table practical.products
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`product_id`),
  KEY `merchant_id` (`merchant_id`),
  KEY `fk_department` (`department_id`),
  CONSTRAINT `fk_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`merchant_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table practical.products: ~8 rows (approximately)
INSERT INTO `products` (`product_id`, `merchant_id`, `name`, `description`, `price`, `stock`, `image_url`, `department_id`) VALUES
	(1, 8, 'Vis', 'Vis wat baie lekker is om te eet.', 69.42, 5, 'https://www.animalesrarosdelmundo.com/wp-content/uploads/2020/04/pez-mancha.jpg', NULL),
	(2, 8, 'Piesang', 'Bietjie Gross piesang', 44.00, 41, 'https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjLELuACSf-R3vzdl5zC-8eUbA8ODaluIB2gD-PTMpvdd24SHHoiU2UJcuI2siy1B-2J9LMGrDPSjaMT7rJKDLHj74lPQcHz8TZovlpZSP-P9OasIkrr2tqB6MKbN47KgVd2IqOprwCjL5c/s400/August+2008+025.jpg', NULL),
	(3, 8, 'Barbie', 'Cute barbie with hair', 256.00, 1, 'https://cdn.thisiswhyimbroke.com/buying-guides/54/weird-amazon-products.jpg', NULL),
	(4, 8, 'Puppy', 'Very cute puppy', 3000.00, 1, 'https://hips.hearstapps.com/hmg-prod/images/dog-puppy-on-garden-royalty-free-image-1586966191.jpg?crop=0.752xw:1.00xh;0.175xw,0&resize=1200:*', NULL),
	(5, 14, 'Dog Basket', 'Keep your dog save with this very human design for keep dogs OKAY', 387.00, 15, 'https://stayweird.com/wp-content/uploads/2023/03/weird_dog_products_dog_dryer.jpg', NULL),
	(6, 8, 'Vleis', 'Lekker Vleis', 400.00, 15, 'https://preview.redd.it/z0g6dy2utpl21.jpg?width=640&crop=smart&auto=webp&s=7dda4aa207a563db5b94bc7e86ebbee3a87f4495', NULL),
	(7, 15, 'Visstok', 'Goeie vistok', 999.00, 34, 'https://media.cnn.com/api/v1/images/stellar/prod/daiwa-samurai-spinning-combo-x.jpg?c=16x9&q=h_720,w_1280,c_fill', NULL),
	(11, 18, 'Slang Hare', 'Suil soos nooit tevore', 999.00, 40, 'https://preview.redd.it/snakes-with-cool-hair-v0-lqxlijvk4heb1.jpg?width=640&crop=smart&auto=webp&s=57d0b4aadd93a35286b545fc464cbace134f7b86', 4);

-- Dumping structure for table practical.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`session_id`),
  UNIQUE KEY `session_token` (`session_token`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table practical.sessions: ~3 rows (approximately)
INSERT INTO `sessions` (`session_id`, `user_id`, `session_token`, `expires_at`) VALUES
	(1, 18, '6c2b3412bd15400adfafb0fe6f02980709775e3b1751cdf01bb3f8ab8fe4ede8', '2024-06-13 09:14:36'),
	(2, 19, '5b475197b4bb1e83f8d7ecd631a57d701fbaaf8eeda616c296188dd04ec8fb78', '2024-06-13 09:11:40'),
	(3, 20, '30376a7eb0e8baeb7298f9e677b96ae47f2acc1c6ba9b9351bf3e6e8a23a95ff', '2024-06-13 09:17:32');

-- Dumping structure for table practical.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `wallet_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('deposit','payment') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`transaction_id`),
  KEY `wallet_id` (`wallet_id`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`wallet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table practical.transactions: ~0 rows (approximately)

-- Dumping structure for table practical.users
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','merchant','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table practical.users: ~14 rows (approximately)
INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `created_at`, `email`) VALUES
	(3, '', '', 'customer', '2024-05-28 10:45:34', NULL),
	(4, 'Piet', '', 'customer', '2024-05-28 10:46:13', NULL),
	(6, 'Gert', '$2y$10$WASQKYpfOiotuJrKXl3xOeETsj68FwZdYLKAlj3CJn6k8PjZbCGbe', 'customer', '2024-05-28 10:56:37', NULL),
	(7, 'Theo', '$2y$10$8J2OZw7QTr0QXvfOiOJTkupYwYbtZhDDowJ5N9MK6JzlkgBe8GQ4W', 'customer', '2024-05-28 11:09:38', NULL),
	(8, 'Elline', '$2y$10$C5S3qitff3rp9WmB7Ec4OOr2UZ64dgufkZhU.dcrF9Ftd9fbbaYoa', 'merchant', '2024-05-28 11:09:48', 'Elline@email.com'),
	(9, 'Beer', '$2y$10$Xco9Ya0h/3o6WQ/uiBZXx.xy8HJHbeYWS.OLQJUBI9/nSwzaVydzy', 'customer', '2024-05-28 11:21:42', NULL),
	(12, 'jan', '$2y$10$VLW4b44i5SCHC1skLUVYve0hkvJSTCyxYaPuv/uZVhX0kBErGhGhS', 'customer', '2024-05-28 12:00:11', NULL),
	(13, 'Stefan', '$2y$10$Tmmuwggx5hnhzU2.PssJu.Fw5BWaUh1w8nlAbnabSaRIhrDcyY/de', 'merchant', '2024-05-28 12:16:38', NULL),
	(14, 'Appel', '$2y$10$gmiCJP6WKiwsJTVVX9uvoeDBSuvIj55/nqxpZ9qX109gg53cL/5IG', 'merchant', '2024-05-28 14:15:22', NULL),
	(15, 'root', '$2y$10$27FK.v2d.ftP2wp5fsP7h.azyvKTQ9HqXsbkHvxbg7TXYhpQFUhVa', 'merchant', '2024-05-29 14:50:29', NULL),
	(17, 'Gor', '$2y$10$nYEpY0e3nOrgCYHRsWNV6OSn3alQ2ZkO2HKeVeiIGOlSAhvGGHADW', 'merchant', '2024-06-11 15:44:23', 'george@email.com'),
	(18, 'G', '$2y$10$znz5Wt726WEXv1XRdc9LA.rokF9Q9OJFeNioom9ZYIpXJ1K./S60a', 'merchant', '2024-06-12 10:34:36', 'G@G.com'),
	(19, 'Q', '$2y$10$9TlVNGxW/6KZGYoMhbyppels0qCe1PfNYzBVD1o9yOuCym6FaKHqe', 'customer', '2024-06-12 11:08:53', 'Q@Q.com'),
	(20, 'W', '$2y$10$MLpZb0BWfjh4txQhSuUO9.txxBEY/0KDdkIBJrsxA1lPaevYi2Lm2', 'admin', '2024-06-12 13:58:40', 'W@W.com');

-- Dumping structure for table practical.wallets
CREATE TABLE IF NOT EXISTS `wallets` (
  `wallet_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`wallet_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `wallets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table practical.wallets: ~2 rows (approximately)
INSERT INTO `wallets` (`wallet_id`, `user_id`, `balance`) VALUES
	(9, 9, 88910.44),
	(10, 12, 4087605.20),
	(11, 19, 9988146.16);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
