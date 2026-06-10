<?php
// Если не авторизован — на страницу входа
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

require_once 'config/db.php';
require_once 'config/levels.php';

// Загружаем прогресс из БД (на случай если сессия устарела)
$prog_stmt = $pdo->prepare("SELECT progress FROM users WHERE id = ?");
$prog_stmt->execute([$_SESSION['user_id']]);
$prog_row = $prog_stmt->fetch(PDO::FETCH_ASSOC);
$progress = (int) ($prog_row['progress'] ?? 0);
$_SESSION['user_progress'] = $progress;

$unlocked_sticker  = isset($_GET['sticker'])       ? (int) $_GET['sticker']       : 0;
$sticker_stage_id  = isset($_GET['sticker_stage']) ? (int) $_GET['sticker_stage'] : 0;

// Загружаем активные аномалии с привязанными артефактами
$anomalies = [];
try {
    $stmt = $pdo->query("
        SELECT stages.id, stages.title, stages.image_path, stages.lore,
               stickers.id AS sticker_id, stickers.file_path AS sticker_img,
               stickers.title AS sticker_title, stickers.description AS sticker_desc
        FROM stages
        LEFT JOIN stickers ON stages.sticker_id = stickers.id
        WHERE stages.active = 1
        ORDER BY stages.id ASC
    ");
    $anomalies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $anomalies = [];
}

// Загружаем пройденные этапы текущего пользователя
$completed_stages = [];
try {
    $cs_stmt = $pdo->prepare("SELECT stage_id FROM user_stages WHERE user_id = ?");
    $cs_stmt->execute([$_SESSION['user_id']]);
    foreach ($cs_stmt->fetchAll(PDO::FETCH_COLUMN) as $sid) {
        $completed_stages[$sid] = true;
    }
} catch (PDOException $e) {
    $completed_stages = [];
}

// Данные стикера для модала разблокированного этапа
$revealed_stage = null;
if ($sticker_stage_id) {
    foreach ($anomalies as $an) {
        if ($an['id'] === $sticker_stage_id) {
            $revealed_stage = $an;
            break;
        }
    }
}

// Определяем текущий доступный этап (первый незавершённый)
$current_stage = null;
foreach ($anomalies as $i => $an) {
    if (!isset($completed_stages[$an['id']])) {
        // Первый этап всегда доступен; остальные — только если предыдущий пройден
        if ($i === 0 || isset($completed_stages[$anomalies[$i - 1]['id']])) {
            $current_stage = $an;
        }
        break;
    }
}

// Данные текущего пользователя из сессии
$user_name  = htmlspecialchars($_SESSION['user_name'] ?? 'Неизвестен');
$user_login = htmlspecialchars($_SESSION['user_login'] ?? '');
$user_id    = (int) $_SESSION['user_id'];

$page_title = 'Профиль — Сны Исаака';
require_once 'includes/header.php';
?>

    <!-- контент профиля -->
    <main class="profile-page">
        <div class="container profile-layout">

            <!-- левый сайдбар -->
            <aside class="sidebar">
                <div class="sidebar-header">// Статус_погружения</div>

                <div class="sidebar-stats">
                    <div class="stat-row">
                        <span class="stat-label">Идентификатор спящего:</span>
                        <span class="stat-value">dormant_<?= $user_id ?></span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">Кодовое имя:</span>
                        <span class="stat-value"><?= $user_name ?></span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">Связь:</span>
                        <span class="stat-value"><?= $user_login ?></span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">Уровень страха:</span>
                        <span class="stat-value accent" id="fear-val">42%</span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">Пульс Исаака:</span>
                        <span class="stat-value" id="pulse-profile">143уд/мин</span>
                    </div>
                </div>

                <!-- блок синхронизации -->
                <div class="sidebar-preview">
                    <span class="sync-text">Синхронизация<br>сна</span>
                    <div class="sync-bar">
                        <div class="sync-bar-fill"></div>
                    </div>
                </div>

                <div class="sidebar-actions">
                    <button class="btn-sidebar-light" id="edit-name-btn">
                        <img src="assets/icons/pen.svg" alt="">
                        Коррекция параметров
                    </button>
                    <a href="actions/logout_action.php" class="btn-sidebar-dark">
                        <img src="assets/icons/logout.svg" alt="">
                        Проснуться
                    </a>
                </div>
            </aside>

            <!-- правая карточка -->
            <div class="fragments-card">
                <h1 class="fragments-title">Фрагменты памяти</h1>
                <div class="fragments-meta">
                    <span class="fragments-path">Сознание/Исаак/Собранный_свет</span>
                    <span class="fragments-tag-nav">
                        <button class="ftab-btn active" data-tab="fragments">Ядро памяти</button>
                        <span class="ftab-sep">//</span>
                        <button class="ftab-btn" data-tab="anomalies">Аномалии</button>
                    </span>
                </div>

                <!-- вкладка: фрагменты памяти -->
                <div id="tab-fragments" class="profile-tab-content active" style="display:block">
                    <div class="sector-list">
                        <?php for ($i = 1; $i <= 6; $i++):
                            $lv         = $levels_data[$i];
                            $is_done    = ($i <= $progress);
                            $is_current = ($i === $progress + 1);
                            $sym        = ($i === 6) ? '└─' : '├─';
                            $num        = str_pad($i, 2, '0', STR_PAD_LEFT);
                        ?>

                        <?php if ($is_done): ?>
                        <div class="sector-row">
                            <div class="sector-left">
                                <span class="sector-symbol"><?= $sym ?></span>
                                <span class="sector-label">[ Сектор <?= $num ?> // Пройден ]</span>
                            </div>
                            <img src="<?= htmlspecialchars($lv['sticker_img']) ?>"
                                 alt="Артефакт <?= $num ?>" class="sector-sticker">
                            <div class="sector-right">
                                <a href="<?= htmlspecialchars($lv['sticker_img']) ?>" download
                                   class="btn-artifact" target="_blank">
                                    <img src="assets/icons/download.svg" alt="">
                                    <span>Получить артефакт</span>
                                </a>
                            </div>
                        </div>

                        <?php elseif ($is_current): ?>
                        <div class="sector-row">
                            <div class="sector-left">
                                <span class="sector-symbol"><?= $sym ?></span>
                                <span class="sector-label">[ Сектор <?= $num ?> // Поглощён сном ]</span>
                            </div>
                            <div class="sector-right full">
                                <button class="btn-dive open-profile-modal">
                                    <span>Начать погружение</span>
                                </button>
                            </div>
                        </div>

                        <?php else: ?>
                        <div class="sector-row">
                            <div class="sector-left">
                                <span class="sector-symbol"><?= $sym ?></span>
                                <span class="sector-label muted">[ Сектор <?= $num ?> // Скрыт во тьме ]</span>
                            </div>
                            <div class="sector-right full">
                                <button class="btn-deep" disabled><span>Глубоко в памяти</span></button>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php endfor; ?>
                    </div>
                </div>

                <!-- вкладка: аномалии из БД -->
                <div id="tab-anomalies" class="profile-tab-content" style="display:none">
                    <div class="sector-list">
                        <?php if (empty($anomalies)): ?>
                            <div class="sector-row">
                                <div class="sector-left">
                                    <span class="sector-symbol">└─</span>
                                    <span class="sector-label muted">[ Аномалии не обнаружены // Система в ожидании ]</span>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php
                            $last_idx = count($anomalies) - 1;
                            foreach ($anomalies as $i => $an):
                                $sym       = ($i === $last_idx) ? '└─' : '├─';
                                $is_done   = isset($completed_stages[$an['id']]);
                                $is_cur    = (!$is_done && $current_stage && $current_stage['id'] === $an['id']);
                                $is_locked = (!$is_done && !$is_cur);
                            ?>
                            <div class="sector-row">
                                <div class="sector-left">
                                    <span class="sector-symbol"><?= $sym ?></span>
                                    <?php if ($is_done): ?>
                                        <span class="sector-label">[ <?= htmlspecialchars($an['title']) ?> // Пройдена ]</span>
                                    <?php elseif ($is_cur): ?>
                                        <span class="sector-label">[ <?= htmlspecialchars($an['title']) ?> // Поглощена тьмой ]</span>
                                    <?php else: ?>
                                        <span class="sector-label muted">[ <?= htmlspecialchars($an['title']) ?> // Скрыта во тьме ]</span>
                                    <?php endif; ?>
                                </div>

                                <?php if ($is_done && $an['sticker_img']): ?>
                                    <img src="<?= htmlspecialchars($an['sticker_img']) ?>"
                                         alt="<?= htmlspecialchars($an['sticker_title'] ?? '') ?>" class="sector-sticker">
                                    <div class="sector-right">
                                        <a href="<?= htmlspecialchars($an['sticker_img']) ?>" download
                                           class="btn-artifact" target="_blank">
                                            <img src="assets/icons/download.svg" alt="">
                                            <span>Получить артефакт</span>
                                        </a>
                                    </div>
                                <?php elseif ($is_done): ?>
                                    <div class="sector-right full">
                                        <span class="btn-completed">// Пройдена</span>
                                    </div>
                                <?php elseif ($is_cur): ?>
                                    <div class="sector-right full">
                                        <button class="btn-dive open-stage-modal"
                                                data-stage="<?= $an['id'] ?>">
                                            <span>Начать погружение</span>
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <div class="sector-right full">
                                        <button class="btn-deep" disabled><span>Глубоко в памяти</span></button>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="fragments-end">// Конец сновидения</div>
            </div>

        </div>
    </main>

<!-- погружение в уровень -->
<?php if ($progress < 6): ?>
<?php $cur = $levels_data[$progress + 1]; ?>
<div id="profile-level-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.75); z-index:1050; align-items:center; justify-content:center;">
    <div class="modal-content card" style="max-width:480px; position:relative;">
        <button class="close-btn" id="profile-level-close">
            <img src="assets/icons/close.svg" alt="Закрыть">
        </button>
        <img src="<?= htmlspecialchars($cur['modal_img'] ?? $cur['sticker_img']) ?>"
             alt="" class="modal-img<?= !$cur['modal_img'] ? ' modal-img--sticker' : '' ?>">
        <div class="modal-text">
            <span class="modal-subtitle">[ <?= htmlspecialchars($cur['sector_label']) ?> ]</span>
            <h2><?= htmlspecialchars($cur['title']) ?></h2>
            <p><?= htmlspecialchars($cur['lore']) ?></p>
            <form method="post" action="actions/complete_level.php">
                <input type="hidden" name="level" value="<?= $progress + 1 ?>">
                <input type="hidden" name="redirect_to" value="../index.php?page=profile">
                <button type="submit" class="btn btn-dark w-100">Извлечь осколок света</button>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- стикер разблокирован -->
<?php if ($unlocked_sticker >= 1 && $unlocked_sticker <= 6): ?>
<?php $rev = $levels_data[$unlocked_sticker]; ?>
<div id="profile-sticker-modal" style="display:flex; position:fixed; inset:0; background:rgba(0,0,0,0.75); z-index:1100; align-items:center; justify-content:center;">
    <div class="modal-content card" style="max-width:440px; text-align:center; position:relative;">
        <button class="close-btn" id="profile-sticker-close">
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
<?php endif; ?>

<!-- МОДАЛ: погружение в этап -->
<?php if ($current_stage): ?>
<div id="stage-level-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.75); z-index:1050; align-items:center; justify-content:center;">
    <div class="modal-content card" style="max-width:480px; position:relative;">
        <button class="close-btn" id="stage-level-close">
            <img src="assets/icons/close.svg" alt="Закрыть">
        </button>
        <?php if ($current_stage['image_path']): ?>
            <img src="<?= htmlspecialchars($current_stage['image_path']) ?>" alt="" class="modal-img">
        <?php elseif ($current_stage['sticker_img']): ?>
            <img src="<?= htmlspecialchars($current_stage['sticker_img']) ?>" alt="" class="modal-img modal-img--sticker">
        <?php endif; ?>
        <div class="modal-text">
            <span class="modal-subtitle">[ Аномалия // <?= htmlspecialchars($current_stage['title']) ?> ]</span>
            <h2><?= htmlspecialchars($current_stage['title']) ?></h2>
            <?php if ($current_stage['lore']): ?>
                <p><?= htmlspecialchars($current_stage['lore']) ?></p>
            <?php endif; ?>
            <form method="post" action="actions/complete_stage.php">
                <input type="hidden" name="stage_id" value="<?= $current_stage['id'] ?>">
                <button type="submit" class="btn btn-dark w-100">Извлечь осколок света</button>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- стикер этапа разблокирован -->
<?php if ($revealed_stage && $revealed_stage['sticker_img']): ?>
<div id="stage-sticker-modal" style="display:flex; position:fixed; inset:0; background:rgba(0,0,0,0.75); z-index:1100; align-items:center; justify-content:center;">
    <div class="modal-content card" style="max-width:440px; text-align:center; position:relative;">
        <button class="close-btn" id="stage-sticker-close">
            <img src="assets/icons/close.svg" alt="Закрыть">
        </button>
        <img src="<?= htmlspecialchars($revealed_stage['sticker_img']) ?>"
             alt="<?= htmlspecialchars($revealed_stage['sticker_title'] ?? '') ?>"
             class="modal-img modal-img--sticker">
        <div class="modal-text">
            <span class="modal-subtitle">[ Артефакт извлечён // <?= htmlspecialchars($revealed_stage['title']) ?> ]</span>
            <h2><?= htmlspecialchars($revealed_stage['sticker_title'] ?? $revealed_stage['title']) ?></h2>
            <?php if ($revealed_stage['sticker_desc']): ?>
                <p><?= htmlspecialchars($revealed_stage['sticker_desc']) ?></p>
            <?php endif; ?>
            <a href="<?= htmlspecialchars($revealed_stage['sticker_img']) ?>" download
               class="btn btn-dark w-100">Скачать артефакт</a>
        </div>
    </div>
</div>
<?php elseif ($revealed_stage): ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var aBtn = document.querySelector('.ftab-btn[data-tab="anomalies"]');
    if (aBtn) aBtn.click();
});
</script>
<?php endif; ?>

<!-- модал редактирования имени -->
<div id="edit-name-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.55); z-index:1000; align-items:center; justify-content:center;">
    <div class="profile-modal">
        <p class="profile-modal-title">// Коррекция параметров</p>
        <form method="post" action="actions/update_name.php">
            <div class="profile-modal-field">
                <label class="profile-modal-label">Кодовое имя</label>
                <input type="text" name="name" class="profile-modal-input"
                       value="<?= $user_name ?>" maxlength="100" required>
            </div>
            <div class="profile-modal-actions">
                <button type="submit" class="btn-sidebar-dark">Сохранить</button>
                <button type="button" class="btn-sidebar-light" id="edit-name-cancel">Отмена</button>
            </div>
        </form>
    </div>
</div>

<script>
// переключение вкладок профиля
var tabBtns     = document.querySelectorAll('.ftab-btn');
var tabContents = document.querySelectorAll('.profile-tab-content');

tabBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
        var target = btn.getAttribute('data-tab');
        tabBtns.forEach(function(b) { b.classList.remove('active'); });
        tabContents.forEach(function(c) { c.style.display = 'none'; });
        btn.classList.add('active');
        document.getElementById('tab-' + target).style.display = 'block';
    });
});

// модал этапа аномалии
var stageLevelModal  = document.getElementById('stage-level-modal');
var stageLevelClose  = document.getElementById('stage-level-close');
var stageStickerModal = document.getElementById('stage-sticker-modal');
var stageStickerClose = document.getElementById('stage-sticker-close');
var openStageBtns    = document.querySelectorAll('.open-stage-modal');

openStageBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
        if (stageLevelModal) stageLevelModal.style.display = 'flex';
    });
});

if (stageLevelClose) {
    stageLevelClose.addEventListener('click', function() { stageLevelModal.style.display = 'none'; });
}
if (stageLevelModal) {
    stageLevelModal.addEventListener('click', function(e) {
        if (e.target === stageLevelModal) stageLevelModal.style.display = 'none';
    });
}
if (stageStickerClose && stageStickerModal) {
    stageStickerClose.addEventListener('click', function() { stageStickerModal.style.display = 'none'; });
    stageStickerModal.addEventListener('click', function(e) {
        if (e.target === stageStickerModal) stageStickerModal.style.display = 'none';
    });
}

// при открытии страницы с ?sticker_stage= переключить таб на аномалии
<?php if ($sticker_stage_id): ?>
document.addEventListener('DOMContentLoaded', function() {
    var aBtn = document.querySelector('.ftab-btn[data-tab="anomalies"]');
    if (aBtn) aBtn.click();
});
<?php endif; ?>

// модал уровня (погружение)
var profileLevelModal = document.getElementById('profile-level-modal');
var profileLevelClose = document.getElementById('profile-level-close');
var openProfileModalBtns = document.querySelectorAll('.open-profile-modal');

openProfileModalBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
        if (profileLevelModal) profileLevelModal.style.display = 'flex';
    });
});

if (profileLevelClose) {
    profileLevelClose.addEventListener('click', function() {
        profileLevelModal.style.display = 'none';
    });
}

if (profileLevelModal) {
    profileLevelModal.addEventListener('click', function(e) {
        if (e.target === profileLevelModal) profileLevelModal.style.display = 'none';
    });
}

// модал стикера (разблокирован)
var profileStickerClose = document.getElementById('profile-sticker-close');
var profileStickerModal = document.getElementById('profile-sticker-modal');

if (profileStickerClose && profileStickerModal) {
    profileStickerClose.addEventListener('click', function() {
        profileStickerModal.style.display = 'none';
    });
    profileStickerModal.addEventListener('click', function(e) {
        if (e.target === profileStickerModal) profileStickerModal.style.display = 'none';
    });
}

// модал редактирования имени
var editNameBtn    = document.getElementById('edit-name-btn');
var editNameModal  = document.getElementById('edit-name-modal');
var editNameCancel = document.getElementById('edit-name-cancel');

if (editNameBtn) {
    editNameBtn.addEventListener('click', function() { editNameModal.style.display = 'flex'; });
}
if (editNameCancel) {
    editNameCancel.addEventListener('click', function() { editNameModal.style.display = 'none'; });
}
if (editNameModal) {
    editNameModal.addEventListener('click', function(e) {
        if (e.target === editNameModal) editNameModal.style.display = 'none';
    });
}
</script>

<?php require_once 'includes/footer.php'; ?>
