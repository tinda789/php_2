<?php
// thanhdat: Import sản phẩm từ CSV, tự động thêm danh mục/thương hiệu nếu chưa có, cộng tồn kho nếu SKU đã tồn tại
require_once __DIR__ . '/config/config.php'; // Kết nối DB
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
    
    // Đọc file CSV
    $rows = [];
    if (($handle = fopen($inputFileName, "r")) !== FALSE) {
        // Bỏ qua BOM nếu có
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }
        
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $rows[] = $data;
        }
        fclose($handle);
    }

    $success = 0;
    $update = 0;
    $fail = 0;
    $failRows = [];
    
    for ($i = 1; $i < count($rows); $i++) { // Bỏ qua dòng tiêu đề
        $row = $rows[$i];
        // Bỏ qua dòng không đủ cột hoặc toàn bộ trường đều rỗng
        if (count($row) < 11 || implode('', $row) === '') continue;
        
        $name = trim($row[0]);
        $sku = trim($row[1]);
        $categoryName = trim($row[2]);
        $brandName = trim($row[3]);
        $price = floatval($row[4]);
        $sale_price = floatval($row[5]);
        $stock_quantity = intval($row[6]);
        $status_raw = trim($row[7]);
        $status = 'active'; // mặc định
        if ($status_raw === 'hoạt động' || $status_raw === 'active' || $status_raw === '1') {
            $status = 'active';
        } elseif ($status_raw === 'ngưng bán' || $status_raw === 'inactive' || $status_raw === '0') {
            $status = 'inactive';
        } elseif ($status_raw === 'out of stock') {
            $status = 'out of stock';
        } elseif ($status_raw === 'discontinued') {
            $status = 'discontinued';
        }
        $featured = trim($row[8]) === 'có' ? 1 : 0;
        $image_name = trim($row[9] ?? ''); // thanhdat: lấy tên ảnh từ cột thứ 10
        $image_link = trim($row[10] ?? ''); // lấy link ảnh từ cột thứ 11

        // Lấy hoặc tạo id danh mục/thương hiệu
        $category_id = getOrCreateCategory($conn, $categoryName); // thanhdat
        $brand_id = getOrCreateBrand($conn, $brandName); // thanhdat

        // Kiểm tra SKU
        $product = getProductBySKU($conn, $sku); // thanhdat
        if ($product) {
            // Cộng thêm số lượng vào tồn kho
            $new_stock = $product['stock_quantity'] + $stock_quantity;
            // Nếu có image_link mới, cập nhật luôn
            if (!empty($image_link)) {
                $sql = "UPDATE products SET stock_quantity = ?, image_link = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('isi', $new_stock, $image_link, $product['id']);
            } else {
                $sql = "UPDATE products SET stock_quantity = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ii', $new_stock, $product['id']);
            }
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
        // Gán giá trị mặc định nếu trường bị rỗng
        $description = !empty($description) ? $description : 'Chưa có mô tả chi tiết.';
        $short_description = !empty($short_description) ? $short_description : 'Chưa có mô tả ngắn.';
        $model = !empty($model) ? $model : '';
        $meta_title = !empty($meta_title) ? $meta_title : $name;
        $meta_description = !empty($meta_description) ? $meta_description : $short_description;
        // Nếu không tìm thấy category_id hoặc brand_id thì bỏ qua dòng này
        if (!$category_id || !$brand_id) continue;
        $data = [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'short_description' => $short_description,
            'price' => $price,
            'sale_price' => $sale_price,
            'stock_quantity' => $stock_quantity,
            'min_stock_level' => 0,
            'category_id' => $category_id,
            'brand_id' => $brand_id,
            'model' => $model,
            'sku' => $sku,
            'barcode' => '',
            'weight' => 0,
            'dimensions' => '',
            'warranty_period' => '',
            'status' => $status,
            'featured' => $featured,
            'image_link' => $image_link,
            'meta_title' => $meta_title,
            'meta_description' => $meta_description,
            'created_by' => 1
        ];
        $result = Product::create($conn, $data); // thanhdat
        if ($result) {
            // thanhdat: thêm ảnh sản phẩm nếu có
            if (!empty($image_name)) {
                $stmt = $conn->prepare("INSERT INTO product_images (product_id, image_url) VALUES (?, ?)");
                $stmt->bind_param("is", $result, $image_name);
                $stmt->execute();
            }
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
    echo "<br><a href='index.php?controller=admin&action=product_index'>Quay lại quản lý sản phẩm</a></h3>";
} else {
    echo "Lỗi upload file!";
}
?> 