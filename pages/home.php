<?php
require_once 'config/levels.php';

// Прогресс пользователя из сессии (0 = ни одного уровня не пройдено)
$progress         = isset($_SESSION['user_id']) ? (int) ($_SESSION['user_progress'] ?? 0) : 0;
$unlocked_sticker = isset($_GET['sticker']) ? (int) $_GET['sticker'] : 0;

$page_title = 'Сны Исаака - Интерактивный квест';
require_once 'includes/header.php';
?>

    <!-- главный экран -->
    <section class="hero container">
        <div class="hero-content">
            <h1>Глубже в сон<br>ближе к выходу</h1>
            <p>Здесь пахнет сырым кафелем и одиночеством. Это сон Исаака. Найди Ключ Света в бесконечных пустых
                комнатах, пока Тень не закрыла дверь навсегда.</p>
            <a href="#trials" class="btn btn-dark">Начать погружение</a>
            <div class="system-status">Системный статус: онлайн</div>
        </div>
        <div class="hero-image-box card">
            <div class="slider-wrap">
                <div class="slider-track">
                    <div class="slide">
                        <img src="assets/images/slider/tonel_slide-1.png" alt="Коридор" class="hero-img">
                        <div class="hero-caption">
                            <p>Сектор 01 // Фрагмент</p>
                            <span>Бесконечный коридор стертых воспоминаний. Сон начинается здесь</span>
                        </div>
                    </div>
                    <div class="slide">
                        <img src="assets/images/slider/empty-mirror_slide-2.png" alt="Зеркало" class="hero-img">
                        <div class="hero-caption">
                            <p>Сектор 02 // Искажение</p>
                            <span>Зеркало, отражающее пустоту. Первая половина Ключа скрыта на той стороне</span>
                        </div>
                    </div>
                    <div class="slide">
                        <img src="assets/images/slider/shadow_slide-3.png" alt="Тень" class="hero-img">
                        <div class="hero-caption">
                            <p>Сектор 03 // Угроза</p>
                            <span>Тень поглощает мысли. Чем глубже ты спускаешься, тем ближе её шаги</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slider-nav">
                <button class="slider-btn" id="slider-prev">
                    <img src="assets/icons/chevron.svg" alt="Назад" class="chevron-flip">
                </button>
                <button class="slider-btn" id="slider-next">
                    <img src="assets/icons/chevron.svg" alt="Вперёд">
                </button>
            </div>
        </div>
    </section>

    <!-- раздел протокол -->
    <section id="protocol" class="protocol container">
        <div class="section-title">[ Раздел 02 // Протокол ]</div>
        <div class="protocol-grid">
            <div class="card memory-card">
                <div class="card-header gray-header">
                    <span>Фрагмент памяти // Забытый бассейн</span>
                    <div class="rec-indicator">
                        <span class="rec-label">REC</span>
                        <span class="rec-dot"></span>
                    </div>
                </div>
                <div class="card-body">
                    <p>Я снова там, где пахнет сырым кафелем и старой водой. Стены этого бассейна уходят в <span
                            class="redacted">пустоту</span>. Я звал маму, но ответом было лишь искаженное эхо моих
                        собственных шагов. Выход заперт. Сама архитектура этого места меняется, стоит мне <span
                            class="redacted">отвернуться</span>. Мне нужно срочно проснуться.</p>
                    <p>Она преследует меня. Эта Тень соткана из моих <span class="redacted">самых жутких</span> детских
                        страхов, и она становится сильнее в темноте. Чтобы открыть дверь наружу, мне нужно найти осколки
                        Ключа Света. Но пульс растет, а Тень шевелится прямо на границе моего <span
                            class="redacted">зрения</span>.</p>
                    <div class="noise-box">
                        <span class="noise-label">визуальные_данные_повреждены</span>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="footer-col">
                        <span class="footer-label">Биометрия</span>
                        <span id="pulse-home">Пульс Исаака: 143уд/мин</span>
                    </div>
                    <div class="footer-col dark">
                        <span class="footer-label">Телеметрия</span>
                        <span>Сектор: Неизвестен</span>
                    </div>
                </div>
            </div>
            <div class="protocol-sidebar">
                <div class="card warning-card">
                    <div class="card-header dark">Аномалия // Тень</div>
                    <div class="warning-content">
                        <h3>Избегать визуального контакта</h3>
                        <span class="stamp">Объект агрессивен</span>
                    </div>
                </div>
                <div class="card info-card">
                    <div class="card-header dark">Протокол // Ключ света</div>
                    <div class="info-content">
                        <p>Для экстренного пробуждения необходимо собрать Ключ Света, расколотый Тенью на две половины.
                            Найдите обе части артефакта в глубинах подсознания, чтобы открыть финальные врата лабиринта.
                        </p>
                        <a href="#trials" class="btn btn-dark w-100">Начать поиск</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- раздел испытания -->
    <section id="trials" class="trials container">
        <div class="section-title">[ Раздел 03 // Испытания ]</div>
        <div class="levels-list">
            <?php for ($i = 1; $i <= 6; $i++):
                $lv          = $levels_data[$i];
                $is_done     = ($i <= $progress);
                $is_current  = ($i === $progress + 1);
                $is_locked   = ($i > $progress + 1);
                $num         = str_pad($i, 2, '0', STR_PAD_LEFT);
            ?>

            <?php if ($i === 4): ?>
            <!-- декоративный вертикальный текст -->
            <div class="vtext-wrap">
                <div class="vtext-inner">
                    <p class="vt-unknown">неизвестна</p>
                    <p class="vt-depth">глубина</p>
                    <p class="vt-memory">памяти</p>
                </div>
            </div>
            <?php endif; ?>

            <div class="level-card card level-<?= $i ?><?= $is_done ? ' level-done' : '' ?>">
                <div class="level-top">
                    <span class="level-name"><?= htmlspecialchars($lv['title']) ?></span>
                    <span class="level-code"><?= $lv['code'] ?></span>
                </div>
                <div class="level-bottom">
                    <div class="level-num"><?= $num ?></div>
                    <?php if ($is_done): ?>
                        <span class="btn-completed">// Пройдено</span>
                    <?php elseif ($is_current): ?>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <button class="btn btn-dark level-btn open-modal-btn">Открыть дверь</button>
                        <?php else: ?>
                            <a href="index.php?page=login" class="btn btn-dark">Войти для прохождения</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <button class="btn-locked" disabled>Дверь заперта</button>
                    <?php endif; ?>
                </div>
            </div>

            <?php endfor; ?>
        </div>
    </section>

    <!-- раздел инвентарь -->
    <section id="inventory" class="inventory container">
        <div class="section-title">[ Раздел 04 // Инвентарь ]</div>
        <div class="inventory-grid">
            <?php for ($i = 1; $i <= 6; $i++):
                $lv       = $levels_data[$i];
                $unlocked = ($i <= $progress);
                $num      = str_pad($i, 2, '0', STR_PAD_LEFT);
            ?>

            <?php if ($unlocked): ?>
            <div class="item-card card">
                <div class="item-img-wrap">
                    <img src="<?= htmlspecialchars($lv['sticker_img']) ?>"
                         alt="<?= htmlspecialchars($lv['sticker_name']) ?>" class="item-img">
                </div>
                <div class="item-info">
                    <span class="item-level">[Уровень <?= $num ?>]</span>
                    <h3><?= htmlspecialchars($lv['sticker_name']) ?></h3>
                    <p><?= htmlspecialchars($lv['sticker_desc']) ?></p>
                    <a href="<?= htmlspecialchars($lv['sticker_img']) ?>" download
                       class="btn btn-dark w-100">Скачать артефакт</a>
                </div>
            </div>
            <?php else: ?>
            <div class="item-card card locked-card">
                <div class="item-img-wrap">
                    <img src="<?= htmlspecialchars($lv['sticker_img']) ?>" alt="Заблокировано" class="item-img">
                    <div class="lock-overlay">
                        <img src="assets/icons/lock.svg" alt="" class="lock-icon">
                    </div>
                </div>
                <div class="item-info">
                    <span class="item-level">[Уровень <?= $num ?>]</span>
                    <h3>???</h3>
                    <p>Фрагмент сна поврежден. Продолжайте спуск, чтобы восстановить доступ.</p>
                    <button class="item-btn-locked w-100" disabled>Выполните уровень</button>
                </div>
            </div>
            <?php endif; ?>

            <?php endfor; ?>
        </div>
    </section>

    <!-- раздел faq -->
    <section id="faq" class="faq container">
        <div class="section-title">[ Раздел 05 // Системные запросы ]</div>
        <div class="faq-list card">
            <div class="faq-item active">
                <div class="faq-header">
                    <span>01 // Как сбросить сон?</span>
                    <span class="faq-icon"><img src="assets/icons/add.svg" alt="Добавить"></span>
                </div>
                <div class="faq-content">
                    <div class="faq-content-inner">
                        <h3>// Совет спящему</h3>
                        <p>Сброс невозможен. Единственный путь наверх лежит через Эпицентр. Соберите обе половины Ключа
                            Света и откройте финальные врата, иначе ваше сознание останется в этой петле навсегда.</p>
                    </div>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-header">
                    <span>02 // Что делать при встрече с тенью?</span>
                    <span class="faq-icon"><img src="assets/icons/add.svg" alt="Добавить"></span>
                </div>
                <div class="faq-content">
                    <div class="faq-content-inner">
                        <h3>// Совет спящему</h3>
                        <p>Бежать. Не пытайтесь вступить в контакт.</p>
                    </div>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-header">
                    <span>03 // Зачем извлекать артефакты?</span>
                    <span class="faq-icon"><img src="assets/icons/add.svg" alt="Добавить"></span>
                </div>
                <div class="faq-content">
                    <div class="faq-content-inner">
                        <h3>// Совет спящему</h3>
                        <p>Артефакты восстанавливают вашу память и открывают новые уровни.</p>
                    </div>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-header">
                    <span>04 // ОШИБКА ДОСТУПА: ДВЕРЬ НА СЛЕДУЮЩИЙ УРОВЕНЬ ЗАКРЫТА</span>
                    <span class="faq-icon"><img src="assets/icons/add.svg" alt="Добавить"></span>
                </div>
                <div class="faq-content">
                    <div class="faq-content-inner">
                        <h3>// Совет спящему</h3>
                        <p>Система пустит вас глубже только в том случае, если артефакт текущего сектора успешно найден и сохранен. Проверьте свой Архив. Вы не можете сбежать, оставив воспоминания здесь.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== МОДАЛ: информация об уровне + извлечение осколка ===== -->
    <?php if (isset($_SESSION['user_id']) && $progress < 6): ?>
    <?php $cur = $levels_data[$progress + 1]; ?>
    <div class="modal" id="info-modal">
        <div class="modal-content card">
            <button class="close-btn"><img src="assets/icons/close.svg" alt="Закрыть"></button>
            <?php if ($cur['modal_img']): ?>
                <img src="<?= htmlspecialchars($cur['modal_img']) ?>" alt="" class="modal-img">
            <?php else: ?>
                <img src="<?= htmlspecialchars($cur['sticker_img']) ?>" alt="" class="modal-img modal-img--sticker">
            <?php endif; ?>
            <div class="modal-text">
                <span class="modal-subtitle">[ <?= htmlspecialchars($cur['sector_label']) ?> ]</span>
                <h2><?= htmlspecialchars($cur['title']) ?></h2>
                <p><?= htmlspecialchars($cur['lore']) ?></p>
                <form method="post" action="actions/complete_level.php">
                    <input type="hidden" name="level" value="<?= $progress + 1 ?>">
                    <input type="hidden" name="redirect_to" value="../index.php">
                    <button type="submit" class="btn btn-dark w-100">Извлечь осколок света</button>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ===== МОДАЛ: стикер разблокирован (показывается после complete_level) ===== -->
    <?php if ($unlocked_sticker >= 1 && $unlocked_sticker <= 6): ?>
    <?php $rev = $levels_data[$unlocked_sticker]; ?>
    <div id="sticker-modal" style="display:flex; position:fixed; inset:0; background:rgba(0,0,0,0.75); z-index:1100; align-items:center; justify-content:center;">
        <div class="modal-content card" style="max-width:460px; text-align:center; position:relative;">
            <button class="close-btn" id="sticker-modal-close">
                <img src="assets/icons/close.svg" alt="Закрыть">
            </button>
            <img src="<?= htmlspecialchars($rev['sticker_img']) ?>"
                 alt="<?= htmlspecialchars($rev['sticker_name']) ?>"
                 class="modal-img modal-img--sticker">
            <div class="modal-text">
                <span class="modal-subtitle">[ Артефакт извлечён // Уровень <?= str_pad($unlocked_sticker, 2, '0', STR_PAD_LEFT) ?> ]</span>
                <h2><?= htmlspecialchars($rev['sticker_name']) ?></h2>
                <p><?= htmlspecialchars($rev['sticker_desc']) ?></p>
                <a href="<?= htmlspecialchars($rev['sticker_img']) ?>" download
                   class="btn btn-dark w-100">Скачать артефакт</a>
            </div>
        </div>
    </div>
    <script>
    var stickerClose = document.getElementById('sticker-modal-close');
    var stickerModal = document.getElementById('sticker-modal');
    if (stickerClose) {
        stickerClose.addEventListener('click', function() { stickerModal.style.display = 'none'; });
    }
    stickerModal.addEventListener('click', function(e) {
        if (e.target === stickerModal) { stickerModal.style.display = 'none'; }
    });
    </script>
    <?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
