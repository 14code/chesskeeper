<?php
function showTournamentList() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM tournaments WHERE user_id = 1");
    $tournaments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $content = __DIR__ . '/../views/tournaments/list.php';
    include __DIR__ . '/../views/layout.php';
}

function showTournamentForm() {
    $content = __DIR__ . '/../views/tournaments/form.php';
    include __DIR__ . '/../views/layout.php';
}
