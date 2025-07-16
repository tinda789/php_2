<?php
// CartController.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../model/Product.php';
require_once __DIR__ . '/../model/Cart.php';

class CartController {
    public function add() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        if ($product_id <= 0) {
            header('Location: index.php?controller=product&action=list&error=invalid_product');
            exit;
        }
        $cartModel = new Cart();
        $cartModel->addToCart($product_id, $quantity);
        header('Location: index.php?controller=cart&action=view');
        exit;
    }
    public function view() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $cartModel = new Cart();
        $cart = $cartModel->getCart();
        require __DIR__ . '/../view/layout/header.php';
        require __DIR__ . '/../view/user/cart_view.php';
        require __DIR__ . '/../view/layout/footer.php';
    }
    public function remove() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
        if ($product_id > 0) {
            $cartModel = new Cart();
            $cartModel->removeFromCart($product_id);
        }
        header('Location: index.php?controller=cart&action=view');
        exit;
    }
    public function update() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        if ($product_id > 0 && $quantity > 0) {
            $cartModel = new Cart();
            $cartModel->updateQuantity($product_id, $quantity);
        }
        header('Location: index.php?controller=cart&action=view');
        exit;
    }
    public function count() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $count = 0;
        if (!empty($_SESSION['cart_items'])) {
            foreach ($_SESSION['cart_items'] as $qty) $count += $qty;
        }
        header('Content-Type: application/json');
        echo json_encode(['count' => $count]);
        exit;
    }
    // Có thể bổ sung các action khác như remove, update quantity...
} 