-- Thanhdat
-- Thêm trường admin_reply vào bảng reviews
ALTER TABLE `reviews` ADD COLUMN `admin_reply` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NULL AFTER `comment`;

-- Thêm một số đánh giá mẫu để test
INSERT INTO `reviews` (`product_id`, `user_id`, `rating`, `title`, `comment`, `pros`, `cons`, `is_verified`, `is_approved`, `created_at`) VALUES
(1, 2, 5, 'Sản phẩm rất tốt!', 'Tôi rất hài lòng với sản phẩm này. Chất lượng tốt, giá cả hợp lý.', 'Chất lượng tốt, giá hợp lý', 'Chưa có', 1, 1, NOW()),
(1, 3, 4, 'Khá hài lòng', 'Sản phẩm đáp ứng được nhu cầu của tôi. Giao hàng nhanh.', 'Giao hàng nhanh, chất lượng tốt', 'Giá hơi cao', 0, 1, NOW()),
(2, 4, 3, 'Tạm được', 'Sản phẩm ổn nhưng có thể cải thiện thêm.', 'Thiết kế đẹp', 'Chất lượng chưa tốt lắm', 0, 0, NOW()),
(2, 2, 5, 'Tuyệt vời!', 'Sản phẩm vượt quá mong đợi. Rất đáng mua!', 'Chất lượng cao, thiết kế đẹp', 'Không có', 1, 1, NOW()),
(3, 3, 2, 'Không hài lòng', 'Sản phẩm không như mô tả. Chất lượng kém.', 'Không có', 'Chất lượng kém, giá cao', 0, 0, NOW()); 