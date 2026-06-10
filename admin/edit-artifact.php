<?php
require_once 'includes/auth_check.php';
require_once '../config/db.php';

$active_page = 'artifacts';

$id = (int) ($_GET['id'] ?? 0);
if (!$id) {
    header('Location: artifacts.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM stickers WHERE id = ?");
$stmt->execute([$id]);
$sticker = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sticker) {
    header('Location: artifacts.php');
    exit;
}

$error = '';
if (isset($_GET['error']) && $_GET['error'] === 'empty') {
    $error = 'Название обязательно для заполнения.';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать артефакт — Сны Исаака</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<?php require_once 'includes/sidebar.php'; ?>

        <div class="admin-page-header">
            <div class="admin-back-wrap">
                <a href="artifacts.php" class="admin-back-link">← Артефакты</a>
                <h1 class="admin-title">Редактировать артефакт</h1>
            </div>
        </div>

        <?php if ($error): ?>
            <p class="admin-alert admin-alert--err"><?= $error ?></p>
        <?php endif; ?>

        <div class="admin-form-card">
            <form method="post" action="actions/update_artifact.php" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $sticker['id'] ?>">
                <input type="hidden" name="old_file_path" value="<?= htmlspecialchars($sticker['file_path']) ?>">

                <div class="form-row-2">
                    <div>
                        <div class="form-group">
                            <label class="form-label">Название артефакта</label>
                            <input type="text" name="title" class="form-input" value="<?= htmlspecialchars($sticker['title']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Описание</label>
                            <textarea name="description" class="form-textarea"><?= htmlspecialchars($sticker['description']) ?></textarea>
                        </div>

                        <label class="form-check">
                            <input type="checkbox" name="active" value="1" <?= $sticker['active'] ? 'checked' : '' ?>>
                            <span class="form-check-label">Активировать артефакт</span>
                        </label>

                        <button type="submit" class="btn btn-dark">Сохранить изменения</button>
                    </div>

                    <div>
                        <span class="form-upload-label">Изображение стикера</span>
                        <?php if ($sticker['file_path']): ?>
                            <div class="current-image-preview">
                                <img src="../<?= htmlspecialchars($sticker['file_path']) ?>" alt="текущее изображение">
                                <span class="current-image-label">Текущее изображение</span>
                            </div>
                        <?php endif; ?>
                        <div class="drop-zone" id="drop-zone">
                            <img src="../assets/icons/upload.svg" alt="" class="drop-zone-icon">
                            <span class="drop-zone-text" id="drop-text">Загрузить новое изображение<br>JPG, PNG</span>
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
