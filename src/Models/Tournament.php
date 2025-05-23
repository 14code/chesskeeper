<?php
namespace Chesskeeper\Models;

use PDO;

class Tournament
{
    public static function findOrCreate(PDO $db, string $name, ?string $site = null): int
    {
        $stmt = $db->prepare("SELECT id FROM tournaments WHERE name = :name");
        $stmt->execute([':name' => $name]);
        $id = $stmt->fetchColumn();

        if ($id) {
            return (int)$id;
        }

        $stmt = $db->prepare("INSERT INTO tournaments (name, location, user_id) VALUES (:name, :location, 1)");
        $stmt->execute([
            ':name' => $name,
            ':location' => $site
        ]);

        return (int)$db->lastInsertId();
    }
}
