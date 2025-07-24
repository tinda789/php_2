<?php include __DIR__ . '/layout/header.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ với chúng tôi - Điện Tử 24h</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body { background: #f4f6fb; }
        .contact-hero {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 18px;
        }
        .contact-hero .icon {
            font-size: 2.8rem;
            color: #1976d2;
            background: linear-gradient(135deg, #4fc3f7 0%, #1976d2 100%);
            border-radius: 50%;
            width: 60px; height: 60px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 16px rgba(79,195,247,0.13);
        }
        .contact-section-modern {
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(25,118,210,0.10);
            padding: 0;
            overflow: hidden;
        }
        .contact-row { display: flex; flex-wrap: wrap; }
        .contact-info-modern {
            flex: 1 1 320px;
            background: linear-gradient(135deg, #e3f2fd 0%, #fff 100%);
            padding: 38px 32px 32px 38px;
            min-width: 280px;
            display: flex; flex-direction: column; justify-content: center;
        }
        .contact-info-modern h2 { color: #1976d2; font-size: 1.3rem; margin-bottom: 18px; font-weight: 700; }
        .contact-info-modern p, .contact-info-modern a { color: #23272f; font-size: 1.08rem; margin-bottom: 10px; }
        .contact-info-modern .info-item { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; font-size: 1.08rem; }
        .contact-info-modern .info-item i { color: #1976d2; font-size: 1.2rem; }
        .contact-info-modern .socials a { margin-right: 12px; font-size: 1.4rem; color: #1976d2; transition: color 0.18s; }
        .contact-info-modern .socials a:hover { color: #4fc3f7; }
        .contact-info-modern .business-contact { background: #f4f6fb; border-radius: 8px; padding: 12px 16px; margin-top: 18px; font-size: 1rem; }
        .contact-form-modern {
            flex: 1 1 380px;
            padding: 38px 38px 32px 32px;
            background: #fff;
            display: flex; flex-direction: column; justify-content: center;
        }
        .contact-form-modern h2 { color: #1976d2; font-size: 1.2rem; font-weight: 700; margin-bottom: 18px; }
        .contact-form-modern form { background: rgba(244,246,251,0.7); border-radius: 16px; box-shadow: 0 2px 12px rgba(79,195,247,0.08); padding: 28px 22px 18px 22px; }
        .contact-form-modern .form-floating { margin-bottom: 18px; }
        .contact-form-modern .form-control { border-radius: 10px; border: 1.5px solid #b0bec5; background: #fff; box-shadow: none; }
        .contact-form-modern .form-control:focus { border-color: #1976d2; box-shadow: 0 0 0 2px #e3f2fd; }
        .contact-form-modern label { color: #1976d2; font-weight: 500; }
        .contact-form-modern button {
            border-radius: 10px; font-weight: 600; font-size: 1.1rem; padding: 10px 0;
            background: linear-gradient(90deg, #1976d2 60%, #4fc3f7 100%);
            border: none; color: #fff;
            box-shadow: 0 2px 8px rgba(79,195,247,0.13);
            transition: background 0.18s, transform 0.12s;
            position: relative;
            overflow: hidden;
        }
        .contact-form-modern button:active { transform: scale(0.97); }
        .contact-form-modern button .fa-paper-plane { transition: margin-left 0.18s; }
        .contact-form-modern button:focus .fa-paper-plane { margin-left: 6px; }
        .contact-form-modern .alert { border-radius: 10px; font-size: 1rem; margin-bottom: 18px; }
        .info-row { margin-top: 18px; }
        .business-contact, .map-box { background: #f4f6fb; border-radius: 8px; padding: 12px 16px; font-size: 1rem; box-shadow: 0 2px 8px rgba(79,195,247,0.07); }
        .contact-subtitle-modern {
            font-size: 1.18rem;
            font-weight: 500;
            background: linear-gradient(90deg, #1976d2 0%, #4fc3f7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
            display: inline-block;
            margin-bottom: 2px;
            letter-spacing: 0.1px;
            position: relative;
        }
        .contact-subtitle-modern:after {
            content: '';
            display: block;
            width: 60%;
            height: 3px;
            background: linear-gradient(90deg, #4fc3f7 0%, #1976d2 100%);
            border-radius: 2px;
            margin: 8px 0 0 0;
        }
        @media (max-width: 900px) {
            .contact-row { flex-direction: column; margin-top: 0; }
            .contact-info-modern, .contact-form-modern { padding: 18px 4px 10px 4px; }
            .contact-hero { margin-bottom: 8px; }
            .info-row { flex-direction: column; gap: 12px; }
        }
        @media (max-width: 600px) {
            .contact-section-modern { border-radius: 0; box-shadow: none; }
            .contact-info-modern, .contact-form-modern { padding: 8px 2px 6px 2px; }
            .contact-hero { margin-bottom: 4px; }
            .contact-subtitle-modern { text-align: center; font-size: 1.05rem; }
            .contact-subtitle-modern:after { margin-left: auto; margin-right: auto; }
        }
        .fb-link {
            color: #1877f3 !important;
            font-size: 1.18rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .fb-link:hover { color: #0056b3 !important; text-decoration: underline; }
        .contact-info-modern {
            background: #e3f2fd;
            border-radius: 12px;
            font-size: 1.08rem;
            color: #23272f;
            padding: 32px 28px 24px 28px;
            margin-bottom: 0;
        }
        .contact-info-modern .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 1.08rem;
            color: #23272f;
        }
        .contact-info-modern .info-item i {
            color: #1976d2;
            font-size: 1.18rem;
            min-width: 22px;
            text-align: center;
        }
        .contact-link {
            color: #1877f3 !important;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.18s;
        }
        .contact-link:hover { color: #0056b3 !important; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="contact-section-modern">
        <div class="contact-hero">
            <div class="icon"><i class="fa-solid fa-headset"></i></div>
            <div>
                <h1 style="margin-bottom: 0.2em; color: #1976d2; font-weight: 800; font-size:2.1rem;">Liên hệ với chúng tôi</h1>
                <div class="contact-subtitle-modern">Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn! <span class="ms-2" style="color:#4fc3f7;"><i class="fa-solid fa-heart-circle-bolt"></i></span></div>
            </div>
        </div>
        <div class="contact-form-modern" style="max-width:600px;margin:0 auto 18px auto;">
            <h2>✉️ Gửi yêu cầu trực tuyến</h2>
            <?php if (isset($_GET['sent']) && $_GET['sent'] === '1'): ?>
                <div class="alert alert-success"><i class="fa fa-check-circle me-2"></i>Yêu cầu của bạn đã được gửi thành công! Chúng tôi sẽ phản hồi trong vòng 24h làm việc.</div>
            <?php endif; ?>
            <form method="post" action="#">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Họ tên" required>
                    <label for="name"><i class="fa fa-user me-2"></i>Họ tên</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email liên hệ" required>
                    <label for="email"><i class="fa fa-envelope me-2"></i>Email liên hệ</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Số điện thoại">
                    <label for="phone"><i class="fa fa-phone me-2"></i>Số điện thoại</label>
                </div>
                <div class="form-floating mb-3">
                    <textarea class="form-control" id="message" name="message" placeholder="Nội dung liên hệ" style="height: 110px; min-height: 80px;" required></textarea>
                    <label for="message"><i class="fa fa-comment-dots me-2"></i>Nội dung liên hệ</label>
                </div>
                <button type="submit" class="btn w-100"><i class="fa fa-paper-plane me-2"></i>Gửi yêu cầu</button>
            </form>
        </div>
        <div class="contact-row">
            <div class="contact-info-modern" style="margin-top:0;">
                <h2 class="mb-3" style="color:#1976d2;font-size:1.25rem;font-weight:700;"><i class="fa-solid fa-info-circle me-2"></i> Thông tin liên hệ</h2>
                <div class="row g-2">
                    <div class="col-md-4 info-item"><i class="fa-solid fa-building"></i> <span class="fw-bold" style="font-size:1.08rem;">Công ty TNHH Điện Tử 24h</span></div>
                    <div class="col-md-4 info-item"><i class="fa-solid fa-location-dot"></i> <span style="font-size:1.08rem;">Số 123, Đường Công Nghệ, Quận 1, TP. Hồ Chí Minh</span></div>
                    <div class="col-md-4 info-item" style="flex-direction:column;align-items:flex-start;gap:2px;">
                        <div><i class="fa-solid fa-phone"></i> <a href="tel:1900123456" class="contact-link" style="font-size:1.08rem;">1900 123 456</a></div>
                        <div class="text-muted" style="font-size:0.98em; margin-left:28px;">(8:00 – 20:00, tất cả các ngày)</div>
                    </div>
                    <div class="col-md-4 info-item"><i class="fa-solid fa-envelope"></i> <a href="mailto:support@dientu24h.vn" class="contact-link" style="font-size:1.08rem;">support@dientu24h.vn</a></div>
                    <div class="col-md-4 info-item"><i class="fa-solid fa-globe"></i> <a href="https://www.dientu24h.vn" target="_blank" class="contact-link" style="font-size:1.08rem;">www.dientu24h.vn</a></div>
                    <div class="col-md-4 info-item"><span class="fw-semibold" style="color:#1976d2;font-size:1.08rem;"></span> <a href="https://fb.com/dientu24h" target="_blank" class="contact-link" style="font-size:1.08rem;"><i class="fab fa-facebook"></i> dientu24h</a></div>
                </div>
                <div class="info-row d-flex flex-wrap align-items-stretch gap-3 mt-3">
                    <div class="business-contact flex-fill" style="min-width:220px;">
                        <b>🤝 Hợp tác kinh doanh</b><br>
                        Đại lý, đối tác hoặc muốn hợp tác phân phối thiết bị điện tử – gửi đề xuất đến:<br>
                        <span style="color:#1976d2;font-weight:600;">business@dientu24h.vn</span>
                    </div>
                    <div class="map-box flex-fill" style="min-width:220px;">
                        <h2 style="font-size:1.1rem;">🗺️ Bản đồ đường đi</h2>
                        <div class="map-responsive" style="margin-bottom:0;">
                            <iframe src="https://www.google.com/maps?q=10.7769,106.7009&z=15&output=embed" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/layout/footer.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html> 