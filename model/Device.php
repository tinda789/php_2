<?php
require_once 'config/config.php';

class Device {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // Lấy danh sách tất cả thiết bị
    public function getAll() {
        $sql = "SELECT d.*, c.name as category_name, 
                       GROUP_CONCAT(DISTINCT w.name) as warehouse_names
                FROM devices d 
                LEFT JOIN categories c ON d.category_id = c.id 
                LEFT JOIN inventory i ON d.id = i.device_id 
                LEFT JOIN warehouses w ON i.warehouse_id = w.id 
                WHERE d.status = 'active' 
                GROUP BY d.id 
                ORDER BY d.name";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Lấy thông tin thiết bị theo ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT d.*, c.name as category_name 
                                     FROM devices d 
                                     LEFT JOIN categories c ON d.category_id = c.id 
                                     WHERE d.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Tìm thiết bị theo serial number
    public function findBySerial($serial_number) {
        $stmt = $this->conn->prepare("SELECT * FROM devices WHERE serial_number = ?");
        $stmt->bind_param("s", $serial_number);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Tìm thiết bị theo QR code
    public function findByQRCode($qr_code) {
        $stmt = $this->conn->prepare("SELECT * FROM devices WHERE qr_code = ?");
        $stmt->bind_param("s", $qr_code);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Thêm thiết bị mới
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO devices (name, category_id, model, serial_number, 
                                     manufacturer, specifications, purchase_date, warranty_expiry, 
                                     qr_code, barcode, image_url, documents_url) 
                                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissssssssss", 
            $data['name'], $data['category_id'], $data['model'], $data['serial_number'],
            $data['manufacturer'], $data['specifications'], $data['purchase_date'], 
            $data['warranty_expiry'], $data['qr_code'], $data['barcode'], 
            $data['image_url'], $data['documents_url']
        );
        return $stmt->execute();
    }
    
    // Cập nhật thông tin thiết bị
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE devices 
                                     SET name = ?, category_id = ?, model = ?, serial_number = ?,
                                         manufacturer = ?, specifications = ?, purchase_date = ?,
                                         warranty_expiry = ?, qr_code = ?, barcode = ?,
                                         image_url = ?, documents_url = ?, status = ?
                                     WHERE id = ?");
        $stmt->bind_param("sissssssssssi", 
            $data['name'], $data['category_id'], $data['model'], $data['serial_number'],
            $data['manufacturer'], $data['specifications'], $data['purchase_date'], 
            $data['warranty_expiry'], $data['qr_code'], $data['barcode'], 
            $data['image_url'], $data['documents_url'], $data['status'], $id
        );
        return $stmt->execute();
    }
    
    // Xóa thiết bị (soft delete)
    public function delete($id) {
        $stmt = $this->conn->prepare("UPDATE devices SET status = 'inactive' WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    // Lấy tồn kho của thiết bị theo kho
    public function getInventoryByWarehouse($device_id) {
        $stmt = $this->conn->prepare("SELECT i.*, w.name as warehouse_name 
                                     FROM inventory i 
                                     JOIN warehouses w ON i.warehouse_id = w.id 
                                     WHERE i.device_id = ? AND w.status = 'active'");
        $stmt->bind_param("i", $device_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Tạo QR code cho thiết bị
    public function generateQRCode($device_id) {
        $qr_code = 'DEVICE_' . str_pad($device_id, 6, '0', STR_PAD_LEFT) . '_' . time();
        $stmt = $this->conn->prepare("UPDATE devices SET qr_code = ? WHERE id = ?");
        $stmt->bind_param("si", $qr_code, $device_id);
        return $stmt->execute() ? $qr_code : false;
    }
    
    // Tìm kiếm thiết bị
    public function search($keyword, $category_id = null, $status = null) {
        $sql = "SELECT d.*, c.name as category_name 
                FROM devices d 
                LEFT JOIN categories c ON d.category_id = c.id 
                WHERE d.status = 'active'";
        
        $params = [];
        $types = "";
        
        if ($keyword) {
            $sql .= " AND (d.name LIKE ? OR d.model LIKE ? OR d.serial_number LIKE ?)";
            $keyword = "%$keyword%";
            $params[] = $keyword;
            $params[] = $keyword;
            $params[] = $keyword;
            $types .= "sss";
        }
        
        if ($category_id) {
            $sql .= " AND d.category_id = ?";
            $params[] = $category_id;
            $types .= "i";
        }
        
        if ($status) {
            $sql .= " AND d.status = ?";
            $params[] = $status;
            $types .= "s";
        }
        
        $sql .= " ORDER BY d.name";
        
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?> 