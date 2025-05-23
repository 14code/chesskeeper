<?php

namespace Chesskeeper\Controllers;

use Chesskeeper\Services\MessageStack;
use PDO;

class FrontController
{
    public object|null $container = null;
    
    protected array $games = [];
    protected array $images = [];
    protected array $messages = [];
    protected array $players = [];
    protected array $tournaments = [];
    
    public function __construct(private PDO $pdo) {
        $this->container = (object) [];
    }

    public function showAssignForm()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM images WHERE user_id = 1 AND game_id IS NULL ORDER BY id DESC");
        $stmt->execute();
        $this->images = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $content = __DIR__ . '/../../views/images/assign.php';
        $this->show($content);
    }

    public function show(string $content)
    {
        $stack = new MessageStack(1);
        $this->messages = $stack->popAll();
        
        include __DIR__ . '/../../views/layout.php';
    }

    public function showHome(): void
    {
        $content = __DIR__ . '/../../views/home.php';
        $this->show($content);
    }

    public function showGameList(): void
    {
        $stmt = $this->pdo->query("
      SELECT g.*, 
             wp.name AS white_name, 
             bp.name AS black_name,
             tou.name AS tournament_name
      FROM games g
      LEFT JOIN players wp ON g.white_player_id = wp.id
      LEFT JOIN players bp ON g.black_player_id = bp.id
      LEFT JOIN tournaments tou ON g.tournament_id = tou.id
      WHERE g.user_id = 1
      ORDER BY g.date DESC
    ");
        $this->games = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $content = __DIR__ . '/../../views/games/list.php';
        $this->show($content);
    }

    public function showImportForm()
    {
        $content = __DIR__ . '/../../views/import/form.php';
        $this->show($content);
    }

    public function showPlayerList(): void
    {
        
        $stmt = $this->pdo->query("SELECT * FROM players WHERE user_id = 1 ORDER BY name COLLATE NOCASE");
        $this->players = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $content = __DIR__ . '/../../views/players/list.php';
        $this->show($content);
    }

    public function showTournamentList(): void
    {
        $stmt = $this->pdo->query("SELECT id, name, location, start_date FROM tournaments  WHERE user_id = 1 ORDER BY start_date DESC");
        $this->tournaments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $content = __DIR__ . '/../../views/tournaments/list.php';
        $this->show($content);
    }

    public function showGameForm() {
        $content = __DIR__ . '/../../views/games/edit.php';
        $this->show($content);
    }

    public function showPlayerForm() {
        $content = __DIR__ . '/../../views/players/form.php';
        $this->show($content);
    }

    function showTournamentForm() {
        $content = __DIR__ . '/../../views/tournaments/form.php';
        $this->show($content);
    }

    public function showUploadForm()
    {
        $content = __DIR__ . '/../../views/upload/form.php';
        $this->show($content);
    }

}

