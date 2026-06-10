<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../../index.php?page=login');
    exit;
}
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../add-artifact.php');
    exit;
}

$title       = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$active      = isset($_POST['active']) ? 1 : 0;

if (empty($title)) {
    header('Location: ../add-artifact.php?error=title_empty');
    exit;
}
if (mb_strlen($title) > 20) {
    header('Location: ../add-artifact.php?error=title_long');
    exit;
}
if (mb_strlen($description) > 500) {
    header('Location: ../add-artifact.php?error=desc_long');
    exit;
}

// Обработка загрузки изображения
$file_path = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
    if (!in_array($_FILES['image']['type'], $allowed_types)) {
        header('Location: ../add-artifact.php?error=filetype');
        exit;
    }
    $ext      = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $filename = 'sticker_' . uniqid() . '.' . $ext;
    $dest     = '../../assets/images/stickers/' . $filename;
    move_uploaded_file($_FILES['image']['tmp_name'], $dest);
    $file_path = 'assets/images/stickers/' . $filename;
}

$stmt = $pdo->prepare("INSERT INTO stickers (title, description, file_path, active) VALUES (?, ?, ?, ?)");
$stmt->execute([$title, $description, $file_path, $active]);

header('Location: ../artifacts.php?success=1');
exit;
