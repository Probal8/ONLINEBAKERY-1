-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 11, 2026 at 06:05 AM
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
-- Database: `baking_valley`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(18, 4, 12, 4),
(29, 5, 12, 2),
(30, 5, 9, 1),
(31, 5, 8, 1),
(32, 5, 10, 1),
(33, 5, 5, 1),
(34, 5, 4, 1),
(35, 5, 6, 1),
(36, 5, 14, 1),
(40, 9, 16, 1),
(41, 9, 15, 1),
(57, 15, 16, 1),
(58, 15, 14, 1),
(59, 13, 16, 1),
(60, 13, 15, 1),
(97, 22, 14, 8),
(98, 22, 17, 4),
(99, 22, 10, 1),
(100, 22, 11, 1),
(101, 25, 14, 9),
(102, 25, 11, 2),
(103, 25, 12, 3),
(104, 25, 13, 1),
(105, 25, 17, 3),
(106, 25, 5, 2),
(107, 25, 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `delivery_charge` int(11) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `trx_id` varchar(100) DEFAULT NULL,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `customer_name`, `total_amount`, `status`, `created_at`, `order_date`, `phone`, `address`, `delivery_charge`, `payment_method`, `trx_id`, `note`) VALUES
(1, 7, 'priyonto saha', 500.00, 'Delivered', '2026-01-13 09:51:23', '2026-01-13 18:39:34', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 7, 'priyonto saha', 500.00, 'Cancelled', '2026-01-13 14:50:35', '2026-01-13 18:39:34', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 7, 'priyonto saha', 200.00, 'Pending', '2026-01-13 18:16:31', '2026-01-13 18:39:34', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 7, 'priyonto saha', 600.00, 'Pending', '2026-01-13 18:31:00', '2026-01-13 18:39:34', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 7, 'priyonto saha', 300.00, 'Pending', '2026-01-13 18:32:02', '2026-01-13 18:39:34', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 7, 'priyonto saha', 950.00, 'Pending', '2026-01-13 18:36:42', '2026-01-13 18:39:34', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 7, 'priyonto saha', 35.00, 'Pending', '2026-01-13 18:43:21', '2026-01-13 18:43:21', NULL, NULL, NULL, NULL, NULL, NULL),
(8, 7, 'priyonto saha', 250.00, 'Pending', '2026-01-13 18:48:13', '2026-01-13 18:48:13', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 7, 'priyonto saha', 250.00, 'Pending', '2026-01-13 18:48:30', '2026-01-13 18:48:30', NULL, NULL, NULL, NULL, NULL, NULL),
(10, 4, 'Mostafizur Rahman', 4105.00, 'Delivered', '2026-01-19 11:52:12', '2026-01-19 11:52:12', NULL, NULL, NULL, NULL, NULL, NULL),
(11, 4, 'Mostafizur Rahman', 600.00, 'Pending', '2026-01-19 12:11:50', '2026-01-19 12:11:50', NULL, NULL, NULL, NULL, NULL, NULL),
(12, 5, 'Abdur Rauf', 590.00, 'Delivered', '2026-01-19 15:24:39', '2026-01-19 15:24:39', NULL, NULL, NULL, NULL, NULL, NULL),
(13, 5, 'Abdur Rauf', 180.00, 'Pending', '2026-01-19 15:31:57', '2026-01-19 15:31:57', NULL, NULL, NULL, NULL, NULL, NULL),
(14, 5, 'Abdur Rauf', 760.00, 'Delivered', '2026-01-19 17:22:14', '2026-01-19 17:22:14', NULL, NULL, NULL, NULL, NULL, NULL),
(15, 6, 'Abdur Rauf', 200.00, 'Pending', '2026-01-19 19:16:14', '2026-01-19 19:16:14', NULL, NULL, NULL, NULL, NULL, NULL),
(16, 12, 'Md.Mostafizur Rahman', 1000.00, 'Pending', '2026-01-20 11:55:50', '2026-01-20 11:55:50', NULL, NULL, NULL, NULL, NULL, NULL),
(17, 12, 'Md.Mostafizur Rahman', 200.00, 'Pending', '2026-01-20 11:57:20', '2026-01-20 11:57:20', NULL, NULL, NULL, NULL, NULL, NULL),
(18, 12, 'Md.Mostafizur Rahman', 2500.00, 'Pending', '2026-01-20 12:04:15', '2026-01-20 12:04:15', NULL, NULL, NULL, NULL, NULL, NULL),
(19, 12, 'Md.Mostafizur Rahman', 400.00, 'Pending', '2026-01-20 12:06:29', '2026-01-20 12:06:29', NULL, NULL, NULL, NULL, NULL, NULL),
(20, 12, 'Md.Mostafizur Rahman', 200.00, 'Delivered', '2026-01-20 12:12:47', '2026-01-20 12:12:47', NULL, NULL, NULL, NULL, NULL, NULL),
(21, 14, 'Md.Mostafizur Rahman', 1000.00, 'Pending', '2026-01-20 18:22:41', '2026-01-20 18:22:41', NULL, NULL, NULL, NULL, NULL, NULL),
(22, 15, 'estiak', 700.00, 'Delivered', '2026-01-20 18:28:54', '2026-01-20 18:28:54', NULL, NULL, NULL, NULL, NULL, NULL),
(23, 16, 'Md.Mostafizur Rahman', 200.00, 'Pending', '2026-01-21 16:39:09', '2026-01-21 16:39:09', NULL, NULL, NULL, NULL, NULL, NULL),
(24, 18, 'Mostafizur Rahman', 200.00, 'Pending', '2026-01-21 18:33:27', '2026-01-21 18:33:27', NULL, NULL, NULL, NULL, NULL, NULL),
(25, 18, 'Mostafizur Rahman', 1635.00, 'Pending', '2026-01-22 06:44:21', '2026-01-22 06:44:21', NULL, NULL, NULL, NULL, NULL, NULL),
(26, 18, 'Mostafizur Rahman', 1260.00, 'Delivered', '2026-01-22 07:06:03', '2026-01-22 07:06:03', NULL, NULL, NULL, NULL, NULL, NULL),
(27, 19, 'Akram Hossian', 2600.00, 'Delivered', '2026-01-26 06:16:01', '2026-01-26 06:16:01', NULL, NULL, NULL, NULL, NULL, NULL),
(28, 1, 'Main Admin', 5685.00, 'Pending', '2026-02-06 19:48:46', '2026-02-06 19:48:46', NULL, NULL, NULL, NULL, NULL, NULL),
(29, 1, 'Main Admin', 1280.00, 'Pending', '2026-02-06 20:03:36', '2026-02-06 20:03:36', '01718926369', 'house no 95,rd 12,f block,Bashundura', 80, 'COD', NULL, ''),
(30, 22, 'Abu Hena', 1380.00, 'Pending', '2026-02-06 20:05:16', '2026-02-06 20:05:16', '01718926369', 'house 95,rd 12,f block,bashundhura', 80, 'COD', NULL, ''),
(31, 22, 'Abu Hena', 1920.00, 'Pending', '2026-02-07 11:07:11', '2026-02-07 11:07:11', '01718926369', 'house no 95,rd 12,f block,bashundhura,dhaka', 120, 'bKash', 'hjbfhhruvmkmgkikorth', ''),
(32, 22, 'Abu Hena', 480.00, 'Delivered', '2026-02-07 11:48:25', '2026-02-07 11:48:25', '01718926369', 'house no,rgb', 80, 'bKash', 'hjbfhhruvmkmgkikorth', ''),
(33, 22, 'Abu Hena', 1020.00, 'Pending', '2026-02-07 13:10:01', '2026-02-07 13:10:01', '01718926369', 'house no95,rd 12,f block,bashundara', 120, 'bKash', 'dhjagurmf', ''),
(34, 23, 'Md Mostafizur Rahman', 2220.00, 'Delivered', '2026-02-07 13:16:01', '2026-02-07 13:16:01', '01718926369', 'House no 95,rd 12,F block,Bashundara,Dhaka', 120, 'bKash', 'gdjsnjefu', 'lift 4 level 5');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 11, 6, 1, 600.00),
(2, 12, 10, 4, 35.00),
(3, 12, 14, 2, 200.00),
(4, 12, 12, 1, 50.00),
(5, 13, 12, 2, 50.00),
(6, 13, 13, 1, 80.00),
(7, 14, 12, 1, 50.00),
(8, 14, 13, 2, 80.00),
(9, 14, 14, 1, 200.00),
(10, 14, 15, 1, 200.00),
(11, 14, 9, 1, 150.00),
(12, 15, 14, 1, 200.00),
(13, 16, 14, 3, 200.00),
(14, 16, 15, 2, 200.00),
(15, 17, 14, 1, 200.00),
(16, 18, 14, 1, 200.00),
(17, 18, 17, 3, 600.00),
(18, 18, 16, 1, 500.00),
(19, 19, 15, 1, 200.00),
(20, 19, 14, 1, 200.00),
(21, 20, 14, 1, 200.00),
(22, 21, 16, 2, 500.00),
(23, 22, 16, 1, 500.00),
(24, 22, 15, 1, 200.00),
(25, 23, 14, 1, 200.00),
(26, 24, 14, 1, 200.00),
(27, 25, 11, 1, 170.00),
(28, 25, 10, 1, 35.00),
(29, 25, 15, 1, 200.00),
(30, 25, 16, 1, 500.00),
(31, 25, 17, 1, 600.00),
(32, 25, 13, 1, 80.00),
(33, 25, 12, 1, 50.00),
(34, 26, 12, 4, 50.00),
(35, 26, 13, 2, 80.00),
(36, 26, 15, 2, 200.00),
(37, 26, 16, 1, 500.00),
(38, 27, 17, 1, 600.00),
(39, 27, 16, 1, 500.00),
(40, 27, 19, 1, 1500.00),
(41, 28, 17, 4, 600.00),
(42, 28, 16, 1, 500.00),
(43, 28, 15, 4, 200.00),
(44, 28, 13, 1, 80.00),
(45, 28, 10, 1, 35.00),
(46, 28, 11, 1, 170.00),
(47, 28, 14, 1, 200.00),
(48, 28, 19, 1, 1500.00),
(49, 29, 15, 1, 200.00),
(50, 29, 16, 2, 500.00),
(51, 30, 15, 1, 200.00),
(52, 30, 16, 1, 500.00),
(53, 30, 17, 1, 600.00),
(54, 31, 14, 2, 200.00),
(55, 31, 15, 2, 200.00),
(56, 31, 16, 2, 500.00),
(57, 32, 17, 1, 400.00),
(58, 33, 16, 1, 500.00),
(59, 33, 15, 1, 200.00),
(60, 33, 14, 1, 200.00),
(61, 34, 16, 3, 500.00),
(62, 34, 15, 3, 200.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `old_price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `stock_status` varchar(20) DEFAULT 'In Stock'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `price`, `description`, `old_price`, `stock`, `image`, `is_deleted`, `stock_status`) VALUES
(5, 'Cake Mold', 'Tools', 300.00, NULL, NULL, 25, 'cakemold.jpeg', 0, 'In Stock'),
(6, 'Cake Board', 'Tools', 600.00, NULL, NULL, 30, 'cakeboard.jpeg', 0, 'In Stock'),
(7, 'Silicone Brash', 'Tools', 100.00, NULL, NULL, 40, 'sliconbrush.jpeg', 0, 'In Stock'),
(8, 'Silicone Spatula', 'Tools', 100.00, NULL, NULL, 25, 'sliconspatula.jpeg', 0, 'In Stock'),
(9, ' Butter Knife', 'Tools', 150.00, NULL, NULL, 15, 'butterknife.jpeg', 0, 'In Stock'),
(10, 'Nozzle', 'Tools', 35.00, NULL, NULL, 100, 'nojel.webp', 0, 'In Stock'),
(11, 'Piping Bag', 'Tools', 170.00, NULL, NULL, 260, 'pipingbag.jpeg', 0, 'In Stock'),
(12, 'Rose Stand', 'Tools', 50.00, NULL, NULL, 140, 'rosestand.jpg', 0, 'In Stock'),
(13, 'Kitchen Scale', 'Tools', 80.00, NULL, NULL, 130, 'kitchenscale.webp', 0, 'In Stock'),
(14, 'Corn Flour', 'Cream', 200.00, NULL, NULL, 50, 'cornflour.jpeg', 1, 'In Stock'),
(15, 'Turn Table', 'Tools', 100.00, NULL, 200.00, 150, 'turntable.jpeg', 0, 'Out of Stock'),
(16, 'Chocolate Cake', '', 350.00, NULL, NULL, 20, '1768854640_chocolate.jpg', 0, 'Out of Stock'),
(17, 'Vanilla Cake', 'Cakes', 300.00, NULL, 400.00, 25, '1768854674_Vanilla.webp', 0, 'In Stock'),
(19, 'Black Forest', 'Cake', 1500.00, NULL, NULL, 20, '1769069783_black-forest-cake-7.webp', 1, 'In Stock'),
(20, 'Vanilla Cake', 'Cakes', 300.00, NULL, NULL, 0, 'Vanilla.webp', 1, 'In Stock'),
(21, 'Handcake', 'Cakes', 400.00, NULL, NULL, 0, 'handcake.jpeg', 1, 'In Stock'),
(22, 'Handcake', 'Cakes', 300.00, NULL, 400.00, 0, 'handcake.jpeg', 1, 'In Stock'),
(23, 'Handcake', 'Uncategorized', 400.00, NULL, NULL, 0, 'handcake.jpeg', 1, 'In Stock'),
(24, 'Chocolate', 'Uncategorized', 200.00, NULL, NULL, 0, 'chocolate.jpeg', 1, 'In Stock'),
(25, 'Insta', 'Whipp cream', 400.00, NULL, NULL, 0, 'instawhip_cream.jpg', 0, 'In Stock'),
(26, 'Tropical', 'Whipp cream', 450.00, NULL, NULL, 0, 'tropical.webp', 0, 'In Stock'),
(27, 'Vivo red', 'Whipp cream', 350.00, NULL, NULL, 0, 'vivo_red_333.jpg', 0, 'In Stock'),
(28, 'Piping Bag', 'Bag', 15.00, NULL, NULL, 0, 'pipingbag.jpg', 0, 'In Stock'),
(29, 'Baking  Organic', 'Baking Powder', 200.00, NULL, NULL, 0, 'organic-baking-powder-100gm.jpg', 0, 'In Stock'),
(30, 'Baking Soda GL 50g', 'Baking Soda', 130.00, NULL, NULL, 0, 'bakingsode.webp', 0, 'In Stock'),
(31, 'Cornflour ', 'Cornflour', 180.00, NULL, NULL, 0, 'cornflour.jpeg', 0, 'In Stock'),
(32, 'Malaysian Cocoa', 'Cocoa Powder', 800.00, NULL, NULL, 0, 'Malaysian Cocoa.jpg', 0, 'In Stock'),
(33, 'Cake Mold 4\"', 'Mold', 110.00, NULL, NULL, 0, '4inch-round-shape-cake-mold.jpg', 0, 'In Stock'),
(34, 'Cake Mold 5\"', 'Mold ', 120.00, NULL, NULL, 0, '5inch-round-shape-cake-mold.jpg', 0, 'In Stock'),
(35, 'Cake Mold 6\"', ' Mold ', 140.00, NULL, NULL, 0, 'Almunium-Round-Mold-6-inch-.avif', 0, 'In Stock'),
(36, 'Cake Mold 7\"', 'Mold ', 160.00, NULL, NULL, 0, '7inchmold.jpg', 0, 'In Stock'),
(37, 'Cake Mold 10\"', ' Mold ', 190.00, NULL, NULL, 0, '10inch-square-shape-cake-mold.jpg', 0, 'In Stock'),
(38, 'Dark Chips  Chocolate ', 'Chocolate ', 200.00, 'Product Title: Premium Dark Chocolate Chips – Intense Cocoa Flavor\r\nShort Description: Rich, velvety, and intensely chocolatey! These premium dark chocolate chips are the perfect baking companion for cookies, cakes, and professional-grade desserts.\r\n\r\nProduct Details:\r\nExperience the perfect balance of bittersweet indulgence with our Premium Dark Chocolate Chips. Made from high-quality cocoa, these chips are designed to melt smoothly while maintaining a rich, bold flavor profile. Whether you are folding them into cookie dough or melting them for a silky ganache, they provide an unmatched gourmet experience.\r\n\r\nKey Features:\r\n\r\nHigh-Quality Cocoa: Crafted with a high percentage of cocoa for a true dark chocolate taste.\r\n\r\nBake-Stable: Specially formulated to hold their shape during baking, giving you those perfect chocolate pockets in every bite.\r\n\r\nMulti-Purpose: Excellent for baking, melting, or simply snacking.\r\n\r\nDairy-Friendly Options: Low in sugar and rich in antioxidants, making it a healthier alternative to milk chocolate.\r\n\r\nSpecifications:\r\nFlavor Profile: Deep Bittersweet Dark Chocolate\r\n\r\nTexture: Smooth and Creamy when melted\r\n\r\nIdeal For: Cookies, Brownies, Muffins, Cakes, and Toppings\r\n\r\nStorage: Keep in a cool, dry place away from direct sunlight\r\n\r\nUsage Suggestions:\r\nBaking: Mix into your favorite cookie or muffin batter.\r\n\r\nTopping: Sprinkle over ice cream, whipped cream, or pancakes.\r\n\r\nMelting: Melt over a double boiler to create a smooth chocolate drizzle or dip for fruits like strawberries.', NULL, 0, 'diy-dark-chocolate-chips_2009.webp', 0, 'In Stock');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `announcement` text DEFAULT NULL,
  `is_marquee_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `announcement`, `is_marquee_active`) VALUES
(1, 'Baking Valley-তে স্বাগতম! আমাদের বিশেষ অফারগুলো উপভোগ করুন।||', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT 'default.png',
  `role` enum('Admin','Customer') NOT NULL DEFAULT 'Customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `phone`, `password`, `image`, `role`, `created_at`) VALUES
(1, 'Main Admin', 'admin1@gmail.com', NULL, 'admin1234', 'default.png', 'Admin', '2026-01-17 09:33:04'),
(9, 'mostafiz', 'mostafiz11@gmail.com', NULL, '123456', 'default.png', 'Customer', '2026-01-19 19:37:04'),
(10, 'Mostafizur Rahman', 'mostafizur11@gmail.com', NULL, '1234', 'default.png', 'Customer', '2026-01-20 09:58:11'),
(11, 'Md.Mostafizur Rahman', 'mostafizurrahman7023@gmail.com', NULL, '123456', 'default.png', 'Customer', '2026-01-20 10:16:18'),
(12, 'Md.Mostafizur Rahman', 'mos11@gmail.com', NULL, '1234', 'default.png', 'Customer', '2026-01-20 10:23:50'),
(13, 'raju', 'raju1@gmail.com', NULL, '1234', 'default.png', 'Customer', '2026-01-20 11:31:26'),
(14, 'Md.Mostafizur Rahman', 'mostafizz@gmail.com', NULL, '1234', 'default.png', 'Customer', '2026-01-20 18:03:17'),
(15, 'estiak', 'estiak1@gmail.com', NULL, '1234', 'default.png', 'Customer', '2026-01-20 18:27:50'),
(16, 'Md.Mostafizur Rahman', 'tagor@gmail.com', NULL, '1234567', 'default.png', 'Customer', '2026-01-20 21:15:21'),
(18, 'Mostafizur Rahman', 'mostafizurrahman70@gmail.com', NULL, '123456', 'default.png', 'Customer', '2026-01-21 17:51:51'),
(22, 'Abu Hena', 'hena1@gmail.com', NULL, '123456', 'default.png', 'Customer', '2026-02-06 20:04:25'),
(23, 'Md Mostafizur Rahman', 'rahman@gmail.com', NULL, '1234567890', 'default.png', 'Customer', '2026-02-07 13:14:19'),
(24, 'Md.Abu Sufian', 'abusufian2026@gmail.com', '01797362734', 'Sufian1234', 'default.png', 'Customer', '2026-02-10 06:23:40'),
(25, 'Md MOstafizur Rahman', 'rahman111@gmail.com', '01718926369', 'Raju1234', '1770706553_Raju.jpg', 'Customer', '2026-02-10 06:44:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
