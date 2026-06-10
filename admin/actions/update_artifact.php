<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../../index.php?page=login');
    exit;
}
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../artifacts.php');
    exit;
}

$id          = (int) ($_POST['id'] ?? 0);
$title       = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$active      = isset($_POST['active']) ? 1 : 0;
$old_path    = trim($_POST['old_file_path'] ?? '');

if (!$id || empty($title)) {
    header('Location: ../edit-artifact.php?id=' . $id . '&error=title_empty');
    exit;
}
if (mb_strlen($title) > 20) {
    header('Location: ../edit-artifact.php?id=' . $id . '&error=title_long');
    exit;
}
if (mb_strlen($description) > 500) {
    header('Location: ../edit-artifact.php?id=' . $id . '&error=desc_long');
    exit;
}

// Проверяем что запись существует
$stmt = $pdo->prepare("SELECT id FROM stickers WHERE id = ?");
$stmt->execute([$id]);
if (!$stmt->fetch()) {
    header('Location: ../artifacts.php');
    exit;
}

// Обработка нового изображения
$file_path = $old_path;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
    if (in_array($_FILES['image']['type'], $allowed_types)) {
        $ext      = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $filename = 'sticker_' . uniqid() . '.' . $ext;
        $dest     = '../../assets/images/stickers/' . $filename;
        move_uploaded_file($_FILES['image']['tmp_name'], $dest);
        $file_path = 'assets/images/stickers/' . $filename;
        // Удаляем старый файл если он был
        if ($old_path && file_exists('../../' . $old_path)) {
            unlink('../../' . $old_path);
        }
    }
}

$stmt = $pdo->prepare("UPDATE stickers SET title = ?, description = ?, file_path = ?, active = ? WHERE id = ?");
$stmt->execute([$title, $description, $file_path, $active, $id]);

header('Location: ../artifacts.php?success=1');
exit;
