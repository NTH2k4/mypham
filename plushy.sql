-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 05, 2024 at 03:46 PM
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
-- Database: `plushy`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id_cart` int(11) NOT NULL,
  `user_username` varchar(15) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price_at_cart` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `date_added` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id_category` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id_category`, `name`) VALUES
(1, 'Animal Plushies'),
(2, 'Fantasy Plushies'),
(3, 'Mini Plushies'),
(4, 'Limited Edition Plushies');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id_order` int(11) NOT NULL,
  `user_username` varchar(15) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `total_price` int(11) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `price_at_purchase` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id_product` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `shortdesc` varchar(100) DEFAULT NULL,
  `longdesc` text DEFAULT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  `image` varchar(100) DEFAULT 'assets/uploads/default.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id_product`, `name`, `shortdesc`, `longdesc`, `price`, `quantity`, `category_id`, `image`, `created_at`) VALUES
(1, 'Capybara Plush', 'Soft and cuddly capybara plush', 'This capybara plush is designed to bring you comfort and joy. Made with premium fabric, it’s perfect for cuddling.', 350000, 50, 1, 'assets/uploads/capy.jpg', '2024-10-05 13:25:33'),
(2, 'Dragon Plush', 'Majestic dragon plush', 'Our dragon plush is a must-have for any fantasy lover. Featuring detailed embroidery and soft material, it’s perfect for both display and play.', 500000, 30, 2, 'assets/uploads/th.jpg', '2024-10-05 13:25:33'),
(3, 'Mini Bunny Plush', 'Adorable mini bunny plush', 'This mini bunny plush is small but full of charm. Perfect for carrying around in your pocket or using as a keychain accessory.', 150000, 200, 3, 'assets/uploads/bunny.jpg', '2024-10-05 13:25:33'),
(4, 'Phoenix Plush', 'Rare phoenix plush', 'This rare plush features a mythical phoenix, designed with intricate stitching and bold colors. A must-have for plushie collectors.', 600000, 15, 4, 'assets/uploads/R.jpg', '2024-10-05 13:25:33'),
(5, 'Panda Plush', 'Cute panda plush', 'Our panda plush is soft, fluffy, and ultra-huggable. Its adorable face will bring a smile to anyone’s day.', 400000, 80, 1, 'assets/uploads/d.jpg', '2024-10-05 13:25:33'),
(6, 'Unicorn Plush', 'Magical unicorn plush', 'A soft unicorn plush complete with a shimmering horn and rainbow mane, perfect for fans of all things magical.', 450000, 40, 2, 'assets/uploads/g.jpg', '2024-10-05 13:25:33'),
(7, 'Mini Elephant Plush', 'Tiny elephant plush', 'This mini elephant plush is irresistibly cute. With its tiny size, it’s perfect for small hands or as a cute collectible.', 120000, 150, 3, 'assets/uploads/bn.jpg', '2024-10-05 13:25:33'),
(8, 'Limited Edition Tiger Plush', 'Exclusive tiger plush', 'Our limited edition tiger plush is made with special fabric and features unique markings. Only a small number are available, making this a true collector’s item.', 700000, 10, 4, 'assets/uploads/gtn.jpg', '2024-10-05 13:25:33');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `isAdmin` tinyint(1) DEFAULT 0,
  `isDisabled` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `fullname`, `phone`, `email`, `address`, `isAdmin`, `isDisabled`) VALUES
(1, 'admin', 'admin', 'Admin User', '0900000000', 'admin@example.com', '123 Admin St', 1, 0),
(2, 'plushieFan1', 'password1', 'Plushie Fan One', '0910000011', 'plushiefan1@example.com', 'Plushie Ave 1', 0, 0),
(3, 'plushieFan2', 'password2', 'Plushie Fan Two', '0910000012', 'plushiefan2@example.com', 'Plushie Ave 2', 0, 0),
(4, 'plushieFan3', 'password3', 'Plushie Fan Three', '0910000013', 'plushiefan3@example.com', 'Plushie Ave 3', 0, 0),
(5, 'plushieFan4', 'password4', 'Plushie Fan Four', '0910000014', 'plushiefan4@example.com', 'Plushie Ave 4', 0, 0),
(6, 'plushieFan5', 'password5', 'Plushie Fan Five', '0910000015', 'plushiefan5@example.com', 'Plushie Ave 5', 0, 0),
(7, 'plushieFan6', 'password6', 'Plushie Fan Six', '0910000016', 'plushiefan6@example.com', 'Plushie Ave 6', 0, 0),
(8, 'plushieFan7', 'password7', 'Plushie Fan Seven', '0910000017', 'plushiefan7@example.com', 'Plushie Ave 7', 0, 0),
(9, 'plushieFan8', 'password8', 'Plushie Fan Eight', '0910000018', 'plushiefan8@example.com', 'Plushie Ave 8', 0, 0),
(10, 'plushieFan9', 'password9', 'Plushie Fan Nine', '0910000019', 'plushiefan9@example.com', 'Plushie Ave 9', 0, 0),
(11, 'plushieFan10', 'password10', 'Plushie Fan Ten', '0910000020', 'plushiefan10@example.com', 'Plushie Ave 10', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id_cart`),
  ADD KEY `FK_user_cart` (`user_username`),
  ADD KEY `FK_product_cart` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id_category`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `FK_user_orders` (`user_username`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`order_id`,`product_id`),
  ADD KEY `FK_product_order_detail` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id_product`),
  ADD KEY `FK_category_product` (`category_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id_cart` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `FK_product_cart` FOREIGN KEY (`product_id`) REFERENCES `product` (`id_product`),
  ADD CONSTRAINT `FK_user_cart` FOREIGN KEY (`user_username`) REFERENCES `user` (`username`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK_user_orders` FOREIGN KEY (`user_username`) REFERENCES `user` (`username`);

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `FK_orders_order_detail` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id_order`),
  ADD CONSTRAINT `FK_product_order_detail` FOREIGN KEY (`product_id`) REFERENCES `product` (`id_product`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_category_product` FOREIGN KEY (`category_id`) REFERENCES `category` (`id_category`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
