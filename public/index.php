<?php
session_start();

$userId = $_SESSION['user_id'] ?? null;
if (!$userId && $_SERVER['REQUEST_URI'] !== '/login' && $_SERVER['REQUEST_URI'] !== '/do-login') {
    header('Location: /login');
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/router.php';

use Chesskeeper\Services\UserService;
use Chesskeeper\Controllers\FrontController;
use Chesskeeper\Services\CommentService;
use Chesskeeper\Services\TagService;

$appRoot = dirname(__DIR__);

$tagService = new TagService($pdo);
$commentService = new CommentService($pdo);
$userService = new UserService($pdo);

$controller = new FrontController($appRoot, $pdo, $tagService, $commentService, $userService);
$controller->setUserId($userId ?? 0); // 1 statt 0 als fallback für Entwicklung

routeRequest($controller);
