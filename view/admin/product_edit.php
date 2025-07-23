<?php echo '<link rel="stylesheet" href="view/layout/product_edit.css">'; ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chỉnh sửa sản phẩm</h1>
        <a href="index.php?controller=admin&action=product_index" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Quay lại
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin sản phẩm</h6>
        </div>
        <div class="card-body">
            <?php if (isset($_GET['success'])): ?>
                <?php if ($_GET['success'] === 'image_deleted'): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> Đã xóa ảnh thành công!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
                <?php if ($_GET['error'] === 'image_not_found'): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> Không tìm thấy ảnh để xóa!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php elseif ($_GET['error'] === 'invalid_image'): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> Thông tin ảnh không hợp lệ!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <?php if (!empty($upload_error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($upload_error); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <form method="POST" action="index.php?controller=admin&action=product_update" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="name">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="short_description">Mô tả ngắn</label>
                            <textarea class="form-control" id="short_description" name="short_description" rows="3"><?php echo htmlspecialchars($product['short_description'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả chi tiết</label>
                            <textarea class="form-control" id="description" name="description" rows="6"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">Giá gốc <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="price" name="price" step="1000" min="0" 
                                           value="<?php echo $product['price']; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sale_price">Giá khuyến mãi</label>
                                    <input type="number" class="form-control" id="sale_price" name="sale_price" step="1000" min="0" 
                                           value="<?php echo $product['sale_price']; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stock_quantity">Số lượng tồn kho</label>
                                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" min="0" 
                                           value="<?php echo $product['stock_quantity']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="min_stock_level">Mức tồn kho tối thiểu</label>
                                    <input type="number" class="form-control" id="min_stock_level" name="min_stock_level" min="0" 
                                           value="<?php echo $product['min_stock_level']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="category_id">Danh mục</label>
                            <select class="form-control" id="category_id" name="category_id">
                                <option value="">Chọn danh mục</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>" 
                                            <?php echo $category['id'] == $product['category_id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="brand_id">Thương hiệu</label>
                            <select class="form-control" id="brand_id" name="brand_id">
                                <option value="">Chọn thương hiệu</option>
                                <?php foreach ($brands as $brand): ?>
                                    <option value="<?php echo $brand['id']; ?>" 
                                            <?php echo $brand['id'] == $product['brand_id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($brand['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="model">Model</label>
                            <input type="text" class="form-control" id="model" name="model" 
                                   value="<?php echo htmlspecialchars($product['model'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="sku">SKU</label>
                            <input type="text" class="form-control" id="sku" name="sku" 
                                   value="<?php echo htmlspecialchars($product['sku'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="barcode">Mã vạch</label>
                            <input type="text" class="form-control" id="barcode" name="barcode" 
                                   value="<?php echo htmlspecialchars($product['barcode'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="weight">Trọng lượng (kg)</label>
                            <input type="number" class="form-control" id="weight" name="weight" step="0.1" min="0" 
                                   value="<?php echo $product['weight']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="dimensions">Kích thước</label>
                            <input type="text" class="form-control" id="dimensions" name="dimensions" 
                                   value="<?php echo htmlspecialchars($product['dimensions'] ?? ''); ?>" placeholder="D x R x C (cm)">
                        </div>

                        <div class="form-group">
                            <label for="warranty_period">Thời gian bảo hành (tháng)</label>
                            <input type="number" class="form-control" id="warranty_period" name="warranty_period" min="0" 
                                   value="<?php echo $product['warranty_period']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="status">Trạng thái</label>
                            <select class="form-control" id="status" name="status">
                                <option value="1" <?php echo $product['status'] ? 'selected' : ''; ?>>Hoạt động</option>
                                <option value="0" <?php echo !$product['status'] ? 'selected' : ''; ?>>Ẩn</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="featured">Nổi bật</label>
                            <select class="form-control" id="featured" name="featured">
                                <option value="0" <?php echo !$product['featured'] ? 'selected' : ''; ?>>Bình thường</option>
                                <option value="1" <?php echo $product['featured'] ? 'selected' : ''; ?>>Nổi bật</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="product_images">
                                <i class="fas fa-images"></i> Thêm ảnh sản phẩm (có thể chọn nhiều)
                            </label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="product_images" name="product_images[]" multiple accept="image/*" onchange="previewImages(this)">
                                    <label class="custom-file-label" for="product_images">Chọn ảnh...</label>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Hỗ trợ: JPG, PNG, GIF, WebP - Tối đa 5MB mỗi ảnh. 
                                Có thể chọn nhiều ảnh cùng lúc.
                            </small>
                            <div id="image-preview" class="mt-2"></div>
                        </div>

                        <!-- Thêm trường nhập đường link ảnh sản phẩm -->
                        <div class="form-group">
                            <label for="image_link">
                                <i class="fas fa-link"></i> Đường link ảnh sản phẩm (tùy chọn)
                            </label>
                            <input type="text" class="form-control" id="image_link" name="image_link"
                                   value="<?php echo htmlspecialchars($product['image_link'] ?? ''); ?>"
                                   placeholder="Nhập URL ảnh sản phẩm (nếu có)">
                            <small class="form-text text-muted">
                                Nếu nhập link, ảnh này sẽ được ưu tiên hiển thị.
                            </small>
                        </div>

                        <script>
                        function previewImages(input) {
                            const preview = document.getElementById('image-preview');
                            const fileLabel = document.querySelector('.custom-file-label');
                            preview.innerHTML = '';
                            
                            // Cập nhật label hiển thị tên file
                            if (input.files && input.files.length > 0) {
                                if (input.files.length === 1) {
                                    fileLabel.textContent = input.files[0].name;
                                } else {
                                    fileLabel.textContent = `${input.files.length} ảnh đã chọn`;
                                }
                            } else {
                                fileLabel.textContent = 'Chọn ảnh...';
                            }
                            
                            if (input.files && input.files.length > 0) {
                                for (let i = 0; i < input.files.length; i++) {
                                    const file = input.files[i];
                                    
                                    // Kiểm tra kích thước file
                                    if (file.size > 5 * 1024 * 1024) {
                                        alert('File ' + file.name + ' quá lớn (tối đa 5MB)');
                                        continue;
                                    }
                                    
                                    // Kiểm tra loại file
                                    if (!file.type.match('image.*')) {
                                        alert('File ' + file.name + ' không phải là ảnh');
                                        continue;
                                    }
                                    
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        const div = document.createElement('div');
                                        div.className = 'd-inline-block mr-2 mb-2';
                                        div.innerHTML = `
                                            <div class="card" style="width: 120px;">
                                                <img src="${e.target.result}" class="card-img-top" style="height: 100px; object-fit: cover;" alt="Preview">
                                                <div class="card-body p-2">
                                                    <small class="text-muted d-block text-truncate" title="${file.name}">${file.name}</small>
                                                    <small class="text-info">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                                                </div>
                                            </div>
                                        `;
                                        preview.appendChild(div);
                                    };
                                    reader.readAsDataURL(file);
                                }
                            }
                        }
                        </script>
                    </div>
                </div>

                <!-- Hiển thị ảnh hiện tại -->
                <?php 
                $product_images = Product::getImages($conn, $product['id']);
                if (!empty($product_images)): 
                ?>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <h6>Ảnh hiện tại:</h6>
                        <div class="row">
                            <?php foreach ($product_images as $index => $image): ?>
                            <div class="col-md-2 mb-2">
                                <div class="card">
                                    <?php require_once 'helpers/image_helper.php'; ?>
                                    <img src="<?php echo htmlspecialchars(getImageUrl($image['image_url'])); ?>" class="card-img-top" alt="Product Image" style="height: 100px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <small class="text-muted">
                                            Ảnh <?php echo $index + 1; ?>
                                            <?php if ($image['is_primary']): ?>
                                                <span class="badge badge-primary">Chính</span>
                                            <?php endif; ?>
                                        </small>
                                        <div class="mt-1">
                                            <a href="index.php?controller=admin&action=delete_product_image&id=<?php echo $image['id']; ?>&product_id=<?php echo $product['id']; ?>" 
                                               class="btn btn-danger btn-sm" 
                                               onclick="return confirm('Bạn có chắc muốn xóa ảnh này?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                   value="<?php echo htmlspecialchars($product['meta_title'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea class="form-control" id="meta_description" name="meta_description" rows="3"><?php echo htmlspecialchars($product['meta_description'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-save"></i> Cập nhật sản phẩm
                            </button>
                        </div>
                        <div class="col-md-6">
                            <a href="index.php?controller=admin&action=product_index" class="btn btn-secondary btn-lg w-100">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> 
                            Sau khi cập nhật, bạn có thể thêm ảnh mới hoặc xóa ảnh cũ bằng cách sử dụng các chức năng bên dưới.
                        </small>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> 