<?php include 'view/layout/header.php'; ?>
<?php if (empty($_SESSION['user'])): ?>
    <div class="profile-section">
        <div class="profile-card">
            <div class="profile-error">Bạn chưa đăng nhập!</div>
        </div>
    </div>
<?php else: ?>
    <div class="profile-section">
        <div class="profile-card">
            <h2 class="profile-title">Sửa thông tin cá nhân</h2>
            <?php if (!empty($error)) echo "<div class='profile-error'>$error</div>"; ?>
            <?php if (!empty($success)) echo "<div class='profile-success'>$success</div>"; ?>
            
            <form method="post" action="?controller=user&action=edit" class="profile-form" enctype="multipart/form-data">
                <!-- Avatar Upload Section -->
                <div class="avatar-upload-section">
                    <label class="section-label">Ảnh đại diện</label>
                    <div class="avatar-container">
                        <div class="avatar-preview" id="avatarPreview">
                            <?php if (!empty($user['avatar'])): ?>
                                <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar hiện tại" class="current-avatar" id="currentAvatar">
                            <?php else: ?>
                                <div class="avatar-placeholder">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            <?php endif; ?>
                            <div class="avatar-overlay">
                                <i class="fa-solid fa-camera"></i>
                                <span>Thay đổi ảnh</span>
                            </div>
                        </div>
                        <input type="file" name="avatar" id="avatarInput" accept="image/*" class="hidden-input">
                        <input type="hidden" name="cropped_avatar" id="croppedAvatarInput">
                        <div class="avatar-info">
                            <p class="avatar-hint">Kéo thả ảnh vào đây hoặc click để chọn</p>
                            <p class="avatar-requirements">Định dạng: JPG, PNG, GIF • Tối đa: 2MB</p>
                        </div>
                        <div class="avatar-actions">
                            <button type="button" class="btn-remove-avatar" id="removeAvatar" <?php echo empty($user['avatar']) ? 'style="display:none;"' : ''; ?>>
                                <i class="fa-solid fa-trash"></i> Xóa ảnh
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Form Fields -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">Họ</label>
                        <input type="text" name="first_name" id="first_name" required value="<?php echo htmlspecialchars($user['first_name']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Tên</label>
                        <input type="text" name="last_name" id="last_name" required value="<?php echo htmlspecialchars($user['last_name']); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <input type="text" name="phone" id="phone" required value="<?php echo htmlspecialchars($user['phone']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($user['email']); ?>">
                </div>
                
                <button type="submit" class="profile-edit-btn">
                    <i class="fa-solid fa-save"></i> Cập nhật thông tin
                </button>
            </form>
            
            <a href="?controller=user" class="profile-back-link">
                <i class="fa-solid fa-arrow-left"></i> Quay lại thông tin cá nhân
            </a>
        </div>
    </div>

    <!-- Crop Modal -->
    <div id="cropModal" class="crop-modal">
        <div class="crop-modal-content">
            <div class="crop-modal-header">
                <h3>Cắt ảnh đại diện</h3>
                <button type="button" class="crop-modal-close" id="closeCropModal">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <div class="crop-modal-body">
                <div class="crop-container">
                    <img id="cropImage" src="" alt="Crop image">
                </div>
                <div class="crop-controls">
                    <div class="crop-info">
                        <p>Kéo để di chuyển, scroll để zoom</p>
                        <p>Ảnh sẽ được cắt thành hình tròn</p>
                    </div>
                    <div class="crop-buttons">
                        <button type="button" class="btn-crop-rotate" id="rotateLeft">
                            <i class="fa-solid fa-rotate-left"></i> Xoay trái
                        </button>
                        <button type="button" class="btn-crop-rotate" id="rotateRight">
                            <i class="fa-solid fa-rotate-right"></i> Xoay phải
                        </button>
                        <button type="button" class="btn-crop-reset" id="resetCrop">
                            <i class="fa-solid fa-undo"></i> Làm lại
                        </button>
                    </div>
                    <div class="crop-zoom-control" style="margin: 16px 0; text-align: center; display: flex; align-items: center; justify-content: center; gap: 12px;">
                        <button type="button" id="zoomOut" style="font-size: 1.5rem; width: 32px; height: 32px; border-radius: 50%; border: none; background: #f0f0f0; cursor: pointer;">-</button>
                        <input type="range" id="cropZoom" min="0.5" max="3" step="0.01" value="1" style="width: 180px; vertical-align: middle;">
                        <button type="button" id="zoomIn" style="font-size: 1.5rem; width: 32px; height: 32px; border-radius: 50%; border: none; background: #f0f0f0; cursor: pointer;">+</button>
                    </div>
                </div>
            </div>
            <div class="crop-modal-footer">
                <button type="button" class="btn-crop-cancel" id="cancelCrop">Hủy</button>
                <button type="button" class="btn-crop-apply" id="applyCrop">
                    <i class="fa-solid fa-check"></i> Áp dụng
                </button>
            </div>
        </div>
    </div>

    <!-- Cropper.js CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">

    <style>
    .profile-section { 
        display: flex; 
        justify-content: center; 
        align-items: flex-start; 
        min-height: 60vh; 
        background: #f4f6fb; 
        padding: 20px 0;
    }
    
    .profile-card { 
        background: #fff; 
        border-radius: 16px; 
        box-shadow: 0 4px 20px rgba(0,0,0,0.1); 
        padding: 40px; 
        max-width: 500px; 
        width: 100%; 
        margin: 20px 0; 
    }
    
    .profile-title { 
        color: #007bff; 
        margin-bottom: 30px; 
        font-size: 1.8rem; 
        text-align: center;
        font-weight: 600;
    }
    
    .section-label {
        display: block;
        margin-bottom: 15px;
        font-weight: 600;
        color: #333;
        font-size: 1.1rem;
    }
    
    /* Avatar Upload Styles */
    .avatar-upload-section {
        margin-bottom: 30px;
        text-align: center;
    }
    
    .avatar-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }
    
    .avatar-preview {
        position: relative;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        cursor: pointer;
        border: 3px solid #e3f2fd;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }
    
    .avatar-preview:hover {
        border-color: #007bff;
        transform: scale(1.05);
    }
    
    .avatar-preview.dragover {
        border-color: #007bff;
        background: #e3f2fd;
    }
    
    .current-avatar {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .avatar-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        color: #adb5bd;
        font-size: 3rem;
    }
    
    .avatar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 123, 255, 0.8);
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        font-size: 0.9rem;
    }
    
    .avatar-preview:hover .avatar-overlay {
        opacity: 1;
    }
    
    .avatar-overlay i {
        font-size: 1.5rem;
        margin-bottom: 5px;
    }
    
    .hidden-input {
        display: none;
    }
    
    .avatar-info {
        text-align: center;
    }
    
    .avatar-hint {
        color: #007bff;
        font-weight: 500;
        margin-bottom: 5px;
        font-size: 0.95rem;
    }
    
    .avatar-requirements {
        color: #6c757d;
        font-size: 0.85rem;
        margin: 0;
    }
    
    .avatar-actions {
        display: flex;
        gap: 10px;
    }
    
    .btn-remove-avatar {
        background: #dc3545;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: background 0.3s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .btn-remove-avatar:hover {
        background: #c82333;
    }
    
    /* Form Styles */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }
    
    .form-group input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }
    
    .form-group input:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }
    
    .profile-edit-btn {
        width: 100%;
        padding: 14px 0;
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .profile-edit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }
    
    .profile-back-link {
        display: block;
        text-align: center;
        color: #007bff;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }
    
    .profile-back-link:hover {
        color: #0056b3;
    }
    
    .profile-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 20px;
        text-align: center;
        font-weight: 500;
    }
    
    .profile-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 20px;
        text-align: center;
        font-weight: 500;
    }
    
    /* Crop Modal Styles */
    .crop-modal {
        display: none;
        position: fixed;
        z-index: 99999 !important;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(5px);
    }
    
    .crop-modal.show {
        display: block !important;
    }
    
    .crop-modal-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        display: flex;
        flex-direction: column;
    }
    
    .crop-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        border-bottom: 1px solid #e9ecef;
        background: #f8f9fa;
    }
    
    .crop-modal-header h3 {
        margin: 0;
        color: #333;
        font-size: 1.3rem;
        font-weight: 600;
    }
    
    .crop-modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #6c757d;
        cursor: pointer;
        padding: 5px;
        border-radius: 50%;
        transition: all 0.3s ease;
    }
    
    .crop-modal-close:hover {
        background: #e9ecef;
        color: #333;
    }
    
    .crop-modal-body {
        padding: 24px;
        flex: 1 1 auto;
        overflow-y: auto;
        min-height: 0;
    }
    
    .crop-container {
        width: 100%;
        max-height: 60vh;
        min-height: 200px;
        background: #f8f9fa;
        border-radius: 8px;
        overflow-y: auto;
        overflow-x: hidden;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .crop-container img {
        max-width: 100%;
        max-height: 100%;
        display: block;
    }
    
    .crop-controls {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .crop-info {
        text-align: center;
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    .crop-info p {
        margin: 5px 0;
    }
    
    .crop-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .btn-crop-rotate, .btn-crop-reset {
        background: #6c757d;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: background 0.3s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .btn-crop-rotate:hover, .btn-crop-reset:hover {
        background: #5a6268;
    }
    
    .crop-modal-footer {
        display: flex !important;
        justify-content: flex-end;
        gap: 10px;
        padding: 20px 24px;
        border-top: 1px solid #e9ecef;
        background: #f8f9fa;
        position: sticky;
        bottom: 0;
        z-index: 2;
    }
    
    .btn-crop-cancel {
        background: #6c757d;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.3s ease;
    }
    
    .btn-crop-cancel:hover {
        background: #5a6268;
    }
    
    .btn-crop-apply {
        background: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.3s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .btn-crop-apply:hover {
        background: #0056b3;
    }
    
    /* Cropper.js Custom Styles */
    .cropper-view-box,
    .cropper-face {
        border-radius: 50%;
    }
    
    .cropper-view-box {
        outline: 2px solid #007bff;
        outline-color: rgba(0, 123, 255, 0.8);
    }
    
    .cropper-face {
        background-color: inherit !important;
    }
    
    /* Responsive */
    @media (max-width: 600px) {
        .profile-card {
            padding: 20px;
            margin: 10px;
        }
        
        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }
        
        .avatar-preview {
            width: 100px;
            height: 100px;
        }
        
        .crop-modal-content {
            width: 98%;
            max-width: 98vw;
            max-height: 98vh;
        }
        
        .crop-modal-body {
            padding: 10px;
        }
        
        .crop-container {
            height: 300px;
        }
        
        .crop-buttons {
            flex-direction: column;
        }
        
        .crop-modal-footer {
            flex-direction: column;
        }
    }
    </style>

    <!-- Cropper.js Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
    // Fallback if Cropper.js fails to load
    if (typeof Cropper === 'undefined') {
        console.error('Cropper.js not loaded! Loading from alternative source...');
        const script = document.createElement('script');
        script.src = 'https://unpkg.com/cropperjs@1.5.12/dist/cropper.min.js';
        document.head.appendChild(script);
    }
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const avatarPreview = document.getElementById('avatarPreview');
        const avatarInput = document.getElementById('avatarInput');
        const croppedAvatarInput = document.getElementById('croppedAvatarInput');
        const removeAvatarBtn = document.getElementById('removeAvatar');
        const currentAvatar = document.getElementById('currentAvatar');
        
        // Crop modal elements
        const cropModal = document.getElementById('cropModal');
        const cropImage = document.getElementById('cropImage');
        const closeCropModal = document.getElementById('closeCropModal');
        const cancelCrop = document.getElementById('cancelCrop');
        const applyCrop = document.getElementById('applyCrop');
        const rotateLeft = document.getElementById('rotateLeft');
        const rotateRight = document.getElementById('rotateRight');
        const resetCrop = document.getElementById('resetCrop');
        const cropZoom = document.getElementById('cropZoom');
        const zoomIn = document.getElementById('zoomIn');
        const zoomOut = document.getElementById('zoomOut');
        
        let cropper = null;
        let originalFile = null;
        
        // Debug: Kiểm tra các element có tồn tại không
        console.log('Avatar elements:', {
            avatarPreview: !!avatarPreview,
            avatarInput: !!avatarInput,
            cropModal: !!cropModal,
            cropImage: !!cropImage
        });
        
        // Click to upload
        avatarPreview.addEventListener('click', function() {
            console.log('Avatar preview clicked');
            avatarInput.click();
        });
        
        // Drag and drop functionality
        avatarPreview.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });
        
        avatarPreview.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });
        
        avatarPreview.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                console.log('File dropped:', files[0].name);
                handleFile(files[0]);
            }
        });
        
        // File input change
        avatarInput.addEventListener('change', function(e) {
            console.log('File input changed');
            if (e.target.files.length > 0) {
                console.log('File selected:', e.target.files[0].name);
                handleFile(e.target.files[0]);
            }
        });
        
        // Remove avatar
        removeAvatarBtn.addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa ảnh đại diện?')) {
                // Clear the preview
                const placeholder = document.createElement('div');
                placeholder.className = 'avatar-placeholder';
                placeholder.innerHTML = '<i class="fa-solid fa-user"></i>';
                
                avatarPreview.innerHTML = '';
                avatarPreview.appendChild(placeholder);
                
                // Hide remove button
                removeAvatarBtn.style.display = 'none';
                
                // Clear file input
                avatarInput.value = '';
                croppedAvatarInput.value = '';
                
                // Add a hidden input to indicate avatar removal
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'remove_avatar';
                hiddenInput.value = '1';
                avatarInput.parentNode.appendChild(hiddenInput);
            }
        });
        
        // Modal controls
        closeCropModal.addEventListener('click', closeModal);
        cancelCrop.addEventListener('click', closeModal);
        
        applyCrop.addEventListener('click', function() {
            console.log('Apply crop clicked');
            if (cropper) {
                const size = 300;
                const squareCanvas = cropper.getCroppedCanvas({
                    width: size,
                    height: size,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high'
                });
                
                // Tạo canvas tròn nền trong suốt
                const circleCanvas = document.createElement('canvas');
                circleCanvas.width = size;
                circleCanvas.height = size;
                const ctx = circleCanvas.getContext('2d');

                // Vẽ vùng tròn
                ctx.clearRect(0, 0, size, size);
                ctx.save();
                ctx.beginPath();
                ctx.arc(size/2, size/2, size/2, 0, 2 * Math.PI, false);
                ctx.closePath();
                ctx.clip();

                // Vẽ ảnh vuông lên vùng tròn
                ctx.drawImage(squareCanvas, 0, 0, size, size);
                ctx.restore();

                // Xuất PNG nền trong suốt
                circleCanvas.toBlob(function(blob) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'current-avatar';
                        img.alt = 'Avatar preview';
                        
                        avatarPreview.innerHTML = '';
                        avatarPreview.appendChild(img);
                        
                        removeAvatarBtn.style.display = 'flex';
                        croppedAvatarInput.value = e.target.result; // base64 PNG hình tròn
                    };
                    reader.readAsDataURL(blob);
                    closeModal();
                }, 'image/png');
            }
        });
        
        rotateLeft.addEventListener('click', function() {
            if (cropper) {
                cropper.rotate(-90);
            }
        });
        
        rotateRight.addEventListener('click', function() {
            if (cropper) {
                cropper.rotate(90);
            }
        });
        
        resetCrop.addEventListener('click', function() {
            if (cropper) {
                cropper.reset();
            }
        });
        
        // Zoom slider event
        if (cropZoom) {
            cropZoom.addEventListener('input', function() {
                if (cropper) {
                    cropper.zoomTo(parseFloat(this.value));
                }
            });
        }
        
        // Zoom in/out button events
        if (zoomIn && cropZoom) {
            zoomIn.addEventListener('click', function() {
                let val = parseFloat(cropZoom.value);
                if (val < 3) {
                    val = Math.min(3, val + 0.05);
                    cropZoom.value = val;
                    if (cropper) cropper.zoomTo(val);
                }
            });
        }
        if (zoomOut && cropZoom) {
            zoomOut.addEventListener('click', function() {
                let val = parseFloat(cropZoom.value);
                if (val > 0.5) {
                    val = Math.max(0.5, val - 0.05);
                    cropZoom.value = val;
                    if (cropper) cropper.zoomTo(val);
                }
            });
        }
        
        function handleFile(file) {
            console.log('Handling file:', file.name, file.type, file.size);
            
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('Chỉ chấp nhận file JPG, PNG, GIF!');
                return;
            }
            
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('File quá lớn! Tối đa 2MB.');
                return;
            }
            
            // Store original file
            originalFile = file;
            
            // Show crop modal
            const reader = new FileReader();
            reader.onload = function(e) {
                console.log('File loaded, showing modal');
                if (cropZoom) cropZoom.value = 1;
                cropImage.src = e.target.result;
                cropModal.style.display = 'block';
                cropModal.classList.add('show');
                
                // Check if Cropper.js is available
                if (typeof Cropper !== 'undefined') {
                    // Initialize cropper after a short delay to ensure image is loaded
                    setTimeout(function() {
                        if (cropper) {
                            cropper.destroy();
                        }
                        
                        console.log('Initializing cropper');
                        cropper = new Cropper(cropImage, {
                            aspectRatio: 1,
                            viewMode: 1,
                            dragMode: 'move',
                            autoCropArea: 0.8,
                            restore: false,
                            guides: true,
                            center: true,
                            highlight: false,
                            cropBoxMovable: true,
                            cropBoxResizable: true,
                            toggleDragModeOnDblclick: false,
                            ready: function() {
                                console.log('Cropper ready');
                                // Auto crop to circle
                                this.crop();
                            }
                        });
                    }, 100);
                } else {
                    console.error('Cropper.js not available, showing simple preview');
                    // Fallback: show simple preview without cropping
                    cropImage.style.maxWidth = '100%';
                    cropImage.style.maxHeight = '100%';
                    
                    // Add a message
                    const cropInfo = document.querySelector('.crop-info');
                    if (cropInfo) {
                        cropInfo.innerHTML = '<p style="color: #dc3545;">⚠️ Cropper.js không khả dụng. Ảnh sẽ được sử dụng nguyên bản.</p>';
                    }
                }
            };
            reader.readAsDataURL(file);
        }
        
        function closeModal() {
            console.log('Closing modal');
            cropModal.style.display = 'none';
            cropModal.classList.remove('show');
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            // Clear file input
            avatarInput.value = '';
        }
        
        // Close modal when clicking outside
        cropModal.addEventListener('click', function(e) {
            if (e.target === cropModal) {
                closeModal();
            }
        });
        
        // Form validation
        const form = document.querySelector('.profile-form');
        form.addEventListener('submit', function(e) {
            const firstName = document.getElementById('first_name').value.trim();
            const lastName = document.getElementById('last_name').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const email = document.getElementById('email').value.trim();
            
            if (!firstName || !lastName) {
                e.preventDefault();
                alert('Vui lòng nhập đầy đủ họ và tên!');
                return;
            }
            
            if (!phone.match(/^[0-9]{8,15}$/)) {
                e.preventDefault();
                alert('Số điện thoại không hợp lệ!');
                return;
            }
            
            if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                e.preventDefault();
                alert('Email không hợp lệ!');
                return;
            }
        });
        
        // Test modal display
        console.log('Script loaded successfully');
        
        // Test modal button
        const testModalBtn = document.getElementById('testModal');
        if (testModalBtn) {
            testModalBtn.addEventListener('click', function() {
                console.log('Test modal button clicked');
                cropModal.style.display = 'block';
                cropModal.classList.add('show');
                
                // Create a test image
                const testImg = document.createElement('img');
                testImg.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMzAwIiBoZWlnaHQ9IjMwMCIgZmlsbD0iIzAwN2JmZiIvPjx0ZXh0IHg9IjE1MCIgeT0iMTUwIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMjQiIGZpbGw9IndoaXRlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+VGVzdCBJbWFnZTwvdGV4dD48L3N2Zz4=';
                testImg.style.width = '100%';
                testImg.style.height = '100%';
                
                cropImage.innerHTML = '';
                cropImage.appendChild(testImg);
                
                // Show simple message
                const cropInfo = document.querySelector('.crop-info');
                if (cropInfo) {
                    cropInfo.innerHTML = '<p>✅ Modal hiện lên thành công!</p><p>Đây là ảnh test để kiểm tra modal.</p>';
                }
            });
        }
    });
    </script>
<?php endif; ?>
<?php include 'view/layout/footer.php'; ?> 