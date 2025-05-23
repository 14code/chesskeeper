<?php

function showTournamentList()
{
    global $pdo;

    $stack = new \Chesskeeper\Services\MessageStack(1);
    $messages = $stack->popAll();

    $stmt = $pdo->query("SELECT id, name, location, start_date FROM tournaments  WHERE user_id = 1 ORDER BY start_date DESC");
    $tournaments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $content = __DIR__ . '/../views/tournaments/list.php';
    include __DIR__ . '/../views/layout.php';
}

function showTournamentForm() {
    $content = __DIR__ . '/../views/tournaments/form.php';
    include __DIR__ . '/../views/layout.php';
}
