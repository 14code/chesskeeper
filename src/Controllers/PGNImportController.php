<?php

namespace Chesskeeper\Controllers;

use Chesskeeper\Services\PGNParser;
use Chesskeeper\Models\Player;
use Chesskeeper\Models\Tournament;
use Chesskeeper\Models\Game;
use PDO;
use RuntimeException;

class PGNImportController
{
    protected PGNParser $parser;

    public function __construct(protected PDO $pdo, protected string $pgnDir)
    {
        if (! is_dir($pgnDir)) {
            throw new RuntimeException('No existing PGN directory ' . $this->pgnDir);
        }
        $this->parser = new PGNParser();
    }

    public function import(string $input): array
    {
        $parsedGames = $this->parser->parse($input);
        $imported = [];

        foreach ($parsedGames as $gameData) {
            // Spieler anlegen oder finden
            $whiteId = Player::findOrCreate($this->pdo, $gameData['white']);
            $blackId = Player::findOrCreate($this->pdo, $gameData['black']);

            // Turnier anlegen oder finden
            $tournamentId = null;
            if ($gameData['event']) {
                $tournamentId = Tournament::findOrCreate($this->pdo, $gameData['event'], $gameData['site'] ?? null);
            }

            // Spiel anlegen
            $gameId = Game::create($this->pdo, [
                'white_player_id' => $whiteId,
                'black_player_id' => $blackId,
                'result' => $gameData['result'],
                'date' => $gameData['date'],
                'round' => $gameData['round'],
                'moves' => $gameData['moves'],
                'tournament_id' => $tournamentId
            ]);

            file_put_contents($this->pgnDir . '/' . $gameId . '.pgn', $gameData['pgn']);

            $imported[] = $gameId;
        }

        return $imported;
    }
}
