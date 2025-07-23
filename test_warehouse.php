<?php
// Bật hiển thị lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Test Warehouse System</h1>";

// Test 1: Kết nối database
echo "<h2>1. Test Database Connection</h2>";
try {
    require_once 'config/config.php';
    echo "✅ Database connection: OK<br>";
    echo "Database name: " . $dbname . "<br>";
} catch (Exception $e) {
    echo "❌ Database connection error: " . $e->getMessage() . "<br>";
    exit;
}

// Test 2: Kiểm tra bảng warehouses
echo "<h2>2. Test Warehouses Table</h2>";
try {
    $result = $conn->query("SHOW TABLES LIKE 'warehouses'");
    if ($result->num_rows > 0) {
        echo "✅ Warehouses table exists<br>";
    } else {
        echo "❌ Warehouses table does not exist<br>";
    }
} catch (Exception $e) {
    echo "❌ Error checking warehouses table: " . $e->getMessage() . "<br>";
}

// Test 3: Load Warehouse model
echo "<h2>3. Test Warehouse Model</h2>";
try {
    require_once 'model/Warehouse.php';
    $warehouse = new Warehouse($conn);
    echo "✅ Warehouse model loaded successfully<br>";
} catch (Exception $e) {
    echo "❌ Error loading Warehouse model: " . $e->getMessage() . "<br>";
}

// Test 4: Test getAll method
echo "<h2>4. Test getAll Method</h2>";
try {
    $warehouses = $warehouse->getAll();
    echo "✅ getAll method works. Found " . count($warehouses) . " warehouses<br>";
    foreach ($warehouses as $w) {
        echo "- " . $w['name'] . "<br>";
    }
} catch (Exception $e) {
    echo "❌ Error in getAll method: " . $e->getMessage() . "<br>";
}

// Test 5: Test WarehouseController
echo "<h2>5. Test WarehouseController</h2>";
try {
    require_once 'controller/WarehouseController.php';
    $controller = new WarehouseController($conn);
    echo "✅ WarehouseController loaded successfully<br>";
} catch (Exception $e) {
    echo "❌ Error loading WarehouseController: " . $e->getMessage() . "<br>";
}

echo "<h2>✅ All tests completed!</h2>";
echo "<a href='index.php?controller=warehouse&action=index' class='btn btn-primary'>Go to Warehouse Management</a>";
?> 