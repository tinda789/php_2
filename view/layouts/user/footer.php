        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5">
        <div class="container py-5">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-4">Về ShopElectrics</h5>
                    <p>Chuyên cung cấp các sản phẩm điện thoại, laptop, phụ kiện chính hãng với giá tốt nhất thị trường.</p>
                    <div class="mt-3">
                        <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-4">Danh mục</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="/index.php?controller=product&action=index" class="text-white text-decoration-none">Điện thoại</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Laptop</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Tablet</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Phụ kiện</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Khuyến mãi</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-4">Hỗ trợ</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Hướng dẫn mua hàng</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Chính sách bảo hành</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Chính sách đổi trả</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Giao hàng & Thanh toán</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Câu hỏi thường gặp</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="text-uppercase mb-4">Liên hệ</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 Đường ABC, Quận 1, TP.HCM</li>
                        <li class="mb-2"><i class="fas fa-phone-alt me-2"></i> <a href="tel:19001000" class="text-white text-decoration-none">1900 1000</a></li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> <a href="mailto:info@shopelectrics.vn" class="text-white text-decoration-none">info@shopelectrics.vn</a></li>
                        <li><i class="fas fa-clock me-2"></i> Thứ 2 - CN: 8:00 - 22:00</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> ShopElectrics. Tất cả các quyền được bảo lưu.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <img src="/assets/images/payment-methods.png" alt="Phương thức thanh toán" class="img-fluid" style="max-height: 30px;">
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#" class="btn btn-primary btn-lg rounded-circle back-to-top" style="position: fixed; bottom: 20px; right: 20px; display: none;">
        <i class="fas fa-arrow-up"></i>
    </a>

    <!-- Custom JavaScript -->
    <script>
        // Back to top button
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $('.back-to-top').fadeIn();
            } else {
                $('.back-to-top').fadeOut();
            }
        });

        $('.back-to-top').click(function(e) {
            e.preventDefault();
            $('html, body').animate({scrollTop: 0}, '300');
            return false;
        });

        // Enable tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Enable popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 5000);
    </script>
</body>
</html>
