# 🛒 Hướng dẫn sử dụng chức năng đơn hàng

## 📋 Các chức năng đã hoàn thành

### 1. ✅ Quản lý giỏ hàng
- **Thay đổi số lượng**: Nhập số lượng mới và bấm nút cập nhật
- **Xóa sản phẩm**: Bấm nút xóa (thùng rác) bên cạnh sản phẩm
- **Chọn tất cả**: Checkbox "Chọn tất cả" để chọn/bỏ chọn tất cả sản phẩm

**File**: `view/user/cart_view.php`

### 2. ✅ Đặt hàng (Checkout)
- **Nhập địa chỉ giao hàng**: Textarea để nhập địa chỉ chi tiết
- **Số điện thoại**: Input để nhập số điện thoại liên hệ
- **Phương thức thanh toán**: 
  - Thanh toán khi nhận hàng (COD)
  - Chuyển khoản ngân hàng
  - VNPAY
- **Ghi chú**: Textarea để ghi chú thêm (không bắt buộc)

**File**: `view/user/checkout.php`

### 3. ✅ Lịch sử đơn hàng
- **Xem danh sách đơn hàng**: Hiển thị tất cả đơn hàng của user
- **Trạng thái đơn hàng**: 
  - Chờ xử lý (pending)
  - Đang xử lý (processing)
  - Đã gửi hàng (shipped)
  - Đã giao hàng (delivered)
  - Đã hủy (cancelled)
- **Hủy đơn hàng**: Chỉ có thể hủy đơn hàng ở trạng thái "Chờ xử lý"

**File**: `view/user/order_history.php`

### 4. ✅ Chi tiết đơn hàng
- **Thông tin sản phẩm**: Danh sách sản phẩm đã đặt
- **Thông tin giao hàng**: Địa chỉ, số điện thoại, phương thức thanh toán
- **Trạng thái đơn hàng**: Hiển thị trạng thái hiện tại và ngày đặt hàng

**File**: `view/user/order_detail.php`

## 🗄️ Cấu trúc Database

### Bảng `orders`
```sql
- id (int, primary key)
- user_id (int, foreign key)
- total_amount (decimal)
- shipping_address (text)
- phone (varchar)
- payment_method (enum: cod, bank, vnpay)
- note (text)
- status (enum: pending, processing, shipped, delivered, cancelled)
- created_at (datetime)
- updated_at (datetime)
```

### Bảng `order_items`
```sql
- id (int, primary key)
- order_id (int, foreign key)
- product_id (int, foreign key)
- quantity (int)
- price (decimal)
- created_at (datetime)
```

## 🚀 Cách sử dụng

### 1. Thêm sản phẩm vào giỏ hàng
1. Vào trang sản phẩm
2. Chọn số lượng
3. Bấm "Thêm vào giỏ hàng"

### 2. Quản lý giỏ hàng
1. Vào giỏ hàng: `index.php?controller=cart&action=view`
2. Thay đổi số lượng: Nhập số mới → Bấm nút cập nhật
3. Xóa sản phẩm: Bấm nút xóa → Xác nhận

### 3. Đặt hàng
1. Từ giỏ hàng → Bấm "Thanh toán"
2. Điền thông tin giao hàng:
   - Địa chỉ giao hàng
   - Số điện thoại
   - Chọn phương thức thanh toán
   - Ghi chú (tùy chọn)
3. Bấm "Xác nhận đặt hàng"

### 4. Xem lịch sử đơn hàng
1. Từ header → Bấm "Lịch sử đơn hàng"
2. Xem danh sách đơn hàng
3. Bấm "Xem chi tiết" để xem thông tin chi tiết
4. Bấm "Hủy đơn hàng" nếu muốn hủy (chỉ đơn hàng chờ xử lý)

## 📁 Files đã tạo/cập nhật

### Controllers
- `controller/CheckoutController.php` - Xử lý checkout và order history
- `controller/UserController.php` - Thêm action orderHistory

### Models
- `model/Order.php` - Quản lý đơn hàng
- `model/OrderItem.php` - Quản lý sản phẩm trong đơn hàng

### Views
- `view/user/checkout.php` - Trang thanh toán
- `view/user/order_history.php` - Trang lịch sử đơn hàng
- `view/user/order_detail.php` - Trang chi tiết đơn hàng
- `view/layout/header.php` - Thêm link lịch sử đơn hàng

### Database
- `database_orders.sql` - Script tạo bảng orders và order_items

## 🔧 Cài đặt

1. **Import database**:
   ```sql
   -- Chạy file database_orders.sql trong phpMyAdmin
   ```

2. **Kiểm tra quyền thư mục**:
   ```bash
   # Đảm bảo thư mục uploads/avatars/ có quyền ghi
   chmod 755 uploads/avatars/
   ```

3. **Test chức năng**:
   - Thêm sản phẩm vào giỏ hàng
   - Đặt hàng với thông tin giao hàng
   - Xem lịch sử đơn hàng
   - Xem chi tiết đơn hàng

## 🎯 Tính năng nổi bật

- ✅ **Responsive design** - Tương thích mobile
- ✅ **User-friendly UI** - Giao diện thân thiện
- ✅ **Real-time updates** - Cập nhật ngay lập tức
- ✅ **Security** - Kiểm tra quyền truy cập
- ✅ **Error handling** - Xử lý lỗi tốt
- ✅ **Validation** - Kiểm tra dữ liệu đầu vào

## 🔄 Workflow

1. **User** → Thêm sản phẩm vào giỏ hàng
2. **User** → Quản lý giỏ hàng (thay đổi số lượng, xóa)
3. **User** → Đặt hàng (checkout)
4. **System** → Tạo đơn hàng trong database
5. **User** → Xem lịch sử đơn hàng
6. **User** → Xem chi tiết đơn hàng
7. **User** → Hủy đơn hàng (nếu cần)

## 📞 Hỗ trợ

Nếu có vấn đề gì, vui lòng kiểm tra:
1. Console browser (F12) để xem lỗi JavaScript
2. Error log PHP để xem lỗi server
3. Database connection và quyền truy cập
4. File permissions cho thư mục uploads 