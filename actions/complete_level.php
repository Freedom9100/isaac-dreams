<?php
session_start();

// Только авторизованные пользователи могут проходить уровни
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php?page=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

require_once '../config/db.php';

$level            = (int) ($_POST['level'] ?? 0);
$current_progress = (int) ($_SESSION['user_progress'] ?? 0);
$redirect_to      = $_POST['redirect_to'] ?? '../index.php';

// Разрешаем пройти только строго следующий уровень (нельзя пропускать)
if ($level < 1 || $level > 6 || $level !== $current_progress + 1) {
    header('Location: ' . $redirect_to);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE users SET progress = ? WHERE id = ?");
    $stmt->execute([$level, $_SESSION['user_id']]);
    $_SESSION['user_progress'] = $level;
} catch (PDOException $e) {
    header('Location: ' . $redirect_to);
    exit;
}

// Возвращаемся на исходную страницу с параметром открытого стикера
$sep = strpos($redirect_to, '?') !== false ? '&' : '?';
header('Location: ' . $redirect_to . $sep . 'sticker=' . $level);
exit;
