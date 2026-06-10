<?php
session_start();
require_once '../config/db.php';

// Только POST-запросы
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=login');
    exit;
}

$login    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Проверяем что поля заполнены
if (empty($login)) {
    header('Location: ../index.php?page=login&error=email_empty');
    exit;
}
if (empty($password)) {
    header('Location: ../index.php?page=login&error=pass_empty&email=' . urlencode($login));
    exit;
}

try {
    // Ищем пользователя по логину
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Проверяем пароль
    if (!$user || !password_verify($password, $user['pass_hash'])) {
        header('Location: ../index.php?page=login&error=wrong');
        exit;
    }

    // Сохраняем данные в сессию
    $_SESSION['user_id']       = $user['id'];
    $_SESSION['user_name']     = $user['name'];
    $_SESSION['user_login']    = $user['login'];
    $_SESSION['user_role']     = $user['role'];
    $_SESSION['user_progress'] = (int) ($user['progress'] ?? 0);

    // Администратора отправляем в панель
    if ($user['role'] === 'admin') {
        header('Location: ../admin/subjects.php');
        exit;
    }

    header('Location: ../index.php?page=profile');
    exit;

} catch (PDOException $e) {
    // Ошибка БД
    header('Location: ../index.php?page=login&error=db');
    exit;
}
