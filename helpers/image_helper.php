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
    // Nếu là link ngoài
    if (preg_match('/^https?:\/\//', $image_url)) {
        return $image_url;
    }
    // Lấy thư mục gốc project (tương đối với domain)
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    return $base . '/' . ltrim($image_url, '/');
}

/**
 * Check if image file exists
 * @param string $image_url The image URL from database
 * @param string $upload_dir The upload directory (default: 'uploads/products/')
 * @return bool True if file exists, false otherwise
 */
function imageExists($image_url, $upload_dir = 'uploads/products/') {
    $file_path = getImageUrl($image_url, $upload_dir);
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