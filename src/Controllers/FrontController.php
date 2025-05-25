<?php

namespace Chesskeeper\Controllers;

use Chesskeeper\Services\MessageStack;
use PDO;

class FrontController
{
    private int $userId = 1;
    private string $viewDir;
    private string $imageDir;

    public object $container;

    public function __construct(private PDO $pdo) {
        $this->viewDir = __DIR__ . '/../../views';
        $this->imageDir = __DIR__ . "/../../data/users/{$this->userId}/images";
        if (!is_dir($this->imageDir)) {
            mkdir($this->imageDir, 0775, true);
        }
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

    public function handleUpload(): void
    {
        $stack = new MessageStack($this->userId);
        $success = [];
        $errors = [];

        if (!empty($_FILES['images']['tmp_name'])) {
            foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
                $error = $_FILES['images']['error'][$i] ?? UPLOAD_ERR_NO_FILE;

                if ($error !== UPLOAD_ERR_OK) {
                    $errors[] = "Fehler beim Hochladen von Datei $i (Fehlercode $error)";
                    continue;
                }

                if (!is_uploaded_file($tmp)) {
                    $errors[] = "Temporäre Datei $i ist ungültig oder fehlt.";
                    continue;
                }

                $hash = md5_file($tmp);
                $ext = pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION);
                $filename = $hash . '.' . strtolower($ext);

                $targetPath = $this->imageDir . '/' . $filename;

                if (!move_uploaded_file($tmp, $targetPath)) {
                    $errors[] = "Datei $i konnte nicht gespeichert werden.";
                    continue;
                }

                $stmt = $this->pdo->prepare("INSERT OR IGNORE INTO images (image_url, user_id, position) VALUES (?, ?, 0)");
                $stmt->execute([$filename, $this->userId]);

                $success[] = $_FILES['images']['name'][$i];
            }

            foreach ($errors as $e) {
                $stack->push('error', $e);
            }

            if (!empty($success)) {
                $stack->push('success', count($success) . ' Bild(er) erfolgreich hochgeladen.');
            }

            header('Location: /upload');
            exit;
        }

        $stack->push('error', 'Keine Datei(en) ausgewählt.');
        header('Location: /upload');
        exit;
    }

}
