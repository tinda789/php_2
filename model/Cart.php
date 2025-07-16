<?php
// model/Cart.php
require_once __DIR__ . '/Product.php';

class Cart {
    private $sessionKey = 'cart_items';

    public function addToCart($product_id, $quantity = 1) {
        if (!isset($_SESSION[$this->sessionKey])) {
            $_SESSION[$this->sessionKey] = [];
        }
        if (isset($_SESSION[$this->sessionKey][$product_id])) {
            $_SESSION[$this->sessionKey][$product_id] += $quantity;
        } else {
            $_SESSION[$this->sessionKey][$product_id] = $quantity;
        }
    }

    public function getCart() {
        if (!isset($_SESSION[$this->sessionKey])) {
            return [];
        }
        $cart = [];
        foreach ($_SESSION[$this->sessionKey] as $product_id => $quantity) {
            $product = Product::findById($product_id);
            if ($product) {
                $product['quantity'] = $quantity;
                $cart[] = $product;
            }
        }
        return $cart;
    }

    public function removeFromCart($product_id) {
        if (isset($_SESSION[$this->sessionKey][$product_id])) {
            unset($_SESSION[$this->sessionKey][$product_id]);
        }
    }

    public function updateQuantity($product_id, $quantity) {
        if (isset($_SESSION[$this->sessionKey][$product_id])) {
            $_SESSION[$this->sessionKey][$product_id] = $quantity;
        }
    }
} 