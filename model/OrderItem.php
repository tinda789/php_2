<?php
require_once __DIR__ . '/../config/config.php';

class OrderItem {
    private $db;
    
    public function __construct($conn = null) {
        global $conn;
        $this->db = $conn;
    }
    
    public function create($data) {
        $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiid", 
            $data['order_id'],
            $data['product_id'],
            $data['quantity'],
            $data['price']
        );
        
        return $stmt->execute();
    }
    
    public function getByOrderId($orderId) {
        $sql = "SELECT oi.*, p.name as product_name, p.images 
                FROM order_items oi 
                LEFT JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM order_items WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function update($id, $data) {
        $sql = "UPDATE order_items SET quantity = ?, price = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("idi", $data['quantity'], $data['price'], $id);
        return $stmt->execute();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM order_items WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    // Thống kê số lượng bán của từng sản phẩm trong tháng
    public function getProductSalesStatsInMonth($month = null, $year = null) {
        if (!$month) $month = date('m');
        if (!$year) $year = date('Y');
        $sql = "SELECT oi.product_id, p.name, SUM(oi.quantity) as total_sold
                FROM order_items oi
                JOIN orders o ON oi.order_id = o.id
                JOIN products p ON oi.product_id = p.id
                WHERE MONTH(o.created_at) = ? AND YEAR(o.created_at) = ?
                GROUP BY oi.product_id
                ORDER BY total_sold DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $month, $year);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }
} 