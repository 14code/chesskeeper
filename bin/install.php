<?php
$dbFile = __DIR__ . '/../data/chesskeeper.sqlite';
$schema = __DIR__ . '/../sql/schema.sql';

if (file_exists($dbFile)) {
    echo "Database already exists at $dbFile\n";
    exit;
}

try {
    $pdo = new PDO('sqlite:' . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = file_get_contents($schema);
    $pdo->exec($sql);
    echo "Database created successfully at $dbFile\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
