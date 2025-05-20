<?php
function showGameList() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM games WHERE user_id = 1");
    $stmt = $pdo->query("
      SELECT g.*, 
             wp.name AS white_name, 
             bp.name AS black_name,
             tou.name AS tournament_name
      FROM games g
      LEFT JOIN players wp ON g.white_player_id = wp.id
      LEFT JOIN players bp ON g.black_player_id = bp.id
      LEFT JOIN tournaments tou ON g.tournament_id = tou.id
      WHERE g.user_id = 1
      ORDER BY g.date DESC
    ");

    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $content = __DIR__ . '/../views/games/list.php';
    include __DIR__ . '/../views/layout.php';
}

function showGameForm() {
    $content = __DIR__ . '/../views/games/form.php';
    include __DIR__ . '/../views/layout.php';
}
