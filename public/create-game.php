<?php
require_once __DIR__ . '/../src/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO games (user_id, date, result) VALUES (1, DATE('now'), 0)");
    $stmt->execute();
    $gameId = $pdo->lastInsertId();

    if (isset($_POST['image_ids']) && is_array($_POST['image_ids'])) {
        $imageIds = $_POST['image_ids'];
        foreach ($imageIds as $pos => $id) {
            $stmt = $pdo->prepare("UPDATE images SET game_id = ?, position = ? WHERE id = ?");
            $stmt->execute([$gameId, $pos + 1, $id]);
        }
    }

    $pdo->commit();
    header("Location: /game?id=" . $gameId);
    exit;
} else {
    echo "No game created.";
}
