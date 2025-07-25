<?php
require_once __DIR__ . '/../layouts/user/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h2>Quản lý địa chỉ</h2>
            <a href="index.php?controller=user&action=profile" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Quay lại hồ sơ
            </a>
            
            <!-- Thông báo -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <!-- Nút thêm địa chỉ mới -->
            <div class="text-end mb-3">
                <button type="button" class="btn btn-primary" id="addAddressBtn">
                    <i class="fas fa-plus"></i> Thêm địa chỉ mới
                </button>
            </div>
            
            <!-- Danh sách địa chỉ -->
            <div class="row" id="addressList">
                <?php if (empty($addresses)): ?>
                    <div class="col-12">
                        <div class="alert alert-info">Bạn chưa có địa chỉ nào. Hãy thêm địa chỉ mới.</div>
                    </div>
                <?php else: ?>
                    <?php foreach ($addresses as $address): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 <?= $address['is_default'] ? 'border-primary' : '' ?>">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <?= htmlspecialchars($address['first_name'] . ' ' . $address['last_name']) ?>
                                        <?php if ($address['is_default']): ?>
                                            <span class="badge bg-primary">Mặc định</span>
                                        <?php endif; ?>
                                    </h5>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary edit-address" 
                                                data-id="<?= $address['id'] ?>"
                                                data-first-name="<?= htmlspecialchars($address['first_name']) ?>"
                                                data-last-name="<?= htmlspecialchars($address['last_name']) ?>"
                                                data-phone="<?= htmlspecialchars($address['phone']) ?>"
                                                data-address-line1="<?= htmlspecialchars($address['address_line1']) ?>"
                                                data-city="<?= htmlspecialchars($address['city']) ?>"
                                                data-district="<?= htmlspecialchars($address['district']) ?>"
                                                data-ward="<?= htmlspecialchars($address['ward']) ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="index.php?controller=address&action=delete" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')">
                                            <input type="hidden" name="id" value="<?= $address['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="mb-1">
                                        <i class="fas fa-phone"></i> <?= htmlspecialchars($address['phone']) ?>
                                    </p>
                                    <p class="mb-1">
                                        <i class="fas fa-map-marker-alt"></i> 
                                        <?= htmlspecialchars($address['address_line1']) ?>
                                        <?= !empty($address['address_line2']) ? ', ' . htmlspecialchars($address['address_line2']) : '' ?>
                                    </p>
                                    <p class="mb-0">
                                        <?= !empty($address['ward']) ? htmlspecialchars($address['ward']) . ', ' : '' ?>
                                        <?= !empty($address['district']) ? htmlspecialchars($address['district']) . ', ' : '' ?>
                                        <?= htmlspecialchars($address['city']) ?>
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <?php if (!$address['is_default']): ?>
                                        <form action="index.php?controller=address&action=setDefault" method="POST" class="d-inline">
                                            <input type="hidden" name="id" value="<?= $address['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-check"></i> Đặt làm mặc định
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm/Chỉnh sửa địa chỉ -->
<div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addressModalLabel">Thêm địa chỉ mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addressForm" onsubmit="return saveAddress(event)">
                <input type="hidden" name="id" id="addressId">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="firstName" class="form-label">Họ</label>
                                <input type="text" class="form-control" id="firstName" name="first_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lastName" class="form-label">Tên</label>
                                <input type="text" class="form-control" id="lastName" name="last_name" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="addressLine1" class="form-label">Địa chỉ (Số nhà, đường, tổ/xóm, ấp/khu phố)</label>
                        <input type="text" class="form-control" id="addressLine1" name="address_line1" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="addressLine2" class="form-label">Địa chỉ bổ sung (Tòa nhà, căn hộ, số phòng, v.v.)</label>
                        <input type="text" class="form-control" id="addressLine2" name="address_line2">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="city" class="form-label">Tỉnh/Thành phố</label>
                                <select class="form-select" id="city" name="city" required onchange="loadDistricts(this.value)">
                                    <option value="">Chọn Tỉnh/TP</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="district" class="form-label">Quận/Huyện</label>
                                <select class="form-select" id="district" name="district" required onchange="loadWards(this.value)">
                                    <option value="">Chọn Quận/Huyện</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="ward" class="form-label">Phường/Xã</label>
                                <select class="form-select" id="ward" name="ward" required>
                                    <option value="">Chọn Phường/Xã</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="isDefault" name="is_default">
                        <label class="form-check-label" for="isDefault">
                            Đặt làm địa chỉ mặc định
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu địa chỉ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Hàm gửi form địa chỉ bằng AJAX
function saveAddress(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    
    // Hiển thị loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang lưu...';
    
    // Gửi dữ liệu bằng AJAX
    fetch('index.php?controller=address&action=save', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hiển thị thông báo thành công
            showAlert('success', data.message);
            
            // Đóng modal sau 1.5 giây
            setTimeout(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('addressModal'));
                modal.hide();
                
                // Tải lại trang sau khi đóng modal
                window.location.reload();
            }, 1500);
        } else {
            // Hiển thị thông báo lỗi
            showAlert('danger', data.message || 'Có lỗi xảy ra khi lưu địa chỉ');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Có lỗi xảy ra khi kết nối đến máy chủ');
    })
    .finally(() => {
        // Khôi phục trạng thái nút
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
    });
    
    return false;
}

// Hàm hiển thị thông báo
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Thêm thông báo vào đầu trang
    const container = document.querySelector('.container.mt-4');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Tự động đóng thông báo sau 5 giây
    setTimeout(() => {
        const bsAlert = new bootstrap.Alert(alertDiv);
        bsAlert.close();
    }, 5000);
}

// Khởi tạo modal
var addressModal = new bootstrap.Modal(document.getElementById('addressModal'));

// Xử lý sự kiện khi click nút thêm địa chỉ mới
document.getElementById('addAddressBtn').addEventListener('click', function() {
    document.getElementById('addressModalLabel').textContent = 'Thêm địa chỉ mới';
    document.getElementById('addressForm').reset();
    document.getElementById('addressId').value = '';
    document.getElementById('city').innerHTML = '<option value="">Chọn Tỉnh/TP</option>';
    document.getElementById('district').innerHTML = '<option value="">Chọn Quận/Huyện</option>';
    document.getElementById('ward').innerHTML = '<option value="">Chọn Phường/Xã</option>';
    addressModal.show();
});

// Xử lý sự kiện khi click nút chỉnh sửa địa chỉ
document.querySelectorAll('.edit-address').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        const firstName = this.dataset.firstName;
        const lastName = this.dataset.lastName;
        const phone = this.dataset.phone;
        const addressLine1 = this.dataset.addressLine1;
        const city = this.dataset.city;
        const district = this.dataset.district;
        const ward = this.dataset.ward;
        
        document.getElementById('addressModalLabel').textContent = 'Chỉnh sửa địa chỉ';
        document.getElementById('addressForm').reset();
        document.getElementById('addressId').value = id;
        document.getElementById('firstName').value = firstName;
        document.getElementById('lastName').value = lastName;
        document.getElementById('phone').value = phone;
        document.getElementById('addressLine1').value = addressLine1;
        
        // Tải danh sách tỉnh/thành phố
        loadProvinces(city, district, ward);
        
        addressModal.show();
    });
});

// Tải danh sách tỉnh/thành phố
function loadProvinces(selectedCity = '', selectedDistrict = '', selectedWard = '') {
    fetch('index.php?controller=address&action=getProvinces')
        .then(response => response.json())
        .then(provinces => {
            const citySelect = document.getElementById('city');
            citySelect.innerHTML = '<option value="">Chọn Tỉnh/TP</option>';
            
            provinces.forEach(province => {
                const option = document.createElement('option');
                option.value = province.code;
                option.textContent = province.name;
                option.selected = (province.name === selectedCity);
                citySelect.appendChild(option);
            });
            
            if (selectedCity) {
                loadDistricts(citySelect.value, selectedDistrict, selectedWard);
            }
        });
}

// Tải danh sách quận/huyện
function loadDistricts(provinceCode, selectedDistrict = '', selectedWard = '') {
    if (!provinceCode) {
        document.getElementById('district').innerHTML = '<option value="">Chọn Quận/Huyện</option>';
        document.getElementById('ward').innerHTML = '<option value="">Chọn Phường/Xã</option>';
        return;
    }
    
    fetch(`index.php?controller=address&action=getDistricts&code=${provinceCode}`)
        .then(response => response.json())
        .then(data => {
            const districtSelect = document.getElementById('district');
            districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
            
            if (data.districts) {
                data.districts.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.code;
                    option.textContent = district.name;
                    option.selected = (district.name === selectedDistrict);
                    districtSelect.appendChild(option);
                });
                
                if (selectedDistrict) {
                    loadWards(districtSelect.value, selectedWard);
                }
            }
        });
}

// Tải danh sách phường/xã
function loadWards(districtCode, selectedWard = '') {
    if (!districtCode) {
        document.getElementById('ward').innerHTML = '<option value="">Chọn Phường/Xã</option>';
        return;
    }
    
    fetch(`index.php?controller=address&action=getWards&code=${districtCode}`)
        .then(response => response.json())
        .then(data => {
            const wardSelect = document.getElementById('ward');
            wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
            
            if (data.wards) {
                data.wards.forEach(ward => {
                    const option = document.createElement('option');
                    option.value = ward.code;
                    option.textContent = ward.name;
                    option.selected = (ward.name === selectedWard);
                    wardSelect.appendChild(option);
                });
            }
        });
}

// Tải danh sách tỉnh/thành phố khi trang được tải
document.addEventListener('DOMContentLoaded', function() {
    // Kiểm tra nếu có dữ liệu địa chỉ trong form
    const editButtons = document.querySelectorAll('.edit-address');
    if (editButtons.length === 0) {
        loadProvinces();
    }
});
</script>

<?php
require_once __DIR__ . '/../layouts/user/footer.php';
?>
