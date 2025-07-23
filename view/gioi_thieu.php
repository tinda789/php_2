<?php include 'view/layout/header.php'; ?>
<style>
.gioi-thieu-hero {
    background: linear-gradient(90deg, #00c6ff 0%, #0072ff 100%);
    color: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(0,123,255,0.13);
    padding: 36px 18px 24px 18px;
    margin-bottom: 32px;
    position: relative;
    overflow: hidden;
}
.gioi-thieu-hero img {
    background: #fff;
    border-radius: 50%;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    padding: 10px;
    max-height: 90px;
    margin-bottom: 12px;
}
.gioi-thieu-hero h1 {
    font-size: 2.2rem;
    font-weight: bold;
    letter-spacing: 1px;
    margin-bottom: 8px;
    text-shadow: 1px 2px 8px #0056b3;
}
.gioi-thieu-section {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(0,123,255,0.07);
    margin-bottom: 28px;
    padding: 24px 28px 18px 28px;
    border-left: 6px solid #00c6ff;
    transition: box-shadow 0.2s, border-color 0.2s;
}
.gioi-thieu-section:hover {
    box-shadow: 0 4px 24px rgba(0,123,255,0.18);
    border-left: 6px solid #ffe082;
}
.gioi-thieu-section h4 {
    color: #007bff;
    font-weight: 600;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.gioi-thieu-section h4 i {
    color: #00c6ff;
    font-size: 1.3em;
}
.gioi-thieu-section ul {
    padding-left: 22px;
}
.gioi-thieu-section li {
    margin-bottom: 7px;
    font-size: 1.08rem;
}
.gioi-thieu-section blockquote {
    background: #e3f2fd;
    color: #0072ff;
    border-left: 4px solid #00c6ff;
    padding: 10px 18px;
    border-radius: 8px;
    font-style: italic;
    margin-top: 12px;
}
@media (max-width: 700px) {
    .gioi-thieu-section { padding: 12px 8px; }
    .gioi-thieu-hero { padding: 18px 6px; }
}
</style>
<div class="container my-5" style="max-width: 800px;">
    <div class="gioi-thieu-hero text-center mb-4">
        <!-- <img src="uploads/logo.png" alt="Logo Shop Điện tử"> -->
        <h1><i class="fa-solid fa-bolt"></i> Giới thiệu về <span style="color:#ffe082;">Shop Điện tử</span></h1>
        <div style="font-size:1.15rem;opacity:0.95;">Nền tảng thương mại điện tử công nghệ dành cho mọi người!</div>
    </div>
    <section class="gioi-thieu-section mb-4">
        <h4><i class="fa-solid fa-circle-info"></i> Chúng tôi là</h4>
        <p><strong>Shop Điện tử</strong> – Nền tảng thương mại điện tử chuyên cung cấp các sản phẩm công nghệ, linh kiện máy tính, điện thoại, phụ kiện và thiết bị số chính hãng. Website dành cho mọi khách hàng yêu thích công nghệ, từ sinh viên, nhân viên văn phòng đến các chuyên gia IT.</p>
    </section>
    <section class="gioi-thieu-section mb-4">
        <h4><i class="fa-solid fa-bullseye"></i> Sứ mệnh</h4>
        <p><strong>Sứ mệnh của chúng tôi:</strong> Mang đến cho khách hàng trải nghiệm mua sắm công nghệ hiện đại, an toàn, nhanh chóng với giá cả cạnh tranh và dịch vụ tận tâm.</p>
    </section>
    <section class="gioi-thieu-section mb-4">
        <h4><i class="fa-solid fa-gem"></i> Giá trị cốt lõi</h4>
        <ul>
            <li><i class="fa-solid fa-shield-halved text-info"></i> Sản phẩm chính hãng, bảo hành uy tín</li>
            <li><i class="fa-solid fa-tags text-success"></i> Giá tốt, nhiều ưu đãi</li>
            <li><i class="fa-solid fa-truck-fast text-warning"></i> Giao hàng nhanh, hỗ trợ tận nơi</li>
            <li><i class="fa-solid fa-users text-primary"></i> Cộng đồng khách hàng thân thiện, hỗ trợ kỹ thuật miễn phí</li>
            <li><i class="fa-solid fa-star text-warning"></i> Thông tin sản phẩm minh bạch, đánh giá thực tế từ người dùng</li>
        </ul>
    </section>
    <section class="gioi-thieu-section mb-4">
        <h4><i class="fa-solid fa-people-group"></i> Đội ngũ phát triển</h4>
        <ul>
            <li>Nguyễn Thành Tín</li>
            <li>Nguyễn Phương Quỳnh</li>
            <li>Lâm Thành Đạt</li>
            <li>Đoàn Xuân Thịnh</li>
        </ul>
        <blockquote class="blockquote">“Chúng tôi luôn lắng nghe và không ngừng cải tiến để phục vụ bạn tốt hơn mỗi ngày!”</blockquote>
    </section>
    <section class="gioi-thieu-section mb-4">
        <h4><i class="fa-solid fa-envelope"></i> 5. Liên hệ</h4>
        <ul>
            <li>Email: <a href="mailto:support@techshop.vn">support@techshop.vn</a></li>
            <li>Hotline: 0123 456 789</li>
            <li><a href="index.php?controller=contact">Trang liên hệ</a></li>
            <li><a href="index.php?controller=feedback">Góp ý & phản hồi</a></li>
        </ul>
    </section>
</div>
<?php include 'view/layout/footer.php'; ?> 