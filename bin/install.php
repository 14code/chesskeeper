<?php
$dbFile = __DIR__ . '/../data/chesskeeper.sqlite';
$schemaFile = __DIR__ . '/../sql/schema.sql';

if (file_exists($dbFile)) {
    echo "Database already exists at {$dbFile}\n";
    exit;
}

try {
    $pdo = new PDO('sqlite:' . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $schema = file_get_contents($schemaFile);
    $pdo->exec($schema);
    echo "Database created successfully.\n";
} catch (PDOException $e) {
    die("DB creation failed: " . $e->getMessage());
}
