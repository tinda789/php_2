<?php

class AddCouponTables {
    public function up($conn) {
        // Create coupons table
        $sql = "CREATE TABLE IF NOT EXISTS `coupons` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `code` varchar(50) NOT NULL,
            `type` enum('fixed','percent') NOT NULL DEFAULT 'fixed',
            `value` decimal(15,2) NOT NULL,
            `minimum_amount` decimal(15,2) DEFAULT 0.00,
            `maximum_discount` decimal(15,2) DEFAULT NULL,
            `usage_limit` int(11) DEFAULT NULL,
            `used_count` int(11) DEFAULT 0,
            `start_date` datetime DEFAULT NULL,
            `end_date` datetime DEFAULT NULL,
            `is_active` tinyint(1) DEFAULT 1,
            `created_at` timestamp NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`),
            UNIQUE KEY `code` (`code`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;";
        $conn->query($sql);

        // Add coupon_id and coupon_code to orders table
        $sql = "ALTER TABLE `orders` 
                ADD COLUMN `coupon_id` INT NULL AFTER `payment_method`,
                ADD COLUMN `coupon_code` VARCHAR(50) NULL AFTER `coupon_id`,
                ADD COLUMN `coupon_discount` DECIMAL(15,2) DEFAULT 0.00 AFTER `discount_amount`,
                ADD INDEX `idx_orders_coupon` (`coupon_id`),
                ADD CONSTRAINT `fk_orders_coupon` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL";
        $conn->query($sql);

        // Create order_coupons table for tracking coupon usage
        $sql = "CREATE TABLE IF NOT EXISTS `order_coupons` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `order_id` int(11) NOT NULL,
            `coupon_id` int(11) NOT NULL,
            `coupon_code` varchar(50) NOT NULL,
            `discount_amount` decimal(15,2) NOT NULL,
            `created_at` timestamp NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`),
            KEY `idx_order_coupons_order` (`order_id`),
            KEY `idx_order_coupons_coupon` (`coupon_id`),
            CONSTRAINT `fk_order_coupons_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
            CONSTRAINT `fk_order_coupons_coupon` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci";
        $conn->query($sql);

        // Add a sample coupon
        $this->addSampleCoupon($conn);
    }

    private function addSampleCoupon($conn) {
        $sql = "INSERT INTO `coupons` 
                (`code`, `type`, `value`, `minimum_amount`, `maximum_discount`, `usage_limit`, `start_date`, `end_date`, `is_active`)
                VALUES 
                ('WELCOME10', 'percent', 10.00, 500000, 500000, 100, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 1),
                ('FREESHIP', 'fixed', 50000.00, 1000000, NULL, 200, NOW(), DATE_ADD(NOW(), INTERVAL 60 DAY), 1),
                ('SALE20', 'percent', 20.00, 1500000, 1000000, 50, NOW(), DATE_ADD(NOW(), INTERVAL 15 DAY), 1)";
        try {
            $conn->query($sql);
        } catch (Exception $e) {
            // Ignore if sample coupons already exist
        }
    }

    public function down($conn) {
        // Drop foreign key constraints first
        $conn->query("ALTER TABLE `orders` DROP FOREIGN KEY `fk_orders_coupon`");
        
        // Drop columns from orders table
        $conn->query("ALTER TABLE `orders` DROP COLUMN `coupon_id`");
        $conn->query("ALTER TABLE `orders` DROP COLUMN `coupon_code`");
        $conn->query("ALTER TABLE `orders` DROP COLUMN `coupon_discount`");
        
        // Drop order_coupons table
        $conn->query("DROP TABLE IF EXISTS `order_coupons`");
        
        // Drop coupons table
        $conn->query("DROP TABLE IF EXISTS `coupons`");
    }
}
                                                                                        