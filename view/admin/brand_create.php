<?php echo '<link rel="stylesheet" href="view/layout/brand_create.css">'; ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Thêm thương hiệu mới</h1>
        <a href="index.php?controller=admin&action=brand_index" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Quay lại
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin thương hiệu</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="index.php?controller=brand&action=create" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Tên thương hiệu <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="description">Mô tả</label>
                    <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label for="logo">Logo</label>
                    <input type="file" class="form-control" id="logo" name="logo">
                </div>

                <div class="form-group">
                    <label for="website">Website</label>
                    <input type="text" class="form-control" id="website" name="website">
                </div>

                <div class="form-group">
                    <label for="founded_date">Ngày thành lập</label>
                    <input type="date" class="form-control" id="founded_date" name="founded_date">
                </div>

                <div class="form-group">
                    <label for="is_active">Trạng thái</label>
                    <select class="form-control" id="is_active" name="is_active">
                        <option value="1" selected>Hoạt động</option>
                        <option value="0">Ẩn</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus"></i> Thêm thương hiệu
                    </button>
                    <a href="index.php?controller=admin&action=brand_index" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
