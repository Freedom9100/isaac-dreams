<?php
require_once 'includes/auth_check.php';
require_once '../config/db.php';

$active_page = 'artifacts';

$stmt = $pdo->query("SELECT * FROM stickers ORDER BY id DESC");
$stickers = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total = count($stickers);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Артефакты памяти — Сны Исаака</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<?php require_once 'includes/sidebar.php'; ?>

        <div class="admin-page-header">
            <h1 class="admin-title">Артефакты памяти</h1>
            <a href="add-artifact.php" class="btn btn-dark">Добавить артефакт</a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <p class="admin-alert admin-alert--ok">Изменения сохранены.</p>
        <?php endif; ?>

        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Превью</th>
                        <th>Название</th>
                        <th>Статус</th>
                        <th>Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($stickers)): ?>
                        <tr>
                            <td colspan="5" class="td-mono" style="text-align:center; padding: 32px;">
                                Артефакты не созданы
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($stickers as $s): ?>
                            <tr>
                                <td class="td-id"><?= $s['id'] ?></td>
                                <td class="td-preview">
                                    <?php if ($s['file_path']): ?>
                                        <img src="../<?= htmlspecialchars($s['file_path']) ?>" alt="<?= htmlspecialchars($s['title']) ?>">
                                    <?php else: ?>
                                        <span class="td-mono">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="td-name"><?= htmlspecialchars($s['title']) ?></td>
                                <td class="td-mono"><?= $s['active'] ? 'Активен' : 'Скрыт' ?></td>
                                <td class="td-actions">
                                    <div class="action-btns">
                                        <a href="edit-artifact.php?id=<?= $s['id'] ?>" class="action-btn" title="Редактировать">
                                            <img src="../assets/icons/pen.svg" alt="редактировать">
                                        </a>
                                        <button class="action-btn delete js-delete-btn"
                                            data-url="actions/delete_artifact.php?id=<?= $s['id'] ?>"
                                            data-name="<?= htmlspecialchars($s['title']) ?>"
                                            title="Удалить">
                                            <img src="../assets/icons/bucket.svg" alt="удалить">
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="table-footer">
                <span class="table-count">Показано <?= $total ?> из <?= $total ?></span>
            </div>
        </div>

    </main>
</div>

<!-- модал подтверждения удаления -->
<div class="admin-modal-overlay" id="delete-modal">
    <div class="admin-modal">
        <p class="admin-modal-text">Удалить <span id="delete-modal-name" class="admin-modal-accent"></span>?</p>
        <p class="admin-modal-sub">Это действие нельзя отменить.</p>
        <div class="admin-modal-actions">
            <button class="btn btn-dark" id="delete-confirm-btn">Удалить</button>
            <button class="btn" id="delete-cancel-btn">Отмена</button>
        </div>
    </div>
</div>

<script src="js/admin.js"></script>
</body>
</html>
