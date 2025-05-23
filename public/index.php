<?php
require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/router.php';

use Chesskeeper\Controllers\FrontController;

$controller = new FrontController($pdo);

routeRequest($controller);
