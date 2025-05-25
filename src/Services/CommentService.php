<?php
namespace Chesskeeper\Services;

use PDO;

class CommentService
{
    public function __construct(private PDO $pdo) {}

    public function add(string $entityType, int $entityId, string $content, int $userId = 1): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO comments (entity_type, entity_id, content, user_id) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$entityType, $entityId, trim($content), $userId]);
    }

    public function getFor(string $entityType, int $entityId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT content, created 
            FROM comments 
            WHERE entity_type = ? AND entity_id = ?
            ORDER BY created DESC
        ");
        $stmt->execute([$entityType, $entityId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete(int $commentId, int $userId): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM comments 
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$commentId, $userId]);
    }
}
