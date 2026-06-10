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
if (empty($name) || empty($login) || empty($password)) {
    header('Location: ../index.php?page=register&error=empty');
    exit;
}

// Проверяем совпадение паролей
if ($password !== $confirm) {
    header('Location: ../index.php?page=register&error=mismatch');
    exit;
}

// Минимальная длина пароля
if (strlen($password) < 6) {
    header('Location: ../index.php?page=register&error=short');
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
