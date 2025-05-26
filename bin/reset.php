<?php
require_once __DIR__ . '/../src/db.php';

echo "\nCheck environment (bin/reset.php)\n";
echo "CK_PLAYER_NAME = " . getenv('CK_PLAYER_NAME') . PHP_EOL;
echo "CK_USERNAME = " . getenv('CK_USERNAME') . PHP_EOL;
echo "CK_PASSWORD = " . getenv('CK_PASSWORD') . PHP_EOL;


$playerName = getenv('CK_PLAYER_NAME') ?: 'Default Player';
$username   = getenv('CK_USERNAME')     ?: 'default_user';
$password   = getenv('CK_PASSWORD')     ?: bin2hex(random_bytes(6));

// Bestehende Daten löschen
@unlink(__DIR__ . '/../data/chesskeeper.sqlite');

array_map('unlink', glob(__DIR__ . '/../data/users/1/images/*') ?: []);
@rmdir(__DIR__ . '/../data/users/1/images');

array_map('unlink', glob(__DIR__ . '/../data/users/1/pgn/*') ?: []);
@rmdir(__DIR__ . '/../data/users/1/pgn');

@rmdir(__DIR__ . '/../data/users/1');

@rmdir(__DIR__ . '/../data/users');

// DB anlegen
echo "\nCreate database (bin/reset.php)\n";
require __DIR__ . '/install.php';

// Seed ausführen
echo "\nExecute seed (bin/reset.php)\n";
$cmd = sprintf(
    'php bin/seed-user.php --name=%s --password=%s --player="%s"',
    escapeshellarg($username),
    escapeshellarg($password),
    escapeshellarg($playerName)
);

echo shell_exec($cmd);

echo "\nReset abgeschlossen\n";

