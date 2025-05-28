<?php
// bin/migrate.php

$dbFile = __DIR__ . '/../data/chesskeeper.sqlite';
$pdo = new PDO('sqlite:' . $dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// helper
function hasColumn(PDO $pdo, string $table, string $column): bool {
    $cols = $pdo->query("PRAGMA table_info($table)")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $col) {
        if ($col['name'] === $column) return true;
    }
    return false;
}

echo "Running migrations…\n";

// Beispiel: tags in games
/*
if (!hasColumn($pdo, 'games', 'tags')) {
    $pdo->exec("ALTER TABLE games ADD COLUMN tags TEXT");
    echo "✔ Added 'tags' column to games\n";
}

// Beispiel: export_flag in players
if (!hasColumn($pdo, 'players', 'export_flag')) {
    $pdo->exec("ALTER TABLE players ADD COLUMN export_flag INTEGER DEFAULT 0");
    echo "✔ Added 'export_flag' column to players\n";
}
*/

echo "Migrations complete.\n";
