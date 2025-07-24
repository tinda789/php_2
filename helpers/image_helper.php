<?php
/**
 * Helper functions for image handling
 */

/**
 * Get the correct image URL for display
 * @param string $image_url The image URL from database
 * @param string $upload_dir The upload directory (default: 'uploads/products/')
 * @return string The corrected image URL
 */
function getImageUrl($image_url, $upload_dir = 'uploads/products/') {
    if (empty($image_url)) {
        return 'https://via.placeholder.com/300x200?text=No+Image';
    }
    
    // Nếu là link ngoài (http/https)
    if (preg_match('/^https?:\/\//', $image_url)) {
        return $image_url;
    }
    
    // Nếu đã có đường dẫn đầy đủ từ uploads/
    if (strpos($image_url, 'uploads/') === 0) {
        return $image_url;
    }
    
    // Nếu chỉ có tên file, thêm đường dẫn uploads/products/
    if (!empty($image_url) && strpos($image_url, '/') === false) {
        return $upload_dir . $image_url;
    }
    
    // Trường hợp khác, trả về như cũ
    return $image_url;
}

/**
 * Check if image file exists
 * @param string $image_url The image URL from database
 * @param string $upload_dir The upload directory (default: 'uploads/products/')
 * @return bool True if file exists, false otherwise
 */
function imageExists($image_url, $upload_dir = 'uploads/products/') {
    if (empty($image_url)) {
        return false;
    }
    
    // Nếu là link ngoài, không kiểm tra
    if (preg_match('/^https?:\/\//', $image_url)) {
        return true;
    }
    
    // Xây dựng đường dẫn file thực tế
    $file_path = '';
    if (strpos($image_url, 'uploads/') === 0) {
        $file_path = __DIR__ . '/../' . $image_url;
    } elseif (strpos($image_url, '/') === false) {
        $file_path = __DIR__ . '/../' . $upload_dir . $image_url;
    } else {
        $file_path = __DIR__ . '/../' . $image_url;
    }
    
    return file_exists($file_path);
}

/**
 * Get image HTML tag with fallback
 * @param string $image_url The image URL from database
 * @param string $alt Alt text for image
 * @param string $class CSS class
 * @param string $style CSS style
 * @param string $upload_dir The upload directory (default: 'uploads/products/')
 * @return string HTML img tag
 */
function getImageTag($image_url, $alt = '', $class = '', $style = '', $upload_dir = 'uploads/products/') {
    $src = getImageUrl($image_url, $upload_dir);
    $attributes = [];
    
    if (!empty($class)) {
        $attributes[] = 'class="' . htmlspecialchars($class) . '"';
    }
    
    if (!empty($style)) {
        $attributes[] = 'style="' . htmlspecialchars($style) . '"';
    }
    
    if (!empty($alt)) {
        $attributes[] = 'alt="' . htmlspecialchars($alt) . '"';
    }
    
    $attr_string = !empty($attributes) ? ' ' . implode(' ', $attributes) : '';
    
    return '<img src="' . htmlspecialchars($src) . '"' . $attr_string . '>';
}
?> 