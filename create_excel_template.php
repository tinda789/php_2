<?php
// thanhdat: Tạo file CSV mẫu chuẩn để import sản phẩm (tương thích với Excel)
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="product_template.csv"');

// Tạo file CSV
$output = fopen('php://output', 'w');

// Header UTF-8 BOM để Excel hiển thị tiếng Việt đúng
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Header các cột
$headers = [
    'Tên sản phẩm',
    'SKU', 
    'Danh mục',
    'Thương hiệu',
    'Giá',
    'Giá khuyến mãi',
    'Tồn kho',
    'Trạng thái',
    'Nổi bật',
    'Ảnh sản phẩm'
];
fputcsv($output, $headers);

// Dữ liệu sản phẩm mẫu
$products = [
    ['Điện thoại mẫu', 'IP15PM256GB', 'Điện thoại', 'Apple', 10000000, 9000000, 50, 'hoạt động', 'có', 'iphone15.jpg'],
    ['VGA ASUS ROG Strix RTX 4090', 'VGA4090ASUS', 'VGA', 'ASUS', 60000000, 58000000, 10, 'hoạt động', 'có', 'rtx4090.jpg'],
    ['VGA MSI GeForce RTX 4070 Ti', 'VGA4070TIMSI', 'VGA', 'MSI', 25000000, 24000000, 15, 'hoạt động', '', 'rtx4070ti.jpg'],
    ['VGA GIGABYTE RTX 4060', 'VGA4060GIGA', 'VGA', 'GIGABYTE', 12000000, 11500000, 20, 'hoạt động', '', 'rtx4060.jpg'],
    ['Mainboard ASUS ROG Z790 Hero', 'MBZ790ASUS', 'Mainboard', 'ASUS', 15000000, 14500000, 8, 'hoạt động', 'có', 'z790.jpg'],
    ['Mainboard MSI B660M Mortar', 'MBB660MMSI', 'Mainboard', 'MSI', 4000000, 3800000, 25, 'hoạt động', '', 'b660m.jpg'],
    ['Mainboard GIGABYTE B550 AORUS', 'MBB550GIGA', 'Mainboard', 'GIGABYTE', 3500000, 3300000, 18, 'hoạt động', '', 'b550.jpg'],
    ['Máy ảnh Canon EOS R6 Mark II', 'CAMR6CANON', 'Máy ảnh', 'Canon', 52000000, 50000000, 5, 'hoạt động', 'có', 'canon_r6.jpg'],
    ['Máy ảnh Sony Alpha A7 IV', 'CAMA7IVSONY', 'Máy ảnh', 'Sony', 48000000, 47000000, 7, 'hoạt động', '', 'sony_a7iv.jpg'],
    ['Máy ảnh Nikon Z6 II', 'CAMZ6IINIKON', 'Máy ảnh', 'Nikon', 42000000, 41000000, 6, 'hoạt động', '', 'nikon_z6.jpg'],
    ['VGA Zotac RTX 3060 Twin Edge', 'VGA3060ZOTAC', 'VGA', 'Zotac', 9000000, 8500000, 12, 'hoạt động', '', 'rtx3060.jpg']
];

// Thêm dữ liệu sản phẩm
foreach ($products as $product) {
    fputcsv($output, $product);
}

fclose($output);
?> 