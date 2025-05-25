<?php

namespace Chesskeeper\Controllers;

use Chesskeeper\Services\CommentService;
use Chesskeeper\Services\MessageStack;
use Chesskeeper\Services\TagService;
use Chesskeeper\Services\UserService;
use PDO;

class FrontController
{
    protected int $userId = 0;

    public object $container;

    public function __construct(
        protected string         $appRoot,
        protected PDO            $pdo,
        protected TagService     $tagService,
        protected CommentService $commentService,
        protected UserService    $userService
    ) {
        $this->container = (object)[];
    }

    /**
     * @return string
     */
    public function getAppRoot(): string
    {
        return $this->appRoot;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }


    public function show(string $relativeViewPath)
    {
        $stack = new MessageStack($this->getUserId());
        $this->container->messages = $stack->popAll();
        extract((array) $this->container);
        $viewDir = $this->buildViewDir();
        $content = $viewDir . '/' . $relativeViewPath;
        include $viewDir . '/layout.php';
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
        $stmt->execute([$this->getUserId()]);
        $this->container->games = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->show('games/list.php');
    }

    public function showImportForm(): void
    {
        $this->show('import/form.php');
    }

    public function showPlayerList(): void
    {
        $stmt = $this->pdo->prepare("SELECT * FROM players WHERE user_id = ? ORDER BY name COLLATE NOCASE");
        $stmt->execute([$this->getUserId()]);
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
        $stmt->execute([$this->getUserId()]);
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
        $stmt->execute([$this->getUserId()]);
        $this->container->images = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->show('images/assign.php');
    }

    public function showUploadForm(): void
    {
        $this->show('upload/form.php');
    }


    public function handleUpload(): void
    {
        $stack = new MessageStack($this->getUserId());
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

                $targetPath = $this->makeImageDir() . '/' . $filename;

                if (!move_uploaded_file($tmp, $targetPath)) {
                    $errors[] = "Datei $i konnte nicht gespeichert werden.";
                    continue;
                }

                $stmt = $this->pdo->prepare("INSERT OR IGNORE INTO images (image_url, user_id, position) VALUES (?, ?, 0)");
                $stmt->execute([$filename, $this->getUserId()]);

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

    public function showCamImportForm(): void
    {
        $content = 'images/camimport.php';
        $this->show($content);
    }


    public function handleCamImport(): void
    {
        $whiteId = null;
        $blackId = null;
        $tournamentId = null;

        if (!empty($_POST['white'])) {
            $whiteId = \Chesskeeper\Models\Player::findOrCreate($this->pdo, trim($_POST['white']));
        }

        if (!empty($_POST['black'])) {
            $blackId = \Chesskeeper\Models\Player::findOrCreate($this->pdo, trim($_POST['black']));
        }

        if (!empty($_POST['event'])) {
            $tournamentId = \Chesskeeper\Models\Tournament::findOrCreate($this->pdo, trim($_POST['event']));
        }

        $gameId = \Chesskeeper\Models\Game::create($this->pdo, [
            'white_player_id' => $whiteId,
            'black_player_id' => $blackId,
            'tournament_id'   => $tournamentId,
            'result'          => ($_POST['result'] !== '') ? (float) $_POST['result'] : null,
            'date'            => $_POST['date'] ?? date('Y-m-d'),
            'round'           => $_POST['round'] ?? null,
            'moves'           => '',
        ]);

        $position = 1;
        foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
            if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                $filename = uniqid('img_') . '.jpg';
                move_uploaded_file($tmp, $this->makeImageDir() . '/' . $filename);

                $stmt = $this->pdo->prepare("INSERT INTO images (image_url, game_id, user_id, position) VALUES (?, ?, $this->getUserId(), $position)");
                $stmt->execute([$filename, $gameId]);
                $position++;
            }
        }

        $stack = new MessageStack(1);
        $stack->push('success', 'Partie erfolgreich erstellt und Bilder zugeordnet.');

        header('Location: /games');
        exit;
    }

    public function showGameForm(): void
    {
        $id = $_GET['id'] ?? null;

        if (!$id || !ctype_digit($id)) {
            http_response_code(400);
            echo 'Ungültige oder fehlende Spiel-ID.';
            return;
        }

        // Spiel mit Spielernamen und Turniernamen laden
        $stmt = $this->pdo->prepare("
        SELECT g.*, 
               wp.name AS white_name,
               bp.name AS black_name,
               t.name AS tournament_name
        FROM games g
        LEFT JOIN players wp ON g.white_player_id = wp.id
        LEFT JOIN players bp ON g.black_player_id = bp.id
        LEFT JOIN tournaments t ON g.tournament_id = t.id
        WHERE g.id = ? AND g.user_id = ?
    ");
        $stmt->execute([$id, $this->getUserId()]);
        $game = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$game) {
            http_response_code(404);
            echo 'Spiel nicht gefunden.';
            return;
        }

        // Dropdown-Listen vorbereiten
        $players = $this->pdo->prepare("SELECT id, name FROM players WHERE user_id = ? ORDER BY name COLLATE NOCASE");
        $players->execute([$this->getUserId()]);
        $tournaments = $this->pdo->prepare("SELECT id, name FROM tournaments WHERE user_id = ? ORDER BY start_date DESC");
        $tournaments->execute([$this->getUserId()]);

// Hole zugeordnete Bilder
        $stmt = $this->pdo->prepare("SELECT * FROM images WHERE game_id = ? ORDER BY position ASC");
        $stmt->execute([$id]);
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Tags & Kommentare
        $tags = $this->tagService->getTagsFor('game', $id);
        print_r($tags);
        $comments = $this->commentService->getFor('game', $id);

        // An View übergeben
        $this->container->game = $game;
        $this->container->players = $players->fetchAll(PDO::FETCH_ASSOC);
        $this->container->tournaments = $tournaments->fetchAll(PDO::FETCH_ASSOC);
        $this->container->images = $images;
        $this->container->tags = $tags;
        $this->container->comments = $comments;

        $this->show('games/edit.php');
    }

    public function handleGameSave(): void
    {
        $id = $_POST['id'] ?? null;

        if (!$id || !ctype_digit($id)) {
            http_response_code(400);
            echo 'Ungültige oder fehlende Spiel-ID.';
            return;
        }

        // Spieler/Turnier ggf. erzeugen
        $whiteId = \Chesskeeper\Models\Player::findOrCreate($this->pdo, trim($_POST['white']));
        $blackId = \Chesskeeper\Models\Player::findOrCreate($this->pdo, trim($_POST['black']));
        $tournamentId = !empty($_POST['tournament'])
            ? \Chesskeeper\Models\Tournament::findOrCreate($this->pdo, trim($_POST['tournament']))
            : null;

        $stmt = $this->pdo->prepare("
        UPDATE games SET 
            white_player_id = ?, 
            black_player_id = ?, 
            tournament_id = ?, 
            result = ?, 
            date = ?, 
            round = ?, 
            moves = ?
        WHERE id = ? AND user_id = ?
    ");
        $stmt->execute([
            $whiteId,
            $blackId,
            $tournamentId,
            $_POST['result'] !== '' ? (float) $_POST['result'] : null,
                $_POST['date'] ?? null,
                $_POST['round'] ?? null,
                $_POST['moves'] ?? null,
            $id,
            $this->getUserId()
        ]);

        // Tags zuweisen
        if (!empty($_POST['tags'])) {
            $tags = array_filter(array_map('trim', explode(',', $_POST['tags'])));
            $this->tagService->assignTags('game', $id, $tags, $this->getUserId());
        }

        // Kommentar speichern
        if (!empty($_POST['comment'])) {
            $this->commentService->add('game', $id, $_POST['comment'], $this->getUserId());
        }

        $stack = new MessageStack($this->getUserId());
        $stack->push('success', 'Partie gespeichert.');

        header("Location: /game?id=$id");
        exit;
    }

    public function handleLogin(): void
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userService->verify($username, $password);

        if ($user) {
            $userId = $user['id'];
            $this->setUserId($userId);
            $_SESSION['user_id'] = $userId;
            $stack = new MessageStack($userId);
            $stack->push('success', 'Login erfolgreich.');
            header('Location: /');
            exit;
        }

        $stack = new MessageStack(0);
        $stack->push('error', 'Login fehlgeschlagen.');
        header('Location: /login');
        exit;
    }

    /**
     * @return string
     */
    public function makeImageDir(): string
    {
        $imageDir = $this->getAppRoot() . '/data/users/' . $this->getUserId() . '/images';
        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0775, true);
        }
        return $imageDir;
    }

    /**
     * @return string
     */
    public function buildViewDir(): string
    {
        return $this->getAppRoot() . '/views';
    }

    public function showLoginForm()
    {
        $content = 'login.php';
        $this->show($content);
    }


}
