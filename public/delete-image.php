<?php
require_once __DIR__ . '/../src/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    if (!$id) {
        http_response_code(400);
        echo "Missing image ID.";
        exit;
    }

    // Bildpfad abrufen
    $stmt = $pdo->prepare("SELECT image_url FROM images WHERE id = ? AND user_id = 1");
    $stmt->execute([$id]);
    $path = $stmt->fetchColumn();

    if ($path) {
        $fullPath = __DIR__ . '/../data/users/1/' . $path;
        if (file_exists($fullPath)) {
            unlink($fullPath); // Datei löschen
        }
    }

    // DB-Eintrag löschen
    $stmt = $pdo->prepare("DELETE FROM images WHERE id = ? AND user_id = 1");
    $stmt->execute([$id]);

    // Optional: Zurück zur vorherigen Seite
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/'));
    exit;
} else {
    http_response_code(405);
    echo "Method not allowed.";
}

