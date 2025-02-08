-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 01, 2025 at 09:51 AM
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
-- Database: `shop`
--
CREATE DATABASE IF NOT EXISTS `shop` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `shop`;

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE IF NOT EXISTS `brand` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`id`, `name`, `description`) VALUES
(2, 'apple', 'hãng táo khuyết đến từ Hoa kì'),
(6, 'samsung', 'Hãng công nghệ đến từ Hàn'),
(9, 'POCO', 'Hãng con của Xiaomi'),
(10, 'xiaomiii', 'China'),
(12, 'Casio', '');

-- --------------------------------------------------------

--
-- Table structure for table `cartitems`
--

CREATE TABLE IF NOT EXISTS `cartitems` (
  `user_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`product_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `description`) VALUES
(1, 'điện thoại android', ''),
(9, 'điện thoại iphone', ''),
(12, 'Laptop', 'fdfjd ');

-- --------------------------------------------------------

--
-- Table structure for table `combo`
--

CREATE TABLE IF NOT EXISTS `combo` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `price` bigint(20) NOT NULL,
  `startdate` date NOT NULL,
  `enddate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `combodetails`
--

CREATE TABLE IF NOT EXISTS `combodetails` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `combo_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `combo_id` (`combo_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE IF NOT EXISTS `image` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `path` varchar(500) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`id`, `path`, `product_id`, `sort_order`) VALUES
(16, '///uploads/productImages/Samsung A50s_6759a1b6755eb.jpg', 7, 0),
(17, '///uploads/productImages/sp1_6759a1c784dcd.jpeg', 8, 0),
(18, '///uploads/productImages/sp1_6759a1c784fc8.jpg', 8, 0),
(35, 'uploads/productImages/Casio_67696c86a4a0c.png', 11, 0),
(36, 'uploads/productImages/Casio_67696c86a5021.png', 11, 0),
(37, 'uploads/productImages/Casio_67696c86a5132.png', 11, 0),
(38, 'uploads/productImages/Iphone 4_67696d46f2442.PNG', 12, 0),
(43, 'uploads/productImages/SamSung S21_6772233312453.PNG', 14, 0),
(46, 'uploads/productImages/SamSung S21_6772235127781.PNG', 14, 0),
(47, 'uploads/productImages/SamSung S21_6772235127953.png', 14, 0),
(50, 'uploads/productImages/Iphone 13_6773e1e55ad3d.png', 6, 0),
(51, 'uploads/productImages/Iphone 13_6773e1e55aeea.png', 6, 0),
(52, 'uploads/productImages/Iphone 13_6773e1e55b01f.png', 6, 0),
(55, 'uploads/productImages/dfdf _6774b6170a7d8.png', 16, 0),
(56, 'uploads/productImages/POCO C40_6774be20b341d.PNG', 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `address` varchar(500) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` enum('processing','confirmed','shipping','delivered','cancelled') DEFAULT 'processing',
  `discount` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `createdAt`, `updatedAt`, `address`, `firstname`, `lastname`, `phone`, `email`, `status`, `discount`) VALUES
(5, '2024-12-18 19:13:26', '2024-12-18 19:13:26', '0399840032', 'nguyen van', 'Nam', 'Phường đúc,', NULL, 'processing', 0),
(6, '2024-12-18 19:17:15', '2024-12-18 19:17:15', '0123456789', 'Nguyen Duy', 'Hoang', 'Da Nang', NULL, 'processing', 0),
(7, '2024-12-18 19:17:46', '2024-12-18 19:17:46', '0123456789', 'Nguyen Duy', 'Hoang', 'Da Nang', NULL, 'processing', 0),
(8, '2024-12-18 19:18:29', '2024-12-18 19:18:29', '0123456789', 'Nguyen Duy', 'Hoang', 'Da Nang', NULL, 'processing', 0),
(9, '2024-12-18 19:19:13', '2024-12-18 19:19:13', '0123456789', 'Nguyen Duy', 'Hoang', 'Da Nang', NULL, 'processing', 0),
(10, '2024-12-18 19:19:54', '2024-12-18 19:19:54', '0123456789', 'Nguyen Duy', 'Hoang', 'Da Nang', NULL, 'processing', 0),
(11, '2024-12-18 19:20:24', '2024-12-18 19:20:24', '0123456789', 'Nguyen Duy', 'Hoang', 'Da Nang', NULL, 'processing', 0),
(12, '2024-12-18 19:20:46', '2024-12-18 19:20:46', '0123456789', 'Nguyen Duy', 'Hoang', 'Da Nang', NULL, 'processing', 0),
(13, '2024-12-18 19:21:17', '2024-12-18 19:21:17', '0123456789', 'Nguyen Duy', 'Hoang', 'Da Nang', NULL, 'processing', 0),
(14, '2024-12-18 19:21:28', '2024-12-18 19:21:28', '0123456789', 'Nguyen Duy', 'Hoang', 'Da Nang', NULL, 'processing', 0),
(15, '2024-12-18 19:23:52', '2024-12-18 19:23:52', '0123456789', 'Nguyen Duy', 'Hoang', 'Da Nang', 'hoang@gmail.com', 'processing', 0),
(16, '2024-12-18 21:40:40', '2024-12-18 21:40:40', '0123456789', 'Nguyen Duy', 'Hoang', 'Da Nang', 'user1@gmail.com', 'processing', 0),
(17, '2024-12-18 21:41:14', '2024-12-18 21:41:14', '0123456789', 'Nguyen Duy', 'Hoang', 'Da Nang', 'user1@gmail.com', 'processing', 0),
(18, '2024-12-18 21:41:32', '2024-12-18 21:41:32', '0123456789', 'Nguyen Duy', 'Hoang', 'Da Nang', 'user1@gmail.com', 'processing', 0),
(19, '2024-12-18 21:42:30', '2024-12-18 21:42:30', '0123456789', 'Nguyen Duy', 'Hoang', 'Da Nang', 'user1@gmail.com', 'processing', 0),
(20, '2024-12-18 21:43:14', '2024-12-18 21:43:14', '0111111111', 'nam', 'nguyen', 'hue', NULL, 'processing', 0),
(21, '2024-12-18 21:49:26', '2024-12-18 21:49:26', '9232222222', 'nguyen van', 'Nam', 'Hue, viet n', NULL, 'processing', 0),
(22, '2024-12-18 21:53:14', '2024-12-18 21:53:14', 'Thua Thien Hue', 'Ho Anh', 'Duy', '0111111111', NULL, 'processing', 0),
(23, '2024-12-18 21:55:19', '2024-12-18 21:55:19', 'fdsfds dfdfd ', 'lfdjkfd', 'fdfsdf', '33434343434', 'df@gmail.com', 'processing', 0),
(24, '2024-12-18 22:05:06', '2024-12-18 22:05:06', 'Đà nẵng', 'nguyễn xuân', 'son', '0123456789', '1@gmail.com', 'processing', 0),
(25, '2024-12-18 22:06:11', '2024-12-18 22:06:11', 'viet nam', 'fd f', ' dfdf dfd', '34343434343', NULL, 'processing', 0),
(26, '2024-12-18 22:07:10', '2024-12-18 22:07:10', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(27, '2024-12-18 22:08:25', '2024-12-18 22:08:25', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(28, '2024-12-18 22:09:59', '2024-12-18 22:09:59', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(29, '2024-12-18 22:10:49', '2024-12-18 22:10:49', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(30, '2024-12-18 22:11:20', '2024-12-18 22:11:20', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(31, '2024-12-18 22:12:32', '2024-12-18 22:12:32', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(32, '2024-12-18 22:13:40', '2024-12-18 22:13:40', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(33, '2024-12-18 22:14:02', '2024-12-18 22:14:02', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(34, '2024-12-18 22:14:18', '2024-12-18 22:14:18', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(35, '2024-12-18 22:15:21', '2024-12-18 22:15:21', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(36, '2024-12-18 22:16:15', '2024-12-18 22:16:15', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(37, '2024-12-18 22:17:01', '2024-12-18 22:17:01', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(38, '2024-12-18 22:17:48', '2024-12-18 22:17:48', 'fds d', 'f dfdfd', 'fd fdfd', '232323232', NULL, 'processing', 0),
(39, '2024-12-18 22:19:15', '2024-12-18 22:19:15', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(40, '2024-12-18 22:20:06', '2024-12-18 22:20:06', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(41, '2024-12-18 22:21:36', '2024-12-18 22:21:36', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(42, '2024-12-18 22:22:20', '2024-12-18 22:22:20', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(43, '2024-12-18 22:24:01', '2024-12-18 22:24:01', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(44, '2024-12-18 22:25:10', '2024-12-18 22:25:10', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(45, '2024-12-18 22:26:22', '2024-12-18 22:26:22', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(46, '2024-12-18 22:27:28', '2024-12-18 22:27:28', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(47, '2024-12-18 22:29:27', '2024-12-18 22:29:27', 'Da Nang', 'Nguyen Duy', 'Hoang', '011111111', 'h@gmail.com', 'processing', 0),
(48, '2024-12-18 22:31:47', '2024-12-18 22:31:47', '0123456789', 'Nguyen Duy', 'Hoang', 'Da Nang', 'user1@gmail.com', 'processing', 0),
(49, '2024-12-18 22:33:46', '2024-12-18 22:33:46', 'Hue', 'nguyen', 'Nam', '011111111', 'n@gmail.com', 'processing', 0),
(50, '2024-12-18 22:34:18', '2024-12-18 22:34:18', 'Hue', 'nguyen', 'Nam', '011111111', 'n@gmail.com', 'processing', 0),
(51, '2024-12-18 22:36:25', '2024-12-18 22:36:25', '0123456789', 'Nguyen Duy', 'Hoang', 'Da Nang', 'user1@gmail.com', 'processing', 0),
(52, '2024-12-18 22:37:56', '2024-12-18 22:37:56', 'jfdklfjd', 'fdsf dfd f', 'd fd fdsfdsf dsf', '34343434343', NULL, 'processing', 0),
(53, '2024-12-18 22:39:11', '2024-12-18 22:39:11', 'Hue', 'nguyen', 'v', '43434343434', 'nam@gmail.com', 'processing', 0),
(54, '2024-12-18 22:40:50', '2024-12-18 22:40:50', 'Hue', 'nguyen', 'v', '43434343434', 'nam@gmail.com', 'processing', 0),
(55, '2024-12-18 22:44:26', '2024-12-18 22:44:26', 'Hue', 'nguyen', 'v', '43434343434', 'nam@gmail.com', 'processing', 0),
(56, '2024-12-18 22:45:30', '2024-12-18 22:45:30', 'Hue', 'nguyen', 'v', '43434343434', 'nam@gmail.com', 'processing', 0),
(57, '2024-12-18 22:45:46', '2024-12-18 22:45:46', 'Hue', 'nguyen', 'v', '43434343434', 'nam@gmail.com', 'processing', 0),
(58, '2024-12-18 22:46:27', '2024-12-18 22:46:27', 'Hue', 'nguyen', 'v', '43434343434', 'nam@gmail.com', 'processing', 0),
(59, '2024-12-18 22:46:45', '2024-12-18 22:46:45', 'Hue', 'nguyen', 'v', '43434343434', 'nam@gmail.com', 'processing', 0),
(60, '2024-12-18 22:46:57', '2024-12-18 22:46:57', 'Hue', 'nguyen', 'v', '43434343434', 'nam@gmail.com', 'processing', 0),
(61, '2024-12-18 22:47:36', '2024-12-18 22:47:36', 'Hue', 'nguyen', 'v', '43434343434', 'nam@gmail.com', 'processing', 0),
(62, '2024-12-18 22:51:07', '2024-12-18 22:51:07', 'Hue', 'nguyen', 'v', '43434343434', 'nam@gmail.com', 'processing', 0),
(63, '2024-12-18 22:55:00', '2024-12-18 22:55:00', 'Thành phố Huế', 'Nguyễn Văn ', 'Nam', '0111111111', 'nam@gmail.com', 'processing', 0),
(64, '2024-12-20 11:01:52', '2024-12-20 11:01:52', 'Da Nang', 'Nguyen Duy', 'Hoanggg', '0123456789', 'user1@gmail.com', 'processing', 0),
(65, '2024-12-20 11:59:41', '2024-12-20 11:59:41', 'Da Nang', 'Nguyen Duy', 'Hoangkfdjkfj', '0123456789', 'user1@gmail.com', 'processing', 0),
(66, '2024-12-20 12:01:32', '2024-12-20 12:01:32', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(67, '2024-12-20 12:02:33', '2024-12-20 12:02:33', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(68, '2024-12-20 12:02:58', '2024-12-20 12:02:58', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(69, '2024-12-20 12:03:15', '2024-12-20 12:03:15', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(70, '2024-12-20 12:03:37', '2024-12-20 12:03:37', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(71, '2024-12-20 12:03:54', '2024-12-20 12:03:54', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(72, '2024-12-20 12:04:00', '2024-12-20 12:04:00', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(73, '2024-12-20 12:04:44', '2024-12-20 12:04:44', 'Da Nang', 'Nguyen Duy', 'jfdkflj', '0123456789', 'user1@gmail.com', 'processing', 0),
(74, '2024-12-20 12:05:45', '2024-12-20 12:05:45', 'Da Nang', 'Nguyen Duy', 'jfdkflj', '0123456789', 'user1@gmail.com', 'processing', 0),
(75, '2024-12-20 12:05:52', '2024-12-20 12:05:52', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(76, '2024-12-20 12:06:34', '2024-12-20 12:06:34', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(77, '2024-12-20 12:07:07', '2024-12-20 12:07:07', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(78, '2024-12-20 12:07:43', '2024-12-20 12:07:43', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(79, '2024-12-20 12:08:00', '2024-12-20 12:08:00', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(80, '2024-12-20 12:09:04', '2024-12-20 12:09:04', 'Da Nang', 'Nguyen Duy', 'Nam', '0123456789', 'user1@gmail.com', 'cancelled', 0),
(81, '2024-12-20 12:10:51', '2024-12-20 12:10:51', 'flkdfjkd', 'dkffdfd', ' fdfd fd', '123456789', NULL, 'cancelled', 0),
(82, '2024-12-27 17:44:58', '2024-12-27 17:44:58', 'Huế', 'nguyen van', 'Nam', '011111111', NULL, 'processing', 0),
(83, '2024-12-27 17:45:38', '2024-12-27 17:45:38', 'Huế', 'nguyen van', 'Nam', '011111111', NULL, 'processing', 0),
(84, '2024-12-27 17:45:51', '2024-12-27 17:45:51', 'Huế', 'nguyen van', 'Nam', '011111111', NULL, 'processing', 0),
(85, '2024-12-27 17:45:59', '2024-12-27 17:45:59', 'Huế', 'nguyen van', 'Nam', '011111111', NULL, 'processing', 0),
(86, '2024-12-27 17:46:26', '2024-12-27 17:46:26', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(87, '2024-12-27 17:49:00', '2024-12-27 17:49:00', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(88, '2024-12-27 17:49:25', '2024-12-27 17:49:25', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(89, '2024-12-27 17:52:09', '2024-12-27 17:52:09', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(90, '2024-12-27 17:55:00', '2024-12-27 17:55:00', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(91, '2024-12-27 17:55:38', '2024-12-27 17:55:38', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'processing', 0),
(92, '2024-12-27 17:56:10', '2024-12-27 17:56:10', 'Da Nang', 'Nguyen Duy', 'Hoang', '0123456789', 'user1@gmail.com', 'shipping', 0),
(93, '2024-12-27 18:00:36', '2024-12-27 18:00:36', 'Huế', 'phan duc', 'khanh beo', '0123456', 'khanhbeo@gmail.com', 'processing', 0),
(94, '2024-12-27 18:01:30', '2024-12-27 18:01:30', 'Huế', 'phan duc', 'khanh beo', '0123456', 'khanhbeo@gmail.com', 'processing', 0),
(95, '2024-12-27 18:03:35', '2024-12-27 18:03:35', 'viet nam', 'dlkfjdlfjdlk', 'fjdfl dfjdlfjd', '011111', 'abc@gmail.com', 'shipping', 0),
(96, '2024-12-29 18:53:53', '2024-12-29 18:53:53', 'Thuỷ vân, Huế', 'Hồ Tấn', 'Quang Trung', '0123456789', 'user1@gmail.com', 'processing', 0),
(97, '2024-12-30 11:32:35', '2024-12-30 11:32:35', 'Thuỷ vân, Huế', 'Hồ Tấn', 'Quang Trung', '0123456789', 'user1@gmail.com', 'processing', 0),
(98, '2024-12-30 11:33:04', '2024-12-30 11:33:04', 'Thuỷ vân, Huế', 'Hồ Tấn', 'Quang Trung', '0123456789', 'user1@gmail.com', 'processing', 0),
(99, '2024-12-30 11:33:43', '2024-12-30 11:33:43', 'Huế', 'nguyen van', 'Nam', '0123456778', 'nam@gmail.com', 'processing', 0),
(100, '2024-12-30 11:34:31', '2024-12-30 11:34:31', 'Huế', 'Nguyen Thi', 'C', '0123434354', NULL, 'cancelled', 0),
(101, '2024-12-31 21:06:53', '2024-12-31 21:06:53', ' dfdfdf dsf', 'dfd d', 'fdfd fd', '0124', NULL, 'processing', 0),
(102, '2024-12-31 21:09:59', '2024-12-31 21:09:59', 'fdfdf', 'fdsfd', 'fdsfdfsds', '0123', NULL, 'processing', 0),
(103, '2024-12-31 21:11:35', '2024-12-31 21:11:35', 'fdfdf', 'fdsfd', 'fdsfdfsds', '0123', NULL, 'processing', 0),
(104, '2024-12-31 21:12:27', '2024-12-31 21:12:27', 'fdfdf', 'fdsfd', 'fdsfdfsds', '0123', NULL, 'processing', 0),
(105, '2024-12-31 21:12:39', '2024-12-31 21:12:39', 'fdfdf', 'fdsfd', 'fdsfdfsds', '0123', NULL, 'processing', 0),
(106, '2024-12-31 21:13:02', '2024-12-31 21:13:02', 'fdfdf', 'fdsfd', 'fdsfdfsds', '0123', NULL, 'processing', 0),
(107, '2024-12-31 21:13:24', '2024-12-31 21:13:24', 'fdfdf', 'fdsfd', 'fdsfdfsds', '0123', NULL, 'processing', 0),
(108, '2024-12-31 21:30:28', '2024-12-31 21:30:28', 'Thuỷ vân, Huế', 'Hồ Tấn', 'Quang Trung', '0123456789', 'user1@gmail.com', 'processing', 0),
(109, '2024-12-31 22:07:06', '2024-12-31 22:07:06', 'fsd fdfdf', 'Nguyễn Văn C', 'jfsdlkf jdf', '23232434343', NULL, 'processing', 0),
(110, '2025-01-01 12:31:22', '2025-01-01 12:31:22', 'dsfdsfdf', 'dfdfdf', 'fsdfd343', '3434343', NULL, 'processing', 0),
(111, '2025-01-01 12:31:47', '2025-01-01 12:31:47', 'dsfdsfdf', 'dfdfdf', 'fsdfd343', '3434343', NULL, 'processing', 0),
(112, '2025-01-01 13:51:19', '2025-01-01 13:51:19', 'fdfdf', 'fdf', 'fdf', '344', NULL, 'processing', 0),
(113, '2025-01-01 13:54:47', '2025-01-01 13:54:47', 'hue', 'nam', 'nguyen', '34324343434', NULL, 'processing', 0),
(114, '2025-01-01 14:13:49', '2025-01-01 14:13:49', 'sdfdsfdf', 'dfdf', 'fdsfd', '34343434', NULL, 'confirmed', 0),
(115, '2025-01-01 14:29:56', '2025-01-01 14:29:56', 'Thuỷ vân, Huế', 'Hồ Tấn', 'Quang Trung', '0123456789', 'user1@gmail.com', 'processing', 0),
(116, '2025-01-01 14:33:10', '2025-01-01 14:33:10', 'Thuỷ vân, Huế', 'Hồ Tấn', 'Quang Trung', '0123456789', 'user1@gmail.com', 'processing', 0),
(117, '2025-01-01 14:33:29', '2025-01-01 14:33:29', 'Thuỷ vân, Huế', 'Hồ Tấn', 'Quang Trung', '0123456789', 'user1@gmail.com', 'processing', 0),
(118, '2025-01-01 14:33:54', '2025-01-01 14:33:54', 'Thuỷ vân, Huế', 'Hồ Tấn', 'Quang Trung', '0123456789', 'user1@gmail.com', 'processing', 0),
(119, '2025-01-01 14:34:01', '2025-01-01 14:34:01', 'Thuỷ vân, Huế', 'Hồ Tấn', 'Quang Trung', '0123456789', 'user1@gmail.com', 'processing', 0),
(120, '2025-01-01 14:34:13', '2025-01-01 14:34:13', 'Thuỷ vân, Huế', 'Hồ Tấn', 'Quang Trung', '0123456789', 'user1@gmail.com', 'processing', 0),
(121, '2025-01-01 14:34:36', '2025-01-01 14:34:36', 'Thuỷ vân, Huế', 'Hồ Tấn', 'Quang Trung', '0123456789', 'user1@gmail.com', 'processing', 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE IF NOT EXISTS `order_details` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 5, 5, 3, 2999009),
(2, 6, 5, 1, 2999009),
(3, 7, 5, 1, 2999009),
(4, 8, 5, 1, 2999009),
(5, 9, 5, 1, 2999009),
(6, 10, 5, 1, 2999009),
(7, 11, 5, 1, 2999009),
(8, 12, 5, 1, 2999009),
(9, 13, 5, 1, 2999009),
(10, 14, 5, 1, 2999009),
(11, 15, 5, 1, 2999009),
(12, 16, 6, 49, 100000000),
(13, 17, 6, 49, 100000000),
(14, 18, 6, 49, 100000000),
(15, 19, 6, 49, 100000000),
(16, 20, 7, 1, 5000000),
(17, 21, 6, 1, 100000000),
(18, 22, 5, 3, 2999009),
(19, 18, 8, 1, 1000000),
(20, 23, 7, 1, 5000000),
(21, 20, 5, 1, 2999009),
(22, 24, 5, 3, 2999009),
(23, 24, 8, 1, 1000000),
(24, 25, 7, 2, 5000000),
(25, 25, 8, 4, 1000000),
(26, 26, 6, 1, 100000000),
(27, 26, 7, 2, 5000000),
(28, 26, 8, 1, 1000000),
(29, 27, 5, 1, 2999009),
(30, 28, 6, 1, 100000000),
(31, 29, 7, 1, 5000000),
(32, 31, 6, 1, 100000000),
(33, 32, 6, 1, 100000000),
(34, 34, 6, 1, 100000000),
(35, 35, 6, 1, 100000000),
(36, 36, 6, 1, 100000000),
(37, 37, 6, 1, 100000000),
(38, 38, 7, 1, 5000000),
(39, 39, 7, 1, 5000000),
(40, 40, 7, 1, 5000000),
(41, 41, 7, 1, 5000000),
(42, 42, 7, 1, 5000000),
(43, 43, 7, 1, 5000000),
(44, 44, 6, 1, 100000000),
(45, 45, 6, 1, 100000000),
(46, 46, 7, 1, 5000000),
(47, 48, 7, 1, 5000000),
(48, 51, 5, 1, 2999009),
(49, 51, 6, 1, 100000000),
(50, 51, 7, 2, 5000000),
(51, 52, 7, 1, 5000000),
(52, 52, 6, 1, 100000000),
(53, 53, 6, 1, 100000000),
(54, 63, 5, 1, 2999009),
(55, 63, 7, 3, 5000000),
(56, 64, 6, 1, 100000000),
(57, 64, 7, 1, 5000000),
(58, 73, 5, 5, 2999009),
(59, 74, 5, 5, 2999009),
(60, 75, 5, 5, 2999009),
(61, 76, 5, 5, 2999009),
(62, 77, 5, 5, 2999009),
(63, 78, 5, 5, 2999009),
(64, 79, 5, 5, 2999009),
(65, 80, 5, 2, 2999009),
(66, 80, 7, 1, 5000000),
(67, 81, 6, 3, 100000000),
(68, 81, 5, 1, 2999009),
(69, 91, 5, 1, 0),
(70, 92, 5, 1, 100),
(71, 93, 6, 3, 0),
(72, 93, 5, 2, 0),
(73, 95, 5, 2, 100),
(74, 95, 6, 3, 15000000),
(75, 96, 6, 2, 15000000),
(76, 97, 6, 2, 100000),
(77, 98, 5, 1, 2500000),
(78, 98, 6, 2, 100000),
(79, 99, 5, 1, 2500000),
(80, 100, 5, 1, 2500000),
(81, 100, 7, 2, 5000000),
(82, 101, 5, 1, 2300000),
(83, 102, 5, 1, 2300000),
(84, 103, 5, 1, 2300000),
(85, 104, 5, 1, 2300000),
(86, 105, 5, 1, 2300000),
(87, 106, 5, 1, 2300000),
(88, 107, 5, 1, 2300000),
(89, 108, 5, 1, 2300000),
(90, 109, 5, 5, 2300000),
(91, 110, 5, 2, 2500000),
(92, 110, 6, 3, 100000),
(93, 112, 5, 1, 2500000),
(94, 113, 5, 1, 2500000),
(95, 114, 5, 4, 2500000),
(96, 115, 5, 1, 2500000),
(97, 116, 5, 1, 2500000),
(98, 117, 5, 1, 2500000),
(99, 118, 5, 1, 2500000),
(100, 119, 5, 1, 2500000),
(101, 120, 5, 1, 2500000),
(102, 121, 5, 1, 2500000);

-- --------------------------------------------------------

--
-- Table structure for table `price`
--

CREATE TABLE IF NOT EXISTS `price` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) NOT NULL,
  `price` bigint(20) NOT NULL,
  `datetime` datetime NOT NULL,
  `isOriginalPrice` tinyint(1) DEFAULT 1,
  `dateStart` date DEFAULT NULL,
  `dateEnd` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `price`
--

INSERT INTO `price` (`id`, `product_id`, `price`, `datetime`, `isOriginalPrice`, `dateStart`, `dateEnd`) VALUES
(5, 5, 2999000, '2024-12-10 09:58:35', 1, NULL, NULL),
(6, 6, 15000000, '2024-12-10 10:08:33', 1, NULL, NULL),
(7, 6, 15000000, '2024-12-10 10:12:19', 1, NULL, NULL),
(8, 6, 15000000, '2024-12-10 10:19:12', 1, NULL, NULL),
(9, 6, 15000000, '2024-12-10 10:19:32', 1, NULL, NULL),
(10, 6, 15000000, '2024-12-10 10:21:36', 1, NULL, NULL),
(11, 6, 15000000, '2024-12-10 10:22:13', 1, NULL, NULL),
(12, 6, 15000000, '2024-12-10 10:25:07', 1, NULL, NULL),
(13, 6, 15000000, '2024-12-10 10:25:17', 1, NULL, NULL),
(14, 7, 5000000, '2024-12-10 10:26:52', 1, NULL, NULL),
(15, 5, 2999000, '2024-12-11 05:12:59', 1, NULL, NULL),
(16, 5, 2999000, '2024-12-11 05:13:08', 1, NULL, NULL),
(17, 5, 2999000, '2024-12-11 05:15:02', 1, NULL, NULL),
(18, 8, 1000000, '2024-12-11 05:46:49', 1, NULL, NULL),
(19, 5, 2999000, '2024-12-11 14:12:15', 1, NULL, NULL),
(20, 5, 2999000, '2024-12-11 14:13:12', 1, NULL, NULL),
(21, 5, 2999009, '2024-12-11 14:14:03', 1, NULL, NULL),
(22, 5, 2999009, '2024-12-11 20:17:43', 1, NULL, NULL),
(23, 5, 2999009, '2024-12-11 21:27:29', 1, NULL, NULL),
(24, 5, 2999009, '2024-12-11 21:28:27', 1, NULL, NULL),
(25, 6, 15000000, '2024-12-11 21:28:56', 1, NULL, NULL),
(26, 7, 5000000, '2024-12-11 21:29:10', 1, NULL, NULL),
(27, 8, 1000000, '2024-12-11 21:29:27', 1, NULL, NULL),
(28, 5, 2999009, '2024-12-11 21:30:07', 1, NULL, NULL),
(29, 5, 2999009, '2024-12-11 21:30:28', 1, NULL, NULL),
(30, 5, 2999009, '2024-12-11 21:33:28', 1, NULL, NULL),
(31, 5, 2999009, '2024-12-11 21:34:53', 1, NULL, NULL),
(32, 5, 2999009, '2024-12-11 21:40:18', 1, NULL, NULL),
(33, 5, 2999009, '2024-12-11 21:40:47', 1, NULL, NULL),
(34, 5, 2999009, '2024-12-11 21:41:01', 1, NULL, NULL),
(35, 5, 2999009, '2024-12-11 21:41:11', 1, NULL, NULL),
(36, 5, 2999009, '2024-12-11 21:42:14', 1, NULL, NULL),
(37, 5, 2999009, '2024-12-11 21:43:49', 1, NULL, NULL),
(38, 5, 2999009, '2024-12-11 21:43:58', 1, NULL, NULL),
(39, 5, 2999009, '2024-12-11 21:44:22', 1, NULL, NULL),
(40, 5, 2999009, '2024-12-11 21:49:18', 1, NULL, NULL),
(41, 5, 2999009, '2024-12-11 21:49:34', 1, NULL, NULL),
(42, 5, 2999009, '2024-12-11 21:49:43', 1, NULL, NULL),
(45, 7, 5000000, '2024-12-11 22:20:11', 1, NULL, NULL),
(46, 6, 150000000, '2024-12-12 16:00:07', 1, NULL, NULL),
(47, 6, 1500000000, '2024-12-12 16:00:20', 1, NULL, NULL),
(48, 6, 15000000, '2024-12-12 16:00:59', 1, NULL, NULL),
(49, 5, 2999008, '2024-12-12 16:15:32', 1, NULL, NULL),
(50, 6, 14999999, '2024-12-12 19:46:32', 1, NULL, NULL),
(51, 6, 100000001, '2024-12-12 19:47:23', 1, NULL, NULL),
(52, 6, 100000000, '2024-12-12 19:47:40', 1, NULL, NULL),
(53, 6, 100000000, '2024-12-12 19:54:57', 1, NULL, NULL),
(54, 6, 100000000, '2024-12-12 19:55:35', 1, NULL, NULL),
(55, 5, 2999009, '2024-12-13 11:14:16', 1, NULL, NULL),
(56, 5, 2999009, '2024-12-13 11:14:49', 1, NULL, NULL),
(59, 8, 1000000, '2024-12-20 09:55:02', 1, NULL, NULL),
(60, 5, 2999009, '2024-12-20 10:26:40', 1, NULL, NULL),
(61, 8, 1000000, '2024-12-20 10:27:29', 1, NULL, NULL),
(62, 8, 1000000, '2024-12-20 10:29:25', 1, NULL, NULL),
(63, 8, 1000000, '2024-12-20 10:31:07', 1, NULL, NULL),
(64, 5, 2999009, '2024-12-20 22:11:39', 1, NULL, NULL),
(65, 5, 2500000, '2024-12-21 07:46:53', 1, NULL, NULL),
(66, 11, 1000, '2024-12-23 20:58:30', 1, NULL, NULL),
(73, 8, 15000, '2024-12-27 15:57:12', 1, NULL, NULL),
(101, 8, 1001, '2024-12-27 17:04:50', 0, '2024-12-28', NULL),
(102, 6, 20000000, '2024-12-27 17:23:47', 1, NULL, NULL),
(129, 6, 100000, '2024-12-29 19:47:10', 0, '2024-12-29', NULL),
(151, 5, 2300000, '2025-01-01 14:35:39', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `unit_id` bigint(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `brand_id` bigint(20) DEFAULT NULL,
  `category_id` bigint(20) DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `unit_id` (`unit_id`),
  KEY `brand_id` (`brand_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `description`, `unit_id`, `quantity`, `status`, `brand_id`, `category_id`, `createdAt`, `updatedAt`) VALUES
(5, 'POCO C40', 'Điện thoại poco c40 4GB - 64GB', 5, 60, 1, 9, 1, '2024-12-11 20:03:09', '2025-01-01 14:35:22'),
(6, 'Iphone 13', 'Iphone 13 8GB - 512GB', 1, 100, 1, 2, 9, '2024-12-11 20:03:09', '2025-01-01 14:35:22'),
(7, 'Samsung A50s', 'Samsung A50s 6GB - 128GB', 1, 150, 1, 6, 1, '2024-12-11 20:03:09', '2024-12-30 12:05:42'),
(8, 'sp1', 'sản phẩm thứ 1', 5, 20, 0, 6, 1, '2024-12-11 20:03:09', '2024-12-20 10:31:07'),
(11, 'Casio', 'máy tính cầm tay casio', 5, 0, 1, 12, 1, '2024-12-23 20:58:30', '2024-12-30 11:21:24'),
(12, 'Iphone 4', 'Iphone 4 8GB', 1, 0, 0, 2, 9, '2024-12-23 21:01:42', '2024-12-23 21:01:42'),
(14, 'SamSung S21', 'mdofjdlk fdjfdfjd', 5, 0, 0, 6, 1, '2024-12-30 11:36:03', '2024-12-30 11:36:33'),
(16, 'dfdf ', 'fs dfd fd', 1, 0, 1, 2, 1, '2025-01-01 10:27:19', '2025-01-01 10:27:19');

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE IF NOT EXISTS `receipt` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint(20) NOT NULL,
  `datetime` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `supplier_id` (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `receipt`
--

INSERT INTO `receipt` (`id`, `supplier_id`, `datetime`) VALUES
(1, 6, '2024-12-27 18:32:34'),
(2, 6, '2024-12-27 21:16:04'),
(3, 6, '2024-12-27 21:17:41'),
(4, 6, '2024-12-27 21:26:40'),
(5, 7, '2024-12-27 21:35:14'),
(6, 6, '2024-12-27 21:36:28'),
(7, 6, '2024-12-27 21:37:07'),
(8, 6, '2024-12-27 21:37:43'),
(9, 6, '2024-12-27 21:38:17'),
(10, 6, '2024-12-27 21:38:33'),
(11, 8, '2024-12-27 21:38:56'),
(12, 6, '2024-12-27 21:39:21'),
(13, 6, '2024-12-27 21:43:31'),
(14, 6, '2024-12-27 21:44:04'),
(15, 6, '2024-12-27 21:44:42'),
(16, 6, '2024-12-27 21:44:58'),
(17, 6, '2024-12-27 21:45:33'),
(18, 6, '2024-12-27 21:46:44'),
(19, 6, '2024-12-27 21:48:19'),
(20, 6, '2024-12-27 21:48:42'),
(21, 6, '2024-12-27 21:49:06'),
(22, 7, '2024-12-27 21:49:59'),
(23, 6, '2024-12-27 21:50:37'),
(24, 8, '2024-12-30 11:12:05'),
(25, 7, '2024-12-30 11:39:05'),
(26, 6, '2024-12-31 22:09:07'),
(27, 6, '2024-12-31 22:10:12'),
(28, 6, '2024-12-31 22:10:54'),
(29, 6, '2024-12-31 22:11:36'),
(30, 6, '2025-01-01 14:35:22');

-- --------------------------------------------------------

--
-- Table structure for table `receipt_details`
--

CREATE TABLE IF NOT EXISTS `receipt_details` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `receipt_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `price` bigint(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `receipt_id` (`receipt_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `receipt_details`
--

INSERT INTO `receipt_details` (`id`, `receipt_id`, `product_id`, `price`, `quantity`) VALUES
(1, 3, 5, 1000000, 10),
(2, 4, 5, 100000, 5),
(3, 5, 5, 2000000, 1),
(4, 6, 5, 1, 1),
(5, 7, 6, 1000000, 3),
(6, 7, 6, 1000000, 3),
(7, 8, 6, 10000000, 4),
(8, 8, 6, 10000000, 4),
(9, 9, 6, 1000000, 5),
(10, 10, 6, 1, 1),
(11, 11, 5, 1000000, 6),
(12, 12, 6, 10000000, 2),
(13, 12, 6, 10000000, 2),
(14, 13, 5, 2, 2),
(15, 14, 5, 2, 2),
(16, 22, 5, 1000000, 6),
(17, 22, 6, 20000000, 7),
(18, 23, 5, 11111, 1),
(19, 23, 5, 200000, 2),
(20, 24, 5, 2000000, 10),
(21, 24, 7, 3000000, 20),
(22, 25, 5, 2000000, 7),
(23, 25, 7, 4000000, 30),
(24, 26, 5, 100000, 2),
(25, 27, 5, 1000, 2),
(26, 27, 6, 1000, 9),
(27, 28, 5, 10000, 1),
(28, 28, 6, 2000, 5),
(29, 29, 5, 1000, 5),
(30, 29, 6, 1000, 2),
(31, 30, 5, 10, 4),
(32, 30, 6, 10, 3);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `description`) VALUES
(1, 'admin', 'người có quyền cao nhất trong hệ thống'),
(2, 'customer', 'khách hàng');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE IF NOT EXISTS `supplier` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `name`, `address`, `phone`, `email`) VALUES
(6, 'Nguyễn Văn Nam', 'Phường Đúc, Huế', '01234567', 'nam@gmail.com'),
(7, 'Nguyễn Tuấn Phong', 'Thuận An, Huế', '01234567', 'windy@gmail.com'),
(8, 'Bruno Fernandes', 'Bồ Đào Nha', '01234567', 'bruno@gmail.com'),
(11, 'Ronaldo', 'Ả Rập Xê Út', '012345677', 'cris@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE IF NOT EXISTS `unit` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `unit`
--

INSERT INTO `unit` (`id`, `name`, `description`) VALUES
(1, 'Cáiii', 'cái'),
(5, 'Chiếc', ''),
(7, 'bộ', '');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(500) NOT NULL,
  `familyname` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `familyname`, `firstname`, `phone`, `email`, `address`) VALUES
(1, 'admin', '827ccb0eea8a706c4c34a16891f84e7b', 'Nguyen', 'Nam', '0123456789', 'nam@gmail.com', 'Hue'),
(2, 'user01', '827ccb0eea8a706c4c34a16891f84e7b', 'Hồ Tấn', 'Quang Trung', '0123456789', 'user1@gmail.com', 'Thuỷ vân, Huế');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE IF NOT EXISTS `user_role` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `role_id`, `user_id`) VALUES
(1, 1, 1),
(2, 2, 2);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cartitems`
--
ALTER TABLE `cartitems`
  ADD CONSTRAINT `cartitems_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `cartitems_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `combodetails`
--
ALTER TABLE `combodetails`
  ADD CONSTRAINT `combodetails_ibfk_1` FOREIGN KEY (`combo_id`) REFERENCES `combo` (`id`),
  ADD CONSTRAINT `combodetails_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `image_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `price`
--
ALTER TABLE `price`
  ADD CONSTRAINT `price_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`id`),
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`),
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Constraints for table `receipt`
--
ALTER TABLE `receipt`
  ADD CONSTRAINT `receipt_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`);

--
-- Constraints for table `receipt_details`
--
ALTER TABLE `receipt_details`
  ADD CONSTRAINT `receipt_details_ibfk_1` FOREIGN KEY (`receipt_id`) REFERENCES `receipt` (`id`),
  ADD CONSTRAINT `receipt_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
