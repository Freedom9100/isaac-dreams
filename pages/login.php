<?php
// Если уже авторизован — на профиль
if (isset($_SESSION['user_id'])) {
    header('Location: index.php?page=profile');
    exit;
}

// Текст ошибки по коду из GET-параметра
$error_messages = [
    'empty' => 'Заполни все поля.',
    'wrong' => 'Неверный логин или пароль.',
    'db'    => 'Ошибка базы данных. Убедитесь что таблицы созданы.',
];
$error = isset($_GET['error']) ? ($error_messages[$_GET['error']] ?? '') : '';
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Войти — Сны Исаака</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;900&family=JetBrains+Mono:wght@400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="auth-page">

        <!-- левая часть — анимация тени -->
        <div class="auth-left">
            <canvas class="shadow-canvas" id="shadow-canvas"></canvas>
            <svg class="shadow-fog" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <defs>
                    <filter id="fog" x="0" y="0" width="100%" height="100%" color-interpolation-filters="sRGB">
                        <feTurbulence type="fractalNoise" baseFrequency="0.013 0.009" numOctaves="3" seed="7" result="noise">
                            <animate attributeName="baseFrequency"
                                dur="24s"
                                keyTimes="0;0.5;1"
                                values="0.013 0.009;0.02 0.014;0.013 0.009"
                                repeatCount="indefinite"/>
                        </feTurbulence>
                        <feDisplacementMap in="SourceGraphic" in2="noise" scale="25" xChannelSelector="R" yChannelSelector="G"/>
                    </filter>
                </defs>
                <rect width="100%" height="100%" fill="rgba(200,200,205,0.06)" filter="url(#fog)"/>
            </svg>
            <div class="shadow-figure"></div>
        </div>

        <!-- правая часть — форма -->
        <div class="auth-right">
            <div class="auth-card">

                <span class="auth-tag">[ Страница 001 ]</span>
                <h1 class="auth-title">Шаг в темноту</h1>

                <?php if ($error): ?>
                    <p class="auth-error"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>

                <form class="auth-form" action="actions/login_action.php" method="post">

                    <div class="form-group">
                        <label class="form-label" for="email">Имя спящего</label>
                        <div class="input-wrap">
                            <input
                                class="form-input"
                                type="email"
                                id="email"
                                name="email"
                                placeholder="example@dormant.ru"
                                value="<?= htmlspecialchars($_GET['email'] ?? '') ?>"
                                required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Код пробуждения</label>
                        <div class="input-wrap">
                            <input
                                class="form-input"
                                type="password"
                                id="password"
                                name="password"
                                placeholder="··········"
                                required>
                            <button type="button" class="input-icon" id="toggle-password">
                                <img src="assets/icons/check.svg" alt="Показать пароль" id="eye-icon">
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-dark w-100">Заснуть</button>

                </form>

                <p class="auth-note">
                    Твои страхи ещё не записаны?
                    <a href="index.php?page=register" class="auth-link">Создать кошмар</a>
                </p>

            </div>
        </div>

    </div>

    <script src="js/auth.js"></script>
    <script src="js/auth-animation.js"></script>

</body>

</html>
