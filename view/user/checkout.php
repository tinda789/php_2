<?php
// view/user/checkout.php
// This file is included within the main layout, so no need to include header/footer here

// Set page title for the layout
$pageTitle = 'Thanh toán đơn hàng';

if (empty($cart)) {
    echo '<div class="alert alert-info text-center">Không có sản phẩm nào để thanh toán.</div>';
    return;
}

if (!empty($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>
<div class="checkout-container" style="max-width:700px;margin:0 auto;padding:20px 0;">
    <h2>Thanh toán đơn hàng</h2>
    <?php if (!empty($error)) echo '<div style="color:red">'.$error.'</div>'; ?>
    <form method="post">
        <?php if (!empty($selected_products)) foreach ($selected_products as $pid): ?>
          <input type="hidden" name="selected_products[]" value="<?= htmlspecialchars($pid) ?>">
        <?php endforeach; ?>
        <h4>Thông tin giao hàng</h4>
        
        <!-- Nút chọn địa chỉ đã lưu -->
        <div class="mb-3">
            <button type="button" class="btn btn-outline-primary btn-sm" id="showSavedAddresses">
                <i class="fas fa-address-book"></i> Chọn từ địa chỉ đã lưu
            </button>
        </div>

        <!-- Danh sách địa chỉ sẽ hiển thị ở đây -->
        <div id="savedAddresses" class="mb-3" style="display: none;">
            <div class="list-group" id="addressList">
                <!-- Địa chỉ sẽ được tải bằng JavaScript -->
                <div class="text-center py-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form nhập địa chỉ -->
        <div class="form-group">
            <label>Họ tên người nhận</label>
            <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Số điện thoại</label>
            <input type="text" name="phone" class="form-control" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Địa chỉ cụ thể</label>
            <input type="text" name="address" class="form-control" required value="<?= htmlspecialchars($_POST['address'] ?? '') ?>">
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Tỉnh/Thành phố <span class="text-danger">*</span></label>
                <select name="city" id="city" class="form-control" required onchange="loadDistricts(this.value)">
                    <option value="">Chọn Tỉnh/TP</option>
                    <!-- Danh sách tỉnh thành sẽ được tải bằng JavaScript -->
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Quận/Huyện <span class="text-danger">*</span></label>
                <select name="district" id="district" class="form-control" required onchange="loadWards(this.value)" disabled>
                    <option value="">Chọn Quận/Huyện</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Phường/Xã <span class="text-danger">*</span></label>
                <select name="ward" id="ward" class="form-control" required disabled>
                    <option value="">Chọn Phường/Xã</option>
                </select>
            </div>
        </div>
        <input type="hidden" id="city_name" name="city_name" value="<?= htmlspecialchars($_POST['city_name'] ?? '') ?>">
        <input type="hidden" id="district_name" name="district_name" value="<?= htmlspecialchars($_POST['district_name'] ?? '') ?>">
        <input type="hidden" id="ward_name" name="ward_name" value="<?= htmlspecialchars($_POST['ward_name'] ?? '') ?>">
        <div class="form-group">
            <label>Ghi chú</label>
            <textarea name="notes" class="form-control"><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
        </div>
        <h4>Phương thức thanh toán</h4>
        <div class="form-group">
            <label><input type="radio" name="payment_method" value="vnpay"> Thanh toán qua VNPay</label>
        </div>
        <h4>Thông tin đơn hàng</h4>
        <?php if (isset($cart['coupon'])): ?>
        <div class="alert alert-success mb-3">
            <i class="fas fa-tag"></i> Đã áp dụng mã giảm giá: 
            <strong><?php echo htmlspecialchars($cart['coupon']['code']); ?></strong> - 
            Giảm <?php echo $cart['coupon']['formatted_discount']; ?>
        </div>
        <?php endif; ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                $subtotal = 0;
                $discount = 0;
                
                // Sử dụng trực tiếp biến $cart đã được lọc từ controller
                foreach ($cart as $item): 
                    // Debug: Kiểm tra từng sản phẩm
                    error_log('Product in cart: ' . print_r($item, true));
                    // Đảm bảo giá và số lượng hợp lệ
                    $price = isset($item['price']) ? (float)$item['price'] : 0;
                    $sale_price = isset($item['sale_price']) ? (float)$item['sale_price'] : 0;
                    $quantity = isset($item['quantity']) ? max(1, (int)$item['quantity']) : 1;
                    
                    // Chọn giá tốt nhất (giá khuyến mãi nếu có, không thì giá gốc)
                    $final_price = ($sale_price > 0 && $sale_price < $price) ? $sale_price : $price;
                    
                    // Tính tiền cho từng sản phẩm
                    $line_total = round($final_price * $quantity);
                    $original_line_total = $price * $quantity;
                    $subtotal += $original_line_total;
                    $discount += ($price - $final_price) * $quantity;
                    $total += $line_total;
                    
                    // Debug log
                    error_log(sprintf(
                        'Product: %s, Price: %s, Sale: %s, Qty: %d, Final: %s, Line Total: %s', 
                        $item['name'] ?? '',
                        $price,
                        $sale_price,
                        $quantity,
                        $final_price,
                        $line_total
                    ));
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['name'] ?? '') ?></td>
                    <td class="text-end">
                        <?php if ($sale_price > 0 && $sale_price < $price): ?>
                            <span class="text-decoration-line-through text-muted small d-block"><?= number_format($price, 0, ",", ".") ?>₫</span>
                            <span class="text-danger fw-bold"><?= number_format($sale_price, 0, ",", ".") ?>₫</span>
                        <?php else: ?>
                            <?= number_format($price, 0, ",", ".") ?>₫
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?= $quantity ?>
                    </td>
                    <td class="text-end fw-bold">
                        <?= number_format($line_total, 0, ",", ".") ?>₫
                        <div class="text-muted small">(<?= number_format($final_price, 0, ",", ".") ?>₫ x <?= $quantity ?>)</div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <!-- Coupon Code -->
                <tr>
                    <td colspan="4">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="coupon_code" name="coupon_code" placeholder="Nhập mã giảm giá" value="<?= htmlspecialchars($_POST['coupon_code'] ?? '') ?>">
                            <button class="btn btn-outline-primary" type="button" id="apply_coupon">Áp dụng</button>
                        </div>
                        <div id="coupon_message" class="small"></div>
                    </td>
                </tr>
                <?php 
                $coupon_discount = 0;
                if (!empty($_SESSION['applied_coupon'])): 
                    $coupon = $_SESSION['applied_coupon'];
                    
                    // Tính toán giảm giá dựa trên tổng tiền trước giảm giá
                    if ($coupon['type'] == 'fixed') {
                        $coupon_discount = min($coupon['value'], $subtotal); // Không giảm quá tổng tiền
                    } else { // percentage
                        $coupon_discount = $subtotal * ($coupon['value'] / 100);
                        // Áp dụng giới hạn giảm giá tối đa nếu có
                        if (!empty($coupon['maximum_discount']) && $coupon_discount > $coupon['maximum_discount']) {
                            $coupon_discount = $coupon['maximum_discount'];
                        }
                    }
                    
                    // Làm tròn xuống 1000 đồng gần nhất
                    $coupon_discount = floor($coupon_discount / 1000) * 1000;
                    
                    // Đảm bảo không âm
                    if ($coupon_discount < 0) $coupon_discount = 0;
                    
                    // Cập nhật lại tổng tiền sau khi giảm giá
                    $total = $subtotal - $coupon_discount;
                    if ($total < 0) $total = 0;
                ?>
                <tr class="table-active">
                    <th colspan="3" class="text-end">Giảm giá (<?= htmlspecialchars($coupon['code']) ?>):</th>
                    <th class="text-end text-success">-<?= number_format($coupon_discount, 0, ",", ".") ?>₫</th>
                    <input type="hidden" name="applied_coupon_code" value="<?= htmlspecialchars($coupon['code']) ?>">
                </tr>
                <?php 
                endif; 
                
                // Tính lại tổng tiền hàng
                $subtotal = 0;
                if (is_array($cart)) {
                    foreach ($cart as $item) {
                        if (is_array($item) && isset($item['price'], $item['quantity'])) {
                            $price = $item['sale_price'] > 0 ? $item['sale_price'] : $item['price'];
                            $subtotal += $price * $item['quantity'];
                        }
                    }
                }
                
                // Nếu có áp dụng mã giảm giá từ session
                if (isset($coupon_discount) && $coupon_discount > 0) {
                    $total = $subtotal - $coupon_discount;
                    if ($total < 0) $total = 0;
                } else {
                    $total = $subtotal;
                }
                ?>
                <tr>
                    <td colspan="3" class="text-end"><strong>Tổng tiền hàng:</strong></td>
                    <td class="text-end"><?= number_format($subtotal, 0, ",", ".") ?>₫</td>
                </tr>
                
                <?php if (isset($coupon_discount) && $coupon_discount > 0): ?>
                <tr class="table-active">
                    <td colspan="3" class="text-end"><strong>Tổng thanh toán:</strong></td>
                    <td class="text-end fw-bold text-danger">
                        <?= number_format($total, 0, ",", ".") ?>₫
                    </td>
                </tr>
                <?php else: ?>
                <tr class="table-active">
                    <td colspan="3" class="text-end"><strong>Tổng thanh toán:</strong></td>
                    <td class="text-end fw-bold text-danger">
                        <?= number_format($subtotal, 0, ",", ".") ?>₫
                    </td>
                </tr>
                <?php endif; ?>
                <?php if ($discount > 0): ?>
                <tr>
                    <th colspan="3" class="text-end text-success">Giảm giá:</th>
                    <th class="text-end text-success">-<?= number_format($discount, 0, ",", ".") ?>₫</th>
                </tr>
                <?php endif; ?>
                <tr>
                    <td colspan="4" class="text-muted small">
                        <i class="fas fa-info-circle"></i> Đã bao gồm VAT (nếu có) và phí vận chuyển sẽ được tính ở bước sau.
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="d-flex justify-content-between align-items-center">
            <button type="submit" class="btn btn-primary">Đặt hàng & Thanh toán</button>
            <a href="index.php?controller=cart" class="btn btn-outline-secondary">Quay lại giỏ hàng</a>
        </div>
    </form>
</div>

<!-- Modal xác nhận xóa địa chỉ -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa địa chỉ này?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Xóa</button>
            </div>
        </div>
    </div>
</div>

<script>
// Biến toàn cục

// Hàm lưu dữ liệu vào session storage
function saveToSessionStorage(key, data) {
    try {
        sessionStorage.setItem(key, JSON.stringify(data));
        console.log(`Đã lưu vào sessionStorage: ${key}`, data);
    } catch (e) {
        console.error('Lỗi khi lưu vào sessionStorage:', e);
    }
}

// Hàm lấy dữ liệu từ session storage
function getFromSessionStorage(key) {
    try {
        const data = sessionStorage.getItem(key);
        if (data) {
            const parsed = JSON.parse(data);
            console.log(`Đã tải từ sessionStorage: ${key}`, parsed);
            return parsed;
        }
        return null;
    } catch (e) {
        console.error('Lỗi khi đọc từ sessionStorage:', e);
        return null;
    }
}

// Hàm lưu dữ liệu tỉnh/thành phố
function saveProvincesToStorage(provinces) {
    saveToSessionStorage('provinces', provinces);
}

// Hàm lưu dữ liệu quận/huyện theo mã tỉnh
function saveDistrictsToStorage(provinceCode, districts) {
    saveToSessionStorage(`districts_${provinceCode}`, districts);
}

// Hàm lưu dữ liệu phường/xã theo mã quận
function saveWardsToStorage(districtCode, wards) {
    saveToSessionStorage(`wards_${districtCode}`, wards);
}

// Hàm lấy dữ liệu tỉnh/thành phố từ storage
function getProvincesFromStorage() {
    return getFromSessionStorage('provinces');
}

// Hàm lấy dữ liệu quận/huyện từ storage
function getDistrictsFromStorage(provinceCode) {
    return getFromSessionStorage(`districts_${provinceCode}`);
}

// Hàm lấy dữ liệu phường/xã từ storage
function getWardsFromStorage(districtCode) {
    return getFromSessionStorage(`wards_${districtCode}`);
}

// Hàm tải danh sách tỉnh/thành phố
async function loadProvinces() {
    const citySelect = document.getElementById('city');
    citySelect.innerHTML = '<option value="">Đang tải tỉnh/thành phố...</option>';
    
    try {
        console.log('Đang tải danh sách tỉnh/thành phố...');
        const response = await fetch('https://provinces.open-api.vn/api/p/');
        
        if (!response.ok) {
            throw new Error(`Lỗi HTTP: ${response.status}`);
        }
        
        const provinces = await response.json();
        console.log('Đã tải xong danh sách tỉnh/thành phố:', provinces);
        
        // Xóa option đang tải
        citySelect.innerHTML = '<option value="">Chọn Tỉnh/Thành phố</option>';
        
        // Sắp xếp tỉnh thành theo thứ tự alphabet
        provinces.sort((a, b) => a.name.localeCompare(b.name));
        
        // Thêm từng tỉnh thành vào select
        provinces.forEach(province => {
            const option = new Option(province.name, province.code);
            citySelect.add(option);
        });
        
        // Kích hoạt select tỉnh/thành phố
        citySelect.disabled = false;
        
        // Kiểm tra nếu có giá trị từ POST thì chọn lại
        const savedCityName = '<?= $_POST['city_name'] ?? '' ?>';
        if (savedCityName) {
            const optionToSelect = Array.from(citySelect.options).find(
                option => option.text === savedCityName
            );
            if (optionToSelect) {
                optionToSelect.selected = true;
                loadDistricts(optionToSelect.value);
            }
        }
        
    } catch (error) {
        console.error('Lỗi khi tải tỉnh/thành phố:', error);
        citySelect.innerHTML = '<option value="">Lỗi khi tải tỉnh/thành phố</option>';
    }
}

// Hàm tải danh sách quận/huyện
async function loadDistricts(provinceCode) {
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    
    // Reset các select phụ thuộc
    districtSelect.innerHTML = '<option value="">Đang tải quận/huyện...</option>';
    wardSelect.innerHTML = '<option value="">Vui lòng chọn quận/huyện trước</option>';
    wardSelect.disabled = true;
    
    if (!provinceCode) {
        districtSelect.innerHTML = '<option value="">Vui lòng chọn tỉnh/thành phố trước</option>';
        return;
    }
    
    try {
        // Kiểm tra trong session storage trước
        const cachedDistricts = getDistrictsFromStorage(provinceCode);
        if (cachedDistricts) {
            console.log(`Đang sử dụng dữ liệu quận/huyện từ bộ nhớ đệm cho tỉnh ${provinceCode}`);
            renderDistricts(cachedDistricts);
            return;
        }
        
        console.log(`Đang tải danh sách quận/huyện cho tỉnh ${provinceCode}...`);
        const response = await fetch(`index.php?controller=address&action=getDistricts&code=${encodeURIComponent(provinceCode)}`);
        
        if (!response.ok) {
            throw new Error(`Lỗi HTTP: ${response.status}`);
        }
        
        const data = await response.json();
        const districts = data.districts || [];
        
        // Lưu vào session storage
        saveDistrictsToStorage(provinceCode, districts);
        
        // Hiển thị lên select
        renderDistricts(districts);
        
    } catch (error) {
        console.error('Lỗi khi tải quận/huyện:', error);
        districtSelect.innerHTML = '<option value="">Lỗi khi tải dữ liệu</option>';
    }
}

// Hàm hiển thị danh sách quận/huyện
function renderDistricts(districts) {
    const districtSelect = document.getElementById('district');
    districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
    
    // Sắp xếp quận/huyện theo thứ tự alphabet
    districts.sort((a, b) => a.name.localeCompare(b.name));
    
    districts.forEach(district => {
        const option = new Option(district.name, district.code);
        districtSelect.add(option);
    });
    
    districtSelect.disabled = false;
    
    // Kiểm tra nếu có giá trị từ POST thì chọn lại
    const savedDistrictName = '<?= $_POST['district_name'] ?? '' ?>';
    if (savedDistrictName) {
        const optionToSelect = Array.from(districtSelect.options).find(
            option => option.text === savedDistrictName
        );
        if (optionToSelect) {
            optionToSelect.selected = true;
            loadWards(optionToSelect.value);
        }
    }
}

// Hàm tải danh sách phường/xã
async function loadWards(districtCode) {
    const wardSelect = document.getElementById('ward');
    
    // Reset select phường/xã
    wardSelect.innerHTML = '<option value="">Đang tải phường/xã...</option>';
    wardSelect.disabled = true;
    
    if (!districtCode) {
        wardSelect.innerHTML = '<option value="">Vui lòng chọn quận/huyện trước</option>';
        return;
    }
    
    try {
        // Kiểm tra trong session storage trước
        const cachedWards = getWardsFromStorage(districtCode);
        if (cachedWards) {
            console.log(`Đang sử dụng dữ liệu phường/xã từ bộ nhớ đệm cho quận ${districtCode}`);
            renderWards(cachedWards);
            return;
        }
        
        console.log(`Đang tải danh sách phường/xã cho quận ${districtCode}...`);
        const response = await fetch(`index.php?controller=address&action=getWards&code=${encodeURIComponent(districtCode)}`);
        
        if (!response.ok) {
            throw new Error(`Lỗi HTTP: ${response.status}`);
        }
        
        const data = await response.json();
        const wards = data.wards || [];
        
        // Lưu vào session storage
        saveWardsToStorage(districtCode, wards);
        
        // Hiển thị lên select
        renderWards(wards);
        
    } catch (error) {
        console.error('Lỗi khi tải phường/xã:', error);
        wardSelect.innerHTML = '<option value="">Lỗi khi tải dữ liệu</option>';
    }
}

// Hàm hiển thị danh sách phường/xã
function renderWards(wards) {
    const wardSelect = document.getElementById('ward');
    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
    
    // Sắp xếp phường/xã theo thứ tự alphabet
    wards.sort((a, b) => a.name.localeCompare(b.name));
    
    wards.forEach(ward => {
        const option = new Option(ward.name, ward.code);
        wardSelect.add(option);
    });
    
    wardSelect.disabled = false;
    
    // Kiểm tra nếu có giá trị từ POST thì chọn lại
    const savedWardName = '<?= $_POST['ward_name'] ?? '' ?>';
    if (savedWardName) {
        const optionToSelect = Array.from(wardSelect.options).find(
            option => option.text === savedWardName
        );
        if (optionToSelect) {
            optionToSelect.selected = true;
        }
    }
}

// Hàm tải danh sách địa chỉ đã lưu
async function loadSavedAddresses() {
    try {
        const response = await fetch('index.php?controller=address&action=getUserAddresses');
        const result = await response.json();
        
        if (result.success) {
            addresses = result.data;
            const addressList = document.getElementById('addressList');
            addressList.innerHTML = '';
            
            if (addresses.length === 0) {
                addressList.innerHTML = `
                    <div class="list-group-item">
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p>Bạn chưa có địa chỉ nào được lưu</p>
                        </div>
                    </div>`;
                return;
            }
            
            addresses.forEach(address => {
                const addressItem = document.createElement('div');
                addressItem.className = `list-group-item ${address.is_default ? 'border-primary' : ''}`;
                addressItem.innerHTML = `
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">${address.first_name} ${address.last_name} ${address.is_default ? '<span class="badge bg-primary">Mặc định</span>' : ''}</h6>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-edit-address" data-id="${address.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete-address" data-id="${address.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <p class="mb-1">${address.phone}</p>
                    <p class="mb-1">${address.address_line1}</p>
                    <small>${address.ward ? address.ward + ', ' : ''}${address.district ? address.district + ', ' : ''}${address.city || ''}</small>
                    <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-primary btn-select-address" data-address='${JSON.stringify(address)}'>
                            <i class="fas fa-check"></i> Chọn địa chỉ này
                        </button>
                        ${!address.is_default ? `
                        <button type="button" class="btn btn-sm btn-outline-secondary btn-set-default" data-id="${address.id}">
                            Đặt làm mặc định
                        </button>` : ''}
                    </div>`;
                
                addressList.appendChild(addressItem);
            });
            
            // Thêm sự kiện cho các nút
            document.querySelectorAll('.btn-select-address').forEach(btn => {
                btn.addEventListener('click', function() {
                    const address = JSON.parse(this.dataset.address);
                    fillAddressForm(address);
                    document.getElementById('savedAddresses').style.display = 'none';
                });
            });
            
            document.querySelectorAll('.btn-set-default').forEach(btn => {
                btn.addEventListener('click', async function() {
                    try {
                        const response = await fetch('index.php?controller=address&action=setDefault', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `id=${this.dataset.id}`
                        });
                        const result = await response.json();
                        
                        if (result.success) {
                            loadSavedAddresses(); // Tải lại danh sách
                        } else {
                            alert('Có lỗi xảy ra: ' + (result.message || 'Không thể đặt làm mặc định'));
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi xử lý yêu cầu');
                    }
                });
            });
            
            document.querySelectorAll('.btn-delete-address').forEach(btn => {
                btn.addEventListener('click', function() {
                    addressToDelete = this.dataset.id;
                    $('#confirmDeleteModal').modal('show');
                });
            });
        }
    } catch (error) {
        console.error('Lỗi khi tải địa chỉ:', error);
        document.getElementById('addressList').innerHTML = `
            <div class="list-group-item">
                <div class="alert alert-danger">Đã xảy ra lỗi khi tải địa chỉ. Vui lòng thử lại sau.</div>
            </div>`;
    }
}

// Hàm điền địa chỉ vào form
function fillAddressForm(address) {
    const fullName = (address.first_name || '') + ' ' + (address.last_name || '');
    document.querySelector('input[name="name"]').value = fullName.trim();
    document.querySelector('input[name="first_name"]').value = address.first_name || '';
    document.querySelector('input[name="last_name"]').value = address.last_name || '';
    document.querySelector('input[name="phone"]').value = address.phone || '';
    document.querySelector('input[name="address"]').value = address.address_line1 || '';
    document.querySelector('input[name="city"]').value = address.city || '';
    document.querySelector('input[name="district"]').value = address.district || '';
    document.querySelector('input[name="ward"]').value = address.ward || '';
}

// Hàm tải danh sách tỉnh/thành phố
async function loadProvinces() {
    const citySelect = document.getElementById('city');
    citySelect.innerHTML = '<option value="">Đang tải tỉnh/thành phố...</option>';
    
    try {
        console.log('Đang tải danh sách tỉnh/thành phố...');
        const response = await fetch('https://provinces.open-api.vn/api/p/');
        
        if (!response.ok) {
            throw new Error(`Lỗi HTTP: ${response.status}`);
        }
        
        const provinces = await response.json();
        console.log('Đã tải xong danh sách tỉnh/thành phố:', provinces);
        
        // Xóa option đang tải
        citySelect.innerHTML = '<option value="">Chọn Tỉnh/Thành phố</option>';
        
        // Sắp xếp tỉnh thành theo thứ tự alphabet
        provinces.sort((a, b) => a.name.localeCompare(b.name));
        
        // Thêm từng tỉnh thành vào select
        provinces.forEach(province => {
            const option = new Option(province.name, province.code);
            citySelect.add(option);
        });
        
        // Kiểm tra nếu có giá trị từ POST thì chọn lại
        const savedCityName = '<?= $_POST['city_name'] ?? '' ?>';
        if (savedCityName) {
            const optionToSelect = Array.from(citySelect.options).find(
                option => option.text === savedCityName
            );
            if (optionToSelect) {
                optionToSelect.selected = true;
                loadDistricts(optionToSelect.value);
            }
        }
        
        // Kích hoạt sự kiện onchange cho select tỉnh/thành phố
        citySelect.onchange = function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('city_name').value = selectedOption.text;
            
            // Nếu đã chọn tỉnh/thành phố thì tải quận/huyện
            if (this.value) {
                loadDistricts(this.value);
            } else {
                // Reset quận/huyện và phường/xã nếu không chọn tỉnh/thành phố
                const districtSelect = document.getElementById('district');
                const wardSelect = document.getElementById('ward');
                
                districtSelect.innerHTML = '<option value="">Vui lòng chọn tỉnh/thành phố trước</option>';
                districtSelect.disabled = true;
                
                wardSelect.innerHTML = '<option value="">Vui lòng chọn quận/huyện trước</option>';
                wardSelect.disabled = true;
            }
        };
        
    } catch (error) {
        console.error('Lỗi khi tải tỉnh/thành phố:', error);
        citySelect.innerHTML = '<option value="">Không thể tải danh sách tỉnh/thành phố</option>';
        alert('Không thể tải danh sách tỉnh/thành phố. Vui lòng tải lại trang hoặc kiểm tra kết nối mạng.');
    }
}

// Hàm tải danh sách quận/huyện
async function loadDistricts(provinceCode) {
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    
    // Reset các select phụ thuộc
    districtSelect.innerHTML = '<option value="">Đang tải quận/huyện...</option>';
    districtSelect.disabled = true;
    wardSelect.innerHTML = '<option value="">Vui lòng chọn quận/huyện trước</option>';
    wardSelect.disabled = true;
    
    if (!provinceCode) return;
    
    // Kiểm tra xem đã có dữ liệu trong session storage chưa
    const cacheKey = `districts_${provinceCode}`;
    console.log(`=== BẮT ĐẦU TẢI QUẬN/HUYỆN CHO TỈNH ${provinceCode} ===`);
    
    const cachedDistricts = getFromSessionStorage(cacheKey);
    if (cachedDistricts) {
        console.log('Đã tìm thấy dữ liệu trong bộ nhớ đệm');
    } else {
        console.log('Không tìm thấy dữ liệu trong bộ nhớ đệm, đang tải từ API...');
    }
    
    if (cachedDistricts) {
        console.log(`Đang sử dụng dữ liệu quận/huyện từ bộ nhớ đệm cho tỉnh ${provinceCode}`);
        renderDistricts(cachedDistricts);
        return;
    }
    
    try {
        console.log(`Đang tải danh sách quận/huyện từ API cho tỉnh ${provinceCode}...`);
        const response = await fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`);
        
        if (!response.ok) {
            throw new Error(`Lỗi HTTP: ${response.status}`);
        }
        
        const provinceData = await response.json();
        console.log(`Đã tải xong danh sách quận/huyện cho tỉnh ${provinceCode}:`, provinceData);
        
        // Lưu vào session storage
        saveToSessionStorage(cacheKey, provinceData.districts);
        
        // Hiển thị dữ liệu
        renderDistricts(provinceData.districts);
        
        // Cập nhật tên tỉnh/thành phố vào hidden field
        const citySelect = document.getElementById('city');
        const selectedCity = citySelect.options[citySelect.selectedIndex];
        document.getElementById('city_name').value = selectedCity.text;
        
        // Xóa option đang tải
        districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
        
        // Sắp xếp quận/huyện theo thứ tự alphabet
        provinceData.districts.sort((a, b) => a.name.localeCompare(b.name));
        
        // Thêm các quận/huyện vào select
        provinceData.districts.forEach(district => {
            const option = new Option(district.name, district.code);
            districtSelect.add(option);
        });
        
        // Kích hoạt select quận/huyện
        districtSelect.disabled = false;
        
        // Kiểm tra nếu có giá trị từ POST thì chọn lại
        const savedDistrictName = '<?= $_POST['district_name'] ?? '' ?>';
        if (savedDistrictName) {
            const optionToSelect = Array.from(districtSelect.options).find(
                option => option.text === savedDistrictName
            );
            if (optionToSelect) {
                optionToSelect.selected = true;
                loadWards(optionToSelect.value);
            }
        }
        
        // Thêm sự kiện onchange cho districtSelect
        districtSelect.onchange = function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('district_name').value = selectedOption.text;
            
            // Nếu đã chọn quận/huyện thì tải phường/xã
            if (this.value) {
                loadWards(this.value);
            } else {
                // Reset phường/xã nếu không chọn quận/huyện
                const wardSelect = document.getElementById('ward');
                wardSelect.innerHTML = '<option value="">Vui lòng chọn quận/huyện trước</option>';
                wardSelect.disabled = true;
            }
        };
        
    } catch (error) {
        console.error('Lỗi khi tải quận/huyện:', error);
        districtSelect.innerHTML = '<option value="">Không thể tải danh sách quận/huyện</option>';
        alert('Không thể tải danh sách quận/huyện. Vui lòng tải lại trang hoặc thử lại sau.');
    }
}

// Hàm tải danh sách phường/xã
async function loadWards(districtCode) {
    const wardSelect = document.getElementById('ward');
    
    // Reset select phường/xã
    wardSelect.innerHTML = '<option value="">Đang tải phường/xã...</option>';
    wardSelect.disabled = true;
    
    if (!districtCode) {
        wardSelect.innerHTML = '<option value="">Vui lòng chọn quận/huyện trước</option>';
        return;
    }
    
    // Kiểm tra xem đã có dữ liệu trong session storage chưa
    const cacheKey = `wards_${districtCode}`;
    const cachedWards = getFromSessionStorage(cacheKey);
    
    if (cachedWards) {
        console.log(`Đang sử dụng dữ liệu phường/xã từ bộ nhớ đệm cho quận ${districtCode}`);
        renderWards(cachedWards);
        return;
    }
    
    try {
        console.log(`Đang tải danh sách phường/xã từ API cho quận ${districtCode}...`);
        const response = await fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`);
        
        if (!response.ok) {
            throw new Error(`Lỗi HTTP: ${response.status}`);
        }
        
        const districtData = await response.json();
        console.log(`Đã tải xong danh sách phường/xã cho quận ${districtCode}:`, districtData);
        
        // Lưu vào session storage
        saveToSessionStorage(cacheKey, districtData.wards);
        
        // Hiển thị dữ liệu
        renderWards(districtData.wards);
        
        // Cập nhật tên quận/huyện vào hidden field
        const districtSelect = document.getElementById('district');
        const selectedDistrict = districtSelect.options[districtSelect.selectedIndex];
        document.getElementById('district_name').value = selectedDistrict.text;
        
        // Xóa option đang tải
        wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        
        // Sắp xếp phường/xã theo thứ tự alphabet
        districtData.wards.sort((a, b) => a.name.localeCompare(b.name));
        
        // Thêm các phường/xã vào select
        districtData.wards.forEach(ward => {
            const option = new Option(ward.name, ward.code);
            wardSelect.add(option);
        });
        
        // Kích hoạt select phường/xã
        wardSelect.disabled = false;
        
        // Kiểm tra nếu có giá trị từ POST thì chọn lại
        const savedWardName = '<?= $_POST['ward_name'] ?? '' ?>';
        if (savedWardName) {
            const optionToSelect = Array.from(wardSelect.options).find(
                option => option.text === savedWardName
            );
            if (optionToSelect) {
                optionToSelect.selected = true;
            }
        }
        
        // Cập nhật tên phường/xã khi chọn
        wardSelect.onchange = function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('ward_name').value = selectedOption.text;
        };
        
    } catch (error) {
        console.error('Lỗi khi tải phường/xã:', error);
        wardSelect.innerHTML = '<option value="">Không thể tải danh sách phường/xã</option>';
        alert('Không thể tải danh sách phường/xã. Vui lòng thử lại sau.');
    }
}

// Hàm xử lý áp dụng mã giảm giá
async function applyCoupon() {
    const couponCode = document.getElementById('coupon_code').value.trim();
    const applyBtn = document.getElementById('apply_coupon');
    const originalBtnText = applyBtn.innerHTML;
    
    if (!couponCode) {
        showCouponMessage('Vui lòng nhập mã giảm giá', 'text-danger');
        return;
    }

    try {
        // Show loading state
        applyBtn.disabled = true;
        applyBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xử lý...';
        
        const response = await fetch('index.php?controller=cart&action=applyCoupon', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `coupon_code=${encodeURIComponent(couponCode)}`
        });
        
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            console.error('Unexpected response:', text);
            throw new Error('Phản hồi không hợp lệ từ máy chủ');
        }
        
        const result = await response.json();
        
        if (result.success) {
            showCouponMessage(result.message, 'text-success');
            // Reload page to update cart with new discount
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showCouponMessage(result.message || 'Có lỗi xảy ra khi áp dụng mã giảm giá', 'text-danger');
        }
    } catch (error) {
        console.error('Lỗi khi áp dụng mã giảm giá:', error);
        showCouponMessage(error.message || 'Đã xảy ra lỗi khi xử lý yêu cầu', 'text-danger');
    } finally {
        // Reset button state
        if (applyBtn) {
            applyBtn.disabled = false;
            applyBtn.innerHTML = originalBtnText;
        }
    }
}

// Hàm hiển thị thông báo mã giảm giá
function showCouponMessage(message, className) {
    const messageElement = document.getElementById('coupon_message');
    messageElement.textContent = message;
    messageElement.className = 'small ' + className;
}

document.addEventListener('DOMContentLoaded', function() {
    // Tải danh sách tỉnh/thành phố khi trang được tải
    loadProvinces();
    
    // Xử lý sự kiện nhấn nút áp dụng mã giảm giá
    const applyCouponBtn = document.getElementById('apply_coupon');
    if (applyCouponBtn) {
        applyCouponBtn.addEventListener('click', applyCoupon);
    }
    
    // Xử lý sự kiện nhấn Enter trong ô mã giảm giá
    const couponInput = document.getElementById('coupon_code');
    if (couponInput) {
        couponInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyCoupon();
            }
        });
    }
    
    // Cập nhật tên quận/huyện khi chọn
    document.getElementById('district').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('district_name').value = selectedOption.text;
    });
    
    // Cập nhật tên phường/xã khi chọn
    document.getElementById('ward').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('ward_name').value = selectedOption.text;
    });
    
    // Xử lý hiển thị form địa chỉ đã lưu
    document.getElementById('showSavedAddresses').addEventListener('click', function() {
        const savedAddresses = document.getElementById('savedAddresses');
        if (savedAddresses.style.display === 'none') {
            loadSavedAddresses();
            savedAddresses.style.display = 'block';
        } else {
            savedAddresses.style.display = 'none';
        }
    });
    
    // Xác nhận xóa địa chỉ
    document.getElementById('confirmDeleteBtn').addEventListener('click', async function() {
        if (!addressToDelete) return;
        
        try {
            const response = await fetch('index.php?controller=address&action=delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${addressToDelete}`
            });
            
            const result = await response.json();
            
            if (result.success) {
                $('#confirmDeleteModal').modal('hide');
                loadSavedAddresses();
            } else {
                alert('Có lỗi xảy ra: ' + (result.message || 'Không thể xóa địa chỉ'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xử lý yêu cầu');
        } finally {
            addressToDelete = null;
        }
    });

    // Thanh toán form
    const checkoutForm = document.querySelector('form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            // Validate địa chỉ
            const citySelect = document.getElementById('city');
            const districtSelect = document.getElementById('district');
            const wardSelect = document.getElementById('ward');
            
            if (!citySelect || !districtSelect || !wardSelect || 
                !citySelect.value || !districtSelect.value || !wardSelect.value) {
                e.preventDefault();
                alert('Vui lòng chọn đầy đủ thông tin địa chỉ (Tỉnh/Thành phố, Quận/Huyện, Phường/Xã)');
                return false;
            }
            
            // Validate phương thức thanh toán
            const paymentChecked = checkoutForm.querySelector('input[name="payment_method"]:checked');
            if (!paymentChecked) {
                alert('Vui lòng chọn phương thức thanh toán!');
                e.preventDefault();
                return false;
            }
            
            // Validate các trường required (HTML5 đã có, nhưng thêm JS cho chắc)
            const requiredFields = checkoutForm.querySelectorAll('[required]');
            for (let field of requiredFields) {
                if (!field.value.trim()) {
                    alert('Vui lòng nhập đầy đủ thông tin giao hàng!');
                    field.focus();
                    e.preventDefault();
                    return false;
                }
            }
            
            // If all validations pass, the form will submit
            return true;
        });
    }
});


// Chỉ ghi đè hàm fetch nếu chưa được ghi đè trước đó
if (!window.fetch._isOverridden) {
    const originalFetch = window.fetch;
    
    window.fetch = async function(resource, options) {
        // Chỉ log các API địa chỉ
        if (typeof resource === 'string' && resource.includes('provinces.open-api.vn')) {
            console.log('\n=== BẮT ĐẦU GỌI API ===');
            console.log('URL:', resource);
            
            try {
                const startTime = performance.now();
                const response = await originalFetch.apply(this, arguments);
                const endTime = performance.now();
                
                console.log('Thời gian phản hồi: ' + (endTime - startTime).toFixed(2) + 'ms');
                console.log('Status:', response.status, response.statusText);
                
                const responseClone = response.clone();
                
                // Log dữ liệu phản hồi
                try {
                    const data = await responseClone.json();
                    console.log('=== DỮ LIỆU TRẢ VỀ ===');
                    
                    if (resource.includes('/p/')) {
                        if (resource.includes('depth=2')) {
                            // Dữ liệu quận/huyện
                            console.log('LOẠI DỮ LIỆU: Danh sách quận/huyện');
                            console.log('Tỉnh/Thành phố:', data.name);
                            console.log('Mã:', data.code);
                            console.log('Số lượng quận/huyện:', data.districts ? data.districts.length : 0);
                            console.log('Mẫu dữ liệu:', data.districts ? data.districts.slice(0, 3) : []);
                        } else {
                            // Dữ liệu tỉnh/thành phố
                            console.log('LOẠI DỮ LIỆU: Danh sách tỉnh/thành phố');
                            console.log('Số lượng:', data.length);
                            console.log('Mẫu dữ liệu (5 tỉnh đầu):', data.slice(0, 5));
                        }
                    } else if (resource.includes('/d/')) {
                        // Dữ liệu phường/xã
                        console.log('LOẠI DỮ LIỆU: Danh sách phường/xã');
                        console.log('Quận/Huyện:', data.name);
                        console.log('Mã:', data.code);
                        console.log('Số lượng phường/xã:', data.wards ? data.wards.length : 0);
                        console.log('Mẫu dữ liệu:', data.wards ? data.wards.slice(0, 3) : []);
                    }
                    
                    console.log('Toàn bộ dữ liệu:', data);
                    console.log('=== KẾT THÚC DỮ LIỆU ===\n');
                } catch (e) {
                    console.error('Lỗi khi phân tích JSON:', e);
                }
                
                return response; // Trả về response gốc
            } catch (error) {
                console.error('=== LỖI KHI GỌI API ===');
                console.error('URL:', resource);
                console.error('Lỗi:', error);
                console.error('=== KẾT THÚC LỖI ===\n');
                throw error;
            }
        }
        
        // Với các API không phải địa chỉ, sử dụng hàm fetch gốc
        return originalFetch.apply(this, arguments);
    };
    
    // Đánh dấu hàm fetch đã được ghi đè
    window.fetch._isOverridden = true;
}
</script>