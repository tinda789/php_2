<?php
class ContactRequest {
    private $conn;
    public function __construct($conn) { $this->conn = $conn; }
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO contact_requests (name, email, phone, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $data['name'], $data['email'], $data['phone'], $data['message']);
        return $stmt->execute();
    }
    public function getAll() {
        $result = $this->conn->query("SELECT * FROM contact_requests ORDER BY created_at DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
} 