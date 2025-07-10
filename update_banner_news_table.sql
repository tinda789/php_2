-- Tạo bảng banners
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

-- Tạo bảng news (posts)
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

-- thanhdat: Tạo bảng lưu nhiều ảnh cho mỗi sản phẩm
CREATE TABLE IF NOT EXISTS product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
); 