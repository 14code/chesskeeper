<?php
function showPlayerList() {
    global $pdo;

    $stack = new \Chesskeeper\Services\MessageStack(1);
    $messages = $stack->popAll();
    
    $stmt = $pdo->query("SELECT * FROM players WHERE user_id = 1 ORDER BY name COLLATE NOCASE");
    $players = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $content = __DIR__ . '/../views/players/list.php';
    include __DIR__ . '/../views/layout.php';
}

function showPlayerForm() {
    $content = __DIR__ . '/../views/players/form.php';
    include __DIR__ . '/../views/layout.php';
}
