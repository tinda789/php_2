<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once 'config/config.php';
require_once 'model/User.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$action = $_GET['action'] ?? '';

if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $phone = trim($_POST['phone']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $error = '';
    if (User::register($conn, $username, $password, $email, $confirm_password, $first_name, $last_name, $phone, $error)) {
        // Gửi email báo đăng ký thành công
        $mail1 = new PHPMailer(true);
        try {
            $mail1->isSMTP();
            $mail1->Host = 'smtp.gmail.com';
            $mail1->SMTPAuth = true;
            $mail1->Username = 'thinhb2303782@student.ctu.edu.vn';
            $mail1->Password = 'wuge yhnn xpae fcte';
            $mail1->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail1->Port = 587;
            $mail1->setFrom('thinhb2303782@student.ctu.edu.vn', 'Shop Điện Tử');
            $mail1->addAddress($email);
            $mail1->isHTML(true);
            $mail1->Subject = 'Đăng ký tài khoản thành công';
            $mail1->Body    = '<p>Chào $first_name $last_name,</p><p>Bạn đã đăng ký tài khoản thành công tại Shop Điện Tử.</p><p>Vui lòng kiểm tra email để kích hoạt tài khoản trước khi đăng nhập.</p>';
            $mail1->send();
        } catch (Exception $e) {}
        // Tạo token active
        $token = bin2hex(random_bytes(32));
        $stmt = $conn->prepare("UPDATE users SET reset_token=? WHERE email=?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();
        // Gửi email xác nhận đăng ký
        $link = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php?controller=auth&action=active_account&token=$token";
        $subject = "Kích hoạt tài khoản Shop Điện Tử";
        $message = "<p>Chào $first_name $last_name,</p>"
                 . "<p>Cảm ơn bạn đã đăng ký tài khoản tại shop của chúng tôi!</p>"
                 . "<p>Vui lòng nhấn vào link sau để kích hoạt tài khoản:<br>"
                 . "<a href='$link'>$link</a></p>"
                 . "<p>Nếu bạn không đăng ký, hãy bỏ qua email này.</p>";
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'thinhb2303782@student.ctu.edu.vn';
            $mail->Password = 'wuge yhnn xpae fcte';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('thinhb2303782@student.ctu.edu.vn', 'Shop Điện Tử');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;
            $mail->send();
        } catch (Exception $e) {}
        echo '<style>.reset-success-box{max-width:440px;margin:80px auto 0 auto;background:#fff;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,0.10);padding:36px 32px 28px 32px;text-align:center;}.reset-success-box .alert-success{font-size:1.18rem;font-weight:600;color:#198754;background:#e9fbe8;border-radius:8px;border:none;margin-bottom:0;}</style>';
        echo '<div class="reset-success-box"><div class="alert alert-success">Đăng ký thành công! Vui lòng kiểm tra email để kích hoạt tài khoản.</div></div>';
        exit;
    }
    include 'view/auth/register.php';
} elseif ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $error = '';
    $user = User::login($conn, $username, $password, $error);
    if ($user) {
        // Lấy role_name từ DB
        $stmt = $conn->prepare("SELECT r.name as role_name FROM user_roles ur 
                              JOIN roles r ON ur.role_id = r.id 
                              WHERE ur.user_id = ?");
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $user['role_name'] = $row['role_name'];
        } else {
            // Default role if not set
            $user['role_name'] = 'customer';
        }
        
        $_SESSION['user'] = $user;
        
        // Redirect to admin if admin, otherwise to home
        if (in_array($user['role_name'], ['admin', 'super_admin'])) {
            header('Location: index.php?controller=admin');
        } else {
            header('Location: index.php');
        }
        exit;
    }
    include 'view/auth/login.php';
} elseif ($action === 'register') {
    include 'view/auth/register.php';
} elseif ($action === 'login') {
    include 'view/auth/login.php';
} elseif ($action === 'logout') {
    session_destroy();
    header('Location: index.php?controller=auth&action=login');
    exit;
}

if ($action === 'forget_password') {
    include 'view/auth/forget_password.php';
    exit;
}

if ($action === 'send_reset' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        $token = bin2hex(random_bytes(32));
        $expired = date("Y-m-d H:i:s", strtotime('+15 minutes'));
        $stmt2 = $conn->prepare("UPDATE users SET reset_token=?, reset_expires=? WHERE email=?");
        $stmt2->bind_param("sss", $token, $expired, $email);
        $stmt2->execute();
        $link = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php?controller=auth&action=reset_password&token=$token";
        $subject = "Khôi phục mật khẩu";
        $message = "<p>Xin chào <b>{$user['username']}</b>,</p>"
                 . "<p>Bạn vừa yêu cầu đặt lại mật khẩu cho tài khoản tại Shop Điện Tử.</p>"
                 . "<p>Nhấn vào link sau để đặt lại mật khẩu (có hiệu lực trong 15 phút):<br>"
                 . "<a href='$link'>$link</a></p>"
                 . "<p>Nếu bạn không yêu cầu, hãy bỏ qua email này.</p>";
        // Gửi email bằng PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'thinhb2303782@student.ctu.edu.vn';
            $mail->Password = 'wuge yhnn xpae fcte';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('thinhb2303782@student.ctu.edu.vn', 'Shop Điện Tử');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;
            $mail->send();
            echo '<style>
            .reset-success-box {
                max-width: 440px;
                margin: 80px auto 0 auto;
                background: #fff;
                border-radius: 16px;
                box-shadow: 0 4px 24px rgba(0,0,0,0.10);
                padding: 36px 32px 28px 32px;
                text-align: center;
            }
            .reset-success-box .alert-success {
                font-size: 1.18rem;
                font-weight: 600;
                color: #198754;
                background: #e9fbe8;
                border-radius: 8px;
                border: none;
                margin-bottom: 0;
            }
            </style>';
            echo '<div class="reset-success-box"><div class="alert alert-success">Đã gửi link khôi phục về email. Vui lòng kiểm tra hộp thư!</div></div>';
        } catch (Exception $e) {
            echo '<style>
            .reset-success-box {
                max-width: 440px;
                margin: 80px auto 0 auto;
                background: #fff;
                border-radius: 16px;
                box-shadow: 0 4px 24px rgba(0,0,0,0.10);
                padding: 36px 32px 28px 32px;
                text-align: center;
            }
            .reset-success-box .alert-danger {
                font-size: 1.18rem;
                font-weight: 600;
                color: #dc3545;
                background: #fbe9e9;
                border-radius: 8px;
                border: none;
                margin-bottom: 0;
            }
            </style>';
            echo '<div class="reset-success-box"><div class="alert alert-danger">Không gửi được email. Lỗi: ' . $mail->ErrorInfo . '</div></div>';
        }
    } else {
        echo '<style>
        .reset-success-box {
            max-width: 440px;
            margin: 80px auto 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            padding: 36px 32px 28px 32px;
            text-align: center;
        }
        .reset-success-box .alert-danger {
            font-size: 1.18rem;
            font-weight: 600;
            color: #dc3545;
            background: #fbe9e9;
            border-radius: 8px;
            border: none;
            margin-bottom: 0;
        }
        </style>';
        echo '<div class="reset-success-box"><div class="alert alert-danger">Email không tồn tại trong hệ thống.</div></div>';
    }
    exit;
}

if ($action === 'reset_password') {
    $token = $_GET['token'] ?? '';
    if (!$token) die('Link không hợp lệ');
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token=?"); // BỎ kiểm tra reset_expires
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        include 'view/auth/reset_password.php';
    } else {
        echo '<div class="container" style="max-width:400px;margin:40px auto;"><div class="alert alert-danger">Link không hợp lệ hoặc đã hết hạn.</div></div>';
    }
    exit;
}

if ($action === 'send_reset_otp' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        $otp = rand(100000, 999999);
        $_SESSION['reset_otp'] = $otp;
        $_SESSION['reset_otp_email'] = $email;
        $_SESSION['reset_otp_expire'] = time() + 300;
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your_gmail@gmail.com';
            $mail->Password = 'your_app_password';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('your_gmail@gmail.com', 'Tên Shop');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Mã OTP đặt lại mật khẩu';
            $mail->Body    = "Mã OTP đặt lại mật khẩu của bạn là: <b>$otp</b> (có hiệu lực trong 5 phút)";
            $mail->send();
            include 'view/auth/verify_reset_otp.php';
        } catch (Exception $e) {
            echo '<div class="container" style="max-width:400px;margin:40px auto;"><div class="alert alert-danger">Không gửi được email. Lỗi: ' . $mail->ErrorInfo . '</div></div>';
        }
    } else {
        echo '<div class="container" style="max-width:400px;margin:40px auto;"><div class="alert alert-danger">Email không tồn tại trong hệ thống.</div></div>';
    }
    exit;
}
if ($action === 'verify_reset_otp' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_otp = $_POST['otp'] ?? '';
    if (
        isset($_SESSION['reset_otp'], $_SESSION['reset_otp_expire'], $_SESSION['reset_otp_email']) &&
        time() < $_SESSION['reset_otp_expire'] &&
        $user_otp == $_SESSION['reset_otp']
    ) {
        $_SESSION['reset_otp_verified'] = true;
        include 'view/auth/reset_password.php';
    } else {
        echo '<div class="container" style="max-width:400px;margin:40px auto;"><div class="alert alert-danger">Mã OTP không đúng hoặc đã hết hạn!</div></div>';
    }
    exit;
}
if ($action === 'save_new_password' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    if (!$new_password || !$token) die('Thiếu thông tin');
    // Kiểm tra token hợp lệ
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token=?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt2 = $conn->prepare("UPDATE users SET password_hash=?, reset_token=NULL, reset_expires=NULL WHERE id=?");
        $stmt2->bind_param("si", $hash, $user['id']);
        $stmt2->execute();
        echo '<style>
        .reset-success-box {
            max-width: 440px;
            margin: 80px auto 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            padding: 36px 32px 28px 32px;
            text-align: center;
        }
        .reset-success-box .alert-success {
            font-size: 1.18rem;
            font-weight: 600;
            color: #198754;
            background: #e9fbe8;
            border-radius: 8px;
            border: none;
            margin-bottom: 0;
        }
        .reset-success-box a {
            color: #0d6efd;
            text-decoration: underline;
            font-weight: 500;
        }
        </style>';
        echo '<div class="reset-success-box"><div class="alert alert-success">Đặt lại mật khẩu thành công! Bạn có thể <a href=\'index.php?controller=auth&action=login\'>đăng nhập</a> ngay.</div></div>';
    } else {
        echo '<style>
        .reset-success-box {
            max-width: 440px;
            margin: 80px auto 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            padding: 36px 32px 28px 32px;
            text-align: center;
        }
        .reset-success-box .alert-danger {
            font-size: 1.18rem;
            font-weight: 600;
            color: #dc3545;
            background: #fbe9e9;
            border-radius: 8px;
            border: none;
            margin-bottom: 0;
        }
        </style>';
        echo '<div class="reset-success-box"><div class="alert alert-danger">Link không hợp lệ hoặc đã hết hạn.</div></div>';
    }
    exit;
}

if ($action === 'send_otp' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_email'] = $email;
    $_SESSION['otp_expire'] = time() + 300;
    // Gửi OTP qua email bằng PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your_gmail@gmail.com'; // Thay bằng Gmail của bạn
        $mail->Password = 'your_app_password';    // Thay bằng App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('your_gmail@gmail.com', 'Tên Shop');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Mã xác thực OTP';
        $mail->Body    = "Mã OTP của bạn là: <b>$otp</b> (có hiệu lực trong 5 phút)";
        $mail->send();
        include 'view/auth/verify_otp.php';
    } catch (Exception $e) {
        echo '<div class="container" style="max-width:400px;margin:40px auto;"><div class="alert alert-danger">Không gửi được email. Lỗi: ' . $mail->ErrorInfo . '</div></div>';
    }
    exit;
}
if ($action === 'verify_otp' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_otp = $_POST['otp'] ?? '';
    if (
        isset($_SESSION['otp'], $_SESSION['otp_expire'], $_SESSION['otp_email']) &&
        time() < $_SESSION['otp_expire'] &&
        $user_otp == $_SESSION['otp']
    ) {
        unset($_SESSION['otp'], $_SESSION['otp_expire'], $_SESSION['otp_email']);
        echo '<div class="container" style="max-width:400px;margin:40px auto;"><div class="alert alert-success">Xác thực OTP thành công!</div></div>';
        // Tiếp tục cho phép đổi mật khẩu, đăng ký, ...
    } else {
        echo '<div class="container" style="max-width:400px;margin:40px auto;"><div class="alert alert-danger">Mã OTP không đúng hoặc đã hết hạn!</div></div>';
    }
    exit;
}

if ($action === 'active_account') {
    $token = $_GET['token'] ?? '';
    if (!$token) die('Link không hợp lệ');
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token=?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        $stmt2 = $conn->prepare("UPDATE users SET active=1, reset_token=NULL WHERE id=?");
        $stmt2->bind_param("i", $user['id']);
        $stmt2->execute();
        echo '<style>.reset-success-box{max-width:440px;margin:80px auto 0 auto;background:#fff;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,0.10);padding:36px 32px 28px 32px;text-align:center;}.reset-success-box .alert-success{font-size:1.18rem;font-weight:600;color:#198754;background:#e9fbe8;border-radius:8px;border:none;margin-bottom:0;}</style>';
        echo '<div class="reset-success-box"><div class="alert alert-success">Kích hoạt tài khoản thành công! Bạn có thể <a href=\'index.php?controller=auth&action=login\'>đăng nhập</a> ngay.</div></div>';
    } else {
        echo '<style>.reset-success-box{max-width:440px;margin:80px auto 0 auto;background:#fff;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,0.10);padding:36px 32px 28px 32px;text-align:center;}.reset-success-box .alert-danger{font-size:1.18rem;font-weight:600;color:#dc3545;background:#fbe9e9;border-radius:8px;border:none;margin-bottom:0;}</style>';
        echo '<div class="reset-success-box"><div class="alert alert-danger">Link kích hoạt không hợp lệ hoặc đã hết hạn.</div></div>';
    }
    exit;
}
?> 