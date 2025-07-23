<?php
require_once 'config/config.php';

echo "<h2>Đang cập nhật database...</h2>";

// SQL để tạo bảng banners
$banner_sql = "
CREATE TABLE IF NOT EXISTS `banners` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `image_url` VARCHAR(500) NOT NULL,
    `link` VARCHAR(500),
    `description` TEXT,
    `position` ENUM('homepage_top','homepage_middle','homepage_bottom','sidebar','popup') DEFAULT 'homepage_top',
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

// SQL để tạo bảng news
$news_sql = "
CREATE TABLE IF NOT EXISTS `news` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) UNIQUE,
    `content` TEXT NOT NULL,
    `image_url` VARCHAR(500),
    `category` VARCHAR(100),
    `is_active` TINYINT(1) DEFAULT 1,
    `published_at` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

// SQL để tạo bảng product_images
$product_images_sql = "
CREATE TABLE IF NOT EXISTS `product_images` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT NOT NULL,
    `image` VARCHAR(255) NOT NULL,
    `sort_order` INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

// SQL để thêm trường admin_reply vào bảng reviews
$reviews_sql = "
ALTER TABLE `reviews` ADD COLUMN IF NOT EXISTS `admin_reply` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NULL AFTER `comment`;
";

// Thực thi các câu lệnh SQL
$sql_commands = [
    'Tạo bảng banners' => $banner_sql,
    'Tạo bảng news' => $news_sql,
    'Tạo bảng product_images' => $product_images_sql,
    'Cập nhật bảng reviews' => $reviews_sql
];

foreach ($sql_commands as $description => $sql) {
    try {
        if ($conn->query($sql)) {
            echo "<p style='color: green;'>✅ $description: Thành công</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ $description: " . $conn->error . "</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ $description: " . $e->getMessage() . "</p>";
    }
}

// Thêm dữ liệu mẫu cho banners
$sample_banner_sql = "
INSERT IGNORE INTO `banners` (`title`, `image_url`, `link`, `description`, `position`, `is_active`, `sort_order`) VALUES
('Banner chính', '/uploads/banners/banner1.jpg', '#', 'Banner chính trang chủ', 'homepage_top', 1, 1),
('Khuyến mãi', '/uploads/banners/banner2.jpg', '#', 'Banner khuyến mãi', 'homepage_middle', 1, 2);
";

try {
    if ($conn->query($sample_banner_sql)) {
        echo "<p style='color: green;'>✅ Thêm dữ liệu mẫu banners: Thành công</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: orange;'>⚠️ Thêm dữ liệu mẫu banners: " . $e->getMessage() . "</p>";
}

echo "<h3>🎉 Cập nhật database hoàn tất!</h3>";
echo "<p><a href='index.php'>← Quay về trang chủ</a></p>";
?> 