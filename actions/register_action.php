<?php
session_start();
require_once '../config/db.php';

// Только POST-запросы
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=register');
    exit;
}

$name     = trim($_POST['name'] ?? '');
$login    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm  = $_POST['password_confirm'] ?? '';

// Проверяем что поля заполнены
if (empty($name)) {
    header('Location: ../index.php?page=register&error=name_empty&email=' . urlencode($login));
    exit;
}
if (empty($login)) {
    header('Location: ../index.php?page=register&error=email_empty&name=' . urlencode($name));
    exit;
}
if (empty($password)) {
    header('Location: ../index.php?page=register&error=pass_empty&name=' . urlencode($name) . '&email=' . urlencode($login));
    exit;
}

// Проверяем формат email
if (!filter_var($login, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../index.php?page=register&error=invalid_email&name=' . urlencode($name) . '&email=' . urlencode($login));
    exit;
}

// Проверяем совпадение паролей
if ($password !== $confirm) {
    header('Location: ../index.php?page=register&error=mismatch&name=' . urlencode($name) . '&email=' . urlencode($login));
    exit;
}

// Минимальная длина пароля
if (strlen($password) < 6) {
    header('Location: ../index.php?page=register&error=short&name=' . urlencode($name) . '&email=' . urlencode($login));
    exit;
}

try {
    // Проверяем, не занят ли логин
    $stmt = $pdo->prepare("SELECT id FROM users WHERE login = ?");
    $stmt->execute([$login]);
    if ($stmt->fetch()) {
        header('Location: ../index.php?page=register&error=exists');
        exit;
    }

    // Хешируем пароль и создаём пользователя
    $pass_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, login, pass_hash, role) VALUES (?, ?, ?, 'user')");
    $stmt->execute([$name, $login, $pass_hash]);
    $user_id = $pdo->lastInsertId();

    // Сразу авторизуем
    $_SESSION['user_id']       = $user_id;
    $_SESSION['user_name']     = $name;
    $_SESSION['user_login']    = $login;
    $_SESSION['user_role']     = 'user';
    $_SESSION['user_progress'] = 0;

    header('Location: ../index.php?page=profile');
    exit;

} catch (PDOException $e) {
    // Ошибка БД — скорее всего таблицы не созданы
    header('Location: ../index.php?page=register&error=db');
    exit;
}
