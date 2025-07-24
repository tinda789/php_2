<?php
require_once 'config/config.php';
require_once 'model/ContactRequest.php';
class ContactController {
    public function index() {
        global $conn;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'message' => trim($_POST['message'] ?? '')
            ];
            $contactModel = new ContactRequest($conn);
            $contactModel->create($data);
            header('Location: index.php?controller=contact&sent=1');
            exit;
        }
        include 'view/contact.php';
    }
} 