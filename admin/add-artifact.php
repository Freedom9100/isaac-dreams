<?php
require_once 'includes/auth_check.php';

$active_page = 'artifacts';

$error = '';
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'empty')    $error = 'Название обязательно для заполнения.';
    if ($_GET['error'] === 'filetype') $error = 'Допустимые форматы: JPG, PNG.';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить артефакт — Сны Исаака</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<?php require_once 'includes/sidebar.php'; ?>

        <div class="admin-page-header">
            <div class="admin-back-wrap">
                <a href="artifacts.php" class="admin-back-link">← Артефакты</a>
                <h1 class="admin-title">Добавить артефакт</h1>
            </div>
        </div>

        <?php if ($error): ?>
            <p class="admin-alert admin-alert--err"><?= $error ?></p>
        <?php endif; ?>

        <div class="admin-form-card">
            <form method="post" action="actions/create_artifact.php" enctype="multipart/form-data">

                <div class="form-row-2">
                    <div>
                        <div class="form-group">
                            <label class="form-label">Название артефакта</label>
                            <input type="text" name="title" class="form-input" placeholder="Введите название" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Описание</label>
                            <textarea name="description" class="form-textarea" placeholder="Краткое описание артефакта..."></textarea>
                        </div>

                        <label class="form-check">
                            <input type="checkbox" name="active" value="1" checked>
                            <span class="form-check-label">Активировать артефакт</span>
                        </label>

                        <button type="submit" class="btn btn-dark">Сохранить артефакт</button>
                    </div>

                    <div>
                        <span class="form-upload-label">Изображение стикера</span>
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
