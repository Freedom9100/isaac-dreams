<?php
// Если уже авторизован — на профиль
if (isset($_SESSION['user_id'])) {
    header('Location: index.php?page=profile');
    exit;
}

// Текст ошибки по коду из GET-параметра
$error_messages = [
    'name_empty'    => 'Введи кодовое имя.',
    'email_empty'   => 'Введи адрес электронной связи.',
    'pass_empty'    => 'Введи код пробуждения.',
    'invalid_email' => 'Неверный формат адреса электронной связи.',
    'mismatch'      => 'Коды пробуждения не совпадают.',
    'short'         => 'Код пробуждения должен содержать не менее 6 символов.',
    'exists'        => 'Этот адрес уже зарегистрирован в системе.',
    'db'            => 'Ошибка базы данных. Убедитесь что таблицы созданы.',
];
$error = isset($_GET['error']) ? ($error_messages[$_GET['error']] ?? '') : '';
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация — Сны Исаака</title>
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
                        <feTurbulence type="fractalNoise" baseFrequency="0.013 0.009" numOctaves="3" seed="3" result="noise">
                            <animate attributeName="baseFrequency"
                                dur="26s"
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

                <span class="auth-tag">[ Страница 002 ]</span>
                <h1 class="auth-title">Первое погружение</h1>

                <?php if ($error): ?>
                    <p class="auth-error"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>

                <form class="auth-form" action="actions/register_action.php" method="post" novalidate>

                    <div class="form-group">
                        <label class="form-label" for="name">Кодовое имя</label>
                        <div class="input-wrap">
                            <input
                                class="form-input"
                                type="text"
                                id="name"
                                name="name"
                                placeholder="Иван Иванов"
                                value="<?= htmlspecialchars($_GET['name'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Адрес электронной связи</label>
                        <div class="input-wrap">
                            <input
                                class="form-input"
                                type="email"
                                id="email"
                                name="email"
                                placeholder="example@dormant.ru"
                                value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="password">Код пробуждения</label>
                            <div class="input-wrap">
                                <input
                                    class="form-input"
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="··········">
                                <button type="button" class="input-icon" id="toggle-password">
                                    <img src="assets/icons/check.svg" alt="Показать пароль">
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="password-confirm">Подтвердите код</label>
                            <div class="input-wrap">
                                <input
                                    class="form-input"
                                    type="password"
                                    id="password-confirm"
                                    name="password_confirm"
                                    placeholder="··········">
                                <button type="button" class="input-icon" id="toggle-confirm">
                                    <img src="assets/icons/check.svg" alt="Показать пароль">
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-dark w-100">Погрузиться</button>

                </form>

                <p class="auth-note">
                    Уже бродили по этим коридорам?
                    <a href="index.php?page=login" class="auth-link">Вернуться в систему</a>
                </p>

            </div>
        </div>

    </div>

    <script src="js/auth.js"></script>
    <script src="js/auth-animation.js"></script>

</body>

</html>
