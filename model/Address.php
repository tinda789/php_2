<?php
class Address {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function save($userId, $data) {
        $stmt = $this->conn->prepare("INSERT INTO addresses 
            (user_id, first_name, last_name, phone, address_line1, address_line2, city, district, ward, is_default) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
        $isDefault = isset($data['is_default']) ? 1 : 0;
        $addressLine2 = $data['address_line2'] ?? '';
        
        $stmt->bind_param(
            "issssssssi",
            $userId,
            $data['first_name'],
            $data['last_name'],
            $data['phone'],
            $data['address_line1'],
            $addressLine2,
            $data['city'],
            $data['district'],
            $data['ward'],
            $isDefault
        );
        
        if ($isDefault) {
            // Reset any other default addresses
            $this->conn->query("UPDATE addresses SET is_default = 0 WHERE user_id = $userId");
        }
        
        return $stmt->execute();
    }
    
    public function update($userId, $addressId, $data) {
        $stmt = $this->conn->prepare("UPDATE addresses SET 
            first_name = ?,
            last_name = ?,
            phone = ?,
            address_line1 = ?,
            address_line2 = ?,
            city = ?,
            district = ?,
            ward = ?,
            is_default = ?
            WHERE id = ? AND user_id = ?");
            
        $isDefault = isset($data['is_default']) ? 1 : 0;
        $addressLine2 = $data['address_line2'] ?? '';
        
        $stmt->bind_param(
            "ssssssssiii",
            $data['first_name'],
            $data['last_name'],
            $data['phone'],
            $data['address_line1'],
            $addressLine2,
            $data['city'],
            $data['district'],
            $data['ward'],
            $isDefault,
            $addressId,
            $userId
        );
        
        if ($isDefault) {
            // Reset any other default addresses
            $this->conn->query("UPDATE addresses SET is_default = 0 WHERE user_id = $userId AND id != $addressId");
        }
        
        return $stmt->execute();
    }
    
    public function getUserAddresses($userId) {
        $result = $this->conn->query("SELECT * FROM addresses WHERE user_id = $userId ORDER BY is_default DESC, id DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getAddress($id, $userId) {
        $stmt = $this->conn->prepare("SELECT * FROM addresses WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function setDefaultAddress($id, $userId) {
        $this->conn->begin_transaction();
        
        try {
            // Reset all defaults
            $this->conn->query("UPDATE addresses SET is_default = 0 WHERE user_id = $userId");
            
            // Set new default
            $stmt = $this->conn->prepare("UPDATE addresses SET is_default = 1 WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $id, $userId);
            $stmt->execute();
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
    
    public function deleteAddress($id, $userId) {
        $stmt = $this->conn->prepare("DELETE FROM addresses WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $userId);
        return $stmt->execute();
    }
}
