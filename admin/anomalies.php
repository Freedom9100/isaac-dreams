<?php
require_once 'includes/auth_check.php';
require_once '../config/db.php';

$active_page = 'anomalies';

$stmt = $pdo->query("
    SELECT stages.*, stickers.title AS sticker_title, stickers.file_path AS sticker_img
    FROM stages
    LEFT JOIN stickers ON stages.sticker_id = stickers.id
    ORDER BY stages.id DESC
");
$stages = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total = count($stages);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Аномалии — Сны Исаака</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<?php require_once 'includes/sidebar.php'; ?>

        <div class="admin-page-header">
            <h1 class="admin-title">Аномалии</h1>
            <a href="add-anomaly.php" class="btn btn-dark">Добавить аномалию</a>
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
                        <th>Артефакт</th>
                        <th>Статус</th>
                        <th>Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($stages)): ?>
                        <tr>
                            <td colspan="6" class="td-mono" style="text-align:center; padding: 32px;">
                                Аномалии не созданы
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($stages as $st): ?>
                            <tr>
                                <td class="td-id"><?= $st['id'] ?></td>
                                <td class="td-preview">
                                    <?php if ($st['image_path']): ?>
                                        <img src="../<?= htmlspecialchars($st['image_path']) ?>" alt="">
                                    <?php elseif ($st['sticker_img']): ?>
                                        <img src="../<?= htmlspecialchars($st['sticker_img']) ?>" alt="">
                                    <?php else: ?>
                                        <span class="td-mono">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="td-name"><?= htmlspecialchars($st['title']) ?></td>
                                <td class="td-mono"><?= $st['sticker_title'] ? htmlspecialchars($st['sticker_title']) : '—' ?></td>
                                <td class="td-mono"><?= $st['active'] ? 'Активна' : 'Скрыта' ?></td>
                                <td class="td-actions">
                                    <div class="action-btns">
                                        <a href="edit-anomaly.php?id=<?= $st['id'] ?>" class="action-btn" title="Редактировать">
                                            <img src="../assets/icons/pen.svg" alt="редактировать">
                                        </a>
                                        <button class="action-btn delete js-delete-btn"
                                            data-url="actions/delete_stage.php?id=<?= $st['id'] ?>"
                                            data-name="<?= htmlspecialchars($st['title']) ?>"
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
