<?php
require_once __DIR__ . '/../src/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    if (!$id) {
        http_response_code(400);
        echo "Missing game ID.";
        exit;
    }

    // Spiel löschen
    $stmt = $pdo->prepare("DELETE FROM games WHERE id = ? AND user_id = 1");
    $stmt->execute([$id]);

    // Zugehörige Bilder aktualisieren
    $stmt = $pdo->prepare("UPDATE images SET game_id = NULL, position = 0 WHERE game_id = ? AND user_id = 1");
    $stmt->execute([$id]);

    header("Location: /games");
    exit;
} else {
    http_response_code(405);
    echo "Method not allowed.";
}
