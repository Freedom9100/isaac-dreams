<?php
require_once 'includes/auth_check.php';
require_once '../config/db.php';

$active_page = 'subjects';

// Загружаем всех пользователей (кроме администраторов)
$stmt = $pdo->query("SELECT id, name, login, created_at FROM users WHERE role = 'user' ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total = count($users);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>База субъектов — Сны Исаака</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<?php require_once 'includes/sidebar.php'; ?>

        <div class="admin-page-header">
            <h1 class="admin-title">База субъектов</h1>
        </div>

        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Связь (Email)</th>
                        <th>Дата погружения</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="4" class="td-mono" style="text-align:center; padding: 32px;">
                                Субъекты не зарегистрированы
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td class="td-id"><?= $user['id'] ?></td>
                                <td class="td-name"><?= htmlspecialchars($user['name']) ?></td>
                                <td class="td-mono"><?= htmlspecialchars($user['login']) ?></td>
                                <td class="td-mono"><?= $user['created_at'] ?></td>
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

<script src="js/admin.js"></script>
</body>
</html>
