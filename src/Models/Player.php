<?php
namespace Chesskeeper\Models;

use PDO;

class Player
{
    public static function findOrCreate(PDO $db, string $name): int
    {
        $stmt = $db->prepare("SELECT id FROM players WHERE name = :name");
        $stmt->execute([':name' => $name]);
        $id = $stmt->fetchColumn();

        if ($id) {
            return (int)$id;
        }

        $stmt = $db->prepare("INSERT INTO players (name, user_id) VALUES (:name, 1)");
        $stmt->execute([':name' => $name]);

        return (int)$db->lastInsertId();
    }
}
