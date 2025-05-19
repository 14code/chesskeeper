<?php
function showPlayerList() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM players WHERE user_id = 1");
    $players = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $content = __DIR__ . '/../views/players/list.php';
    include __DIR__ . '/../views/layout.php';
}

function showPlayerForm() {
    $content = __DIR__ . '/../views/players/form.php';
    include __DIR__ . '/../views/layout.php';
}
