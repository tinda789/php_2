-- SQL mẫu tạo bảng kho và dữ liệu mẫu tiếng Việt chuẩn (utf8mb4)

CREATE TABLE IF NOT EXISTS warehouses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    location VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    description TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    manager_id INT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO warehouses (name, location, description) VALUES
('Kho chính', 'Tầng 1, Tòa A', 'Kho chính chứa thiết bị điện tử'),
('Kho phụ', 'Tầng 2, Tòa B', 'Kho phụ cho thiết bị dự phòng'),
('Kho linh kiện', 'Tầng 1, Tòa C', 'Kho chứa linh kiện và phụ tùng'); 