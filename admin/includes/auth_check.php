<?php
session_start();

// Только авторизованные администраторы имеют доступ к панели
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../index.php?page=login');
    exit;
}
