<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../../index.php?page=login');
    exit;
}
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../add-anomaly.php');
    exit;
}

$title      = trim($_POST['title'] ?? '');
$lore       = trim($_POST['lore'] ?? '');
$sticker_id = !empty($_POST['sticker_id']) ? (int) $_POST['sticker_id'] : null;
$active     = isset($_POST['active']) ? 1 : 0;

if (empty($title)) {
    header('Location: ../add-anomaly.php?error=empty');
    exit;
}

// Обработка загрузки изображения
$image_path = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
    if (!in_array($_FILES['image']['type'], $allowed_types)) {
        header('Location: ../add-anomaly.php?error=filetype');
        exit;
    }
    $ext      = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $filename = 'stage_' . uniqid() . '.' . $ext;
    $dest     = '../../assets/images/stages/' . $filename;
    move_uploaded_file($_FILES['image']['tmp_name'], $dest);
    $image_path = 'assets/images/stages/' . $filename;
}

$stmt = $pdo->prepare("INSERT INTO stages (title, lore, sticker_id, image_path, active) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$title, $lore, $sticker_id, $image_path, $active]);

header('Location: ../anomalies.php?success=1');
exit;
