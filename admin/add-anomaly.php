<?php
require_once 'includes/auth_check.php';
require_once '../config/db.php';

$active_page = 'anomalies';

// Загружаем все активные стикеры для выпадающего списка
$stmt = $pdo->query("SELECT id, title FROM stickers WHERE active = 1 ORDER BY title");
$sticker_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

$error = '';
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'title_empty': $error = 'Ошибка: название обязательно для заполнения.'; break;
        case 'title_long':  $error = 'Ошибка: название не должно превышать 20 символов.'; break;
        case 'lore_long':   $error = 'Ошибка: описание аномалии не должно превышать 1000 символов.'; break;
        case 'filetype':    $error = 'Ошибка: допустимые форматы изображения — JPG, PNG.'; break;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить аномалию — Сны Исаака</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<?php require_once 'includes/sidebar.php'; ?>

        <div class="admin-page-header">
            <div class="admin-back-wrap">
                <a href="anomalies.php" class="admin-back-link">← Аномалии</a>
                <h1 class="admin-title">Добавить аномалию</h1>
            </div>
        </div>

        <?php if ($error): ?>
            <p class="admin-alert admin-alert--err"><?= $error ?></p>
        <?php endif; ?>

        <div class="admin-form-card">
            <form method="post" action="actions/create_stage.php" enctype="multipart/form-data" novalidate>

                <div class="form-row-2">
                    <div>
                        <div class="form-group">
                            <label class="form-label">Название аномалии</label>
                            <input type="text" name="title" class="form-input" placeholder="Введите название" maxlength="20">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Привязанный артефакт</label>
                            <select name="sticker_id" class="form-select">
                                <option value="">— Без артефакта —</option>
                                <?php foreach ($sticker_list as $sk): ?>
                                    <option value="<?= $sk['id'] ?>"><?= htmlspecialchars($sk['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Сюжетная сводка (лор)</label>
                            <textarea name="lore" class="form-textarea" placeholder="Описание аномалии..." maxlength="1000"></textarea>
                        </div>

                        <label class="form-check">
                            <input type="checkbox" name="active" value="1" checked>
                            <span class="form-check-label">Активировать аномалию</span>
                        </label>

                        <button type="submit" class="btn btn-dark">Сохранить аномалию</button>
                    </div>

                    <div>
                        <span class="form-upload-label">Изображение аномалии</span>
                        <div class="drop-zone" id="drop-zone">
                            <img src="../assets/icons/upload.svg" alt="" class="drop-zone-icon">
                            <span class="drop-zone-text" id="drop-text">Перетащите или кликните для выбора<br>JPG, PNG</span>
                            <span class="drop-zone-filename" id="drop-filename"></span>
                            <input type="file" name="image" id="file-input" accept=".jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>

            </form>
        </div>

    </main>
</div>

<script src="js/admin.js"></script>
</body>
</html>
