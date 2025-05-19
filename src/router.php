<?php
require_once __DIR__ . '/GameController.php';
require_once __DIR__ . '/PlayerController.php';
require_once __DIR__ . '/TournamentController.php';

function routeRequest() {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $base = dirname($_SERVER['SCRIPT_NAME']);
    $route = trim(str_replace($base, '', $uri), '/');

    switch ($route) {
        case 'games': showGameList(); break;
        case 'add-game': showGameForm(); break;
        case 'players': showPlayerList(); break;
        case 'add-player': showPlayerForm(); break;
        case 'tournaments': showTournamentList(); break;
        case 'add-tournament': showTournamentForm(); break;
        case '': include __DIR__ . '/../views/home.php'; break;
        default: http_response_code(404); echo '404 Not Found';
    }
}
