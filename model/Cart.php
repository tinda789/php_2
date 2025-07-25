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
        $subtotal = 0;
        
        // Get cart items
        foreach ($_SESSION[$this->sessionKey] as $product_id => $quantity) {
            $product = Product::findById($product_id);
            if ($product) {
                $product['quantity'] = $quantity;
                $product['item_total'] = isset($product['sale_price']) && $product['sale_price'] > 0 ? 
                    $product['sale_price'] * $quantity : 
                    $product['price'] * $quantity;
                $subtotal += $product['item_total'];
                $cart[$product_id] = $product;
            }
        }
        
        // Calculate totals
        $discount = 0;
        $total = $subtotal;
        
        // Apply coupon if exists
        if (isset($_SESSION['applied_coupon'])) {
            $coupon = $_SESSION['applied_coupon'];
            
            if ($coupon['type'] == 'fixed') {
                $discount = $coupon['value'];
            } else {
                $discount = $subtotal * ($coupon['value'] / 100);
                if ($coupon['maximum_discount'] > 0 && $discount > $coupon['maximum_discount']) {
                    $discount = $coupon['maximum_discount'];
                }
            }
            
            $total = $subtotal - $discount;
            if ($total < 0) $total = 0;
            
            // Add coupon info to cart
            $cart['coupon'] = [
                'code' => $coupon['code'],
                'name' => $coupon['name'],
                'type' => $coupon['type'],
                'value' => $coupon['value'],
                'discount' => $discount,
                'formatted_discount' => number_format($discount, 0, ',', '.') . 'đ'
            ];
        }
        
        // Add summary to cart
        $cart['summary'] = [
            'subtotal' => $subtotal,
            'formatted_subtotal' => number_format($subtotal, 0, ',', '.') . 'đ',
            'discount' => $discount,
            'formatted_discount' => number_format($discount, 0, ',', '.') . 'đ',
            'total' => $total,
            'formatted_total' => number_format($total, 0, ',', '.') . 'đ'
        ];
        
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