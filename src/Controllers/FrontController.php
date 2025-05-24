<?php

namespace Chesskeeper\Controllers;

use Chesskeeper\Services\MessageStack;
use PDO;

class FrontController
{
    private int $userId = 1;
    private string $viewDir;

    public object $container;

    public function __construct(private PDO $pdo) {
        $this->viewDir = __DIR__ . '/../../views';
        $this->container = (object)[];
    }


    public function show(string $relativeViewPath)
    {
        $stack = new MessageStack($this->userId);
        $this->container->messages = $stack->popAll();
        extract((array) $this->container);
        $content = $this->viewDir . '/' . $relativeViewPath;
        include $this->viewDir . '/layout.php';
    }


    public function showHome(): void
    {
        $this->show('home.php');
    }

    public function showGameList(): void
    {
        $stmt = $this->pdo->prepare("
            SELECT g.*, 
                   wp.name AS white_name, 
                   bp.name AS black_name,
                   tou.name AS tournament_name
            FROM games g
            LEFT JOIN players wp ON g.white_player_id = wp.id
            LEFT JOIN players bp ON g.black_player_id = bp.id
            LEFT JOIN tournaments tou ON g.tournament_id = tou.id
            WHERE g.user_id = ?
            ORDER BY g.date DESC
        ");
        $stmt->execute([$this->userId]);
        $this->container->games = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->show('games/list.php');
    }

    public function showGameForm(): void
    {
        $this->show('games/edit.php');
    }

    public function showImportForm(): void
    {
        $this->show('import/form.php');
    }

    public function showPlayerList(): void
    {
        $stmt = $this->pdo->prepare("SELECT * FROM players WHERE user_id = ? ORDER BY name COLLATE NOCASE");
        $stmt->execute([$this->userId]);
        $this->container->players = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->show('players/list.php');
    }

    public function showPlayerForm(): void
    {
        $this->show('players/form.php');
    }

    public function showTournamentList(): void
    {
        $stmt = $this->pdo->prepare("SELECT id, name, location, start_date FROM tournaments WHERE user_id = ? ORDER BY start_date DESC");
        $stmt->execute([$this->userId]);
        $this->container->tournaments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->show('tournaments/list.php');
    }

    public function showTournamentForm(): void
    {
        $this->show('tournaments/form.php');
    }

    public function showAssignForm(): void
    {
        $stmt = $this->pdo->prepare("SELECT * FROM images WHERE user_id = ? AND game_id IS NULL ORDER BY id DESC");
        $stmt->execute([$this->userId]);
        $this->container->images = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->show('images/assign.php');
    }

    public function showUploadForm(): void
    {
        $this->show('upload/form.php');
    }

    public function showQuickUploadForm(): void
    {
        $this->show('games/quick-upload.php');
    }
}
