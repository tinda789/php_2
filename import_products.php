<?php
// thanhdat: Import sản phẩm từ Excel, tự động thêm danh mục/thương hiệu nếu chưa có, cộng tồn kho nếu SKU đã tồn tại
require_once __DIR__ . '/config/config.php'; // Kết nối DB
require_once __DIR__ . '/libs/PHPExcel-1.8/Classes/PHPExcel.php'; // Thư viện PHPExcel
require_once __DIR__ . '/model/Product.php';
require_once __DIR__ . '/model/Category.php';

// Hàm thêm mới danh mục nếu chưa có (thanhdat)
function getOrCreateCategory($conn, $name) {
    $name = trim($name);
    $sql = "SELECT id FROM categories WHERE name = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) return $row['id'];
    // Thêm mới
    $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));
    $sql = "INSERT INTO categories (name, description, parent_id, slug, image, is_active, sort_order, created_at, updated_at) VALUES (?, '', NULL, ?, '', 1, 0, NOW(), NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $name, $slug);
    $stmt->execute();
    return $conn->insert_id;
}
// Hàm thêm mới thương hiệu nếu chưa có (thanhdat)
function getOrCreateBrand($conn, $name) {
    $name = trim($name);
    $sql = "SELECT id FROM brands WHERE name = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) return $row['id'];
    // Thêm mới
    $sql = "INSERT INTO brands (name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $name);
    $stmt->execute();
    return $conn->insert_id;
}
// Hàm kiểm tra SKU đã tồn tại chưa (thanhdat)
function getProductBySKU($conn, $sku) {
    $sql = "SELECT * FROM products WHERE sku = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $sku);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
    $inputFileName = $_FILES['excel_file']['tmp_name'];
    $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
    $sheet = $objPHPExcel->getActiveSheet();
    $rows = $sheet->toArray();

    $success = 0;
    $update = 0;
    $fail = 0;
    $failRows = [];
    for ($i = 1; $i < count($rows); $i++) { // Bỏ qua dòng tiêu đề
        $row = $rows[$i];
        $name = trim($row[0]);
        $sku = trim($row[1]);
        $categoryName = trim($row[2]);
        $brandName = trim($row[3]);
        $price = floatval($row[4]);
        $sale_price = floatval($row[5]);
        $stock_quantity = intval($row[6]);
        $status = trim($row[7]) === 'hoạt động' ? 1 : 0;
        $featured = trim($row[8]) === 'có' ? 1 : 0;

        // Lấy hoặc tạo id danh mục/thương hiệu
        $category_id = getOrCreateCategory($conn, $categoryName); // thanhdat
        $brand_id = getOrCreateBrand($conn, $brandName); // thanhdat

        // Kiểm tra SKU
        $product = getProductBySKU($conn, $sku); // thanhdat
        if ($product) {
            // Cộng thêm số lượng vào tồn kho
            $new_stock = $product['stock_quantity'] + $stock_quantity;
            $sql = "UPDATE products SET stock_quantity = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ii', $new_stock, $product['id']);
            if ($stmt->execute()) {
                $update++;
            } else {
                $fail++;
                $failRows[] = $i+1;
            }
            continue;
        }
        // Tạo slug đơn giản từ tên sản phẩm
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));
        $data = [
            'name' => $name,
            'slug' => $slug,
            'description' => '',
            'short_description' => '',
            'price' => $price,
            'sale_price' => $sale_price,
            'stock_quantity' => $stock_quantity,
            'min_stock_level' => 0,
            'category_id' => $category_id,
            'brand_id' => $brand_id,
            'model' => '',
            'sku' => $sku,
            'barcode' => '',
            'weight' => 0,
            'dimensions' => '',
            'warranty_period' => '',
            'status' => $status,
            'featured' => $featured,
            'meta_title' => '',
            'meta_description' => '',
            'created_by' => 1 // hoặc id admin hiện tại
        ];
        $result = Product::create($conn, $data); // thanhdat
        if ($result) {
            $success++;
        } else {
            $fail++;
            $failRows[] = $i+1;
        }
    }
    echo "<h3>Đã thêm mới $success sản phẩm, cập nhật tồn kho $update sản phẩm.";
    if ($fail > 0) {
        echo "<br>Thất bại $fail dòng (dòng: " . implode(', ', $failRows) . ")";
    }
    echo "<br><a href='view/admin/product_index.php'>Quay lại quản lý sản phẩm</a></h3>";
} else {
    echo "Lỗi upload file!";
}
?> 