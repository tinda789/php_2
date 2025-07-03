-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: db:3306
-- Thời gian đã tạo: Th6 20, 2025 lúc 05:52 AM
-- Phiên bản máy phục vụ: 8.0.42
-- Phiên bản PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `shopelectrics`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `addresses`
--

CREATE TABLE `addresses` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `type` enum('shipping','billing','both') CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT 'both',
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `company` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `address_line1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `address_line2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `district` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `ward` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `postal_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `country` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT 'Vietnam',
  `is_default` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `brands`
--

CREATE TABLE `brands` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci,
  `logo` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `brands`
--

INSERT INTO `brands` (`id`, `name`, `description`, `logo`, `website`, `is_active`, `created_at`) VALUES
(1, 'Apple', 'Công ty công nghệ hàng đầu thế giới', NULL, 'https://www.apple.com', 1, '2025-06-12 16:33:25'),
(2, 'Samsung', 'Tập đoàn điện tử đa quốc gia Hàn Quốc', NULL, 'https://www.samsung.com', 1, '2025-06-12 16:33:25'),
(3, 'Canon', 'Chuyên gia về máy ảnh và máy in', NULL, 'https://www.canon.com', 1, '2025-06-12 16:33:25'),
(4, 'Corsair', 'Thương hiệu gaming và PC cao cấp', NULL, 'https://www.corsair.com', 1, '2025-06-12 16:33:25'),
(5, 'ASUS', 'Thương hiệu máy tính và linh kiện', NULL, 'https://www.asus.com', 1, '2025-06-12 16:33:25'),
(6, 'Dell', 'Công ty máy tính và công nghệ', NULL, 'https://www.dell.com', 1, '2025-06-12 16:33:25'),
(7, 'HP', 'Hewlett-Packard Enterprise', NULL, 'https://www.hp.com', 1, '2025-06-12 16:33:25'),
(8, 'Sony', 'Tập đoàn giải trí và điện tử Nhật Bản', NULL, 'https://www.sony.com', 1, '2025-06-12 16:33:25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int NOT NULL,
  `cart_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(10,2) NOT NULL,
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci,
  `parent_id` int DEFAULT NULL,
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `image` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
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
(18, 'Mainboard', 'Bo mạch chủ', 4, 'mainboard', NULL, 1, 4, '2025-06-12 16:33:25', '2025-06-12 16:33:25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coupons`
--

CREATE TABLE `coupons` (
  `id` int NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci,
  `type` enum('fixed','percentage') CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `minimum_amount` decimal(10,2) DEFAULT '0.00',
  `maximum_discount` decimal(10,2) DEFAULT NULL,
  `usage_limit` int DEFAULT NULL,
  `used_count` int DEFAULT '0',
  `start_date` timestamp NOT NULL,
  `end_date` timestamp NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `name`, `description`, `type`, `value`, `minimum_amount`, `maximum_discount`, `usage_limit`, `used_count`, `start_date`, `end_date`, `is_active`, `created_at`) VALUES
(1, 'WELCOME10', 'Chào mừng khách hàng mới', 'Giảm 10% cho đơn hàng đầu tiên', 'percentage', 10.00, 1000000.00, NULL, 100, 0, '2025-06-12 16:33:25', '2025-07-12 16:33:25', 1, '2025-06-12 16:33:25'),
(2, 'SUMMER2024', 'Khuyến mãi hè 2024', 'Giảm 500.000đ cho đơn hàng từ 10 triệu', 'fixed', 500000.00, 10000000.00, NULL, 50, 0, '2025-06-12 16:33:25', '2025-08-11 16:33:25', 1, '2025-06-12 16:33:25'),
(3, 'TECH20', 'Giảm giá công nghệ', 'Giảm 20% tối đa 2 triệu', 'percentage', 20.00, 5000000.00, NULL, 30, 0, '2025-06-12 16:33:25', '2025-07-27 16:33:25', 1, '2025-06-12 16:33:25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `type` enum('info','success','warning','error') CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `order_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed','refunded','partial_refund') CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT 'pending',
  `payment_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `tax_amount` decimal(12,2) DEFAULT '0.00',
  `shipping_fee` decimal(12,2) DEFAULT '0.00',
  `discount_amount` decimal(12,2) DEFAULT '0.00',
  `total_amount` decimal(12,2) NOT NULL,
  `shipping_address` json DEFAULT NULL,
  `billing_address` json DEFAULT NULL,
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `shipped_date` timestamp NULL DEFAULT NULL,
  `delivered_date` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_coupons`
--

CREATE TABLE `order_coupons` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `coupon_id` int DEFAULT NULL,
  `coupon_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `product_sku` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `permissions`
--

CREATE TABLE `permissions` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci,
  `resource` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `permissions`
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
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci,
  `short_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci,
  `price` decimal(12,2) NOT NULL,
  `sale_price` decimal(12,2) DEFAULT NULL,
  `stock_quantity` int DEFAULT '0',
  `min_stock_level` int DEFAULT '5',
  `category_id` int DEFAULT NULL,
  `brand_id` int DEFAULT NULL,
  `model` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `sku` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `barcode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `weight` decimal(8,2) DEFAULT NULL,
  `dimensions` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `warranty_period` int DEFAULT NULL,
  `status` enum('active','inactive','out_of_stock','discontinued') CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT 'active',
  `featured` tinyint(1) DEFAULT '0',
  `meta_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci,
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `description`, `short_description`, `price`, `sale_price`, `stock_quantity`, `min_stock_level`, `category_id`, `brand_id`, `model`, `sku`, `barcode`, `weight`, `dimensions`, `warranty_period`, `status`, `featured`, `meta_title`, `meta_description`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'iPhone 15 Pro Max 256GB', 'iphone-15-pro-max-256gb', 'iPhone 15 Pro Max với chip A17 Pro mạnh mẽ, camera chuyên nghiệp và thiết kế titanium cao cấp.', 'iPhone cao cấp nhất với màn hình 6.7 inch và camera 48MP', 32990000.00, 31990000.00, 50, 5, 6, 1, 'iPhone 15 Pro Max', 'IP15PM256GB', NULL, NULL, NULL, NULL, 'active', 1, NULL, NULL, 1, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(2, 'iPhone 15 128GB', 'iphone-15-128gb', 'iPhone 15 với Dynamic Island, camera 48MP và chip A16 Bionic mạnh mẽ.', 'iPhone thế hệ mới với nhiều cải tiến', 24990000.00, NULL, 75, 5, 6, 1, 'iPhone 15', 'IP15128GB', NULL, NULL, NULL, NULL, 'active', 1, NULL, NULL, 1, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(3, 'Samsung Galaxy S24 Ultra', 'samsung-galaxy-s24-ultra', 'Flagship Android với S Pen tích hợp, camera zoom 100x và hiệu năng đỉnh cao.', 'Smartphone Android cao cấp nhất của Samsung', 29990000.00, 28990000.00, 30, 5, 7, 2, 'Galaxy S24 Ultra', 'SGS24U256GB', NULL, NULL, NULL, NULL, 'active', 1, NULL, NULL, 1, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(4, 'MacBook Pro M3 14 inch', 'macbook-pro-m3-14-inch', 'MacBook Pro với chip M3 mạnh mẽ, màn hình Liquid Retina XDR và thời lượng pin lên đến 22 giờ.', 'Laptop chuyên nghiệp cho sáng tạo', 52990000.00, NULL, 20, 5, 9, 1, 'MacBook Pro 14\"', 'MBP14M3512GB', NULL, NULL, NULL, NULL, 'active', 1, NULL, NULL, 1, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(5, 'Dell XPS 13', 'dell-xps-13', 'Laptop siêu mỏng với màn hình InfinityEdge và hiệu năng Intel Core thế hệ 13.', 'Laptop Windows cao cấp, thiết kế đẹp', 28990000.00, 27990000.00, 25, 5, 9, 6, 'XPS 13', 'DELLXPS13I7', NULL, NULL, NULL, NULL, 'active', 0, NULL, NULL, 1, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(6, 'Canon EOS R6 Mark II', 'canon-eos-r6-mark-ii', 'Máy ảnh mirrorless chuyên nghiệp với cảm biến full-frame 24.2MP và khả năng quay video 4K.', 'Máy ảnh mirrorless chuyên nghiệp', 45990000.00, NULL, 15, 5, 12, 3, 'EOS R6 Mark II', 'CANR6M2BODY', NULL, NULL, NULL, NULL, 'active', 1, NULL, NULL, 1, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(7, 'Sony A7 IV', 'sony-a7-iv', 'Máy ảnh mirrorless full-frame với cảm biến 33MP và khả năng quay video 4K 60p.', 'Máy ảnh Sony thế hệ mới', 53990000.00, 51990000.00, 12, 5, 12, 8, 'Alpha A7 IV', 'SONYA7IV', NULL, NULL, NULL, NULL, 'active', 1, NULL, NULL, 1, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(8, 'Corsair Vengeance LPX 32GB DDR4', 'corsair-vengeance-lpx-32gb-ddr4', 'Bộ nhớ DDR4 32GB (2x16GB) tốc độ 3200MHz với tản nhiệt nhôm cao cấp.', 'RAM DDR4 hiệu năng cao cho gaming', 3290000.00, NULL, 100, 5, 15, 4, 'Vengeance LPX', 'CORS32GB3200', NULL, NULL, NULL, NULL, 'active', 0, NULL, NULL, 1, '2025-06-12 16:33:25', '2025-06-12 16:33:25'),
(9, 'AMD Ryzen 7 7700X', 'amd-ryzen-7-7700x', 'Bộ vi xử lý 8 nhân 16 luồng với kiến trúc Zen 4 và tốc độ boost lên đến 5.4GHz.', 'CPU AMD thế hệ mới cho gaming và làm việc', 8990000.00, 8490000.00, 40, 5, 14, 1, 'Ryzen 7 7700X', 'AMDR77700X', NULL, NULL, NULL, NULL, 'active', 1, NULL, NULL, 1, '2025-06-12 16:33:25', '2025-06-12 16:33:25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_images`
--

CREATE TABLE `product_images` (
  `id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `image_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `alt_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT '0',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `alt_text`, `is_primary`, `sort_order`, `created_at`) VALUES
(1, 1, '/images/iphone-15-pro-max-1.jpg', 'iPhone 15 Pro Max màu Titan Tự Nhiên', 1, 1, '2025-06-12 16:33:25'),
(2, 1, '/images/iphone-15-pro-max-2.jpg', 'iPhone 15 Pro Max mặt sau', 0, 2, '2025-06-12 16:33:25'),
(3, 2, '/images/iphone-15-1.jpg', 'iPhone 15 màu hồng', 1, 1, '2025-06-12 16:33:25'),
(4, 3, '/images/galaxy-s24-ultra-1.jpg', 'Samsung Galaxy S24 Ultra với S Pen', 1, 1, '2025-06-12 16:33:25'),
(5, 4, '/images/macbook-pro-m3-1.jpg', 'MacBook Pro M3 14 inch', 1, 1, '2025-06-12 16:33:25'),
(6, 5, '/images/dell-xps-13-1.jpg', 'Dell XPS 13 màn hình InfinityEdge', 1, 1, '2025-06-12 16:33:25'),
(7, 6, '/images/canon-r6-mark-ii-1.jpg', 'Canon EOS R6 Mark II body', 1, 1, '2025-06-12 16:33:25'),
(8, 7, '/images/sony-a7-iv-1.jpg', 'Sony Alpha A7 IV', 1, 1, '2025-06-12 16:33:25'),
(9, 8, '/images/corsair-ram-1.jpg', 'Corsair Vengeance LPX 32GB', 1, 1, '2025-06-12 16:33:25'),
(10, 9, '/images/amd-ryzen-7-1.jpg', 'AMD Ryzen 7 7700X', 1, 1, '2025-06-12 16:33:25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_specifications`
--

CREATE TABLE `product_specifications` (
  `id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `spec_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `spec_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `spec_group` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `sort_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `product_specifications`
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
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `order_id` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci,
  `pros` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci,
  `cons` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci,
  `is_verified` tinyint(1) DEFAULT '0',
  `is_approved` tinyint(1) DEFAULT '1',
  `helpful_count` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `review_images`
--

CREATE TABLE `review_images` (
  `id` int NOT NULL,
  `review_id` int DEFAULT NULL,
  `image_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `alt_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'super_admin', 'Quyền cao nhất, quản lý toàn bộ hệ thống', '2025-06-12 16:33:25'),
(2, 'admin', 'Quản trị viên, quản lý sản phẩm và đơn hàng', '2025-06-12 16:33:25'),
(3, 'staff', 'Nhân viên, xử lý đơn hàng', '2025-06-12 16:33:25'),
(4, 'customer', 'Khách hàng thông thường', '2025-06-12 16:33:25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(2, 2),
(1, 3),
(2, 3),
(3, 3),
(1, 4),
(2, 4),
(1, 5),
(2, 5),
(1, 6),
(2, 6),
(3, 7),
(4, 7),
(4, 8),
(4, 9),
(4, 10);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `avatar` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `email_verified` tinyint(1) DEFAULT '0',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password_hash`, `first_name`, `last_name`, `phone`, `avatar`, `date_of_birth`, `is_active`, `email_verified`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin@shopelectrics.com', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'System', NULL, NULL, NULL, 1, 1, NULL, '2025-06-12 16:33:25', '2025-06-20 05:47:06'),
(2, 'user1@example.com', 'user1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nguy???n V??n', 'A', '0987654321', NULL, NULL, 1, 1, NULL, '2025-06-20 05:49:49', '2025-06-20 05:49:49'),
(3, 'user2@example.com', 'user2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Tr???n Th???', 'B', '0987654322', NULL, NULL, 1, 1, NULL, '2025-06-20 05:49:49', '2025-06-20 05:49:49'),
(4, 'user3@example.com', 'user3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'L?? V??n', 'C', '0987654323', NULL, NULL, 1, 1, NULL, '2025-06-20 05:49:49', '2025-06-20 05:49:49');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` int NOT NULL,
  `role_id` int NOT NULL,
  `assigned_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `user_roles`
--

INSERT INTO `user_roles` (`user_id`, `role_id`, `assigned_at`) VALUES
(1, 1, '2025-06-12 16:33:25'),
(2, 2, '2025-06-20 05:49:49'),
(3, 2, '2025-06-20 05:49:49'),
(4, 2, '2025-06-20 05:49:49');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `wishlists`
--

CREATE TABLE `wishlists` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_session_id` (`session_id`);

--
-- Chỉ mục cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart_product` (`cart_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_categories_parent` (`parent_id`),
  ADD KEY `idx_categories_active` (`is_active`);

--
-- Chỉ mục cho bảng `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_orders_user` (`user_id`),
  ADD KEY `idx_orders_status` (`status`),
  ADD KEY `idx_orders_payment_status` (`payment_status`),
  ADD KEY `idx_orders_date` (`order_date`);

--
-- Chỉ mục cho bảng `order_coupons`
--
ALTER TABLE `order_coupons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `coupon_id` (`coupon_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `products`
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
-- Chỉ mục cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `product_specifications`
--
ALTER TABLE `product_specifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `idx_reviews_product` (`product_id`),
  ADD KEY `idx_reviews_user` (`user_id`),
  ADD KEY `idx_reviews_rating` (`rating`);

--
-- Chỉ mục cho bảng `review_images`
--
ALTER TABLE `review_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `review_id` (`review_id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username_unique` (`username`);

--
-- Chỉ mục cho bảng `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Chỉ mục cho bảng `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `order_coupons`
--
ALTER TABLE `order_coupons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `product_specifications`
--
ALTER TABLE `product_specifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `review_images`
--
ALTER TABLE `review_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Ràng buộc đối với các bảng kết xuất
--

--
-- Ràng buộc cho bảng `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`);

--
-- Ràng buộc cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ràng buộc cho bảng `order_coupons`
--
ALTER TABLE `order_coupons`
  ADD CONSTRAINT `order_coupons_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_coupons_ibfk_2` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`);

--
-- Ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Ràng buộc cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `product_specifications`
--
ALTER TABLE `product_specifications`
  ADD CONSTRAINT `product_specifications_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Ràng buộc cho bảng `review_images`
--
ALTER TABLE `review_images`
  ADD CONSTRAINT `review_images_ibfk_1` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
