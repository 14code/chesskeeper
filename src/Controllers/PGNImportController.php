<?php

namespace Chesskeeper\Controllers;

use Chesskeeper\Services\PGNParser;
use Chesskeeper\Models\Player;
use Chesskeeper\Models\Tournament;
use Chesskeeper\Models\Game;
use PDO;

class PGNImportController
{
    private PDO $db;
    private PGNParser $parser;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->parser = new PGNParser();
    }

    public function import(string $input): array
    {
        $parsedGames = $this->parser->parse($input);
        $imported = [];

        foreach ($parsedGames as $gameData) {
            // Spieler anlegen oder finden
            $whiteId = Player::findOrCreate($this->db, $gameData['white']);
            $blackId = Player::findOrCreate($this->db, $gameData['black']);

            // Turnier anlegen oder finden
            $tournamentId = null;
            if ($gameData['event']) {
                $tournamentId = Tournament::findOrCreate($this->db, $gameData['event'], $gameData['site'] ?? null);
            }

            // Spiel anlegen
            $gameId = Game::create($this->db, [
                'white_player_id' => $whiteId,
                'black_player_id' => $blackId,
                'result' => $gameData['result'],
                'date' => $gameData['date'],
                'round' => $gameData['round'],
                'pgn' => $gameData['pgn'],
                'tournament_id' => $tournamentId
            ]);

            $imported[] = $gameId;
        }

        return $imported;
    }
}
