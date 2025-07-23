-- Tạo bảng warehouses
CREATE TABLE IF NOT EXISTS warehouses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(200),
    description TEXT,
    manager_id INT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tạo bảng devices
CREATE TABLE IF NOT EXISTS devices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    category_id INT,
    model VARCHAR(100),
    serial_number VARCHAR(100) UNIQUE,
    manufacturer VARCHAR(100),
    specifications TEXT,
    purchase_date DATE,
    warranty_expiry DATE,
    status ENUM('active', 'inactive', 'damaged', 'maintenance') DEFAULT 'active',
    qr_code VARCHAR(255),
    barcode VARCHAR(100),
    image_url VARCHAR(255),
    documents_url TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tạo bảng inventory
CREATE TABLE IF NOT EXISTS inventory (
    id INT PRIMARY KEY AUTO_INCREMENT,
    device_id INT NOT NULL,
    warehouse_id INT NOT NULL,
    quantity INT DEFAULT 0,
    min_quantity INT DEFAULT 0,
    max_quantity INT,
    unit_price DECIMAL(10,2),
    location_in_warehouse VARCHAR(100),
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_device_warehouse (device_id, warehouse_id)
);

-- Thêm dữ liệu mẫu
INSERT INTO warehouses (name, location, description) VALUES
('Kho chính', 'Tầng 1, Tòa A', 'Kho chính chứa thiết bị điện tử'),
('Kho phụ', 'Tầng 2, Tòa B', 'Kho phụ cho thiết bị dự phòng'),
('Kho linh kiện', 'Tầng 1, Tòa C', 'Kho chứa linh kiện và phụ tùng'); 