<?php
session_start();
require_once 'config/config.php';
require_once 'model/User.php';

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
        header('Location: index.php?msg=Đăng ký thành công! Đăng nhập ngay.');
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
        $stmt = $conn->prepare("SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = ?");
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $user['role_name'] = $row['name'];
        }
        $_SESSION['user'] = $user;
        header('Location: index.php');
        exit;
    }
    include 'view/auth/login.php';
} elseif ($action === 'register') {
    include 'view/auth/register.php';
} elseif ($action === 'login') {
    include 'view/auth/login.php';
} elseif ($action === 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;
}
?> 