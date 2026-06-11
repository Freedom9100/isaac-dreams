<?php
session_start();

// Список допустимых страниц (защита от path traversal)
$allowed_pages = ['home', 'login', 'register', 'profile'];

// Получаем запрошенную страницу, по умолчанию — главная
$page = $_GET['page'] ?? 'home';

// Если страница не в белом списке — показываем главную
if (!in_array($page, $allowed_pages)) {
    $page = 'home';
}

require_once "pages/{$page}.php";
