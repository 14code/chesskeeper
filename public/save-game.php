<?php
require_once __DIR__ . '/../src/db.php';

function getOrCreatePlayerId(PDO $pdo, string $name): ?int {
    $name = trim($name);
    if ($name === '') return null;

    $stmt = $pdo->prepare("SELECT id FROM players WHERE user_id = 1 AND name = ?");
    $stmt->execute([$name]);
    $id = $stmt->fetchColumn();
    if ($id) return (int) $id;

    $stmt = $pdo->prepare("INSERT INTO players (user_id, name) VALUES (1, ?)");
    $stmt->execute([$name]);
    return (int) $pdo->lastInsertId();
}

function getOrCreateTournamentId(PDO $pdo, string $name): ?int {
    $name = trim($name);
    if ($name === '') return null;

    $stmt = $pdo->prepare("SELECT id FROM tournaments WHERE user_id = 1 AND name = ?");
    $stmt->execute([$name]);
    $id = $stmt->fetchColumn();
    if ($id) return (int) $id;

    $stmt = $pdo->prepare("INSERT INTO tournaments (user_id, name) VALUES (1, ?)");
    $stmt->execute([$name]);
    return (int) $pdo->lastInsertId();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $date = $_POST['date'] ?? null;
    $white = getOrCreatePlayerId($pdo, $_POST['white_player_name'] ?? '');
    $black = getOrCreatePlayerId($pdo, $_POST['black_player_name'] ?? '');
    $result = $_POST['result'] ?? null;
    
    $tournament = getOrCreateTournamentId($pdo, $_POST['tournament_name'] ?? '');
    
    $pgn = $_POST['pgn'] ?? null;

    $stmt = $pdo->prepare("
        UPDATE games
        SET date = ?, white_player_id = ?, black_player_id = ?, result = ?, tournament_id = ?, pgn = ?
        WHERE id = ? AND user_id = 1
    ");

    $stmt->execute([
        $date,
        $white,
        $black,
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
