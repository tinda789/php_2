-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2025 at 08:52 PM
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
-- Database: `shopelectrics`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` enum('shipping','billing','both') DEFAULT 'both',
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `district` varchar(100) DEFAULT NULL,
  `ward` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'Vietnam',
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `link` varchar(500) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `position` enum('homepage_top','homepage_middle','homepage_bottom','sidebar','popup') DEFAULT 'homepage_top',
  `status` tinyint(1) DEFAULT 1,
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `logo` varchar(500) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `description`, `logo`, `website`, `is_active`, `created_at`) VALUES
(1, 'Apple', 'Công ty công nghệ hàng đầu thế giới', NULL, 'https://www.apple.com', 1, '2025-06-12 16:33:25'),
(2, 'Samsung', 'Tập đoàn điện tử đa quốc gia Hàn Quốc', NULL, 'https://www.samsung.com', 1, '2025-06-12 16:33:25'),
(3, 'Canon', 'Chuyên gia về máy ảnh và máy in', NULL, 'https://www.canon.com', 1, '2025-06-12 16:33:25'),
(4, 'Corsair', 'Thương hiệu gaming và PC cao cấp', NULL, 'https://www.corsair.com', 1, '2025-06-12 16:33:25'),
(5, 'ASUS', 'Thương hiệu máy tính và linh kiện', NULL, 'https://www.asus.com', 1, '2025-06-12 16:33:25'),
(6, 'Dell', 'Công ty máy tính và công nghệ', NULL, 'https://www.dell.com', 1, '2025-06-12 16:33:25'),
(7, 'HP', 'Hewlett-Packard Enterprise', NULL, 'https://www.hp.com', 1, '2025-06-12 16:33:25'),
(8, 'Sony', 'Tập đoàn giải trí và điện tử Nhật Bản', NULL, 'https://www.sony.com', 1, '2025-06-12 16:33:25'),
(9, 'MSI', NULL, NULL, NULL, 1, '2025-07-19 05:20:53'),
(10, 'GIGABYTE', NULL, NULL, NULL, 1, '2025-07-19 05:20:53'),
(11, 'Nikon', NULL, NULL, NULL, 1, '2025-07-19 05:20:53'),
(12, 'Zotac', NULL, NULL, NULL, 1, '2025-07-19 05:20:53'),
(13, '', NULL, NULL, NULL, 1, '2025-07-20 15:43:04'),
(14, 'Xiaomi', NULL, NULL, NULL, 1, '2025-07-23 09:38:40'),
(15, 'Oppo', NULL, NULL, NULL, 1, '2025-07-23 09:38:40'),
(16, 'Vivo', NULL, NULL, NULL, 1, '2025-07-23 09:38:40'),
(17, 'Realme', NULL, NULL, NULL, 1, '2025-07-23 09:38:40'),
(18, 'OnePlus', NULL, NULL, NULL, 1, '2025-07-23 09:38:40'),
(19, 'Google', NULL, NULL, NULL, 1, '2025-07-23 09:38:40'),
(20, 'Nokia', NULL, NULL, NULL, 1, '2025-07-23 09:38:40'),
(21, 'Motorola', NULL, NULL, NULL, 1, '2025-07-23 09:38:40'),
(22, 'Tecno', NULL, NULL, NULL, 1, '2025-07-23 09:38:40'),
(23, 'Vsmart', NULL, NULL, NULL, 1, '2025-07-23 09:38:40'),
(24, 'Masstel', NULL, NULL, NULL, 1, '2025-07-23 09:38:40'),
(25, 'Logitech', NULL, NULL, NULL, 1, '2025-07-23 10:13:14'),
(26, 'Razer', NULL, NULL, NULL, 1, '2025-07-23 10:13:14'),
(27, 'SteelSeries', NULL, NULL, NULL, 1, '2025-07-23 10:13:14'),
(28, 'Bose', NULL, NULL, NULL, 1, '2025-07-23 10:13:14'),
(29, 'JBL', NULL, NULL, NULL, 1, '2025-07-23 10:13:14'),
(30, 'Anker', NULL, NULL, NULL, 1, '2025-07-23 10:13:14'),
(31, 'Keychron', NULL, NULL, NULL, 1, '2025-07-23 10:13:14'),
(32, 'Akko', NULL, NULL, NULL, 1, '2025-07-23 10:13:14'),
(33, 'Ducky', NULL, NULL, NULL, 1, '2025-07-23 10:13:14'),
(34, 'E-DRA', NULL, NULL, NULL, 1, '2025-07-24 05:00:43'),
(35, 'Anda Seat', NULL, NULL, NULL, 1, '2025-07-24 05:00:43'),
(36, 'Cougar', NULL, NULL, NULL, 1, '2025-07-24 05:00:43'),
(37, 'HyperX', NULL, NULL, NULL, 1, '2025-07-24 05:00:43'),
(38, 'Elgato', NULL, NULL, NULL, 1, '2025-07-24 05:00:44'),
(39, 'Blue', NULL, NULL, NULL, 1, '2025-07-24 05:00:44'),
(40, 'Fifine', NULL, NULL, NULL, 1, '2025-07-24 05:00:44'),
(41, 'Microsoft', NULL, NULL, NULL, 1, '2025-07-24 05:00:44'),
(42, 'AVerMedia', NULL, NULL, NULL, 1, '2025-07-24 05:00:44'),
(43, 'DareU', NULL, NULL, NULL, 1, '2025-07-24 05:06:32'),
(44, 'Huawei', NULL, NULL, NULL, 1, '2025-07-24 05:06:32'),
(45, 'Garmin', NULL, NULL, NULL, 1, '2025-07-24 05:06:32'),
(46, 'Lenovo', NULL, NULL, NULL, 1, '2025-07-24 05:06:32'),
(47, 'LG', NULL, NULL, NULL, 1, '2025-07-24 05:08:29'),
(48, 'ViewSonic', NULL, NULL, NULL, 1, '2025-07-24 05:08:29'),
(49, 'Western Digital', NULL, NULL, NULL, 1, '2025-07-24 05:08:29'),
(50, 'Seagate', NULL, NULL, NULL, 1, '2025-07-24 05:08:29'),
(51, 'Kingston', NULL, NULL, NULL, 1, '2025-07-24 05:08:29'),
(52, 'Toshiba', NULL, NULL, NULL, 1, '2025-07-24 05:08:29'),
(53, 'Cooler Master', NULL, NULL, NULL, 1, '2025-07-24 05:08:29'),
(54, 'NZXT', NULL, NULL, NULL, 1, '2025-07-24 05:08:29'),
(55, 'Xigmatek', NULL, NULL, NULL, 1, '2025-07-24 05:08:29'),
(56, 'Lian Li', NULL, NULL, NULL, 1, '2025-07-24 05:08:29'),
(57, 'Vitra', NULL, NULL, NULL, 1, '2025-07-24 05:08:29'),
(58, 'G.SKILL', NULL, NULL, NULL, 1, '2025-07-24 05:10:24'),
(59, 'TeamGroup', NULL, NULL, NULL, 1, '2025-07-24 05:10:24'),
(60, 'ADATA', NULL, NULL, NULL, 1, '2025-07-24 05:10:24'),
(61, 'DeepCool', NULL, NULL, NULL, 1, '2025-07-24 05:10:24'),
(62, 'Noctua', NULL, NULL, NULL, 1, '2025-07-24 05:10:24'),
(63, 'Intel', NULL, NULL, NULL, 1, '2025-07-24 05:10:24'),
(64, 'AMD', NULL, NULL, NULL, 1, '2025-07-24 05:10:24'),
(65, 'ASRock', NULL, NULL, NULL, 1, '2025-07-24 05:12:42'),
(66, 'Antec', NULL, NULL, NULL, 1, '2025-07-24 05:12:42'),
(67, 'Thermaltake', NULL, NULL, NULL, 1, '2025-07-24 05:12:42'),
(68, 'FSP', NULL, NULL, NULL, 1, '2025-07-24 05:12:42'),
(69, 'Sapphire', NULL, NULL, NULL, 1, '2025-07-24 05:12:42'),
(70, 'Casper', NULL, NULL, NULL, 1, '2025-07-24 08:34:21'),
(71, 'TCL', NULL, NULL, NULL, 1, '2025-07-24 08:34:21'),
(72, 'Electrolux', NULL, NULL, NULL, 1, '2025-07-24 08:34:21'),
(73, 'Aqua', NULL, NULL, NULL, 1, '2025-07-24 08:34:21'),
(74, 'Daikin', NULL, NULL, NULL, 1, '2025-07-24 08:34:21'),
(75, 'Panasonic', NULL, NULL, NULL, 1, '2025-07-24 08:34:21'),
(76, 'Midea', NULL, NULL, NULL, 1, '2025-07-24 08:34:21'),
(77, 'Sharp', NULL, NULL, NULL, 1, '2025-07-24 08:51:16'),
(78, 'Mitsubishi', NULL, NULL, NULL, 1, '2025-07-24 08:51:16'),
(79, 'Ferroli', NULL, NULL, NULL, 1, '2025-07-24 08:51:16'),
(80, 'Ariston', NULL, NULL, NULL, 1, '2025-07-24 08:51:16'),
(81, 'Rossi', NULL, NULL, NULL, 1, '2025-07-24 08:51:16'),
(82, 'Centon', NULL, NULL, NULL, 1, '2025-07-24 08:51:16'),
(83, 'Beko', NULL, NULL, NULL, 1, '2025-07-24 08:51:16'),
(84, 'Lock&Lock', NULL, NULL, NULL, 1, '2025-07-24 08:51:16'),
(85, 'Sunhouse', NULL, NULL, NULL, 1, '2025-07-24 08:51:16'),
(86, 'Philips', NULL, NULL, NULL, 1, '2025-07-24 08:51:16'),
(87, 'Cuckoo', NULL, NULL, NULL, 1, '2025-07-24 08:51:16'),
(88, 'Mishio', NULL, NULL, NULL, 1, '2025-07-24 08:51:16'),
(89, 'Tiross', NULL, NULL, NULL, 1, '2025-07-24 08:51:16'),
(90, 'Ranbem', NULL, NULL, NULL, 1, '2025-07-24 08:51:16'),
(91, 'Bluestone', NULL, NULL, NULL, 1, '2025-07-24 08:51:16'),
(92, 'Rinnai', NULL, NULL, NULL, 1, '2025-07-24 08:51:16');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `added_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `parent_id`, `slug`, `image`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Điện thoại', 'Điện thoại thông minh và phụ kiện', NULL, 'dien-thoai', NULL, 1, 1, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(2, 'Máy tính', 'Laptop, PC và thiết bị máy tính', NULL, 'may-tinh', NULL, 1, 2, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(3, 'Máy ảnh', 'Máy ảnh số và phụ kiện nhiếp ảnh', NULL, 'may-anh', NULL, 1, 3, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(4, 'Linh kiện PC', 'RAM, CPU, VGA và linh kiện máy tính', NULL, 'linh-kien-pc', NULL, 1, 4, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(5, 'Phụ kiện', 'Phụ kiện và đồ dùng công nghệ', NULL, 'phu-kien', NULL, 1, 5, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(6, 'iPhone', 'Điện thoại iPhone của Apple', 1, 'iphone', NULL, 1, 1, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(7, 'Samsung Galaxy', 'Điện thoại Samsung Galaxy', 1, 'samsung-galaxy', NULL, 1, 2, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(8, 'Xiaomi', 'Điện thoại Xiaomi', 1, 'xiaomi', NULL, 1, 3, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(9, 'Laptop', 'Máy tính xách tay', 2, 'laptop', NULL, 1, 1, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(10, 'PC Desktop', 'Máy tính để bàn', 2, 'pc-desktop', NULL, 1, 2, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(11, 'Màn hình', 'Màn hình máy tính', 2, 'man-hinh', NULL, 1, 3, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(12, 'Máy ảnh DSLR', 'Máy ảnh phản xạ ống kính đơn', 3, 'may-anh-dslr', NULL, 1, 1, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(13, 'Máy ảnh Mirrorless', 'Máy ảnh không gương lật', 3, 'may-anh-mirrorless', NULL, 1, 2, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(14, 'Ống kính', 'Ống kính máy ảnh', 3, 'ong-kinh', NULL, 1, 3, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(15, 'CPU', 'Bộ vi xử lý', 4, 'cpu', NULL, 1, 1, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(16, 'RAM', 'Bộ nhớ trong', 4, 'ram', NULL, 1, 2, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(17, 'VGA', 'Card đồ họa', 4, 'vga', NULL, 1, 3, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(18, 'Mainboard', 'Bo mạch chủ', 4, 'mainboard', NULL, 1, 4, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(19, '', '', NULL, '', '', 1, 0, '2025-07-20 15:43:04', '2025-07-20 15:43:04'),
(20, 'Chuột máy tính', '', NULL, 'chu-t-m-y-t-nh', '', 1, 0, '2025-07-23 10:13:14', '2025-07-23 10:13:14'),
(21, 'Tai nghe', '', NULL, 'tai-nghe', '', 1, 0, '2025-07-23 10:13:14', '2025-07-23 10:13:14'),
(22, 'Bàn phím cơ', '', NULL, 'b-n-ph-m-c-', '', 1, 0, '2025-07-23 10:13:14', '2025-07-23 10:13:14'),
(23, 'Ghế gaming', '', NULL, 'gh-gaming', '', 1, 0, '2025-07-24 05:00:43', '2025-07-24 05:00:43'),
(24, 'Micro', '', NULL, 'micro', '', 1, 0, '2025-07-24 05:00:43', '2025-07-24 05:00:43'),
(25, 'Webcam', '', NULL, 'webcam', '', 1, 0, '2025-07-24 05:00:44', '2025-07-24 05:00:44'),
(26, 'Lót chuột', '', NULL, 'l-t-chu-t', '', 1, 0, '2025-07-24 05:06:32', '2025-07-24 05:06:32'),
(27, 'Đồng hồ thông minh', '', NULL, '-ng-h-th-ng-minh', '', 1, 0, '2025-07-24 05:06:32', '2025-07-24 05:06:32'),
(28, 'Máy tính bảng', '', NULL, 'm-y-t-nh-b-ng', '', 1, 0, '2025-07-24 05:06:32', '2025-07-24 05:06:32'),
(29, 'Màn hình máy tính', '', NULL, 'm-n-h-nh-m-y-t-nh', '', 1, 0, '2025-07-24 05:08:29', '2025-07-24 05:08:29'),
(30, 'Ổ cứng', '', NULL, '-c-ng', '', 1, 0, '2025-07-24 05:08:29', '2025-07-24 05:08:29'),
(31, 'Case máy tính', '', NULL, 'case-m-y-t-nh', '', 1, 0, '2025-07-24 05:08:29', '2025-07-24 05:08:29'),
(32, 'RAM PC', '', NULL, 'ram-pc', '', 1, 0, '2025-07-24 05:10:24', '2025-07-24 05:10:24'),
(33, 'Tản nhiệt', '', NULL, 't-n-nhi-t', '', 1, 0, '2025-07-24 05:10:24', '2025-07-24 05:10:24'),
(34, 'PSU', '', NULL, 'psu', '', 1, 0, '2025-07-24 05:12:42', '2025-07-24 05:12:42'),
(35, 'Tivi', '', NULL, 'tivi', '', 1, 0, '2025-07-24 08:34:21', '2025-07-24 08:34:21'),
(36, 'Máy giặt', '', NULL, 'm-y-gi-t', '', 1, 0, '2025-07-24 08:34:21', '2025-07-24 08:34:21'),
(37, 'Máy lạnh', '', NULL, 'm-y-l-nh', '', 1, 0, '2025-07-24 08:34:21', '2025-07-24 08:34:21'),
(38, 'Tủ lạnh', '', NULL, 't-l-nh', '', 1, 0, '2025-07-24 08:51:16', '2025-07-24 08:51:16'),
(39, 'Máy nước nóng', '', NULL, 'm-y-n-c-n-ng', '', 1, 0, '2025-07-24 08:51:16', '2025-07-24 08:51:16'),
(40, 'Gia dụng nhà bếp', '', NULL, 'gia-d-ng-nh-b-p', '', 1, 0, '2025-07-24 08:51:16', '2025-07-24 08:51:16');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('fixed','percentage') NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `minimum_amount` decimal(10,2) DEFAULT 0.00,
  `maximum_discount` decimal(10,2) DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) DEFAULT 0,
  `start_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `name`, `description`, `type`, `value`, `minimum_amount`, `maximum_discount`, `usage_limit`, `used_count`, `start_date`, `end_date`, `is_active`, `created_at`) VALUES
(1, 'WELCOME10', 'Chào mừng khách hàng mới', 'Giảm 10% cho đơn hàng đầu tiên', 'percentage', 10.00, 1000000.00, NULL, 100, 0, '2025-06-12 16:33:25', '2025-07-12 16:33:25', 1, '2025-06-12 16:33:25'),
(2, 'SUMMER2024', 'Khuyến mãi hè 2024', 'Giảm 500.000đ cho đơn hàng từ 10 triệu', 'fixed', 500000.00, 10000000.00, NULL, 50, 0, '2025-06-12 16:33:25', '2025-08-11 16:33:25', 1, '2025-06-12 16:33:25'),
(3, 'TECH20', 'Giảm giá công nghệ', 'Giảm 20% tối đa 2 triệu', 'percentage', 20.00, 5000000.00, NULL, 30, 0, '2025-06-12 16:33:25', '2025-07-27 16:33:25', 1, '2025-06-12 16:33:25');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `summary` varchar(500) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'published',
  `views` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `featured` tinyint(1) DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `summary`, `slug`, `content`, `image_url`, `category`, `status`, `views`, `is_active`, `featured`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'Laptop Acer Aspire 5 Gaming', 'aptop cực ngon trong tầm trung, thiết kế đẹp, hiệu năng mạnh mẽ, phù hợp cho cả học tập và giải trí.', 'laptop-acer-aspire-5-gaming', '  Laptop Acer Aspire 5 Gaming là lựa chọn tuyệt vời cho sinh viên và dân văn phòng. Máy sở hữu CPU Intel Core i5 thế hệ 12, RAM 8GB, SSD 512GB, card đồ họa rời GTX 1650 giúp xử lý tốt các tác vụ học tập, làm việc và giải trí. Thiết kế mỏng nhẹ, pin trâu, màn hình Full HD sắc nét. Giá bán hợp lý, bảo hành chính hãng 2 năm.', 'news_1752658868_9039.jpg', '9', 'published', 10, 1, 0, NULL, '2025-07-16 09:41:08', '2025-07-23 10:32:11');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','success','warning','error') DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_number` varchar(50) NOT NULL,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled','refunded') DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed','refunded','partial_refund') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `tax_amount` decimal(12,2) DEFAULT 0.00,
  `shipping_fee` decimal(12,2) DEFAULT 0.00,
  `discount_amount` decimal(12,2) DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL,
  `shipping_address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`shipping_address`)),
  `billing_address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`billing_address`)),
  `order_date` timestamp NULL DEFAULT current_timestamp(),
  `shipped_date` timestamp NULL DEFAULT NULL,
  `delivered_date` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `status`, `payment_status`, `payment_method`, `subtotal`, `tax_amount`, `shipping_fee`, `discount_amount`, `total_amount`, `shipping_address`, `billing_address`, `order_date`, `shipped_date`, `delivered_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'OD20250718104737397', 'pending', 'pending', 'vnpay', 31990000.00, 0.00, 0.00, 0.00, 31990000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"123456789\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"123456789\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-18 08:47:37', NULL, NULL, '', '2025-07-18 08:47:37', '2025-07-18 08:47:37'),
(2, 1, 'OD20250718105156731', 'pending', 'pending', 'vnpay', 28990000.00, 0.00, 0.00, 0.00, 28990000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"123456789\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"123456789\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-18 08:51:56', NULL, NULL, '', '2025-07-18 08:51:56', '2025-07-18 08:51:56'),
(3, 1, 'OD20250718105727146', 'pending', 'pending', 'vnpay', 31990000.00, 0.00, 0.00, 0.00, 31990000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"123456789\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"123456789\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-18 08:57:27', NULL, NULL, '', '2025-07-18 08:57:27', '2025-07-18 08:57:27'),
(4, 1, 'OD20250718111447322', 'pending', 'pending', 'vnpay', 52990000.00, 0.00, 0.00, 0.00, 52990000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"123456789\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"123456789\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-18 09:14:47', NULL, NULL, '', '2025-07-18 09:14:47', '2025-07-18 09:14:47'),
(5, 1, 'OD20250718111611237', 'pending', 'pending', 'vnpay', 52990000.00, 0.00, 0.00, 0.00, 52990000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-18 09:16:11', NULL, NULL, '', '2025-07-18 09:16:11', '2025-07-18 09:16:11'),
(6, 1, 'OD20250718112049260', 'pending', 'pending', 'vnpay', 52990000.00, 0.00, 0.00, 0.00, 52990000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-18 09:20:49', NULL, NULL, '', '2025-07-18 09:20:49', '2025-07-18 09:20:49'),
(7, 1, 'OD20250718112248133', 'pending', 'pending', 'vnpay', 139960000.00, 0.00, 0.00, 0.00, 139960000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-18 09:22:48', NULL, NULL, '', '2025-07-18 09:22:48', '2025-07-18 09:22:48'),
(8, 1, 'OD20250718112932750', 'pending', 'pending', 'vnpay', 52990000.00, 0.00, 0.00, 0.00, 52990000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-18 09:29:32', NULL, NULL, '', '2025-07-18 09:29:32', '2025-07-18 09:29:32'),
(9, 1, 'OD20250718113059844', 'pending', 'pending', 'vnpay', 28990000.00, 0.00, 0.00, 0.00, 28990000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-18 09:30:59', NULL, NULL, '', '2025-07-18 09:30:59', '2025-07-18 09:30:59'),
(10, 1, 'OD20250718163244597', 'pending', 'pending', 'vnpay', 28990000.00, 0.00, 0.00, 0.00, 28990000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-18 09:32:44', NULL, NULL, '', '2025-07-18 09:32:44', '2025-07-18 09:32:44'),
(11, 1, 'OD20250718163727284', 'pending', 'pending', 'vnpay', 28990000.00, 0.00, 0.00, 0.00, 28990000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-18 09:37:27', NULL, NULL, '', '2025-07-18 09:37:27', '2025-07-18 09:37:27'),
(12, 1, 'OD20250718164056230', 'pending', 'pending', 'vnpay', 28990000.00, 0.00, 0.00, 0.00, 28990000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-18 09:40:56', NULL, NULL, '', '2025-07-18 09:40:56', '2025-07-18 09:40:56'),
(13, 1, 'OD20250718164341443', 'pending', 'pending', 'vnpay', 31990000.00, 0.00, 0.00, 0.00, 31990000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-18 09:43:41', NULL, NULL, '', '2025-07-18 09:43:41', '2025-07-18 09:43:41'),
(14, 1, 'OD20250718164441753', 'pending', 'pending', 'vnpay', 31990000.00, 0.00, 0.00, 0.00, 31990000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-18 09:44:41', NULL, NULL, '', '2025-07-18 09:44:41', '2025-07-18 09:44:41'),
(15, 1, 'OD20250718164544107', 'pending', 'pending', 'vnpay', 63980000.00, 0.00, 0.00, 0.00, 63980000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-18 09:45:44', NULL, NULL, 'lll', '2025-07-18 09:45:44', '2025-07-18 09:45:44'),
(16, 1, 'OD20250718164819446', 'pending', 'pending', 'vnpay', 31990000.00, 0.00, 0.00, 0.00, 31990000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-18 09:48:19', NULL, NULL, '', '2025-07-18 09:48:19', '2025-07-18 09:48:19'),
(17, 1, 'OD20250718165054238', 'pending', 'pending', 'vnpay', 31990000.00, 0.00, 0.00, 0.00, 31990000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-18 09:50:54', NULL, NULL, '', '2025-07-18 09:50:54', '2025-07-18 09:50:54'),
(18, 1, 'OD20250718165527585', 'pending', 'pending', 'vnpay', 31990000.00, 0.00, 0.00, 0.00, 31990000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-18 09:55:27', NULL, NULL, '', '2025-07-18 09:55:27', '2025-07-18 09:55:27'),
(19, 1, 'OD20250718205935265', 'pending', 'pending', 'vnpay', 31990000.00, 0.00, 0.00, 0.00, 31990000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-18 13:59:35', NULL, NULL, '', '2025-07-18 13:59:35', '2025-07-18 13:59:35'),
(20, 1, 'OD20250723175426306', 'pending', 'pending', 'vnpay', 13000000.00, 0.00, 0.00, 0.00, 13000000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-23 10:54:26', NULL, NULL, '', '2025-07-23 10:54:26', '2025-07-23 10:54:26'),
(21, 1, 'OD20250723175728854', 'pending', 'pending', 'vnpay', 1900000.00, 0.00, 0.00, 0.00, 1900000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-23 10:57:28', NULL, NULL, '', '2025-07-23 10:57:28', '2025-07-23 10:57:28'),
(22, 1, 'OD20250723180000540', 'pending', 'pending', 'vnpay', 2500000.00, 0.00, 0.00, 0.00, 2500000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-23 11:00:00', NULL, NULL, '', '2025-07-23 11:00:00', '2025-07-23 11:00:00'),
(23, 1, 'OD20250723180333220', 'pending', 'pending', 'vnpay', 1900000.00, 0.00, 0.00, 0.00, 1900000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-23 11:03:33', NULL, NULL, '', '2025-07-23 11:03:33', '2025-07-23 11:03:33'),
(24, 1, 'OD20250723181014382', 'shipped', 'pending', 'vnpay', 2500000.00, 0.00, 0.00, 0.00, 2500000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-23 11:10:14', NULL, NULL, '', '2025-07-23 11:10:14', '2025-07-23 11:20:05'),
(25, 1, 'OD20250724200530538', 'pending', 'pending', 'vnpay', 2250000.00, 0.00, 0.00, 0.00, 2250000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-24 13:05:30', NULL, NULL, '', '2025-07-24 13:05:30', '2025-07-24 13:05:30'),
(26, 1, 'OD20250724200706885', 'pending', 'pending', 'vnpay', 2250000.00, 0.00, 0.00, 0.00, 2250000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-24 13:07:06', NULL, NULL, '', '2025-07-24 13:07:06', '2025-07-24 13:07:06'),
(27, 1, 'OD20250724201149423', 'pending', 'pending', 'vnpay', 6790000.00, 0.00, 0.00, 0.00, 6790000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-24 13:11:49', NULL, NULL, '', '2025-07-24 13:11:49', '2025-07-24 13:11:49'),
(28, 1, 'OD20250724201513480', 'pending', 'pending', 'vnpay', 15700000.00, 0.00, 0.00, 0.00, 15700000.00, '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '{\"name\":\"Đoàn Thịnh\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"CT\",\"district\":\"CT\",\"ward\":\"CT\"}', '2025-07-24 13:15:13', NULL, NULL, '', '2025-07-24 13:15:13', '2025-07-24 13:15:13'),
(29, 1, 'OD20250724203502458', 'pending', 'pending', 'vnpay', 10800000.00, 0.00, 0.00, 0.00, 10800000.00, '{\"name\":\"Lâm Thành Đạt\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"ctct\",\"district\":\"ct\",\"ward\":\"ct\"}', '{\"name\":\"Lâm Thành Đạt\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"ctct\",\"district\":\"ct\",\"ward\":\"ct\"}', '2025-07-24 13:35:02', NULL, NULL, '', '2025-07-24 13:35:02', '2025-07-24 13:35:02'),
(30, 1, 'OD20250724203537433', 'confirmed', 'paid', 'vnpay', 6790000.00, 0.00, 0.00, 0.00, 6790000.00, '{\"name\":\"Lâm Thành Đạt\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"ctct\",\"district\":\"ct\",\"ward\":\"ct\"}', '{\"name\":\"Lâm Thành Đạt\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"ctct\",\"district\":\"ct\",\"ward\":\"ct\"}', '2025-07-24 13:35:37', NULL, NULL, '\nThanh toán thành công qua VNPay. Mã giao dịch: 15097347, Ngân hàng: NCB, Ngày thanh toán: 20250724203701', '2025-07-24 13:35:37', '2025-07-24 13:36:49'),
(31, 1, 'OD20250724203738302', 'pending', 'pending', 'vnpay', 6790000.00, 0.00, 0.00, 0.00, 6790000.00, '{\"name\":\"Lâm Thành Đạt\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"ctct\",\"district\":\"ct\",\"ward\":\"ct\"}', '{\"name\":\"Lâm Thành Đạt\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"ctct\",\"district\":\"ct\",\"ward\":\"ct\"}', '2025-07-24 13:37:38', NULL, NULL, '', '2025-07-24 13:37:38', '2025-07-24 13:37:38'),
(32, 1, 'OD20250724203901107', 'pending', 'pending', 'vnpay', 8290000.00, 0.00, 0.00, 0.00, 8290000.00, '{\"name\":\"Lâm Thành Đạt\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"ctct\",\"district\":\"ct\",\"ward\":\"ct\"}', '{\"name\":\"Lâm Thành Đạt\",\"phone\":\"0834670200\",\"address\":\"KTX A\",\"city\":\"ctct\",\"district\":\"ct\",\"ward\":\"ct\"}', '2025-07-24 13:39:01', NULL, NULL, '', '2025-07-24 13:39:01', '2025-07-24 13:39:01');

-- --------------------------------------------------------

--
-- Table structure for table `order_coupons`
--

CREATE TABLE `order_coupons` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `coupon_code` varchar(50) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_sku` varchar(50) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_sku`, `quantity`, `unit_price`, `total_price`) VALUES
(1, 1, 1, 'iPhone 15 Pro Max 256GB', 'IP15PM256GB', 1, 31990000.00, 31990000.00),
(2, 2, 3, 'Samsung Galaxy S24 Ultra', 'SGS24U256GB', 1, 28990000.00, 28990000.00),
(3, 3, 1, 'iPhone 15 Pro Max 256GB', 'IP15PM256GB', 1, 31990000.00, 31990000.00),
(4, 4, 4, 'MacBook Pro M3 14 inch', 'MBP14M3512GB', 1, 52990000.00, 52990000.00),
(5, 5, 4, 'MacBook Pro M3 14 inch', 'MBP14M3512GB', 1, 52990000.00, 52990000.00),
(6, 6, 4, 'MacBook Pro M3 14 inch', 'MBP14M3512GB', 1, 52990000.00, 52990000.00),
(7, 7, 3, 'Samsung Galaxy S24 Ultra', 'SGS24U256GB', 3, 28990000.00, 86970000.00),
(8, 7, 4, 'MacBook Pro M3 14 inch', 'MBP14M3512GB', 1, 52990000.00, 52990000.00),
(9, 8, 4, 'MacBook Pro M3 14 inch', 'MBP14M3512GB', 1, 52990000.00, 52990000.00),
(10, 9, 3, 'Samsung Galaxy S24 Ultra', 'SGS24U256GB', 1, 28990000.00, 28990000.00),
(11, 10, 3, 'Samsung Galaxy S24 Ultra', 'SGS24U256GB', 1, 28990000.00, 28990000.00),
(12, 11, 3, 'Samsung Galaxy S24 Ultra', 'SGS24U256GB', 1, 28990000.00, 28990000.00),
(13, 12, 3, 'Samsung Galaxy S24 Ultra', 'SGS24U256GB', 1, 28990000.00, 28990000.00),
(14, 13, 1, 'iPhone 15 Pro Max 256GB', 'IP15PM256GB', 1, 31990000.00, 31990000.00),
(15, 14, 1, 'iPhone 15 Pro Max 256GB', 'IP15PM256GB', 1, 31990000.00, 31990000.00),
(16, 15, 1, 'iPhone 15 Pro Max 256GB', 'IP15PM256GB', 2, 31990000.00, 63980000.00),
(17, 16, 1, 'iPhone 15 Pro Max 256GB', 'IP15PM256GB', 1, 31990000.00, 31990000.00),
(18, 17, 1, 'iPhone 15 Pro Max 256GB', 'IP15PM256GB', 1, 31990000.00, 31990000.00),
(19, 18, 1, 'iPhone 15 Pro Max 256GB', 'IP15PM256GB', 1, 31990000.00, 31990000.00),
(20, 19, 1, 'iPhone 15 Pro Max 256GB', 'IP15PM256GB', 1, 31990000.00, 31990000.00),
(21, 20, 31, 'Tecno Phantom X2 Pro', 'TECPHX2P', 1, 13000000.00, 13000000.00),
(22, 21, 37, 'Asus ROG Gladius III', 'ASROGGL3', 1, 1900000.00, 1900000.00),
(23, 22, 34, 'Logitech MX Master 3S', 'LGMX3S', 1, 2500000.00, 2500000.00),
(24, 23, 37, 'Asus ROG Gladius III', 'ASROGGL3', 1, 1900000.00, 1900000.00),
(25, 24, 34, 'Logitech MX Master 3S', 'LGMX3S', 1, 2500000.00, 2500000.00),
(26, 25, 148, 'Máy nước nóng Ariston Slim2 20L', 'WHARIS20L', 1, 2250000.00, 2250000.00),
(27, 26, 148, 'Máy nước nóng Ariston Slim2 20L', 'WHARIS20L', 1, 2250000.00, 2250000.00),
(28, 27, 139, 'Tủ lạnh Samsung Inverter 208 lít RT20HAR8DBU/SV', 'FRSAM208L', 1, 6790000.00, 6790000.00),
(29, 28, 143, 'Tủ lạnh Toshiba Inverter 555 lít GR-RF610WE-PGV(37)-XK', 'FRTOSH555L', 1, 15700000.00, 15700000.00),
(30, 29, 142, 'Tủ lạnh LG Inverter 393 lít GN-D392BL', 'FRLG393L', 1, 10800000.00, 10800000.00),
(31, 30, 139, 'Tủ lạnh Samsung Inverter 208 lít RT20HAR8DBU/SV', 'FRSAM208L', 1, 6790000.00, 6790000.00),
(32, 31, 139, 'Tủ lạnh Samsung Inverter 208 lít RT20HAR8DBU/SV', 'FRSAM208L', 1, 6790000.00, 6790000.00),
(33, 32, 140, 'Tủ lạnh Panasonic Inverter 255 lít NR-BV289QSV2', 'FRPAN255L', 1, 8290000.00, 8290000.00);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `resource` varchar(50) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `description`, `resource`, `action`, `created_at`) VALUES
(1, 'manage_users', 'Quản lý người dùng', 'users', 'all', '2025-06-12 16:33:25'),
(2, 'manage_products', 'Quản lý sản phẩm', 'products', 'all', '2025-06-12 16:33:25'),
(3, 'manage_orders', 'Quản lý đơn hàng', 'orders', 'all', '2025-06-12 16:33:25'),
(4, 'view_reports', 'Xem báo cáo', 'reports', 'read', '2025-06-12 16:33:25'),
(5, 'manage_categories', 'Quản lý danh mục', 'categories', 'all', '2025-06-12 16:33:25'),
(6, 'manage_coupons', 'Quản lý mã giảm giá', 'coupons', 'all', '2025-06-12 16:33:25'),
(7, 'view_products', 'Xem sản phẩm', 'products', 'read', '2025-06-12 16:33:25'),
(8, 'create_orders', 'Tạo đơn hàng', 'orders', 'create', '2025-06-12 16:33:25'),
(9, 'view_own_orders', 'Xem đơn hàng của mình', 'orders', 'read_own', '2025-06-12 16:33:25'),
(10, 'write_reviews', 'Viết đánh giá', 'reviews', 'create', '2025-06-12 16:33:25');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `sale_price` decimal(12,2) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `min_stock_level` int(11) DEFAULT 5,
  `category_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `weight` decimal(8,2) DEFAULT NULL,
  `dimensions` varchar(100) DEFAULT NULL,
  `warranty_period` int(11) DEFAULT NULL,
  `status` enum('active','inactive','out_of_stock','discontinued') DEFAULT 'active',
  `featured` tinyint(1) DEFAULT 0,
  `image_link` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `description`, `short_description`, `price`, `sale_price`, `stock`, `min_stock_level`, `category_id`, `brand_id`, `model`, `sku`, `barcode`, `weight`, `dimensions`, `warranty_period`, `status`, `featured`, `image_link`, `meta_title`, `meta_description`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'iPhone 15 Pro Max 256GB', 'iphone-15-pro-max-256gb', 'iPhone 15 Pro Max với chip A17 Pro mạnh mẽ, camera chuyên nghiệp và thiết kế titanium cao cấp.', 'iPhone cao cấp nhất với màn hình 6.7 inch và camera 48MP', 32990000.00, 31990000.00, 500, 5, 6, 1, 'iPhone 15 Pro Max', 'IP15PM256GB', '0', 0.00, '', 0, 'active', 1, 'https://example.com/image1.jpg', '', '', 1, '2025-06-12 16:33:25', '2025-07-23 07:48:05'),
(3, 'Samsung Galaxy S24 Ultra', 'samsung-galaxy-s24-ultra', 'Flagship Android với S Pen tích hợp, camera zoom 100x và hiệu năng đỉnh cao.', 'Smartphone Android cao cấp nhất của Samsung', 29990000.00, 28990000.00, 30, 5, 7, 2, 'Galaxy S24 Ultra', 'SGS24U256GB', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6880941f8005a_1753256991.jpg', '', '', 1, '2025-06-12 16:33:25', '2025-07-23 07:49:51'),
(4, 'MacBook Pro M3 14 inch', 'macbook-pro-m3-14-inch', 'MacBook Pro với chip M3 mạnh mẽ, màn hình Liquid Retina XDR và thời lượng pin lên đến 22 giờ.', 'Laptop chuyên nghiệp cho sáng tạo', 52990000.00, 0.00, 20, 5, 9, 1, 'MacBook Pro 14\"', 'MBP14M3512GB', '0', 0.00, '', 0, 'active', 1, 'uploads/products/688094a75d69d_1753257127.jpeg', '', '', 1, '2025-06-12 16:33:25', '2025-07-23 07:52:07'),
(5, 'Dell XPS 13', 'dell-xps-13', 'Laptop siêu mỏng với màn hình InfinityEdge và hiệu năng Intel Core thế hệ 13.', 'Laptop Windows cao cấp, thiết kế đẹp', 28990000.00, 27990000.00, 25, 5, 9, 6, 'XPS 13', 'DELLXPS13I7', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6880955538c39_1753257301.jpg', '', '', 1, '2025-06-12 16:33:25', '2025-07-23 07:55:01'),
(6, 'Canon EOS R6 Mark II', 'canon-eos-r6-mark-ii', 'Máy ảnh mirrorless chuyên nghiệp với cảm biến full-frame 24.2MP và khả năng quay video 4K.', 'Máy ảnh mirrorless chuyên nghiệp', 45990000.00, 0.00, 15, 5, 12, 3, 'EOS R6 Mark II', 'CANR6M2BODY', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6880959a93a73_1753257370.jpg', '', '', 1, '2025-06-12 16:33:25', '2025-07-23 07:56:10'),
(8, 'Corsair Vengeance LPX 32GB DDR4', 'corsair-vengeance-lpx-32gb-ddr4', 'Bộ nhớ DDR4 32GB (2x16GB) tốc độ 3200MHz với tản nhiệt nhôm cao cấp.', 'RAM DDR4 hiệu năng cao cho gaming', 3290000.00, 0.00, 100, 5, 15, 4, 'Vengeance LPX', 'CORS32GB3200', '0', 0.00, '', 0, 'active', 0, 'uploads/products/688096b35511e_1753257651.jpg', '', '', 1, '2025-06-12 16:33:25', '2025-07-23 08:00:51'),
(9, 'AMD Ryzen 7 7700X', 'amd-ryzen-7-7700x', 'Bộ vi xử lý 8 nhân 16 luồng với kiến trúc Zen 4 và tốc độ boost lên đến 5.4GHz.', 'CPU AMD thế hệ mới cho gaming và làm việc', 8990000.00, 8490000.00, 40, 5, 14, 1, 'Ryzen 7 7700Xa', 'AMDR77700X', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6880972ac8533_1753257770.jpg', '', '', 1, '2025-06-12 16:33:25', '2025-07-23 08:02:50'),
(10, 'VGA ASUS ROG Strix RTX 4090', 'vga-asus-rog-strix-rtx-4090', '', '', 60000000.00, 58000000.00, 90, 17, 17, 5, '', 'VGA4090ASUS', '0', 0.00, '', 0, 'active', 1, 'uploads/products/688093a9db43b_1753256873.jpg', '', '', 1, '2025-07-19 05:16:52', '2025-07-23 07:47:53'),
(11, 'VGA MSI GeForce RTX 4070 Ti', 'vga-msi-geforce-rtx-4070-ti', '', '', 25000000.00, 24000000.00, 120, 0, 17, 9, '', 'VGA4070TIMSI', '0', 0.00, '', 0, 'active', 0, 'uploads/products/688092dfab139_1753256671.webp', '', '', 1, '2025-07-19 05:20:53', '2025-07-23 07:44:31'),
(12, 'VGA GIGABYTE RTX 4060', 'vga-gigabyte-rtx-4060', '', '', 12000000.00, 11500000.00, 160, 0, 17, 10, '', 'VGA4060GIGA', '0', 0.00, '', 0, 'active', 0, 'uploads/products/68809244e245f_1753256516.webp', '', '', 1, '2025-07-19 05:20:53', '2025-07-23 07:43:41'),
(13, 'Mainboard ASUS ROG Z790 Hero', 'mainboard-asus-rog-z790-hero', '', '', 15000000.00, 14500000.00, 64, 0, 18, 5, '', 'MBZ790ASUS', '0', 0.00, '', 0, 'active', 1, 'uploads/products/688092fbebea6_1753256699.jpg', '', '', 1, '2025-07-19 05:20:53', '2025-07-23 07:44:59'),
(14, 'Mainboard MSI B660M Mortar', 'mainboard-msi-b660m-mortar', '', '', 4000000.00, 3800000.00, 200, 0, 18, 9, '', 'MBB660MMSI', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6880931a7d5b1_1753256730.jpg', '', '', 1, '2025-07-19 05:20:53', '2025-07-23 07:45:30'),
(15, 'Mainboard GIGABYTE B550 AORUS', 'mainboard-gigabyte-b550-aorus', '', '', 3500000.00, 3300000.00, 144, 0, 18, 10, '', 'MBB550GIGA', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6880933652b26_1753256758.webp', '', '', 1, '2025-07-19 05:20:53', '2025-07-23 07:45:58'),
(16, 'Máy ảnh Canon EOS R6 Mark II', 'm-y-nh-canon-eos-r6-mark-ii', '', '', 52000000.00, 50000000.00, 40, 0, 3, 3, '', 'CAMR6CANON', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6880935025f7b_1753256784.jpg', '', '', 1, '2025-07-19 05:20:53', '2025-07-23 07:46:24'),
(17, 'Máy ảnh Sony Alpha A7 IV', 'm-y-nh-sony-alpha-a7-iv', '', '', 48000000.00, 47000000.00, 56, 0, 3, 8, '', 'CAMA7IVSONY', '0', 0.00, '', 0, 'active', 0, 'uploads/products/68809367789d7_1753256807.webp', '', '', 1, '2025-07-19 05:20:53', '2025-07-23 07:46:47'),
(18, 'Máy ảnh Nikon Z6 II', 'm-y-nh-nikon-z6-ii', '', '', 42000000.00, 41000000.00, 48, 0, 3, 11, '', 'CAMZ6IINIKON', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6880937891885_1753256824.jpg', '', '', 1, '2025-07-19 05:20:53', '2025-07-23 07:47:04'),
(19, 'VGA Zotac RTX 3060 Twin Edge', 'vga-zotac-rtx-3060-twin-edge', '', '', 9000000.00, 8500000.00, 96, 0, 17, 12, '', 'VGA3060ZOTAC', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6880939792140_1753256855.jpg', '', '', 1, '2025-07-19 05:20:53', '2025-07-23 07:47:35'),
(20, '', '', '', '', 0.00, 0.00, 0, 0, 19, 13, '', '', '0', 0.00, '', 0, '', 0, '', '', '', 1, '2025-07-20 16:09:13', '2025-07-20 16:09:13'),
(21, 'Xiaomi 14 Ultra', 'xiaomi-14-ultra', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 25000000.00, 24000000.00, 60, 0, 1, 14, '', 'XM14U', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6880ae158fdc0_1753263637.png', 'Xiaomi 14 Ultra', 'Chưa có mô tả ngắn.', 1, '2025-07-23 09:38:40', '2025-07-23 09:40:37'),
(22, 'Oppo Find X7 Pro', 'oppo-find-x7-pro', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 22000000.00, 21000000.00, 35, 0, 1, 15, '', 'OPFX7P', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6880ae734d9d5_1753263731.jpg', 'Xiaomi 14 Ultra', 'Chưa có mô tả ngắn.', 1, '2025-07-23 09:38:40', '2025-07-23 09:42:11'),
(23, 'Vivo X100 Pro', 'vivo-x100-pro', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 21000000.00, 20000000.00, 30, 0, 1, 16, '', 'VVX100P', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6880af05d8eb5_1753263877.jpeg', 'Vivo X100 Pro', 'Chưa có mô tả ngắn.', 1, '2025-07-23 09:38:40', '2025-07-23 09:44:37'),
(24, 'Realme GT 5 Pro', 'realme-gt-5-pro', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 18000000.00, 17000000.00, 25, 0, 1, 17, '', 'RMGT5P', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6880af6a7ae51_1753263978.jpg', 'Realme GT 5 Pro', 'Chưa có mô tả ngắn.', 1, '2025-07-23 09:38:40', '2025-07-23 09:46:18'),
(25, 'Asus ROG Phone 8', 'asus-rog-phone-8', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 26000000.00, 25000000.00, 20, 0, 1, 5, '', 'ASROG8', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6880af969682c_1753264022.png', 'Xiaomi 14 Ultra', 'Chưa có mô tả ngắn.', 1, '2025-07-23 09:38:40', '2025-07-23 09:47:02'),
(26, 'OnePlus 12', 'oneplus-12', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 20000000.00, 19000000.00, 18, 0, 1, 18, '', 'OP12', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6880afc54dd54_1753264069.png', 'OnePlus 12', 'Chưa có mô tả ngắn.', 1, '2025-07-23 09:38:40', '2025-07-23 09:47:49'),
(27, 'Google Pixel 8 Pro', 'google-pixel-8-pro', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 23000000.00, 22000000.00, 22, 0, 1, 19, '', 'GGPX8P', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6880b00d1250d_1753264141.jpg', 'Google Pixel 8 Pro', 'Chưa có mô tả ngắn.', 1, '2025-07-23 09:38:40', '2025-07-23 09:49:01'),
(28, 'Sony Xperia 1 V', 'sony-xperia-1-v', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 27000000.00, 26000000.00, 12, 0, 1, 8, '', 'SNYX1V', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6880b0485c54d_1753264200.png', 'Sony Xperia 1 V', 'Chưa có mô tả ngắn.', 1, '2025-07-23 09:38:40', '2025-07-23 09:50:00'),
(29, 'Nokia X30 5G', 'nokia-x30-5g', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 12000000.00, 11000000.00, 28, 0, 1, 20, '', 'NKX305G', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6880b08bbb0c4_1753264267.jpg', 'Nokia X30 5G', 'Chưa có mô tả ngắn.', 1, '2025-07-23 09:38:40', '2025-07-23 09:51:07'),
(30, 'Motorola Edge 40 Pro', 'motorola-edge-40-pro', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 17000000.00, 16000000.00, 15, 0, 1, 21, '', 'MOTEDG40P', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6880b0bc97358_1753264316.jpg', 'Motorola Edge 40 Pro', 'Chưa có mô tả ngắn.', 1, '2025-07-23 09:38:40', '2025-07-23 09:51:56'),
(31, 'Tecno Phantom X2 Pro', 'tecno-phantom-x2-pro', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 14000000.00, 13000000.00, 9, 0, 1, 22, '', 'TECPHX2P', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6880b12bdf8dd_1753264427.jpg', 'Tecno Phantom X2 Pro', 'Chưa có mô tả ngắn.', 1, '2025-07-23 09:38:40', '2025-07-23 10:54:26'),
(32, 'Vsmart Aris Pro', 'vsmart-aris-pro', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 9000000.00, 8500000.00, 8, 0, 1, 23, '', 'VSARISP', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6880b17b7fd4f_1753264507.png', 'Vsmart Aris Pro', 'Chưa có mô tả ngắn.', 1, '2025-07-23 09:38:40', '2025-07-23 09:55:07'),
(33, 'Masstel N6', 'masstel-n6', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1500000.00, 1400000.00, 50, 0, 1, 24, '', 'MASN6', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6880b1cde6bfd_1753264589.webp', 'Masstel N6', 'Chưa có mô tả ngắn.', 1, '2025-07-23 09:38:40', '2025-07-23 09:56:29'),
(34, 'Logitech MX Master 3S', 'logitech-mx-master-3s', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2800000.00, 2500000.00, 28, 0, 20, 25, '', 'LGMX3S', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881b5c324ea8_1753331139.jpg', 'Logitech MX Master 3S', 'Chưa có mô tả ngắn.', 1, '2025-07-23 10:13:14', '2025-07-24 04:25:39'),
(35, 'Razer DeathAdder V3 Pro', 'razer-deathadder-v3-pro', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 3200000.00, 3000000.00, 20, 0, 20, 26, '', 'RZDAV3P', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881b61e45ad4_1753331230.jpg', 'Razer DeathAdder V3 Pro', 'Chưa có mô tả ngắn.', 1, '2025-07-23 10:13:14', '2025-07-24 04:27:10'),
(36, 'Corsair Dark Core RGB Pro', 'corsair-dark-core-rgb-pro', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2700000.00, 2500000.00, 15, 0, 20, 4, '', 'CSRDRKRGB', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881b6637d948_1753331299.jpg', 'Corsair Dark Core RGB Pro', 'Chưa có mô tả ngắn.', 1, '2025-07-23 10:13:14', '2025-07-24 04:28:19'),
(37, 'Asus ROG Gladius III', 'asus-rog-gladius-iii', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2100000.00, 1900000.00, 16, 0, 20, 5, '', 'ASROGGL3', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881b696a2b79_1753331350.png', 'Asus ROG Gladius III', 'Chưa có mô tả ngắn.', 1, '2025-07-23 10:13:14', '2025-07-24 04:29:10'),
(38, 'SteelSeries Rival 600', 'steelseries-rival-600', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2300000.00, 2100000.00, 25, 0, 20, 27, '', 'SSRVL600', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881b72f0c7f9_1753331503.jpg', 'SteelSeries Rival 600', 'Chưa có mô tả ngắn.', 1, '2025-07-23 10:13:14', '2025-07-24 04:31:43'),
(39, 'Sony WH-1000XM5', 'sony-wh-1000xm5', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 9000000.00, 8500000.00, 10, 0, 21, 8, '', 'SNYWH1000XM5', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881b79bee66f_1753331611.jpg', 'Sony WH-1000XM5', 'Chưa có mô tả ngắn.', 1, '2025-07-23 10:13:14', '2025-07-24 04:33:31'),
(40, 'Apple AirPods Pro 2', 'apple-airpods-pro-2', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 6500000.00, 6000000.00, 20, 0, 21, 1, '', 'APPAPP2', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881b81b9e572_1753331739.jpg', 'Apple AirPods Pro 2', 'Chưa có mô tả ngắn.', 1, '2025-07-23 10:13:14', '2025-07-24 04:35:39'),
(41, 'Bose QuietComfort Ultra', 'bose-quietcomfort-ultra', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 8900000.00, 8700000.00, 12, 0, 21, 28, '', 'BOSEQCU', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881b86639673_1753331814.jpg', 'Bose QuietComfort Ultra', 'Chưa có mô tả ngắn.', 1, '2025-07-23 10:13:14', '2025-07-24 04:36:54'),
(42, 'JBL Tune 770NC', 'jbl-tune-770nc', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2500000.00, 2300000.00, 30, 0, 21, 29, '', 'JBLT770NC', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881b8f5f2ca5_1753331957.jpg', 'JBL Tune 770NC', 'Chưa có mô tả ngắn.', 1, '2025-07-23 10:13:14', '2025-07-24 04:39:17'),
(43, 'Anker Soundcore Life Q35', 'anker-soundcore-life-q35', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1900000.00, 1700000.00, 40, 0, 21, 30, '', 'ANKLQ35', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881b96a256de_1753332074.jpg', 'Anker Soundcore Life Q35', 'Chưa có mô tả ngắn.', 1, '2025-07-23 10:13:14', '2025-07-24 04:41:14'),
(44, 'Keychron K6 Wireless', 'keychron-k6-wireless', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2500000.00, 2300000.00, 22, 0, 22, 31, '', 'KEYK6W', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881b9c091407_1753332160.jpg', 'Keychron K6 Wireless', 'Chưa có mô tả ngắn.', 1, '2025-07-23 10:13:14', '2025-07-24 04:42:40'),
(45, 'Akko 3068B Plus', 'akko-3068b-plus', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1800000.00, 1650000.00, 18, 0, 22, 32, '', 'AKK3068BP', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881b9f241616_1753332210.jpg', 'Akko 3068B Plus', 'Chưa có mô tả ngắn.', 1, '2025-07-23 10:13:14', '2025-07-24 04:43:30'),
(46, 'Logitech G Pro X', 'logitech-g-pro-x', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 3200000.00, 3000000.00, 16, 0, 22, 25, '', 'LOGGPROX', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881bb0c53b7f_1753332492.png', 'Logitech G Pro X', 'Chưa có mô tả ngắn.', 1, '2025-07-23 10:13:14', '2025-07-24 04:48:12'),
(47, 'Razer BlackWidow V4', 'razer-blackwidow-v4', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 3500000.00, 3300000.00, 14, 0, 22, 26, '', 'RZBWV4', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881bb853d4b2_1753332613.jpg', 'Razer BlackWidow V4', 'Chưa có mô tả ngắn.', 1, '2025-07-23 10:13:14', '2025-07-24 04:50:13'),
(48, 'Ducky One 3 Mini', 'ducky-one-3-mini', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2900000.00, 2700000.00, 12, 0, 22, 33, '', 'DUCKYONE3M', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881bbdc5ce67_1753332700.jpg', 'Ducky One 3 Mini', 'Chưa có mô tả ngắn.', 1, '2025-07-23 10:13:14', '2025-07-24 04:51:40'),
(49, 'GHẾ GAMING E-DRA Hercules EGC203', 'gh-gaming-e-dra-hercules-egc203', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 3500000.00, 3200000.00, 15, 0, 23, 34, '', 'EDRAEGC203', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881e74a74c57_1753343818.jpg', 'GHẾ GAMING E-DRA Hercules EGC203', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:00:43', '2025-07-24 07:56:58'),
(50, 'GHẾ GAMING MSI MAG CH130 X', 'gh-gaming-msi-mag-ch130-x', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 5600000.00, 5300000.00, 10, 0, 23, 9, '', 'MSICH130X', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e72f09c98_1753343791.jpg', 'GHẾ GAMING MSI MAG CH130 X', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:00:43', '2025-07-24 07:56:31'),
(51, 'GHẾ GAMING Razer Iskur X', 'gh-gaming-razer-iskur-x', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 8900000.00, 8600000.00, 8, 0, 23, 26, '', 'RZRISKURX', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e70846e7e_1753343752.jpg', 'GHẾ GAMING Razer Iskur X', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:00:43', '2025-07-24 07:55:52'),
(52, 'GHẾ GAMING Anda Seat T-Pro 2', 'gh-gaming-anda-seat-t-pro-2', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 7000000.00, 6700000.00, 12, 0, 23, 35, '', 'ANDASTP2', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e6e9b26ee_1753343721.jpg', 'GHẾ GAMING Anda Seat T-Pro 2', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:00:43', '2025-07-24 07:55:21'),
(53, 'GHẾ GAMING Cougar Armor One', 'gh-gaming-cougar-armor-one', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 4500000.00, 4200000.00, 20, 0, 23, 36, '', 'CGARMOR1', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e6be0909f_1753343678.jpg', 'GHẾ GAMING Cougar Armor One', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:00:43', '2025-07-24 07:54:38'),
(54, 'Micro USB HyperX QuadCast', 'micro-usb-hyperx-quadcast', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 3200000.00, 3000000.00, 25, 0, 24, 37, '', 'HXQUADCAST', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881e69960d62_1753343641.jpg', 'Micro USB HyperX QuadCast', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:00:43', '2025-07-24 07:54:01'),
(55, 'Micro Razer Seiren Mini', 'micro-razer-seiren-mini', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1500000.00, 1350000.00, 30, 0, 24, 26, '', 'RZSEIRMINI', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e814053fb_1753344020.jpg', 'Micro Razer Seiren Mini', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:00:44', '2025-07-24 08:00:20'),
(56, 'Micro Elgato Wave:3', 'micro-elgato-wave-3', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 4000000.00, 3800000.00, 12, 0, 24, 38, '', 'ELGWAVE3', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e802713b6_1753344002.jpg', 'Micro Elgato Wave:3', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:00:44', '2025-07-24 08:00:02'),
(57, 'Micro Blue Yeti Nano', 'micro-blue-yeti-nano', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2700000.00, 2500000.00, 18, 0, 24, 39, '', 'BLYETINANO', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e7ec44b7c_1753343980.jpg', 'Micro Blue Yeti Nano', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:00:44', '2025-07-24 07:59:40'),
(58, 'Micro Fifine K690', 'micro-fifine-k690', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1900000.00, 1750000.00, 22, 0, 24, 40, '', 'FIFK690', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e7d81fd7b_1753343960.jpg', 'Micro Fifine K690', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:00:44', '2025-07-24 07:59:20'),
(59, 'Webcam Logitech C920 HD Pro', 'webcam-logitech-c920-hd-pro', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2100000.00, 2000000.00, 25, 0, 25, 25, '', 'LOGC920', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881e7be33b2c_1753343934.jpg', 'Webcam Logitech C920 HD Pro', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:00:44', '2025-07-24 07:58:54'),
(60, 'Webcam Razer Kiyo', 'webcam-razer-kiyo', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2600000.00, 2400000.00, 14, 0, 25, 26, '', 'RZKIYO', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e7a6d6234_1753343910.jpg', 'Webcam Razer Kiyo', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:00:44', '2025-07-24 07:58:30'),
(61, 'Webcam Elgato Facecam', 'webcam-elgato-facecam', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 4700000.00, 4500000.00, 10, 0, 25, 38, '', 'ELGFCAM', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e791baebb_1753343889.jpg', 'Webcam Elgato Facecam', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:00:44', '2025-07-24 07:58:09'),
(62, 'Webcam Microsoft LifeCam HD-3000', 'webcam-microsoft-lifecam-hd-3000', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 990000.00, 900000.00, 30, 0, 25, 41, '', 'MSHD3000', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e78015566_1753343872.jpg', 'Webcam Microsoft LifeCam HD-3000', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:00:44', '2025-07-24 07:57:52'),
(63, 'Webcam AVerMedia PW513 4K', 'webcam-avermedia-pw513-4k', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 5900000.00, 5600000.00, 8, 0, 25, 42, '', 'AVERPW513', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e75ce028d_1753343836.jpg', 'Webcam AVerMedia PW513 4K', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:00:44', '2025-07-24 07:57:16'),
(64, 'Lót chuột Logitech G640', 'l-t-chu-t-logitech-g640', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 600000.00, 550000.00, 40, 0, 26, 25, '', 'LOGG640', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e91abdaa9_1753344282.png', 'Lót chuột Logitech G640', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:06:32', '2025-07-24 08:04:42'),
(65, 'Lót chuột Razer Goliathus Chroma', 'l-t-chu-t-razer-goliathus-chroma', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 950000.00, 890000.00, 30, 0, 26, 26, '', 'RZGLCHRO', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881e90b1f1cb_1753344267.jpg', 'Lót chuột Razer Goliathus Chroma', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:06:32', '2025-07-24 08:04:27'),
(66, 'Lót chuột SteelSeries QcK Heavy', 'l-t-chu-t-steelseries-qck-heavy', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 550000.00, 500000.00, 35, 0, 26, 27, '', 'SSQCKHVY', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e8fb7cf96_1753344251.jpg', 'Lót chuột SteelSeries QcK Heavy', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:06:32', '2025-07-24 08:04:11'),
(67, 'Lót chuột DareU ESP111', 'l-t-chu-t-dareu-esp111', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 250000.00, 220000.00, 60, 0, 26, 43, '', 'DARESP111', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e8e69bd09_1753344230.jpg', 'Lót chuột DareU ESP111', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:06:32', '2025-07-24 08:03:50'),
(68, 'Lót chuột ASUS ROG Scabbard II', 'l-t-chu-t-asus-rog-scabbard-ii', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1200000.00, 1100000.00, 15, 0, 26, 5, '', 'ASROGSC2', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e8d98a6c1_1753344217.png', 'Lót chuột ASUS ROG Scabbard II', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:06:32', '2025-07-24 08:03:37'),
(69, 'Apple Watch Series 9 GPS 41mm', 'apple-watch-series-9-gps-41mm', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 10500000.00, 9900000.00, 20, 0, 27, 1, '', 'APW9GPS41', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881e8c2d9af9_1753344194.jpg', 'Apple Watch Series 9 GPS 41mm', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:06:32', '2025-07-24 08:03:14'),
(70, 'Samsung Galaxy Watch6 Classic 47mm', 'samsung-galaxy-watch6-classic-47mm', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 9500000.00, 9100000.00, 18, 0, 27, 2, '', 'SSGW6C47', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e8b39b2ab_1753344179.png', 'Samsung Galaxy Watch6 Classic 47mm', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:06:32', '2025-07-24 08:02:59'),
(71, 'Xiaomi Watch S1 Active', 'xiaomi-watch-s1-active', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 4600000.00, 4300000.00, 25, 0, 27, 14, '', 'XMWS1ACT', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e8a22ccaa_1753344162.jpg', 'Xiaomi Watch S1 Active', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:06:32', '2025-07-24 08:02:42'),
(72, 'Huawei Watch GT 3 Pro', 'huawei-watch-gt-3-pro', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 8200000.00, 7900000.00, 10, 0, 27, 44, '', 'HWWGT3PRO', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e8928d804_1753344146.png', 'Huawei Watch GT 3 Pro', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:06:32', '2025-07-24 08:02:26'),
(73, 'Garmin Forerunner 255', 'garmin-forerunner-255', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 9200000.00, 8800000.00, 12, 0, 27, 45, '', 'GRMFR255', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e880e3098_1753344128.jpg', 'Garmin Forerunner 255', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:06:32', '2025-07-24 08:02:08'),
(74, 'iPad Air 5 (Wi-Fi, 64GB)', 'ipad-air-5-wi-fi-64gb', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 16000000.00, 15500000.00, 14, 0, 28, 1, '', 'IPADA5W64', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881e86e8b2a2_1753344110.jpg', 'iPad Air 5 (Wi-Fi, 64GB)', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:06:32', '2025-07-24 08:01:50'),
(75, 'Samsung Galaxy Tab S9 FE 5G', 'samsung-galaxy-tab-s9-fe-5g', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 13500000.00, 12900000.00, 16, 0, 28, 2, '', 'SSTABS9FE', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e85d77fac_1753344093.jpg', 'Samsung Galaxy Tab S9 FE 5G', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:06:32', '2025-07-24 08:01:33'),
(76, 'Xiaomi Pad 6 (8GB/128GB)', 'xiaomi-pad-6-8gb-128gb', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 9500000.00, 9100000.00, 20, 0, 28, 14, '', 'XMIPAD6', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e84d2839b_1753344077.jpg', 'Xiaomi Pad 6 (8GB/128GB)', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:06:32', '2025-07-24 08:01:17'),
(77, 'Lenovo Tab M10 Gen 3', 'lenovo-tab-m10-gen-3', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 5800000.00, 5500000.00, 25, 0, 28, 46, '', 'LNVMT10G3', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e834930b2_1753344052.jpg', 'Lenovo Tab M10 Gen 3', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:06:32', '2025-07-24 08:00:52'),
(78, 'Huawei MatePad 11.5', 'huawei-matepad-11-5', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 8700000.00, 8300000.00, 13, 0, 28, 44, '', 'HWMP11P', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e823b0707_1753344035.png', 'Huawei MatePad 11.5', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:06:32', '2025-07-24 08:00:35'),
(79, 'Màn hình LG UltraGear 27GN750-B 240Hz', 'm-n-h-nh-lg-ultragear-27gn750-b-240hz', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 7200000.00, 6900000.00, 18, 0, 29, 47, '', 'LG27GN750', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881ea11b82d9_1753344529.png', 'Màn hình LG UltraGear 27GN750-B 240Hz', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:08:29', '2025-07-24 08:08:49'),
(80, 'Màn hình ASUS TUF Gaming VG249Q1A 165Hz', 'm-n-h-nh-asus-tuf-gaming-vg249q1a-165hz', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 4900000.00, 4600000.00, 25, 0, 29, 5, '', 'ASVG249Q1A', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881ea02cbdeb_1753344514.jpg', 'Màn hình ASUS TUF Gaming VG249Q1A 165Hz', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:08:29', '2025-07-24 08:08:34'),
(81, 'Màn hình Dell UltraSharp U2723QE 4K', 'm-n-h-nh-dell-ultrasharp-u2723qe-4k', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 13200000.00, 12800000.00, 10, 0, 29, 6, '', 'DELU2723QE', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e9f5867c2_1753344501.jpg', 'Màn hình Dell UltraSharp U2723QE 4K', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:08:29', '2025-07-24 08:08:21'),
(82, 'Màn hình Samsung Odyssey G5 32\" 2K', 'm-n-h-nh-samsung-odyssey-g5-32-2k', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 8300000.00, 8000000.00, 12, 0, 29, 2, '', 'SSODG532K', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e9e2c45a1_1753344482.jpg', 'Màn hình Samsung Odyssey G5 32\" 2K', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:08:29', '2025-07-24 08:08:02'),
(83, 'Màn hình ViewSonic VX3276-2K-MHD 32\"', 'm-n-h-nh-viewsonic-vx3276-2k-mhd-32', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 6900000.00, 6600000.00, 14, 0, 29, 48, '', 'VS3276MHD', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e9d3d19fb_1753344467.jpg', 'Màn hình ViewSonic VX3276-2K-MHD 32\"', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:08:29', '2025-07-24 08:07:47'),
(84, 'SSD Samsung 980 Pro 1TB M.2 NVMe', 'ssd-samsung-980-pro-1tb-m-2-nvme', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 3300000.00, 3100000.00, 20, 0, 30, 2, '', 'SS980PRO1TB', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881e9c52d904_1753344453.jpg', 'SSD Samsung 980 Pro 1TB M.2 NVMe', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:08:29', '2025-07-24 08:07:33'),
(85, 'SSD WD Black SN850X 1TB NVMe', 'ssd-wd-black-sn850x-1tb-nvme', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 3200000.00, 2950000.00, 15, 0, 30, 49, '', 'WDBSN850X1TB', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e9b48dcc6_1753344436.jpg', 'SSD WD Black SN850X 1TB NVMe', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:08:29', '2025-07-24 08:07:16'),
(86, 'HDD Seagate Barracuda 2TB 3.5\"', 'hdd-seagate-barracuda-2tb-3-5', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1500000.00, 1400000.00, 25, 0, 30, 50, '', 'SGBC2TB', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e9a6291a5_1753344422.jpg', 'HDD Seagate Barracuda 2TB 3.5\"', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:08:29', '2025-07-24 08:07:02'),
(87, 'SSD Kingston NV2 1TB M.2 NVMe', 'ssd-kingston-nv2-1tb-m-2-nvme', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2300000.00, 2150000.00, 30, 0, 30, 51, '', 'KNGNV21TB', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e997594f4_1753344407.jpg', 'SSD Kingston NV2 1TB M.2 NVMe', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:08:29', '2025-07-24 08:06:47'),
(88, 'HDD Toshiba 1TB 3.5\"', 'hdd-toshiba-1tb-3-5', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1100000.00, 1000000.00, 35, 0, 30, 52, '', 'TOS1TB', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e9863faf9_1753344390.jpg', 'HDD Toshiba 1TB 3.5\"', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:08:29', '2025-07-24 08:06:30'),
(89, 'Case Cooler Master MasterBox MB520 ARGB', 'case-cooler-master-masterbox-mb520-argb', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1650000.00, 1550000.00, 20, 0, 31, 53, '', 'CMMB520ARGB', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881e9783abb2_1753344376.jpeg', 'Case Cooler Master MasterBox MB520 ARGB', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:08:29', '2025-07-24 08:06:16'),
(90, 'Case NZXT H510 Mid Tower', 'case-nzxt-h510-mid-tower', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2100000.00, 2000000.00, 15, 0, 31, 54, '', 'NZXT510MT', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e96a184ae_1753344362.jpg', 'Case NZXT H510 Mid Tower', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:08:29', '2025-07-24 08:06:02'),
(91, 'Case Xigmatek Aquarius Plus Arctic', 'case-xigmatek-aquarius-plus-arctic', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1950000.00, 1850000.00, 18, 0, 31, 55, '', 'XGMAQPLUSARC', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e95797a8a_1753344343.png', 'Case Xigmatek Aquarius Plus Arctic', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:08:29', '2025-07-24 08:05:43'),
(92, 'Case Lian Li LANCOOL 216 RGB', 'case-lian-li-lancool-216-rgb', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2700000.00, 2500000.00, 10, 0, 31, 56, '', 'LL216RGB', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e93fef40c_1753344319.jpg', 'Case Lian Li LANCOOL 216 RGB', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:08:29', '2025-07-24 08:05:19'),
(93, 'Case Vitra Gaming S301', 'case-vitra-gaming-s301', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 850000.00, 790000.00, 25, 0, 31, 57, '', 'VTRS301', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881e92a5f616_1753344298.jpg', 'Case Vitra Gaming S301', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:08:29', '2025-07-24 08:04:58'),
(94, 'RAM Corsair Vengeance LPX 16GB (2x8GB) DDR4 3200MHz', 'ram-corsair-vengeance-lpx-16gb-2x8gb-ddr4-3200mhz', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1600000.00, 1500000.00, 20, 0, 32, 4, '', 'COR16GB3200', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881eb0a7301e_1753344778.jpg', 'RAM Corsair Vengeance LPX 16GB (2x8GB) DDR4 3200MHz', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:10:24', '2025-07-24 08:12:58'),
(95, 'RAM G.SKILL Ripjaws V 16GB (2x8GB) DDR4 3600MHz', 'ram-g-skill-ripjaws-v-16gb-2x8gb-ddr4-3600mhz', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1700000.00, 1550000.00, 15, 0, 32, 58, '', 'GSK16GB3600', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881eaff3a89f_1753344767.jpg', 'RAM G.SKILL Ripjaws V 16GB (2x8GB) DDR4 3600MHz', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:10:24', '2025-07-24 08:12:47'),
(96, 'RAM Kingston Fury Beast 16GB (2x8GB) DDR4 3200MHz', 'ram-kingston-fury-beast-16gb-2x8gb-ddr4-3200mhz', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1550000.00, 1450000.00, 25, 0, 32, 51, '', 'KINGFURY16GB', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881eaf4900e7_1753344756.jpg', 'RAM Kingston Fury Beast 16GB (2x8GB) DDR4 3200MHz', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:10:24', '2025-07-24 08:12:36'),
(97, 'RAM TeamGroup T-Force Delta RGB 16GB DDR4 3600MHz', 'ram-teamgroup-t-force-delta-rgb-16gb-ddr4-3600mhz', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1750000.00, 1600000.00, 18, 0, 32, 59, '', 'TFTFDELTA16', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881eae944685_1753344745.jpg', 'RAM TeamGroup T-Force Delta RGB 16GB DDR4 3600MHz', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:10:24', '2025-07-24 08:12:25'),
(98, 'RAM ADATA XPG GAMMIX D30 8GB DDR4 3200MHz', 'ram-adata-xpg-gammix-d30-8gb-ddr4-3200mhz', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 800000.00, 750000.00, 30, 0, 32, 60, '', 'ADATA8GB3200', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881eaddc7591_1753344733.jpg', 'RAM ADATA XPG GAMMIX D30 8GB DDR4 3200MHz', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:10:24', '2025-07-24 08:12:13'),
(99, 'Tản nhiệt khí Cooler Master Hyper 212 Black Edition', 't-n-nhi-t-kh-cooler-master-hyper-212-black-edition', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 850000.00, 790000.00, 20, 0, 33, 53, '', 'CMH212BE', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881ead2ba6f5_1753344722.jpg', 'Tản nhiệt khí Cooler Master Hyper 212 Black Edition', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:10:24', '2025-07-24 08:12:02'),
(100, 'Tản nhiệt khí DeepCool GAMMAXX 400 V2', 't-n-nhi-t-kh-deepcool-gammaxx-400-v2', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 600000.00, 550000.00, 22, 0, 33, 61, '', 'DCGAMMAXX400', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881eac6ec55f_1753344710.jpg', 'Tản nhiệt khí DeepCool GAMMAXX 400 V2', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:10:24', '2025-07-24 08:11:50'),
(101, 'Tản nhiệt nước AIO ASUS ROG Strix LC II 240 ARGB', 't-n-nhi-t-n-c-aio-asus-rog-strix-lc-ii-240-argb', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 3200000.00, 3100000.00, 10, 0, 33, 5, '', 'ASUSLCII240', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881eab68abd5_1753344694.jpg', 'Tản nhiệt nước AIO ASUS ROG Strix LC II 240 ARGB', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:10:24', '2025-07-24 08:11:34'),
(102, 'Tản nhiệt khí Noctua NH-D15 Chromax Black', 't-n-nhi-t-kh-noctua-nh-d15-chromax-black', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2900000.00, 2750000.00, 8, 0, 33, 62, '', 'NHD15CHROMAX', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881eaa92a21b_1753344681.jpg', 'Tản nhiệt khí Noctua NH-D15 Chromax Black', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:10:24', '2025-07-24 08:11:21'),
(103, 'Tản nhiệt nước AIO MSI MAG CORELIQUID C240', 't-n-nhi-t-n-c-aio-msi-mag-coreliquid-c240', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2500000.00, 2400000.00, 12, 0, 33, 9, '', 'MSIC240', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881ea9b8f0b4_1753344667.jpg', 'Tản nhiệt nước AIO MSI MAG CORELIQUID C240', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:10:24', '2025-07-24 08:11:07'),
(104, 'CPU Intel Core i5-12400F (6C/12T, up to 4.4GHz)', 'cpu-intel-core-i5-12400f-6c-12t-up-to-4-4ghz', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 4100000.00, 3950000.00, 20, 0, 15, 63, '', 'I512400F', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881ea8c43858_1753344652.jpg', 'CPU Intel Core i5-12400F (6C/12T, up to 4.4GHz)', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:10:24', '2025-07-24 08:10:52'),
(105, 'CPU AMD Ryzen 5 5600X (6C/12T, up to 4.6GHz)', 'cpu-amd-ryzen-5-5600x-6c-12t-up-to-4-6ghz', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 4600000.00, 4450000.00, 15, 0, 15, 64, '', 'R55600X', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881ea779712f_1753344631.jpg', 'CPU AMD Ryzen 5 5600X (6C/12T, up to 4.6GHz)', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:10:24', '2025-07-24 08:10:31'),
(106, 'CPU Intel Core i7-13700K (16C/24T, up to 5.4GHz)', 'cpu-intel-core-i7-13700k-16c-24t-up-to-5-4ghz', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 9800000.00, 9500000.00, 10, 0, 15, 63, '', 'I713700K', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881ea62730fc_1753344610.jpg', 'CPU Intel Core i7-13700K (16C/24T, up to 5.4GHz)', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:10:24', '2025-07-24 08:10:10'),
(107, 'CPU AMD Ryzen 7 5800X3D (8C/16T, 4.5GHz)', 'cpu-amd-ryzen-7-5800x3d-8c-16t-4-5ghz', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 9200000.00, 8900000.00, 12, 0, 15, 64, '', 'R75800X3D', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881ea4b05d6e_1753344587.jpg', 'CPU AMD Ryzen 7 5800X3D (8C/16T, 4.5GHz)', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:10:24', '2025-07-24 08:09:47'),
(108, 'CPU Intel Core i3-12100F (4C/8T, up to 4.3GHz)', 'cpu-intel-core-i3-12100f-4c-8t-up-to-4-3ghz', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2800000.00, 2650000.00, 18, 0, 15, 63, '', 'I312100F', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881ea347007b_1753344564.png', 'CPU Intel Core i3-12100F (4C/8T, up to 4.3GHz)', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:10:24', '2025-07-24 08:09:24'),
(109, 'Mainboard ASUS PRIME B660M-A D4', 'mainboard-asus-prime-b660m-a-d4', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 3100000.00, 2950000.00, 20, 0, 18, 5, '', 'ASUSB660MA', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881ec06f12e8_1753345030.jpg', 'Mainboard ASUS PRIME B660M-A D4', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:12:42', '2025-07-24 08:17:10'),
(110, 'Mainboard MSI B550M PRO-VDH WIFI', 'mainboard-msi-b550m-pro-vdh-wifi', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2900000.00, 2750000.00, 15, 0, 18, 9, '', 'MSIB550VDH', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881ebfb077df_1753345019.jpg', 'Mainboard MSI B550M PRO-VDH WIFI', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:12:42', '2025-07-24 08:16:59'),
(111, 'Mainboard GIGABYTE B760M DS3H DDR4', 'mainboard-gigabyte-b760m-ds3h-ddr4', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2950000.00, 2800000.00, 18, 0, 18, 10, '', 'GIGAB760MDS3H', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881ebf0b1cd1_1753345008.png', 'Mainboard GIGABYTE B760M DS3H DDR4', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:12:42', '2025-07-24 08:16:48'),
(112, 'Mainboard ASRock B450M Steel Legend', 'mainboard-asrock-b450m-steel-legend', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2600000.00, 2500000.00, 12, 0, 18, 65, '', 'ASRB450MSTL', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881ebe4c305b_1753344996.jpg', 'Mainboard ASRock B450M Steel Legend', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:12:42', '2025-07-24 08:16:36'),
(113, 'Mainboard ASUS TUF GAMING B660M-PLUS WIFI D4', 'mainboard-asus-tuf-gaming-b660m-plus-wifi-d4', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 3500000.00, 3350000.00, 10, 0, 18, 5, '', 'ASUSTUFB660M', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881ebd8e2b72_1753344984.png', 'Mainboard ASUS TUF GAMING B660M-PLUS WIFI D4', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:12:42', '2025-07-24 08:16:24'),
(114, 'PSU Cooler Master MWE 550W V2 80 Plus Bronze', 'psu-cooler-master-mwe-550w-v2-80-plus-bronze', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1150000.00, 1090000.00, 25, 0, 34, 53, '', 'CM550WBRNZ', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881ebbdb5c68_1753344957.jpg', 'PSU Cooler Master MWE 550W V2 80 Plus Bronze', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:12:42', '2025-07-24 08:15:57'),
(115, 'PSU Corsair CV650 650W 80 Plus Bronze', 'psu-corsair-cv650-650w-80-plus-bronze', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1250000.00, 1190000.00, 20, 0, 34, 4, '', 'CORCV650BRZ', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881eb7c723b2_1753344892.jpg', 'PSU Corsair CV650 650W 80 Plus Bronze', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:12:42', '2025-07-24 08:14:52'),
(116, 'PSU Antec Atom B650 650W', 'psu-antec-atom-b650-650w', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1000000.00, 950000.00, 30, 0, 34, 66, '', 'ANTECATOM650', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881eb6c94924_1753344876.jpg', 'PSU Antec Atom B650 650W', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:12:42', '2025-07-24 08:14:36'),
(117, 'PSU Thermaltake Smart 700W 80 Plus', 'psu-thermaltake-smart-700w-80-plus', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1350000.00, 1290000.00, 18, 0, 34, 67, '', 'TTSMART700', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881eb5feef21_1753344863.jpg', 'PSU Thermaltake Smart 700W 80 Plus', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:12:42', '2025-07-24 08:14:23'),
(118, 'PSU FSP Power Supply Hydro PRO 600W', 'psu-fsp-power-supply-hydro-pro-600w', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1200000.00, 1120000.00, 22, 0, 34, 68, '', 'FSPHYDRO600', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881eb56ca982_1753344854.png', 'PSU FSP Power Supply Hydro PRO 600W', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:12:42', '2025-07-24 08:14:14'),
(119, 'VGA ASUS Dual GeForce RTX 3060 12GB', 'vga-asus-dual-geforce-rtx-3060-12gb', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 8500000.00, 8200000.00, 10, 0, 17, 5, '', 'ASUS306012GB', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881eb4d41224_1753344845.png', 'VGA ASUS Dual GeForce RTX 3060 12GB', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:12:42', '2025-07-24 08:14:05'),
(120, 'VGA GIGABYTE Radeon RX 6600 Eagle 8G', 'vga-gigabyte-radeon-rx-6600-eagle-8g', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 7400000.00, 7100000.00, 12, 0, 17, 10, '', 'GIGARX6600EGL', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881eb3f67192_1753344831.jpg', 'VGA GIGABYTE Radeon RX 6600 Eagle 8G', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:12:42', '2025-07-24 08:13:51'),
(121, 'VGA MSI GeForce GTX 1660 Super Ventus XS OC', 'vga-msi-geforce-gtx-1660-super-ventus-xs-oc', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 6700000.00, 6450000.00, 15, 0, 17, 9, '', 'MSIGTX1660S', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881eb32247e3_1753344818.jpg', 'VGA MSI GeForce GTX 1660 Super Ventus XS OC', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:12:42', '2025-07-24 08:13:38'),
(122, 'VGA ZOTAC GeForce RTX 4060 Twin Edge 8GB', 'vga-zotac-geforce-rtx-4060-twin-edge-8gb', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 9300000.00, 8990000.00, 8, 0, 17, 12, '', 'ZOTAC4060TE', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881eb25528a0_1753344805.jpg', 'VGA ZOTAC GeForce RTX 4060 Twin Edge 8GB', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:12:42', '2025-07-24 08:13:25'),
(123, 'VGA Sapphire Pulse Radeon RX 6700 XT 12GB', 'vga-sapphire-pulse-radeon-rx-6700-xt-12gb', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 10500000.00, 9990000.00, 6, 0, 17, 69, '', 'SAPRX6700XT', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881eb17a69ee_1753344791.jpg', 'VGA Sapphire Pulse Radeon RX 6700 XT 12GB', 'Chưa có mô tả ngắn.', 1, '2025-07-24 05:12:42', '2025-07-24 08:13:11'),
(124, 'Smart Tivi Samsung 4K 55 inch UA55AU7002', 'smart-tivi-samsung-4k-55-inch-ua55au7002', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 11900000.00, 10900000.00, 10, 0, 35, 2, '', 'TVSAM55AU', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881f332ebee2_1753346866.jpg', 'Smart Tivi Samsung 4K 55 inch UA55AU7002', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:34:21', '2025-07-24 08:47:46'),
(125, 'Smart Tivi LG 43 inch 4K UHD 43UQ7550PSF', 'smart-tivi-lg-43-inch-4k-uhd-43uq7550psf', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 8490000.00, 7990000.00, 15, 0, 35, 47, '', 'TVLG43UQ', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f31268569_1753346834.jpg', 'Smart Tivi LG 43 inch 4K UHD 43UQ7550PSF', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:34:21', '2025-07-24 08:47:14'),
(126, 'Tivi Sony Bravia 4K 50 inch KD-50X75K', 'tivi-sony-bravia-4k-50-inch-kd-50x75k', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 11700000.00, 11200000.00, 12, 0, 35, 8, '', 'TVSONY50X75', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f2d861e4e_1753346776.jpg', 'Tivi Sony Bravia 4K 50 inch KD-50X75K', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:34:21', '2025-07-24 08:46:16'),
(127, 'Tivi Casper 43 inch 4K UHD 43UG6100', 'tivi-casper-43-inch-4k-uhd-43ug6100', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 5900000.00, 5490000.00, 8, 0, 35, 70, '', 'TVCAS43UG', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f2b591933_1753346741.jpg', 'Tivi Casper 43 inch 4K UHD 43UG6100', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:34:21', '2025-07-24 08:45:41'),
(128, 'Smart Tivi TCL 50 inch 4K UHD 50P635', 'smart-tivi-tcl-50-inch-4k-uhd-50p635', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 7790000.00, 7390000.00, 10, 0, 35, 71, '', 'TVTCL50P635', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f28324c82_1753346691.jpg', 'Smart Tivi TCL 50 inch 4K UHD 50P635', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:34:21', '2025-07-24 08:44:51'),
(129, 'Máy giặt LG Inverter 9Kg FV1409S2V', 'm-y-gi-t-lg-inverter-9kg-fv1409s2v', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 8990000.00, 8590000.00, 14, 0, 36, 47, '', 'WMLG9KGINV', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f25d355eb_1753346653.jpg', 'Máy giặt LG Inverter 9Kg FV1409S2V', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:34:21', '2025-07-24 08:44:13'),
(130, 'Máy giặt Samsung Inverter 8.5Kg WW85T4040CX/SV', 'm-y-gi-t-samsung-inverter-8-5kg-ww85t4040cx-sv', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 7490000.00, 7090000.00, 10, 0, 36, 2, '', 'WMSAM8.5KG', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f23ee1789_1753346622.png', 'Máy giặt Samsung Inverter 8.5Kg WW85T4040CX/SV', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:34:21', '2025-07-24 08:43:42'),
(131, 'Máy giặt Electrolux Inverter 10Kg EWF1024P5WB', 'm-y-gi-t-electrolux-inverter-10kg-ewf1024p5wb', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 11490000.00, 10700000.00, 7, 0, 36, 72, '', 'WMELEC10KG', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881f20e69a7b_1753346574.jpg', 'Máy giặt Electrolux Inverter 10Kg EWF1024P5WB', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:34:21', '2025-07-24 08:42:54'),
(132, 'Máy giặt Toshiba Inverter 9.5Kg TW-BK105G4V', 'm-y-gi-t-toshiba-inverter-9-5kg-tw-bk105g4v', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 8390000.00, 7990000.00, 9, 0, 36, 52, '', 'WMTOSH9.5', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f1f3b2e40_1753346547.jpg', 'Máy giặt Toshiba Inverter 9.5Kg TW-BK105G4V', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:34:21', '2025-07-24 08:42:27'),
(133, 'Máy giặt Aqua 8Kg AQW-FR805AT.S', 'm-y-gi-t-aqua-8kg-aqw-fr805at-s', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 5790000.00, 5390000.00, 11, 0, 36, 73, '', 'WMAQUA8KG', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f1d344b23_1753346515.jpg', 'Máy giặt Aqua 8Kg AQW-FR805AT.S', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:34:21', '2025-07-24 08:41:55'),
(134, 'Máy lạnh Daikin Inverter 1HP FTKY25WAVMV', 'm-y-l-nh-daikin-inverter-1hp-ftky25wavmv', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 9990000.00, 9500000.00, 12, 0, 37, 74, '', 'ACDAI1HP', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f12c95411_1753346348.jpg', 'Máy lạnh Daikin Inverter 1HP FTKY25WAVMV', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:34:21', '2025-07-24 08:39:08'),
(135, 'Máy lạnh Panasonic Inverter 1.5HP CU/CS-XU12ZKH-8', 'm-y-l-nh-panasonic-inverter-1-5hp-cu-cs-xu12zkh-8', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 13300000.00, 12700000.00, 8, 0, 37, 75, '', 'ACPANA1.5HP', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881f0fb2f4dd_1753346299.jpg', 'Máy lạnh Panasonic Inverter 1.5HP CU/CS-XU12ZKH-8', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:34:21', '2025-07-24 08:38:19'),
(136, 'Máy lạnh LG Inverter 1.5HP V13API1', 'm-y-l-nh-lg-inverter-1-5hp-v13api1', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 11600000.00, 11000000.00, 10, 0, 37, 47, '', 'ACLGINV1.5', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f0cde60a0_1753346253.jpg', 'Máy lạnh LG Inverter 1.5HP V13API1', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:34:21', '2025-07-24 08:37:33'),
(137, 'Máy lạnh Midea Inverter 1HP MS11D1-10CRDN1', 'm-y-l-nh-midea-inverter-1hp-ms11d1-10crdn1', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 6990000.00, 6590000.00, 14, 0, 37, 76, '', 'ACMIDEA1HP', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f0a1c3aa3_1753346209.jpg', 'Máy lạnh Midea Inverter 1HP MS11D1-10CRDN1', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:34:21', '2025-07-24 08:36:49'),
(138, 'Máy lạnh Casper Inverter 1HP GC-09IS32', 'm-y-l-nh-casper-inverter-1hp-gc-09is32', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 6090000.00, 5790000.00, 13, 0, 37, 70, '', 'ACCASP1HP', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f07626532_1753346166.jpg', 'Máy lạnh Casper Inverter 1HP GC-09IS32', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:34:21', '2025-07-24 08:36:06'),
(139, 'Tủ lạnh Samsung Inverter 208 lít RT20HAR8DBU/SV', 't-l-nh-samsung-inverter-208-l-t-rt20har8dbu-sv', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 7190000.00, 6790000.00, 7, 0, 38, 2, '', 'FRSAM208L', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f99daa1c9_1753348509.jpg', 'Tủ lạnh Samsung Inverter 208 lít RT20HAR8DBU/SV', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 13:37:38'),
(140, 'Tủ lạnh Panasonic Inverter 255 lít NR-BV289QSV2', 't-l-nh-panasonic-inverter-255-l-t-nr-bv289qsv2', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 8790000.00, 8290000.00, 6, 0, 38, 75, '', 'FRPAN255L', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f97374687_1753348467.jpg', 'Tủ lạnh Panasonic Inverter 255 lít NR-BV289QSV2', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 13:39:01'),
(141, 'Tủ lạnh Aqua Inverter 260 lít AQR-I287BN PS', 't-l-nh-aqua-inverter-260-l-t-aqr-i287bn-ps', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 6990000.00, 6590000.00, 9, 0, 38, 73, '', 'FRAQUA260L', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f952e57ab_1753348434.jpg', 'Tủ lạnh Aqua Inverter 260 lít AQR-I287BN PS', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 09:13:54');
INSERT INTO `products` (`id`, `name`, `slug`, `description`, `short_description`, `price`, `sale_price`, `stock`, `min_stock_level`, `category_id`, `brand_id`, `model`, `sku`, `barcode`, `weight`, `dimensions`, `warranty_period`, `status`, `featured`, `image_link`, `meta_title`, `meta_description`, `created_by`, `created_at`, `updated_at`) VALUES
(142, 'Tủ lạnh LG Inverter 393 lít GN-D392BL', 't-l-nh-lg-inverter-393-l-t-gn-d392bl', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 11490000.00, 10800000.00, 5, 0, 38, 47, '', 'FRLG393L', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881f945c6744_1753348421.jpg', 'Tủ lạnh LG Inverter 393 lít GN-D392BL', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 13:35:02'),
(143, 'Tủ lạnh Toshiba Inverter 555 lít GR-RF610WE-PGV(37)-XK', 't-l-nh-toshiba-inverter-555-l-t-gr-rf610we-pgv-37-xk', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 16490000.00, 15700000.00, 4, 0, 38, 52, '', 'FRTOSH555L', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f93636694_1753348406.jpg', 'Tủ lạnh Toshiba Inverter 555 lít GR-RF610WE-PGV(37)-XK', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 13:15:13'),
(144, 'Tủ lạnh Sharp Inverter 196 lít SJ-X201E-SL', 't-l-nh-sharp-inverter-196-l-t-sj-x201e-sl', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 5190000.00, 4890000.00, 8, 0, 38, 77, '', 'FRSHARP196L', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f926d5548_1753348390.jpg', 'Tủ lạnh Sharp Inverter 196 lít SJ-X201E-SL', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 09:13:10'),
(145, 'Tủ lạnh Electrolux Inverter 308 lít EBB3442K-A', 't-l-nh-electrolux-inverter-308-l-t-ebb3442k-a', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 10490000.00, 9990000.00, 6, 0, 38, 72, '', 'FRELEC308L', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f910a4d53_1753348368.jpg', 'Tủ lạnh Electrolux Inverter 308 lít EBB3442K-A', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 09:12:48'),
(146, 'Tủ lạnh Mitsubishi Electric 365 lít MR-FX47EN-GSL-V', 't-l-nh-mitsubishi-electric-365-l-t-mr-fx47en-gsl-v', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 12990000.00, 12400000.00, 4, 0, 38, 78, '', 'FRMITSU365L', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f8febb550_1753348350.jpg', 'Tủ lạnh Mitsubishi Electric 365 lít MR-FX47EN-GSL-V', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 09:12:30'),
(147, 'Máy nước nóng Ferroli 20L VERDI-SE 20L', 'm-y-n-c-n-ng-ferroli-20l-verdi-se-20l', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2090000.00, 1990000.00, 12, 0, 39, 79, '', 'WHFER20L', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f8ee72d25_1753348334.jpg', 'Máy nước nóng Ferroli 20L VERDI-SE 20L', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 09:12:14'),
(148, 'Máy nước nóng Ariston Slim2 20L', 'm-y-n-c-n-ng-ariston-slim2-20l', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2390000.00, 2250000.00, 8, 0, 39, 80, '', 'WHARIS20L', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881f8e1ccf16_1753348321.jpg', 'Máy nước nóng Ariston Slim2 20L', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 13:07:06'),
(149, 'Máy nước nóng Panasonic DH-4RP2VK', 'm-y-n-c-n-ng-panasonic-dh-4rp2vk', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2590000.00, 2450000.00, 9, 0, 39, 75, '', 'WHPANA4RP2', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f8ce30281_1753348302.jpg', 'Máy nước nóng Panasonic DH-4RP2VK', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 09:11:42'),
(150, 'Máy nước nóng Rossi S20 HQ', 'm-y-n-c-n-ng-rossi-s20-hq', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1950000.00, 1850000.00, 13, 0, 39, 81, '', 'WHROSSI20L', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f8baaec49_1753348282.jpg', 'Máy nước nóng Rossi S20 HQ', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 09:11:22'),
(151, 'Máy nước nóng Midea DSK45P5', 'm-y-n-c-n-ng-midea-dsk45p5', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1690000.00, 1590000.00, 11, 0, 39, 76, '', 'WHMIDEA45', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f8aa10d0f_1753348266.jpg', 'Máy nước nóng Midea DSK45P5', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 09:11:06'),
(152, 'Máy nước nóng Centon Water Heater WH601E', 'm-y-n-c-n-ng-centon-water-heater-wh601e', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1790000.00, 1690000.00, 10, 0, 39, 82, '', 'WHCENTON60', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f89a62b82_1753348250.jpg', 'Máy nước nóng Centon Water Heater WH601E', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 09:10:50'),
(153, 'Máy nước nóng Beko BWI45S1A', 'm-y-n-c-n-ng-beko-bwi45s1a', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1990000.00, 1890000.00, 10, 0, 39, 83, '', 'WHBEKO45', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f88a85980_1753348234.jpg', 'Máy nước nóng Beko BWI45S1A', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 09:10:34'),
(154, 'Nồi chiên không dầu Lock&Lock EJF148', 'n-i-chi-n-kh-ng-d-u-lock-lock-ejf148', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 2090000.00, 1990000.00, 15, 0, 40, 84, '', 'KITCHAIRFRY1', '0', 0.00, '', 0, 'active', 1, 'uploads/products/6881f8784a1a7_1753348216.jpg', 'Nồi chiên không dầu Lock&Lock EJF148', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 09:10:16'),
(155, 'Lò vi sóng Sharp R-G222VN-S 20L', 'l-vi-s-ng-sharp-r-g222vn-s-20l', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1390000.00, 1290000.00, 10, 0, 40, 77, '', 'KITCHMICRO1', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f85decf10_1753348189.jpg', 'Lò vi sóng Sharp R-G222VN-S 20L', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 09:09:49'),
(156, 'Bếp điện từ Sunhouse SHD6866', 'b-p-i-n-t-sunhouse-shd6866', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 849000.00, 799000.00, 20, 0, 40, 85, '', 'KITCHCOOK1', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f84c8487c_1753348172.jpg', 'Bếp điện từ Sunhouse SHD6866', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 09:09:32'),
(157, 'Máy xay sinh tố Philips HR2223/00', 'm-y-xay-sinh-t-philips-hr2223-00', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1290000.00, 1190000.00, 14, 0, 40, 86, '', 'KITCHBLEND1', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f83aa0c28_1753348154.jpg', 'Máy xay sinh tố Philips HR2223/00', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 09:09:14'),
(158, 'Nồi cơm điện Cuckoo CR-0675F 1.08L', 'n-i-c-m-i-n-cuckoo-cr-0675f-1-08l', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1090000.00, 990000.00, 16, 0, 40, 87, '', 'KITCHRICE1', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f8294a318_1753348137.jpg', 'Nồi cơm điện Cuckoo CR-0675F 1.08L', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 09:08:57'),
(159, 'Máy ép chậm Mishio MK-197', 'm-y-p-ch-m-mishio-mk-197', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1490000.00, 1390000.00, 12, 0, 40, 88, '', 'KITCHEXTRCT1', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f81a67794_1753348122.jpg', 'Máy ép chậm Mishio MK-197', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 09:08:42'),
(160, 'Máy pha cà phê Tiross TS621', 'm-y-pha-c-ph-tiross-ts621', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 990000.00, 940000.00, 11, 0, 40, 89, '', 'KITCHCOFFEE1', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f7bfef6b8_1753348031.jpg', 'Máy pha cà phê Tiross TS621', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 09:07:11'),
(161, 'Máy làm sữa hạt Ranbem 769S', 'm-y-l-m-s-a-h-t-ranbem-769s', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 1690000.00, 1590000.00, 10, 0, 40, 90, '', 'KITCHMILK1', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f7aebf290_1753348014.jpg', 'Máy làm sữa hạt Ranbem 769S', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 09:06:54'),
(162, 'Máy vắt cam Bluestone CJ-6338', 'm-y-v-t-cam-bluestone-cj-6338', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 459000.00, 429000.00, 18, 0, 40, 91, '', 'KITCHJUICE1', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f79d3c905_1753347997.jpg', 'Máy vắt cam Bluestone CJ-6338', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 09:06:37'),
(163, 'Bếp gas đôi Rinnai RV-970(G)', 'b-p-gas-i-rinnai-rv-970-g', 'Chưa có mô tả chi tiết.', 'Chưa có mô tả ngắn.', 849000.00, 799000.00, 9, 0, 40, 92, '', 'KITCHGAS1', '0', 0.00, '', 0, 'active', 0, 'uploads/products/6881f773319a6_1753347955.jpeg', 'Bếp gas đôi Rinnai RV-970(G)', 'Chưa có mô tả ngắn.', 1, '2025-07-24 08:51:16', '2025-07-24 09:05:55');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `image_url` varchar(500) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `alt_text`, `is_primary`, `sort_order`, `created_at`) VALUES
(1, 1, 'https://news.khangz.com/wp-content/uploads/2023/09/iphone-15-pro-titanium-tu-nhien.jpg', 'iPhone 15 Pro Max màu Titan Tự Nhiên', 1, 1, '2025-06-12 16:33:25'),
(42, 12, '/uploads/products/68808fefb2eae_1753255919.png', 'VGA GIGABYTE RTX 4060', 0, 999, '2025-07-23 07:31:59'),
(46, 11, 'uploads/products/688092dfab139_1753256671.webp', 'VGA MSI GeForce RTX 4070 Ti', 0, 999, '2025-07-23 07:44:31'),
(47, 13, 'uploads/products/688092fbebea6_1753256699.jpg', 'Mainboard ASUS ROG Z790 Hero', 0, 999, '2025-07-23 07:44:59'),
(48, 14, 'uploads/products/6880931a7d5b1_1753256730.jpg', 'Mainboard MSI B660M Mortar', 0, 999, '2025-07-23 07:45:30'),
(49, 15, 'uploads/products/6880933652b26_1753256758.webp', 'Mainboard GIGABYTE B550 AORUS', 0, 999, '2025-07-23 07:45:58'),
(50, 16, 'uploads/products/6880935025f7b_1753256784.jpg', 'Máy ảnh Canon EOS R6 Mark II', 0, 999, '2025-07-23 07:46:24'),
(51, 17, 'uploads/products/68809367789d7_1753256807.webp', 'Máy ảnh Sony Alpha A7 IV', 0, 999, '2025-07-23 07:46:47'),
(52, 18, 'uploads/products/6880937891885_1753256824.jpg', 'Máy ảnh Nikon Z6 II', 0, 999, '2025-07-23 07:47:04'),
(53, 19, 'uploads/products/6880939792140_1753256855.jpg', 'VGA Zotac RTX 3060 Twin Edge', 0, 999, '2025-07-23 07:47:35'),
(54, 10, 'uploads/products/688093a9db43b_1753256873.jpg', 'VGA ASUS ROG Strix RTX 4090', 0, 999, '2025-07-23 07:47:53'),
(55, 3, 'uploads/products/6880941f8005a_1753256991.jpg', 'Samsung Galaxy S24 Ultra', 0, 999, '2025-07-23 07:49:51'),
(56, 4, 'uploads/products/688094a75d69d_1753257127.jpeg', 'MacBook Pro M3', 0, 999, '2025-07-23 07:52:07'),
(57, 5, 'uploads/products/6880955538c39_1753257301.jpg', 'Dell XPS 13', 0, 999, '2025-07-23 07:55:01'),
(58, 6, 'uploads/products/6880959a93a73_1753257370.jpg', 'Canon EOS R6 Mark II', 0, 999, '2025-07-23 07:56:10'),
(59, 8, 'uploads/products/688096b35511e_1753257651.jpg', 'Corsair Vengeance LPX 32GB DDR4', 0, 999, '2025-07-23 08:00:51'),
(60, 9, 'uploads/products/6880972ac8533_1753257770.jpg', 'AMD Ryzen 7 7700X', 0, 999, '2025-07-23 08:02:50'),
(74, 21, 'uploads/products/6880ae158fdc0_1753263637.png', 'Xiaomi 14 Ultra', 0, 999, '2025-07-23 09:40:37'),
(75, 22, 'uploads/products/6880ae734d9d5_1753263731.jpg', 'Oppo Find X7 Pro', 0, 999, '2025-07-23 09:42:11'),
(76, 23, 'uploads/products/6880af05d8eb5_1753263877.jpeg', 'Vivo X100 Pro', 0, 999, '2025-07-23 09:44:37'),
(77, 24, 'uploads/products/6880af6a7ae51_1753263978.jpg', 'Realme GT 5 Pro', 0, 999, '2025-07-23 09:46:18'),
(78, 25, 'uploads/products/6880af969682c_1753264022.png', 'Asus ROG Phone 8', 0, 999, '2025-07-23 09:47:02'),
(79, 26, 'uploads/products/6880afc54dd54_1753264069.png', 'OnePlus 12', 0, 999, '2025-07-23 09:47:49'),
(80, 27, 'uploads/products/6880b00d1250d_1753264141.jpg', 'Google Pixel 8 Pro', 0, 999, '2025-07-23 09:49:01'),
(81, 28, 'uploads/products/6880b0485c54d_1753264200.png', 'Sony Xperia 1 V', 0, 999, '2025-07-23 09:50:00'),
(82, 29, 'uploads/products/6880b08bbb0c4_1753264267.jpg', 'Nokia X30 5G', 0, 999, '2025-07-23 09:51:07'),
(83, 30, 'uploads/products/6880b0bc97358_1753264316.jpg', 'Motorola Edge 40 Pro', 0, 999, '2025-07-23 09:51:56'),
(84, 31, 'uploads/products/6880b12bdf8dd_1753264427.jpg', 'Tecno Phantom X2 Pro', 0, 999, '2025-07-23 09:53:47'),
(85, 32, 'uploads/products/6880b17b7fd4f_1753264507.png', 'Vsmart Aris Pro', 0, 999, '2025-07-23 09:55:07'),
(86, 33, 'uploads/products/6880b1cde6bfd_1753264589.webp', 'Masstel N6', 0, 999, '2025-07-23 09:56:29'),
(102, 34, 'uploads/products/6881b5c324ea8_1753331139.jpg', 'Logitech MX Master 3S', 0, 999, '2025-07-24 04:25:39'),
(103, 35, 'uploads/products/6881b61e45ad4_1753331230.jpg', 'Razer DeathAdder V3 Pro', 0, 999, '2025-07-24 04:27:10'),
(104, 36, 'uploads/products/6881b6637d948_1753331299.jpg', 'Corsair Dark Core RGB Pro', 0, 999, '2025-07-24 04:28:19'),
(105, 37, 'uploads/products/6881b696a2b79_1753331350.png', 'Asus ROG Gladius III', 0, 999, '2025-07-24 04:29:10'),
(106, 38, 'uploads/products/6881b72f0c7f9_1753331503.jpg', 'SteelSeries Rival 600', 0, 999, '2025-07-24 04:31:43'),
(107, 39, 'uploads/products/6881b79bee66f_1753331611.jpg', 'Sony WH-1000XM5', 0, 999, '2025-07-24 04:33:31'),
(108, 40, 'uploads/products/6881b81b9e572_1753331739.jpg', 'Apple AirPods Pro 2', 0, 999, '2025-07-24 04:35:39'),
(109, 41, 'uploads/products/6881b855a6c49_1753331797.jpg', 'Bose QuietComfort Ultra', 0, 999, '2025-07-24 04:36:37'),
(110, 41, 'uploads/products/6881b86639673_1753331814.jpg', 'Bose QuietComfort Ultra 2', 0, 999, '2025-07-24 04:36:54'),
(111, 42, 'uploads/products/6881b8f5f2ca5_1753331957.jpg', 'JBL Tune 770NC', 0, 999, '2025-07-24 04:39:17'),
(112, 43, 'uploads/products/6881b95f208b9_1753332063.jpg', 'Anker Soundcore Life Q35', 0, 999, '2025-07-24 04:41:03'),
(113, 43, 'uploads/products/6881b96a256de_1753332074.jpg', 'Anker Soundcore Life Q35 2', 0, 999, '2025-07-24 04:41:14'),
(114, 44, 'uploads/products/6881b9c091407_1753332160.jpg', 'Keychron K6 Wireless', 0, 999, '2025-07-24 04:42:40'),
(115, 45, 'uploads/products/6881b9f241616_1753332210.jpg', 'Akko 3068B Plus', 0, 999, '2025-07-24 04:43:30'),
(116, 46, 'uploads/products/6881baf42eda1_1753332468.jpg', 'Logitech G Pro X', 0, 999, '2025-07-24 04:47:48'),
(117, 46, 'uploads/products/6881bb0c53b7f_1753332492.png', 'Logitech G Pro X 2', 0, 999, '2025-07-24 04:48:12'),
(118, 47, 'uploads/products/6881bb853d4b2_1753332613.jpg', 'Razer BlackWidow V4', 0, 999, '2025-07-24 04:50:13'),
(119, 48, 'uploads/products/6881bbdc5ce67_1753332700.jpg', 'Ducky One 3 Mini', 0, 999, '2025-07-24 04:51:40'),
(135, 54, 'uploads/products/6881e69960d62_1753343641.jpg', 'Micro USB HyperX QuadCast', 0, 999, '2025-07-24 07:54:01'),
(136, 53, 'uploads/products/6881e6be0909f_1753343678.jpg', 'GHẾ GAMING Cougar Armor One', 0, 999, '2025-07-24 07:54:38'),
(137, 52, 'uploads/products/6881e6e9b26ee_1753343721.jpg', 'GHẾ GAMING Anda Seat T-Pro 2', 0, 999, '2025-07-24 07:55:21'),
(138, 51, 'uploads/products/6881e70846e7e_1753343752.jpg', 'GHẾ GAMING Razer Iskur X', 0, 999, '2025-07-24 07:55:52'),
(139, 50, 'uploads/products/6881e72f09c98_1753343791.jpg', 'GHẾ GAMING MSI MAG CH130 X', 0, 999, '2025-07-24 07:56:31'),
(140, 49, 'uploads/products/6881e74a74c57_1753343818.jpg', 'GHẾ GAMING E-DRA Hercules EGC203', 0, 999, '2025-07-24 07:56:58'),
(141, 63, 'uploads/products/6881e75ce028d_1753343836.jpg', 'Webcam AVerMedia PW513 4K', 0, 999, '2025-07-24 07:57:16'),
(142, 62, 'uploads/products/6881e78015566_1753343872.jpg', 'Webcam Microsoft LifeCam HD-3000', 0, 999, '2025-07-24 07:57:52'),
(143, 61, 'uploads/products/6881e791baebb_1753343889.jpg', 'Webcam Elgato Facecam', 0, 999, '2025-07-24 07:58:09'),
(144, 60, 'uploads/products/6881e7a6d6234_1753343910.jpg', 'Webcam Razer Kiyo', 0, 999, '2025-07-24 07:58:30'),
(145, 59, 'uploads/products/6881e7be33b2c_1753343934.jpg', 'Webcam Logitech C920 HD Pro', 0, 999, '2025-07-24 07:58:54'),
(146, 58, 'uploads/products/6881e7d81fd7b_1753343960.jpg', 'Micro Fifine K690', 0, 999, '2025-07-24 07:59:20'),
(147, 57, 'uploads/products/6881e7ec44b7c_1753343980.jpg', 'Micro Blue Yeti Nano', 0, 999, '2025-07-24 07:59:40'),
(148, 56, 'uploads/products/6881e802713b6_1753344002.jpg', 'Micro Elgato Wave', 0, 999, '2025-07-24 08:00:02'),
(149, 55, 'uploads/products/6881e814053fb_1753344020.jpg', 'Micro Razer Seiren Mini', 0, 999, '2025-07-24 08:00:20'),
(150, 78, 'uploads/products/6881e823b0707_1753344035.png', 'Huawei MatePad 11.5', 0, 999, '2025-07-24 08:00:35'),
(151, 77, 'uploads/products/6881e834930b2_1753344052.jpg', 'Lenovo Tab M10 Gen 3', 0, 999, '2025-07-24 08:00:52'),
(152, 76, 'uploads/products/6881e84d2839b_1753344077.jpg', 'Xiaomi Pad 6', 0, 999, '2025-07-24 08:01:17'),
(153, 75, 'uploads/products/6881e85d77fac_1753344093.jpg', 'Samsung Galaxy Tab S9 FE 5G', 0, 999, '2025-07-24 08:01:33'),
(154, 74, 'uploads/products/6881e86e8b2a2_1753344110.jpg', 'iPad Air 5 (Wi-Fi, 64GB)', 0, 999, '2025-07-24 08:01:50'),
(155, 73, 'uploads/products/6881e880e3098_1753344128.jpg', 'Garmin Forerunner 255', 0, 999, '2025-07-24 08:02:08'),
(156, 72, 'uploads/products/6881e8928d804_1753344146.png', 'Huawei Watch GT 3 Pro', 0, 999, '2025-07-24 08:02:26'),
(157, 71, 'uploads/products/6881e8a22ccaa_1753344162.jpg', 'Xiaomi Watch S1 Active', 0, 999, '2025-07-24 08:02:42'),
(158, 70, 'uploads/products/6881e8b39b2ab_1753344179.png', 'Samsung Galaxy Watch6 Classic 47mm', 0, 999, '2025-07-24 08:02:59'),
(159, 69, 'uploads/products/6881e8c2d9af9_1753344194.jpg', 'Apple Watch Series 9 GPS 41mm', 0, 999, '2025-07-24 08:03:14'),
(160, 68, 'uploads/products/6881e8d98a6c1_1753344217.png', 'Lót chuột ASUS ROG Scabbard II', 0, 999, '2025-07-24 08:03:37'),
(161, 67, 'uploads/products/6881e8e69bd09_1753344230.jpg', 'Lót chuột DareU ESP111', 0, 999, '2025-07-24 08:03:50'),
(162, 66, 'uploads/products/6881e8fb7cf96_1753344251.jpg', 'Lót chuột SteelSeries QcK Heavy', 0, 999, '2025-07-24 08:04:11'),
(163, 65, 'uploads/products/6881e90b1f1cb_1753344267.jpg', 'Lót chuột Razer Goliathus Chroma', 0, 999, '2025-07-24 08:04:27'),
(164, 64, 'uploads/products/6881e91abdaa9_1753344282.png', 'Lót chuột Logitech G640', 0, 999, '2025-07-24 08:04:42'),
(165, 93, 'uploads/products/6881e92a5f616_1753344298.jpg', 'Case Vitra Gaming S301', 0, 999, '2025-07-24 08:04:58'),
(166, 92, 'uploads/products/6881e93fef40c_1753344319.jpg', 'Case Lian Li LANCOOL 216 RGB', 0, 999, '2025-07-24 08:05:19'),
(167, 91, 'uploads/products/6881e95797a8a_1753344343.png', 'Case Xigmatek Aquarius Plus Arctic', 0, 999, '2025-07-24 08:05:43'),
(168, 90, 'uploads/products/6881e96a184ae_1753344362.jpg', 'Case NZXT H510 Mid Tower', 0, 999, '2025-07-24 08:06:02'),
(169, 89, 'uploads/products/6881e9783abb2_1753344376.jpeg', 'Case Cooler Master MasterBox MB520 ARGB', 0, 999, '2025-07-24 08:06:16'),
(170, 88, 'uploads/products/6881e9863faf9_1753344390.jpg', 'HDD Toshiba 1TB 3.5', 0, 999, '2025-07-24 08:06:30'),
(171, 87, 'uploads/products/6881e997594f4_1753344407.jpg', 'SSD Kingston NV2 1TB M.2 NVMe', 0, 999, '2025-07-24 08:06:47'),
(172, 86, 'uploads/products/6881e9a6291a5_1753344422.jpg', 'HDD Seagate Barracuda 2TB 3.5', 0, 999, '2025-07-24 08:07:02'),
(173, 85, 'uploads/products/6881e9b48dcc6_1753344436.jpg', 'SSD WD Black SN850X 1TB NVMe', 0, 999, '2025-07-24 08:07:16'),
(174, 84, 'uploads/products/6881e9c52d904_1753344453.jpg', 'SSD Samsung 980 Pro 1TB M.2 NVMe', 0, 999, '2025-07-24 08:07:33'),
(175, 83, 'uploads/products/6881e9d3d19fb_1753344467.jpg', 'Màn hình ViewSonic VX3276-2K-MHD 32', 0, 999, '2025-07-24 08:07:47'),
(176, 82, 'uploads/products/6881e9e2c45a1_1753344482.jpg', 'Màn hình Samsung Odyssey G5 32 2K', 0, 999, '2025-07-24 08:08:02'),
(177, 81, 'uploads/products/6881e9f5867c2_1753344501.jpg', 'Màn hình Dell UltraSharp U2723QE 4K', 0, 999, '2025-07-24 08:08:21'),
(178, 80, 'uploads/products/6881ea02cbdeb_1753344514.jpg', 'Màn hình ASUS TUF Gaming VG249Q1A 165Hz', 0, 999, '2025-07-24 08:08:34'),
(179, 79, 'uploads/products/6881ea11b82d9_1753344529.png', 'Màn hình LG UltraGear 27GN750-B 240Hz', 0, 999, '2025-07-24 08:08:49'),
(180, 108, 'uploads/products/6881ea347007b_1753344564.png', 'CPU Intel Core i3-12100F', 0, 999, '2025-07-24 08:09:24'),
(181, 107, 'uploads/products/6881ea4b05d6e_1753344587.jpg', 'CPU AMD Ryzen 7 5800X3D', 0, 999, '2025-07-24 08:09:47'),
(182, 106, 'uploads/products/6881ea62730fc_1753344610.jpg', 'CPU Intel Core i7-13700K', 0, 999, '2025-07-24 08:10:10'),
(183, 105, 'uploads/products/6881ea779712f_1753344631.jpg', 'CPU AMD Ryzen 5 5600X', 0, 999, '2025-07-24 08:10:31'),
(184, 104, 'uploads/products/6881ea8c43858_1753344652.jpg', 'CPU Intel Core i5-12400F', 0, 999, '2025-07-24 08:10:52'),
(185, 103, 'uploads/products/6881ea9b8f0b4_1753344667.jpg', 'Tản nhiệt nước AIO MSI MAG CORELIQUID C240', 0, 999, '2025-07-24 08:11:07'),
(186, 102, 'uploads/products/6881eaa92a21b_1753344681.jpg', 'Tản nhiệt khí Noctua NH-D15 Chromax Black', 0, 999, '2025-07-24 08:11:21'),
(187, 101, 'uploads/products/6881eab68abd5_1753344694.jpg', 'Tản nhiệt nước AIO ASUS ROG Strix LC II 240 ARGB', 0, 999, '2025-07-24 08:11:34'),
(188, 100, 'uploads/products/6881eac6ec55f_1753344710.jpg', 'Tản nhiệt khí DeepCool GAMMAXX 400 V2', 0, 999, '2025-07-24 08:11:50'),
(189, 99, 'uploads/products/6881ead2ba6f5_1753344722.jpg', 'Tản nhiệt khí Cooler Master Hyper 212 Black Edition', 0, 999, '2025-07-24 08:12:02'),
(190, 98, 'uploads/products/6881eaddc7591_1753344733.jpg', 'RAM ADATA XPG GAMMIX D30 8GB DDR4 3200MHz', 0, 999, '2025-07-24 08:12:13'),
(191, 97, 'uploads/products/6881eae944685_1753344745.jpg', 'RAM TeamGroup T-Force Delta RGB 16GB DDR4 3600MHz', 0, 999, '2025-07-24 08:12:25'),
(192, 96, 'uploads/products/6881eaf4900e7_1753344756.jpg', 'RAM Kingston Fury Beast 16GB (2x8GB) DDR4 3200MHz', 0, 999, '2025-07-24 08:12:36'),
(193, 95, 'uploads/products/6881eaff3a89f_1753344767.jpg', 'RAM G.SKILL Ripjaws V 16GB (2x8GB) DDR4 3600MHz', 0, 999, '2025-07-24 08:12:47'),
(194, 94, 'uploads/products/6881eb0a7301e_1753344778.jpg', 'RAM Corsair Vengeance LPX 16GB (2x8GB) DDR4 3200MHz', 0, 999, '2025-07-24 08:12:58'),
(195, 123, 'uploads/products/6881eb17a69ee_1753344791.jpg', 'VGA Sapphire Pulse Radeon RX 6700 XT 12GB', 0, 999, '2025-07-24 08:13:11'),
(196, 122, 'uploads/products/6881eb25528a0_1753344805.jpg', 'VGA ZOTAC GeForce RTX 4060 Twin Edge 8GB', 0, 999, '2025-07-24 08:13:25'),
(197, 121, 'uploads/products/6881eb32247e3_1753344818.jpg', 'VGA MSI GeForce GTX 1660 Super Ventus XS OC', 0, 999, '2025-07-24 08:13:38'),
(198, 120, 'uploads/products/6881eb3f67192_1753344831.jpg', 'VGA GIGABYTE Radeon RX 6600 Eagle 8G', 0, 999, '2025-07-24 08:13:51'),
(199, 119, 'uploads/products/6881eb4d41224_1753344845.png', 'VGA ASUS Dual GeForce RTX 3060 12GB', 0, 999, '2025-07-24 08:14:05'),
(200, 118, 'uploads/products/6881eb56ca982_1753344854.png', 'PSU FSP Power Supply Hydro PRO 600W', 0, 999, '2025-07-24 08:14:14'),
(201, 117, 'uploads/products/6881eb5feef21_1753344863.jpg', 'PSU Thermaltake Smart 700W 80 Plus', 0, 999, '2025-07-24 08:14:23'),
(202, 116, 'uploads/products/6881eb6c94924_1753344876.jpg', 'PSU Antec Atom B650 650W', 0, 999, '2025-07-24 08:14:36'),
(203, 115, 'uploads/products/6881eb7c723b2_1753344892.jpg', 'PSU Corsair CV650 650W 80 Plus Bronze', 0, 999, '2025-07-24 08:14:52'),
(204, 114, 'uploads/products/6881ebbdb5c68_1753344957.jpg', 'PSU Cooler Master MWE 550W V2 80 Plus Bronze', 0, 999, '2025-07-24 08:15:57'),
(205, 113, 'uploads/products/6881ebd8e2b72_1753344984.png', 'Mainboard ASUS TUF GAMING B660M-PLUS WIFI D4', 0, 999, '2025-07-24 08:16:24'),
(206, 112, 'uploads/products/6881ebe4c305b_1753344996.jpg', 'Mainboard ASRock B450M Steel Legend', 0, 999, '2025-07-24 08:16:36'),
(207, 111, 'uploads/products/6881ebf0b1cd1_1753345008.png', 'Mainboard GIGABYTE B760M DS3H DDR4', 0, 999, '2025-07-24 08:16:48'),
(208, 110, 'uploads/products/6881ebfb077df_1753345019.jpg', 'Mainboard MSI B550M PRO-VDH WIFI', 0, 999, '2025-07-24 08:16:59'),
(209, 109, 'uploads/products/6881ec06f12e8_1753345030.jpg', 'Mainboard ASUS PRIME B660M-A D4', 0, 999, '2025-07-24 08:17:10'),
(210, 138, 'uploads/products/6881f07626532_1753346166.jpg', 'Máy lạnh Casper Inverter 1HP GC-09IS32', 0, 999, '2025-07-24 08:36:06'),
(211, 137, 'uploads/products/6881f0a1c3aa3_1753346209.jpg', 'Máy lạnh Midea Inverter 1HP MS11D1-10CRDN1', 0, 999, '2025-07-24 08:36:49'),
(212, 136, 'uploads/products/6881f0cde60a0_1753346253.jpg', 'Máy lạnh LG Inverter 1.5HP V13API1', 0, 999, '2025-07-24 08:37:33'),
(213, 135, 'uploads/products/6881f0fb2f4dd_1753346299.jpg', 'Máy lạnh Panasonic Inverter 1.5HP', 0, 999, '2025-07-24 08:38:19'),
(214, 134, 'uploads/products/6881f12c95411_1753346348.jpg', 'Máy lạnh Daikin Inverter 1HP FTKY25WAVMV', 0, 999, '2025-07-24 08:39:08'),
(215, 133, 'uploads/products/6881f1d344b23_1753346515.jpg', 'Máy giặt Aqua 8Kg AQW-FR805AT', 0, 999, '2025-07-24 08:41:55'),
(216, 132, 'uploads/products/6881f1f3b2e40_1753346547.jpg', 'Máy giặt Toshiba Inverter 9.5Kg TW-BK105G4V', 0, 999, '2025-07-24 08:42:27'),
(217, 131, 'uploads/products/6881f20e69a7b_1753346574.jpg', 'Máy giặt Electrolux Inverter 10Kg EWF1024P5WB', 0, 999, '2025-07-24 08:42:54'),
(218, 130, 'uploads/products/6881f23ee1789_1753346622.png', 'Máy giặt Samsung Inverter 8.5Kg WW85T4040CX', 0, 999, '2025-07-24 08:43:42'),
(219, 129, 'uploads/products/6881f25d355eb_1753346653.jpg', 'Máy giặt LG Inverter 9Kg FV1409S2V', 0, 999, '2025-07-24 08:44:13'),
(220, 128, 'uploads/products/6881f28324c82_1753346691.jpg', 'Smart Tivi TCL 50 inch 4K UHD 50P635', 0, 999, '2025-07-24 08:44:51'),
(221, 127, 'uploads/products/6881f2b591933_1753346741.jpg', 'Tivi Casper 43 inch 4K UHD 43UG6100', 0, 999, '2025-07-24 08:45:41'),
(222, 126, 'uploads/products/6881f2d861e4e_1753346776.jpg', 'Tivi Sony Bravia 4K 50 inch KD-50X75K', 0, 999, '2025-07-24 08:46:16'),
(223, 125, 'uploads/products/6881f31268569_1753346834.jpg', 'Smart Tivi LG 43 inch 4K UHD 43UQ7550PSF', 0, 999, '2025-07-24 08:47:14'),
(224, 124, 'uploads/products/6881f332ebee2_1753346866.jpg', 'Smart Tivi Samsung 4K 55 inch UA55AU7002', 0, 999, '2025-07-24 08:47:46'),
(225, 163, 'uploads/products/6881f773319a6_1753347955.jpeg', 'Bếp gas đôi Rinnai RV-970(G)', 0, 999, '2025-07-24 09:05:55'),
(226, 162, 'uploads/products/6881f79d3c905_1753347997.jpg', 'Máy vắt cam Bluestone CJ-6338', 0, 999, '2025-07-24 09:06:37'),
(227, 161, 'uploads/products/6881f7aebf290_1753348014.jpg', 'Máy làm sữa hạt Ranbem 769S', 0, 999, '2025-07-24 09:06:54'),
(228, 160, 'uploads/products/6881f7bfef6b8_1753348031.jpg', 'Máy pha cà phê Tiross TS621', 0, 999, '2025-07-24 09:07:11'),
(229, 159, 'uploads/products/6881f81a67794_1753348122.jpg', 'Máy ép chậm Mishio MK-197', 0, 999, '2025-07-24 09:08:42'),
(230, 158, 'uploads/products/6881f8294a318_1753348137.jpg', 'Nồi cơm điện Cuckoo CR-0675F 1.08L', 0, 999, '2025-07-24 09:08:57'),
(231, 157, 'uploads/products/6881f83aa0c28_1753348154.jpg', 'Máy xay sinh tố Philips HR2223', 0, 999, '2025-07-24 09:09:14'),
(232, 156, 'uploads/products/6881f84c8487c_1753348172.jpg', 'Bếp điện từ Sunhouse SHD6866', 0, 999, '2025-07-24 09:09:32'),
(233, 155, 'uploads/products/6881f85decf10_1753348189.jpg', 'Lò vi sóng Sharp R-G222VN-S 20L', 0, 999, '2025-07-24 09:09:49'),
(234, 154, 'uploads/products/6881f8784a1a7_1753348216.jpg', 'Nồi chiên không dầu Lock&Lock EJF148', 0, 999, '2025-07-24 09:10:16'),
(235, 153, 'uploads/products/6881f88a85980_1753348234.jpg', 'Máy nước nóng Beko BWI45S1A', 0, 999, '2025-07-24 09:10:34'),
(236, 152, 'uploads/products/6881f89a62b82_1753348250.jpg', 'Máy nước nóng Centon Water Heater WH601E', 0, 999, '2025-07-24 09:10:50'),
(237, 151, 'uploads/products/6881f8aa10d0f_1753348266.jpg', 'Máy nước nóng Midea DSK45P5', 0, 999, '2025-07-24 09:11:06'),
(238, 150, 'uploads/products/6881f8baaec49_1753348282.jpg', 'Máy nước nóng Rossi S20 HQ', 0, 999, '2025-07-24 09:11:22'),
(239, 149, 'uploads/products/6881f8ce30281_1753348302.jpg', 'Máy nước nóng Panasonic DH-4RP2VK', 0, 999, '2025-07-24 09:11:42'),
(240, 148, 'uploads/products/6881f8e1ccf16_1753348321.jpg', 'Máy nước nóng Ariston Slim2 20L', 0, 999, '2025-07-24 09:12:01'),
(241, 147, 'uploads/products/6881f8ee72d25_1753348334.jpg', 'Máy nước nóng Ferroli 20L VERDI-SE 20L', 0, 999, '2025-07-24 09:12:14'),
(242, 146, 'uploads/products/6881f8febb550_1753348350.jpg', 'Tủ lạnh Mitsubishi Electric 365 lít MR-FX47EN-GSL-V', 0, 999, '2025-07-24 09:12:30'),
(243, 145, 'uploads/products/6881f910a4d53_1753348368.jpg', 'Tủ lạnh Electrolux Inverter 308 lít EBB3442K-A', 0, 999, '2025-07-24 09:12:48'),
(244, 144, 'uploads/products/6881f926d5548_1753348390.jpg', 'Tủ lạnh Sharp Inverter 196 lít SJ-X201E-SL', 0, 999, '2025-07-24 09:13:10'),
(245, 143, 'uploads/products/6881f93636694_1753348406.jpg', 'Tủ lạnh Toshiba Inverter 555 lít GR-RF610WE-PGV(37)-XK', 0, 999, '2025-07-24 09:13:26'),
(246, 142, 'uploads/products/6881f945c6744_1753348421.jpg', 'Tủ lạnh LG Inverter 393 lít GN-D392BL', 0, 999, '2025-07-24 09:13:41'),
(247, 141, 'uploads/products/6881f952e57ab_1753348434.jpg', 'Tủ lạnh Aqua Inverter 260 lít AQR-I287BN PS', 0, 999, '2025-07-24 09:13:54'),
(248, 140, 'uploads/products/6881f97374687_1753348467.jpg', 'Tủ lạnh Panasonic Inverter 255 lít NR-BV289QSV2', 0, 999, '2025-07-24 09:14:27'),
(249, 139, 'uploads/products/6881f99daa1c9_1753348509.jpg', 'Tủ lạnh Samsung Inverter 208 lít RT20HAR8DBU', 0, 999, '2025-07-24 09:15:09');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_specifications`
--

CREATE TABLE `product_specifications` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `spec_name` varchar(100) NOT NULL,
  `spec_value` text NOT NULL,
  `spec_group` varchar(50) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `product_specifications`
--

INSERT INTO `product_specifications` (`id`, `product_id`, `spec_name`, `spec_value`, `spec_group`, `sort_order`) VALUES
(1, 1, 'Màn hình', '6.7\" Super Retina XDR OLED', 'Display', 1),
(2, 1, 'Chip', 'A17 Pro', 'Performance', 2),
(3, 1, 'Camera chính', '48MP + 12MP + 12MP', 'Camera', 3),
(4, 1, 'Dung lượng', '256GB', 'Storage', 4),
(5, 1, 'RAM', '8GB', 'Memory', 5),
(6, 1, 'Pin', 'Lên đến 29 giờ xem video', 'Battery', 6),
(7, 4, 'Màn hình', '14.2\" Liquid Retina XDR', 'Display', 1),
(8, 4, 'Chip', 'Apple M3', 'Performance', 2),
(9, 4, 'RAM', '16GB Unified Memory', 'Memory', 3),
(10, 4, 'Ổ cứng', '512GB SSD', 'Storage', 4),
(11, 4, 'Cổng kết nối', '3x Thunderbolt 4, HDMI, SDXC', 'Connectivity', 5),
(12, 4, 'Pin', 'Lên đến 22 giờ', 'Battery', 6),
(13, 6, 'Cảm biến', '24.2MP Full-Frame CMOS', 'Sensor', 1),
(14, 6, 'Bộ xử lý', 'DIGIC X', 'Processor', 2),
(15, 6, 'ISO', '100-102400 (có thể mở rộng)', 'ISO Range', 3),
(16, 6, 'Video', '4K UHD 60p', 'Video', 4),
(17, 6, 'Màn hình', '3.0\" Vari-angle Touch Screen', 'Display', 5),
(18, 6, 'Thẻ nhớ', 'Dual SD Card Slots', 'Storage', 6);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `admin_reply` text DEFAULT NULL,
  `pros` text DEFAULT NULL,
  `cons` text DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `is_approved` tinyint(1) DEFAULT 1,
  `helpful_count` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `order_id`, `rating`, `title`, `comment`, `admin_reply`, `pros`, `cons`, `is_verified`, `is_approved`, `helpful_count`, `created_at`, `updated_at`) VALUES
(1, 1, 2, NULL, 5, 'Sản phẩm rất tốt!', 'Tôi rất hài lòng với sản phẩm này. Chất lượng tốt, giá cả hợp lý.', NULL, 'Chất lượng tốt, giá hợp lý', 'Chưa có', 1, 1, 0, '2025-07-16 08:24:08', '2025-07-16 08:24:08'),
(2, 1, 3, NULL, 4, 'Khá hài lòng', 'Sản phẩm đáp ứng được nhu cầu của tôi. Giao hàng nhanh.', NULL, 'Giao hàng nhanh, chất lượng tốt', 'Giá hơi cao', 0, 1, 0, '2025-07-16 08:24:08', '2025-07-16 08:24:08'),
(5, 3, 3, NULL, 2, 'Không hài lòng', 'Sản phẩm không như mô tả. Chất lượng kém.', NULL, 'Không có', 'Chất lượng kém, giá cao', 0, 0, 0, '2025-07-16 08:24:08', '2025-07-16 08:24:08');

-- --------------------------------------------------------

--
-- Table structure for table `review_images`
--

CREATE TABLE `review_images` (
  `id` int(11) NOT NULL,
  `review_id` int(11) DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'super_admin', 'Quyền cao nhất, quản lý toàn bộ hệ thống', '2025-06-12 16:33:25'),
(2, 'admin', 'Quản trị viên, quản lý sản phẩm và đơn hàng', '2025-06-12 16:33:25'),
(3, 'staff', 'Nhân viên, xử lý đơn hàng', '2025-06-12 16:33:25'),
(4, 'customer', 'Khách hàng thông thường', '2025-06-12 16:33:25');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(2, 2),
(2, 3),
(2, 4),
(2, 5),
(2, 6),
(3, 3),
(3, 7),
(4, 7),
(4, 8),
(4, 9),
(4, 10);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(500) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `email_verified` tinyint(1) DEFAULT 0,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password_hash`, `first_name`, `last_name`, `phone`, `avatar`, `date_of_birth`, `is_active`, `email_verified`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin@shopelectrics.com', 'admin', '$2y$10$2jlEoxVPyiluGcb4xu1H3ucDL38qHvI2pAeendAO5/FR8GrOfIjYu', 'Admin', 'System', '0834670200', 'uploads/avatars/avatar_6880c50eabcdd.jpg', NULL, 1, 1, NULL, '2025-06-12 16:33:25', '2025-07-23 11:18:38'),
(2, 'user1@example.com', 'user1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nguy???n V??n', 'A', '0987654321', NULL, NULL, 1, 1, NULL, '2025-06-20 05:49:49', '2025-06-20 05:49:49'),
(3, 'user2@example.com', 'user2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Tr???n Th???', 'B', '0987654322', NULL, NULL, 1, 1, NULL, '2025-06-20 05:49:49', '2025-06-20 05:49:49'),
(4, 'user3@example.com', 'user3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'L?? V??n', 'C', '0987654323', NULL, NULL, 1, 1, NULL, '2025-06-20 05:49:49', '2025-06-20 05:49:49'),
(6, 'lamdat@gmail.com', 'meomeomeo', '$2y$10$HciM4L583d4UHWNDlbSDG.478QQ8v9XZ4OlbzbghBLN6K7Z5pFSh6', 'Lam', 'Dat', '0123456789', NULL, NULL, 1, 0, NULL, '2025-07-16 09:26:36', '2025-07-16 09:26:36'),
(7, 'doanthinh@gmail.com', 'ThinhDoan', '$2y$10$oBDkja.5qboOoBxpm.AcgOtP/FixLKLM5oJgAtW3pd/BxP1pISh.G', 'Đoàn', 'Thịnh', '0123456789', NULL, NULL, 1, 0, NULL, '2025-07-16 12:21:59', '2025-07-16 12:21:59'),
(8, 'datb2303736@student.ctu.edu.vn', 'changbeoquemientay', '$2y$10$XGgFKk3tt5RLm12K9oksYuzT5fU7J9.IROiGm/GoT/tbdSVSDKv1.', 'Lâm', 'Đạt', '0834670200', NULL, NULL, 1, 0, NULL, '2025-07-23 08:09:46', '2025-07-23 08:09:46');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `assigned_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`user_id`, `role_id`, `assigned_at`) VALUES
(1, 1, '2025-06-12 16:33:25'),
(2, 2, '2025-06-20 05:49:49'),
(3, 2, '2025-06-20 05:49:49'),
(4, 2, '2025-06-20 05:49:49'),
(6, 4, '2025-07-16 09:26:36'),
(7, 4, '2025-07-16 12:21:59'),
(8, 4, '2025-07-23 08:09:46');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `added_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_session_id` (`session_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart_product` (`cart_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_categories_parent` (`parent_id`),
  ADD KEY `idx_categories_active` (`is_active`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_orders_user` (`user_id`),
  ADD KEY `idx_orders_status` (`status`),
  ADD KEY `idx_orders_payment_status` (`payment_status`),
  ADD KEY `idx_orders_date` (`order_date`);

--
-- Indexes for table `order_coupons`
--
ALTER TABLE `order_coupons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `coupon_id` (`coupon_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_products_category` (`category_id`),
  ADD KEY `idx_products_brand` (`brand_id`),
  ADD KEY `idx_products_status` (`status`),
  ADD KEY `idx_products_featured` (`featured`),
  ADD KEY `idx_products_price` (`price`),
  ADD KEY `idx_products_created_at` (`created_at`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `product_specifications`
--
ALTER TABLE `product_specifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `idx_reviews_product` (`product_id`),
  ADD KEY `idx_reviews_user` (`user_id`),
  ADD KEY `idx_reviews_rating` (`rating`);

--
-- Indexes for table `review_images`
--
ALTER TABLE `review_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `review_id` (`review_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username_unique` (`username`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `order_coupons`
--
ALTER TABLE `order_coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_specifications`
--
ALTER TABLE `product_specifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `review_images`
--
ALTER TABLE `review_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_coupons`
--
ALTER TABLE `order_coupons`
  ADD CONSTRAINT `order_coupons_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_coupons_ibfk_2` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `product_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `product_specifications`
--
ALTER TABLE `product_specifications`
  ADD CONSTRAINT `product_specifications_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `review_images`
--
ALTER TABLE `review_images`
  ADD CONSTRAINT `review_images_ibfk_1` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
