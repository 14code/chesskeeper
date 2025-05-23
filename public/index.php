<?php
require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/router.php';

use Chesskeeper\Services\MessageStack;

$stack = new MessageStack(1);
foreach ($stack->popAll() as $msg) {
    echo "<p class=\"msg {$msg['type']}\">" . htmlspecialchars($msg['text']) . "</p>";
}

routeRequest();
