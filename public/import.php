<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/db.php';


use Chesskeeper\Controllers\PGNImportController;
use Chesskeeper\Services\MessageStack;

$stack = new MessageStack(1);

$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = '';

    if (!empty($_FILES['pgn_file']['tmp_name'])) {
        $input = file_get_contents($_FILES['pgn_file']['tmp_name']);
    } elseif (!empty($_POST['pgn_text'])) {
        $input = $_POST['pgn_text'];
    }

    if (trim($input) !== '') {
        try {
            $controller = new PGNImportController($pdo);
            $importedIds = $controller->import($input);
            $success = count($importedIds) . " game(s) imported successfully.";
            $stack->push('success', count($importedIds) . ' game(s) imported.');
            header('Location: /games');
            exit;
        } catch (Exception $e) {
            $stack->push('error', 'Import failed: ' . $e->getMessage());
        }
    } else {
        $error = "No PGN input provided.";
    }
}


$content = __DIR__ . '/../views/import/form.php';
include __DIR__ . '/../views/layout.php';
