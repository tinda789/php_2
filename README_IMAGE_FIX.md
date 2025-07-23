# Sửa lỗi hiển thị ảnh sản phẩm

## Vấn đề đã được giải quyết

### 1. **Vấn đề ban đầu:**
- Ảnh sản phẩm không hiển thị sau khi upload
- Có sự không nhất quán trong cách lưu đường dẫn ảnh trong database
- Các loại đường dẫn khác nhau:
  - `/uploads/products/filename.jpg` (có dấu / đầu)
  - `filename.jpg` (chỉ tên file)
  - `/images/filename.jpg` (đường dẫn cũ)
  - `https://external-url.com/image.jpg` (URL bên ngoài)

### 2. **Giải pháp đã áp dụng:**

#### A. Tạo Helper Function (`helpers/image_helper.php`)
```php
function getImageUrl($image_url, $upload_dir = 'uploads/products/') {
    if (empty($image_url)) {
        return 'https://via.placeholder.com/300x200?text=No+Image';
    }
    
    // Nếu là URL bên ngoài, trả về nguyên bản
    if (preg_match('/^https?:\/\//', $image_url)) {
        return $image_url;
    }
    
    // Nếu bắt đầu bằng /, loại bỏ dấu / đầu tiên
    if (strpos($image_url, '/') === 0) {
        return ltrim($image_url, '/');
    }
    
    // Nếu không bắt đầu bằng upload_dir, thêm vào
    if (strpos($image_url, $upload_dir) !== 0) {
        return $upload_dir . $image_url;
    }
    
    return $image_url;
}
```

#### B. Cập nhật tất cả file view
- `view/user/product_detail.php`
- `view/user/product_list.php`
- `view/home.php`
- `view/admin/product_edit.php`
- `view/user/cart_view.php`

#### C. Chuẩn hóa đường dẫn trong database
- Loại bỏ dấu `/` đầu tiên
- Thêm prefix `uploads/products/` cho các file chỉ có tên
- Chuyển đổi từ `/images/` sang `uploads/products/`

## Cách sử dụng

### 1. **Trong file PHP:**
```php
require_once 'helpers/image_helper.php';

// Lấy URL ảnh đã được xử lý
$image_url = getImageUrl($product['image_url']);

// Hoặc sử dụng trực tiếp trong HTML
<img src="<?php echo htmlspecialchars(getImageUrl($img['image_url'])); ?>" alt="Product">
```

### 2. **Trong HTML:**
```html
<?php require_once 'helpers/image_helper.php'; ?>
<img src="<?php echo htmlspecialchars(getImageUrl($image['image_url'])); ?>" alt="Product">
```

## Các tính năng đã được cải thiện

### 1. **Quản lý ảnh trong Admin:**
- ✅ Nút "Cập nhật sản phẩm" nổi bật và dễ sử dụng
- ✅ Upload nhiều ảnh cùng lúc
- ✅ Preview ảnh trước khi upload
- ✅ Hiển thị ảnh hiện tại với badge "Chính"
- ✅ Nút xóa ảnh từng cái
- ✅ Thông báo thành công/lỗi

### 2. **Hiển thị ảnh cho người dùng:**
- ✅ Ảnh hiển thị đúng ở trang chi tiết sản phẩm
- ✅ Ảnh hiển thị đúng ở trang danh sách sản phẩm
- ✅ Ảnh hiển thị đúng ở trang chủ
- ✅ Ảnh hiển thị đúng trong giỏ hàng
- ✅ Fallback ảnh khi không có ảnh

### 3. **Tương thích:**
- ✅ Hỗ trợ ảnh cũ từ database
- ✅ Hỗ trợ ảnh mới upload
- ✅ Hỗ trợ URL ảnh bên ngoài
- ✅ Tự động xử lý các định dạng đường dẫn khác nhau

## Kiểm tra và bảo trì

### 1. **Kiểm tra ảnh:**
```php
// Kiểm tra file có tồn tại không
if (imageExists($image_url)) {
    echo "Ảnh tồn tại";
} else {
    echo "Ảnh không tồn tại";
}
```

### 2. **Tạo HTML tag ảnh:**
```php
// Tạo tag img với fallback
echo getImageTag($image_url, 'Alt text', 'css-class', 'width: 100px;');
```

## Lưu ý quan trọng

1. **Đường dẫn ảnh:** Tất cả ảnh sản phẩm nên được lưu trong thư mục `uploads/products/`
2. **Quyền thư mục:** Đảm bảo thư mục `uploads/products/` có quyền ghi
3. **Kích thước file:** Giới hạn 5MB cho mỗi ảnh
4. **Định dạng:** Hỗ trợ JPG, PNG, GIF, WebP

## Kết quả

✅ **Ảnh sản phẩm hiển thị đúng ở tất cả trang**
✅ **Chức năng upload ảnh hoạt động hoàn hảo**
✅ **Quản lý ảnh trong admin dễ dàng**
✅ **Tương thích với dữ liệu cũ**
✅ **Code sạch và dễ bảo trì** 