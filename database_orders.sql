-- Tạo bảng orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `payment_method` enum('cod','bank','vnpay') NOT NULL DEFAULT 'cod',
  `note` text,
  `status` enum('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tạo bảng order_items
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm dữ liệu mẫu cho orders (tùy chọn)
INSERT INTO `orders` (`user_id`, `total_amount`, `shipping_address`, `phone`, `payment_method`, `note`, `status`) VALUES
(1, 2500000.00, '123 Đường ABC, Quận 1, TP.HCM', '0901234567', 'cod', 'Giao hàng giờ hành chính', 'pending'),
(1, 1800000.00, '456 Đường XYZ, Quận 2, TP.HCM', '0909876543', 'bank', 'Giao hàng buổi chiều', 'processing');

-- Thêm dữ liệu mẫu cho order_items (tùy chọn)
INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 2, 1250000.00),
(2, 2, 1, 1800000.00); 