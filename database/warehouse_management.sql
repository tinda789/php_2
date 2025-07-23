-- Hệ thống quản lý kho và xuất nhập thiết bị điện tử

-- Bảng kho
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

-- Bảng danh mục thiết bị
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    parent_id INT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Bảng thiết bị
CREATE TABLE devices (
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Bảng tồn kho
CREATE TABLE inventory (
    id INT PRIMARY KEY AUTO_INCREMENT,
    device_id INT NOT NULL,
    warehouse_id INT NOT NULL,
    quantity INT DEFAULT 0,
    min_quantity INT DEFAULT 0, -- Ngưỡng cảnh báo tồn kho thấp
    max_quantity INT,
    unit_price DECIMAL(10,2),
    location_in_warehouse VARCHAR(100), -- Vị trí trong kho
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (device_id) REFERENCES devices(id) ON DELETE CASCADE,
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_device_warehouse (device_id, warehouse_id)
);

-- Bảng phiếu nhập kho
CREATE TABLE import_orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    warehouse_id INT NOT NULL,
    supplier_name VARCHAR(200),
    supplier_contact VARCHAR(200),
    total_amount DECIMAL(12,2),
    notes TEXT,
    status ENUM('pending', 'approved', 'completed', 'cancelled') DEFAULT 'pending',
    created_by INT,
    approved_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id)
);

-- Bảng chi tiết phiếu nhập kho
CREATE TABLE import_order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    import_order_id INT NOT NULL,
    device_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2),
    total_price DECIMAL(10,2),
    notes TEXT,
    FOREIGN KEY (import_order_id) REFERENCES import_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (device_id) REFERENCES devices(id)
);

-- Bảng phiếu xuất kho
CREATE TABLE export_orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    warehouse_id INT NOT NULL,
    department_id INT,
    employee_id INT,
    purpose VARCHAR(200),
    total_amount DECIMAL(12,2),
    notes TEXT,
    status ENUM('pending', 'approved', 'completed', 'cancelled') DEFAULT 'pending',
    created_by INT,
    approved_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id),
    FOREIGN KEY (department_id) REFERENCES departments(id),
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id)
);

-- Bảng chi tiết phiếu xuất kho
CREATE TABLE export_order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    export_order_id INT NOT NULL,
    device_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2),
    total_price DECIMAL(10,2),
    notes TEXT,
    FOREIGN KEY (export_order_id) REFERENCES export_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (device_id) REFERENCES devices(id)
);

-- Bảng chuyển kho
CREATE TABLE transfer_orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    from_warehouse_id INT NOT NULL,
    to_warehouse_id INT NOT NULL,
    reason VARCHAR(200),
    notes TEXT,
    status ENUM('pending', 'approved', 'completed', 'cancelled') DEFAULT 'pending',
    created_by INT,
    approved_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (from_warehouse_id) REFERENCES warehouses(id),
    FOREIGN KEY (to_warehouse_id) REFERENCES warehouses(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id)
);

-- Bảng chi tiết chuyển kho
CREATE TABLE transfer_order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    transfer_order_id INT NOT NULL,
    device_id INT NOT NULL,
    quantity INT NOT NULL,
    notes TEXT,
    FOREIGN KEY (transfer_order_id) REFERENCES transfer_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (device_id) REFERENCES devices(id)
);

-- Bảng kiểm kê kho
CREATE TABLE inventory_checks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    check_number VARCHAR(50) UNIQUE NOT NULL,
    warehouse_id INT NOT NULL,
    check_date DATE NOT NULL,
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    notes TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Bảng chi tiết kiểm kê
CREATE TABLE inventory_check_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    inventory_check_id INT NOT NULL,
    device_id INT NOT NULL,
    expected_quantity INT NOT NULL,
    actual_quantity INT,
    difference INT,
    notes TEXT,
    checked_by INT,
    checked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (inventory_check_id) REFERENCES inventory_checks(id) ON DELETE CASCADE,
    FOREIGN KEY (device_id) REFERENCES devices(id),
    FOREIGN KEY (checked_by) REFERENCES users(id)
);

-- Bảng lịch sử hoạt động kho
CREATE TABLE warehouse_activities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    warehouse_id INT,
    activity_type ENUM('import', 'export', 'transfer', 'check', 'adjustment') NOT NULL,
    reference_id INT, -- ID của phiếu tương ứng
    reference_type VARCHAR(50), -- Loại tham chiếu (import_orders, export_orders, etc.)
    device_id INT,
    quantity_change INT, -- Số lượng thay đổi (+ cho nhập, - cho xuất)
    notes TEXT,
    performed_by INT,
    performed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id),
    FOREIGN KEY (device_id) REFERENCES devices(id),
    FOREIGN KEY (performed_by) REFERENCES users(id)
);

-- Bảng cảnh báo tồn kho
CREATE TABLE inventory_alerts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    device_id INT NOT NULL,
    warehouse_id INT NOT NULL,
    alert_type ENUM('low_stock', 'overstock', 'expiry_warning') NOT NULL,
    current_quantity INT,
    threshold_quantity INT,
    message TEXT,
    status ENUM('active', 'acknowledged', 'resolved') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    acknowledged_at TIMESTAMP NULL,
    acknowledged_by INT,
    FOREIGN KEY (device_id) REFERENCES devices(id),
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id),
    FOREIGN KEY (acknowledged_by) REFERENCES users(id)
);

-- Bảng phòng ban (nếu chưa có)
CREATE TABLE departments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    manager_id INT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng nhân viên (nếu chưa có)
CREATE TABLE employees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_code VARCHAR(50) UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    department_id INT,
    position VARCHAR(100),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(id)
);

-- Bảng người dùng (nếu chưa có)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE,
    full_name VARCHAR(100),
    role ENUM('admin', 'manager', 'staff', 'viewer') DEFAULT 'staff',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Thêm dữ liệu mẫu
INSERT INTO warehouses (name, location, description) VALUES
('Kho chính', 'Tầng 1, Tòa A', 'Kho chính chứa thiết bị điện tử'),
('Kho phụ', 'Tầng 2, Tòa B', 'Kho phụ cho thiết bị dự phòng'),
('Kho linh kiện', 'Tầng 1, Tòa C', 'Kho chứa linh kiện và phụ tùng');

INSERT INTO categories (name, description) VALUES
('Máy tính', 'Máy tính để bàn và laptop'),
('Máy in', 'Máy in và máy scan'),
('Thiết bị mạng', 'Router, switch, modem'),
('Thiết bị di động', 'Điện thoại, tablet'),
('Linh kiện', 'RAM, ổ cứng, màn hình');

INSERT INTO departments (name, description) VALUES
('IT', 'Phòng Công nghệ thông tin'),
('HR', 'Phòng Nhân sự'),
('Finance', 'Phòng Tài chính'),
('Marketing', 'Phòng Marketing');

INSERT INTO users (username, password, email, full_name, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@company.com', 'Administrator', 'admin'),
('manager1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager1@company.com', 'Warehouse Manager', 'manager'),
('staff1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff1@company.com', 'Warehouse Staff', 'staff'); 