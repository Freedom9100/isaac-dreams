<?php
// $active_page — имя активного пункта меню: 'subjects', 'artifacts', 'anomalies'
$active_page = $active_page ?? '';
?>
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<div class="admin-layout">

    <!-- сайдбар -->
    <aside class="admin-sidebar" id="admin-sidebar">
        <div class="sidebar-top">
            <span class="sidebar-logo-title">Сны Исаака</span>
            <span class="sidebar-logo-sub">Административная панель</span>
        </div>
        <nav class="sidebar-nav">
            <a href="subjects.php" <?= $active_page === 'subjects' ? 'class="active"' : '' ?>>[ Субъекты ]</a>
            <a href="artifacts.php" <?= $active_page === 'artifacts' ? 'class="active"' : '' ?>>[ Артефакты ]</a>
            <a href="anomalies.php" <?= $active_page === 'anomalies' ? 'class="active"' : '' ?>>[ Аномалии ]</a>
        </nav>
        <div class="sidebar-footer">
            <a href="../actions/logout_action.php" class="sidebar-logout">
                <img src="../assets/icons/logout.svg" alt="">
                [ Выход ]
            </a>
        </div>
    </aside>

    <!-- основной контент -->
    <main class="admin-main">

        <!-- мобильный топбар -->
        <div class="admin-topbar">
            <span class="admin-topbar-logo">Сны Исаака</span>
            <button class="admin-burger" id="admin-burger">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
