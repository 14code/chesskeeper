<?php
function showGameList() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM games WHERE user_id = 1");
    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $content = __DIR__ . '/../views/games/list.php';
    include __DIR__ . '/../views/layout.php';
}

function showGameForm() {
    $content = __DIR__ . '/../views/games/form.php';
    include __DIR__ . '/../views/layout.php';
}
