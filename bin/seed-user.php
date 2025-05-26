#!/usr/bin/env php
<?php
require_once __DIR__ . '/../src/db.php';

function generateRandomString(int $length = 12): string
{
    return substr(bin2hex(random_bytes($length)), 0, $length);
}

// Argumente parsen
$options = getopt('', ['name::', 'password::', 'player::']);

$username = $options['name'] ?? 'user_' . generateRandomString(6);
$password = $options['password'] ?? generateRandomString(12);
$playerName = $options['player'] ?? 'User ' . ucfirst($username);

$hash = password_hash($password, PASSWORD_DEFAULT);

// User anlegen
$pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)")
    ->execute([$username, $hash]);

$userId = $pdo->lastInsertId();

// Player anlegen
$pdo->prepare("INSERT INTO players (name, user_id) VALUES (?, ?)")
    ->execute([$playerName, $userId]);

$playerId = $pdo->lastInsertId();

// User aktualisieren mit player_id
$pdo->prepare("UPDATE users SET player_id = ? WHERE id = ?")
    ->execute([$playerId, $userId]);

// Ausgabe
echo "✅ User + Player erfolgreich angelegt:\n";
echo "Username: $username\n";
echo "Password: $password\n";
echo "Player:  $playerName\n";
