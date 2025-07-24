<?php
require_once 'helpers/image_helper.php';

echo "<h2>Test Image Helper Functions</h2>";

// Test cases
$test_images = [
    '6881f773319a6_1753347955.jpeg',
    'uploads/products/6881f773319a6_1753347955.jpeg',
    'http://uploads/products/6881f773319a6_1753347955.jpeg',
    'https://via.placeholder.com/300x200',
    '',
    null
];

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Input</th><th>getImageUrl() Result</th><th>imageExists()</th><th>Preview</th></tr>";

foreach ($test_images as $img) {
    $url = getImageUrl($img);
    $exists = imageExists($img);
    
    echo "<tr>";
    echo "<td>" . htmlspecialchars($img ?? 'null') . "</td>";
    echo "<td>" . htmlspecialchars($url) . "</td>";
    echo "<td>" . ($exists ? 'Yes' : 'No') . "</td>";
    echo "<td><img src='" . htmlspecialchars($url) . "' style='max-width: 100px; max-height: 100px;' onerror='this.style.display=\"none\"'></td>";
    echo "</tr>";
}

echo "</table>";

// Kiểm tra thư mục uploads
echo "<h3>Kiểm tra thư mục uploads/products/</h3>";
$upload_dir = 'uploads/products/';
if (is_dir($upload_dir)) {
    echo "<p>✅ Thư mục $upload_dir tồn tại</p>";
    $files = scandir($upload_dir);
    echo "<p>Các file trong thư mục:</p><ul>";
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "<li>$file</li>";
        }
    }
    echo "</ul>";
} else {
    echo "<p>❌ Thư mục $upload_dir không tồn tại</p>";
}
?>
