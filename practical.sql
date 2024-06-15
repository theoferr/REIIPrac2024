-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 15, 2024 at 07:21 PM
-- Server version: 10.5.20-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id22313481_practical`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `merchant_messages`
--

CREATE TABLE `merchant_messages` (
  `message_id` int(11) NOT NULL,
  `merchant_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `product_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `merchant_messages`
--

INSERT INTO `merchant_messages` (`message_id`, `merchant_id`, `admin_id`, `message`, `product_id`, `created_at`, `product_name`) VALUES
(7, 18, 20, 'nO PROFILE PHOTO NOT LEGIT', 10, '2024-06-12 15:43:12', 'PISANGS'),
(8, 27, 20, 'Sorry but would not approve', 23, '2024-06-13 13:19:03', 'Andreas Poop'),
(9, 27, 20, 'Ek weet nie dis bietjie outa pocket volgens Jon', 27, '2024-06-14 07:42:40', 'Ek weet nie eers nie'),
(10, 43, 20, 'Camera does not exist, please check sources: https://en.wikipedia.org/wiki/Rickrolling', 30, '2024-06-15 18:37:40', 'Sony Alpha a1 Camera Body');

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
(9, 12, 138.84, 'shipped', '2024-05-28 13:56:32'),
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
(22, 19, 9990.00, 'shipped', '2024-06-13 08:13:56'),
(23, 19, 420000.00, 'pending', '2024-06-13 12:39:49'),
(24, 19, 69.42, 'pending', '2024-06-13 12:50:41'),
(25, 19, 40.00, 'shipped', '2024-06-13 12:56:19'),
(26, 29, 333.00, 'delivered', '2024-06-13 13:03:30'),
(27, 29, 17982.00, 'delivered', '2024-06-13 13:03:44'),
(28, 29, 11997000.00, 'delivered', '2024-06-13 13:05:54'),
(29, 19, 11880.00, 'pending', '2024-06-13 13:12:44'),
(30, 19, 15999.00, 'pending', '2024-06-13 13:13:53'),
(31, 19, 3980.00, 'delivered', '2024-06-13 13:14:48'),
(32, 12, 44.00, 'pending', '2024-06-13 17:01:35'),
(33, 19, 69.42, 'pending', '2024-06-14 11:12:21'),
(34, 30, 80.00, 'pending', '2024-06-14 11:22:31'),
(35, 19, 256.00, 'pending', '2024-06-15 12:15:52'),
(36, 41, 558.00, 'pending', '2024-06-15 12:36:49'),
(37, 19, 1200.00, 'pending', '2024-06-15 14:11:53'),
(38, 44, 304000.00, 'delivered', '2024-06-15 17:50:32'),
(39, 44, 152000.00, 'delivered', '2024-06-15 17:57:27'),
(40, 44, 104000.00, 'delivered', '2024-06-15 18:35:57'),
(41, 44, 89998.00, 'delivered', '2024-06-15 18:38:24'),
(42, 44, 152000.00, 'pending', '2024-06-15 19:01:33');

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
(22, 18, 6, 30, 400.00),
(24, 20, 1, 2, 69.42),
(25, 20, 2, 4, 44.00),
(28, 22, 11, 10, 999.00),
(29, 23, 12, 1, 420000.00),
(30, 24, 1, 1, 69.42),
(31, 25, 13, 1, 40.00),
(32, 26, 15, 1, 333.00),
(33, 27, 15, 54, 333.00),
(34, 28, 17, 3999, 3000.00),
(35, 29, 21, 99, 120.00),
(36, 30, 20, 100, 159.99),
(37, 31, 22, 20, 199.00),
(38, 32, 2, 1, 44.00),
(39, 33, 1, 1, 69.42),
(40, 34, 14, 1, 80.00),
(41, 35, 3, 1, 256.00),
(42, 36, 14, 2, 80.00),
(43, 36, 24, 2, 199.00),
(44, 37, 6, 3, 400.00),
(45, 38, 32, 2, 152000.00),
(46, 39, 32, 1, 152000.00),
(47, 40, 28, 2, 52000.00),
(48, 41, 29, 2, 44999.00),
(49, 42, 32, 1, 152000.00);

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
  `image_url` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `merchant_id`, `name`, `description`, `price`, `stock`, `image_url`, `department_id`) VALUES
(1, 8, 'Vis', 'Vis wat baie lekker is om te eet.', 69.42, 3, 'https://www.animalesrarosdelmundo.com/wp-content/uploads/2020/04/pez-mancha.jpg', NULL),
(2, 8, 'Piesang', 'Bietjie Gross piesang', 44.00, 40, 'https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjLELuACSf-R3vzdl5zC-8eUbA8ODaluIB2gD-PTMpvdd24SHHoiU2UJcuI2siy1B-2J9LMGrDPSjaMT7rJKDLHj74lPQcHz8TZovlpZSP-P9OasIkrr2tqB6MKbN47KgVd2IqOprwCjL5c/s400/August+2008+025.jpg', NULL),
(3, 8, 'Barbie', 'Cute barbie with hair', 256.00, 0, 'https://cdn.thisiswhyimbroke.com/buying-guides/54/weird-amazon-products.jpg', NULL),
(4, 8, 'Puppy', 'Very cute puppy', 3000.00, 1, 'https://hips.hearstapps.com/hmg-prod/images/dog-puppy-on-garden-royalty-free-image-1586966191.jpg?crop=0.752xw:1.00xh;0.175xw,0&resize=1200:*', NULL),
(5, 14, 'Dog Basket', 'Keep your dog save with this very human design for keep dogs OKAY', 387.00, 15, 'https://stayweird.com/wp-content/uploads/2023/03/weird_dog_products_dog_dryer.jpg', NULL),
(6, 8, 'Vleis', 'Lekker Vleis', 400.00, 12, 'https://preview.redd.it/z0g6dy2utpl21.jpg?width=640&crop=smart&auto=webp&s=7dda4aa207a563db5b94bc7e86ebbee3a87f4495', NULL),
(7, 15, 'Visstok', 'Goeie vistok', 999.00, 34, 'https://media.cnn.com/api/v1/images/stellar/prod/daiwa-samurai-spinning-combo-x.jpg?c=16x9&q=h_720,w_1280,c_fill', NULL),
(11, 18, 'Slang Hare', 'Suil soos nooit tevore', 999.00, 9999, 'https://preview.redd.it/snakes-with-cool-hair-v0-lqxlijvk4heb1.jpg?width=640&crop=smart&auto=webp&s=57d0b4aadd93a35286b545fc464cbace134f7b86', 4),
(12, 8, 'Elon Mah', 'Errow ehery one. Mah name Elon Mah', 420000.00, 1, 'https://img-9gag-fun.9cache.com/photo/aBn47Wz_460s.jpg', 3),
(13, 18, 'Goofy Soup', 'Known to make one wise, full and enter into new dimensions.', 40.00, 29, 'https://lh3.googleusercontent.com/u/0/drive-viewer/AKGpihZUdPfwEsW5FGD-rzbP4rz_sAeRBVTGXThAleKn0y4jiNnXzRpbNYs-Jpn9398QrduJ65uDYyF7jcydO_L2x3xct7WIgrMVcfM=w1920-h947-rw-v1', 11),
(14, 26, 'Red Paint', 'Use this to paint yourself into corners and make your life more colorful.', 80.00, 17, 'https://arpmanet.cloudns.ph/REII414/.corner.jpg', 14),
(15, 27, 'Ouma Asem', 'Asem for your ouma', 333.00, 0, 'https://i.pinimg.com/236x/be/91/17/be911784ad625e30bbf35bdce55808cf.jpg', 2),
(16, 18, 'Harambe', 'Quick kid before they come you have to know....', 2016.00, 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSBW59bDarWyyEYP1FvWaBq4N6YgWsvnvKflw&s', 5),
(17, 27, 'Ouma Asem', 'Asem vir jou ouma', 3000.00, 1, 'https://i.pinimg.com/236x/be/91/17/be911784ad625e30bbf35bdce55808cf.jpg', 5),
(18, 26, 'The 80\'s', 'Never gonna give you up\r\nNever gonna let you down', 1987.00, 300, 'https://i.pinimg.com/originals/89/5c/e7/895ce751ba0379700381d17a67086931.gif', 4),
(19, 27, 'Vinger Spinnekop', 'Vir lekker kop scratches', 600.00, 5000, 'https://m.media-amazon.com/images/I/4139QzhQ3uL._SS400_.jpg', 11),
(20, 18, 'AQKILO® Squirrel Finger Puppet Set', 'Top reviews from the United States\r\n	Amazon Customer\r\n5.0 out of 5 stars Squirrel!! Fun gag gift.\r\nReviewed in the United States on April 23, 2024\r\nVerified Purchase\r\nAQKILO® Squirrel Finger Puppet Set is a fun little novelty gatchet to fiddle with, cats also found it entertaining. Stretches nicely for a good fit, except littles.\r\nHelpful\r\nReport\r\n	Mary Spurlin\r\n5.0 out of 5 stars Prize\r\nReviewed in the United States on April 20, 2024\r\nVerified Purchase\r\nReally nice and funny\r\nHelpful\r\nReport\r\n	Mimiof3\r\n5.0 out of 5 stars So cute - perfect white elephant gift!\r\nReviewed in the United States on January 2, 2024\r\nVerified Purchase\r\nPurchased this for a family white elephant gift exchange and it got so many laughs! The quality is surprising good and it’s made of a flexible silicone-like product. Side note - don’t drink alcohol and stick it on your face because it WILL leave deep red suction marks', 159.99, 0, 'https://m.media-amazon.com/images/I/81y2kuGAntL.__AC_SX300_SY300_QL70_FMwebp_.jpg', 14),
(21, 18, 'Sister Amy Anatomy Bathing Suit', 'Product details\r\nDepartment ‏ : ‎ womens\r\nDate First Available ‏ : ‎ August 7, 2021\r\nASIN ‏ : ‎ B09C37RFJY\r\nBest Sellers Rank: #1,041,342 in Clothing, Shoes & Jewelry', 120.00, 0, 'https://m.media-amazon.com/images/I/71vxpohpACL._AC_SX425_.jpg', 3),
(22, 18, 'Alpaca Breathable Panties', 'Okay, this might not be your first choice pair of undies in the AM, but it makes a hilarious bridal shower gift.', 199.00, 0, 'https://hips.hearstapps.com/vader-prod.s3.amazonaws.com/1565357899-41cvNzV6pHL.jpg?crop=1xw:1.00xh;center,top&resize=980:*', 3),
(24, 18, 'Dinosuarus ', 'Tea time doesn\'t get any cutter than this.', 199.00, 1998, 'https://m.media-amazon.com/images/I/717MvjMEKoL._AC_SY450_.jpg', 6),
(25, 18, 'But... You\'re a Horse', 'From internet mischief-maker David Bussell comes, But... You\'re a Horse.', 200.00, 200, 'https://m.media-amazon.com/images/I/51Cszt-dhuL._SY445_SX342_.jpg', 7),
(26, 18, 'Stained Underware Wallet', 'Store your fortunes in a place that absolutely no one, not a single soul, would ever think of or even dare to look.', 1599.00, 100, 'https://cdn.thisiswhyimbroke.com/buying-guides-thumb/54/stained-underwear-wallet_400x.jpg', 3),
(28, 43, 'Sony a7 IV Camera Body', 'An all-arounder that pushes beyond basic, the Sony a7 IV does double duty with strong stills and video performance. An advanced hybrid mirrorless camera, the a7 IV has the resolution and AF performance that appeals to photographers along with robust 4K 60p video recording for filmmakers and content creators.', 52000.00, 13, 'https://www.fotodiscountworld.co.za/wp-content/uploads/2022/10/sony-a7-1-1200x1200.jpg', 1),
(29, 43, 'Sony FX30 Camera Body', 'Leveraging the power of a newly-developed APS-C sensor, the Sony FX30 provides everyday content creators and aspiring filmmakers with a powerful, yet accessible cinema camera with which to push their cinematic journey to new heights. It blends an imaging pipeline designed for cinematic capture with a comprehensive feature set and intuitive operation to create a complete system for all levels of filmmaking.', 44999.00, 18, 'https://www.fotodiscountworld.co.za/wp-content/uploads/2023/07/sony-fx30.jpg', 1),
(31, 43, 'Sony Alpha a7C II Camera Body', 'Building off the sleek profile and full-frame sensor combination of its first-generation predecessor, the black Sony a7C II offers improved resolution, autofocus, in-body image stabilization, and video capabilities in the same compact, all-day, everyday form factor.', 51899.00, 20, 'https://www.fotodiscountworld.co.za/wp-content/uploads/2023/09/Sony-Alpha-a7C-II.jpg', 1),
(32, 43, 'Sony a9 iii Camera Body', 'BIONZ XR™ image processing engine\r\nWith up to eight times7 more processing power than previous versions, the BIONZ XR image processing engine minimises processing latency while markedly boosting image processing power. The high volume of data generated by the newly developed Exmor RS™ image sensor can be processed in real time even while shooting continuous bursts at up to 120 fps4. This significantly improves image quality, from offering higher colour gradation and more realistic colour reproduction to reducing image noise. The camera can also capture high-quality 14-bit RAW images in all still shooting modes.', 152000.00, 0, 'https://www.sony-mea.com/image/c210434dd3b79277a35840044d23e669?fmt=pjpeg&wid=1014&hei=396&bgcolor=F1F5F9&bgc=F1F5F9', 1),
(33, 43, 'Sony a1 Camera Body', 'Sony continues to challenge the limits of conventional imaging tools with innovative technology that delivers an unprecedented combination of resolution and speed plus intuitive operation. The α1 offers new dimensions of imaging performance as well as efficient workflow, giving the user new creative freedom.', 152599.00, 7, 'https://www.fotodiscountworld.co.za/wp-content/uploads/2023/05/SONY-A1.jpg', 1);

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`session_id`, `user_id`, `session_token`, `expires_at`) VALUES
(1, 18, '823a86ad5fefd9baa3e2f086153d0cbe1319e5210bb6e9b21fee0597a254bef7', '2024-06-15 12:57:43'),
(2, 19, '8860a9cc7ebe4d67b18fbafbbd8c8635c158d8696d0875975f89ab6b8b3b1b72', '2024-06-15 18:04:50'),
(3, 20, '58744487e725c14cf97e986eff51bcb1d5d4b4e2bebe9a1745f9081bbaf18438', '2024-06-15 19:36:54'),
(4, 21, 'e0ac3f19dc636002e64d2621111840e22e69d654f2dc512591ee8c9591eea5b1', '2024-06-13 12:53:42'),
(5, 22, '2232892939d58bbe7132306ccc5c83dbb6ef2af001551cf636a4f45cfa98c07b', '2024-06-13 13:07:12'),
(6, 8, 'b56debc583d04d0625a366920292b229a2a70ecf7ab515ed07063f627ab08bde', '2024-06-15 15:10:29'),
(7, 24, 'b5c125caf83312114c91ad9450ee8ff6c657c1e49e1eb7375e049d272c3e5ab0', '2024-06-13 12:52:39'),
(8, 25, '96f3eeb91339382b98428bc7deabcfaa345c16224911ebb24aa5b78c27a32a6f', '2024-06-13 13:56:08'),
(9, 26, '03baefb0bdc28ad6a9b3ea1c29813d0aa2f16d573ee3b68bae3f088c974f20a8', '2024-06-13 14:05:04'),
(10, 27, '0e7f556cf6e3d26355f9507df8f0c5bccbb91b090cfa1a9f3f7d1cad49e4b9bc', '2024-06-15 15:09:14'),
(11, 28, 'e018596b961893d397187b903dc1cc78881d5825cdbf401f1a0a2da9a9e49da7', '2024-06-13 13:59:14'),
(12, 29, '23c796b8cabb06e71e2e9aaee045f96363429cc8f4c9e62a217f755afdf953f4', '2024-06-13 14:03:05'),
(13, 30, 'c5144e36f02ff18b8185aa94aa41b206413e6afc051025f8f359ca0f7426cb03', '2024-06-15 13:52:49'),
(14, 32, 'cb900f2f50cc4c657af48cc5f5e50bbd141c43359e43823fe0b873fa0e06a91d', '2024-06-13 14:12:59'),
(15, 12, 'a2ba36b354694c29c6ea37c47477fc7f60e165a021adf4fcadf64b6b89e03ad8', '2024-06-13 18:00:58'),
(16, 40, '642dc8651af918c577456a8be8bb98d4f7c7347f8c5974090c324dd7af49881e', '2024-06-15 13:31:44'),
(17, 41, '6f34d52f2cf702b5e411eb9e36731d67c46ee38ed9ee149aec8f55d7741d6d1b', '2024-06-15 13:35:27'),
(18, 42, 'd81e0a42621ea198b1c224ed40b01eae637a59bb68f33a8caa561942dffacc61', '2024-06-15 18:26:17'),
(19, 43, 'a573562f1b37b7951e28ffc59be6aa129d6f9dfe8f1ba6955ec355b6d7d0bfd2', '2024-06-15 20:02:11'),
(20, 44, '89a1db5b4b5feca38fe57bf155b86533715f5f82b78129cd164d190d7f2b0ff6', '2024-06-15 20:01:19');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

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
(20, 'W', '$2y$10$MLpZb0BWfjh4txQhSuUO9.txxBEY/0KDdkIBJrsxA1lPaevYi2Lm2', 'admin', '2024-06-12 13:58:40', 'W@W.com'),
(21, 'K', '$2y$10$sy/CSriAV.VnKAvw4Dcl4.2zIDuy08Nz6Y0/FjUdz84bC3Ne2RHgi', 'customer', '2024-06-13 11:25:22', 'K@K.com'),
(22, 'A', '$2y$10$xpasUeW53sN7jL33n96P0uhfKimzGdJ5xf2TuL0Eu6kaFlKelqREm', 'admin', '2024-06-13 11:45:30', 'A@A.com'),
(24, 'Pieter', '$2y$10$gWjjXx3zyXVTIp/tvuBj3eBoG2Nz55w3XE74GbT3xw867vg5nv8ZS', 'customer', '2024-06-13 11:51:57', 'SlegDemi@gmail.com'),
(25, 'T', '$2y$10$M9IxRrJ6VUAphQrj4stZg.uvN6mYSEgj58q//e7VUtcdy0.tjw3Xy', 'customer', '2024-06-13 12:55:54', 'T@T.com'),
(26, 'PSnoek', '$2y$10$x84W/YQFTEG.P6zFp3VfIuHzuz0Sib04mf1.X7kRyUS0WjbCKfdN.', 'merchant', '2024-06-13 12:58:36', 'P@S.com'),
(27, 'E', '$2y$10$4Lkppb7.pXJM5T.6cL8gm.IxML4p51dPbDSeiYkKJymnxS8qAlp0i', 'merchant', '2024-06-13 12:58:50', 'E@E.com'),
(28, 'Erik', '$2y$10$5IiRQkyY4aCRP0WYRiUSO.J.a4ITsZGJBix7EgskbI0nUBVJCM9pe', 'customer', '2024-06-13 12:59:06', 'erikfourie.beekman@gmail.com'),
(29, 'F', '$2y$10$ABFXibRoP980.AFS/lOFXuDenqwDTKbmqMWhFOGG1H79Xnm6.5LDi', 'customer', '2024-06-13 13:02:58', 'F@F.com'),
(30, 'B', '$2y$10$SqJ2dKMWFpdpHAuLjBKPCOyAllwaytVkquA.FbUuewwfubiZPIije', 'customer', '2024-06-13 13:03:36', 'B@B.com'),
(31, 'ee', '$2y$10$8HaAdRsvj866MOSZ3h1w7OyH1Cni7ym5xUaCgPvoiT/pZ6LEIqqS6', 'merchant', '2024-06-13 13:09:37', 'ee@gmail.com'),
(32, 'ee1', '$2y$10$1Yv1L1hbcUm/fmClybhJ6.yOdh3Cb4WQ1Sop8RnBBfq54vHQNk8Ry', 'customer', '2024-06-13 13:10:07', 'ee1@gmail.com'),
(33, 'Jonathan', '$2y$10$lxH5ldcCcKNQUDlLqhM4nuZDUSHqewq8g7zVWQHflGcTOiF3P0g4u', 'customer', '2024-06-15 12:18:54', 'lemonjonathanvorster@gmail.com'),
(34, 'asd', '$2y$10$ukuvWT08CQQK80x/g6nn9uOY8N.EQXAAsmhwFzp6dJ2P3x8efAeFq', 'customer', '2024-06-15 12:19:28', 'asd@asd.com'),
(35, 'aUser', '$2y$10$6jM/8xva6mrVSeSL1.V/tO5suhi.8lOC2hFowyftqUbTKKXJEmt7S', 'customer', '2024-06-15 12:22:20', 'auser@a.com'),
(36, '1', '$2y$10$7MP8vs6D6983UtKPZN2dvuq.Bm4iaoKVvSO.6MrHJsPp1es7JrYWO', 'customer', '2024-06-15 12:25:13', '1@1.com'),
(37, 'D', '$2y$10$pgEFCBSoMb1Rupq08LzNqO.M73KVihi7nphKAJXAe5JrG7ylGKUnC', 'customer', '2024-06-15 12:26:26', 'D@D.com'),
(38, 'N', '$2y$10$JYZsHvkvc9Hj30DEbA1SjO/PiAw.qR1qXS2N19GInvHerS62CVDiy', 'customer', '2024-06-15 12:29:54', 'N@N.n'),
(40, 'OKAY', '$2y$10$hCkVstG3Sw6fgBk9YI8D5.CV7kAqsa6DBgq5oygTtk145LP5V1szK', 'merchant', '2024-06-15 12:31:28', 'OKAY@O.kay'),
(41, 'MrBeast', '$2y$10$I2VVI9Gd5rH4ppXtgOFbtOhC5w1raqm.5TQz0apQPoZXtD3kSGuGW', 'customer', '2024-06-15 12:35:11', 'm@b.com'),
(42, 'George', '$2y$10$3QNmVTFbr5E.Edx8cFMeP.1/c.avyyXUQ1izAi6Xbv7NN1KIqhQkq', 'customer', '2024-06-15 12:46:33', 'george@nomail.com'),
(43, 'Seller', '$2y$10$g7CZSJ.g1qyUGdQ5aGPk2.jppDWz7EX4Z7ThwrmbdYWSrLJXZBKr6', 'merchant', '2024-06-15 17:27:11', 'sell@gmail.com'),
(44, 'Buyer', '$2y$10$xNw525Ds/lDNMUDhJeFqDuCmdcWBvhQu9jnlb4OTMuCAlyXwsOMVy', 'customer', '2024-06-15 17:45:35', 'Buy@gmail.com');

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
(10, 12, 4087561.20),
(11, 19, 10566669.32),
(12, 25, 10000000.00),
(13, 29, 184684.00),
(14, 30, 4920.00),
(15, 3, 0.00),
(16, 22, 0.00),
(17, 14, 0.00),
(18, 27, 0.00),
(19, 31, 0.00),
(20, 32, 0.00),
(21, 8, 0.00),
(22, 28, 0.00),
(23, 18, 0.00),
(24, 6, 0.00),
(25, 17, 0.00),
(26, 21, 0.00),
(27, 4, 0.00),
(28, 24, 0.00),
(29, 26, 0.00),
(30, 15, 0.00),
(31, 13, 0.00),
(32, 7, 0.00),
(33, 20, 0.00),
(46, 33, 0.00),
(47, 34, 0.00),
(48, 35, 0.00),
(49, 36, 0.00),
(50, 37, 0.00),
(51, 38, 0.00),
(52, 40, 0.00),
(53, 41, 42.00),
(54, 42, 0.00),
(55, 43, 39998.00),
(56, 44, 1204002.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `merchant_messages`
--
ALTER TABLE `merchant_messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `admin_id` (`admin_id`);

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
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `fk_department` (`department_id`);

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
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `merchant_messages`
--
ALTER TABLE `merchant_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `wallet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `merchant_messages`
--
ALTER TABLE `merchant_messages`
  ADD CONSTRAINT `merchant_messages_ibfk_2` FOREIGN KEY (`merchant_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `merchant_messages_ibfk_3` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`);

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
  ADD CONSTRAINT `fk_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
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
