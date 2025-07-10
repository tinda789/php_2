<?php
require_once '../config/config.php';
require_once '../model/Category.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if ($action === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    $data = [
        'name' => $_POST['name'] ?? '',
        'slug' => $_POST['slug'] ?? '',
        'description' => $_POST['description'] ?? '',
        'parent_id' => isset($_POST['parent_id']) && $_POST['parent_id'] !== '' ? (int)$_POST['parent_id'] : null,
        'image' => $_POST['image'] ?? '',
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
        'sort_order' => 0
    ];
    $result = Category::update($conn, $id, $data);
    echo json_encode(['success' => $result]);
    exit;
}

if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    $result = Category::delete($conn, $id);
    echo json_encode(['success' => $result]);
    exit;
}

echo json_encode(['error' => 'Invalid action']);