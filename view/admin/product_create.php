<?php echo '<link rel="stylesheet" href="view/layout/product_create.css">'; ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Thêm sản phẩm mới</h1>
        <a href="index.php?controller=admin&action=product_index" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Quay lại
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin sản phẩm</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="index.php?controller=product&action=store" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="name">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($_SESSION['old_input']['name']) ? htmlspecialchars($_SESSION['old_input']['name']) : ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="short_description">Mô tả ngắn</label>
                            <textarea class="form-control" id="short_description" name="short_description" rows="3"><?php echo isset($_SESSION['old_input']['short_description']) ? htmlspecialchars($_SESSION['old_input']['short_description']) : ''; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả chi tiết</label>
                            <textarea class="form-control" id="description" name="description" rows="6"><?php echo isset($_SESSION['old_input']['description']) ? htmlspecialchars($_SESSION['old_input']['description']) : ''; ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">Giá gốc <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="price" name="price" step="1000" min="0" value="<?php echo isset($_SESSION['old_input']['price']) ? htmlspecialchars($_SESSION['old_input']['price']) : ''; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sale_price">Giá khuyến mãi</label>
                                    <input type="number" class="form-control" id="sale_price" name="sale_price" step="1000" min="0" value="<?php echo isset($_SESSION['old_input']['sale_price']) ? htmlspecialchars($_SESSION['old_input']['sale_price']) : ''; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stock">Số lượng tồn kho</label>
                                    <input type="number" class="form-control" id="stock" name="stock" min="0" value="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="min_stock_level">Mức tồn kho tối thiểu</label>
                                    <input type="number" class="form-control" id="min_stock_level" name="min_stock_level" min="0" value="0">
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
                                    <option value="<?php echo $category['id']; ?>">
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
                                    <option value="<?php echo $brand['id']; ?>">
                                        <?php echo htmlspecialchars($brand['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="model">Model</label>
                            <input type="text" class="form-control" id="model" name="model">
                        </div>

                        <div class="form-group">
                            <label for="sku">SKU</label>
                            <input type="text" class="form-control" id="sku" name="sku">
                        </div>

                        <div class="form-group">
                            <label for="barcode">Mã vạch</label>
                            <input type="text" class="form-control" id="barcode" name="barcode">
                        </div>

                        <div class="form-group">
                            <label for="weight">Trọng lượng (kg)</label>
                            <input type="number" class="form-control" id="weight" name="weight" step="0.1" min="0">
                        </div>

                        <div class="form-group">
                            <label for="dimensions">Kích thước</label>
                            <input type="text" class="form-control" id="dimensions" name="dimensions" placeholder="D x R x C (cm)">
                        </div>

                        <div class="form-group">
                            <label for="warranty_period">Thời gian bảo hành (tháng)</label>
                            <input type="number" class="form-control" id="warranty_period" name="warranty_period" min="0" value="12">
                        </div>

                        <div class="form-group">
                            <label for="status">Trạng thái</label>
                            <select class="form-control" id="status" name="status">
                                <option value="1">Hoạt động</option>
                                <option value="0">Ẩn</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="featured">Nổi bật</label>
                            <select class="form-control" id="featured" name="featured">
                                <option value="0">Bình thường</option>
                                <option value="1">Nổi bật</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="product_images">Ảnh sản phẩm (có thể chọn nhiều)</label>
                            <input type="file" class="form-control" id="product_images" name="product_images[]" multiple accept="image/*" onchange="previewImages(this)">
                            <small class="form-text text-muted">Chọn ảnh (JPG, PNG, GIF, WebP) - Tối đa 5MB mỗi ảnh</small>
                            <div id="image-preview" class="mt-2"></div>
                        </div>

                        <!-- Thêm trường nhập đường link ảnh sản phẩm -->
                        <div class="form-group">
                            <label for="image_link">
                                <i class="fas fa-link"></i> Đường link ảnh sản phẩm (tùy chọn)
                            </label>
                            <input type="text" class="form-control" id="image_link" name="image_link"
                                   placeholder="Nhập URL ảnh sản phẩm (nếu có)">
                            <small class="form-text text-muted">
                                Nếu nhập link, ảnh này sẽ được ưu tiên hiển thị.
                            </small>
                        </div>

                        <script>
                        function previewImages(input) {
                            const preview = document.getElementById('image-preview');
                            preview.innerHTML = '';
                            
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
                                            <img src="${e.target.result}" style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;" alt="Preview">
                                            <div class="text-center mt-1">
                                                <small class="text-muted">${file.name}</small>
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

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title">
                        </div>

                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea class="form-control" id="meta_description" name="meta_description" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu sản phẩm
                    </button>
                    <a href="index.php?controller=admin&action=product_index" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div> 