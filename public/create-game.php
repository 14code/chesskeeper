<?php
require_once __DIR__ . '/../src/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['image_ids']) && is_array($_POST['image_ids'])) {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO games (user_id, date, result) VALUES (1, DATE('now'), 0.5)");
    $stmt->execute();
    $gameId = $pdo->lastInsertId();

    $imageIds = $_POST['image_ids'];
    foreach ($imageIds as $pos => $id) {
        $stmt = $pdo->prepare("UPDATE images SET game_id = ?, position = ? WHERE id = ?");
        $stmt->execute([$gameId, $pos + 1, $id]);
    }

    $pdo->commit();
    header("Location: /edit-game.php?id=" . $gameId);
    exit;
} else {
    echo "No images selected.";
}
