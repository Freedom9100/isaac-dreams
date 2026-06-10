<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../../index.php?page=login');
    exit;
}
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../anomalies.php');
    exit;
}

$id         = (int) ($_POST['id'] ?? 0);
$title      = trim($_POST['title'] ?? '');
$lore       = trim($_POST['lore'] ?? '');
$sticker_id = !empty($_POST['sticker_id']) ? (int) $_POST['sticker_id'] : null;
$active     = isset($_POST['active']) ? 1 : 0;
$old_path   = trim($_POST['old_image_path'] ?? '');

if (!$id || empty($title)) {
    header('Location: ../edit-anomaly.php?id=' . $id . '&error=title_empty');
    exit;
}
if (mb_strlen($title) > 20) {
    header('Location: ../edit-anomaly.php?id=' . $id . '&error=title_long');
    exit;
}
if (mb_strlen($lore) > 1000) {
    header('Location: ../edit-anomaly.php?id=' . $id . '&error=lore_long');
    exit;
}

// Проверяем что запись существует
$stmt = $pdo->prepare("SELECT id FROM stages WHERE id = ?");
$stmt->execute([$id]);
if (!$stmt->fetch()) {
    header('Location: ../anomalies.php');
    exit;
}

// Обработка нового изображения
$image_path = $old_path;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
    if (in_array($_FILES['image']['type'], $allowed_types)) {
        $ext      = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $filename = 'stage_' . uniqid() . '.' . $ext;
        $dest     = '../../assets/images/stages/' . $filename;
        move_uploaded_file($_FILES['image']['tmp_name'], $dest);
        $image_path = 'assets/images/stages/' . $filename;
        if ($old_path && file_exists('../../' . $old_path)) {
            unlink('../../' . $old_path);
        }
    }
}

$stmt = $pdo->prepare("UPDATE stages SET title = ?, lore = ?, sticker_id = ?, image_path = ?, active = ? WHERE id = ?");
$stmt->execute([$title, $lore, $sticker_id, $image_path, $active, $id]);

header('Location: ../anomalies.php?success=1');
exit;
