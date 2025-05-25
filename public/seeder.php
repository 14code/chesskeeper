<?php
exit;

require_once __DIR__ . '/../src/db.php';

function generateRandomString(int $length = 12): string
{
    return substr(bin2hex(random_bytes($length)), 0, $length);
}

$randomName = generateRandomString(6);
$userName = 'user_' . $randomName;
$password = generateRandomString(12);

$hash = password_hash($password, PASSWORD_DEFAULT);
$createUserStmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
$createUserStmt->execute([$userName, $hash]);
$userId = $pdo->lastInsertId();

$createPlayerStmt = $pdo->prepare("INSERT INTO players (name, user_id) VALUES (?, ?)");
$createPlayerStmt->execute(['User ' . $randomName, $userId]);

$updateUserStmt = $pdo->prepare("UPDATE users SET player_id = ? WHERE id = ?");
$updateUserStmt->execute([$pdo->lastInsertId(), $userId]);

echo "User + Player erfolgreich angelegt\n";
echo "Username: $userName\n";
echo "Password: $password\n";
