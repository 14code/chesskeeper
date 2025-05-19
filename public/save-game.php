<?php
require_once __DIR__ . '/../src/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $date = $_POST['date'] ?? null;
    $white = $_POST['white_player_id'] ?? null;
    $black = $_POST['black_player_id'] ?? null;
    $result = $_POST['result'] ?? null;
    $tournament = $_POST['tournament_id'] ?? null;
    $pgn = $_POST['pgn'] ?? null;

    $stmt = $pdo->prepare("
        UPDATE games
        SET date = ?, white_player_id = ?, black_player_id = ?, result = ?, tournament_id = ?, pgn = ?
        WHERE id = ? AND user_id = 1
    ");

    $stmt->execute([
        $date,
        $white !== '' ? $white : null,
        $black !== '' ? $black : null,
        $result,
        $tournament !== '' ? $tournament : null,
        $pgn,
        $id
    ]);

    header("Location: /edit-game.php?id=" . $id);
    exit;
} else {
    http_response_code(405);
    echo "Method not allowed.";
}

