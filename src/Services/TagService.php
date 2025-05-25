<?php
namespace Chesskeeper\Services;

use PDO;

class TagService
{
    public function __construct(private PDO $pdo) {}

    public function assignTags(string $type, int $id, array $tags, int $userId = 1): void
    {
        $tags = array_unique($tags);
        foreach ($tags as $tag) {
            $tagId = $this->findOrCreate($tag, $userId);
            $stmt = $this->pdo->prepare("INSERT OR IGNORE INTO tag_assignments (tag_id, entity_type, entity_id, user_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$tagId, $type, $id, $userId]);
        }
    }

    public function findOrCreate(string $name, int $userId): int
    {
        $stmt = $this->pdo->prepare("SELECT id FROM tags WHERE name = ? AND user_id = ?");
        $stmt->execute([$name, $userId]);
        $id = $stmt->fetchColumn();
        if ($id) return (int)$id;

        $stmt = $this->pdo->prepare("INSERT INTO tags (name, user_id) VALUES (?, ?)");
        $stmt->execute([$name, $userId]);
        return (int)$this->pdo->lastInsertId();
    }

    public function getTagsFor(string $type, int $id): array
    {
        $stmt = $this->pdo->prepare("
            SELECT t.name FROM tags t
            JOIN tag_assignments ta ON ta.tag_id = t.id
            WHERE ta.entity_type = ? AND ta.entity_id = ?
            ORDER BY t.name
        ");
        $stmt->execute([$type, $id]);
        $tags = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return array_unique($tags);
    }
}

