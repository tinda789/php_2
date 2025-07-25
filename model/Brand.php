<?php
class Brand {
    public static function getAll($conn, $search = '') {
        if ($search !== '') {
            $sql = 'SELECT * FROM brands WHERE name LIKE ?';
            $stmt = $conn->prepare($sql);
            $like = '%' . $search . '%';
            $stmt->bind_param('s', $like);
        } else {
            $sql = 'SELECT * FROM brands';
            $stmt = $conn->prepare($sql);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $brands = [];
        while ($row = $result->fetch_assoc()) {
            $brands[] = $row;
        }
        return $brands;
    }

    public static function getById($conn, $id) {
        $sql = 'SELECT * FROM brands WHERE id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function create($conn, $data) {
    $sql = "INSERT INTO brands (name, description, logo, website, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'ssssis',
        $data['name'],
        $data['description'],
        $data['logo'],
        $data['website'],
        $data['is_active'],
        $data['created_at']
    );
    return $stmt->execute();
}
    public static function update($conn, $id, $data) {
    $sql = 'UPDATE brands SET name = ?, description = ?, logo = ?, website = ?, is_active = ? WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'ssssii',
        $data['name'],
        $data['description'],
        $data['logo'],
        $data['website'],
        $data['is_active'],
        $id
    );
    return $stmt->execute();
}
    public static function delete($conn, $id) {
        $sql = 'DELETE FROM brands WHERE id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    
}
?>