<?php

use Chesskeeper\Services\MessageStack;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/db.php';

$stack = new MessageStack(1);

$uploadDir = __DIR__ . '/../data/users/1/images/';

// Verzeichnis sicherstellen
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0775, true)) {
        die("Failed to create upload directory: $uploadDir");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
        $originalName = $_FILES['images']['name'][$index];
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        $hash = md5_file($tmpName);
        $filename = $hash . '.' . $ext;
        $destination = $uploadDir . $filename;

        if (!move_uploaded_file($tmpName, $destination)) {
            $stack->push('error', 'Failed to upload ' . $originalName);
            continue;
        }

        // Insert into DB
        $stmt = $pdo->prepare("INSERT INTO images (user_id, game_id, image_url, position) VALUES (1, NULL, ?, 0)");
        $stmt->execute(["users/1/images/" . $filename]);

        $stack->push('success', $originalName);
    }
}

header('Location: /assign');
exit;