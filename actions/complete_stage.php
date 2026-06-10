<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php?page=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?page=profile');
    exit;
}

require_once '../config/db.php';

$stage_id    = (int) ($_POST['stage_id'] ?? 0);
$user_id     = (int) $_SESSION['user_id'];

if (!$stage_id) {
    header('Location: ../index.php?page=profile');
    exit;
}

try {
    // Проверяем что этап существует и активен
    $stmt = $pdo->prepare("SELECT id FROM stages WHERE id = ? AND active = 1");
    $stmt->execute([$stage_id]);
    if (!$stmt->fetch()) {
        header('Location: ../index.php?page=profile');
        exit;
    }

    // Получаем все активные этапы по порядку
    $all = $pdo->query("SELECT id FROM stages WHERE active = 1 ORDER BY id ASC")->fetchAll(PDO::FETCH_COLUMN);
    $idx = array_search($stage_id, $all);

    // Первый этап всегда можно пройти, иначе нужно чтобы предыдущий был завершён
    if ($idx > 0) {
        $prev_id = $all[$idx - 1];
        $check   = $pdo->prepare("SELECT id FROM user_stages WHERE user_id = ? AND stage_id = ?");
        $check->execute([$user_id, $prev_id]);
        if (!$check->fetch()) {
            // Предыдущий этап не пройден
            header('Location: ../index.php?page=profile');
            exit;
        }
    }

    // Записываем (INSERT IGNORE — если уже есть запись, ничего не делаем)
    $stmt = $pdo->prepare("INSERT IGNORE INTO user_stages (user_id, stage_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $stage_id]);

} catch (PDOException $e) {
    header('Location: ../index.php?page=profile');
    exit;
}

header('Location: ../index.php?page=profile&sticker_stage=' . $stage_id);
exit;
