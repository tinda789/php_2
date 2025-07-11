<?php
// Test upload ảnh sản phẩm
require_once 'config/config.php';

echo "<h2>Test Upload Ảnh Sản Phẩm</h2>";

// Kiểm tra thư mục upload
$upload_dir = 'uploads/products/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
    echo "<p style='color: green;'>✓ Đã tạo thư mục upload: $upload_dir</p>";
} else {
    echo "<p style='color: green;'>✓ Thư mục upload đã tồn tại: $upload_dir</p>";
}

// Kiểm tra quyền ghi
if (is_writable($upload_dir)) {
    echo "<p style='color: green;'>✓ Thư mục có quyền ghi</p>";
} else {
    echo "<p style='color: red;'>✗ Thư mục không có quyền ghi</p>";
}

// Test tạo file ảnh mẫu
$test_image_path = $upload_dir . 'test_image.jpg';
$test_image_content = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxAAPwA/8A');
file_put_contents($test_image_path, $test_image_content);
echo "<p style='color: green;'>✓ Đã tạo file test ảnh</p>";

// Test database connection
try {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM product_images");
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];
    echo "<p style='color: green;'>✓ Kết nối database thành công. Có $count ảnh sản phẩm</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Lỗi database: " . $e->getMessage() . "</p>";
}

// Test form upload
echo "<h3>Test Form Upload</h3>";
echo "<form method='POST' action='test_upload_process.php' enctype='multipart/form-data'>";
echo "<input type='file' name='product_images[]' multiple accept='image/*' required>";
echo "<br><br>";
echo "<input type='submit' value='Test Upload'>";
echo "</form>";

// Hiển thị ảnh hiện có
echo "<h3>Ảnh hiện có trong thư mục upload:</h3>";
$files = glob($upload_dir . '*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
if (!empty($files)) {
    echo "<div style='display: flex; flex-wrap: wrap; gap: 10px;'>";
    foreach ($files as $file) {
        $filename = basename($file);
        echo "<div style='border: 1px solid #ddd; padding: 10px; text-align: center;'>";
        echo "<img src='$file' style='width: 100px; height: 100px; object-fit: cover;' alt='$filename'>";
        echo "<br><small>$filename</small>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "<p>Không có ảnh nào trong thư mục upload</p>";
}
?> 