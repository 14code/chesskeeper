<?php

use Chesskeeper\Controllers\FrontController;

function routeRequest(FrontController $controller) {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $base = dirname($_SERVER['SCRIPT_NAME']);
    $route = trim(str_replace($base, '', $uri), '/');

    switch ($route) {
        case 'games': $controller->showGameList(); break;
        case 'players': $controller->showPlayerList(); break;
        case 'tournaments': $controller->showTournamentList(); break;
        case 'assign': $controller->showAssignForm(); break;
        case 'import': $controller->showImportForm(); break;
        case 'upload':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->handleUpload();
            } else {
                $controller->showUploadForm();
            }
            break;
        case 'camimport':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->handleCamImport();
            } else {
                $controller->showCamImportForm();
            }
            break;

        case '': $controller->showHome(); break;
        default: http_response_code(404); echo '404 Not Found';
    }
}
