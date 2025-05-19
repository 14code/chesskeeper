<?php
require_once __DIR__ . '/../src/db.php';

$stmt = $pdo->prepare("SELECT * FROM images WHERE user_id = 1 AND game_id IS NULL ORDER BY id DESC");
$stmt->execute();
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

$content = __DIR__ . '/../views/images/assign.php';
include __DIR__ . '/../views/layout.php';
