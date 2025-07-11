<?php
session_start();
require_once 'config/config.php';

echo "<h1>Debug Product Pages</h1>";

// Test 1: Database connection
echo "<h2>1. Database Connection:</h2>";
if ($conn->ping()) {
    echo "✅ Database connected successfully<br>";
} else {
    echo "❌ Database connection failed<br>";
}

// Test 2: Get products
echo "<h2>2. Get Products:</h2>";
try {
    $products = Product::getAllForUser($conn, 3, 0, '', 0);
    echo "✅ Found " . count($products) . " products<br>";
    foreach ($products as $product) {
        echo "- " . htmlspecialchars($product['name']) . " (ID: " . $product['id'] . ")<br>";
    }
} catch (Exception $e) {
    echo "❌ Error getting products: " . $e->getMessage() . "<br>";
}

// Test 3: Get single product
echo "<h2>3. Get Single Product:</h2>";
if (!empty($products)) {
    try {
        $product = Product::getById($conn, $products[0]['id']);
        if ($product) {
            echo "✅ Product found: " . htmlspecialchars($product['name']) . "<br>";
            
            // Test images
            $images = Product::getImages($conn, $product['id']);
            echo "Images: " . count($images) . " found<br>";
            
            // Test specs
            $specs = Product::getSpecifications($conn, $product['id']);
            echo "Specifications: " . count($specs) . " found<br>";
        } else {
            echo "❌ Product not found<br>";
        }
    } catch (Exception $e) {
        echo "❌ Error getting product: " . $e->getMessage() . "<br>";
    }
}

// Test 4: File includes
echo "<h2>4. File Includes:</h2>";
$files_to_check = [
    'view/layout/header.php',
    'view/layout/footer.php',
    'view/user/product_list.php',
    'view/user/product_detail.php'
];

foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        echo "✅ " . $file . " exists<br>";
    } else {
        echo "❌ " . $file . " missing<br>";
    }
}

// Test 5: URL structure
echo "<h2>5. Test URLs:</h2>";
echo "List products: <a href='index.php?controller=product&action=list'>index.php?controller=product&action=list</a><br>";
if (!empty($products)) {
    echo "Product detail: <a href='index.php?controller=product&action=detail&id=" . $products[0]['id'] . "'>index.php?controller=product&action=detail&id=" . $products[0]['id'] . "</a><br>";
}
?> 