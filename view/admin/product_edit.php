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
                            <label for="product_images">Thêm ảnh sản phẩm (có thể chọn nhiều)</label>
                            <input type="file" class="form-control" id="product_images" name="product_images[]" multiple accept="image/*" onchange="previewImages(this)">
                            <small class="form-text text-muted">Chọn ảnh mới để thêm vào sản phẩm (JPG, PNG, GIF, WebP) - Tối đa 5MB mỗi ảnh</small>
                            <div id="image-preview" class="mt-2"></div>
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

                <!-- Hiển thị ảnh hiện tại -->
                <?php 
                $product_images = Product::getImages($conn, $product['id']);
                if (!empty($product_images)): 
                ?>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <h6>Ảnh hiện tại:</h6>
                        <div class="row">
                            <?php foreach ($product_images as $index => $image_url): ?>
                            <div class="col-md-2 mb-2">
                                <div class="card">
                                    <img src="<?php echo htmlspecialchars($image_url); ?>" class="card-img-top" alt="Product Image" style="height: 100px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <small class="text-muted">Ảnh <?php echo $index + 1; ?></small>
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
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập nhật sản phẩm
                    </button>
                    <a href="index.php?controller=admin&action=product_index" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div> 