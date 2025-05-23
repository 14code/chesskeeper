<?php
namespace Chesskeeper\Models;

use PDO;

class Game
{
    public static function create(PDO $db, array $data): int
    {
        $stmt = $db->prepare("
            INSERT INTO games (
                white_player_id, black_player_id, result, date, round, pgn, tournament_id, user_id
            ) VALUES (
                :white_player_id, :black_player_id, :result, :date, :round, :pgn, :tournament_id, 1
            )
        ");

        $stmt->execute([
            ':white_player_id' => $data['white_player_id'],
            ':black_player_id' => $data['black_player_id'],
            ':result' => $data['result'],
            ':date' => $data['date'],
            ':round' => $data['round'],
            ':pgn' => $data['pgn'],
            ':tournament_id' => $data['tournament_id'],
        ]);

        return (int)$db->lastInsertId();
    }
}
