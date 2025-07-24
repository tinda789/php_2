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
    'Ảnh sản phẩm',
    'Đường link ảnh sản phẩm' // thêm cột mới
];
fputcsv($output, $headers);

// Dữ liệu sản phẩm mẫu (thanhdat: cập nhật 15 sản phẩm mới gồm chuột, tai nghe, bàn phím)
$products = [
    // Chuột máy tính
    ['Logitech MX Master 3S', 'LGMX3S', 'Chuột máy tính', 'Logitech', 2800000, 2500000, 30, 'hoạt động', 'có', 'logitech_mx_master_3s.jpg', 'https://example.com/logitech_mx_master_3s.jpg'],
    ['Razer DeathAdder V3 Pro', 'RZDAV3P', 'Chuột máy tính', 'Razer', 3200000, 3000000, 20, 'hoạt động', '', 'razer_deathadder_v3_pro.jpg', 'https://example.com/razer_deathadder_v3_pro.jpg'],
    ['Corsair Dark Core RGB Pro', 'CSRDRKRGB', 'Chuột máy tính', 'Corsair', 2700000, 2500000, 15, 'hoạt động', '', 'corsair_dark_core.jpg', 'https://example.com/corsair_dark_core.jpg'],
    ['Asus ROG Gladius III', 'ASROGGL3', 'Chuột máy tính', 'Asus', 2100000, 1900000, 18, 'hoạt động', '', 'rog_gladius_iii.jpg', 'https://example.com/rog_gladius_iii.jpg'],
    ['SteelSeries Rival 600', 'SSRVL600', 'Chuột máy tính', 'SteelSeries', 2300000, 2100000, 25, 'hoạt động', '', 'rival600.jpg', 'https://example.com/rival600.jpg'],

    // Tai nghe
    ['Sony WH-1000XM5', 'SNYWH1000XM5', 'Tai nghe', 'Sony', 9000000, 8500000, 10, 'hoạt động', 'có', 'sony_wh1000xm5.jpg', 'https://example.com/sony_wh1000xm5.jpg'],
    ['Apple AirPods Pro 2', 'APPAPP2', 'Tai nghe', 'Apple', 6500000, 6000000, 20, 'hoạt động', '', 'airpods_pro_2.jpg', 'https://example.com/airpods_pro_2.jpg'],
    ['Bose QuietComfort Ultra', 'BOSEQCU', 'Tai nghe', 'Bose', 8900000, 8700000, 12, 'hoạt động', '', 'bose_qc_ultra.jpg', 'https://example.com/bose_qc_ultra.jpg'],
    ['JBL Tune 770NC', 'JBLT770NC', 'Tai nghe', 'JBL', 2500000, 2300000, 30, 'hoạt động', '', 'jbl_tune_770nc.jpg', 'https://example.com/jbl_tune_770nc.jpg'],
    ['Anker Soundcore Life Q35', 'ANKLQ35', 'Tai nghe', 'Anker', 1900000, 1700000, 40, 'hoạt động', '', 'anker_q35.jpg', 'https://example.com/anker_q35.jpg'],

    // Bàn phím cơ
    ['Keychron K6 Wireless', 'KEYK6W', 'Bàn phím cơ', 'Keychron', 2500000, 2300000, 22, 'hoạt động', 'có', 'keychron_k6.jpg', 'https://example.com/keychron_k6.jpg'],
    ['Akko 3068B Plus', 'AKK3068BP', 'Bàn phím cơ', 'Akko', 1800000, 1650000, 18, 'hoạt động', '', 'akko_3068b_plus.jpg', 'https://example.com/akko_3068b_plus.jpg'],
    ['Logitech G Pro X', 'LOGGPROX', 'Bàn phím cơ', 'Logitech', 3200000, 3000000, 16, 'hoạt động', '', 'logitech_g_pro_x.jpg', 'https://example.com/logitech_g_pro_x.jpg'],
    ['Razer BlackWidow V4', 'RZBWV4', 'Bàn phím cơ', 'Razer', 3500000, 3300000, 14, 'hoạt động', '', 'razer_blackwidow_v4.jpg', 'https://example.com/razer_blackwidow_v4.jpg'],
    ['Ducky One 3 Mini', 'DUCKYONE3M', 'Bàn phím cơ', 'Ducky', 2900000, 2700000, 12, 'hoạt động', '', 'ducky_one3mini.jpg', 'https://example.com/ducky_one3mini.jpg'],
];


// Thêm dữ liệu sản phẩm
foreach ($products as $product) {
    fputcsv($output, $product);
}

fclose($output);
?> 