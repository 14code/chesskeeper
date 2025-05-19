<?php
// Nur user_id = 1 – später ggf. per Session o.ä.
$userId = 1;
$relative = $_GET['file'] ?? '';
$cleanPath = basename($relative); // nur Dateiname
$path = __DIR__ . "/../data/users/{$userId}/images/{$cleanPath}";

if (!file_exists($path)) {
    http_response_code(404);
    exit("Not found");
}

$ext = pathinfo($path, PATHINFO_EXTENSION);
$mime = match (strtolower($ext)) {
    'jpg', 'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
    default => 'application/octet-stream',
};

header("Content-Type: $mime");
readfile($path);
exit;

