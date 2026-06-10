<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../../index.php?page=login');
    exit;
}
require_once '../../config/db.php';

$id = (int) ($_GET['id'] ?? 0);
if (!$id) {
    header('Location: ../anomalies.php');
    exit;
}

$stmt = $pdo->prepare("SELECT image_path FROM stages WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    if ($row['image_path'] && file_exists('../../' . $row['image_path'])) {
        unlink('../../' . $row['image_path']);
    }
    $stmt = $pdo->prepare("DELETE FROM stages WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: ../anomalies.php?success=1');
exit;
