    </div> <!-- Đóng container từ header -->
    <footer class="site-footer bg-dark text-light py-4 mt-5" style="background: linear-gradient(90deg, #232526 60%, #414345 100%); border-top: 3px solid #007bff;">
      <div class="container-fluid px-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-2 mb-2 mb-md-0" style="font-size:1.08rem;">
          <i class="fa-solid fa-bolt text-info"></i>
          <span>&copy; 2025 <b>Shop Điện Tử</b>. All rights reserved.</span>
        </div>
        <div style="font-size:1.08rem;">
          <i class="fa-solid fa-envelope text-info me-1"></i>
          <a href="mailto:info@shopelectrics.vn" class="text-info text-decoration-underline">info@shopelectrics.vn</a>
          <span class="mx-2">|</span>
          <i class="fa-solid fa-phone text-info me-1"></i>0123 456 789
        </div>
      </div>
    </footer>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    
    <!-- Initialize Bootstrap components -->
    <script>
    // Hàm khởi tạo dropdown đơn giản
    function initSimpleDropdowns() {
        document.querySelectorAll('.dropdown-toggle').forEach(function(toggle) {
            // Xóa các sự kiện click cũ
            toggle.removeEventListener('click', handleDropdownClick);
            // Thêm sự kiện click mới
            toggle.addEventListener('click', handleDropdownClick);
        });
    }
    
    // Xử lý sự kiện click cho dropdown
    function handleDropdownClick(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Lấy menu tương ứng
        const dropdownMenu = this.nextElementSibling;
        if (!dropdownMenu || !dropdownMenu.classList.contains('dropdown-menu')) return;
        
        // Đóng tất cả các dropdown khác
        document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
            if (menu !== dropdownMenu) {
                menu.classList.remove('show');
                menu.previousElementSibling?.setAttribute('aria-expanded', 'false');
            }
        });
        
        // Toggle menu hiện tại
        const isShown = dropdownMenu.classList.toggle('show');
        this.setAttribute('aria-expanded', isShown ? 'true' : 'false');
    }
    
    // Đóng dropdown khi click bên ngoài
    document.addEventListener('click', function(e) {
        if (!e.target.matches('.dropdown-toggle')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                menu.classList.remove('show');
                const toggle = menu.previousElementSibling;
                if (toggle && toggle.matches('.dropdown-toggle')) {
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });
        }
    });
    
    // Khởi tạo khi trang tải xong
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSimpleDropdowns);
    } else {
        initSimpleDropdowns();
    }
    
    // Khởi tạo lại khi có sự kiện load
    window.addEventListener('load', initSimpleDropdowns);
    
    // Khởi tạo lại khi có sự kiện ajaxComplete (nếu có sử dụng AJAX)
    if (typeof jQuery !== 'undefined') {
        $(document).ajaxComplete(initSimpleDropdowns);
    }
    
    // Khởi tạo tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
        try {
            new bootstrap.Tooltip(el);
        } catch (e) {
            console.error('Lỗi khi khởi tạo tooltip:', e);
        }
    });
    </script>
    <!-- Sticky footer CSS -->
    <style>
    html, body { 
        height: 100%; 
        margin: 0; 
        padding: 0; 
    }
    body { 
        min-height: 100vh; 
        display: flex; 
        flex-direction: column; 
    }
    .site-footer { 
        flex-shrink: 0; 
        margin-top: auto;
        position: relative;
        z-index: 100;
    }
    .main-content { 
        flex: 1 0 auto; 
    }
    .container {
        min-height: calc(100vh - 200px); /* Đảm bảo container có chiều cao tối thiểu */
    }
    </style>
    </body>
    </html> 