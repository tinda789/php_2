# 📝 Hướng dẫn sử dụng chức năng Quản lý đánh giá sản phẩm Thanh Dat

## 🚀 Cài đặt và thiết lập

### 1. Cập nhật Database
Chạy file `update_database.php` để cập nhật cấu trúc database:
```
http://localhost/php_2/update_database.php
```

File này sẽ:
- Thêm trường `admin_reply` vào bảng `reviews`
- Thêm dữ liệu mẫu để test chức năng

### 2. Truy cập chức năng
Sau khi đăng nhập với tài khoản admin, truy cập:
```
http://localhost/php_2/index.php?controller=review&action=index
```

Hoặc click vào menu "⭐ Quản lý đánh giá" trong sidebar admin.

## 🎯 Các chức năng chính

### 📋 1. Xem danh sách đánh giá
- **Hiển thị**: Tất cả đánh giá với thông tin chi tiết
- **Phân trang**: 10 đánh giá/trang
- **Tìm kiếm**: Theo tên sản phẩm, tên khách hàng, tiêu đề đánh giá
- **Lọc theo trạng thái**: Tất cả, Đã duyệt, Chờ duyệt, Đã xác minh

### 📊 2. Thống kê tổng quan
- Tổng số đánh giá
- Số đánh giá đã duyệt
- Số đánh giá chờ duyệt
- Số đánh giá đã xác minh

### ✅ 3. Duyệt đánh giá
- **Duyệt từng đánh giá**: Click nút "Duyệt" trên từng đánh giá
- **Duyệt hàng loạt**: Chọn nhiều đánh giá và click "Duyệt đã chọn"
- **Ẩn đánh giá**: Click nút "Ẩn" để ẩn đánh giá đã duyệt

### 🗑️ 4. Xóa đánh giá
- **Xóa từng đánh giá**: Click nút "Xóa" (có xác nhận)
- **Xóa hàng loạt**: Chọn nhiều đánh giá và click "Xóa đã chọn"
- ⚠️ **Lưu ý**: Hành động xóa không thể hoàn tác!

### 💬 5. Trả lời đánh giá
- Click nút "Trả lời" trên đánh giá
- Nhập nội dung trả lời
- Trả lời sẽ hiển thị công khai dưới đánh giá của khách hàng
- Có thể cập nhật trả lời đã có

### 🔍 6. Xem chi tiết đánh giá
- Click nút "Xem" để xem đầy đủ thông tin
- Hiển thị: Thông tin sản phẩm, khách hàng, nội dung đánh giá, ưu/nhược điểm
- Các nút thao tác nhanh

### ✅ 7. Đánh dấu xác minh
- Click nút "Xác minh" để đánh dấu đánh giá đã được xác minh
- Đánh giá xác minh sẽ có badge đặc biệt

## 🎨 Giao diện

### 📱 Responsive Design
- Hỗ trợ mobile và tablet
- Giao diện thân thiện, dễ sử dụng
- Màu sắc phân biệt rõ ràng các trạng thái

### 🎯 UX/UI Features
- **Thông báo**: Hiển thị thông báo thành công/lỗi
- **Xác nhận**: Hỏi xác nhận trước khi xóa
- **Loading**: Hiển thị trạng thái loading
- **Search**: Tìm kiếm real-time
- **Filter**: Lọc theo nhiều tiêu chí

## 🔧 Cấu trúc code

### 📁 Files chính
```
model/Review.php              # Model xử lý dữ liệu
controller/ReviewController.php # Controller xử lý logic
view/admin/review_index.php   # Trang danh sách
view/admin/review_view.php    # Trang chi tiết
view/admin/review_reply.php   # Trang trả lời
```

### 🗄️ Database
```sql
-- Bảng reviews (đã có sẵn)
CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT,
    user_id INT,
    rating INT,
    title VARCHAR(255),
    comment TEXT,
    admin_reply TEXT,  -- Trường mới thêm
    pros TEXT,
    cons TEXT,
    is_verified TINYINT(1) DEFAULT 0,
    is_approved TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## 🚨 Lưu ý quan trọng

### 🔐 Bảo mật
- Chỉ admin và super_admin mới có quyền truy cập
- Kiểm tra quyền ở mọi trang
- Validate dữ liệu đầu vào

### ⚡ Performance
- Sử dụng prepared statements để tránh SQL injection
- Phân trang để tối ưu hiệu suất
- Index database cho các trường tìm kiếm

### 🛠️ Maintenance
- Backup database trước khi cập nhật
- Test kỹ trước khi deploy production
- Monitor log lỗi

## 🔄 Workflow sử dụng

1. **Khách hàng đánh giá** → Đánh giá chờ duyệt
2. **Admin xem danh sách** → Lọc theo trạng thái
3. **Admin duyệt đánh giá** → Đánh giá hiển thị công khai
4. **Admin trả lời** (tùy chọn) → Trả lời hiển thị dưới đánh giá
5. **Admin xác minh** (tùy chọn) → Đánh giá có badge xác minh

## 📞 Hỗ trợ

Nếu gặp vấn đề, hãy kiểm tra:
1. Database connection
2. File permissions
3. PHP error logs
4. Browser console

---

**Tác giả**: AI Assistant  
**Ngày tạo**: 2025  
**Phiên bản**: 1.0 