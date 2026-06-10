<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php?page=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=profile');
    exit;
}

$name = trim($_POST['name'] ?? '');

if (empty($name)) {
    header('Location: ../index.php?page=profile&error=empty_name');
    exit;
}

$stmt = $pdo->prepare("UPDATE users SET name = ? WHERE id = ?");
$stmt->execute([$name, $_SESSION['user_id']]);

// Обновляем имя в сессии
$_SESSION['user_name'] = $name;

header('Location: ../index.php?page=profile');
exit;
