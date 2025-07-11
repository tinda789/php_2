<?php
// Test xử lý upload ảnh
require_once 'config/config.php';

echo "<h2>Kết quả Test Upload</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['product_images']) && !empty($_FILES['product_images']['name'][0])) {
        $upload_dir = 'uploads/products/';
        
        // Tạo thư mục nếu chưa tồn tại
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $uploaded_count = 0;
        $error_count = 0;
        
        foreach ($_FILES['product_images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['product_images']['error'][$key] === UPLOAD_ERR_OK) {
                $file_name = $_FILES['product_images']['name'][$key];
                $file_size = $_FILES['product_images']['size'][$key];
                $file_type = $_FILES['product_images']['type'][$key];
                
                echo "<h4>Xử lý file: $file_name</h4>";
                echo "<ul>";
                echo "<li>Kích thước: " . number_format($file_size / 1024, 2) . " KB</li>";
                echo "<li>Loại file: $file_type</li>";
                
                // Kiểm tra loại file
                $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($file_type, $allowed_types)) {
                    echo "<li style='color: red;'>✗ Loại file không được hỗ trợ</li>";
                    $error_count++;
                    echo "</ul>";
                    continue;
                }
                
                // Kiểm tra kích thước file (tối đa 5MB)
                if ($file_size > 5 * 1024 * 1024) {
                    echo "<li style='color: red;'>✗ File quá lớn (tối đa 5MB)</li>";
                    $error_count++;
                    echo "</ul>";
                    continue;
                }
                
                // Tạo tên file mới để tránh trùng lặp
                $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
                $new_file_name = uniqid() . '_' . time() . '.' . $file_extension;
                $file_path = $upload_dir . $new_file_name;
                
                // Upload file
                if (move_uploaded_file($tmp_name, $file_path)) {
                    echo "<li style='color: green;'>✓ Upload thành công</li>";
                    echo "<li>File mới: $new_file_name</li>";
                    echo "<li>Đường dẫn: $file_path</li>";
                    $uploaded_count++;
                    
                    // Test lưu vào database
                    try {
                        $stmt = $conn->prepare("INSERT INTO product_images (product_id, image_url, alt_text, is_primary, sort_order) VALUES (?, ?, ?, ?, ?)");
                        $product_id = 1; // Test với sản phẩm ID 1
                        $image_url = '/' . $file_path;
                        $alt_text = pathinfo($file_name, PATHINFO_FILENAME);
                        $is_primary = 0;
                        $sort_order = 999;
                        
                        $stmt->bind_param("issii", $product_id, $image_url, $alt_text, $is_primary, $sort_order);
                        if ($stmt->execute()) {
                            echo "<li style='color: green;'>✓ Lưu vào database thành công</li>";
                        } else {
                            echo "<li style='color: red;'>✗ Lỗi lưu database</li>";
                        }
                    } catch (Exception $e) {
                        echo "<li style='color: red;'>✗ Lỗi database: " . $e->getMessage() . "</li>";
                    }
                } else {
                    echo "<li style='color: red;'>✗ Lỗi upload file</li>";
                    $error_count++;
                }
                echo "</ul>";
            } else {
                echo "<p style='color: red;'>✗ Lỗi upload: " . $_FILES['product_images']['error'][$key] . "</p>";
                $error_count++;
            }
        }
        
        echo "<h3>Tổng kết:</h3>";
        echo "<p style='color: green;'>✓ Upload thành công: $uploaded_count file</p>";
        if ($error_count > 0) {
            echo "<p style='color: red;'>✗ Lỗi: $error_count file</p>";
        }
    } else {
        echo "<p style='color: red;'>Không có file nào được chọn</p>";
    }
} else {
    echo "<p style='color: red;'>Phương thức không hợp lệ</p>";
}

echo "<br><a href='test_upload.php'>← Quay lại test</a>";
echo "<br><a href='index.php?controller=admin&action=product_create'>← Tạo sản phẩm mới</a>";
?> 