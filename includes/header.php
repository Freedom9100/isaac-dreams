<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'Сны Исаака') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;900&family=JetBrains+Mono:wght@400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- фиксированные края страницы -->
    <div id="page-edges">
        <canvas id="edge-canvas"></canvas>
    </div>

    <!-- шапка -->
    <header class="header">
        <div class="container header-inner">
            <div class="logo">
                <a href="index.php"><img src="assets/logo.svg" alt="Сны Исаака"></a>
            </div>
            <nav class="nav" id="mobile-nav">
                <a href="index.php#protocol">Протокол</a>
                <a href="index.php#trials">Испытания</a>
                <a href="index.php#inventory">Инвентарь</a>
                <a href="index.php#faq">FAQ</a>
            </nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <button class="btn btn-dark"><a href="index.php?page=profile">Информация о субъекте</a></button>
            <?php else: ?>
                <button class="btn btn-dark"><a href="index.php?page=login">Войти в систему</a></button>
            <?php endif; ?>
            <button class="burger" id="burger">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

    <!-- мобильное меню -->
    <div class="mobile-overlay" id="mobile-overlay">
        <div class="mobile-menu" id="mobile-menu">
            <div class="mobile-menu-top">
                <button class="mobile-menu-close" id="mobile-menu-close">
                    <img src="assets/icons/close.svg" alt="Закрыть">
                </button>
            </div>
            <nav class="mobile-menu-nav">
                <a href="index.php#protocol">> Протокол</a>
                <a href="index.php#trials">> Испытания</a>
                <a href="index.php#inventory">> Инвентарь</a>
                <a href="index.php#faq">> FAQ</a>
            </nav>
            <div class="mobile-menu-footer">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="btn btn-light w-100" onclick="location.href='index.php?page=profile'">Информация о субъекте</button>
                <?php else: ?>
                    <button class="btn btn-light w-100" onclick="location.href='index.php?page=login'">Войти в систему</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
