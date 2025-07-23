<?php
// Bật hiển thị lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Debug Warehouse System</h1>";

// Test kết nối database
echo "<h2>Database Connection Test</h2>";
try {
    require_once 'config/config.php';
    echo "✅ Database connected successfully<br>";
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
    exit;
}

// Test Warehouse model
echo "<h2>Warehouse Model Test</h2>";
try {
    require_once 'model/Warehouse.php';
    $warehouse = new Warehouse($conn);
    echo "✅ Warehouse model created<br>";
    
    $warehouses = $warehouse->getAll();
    echo "✅ Found " . count($warehouses) . " warehouses<br>";
    
    foreach ($warehouses as $w) {
        echo "- " . $w['name'] . " (ID: " . $w['id'] . ")<br>";
    }
} catch (Exception $e) {
    echo "❌ Warehouse model error: " . $e->getMessage() . "<br>";
    echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
}

// Test WarehouseController
echo "<h2>WarehouseController Test</h2>";
try {
    require_once 'controller/WarehouseController.php';
    $controller = new WarehouseController($conn);
    echo "✅ WarehouseController created<br>";
} catch (Exception $e) {
    echo "❌ WarehouseController error: " . $e->getMessage() . "<br>";
    echo "Stack trace: <pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>✅ Debug completed!</h2>";
echo "<a href='index.php?controller=warehouse&action=index' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Warehouse Management</a>";
?> 