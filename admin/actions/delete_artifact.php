<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../../index.php?page=login');
    exit;
}
require_once '../../config/db.php';

$id = (int) ($_GET['id'] ?? 0);
if (!$id) {
    header('Location: ../artifacts.php');
    exit;
}

// Получаем путь к файлу перед удалением
$stmt = $pdo->prepare("SELECT file_path FROM stickers WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    // Удаляем физический файл
    if ($row['file_path'] && file_exists('../../' . $row['file_path'])) {
        unlink('../../' . $row['file_path']);
    }
    // Удаляем запись из БД
    $stmt = $pdo->prepare("DELETE FROM stickers WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: ../artifacts.php?success=1');
exit;
