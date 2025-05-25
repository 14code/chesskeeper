<?php
require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/router.php';

use Chesskeeper\Controllers\FrontController;
use Chesskeeper\Services\CommentService;
use Chesskeeper\Services\TagService;

$tagService = new TagService($pdo);
$commentService = new CommentService($pdo);
$controller = new FrontController($pdo, $tagService, $commentService);


routeRequest($controller);
