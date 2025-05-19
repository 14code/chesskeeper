<?php
require_once __DIR__ . '/../src/db.php';

$gameId = $_GET['id'] ?? null;
if (!$gameId) {
    http_response_code(400);
    echo "Missing game ID.";
    exit;
}

// Hole Spiel
$stmt = $pdo->prepare("SELECT * FROM games WHERE user_id = 1 AND id = ?");
$stmt->execute([$gameId]);
$game = $stmt->fetch(PDO::FETCH_ASSOC);

// Hole zugeordnete Bilder
$stmt = $pdo->prepare("SELECT * FROM images WHERE game_id = ? ORDER BY position ASC");
$stmt->execute([$gameId]);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

$content = __DIR__ . '/../views/games/edit.php';
include __DIR__ . '/../views/layout.php';

