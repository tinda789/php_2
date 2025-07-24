<!DOCTYPE html>
<html>
<head>
    <title>Test Image Display</title>
</head>
<body>
    <h2>Test hiển thị ảnh sản phẩm</h2>
    
    <h3>1. Đường dẫn trực tiếp:</h3>
    <img src="uploads/products/6881f773319a6_1753347955.jpeg" alt="Test 1" style="max-width: 200px; border: 1px solid red;">
    
    <h3>2. Sử dụng helper function:</h3>
    <?php 
    require_once 'helpers/image_helper.php';
    $image_url = getImageUrl('6881f773319a6_1753347955.jpeg');
    echo "<p>URL được tạo: " . htmlspecialchars($image_url) . "</p>";
    ?>
    <img src="<?php echo htmlspecialchars($image_url); ?>" alt="Test 2" style="max-width: 200px; border: 1px solid blue;">
    
    <h3>3. Kiểm tra file tồn tại:</h3>
    <?php
    $file_path = 'uploads/products/6881f773319a6_1753347955.jpeg';
    if (file_exists($file_path)) {
        echo "<p style='color: green;'>✅ File tồn tại: $file_path</p>";
        echo "<p>Kích thước: " . filesize($file_path) . " bytes</p>";
    } else {
        echo "<p style='color: red;'>❌ File không tồn tại: $file_path</p>";
    }
    ?>
    
    <h3>4. Thông tin server:</h3>
    <p>Document Root: <?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'N/A'; ?></p>
    <p>Script Name: <?php echo $_SERVER['SCRIPT_NAME'] ?? 'N/A'; ?></p>
    <p>Request URI: <?php echo $_SERVER['REQUEST_URI'] ?? 'N/A'; ?></p>
    <p>Current Directory: <?php echo __DIR__; ?></p>
</body>
</html>
