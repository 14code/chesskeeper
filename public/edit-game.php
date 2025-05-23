<?php

use Chesskeeper\Controllers\FrontController;

require_once __DIR__ . '/../vendor/autoload.php';

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

$whiteName = null;
$blackName = null;
if (!empty($game['white_player_id'])) {
    $stmt = $pdo->prepare("SELECT name FROM players WHERE id = ?");
    $stmt->execute([$game['white_player_id']]);
    $whiteName = $stmt->fetchColumn();
}
if (!empty($game['black_player_id'])) {
    $stmt = $pdo->prepare("SELECT name FROM players WHERE id = ?");
    $stmt->execute([$game['black_player_id']]);
    $blackName = $stmt->fetchColumn();
}

$tournamentName = null;
if (!empty($game['tournament_id'])) {
    $stmt = $pdo->prepare("SELECT name FROM tournaments WHERE id = ?");
    $stmt->execute([$game['tournament_id']]);
    $tournamentName = $stmt->fetchColumn();
}

$content = __DIR__ . '/../views/games/edit.php';

$controller = new FrontController($pdo);
$controller->container->game = $game;
$controller->container->images = $images;
$controller->container->blackName = $blackName;
$controller->container->whiteName = $whiteName;
$controller->container->tournamentName = $tournamentName;
$controller->show($content);
