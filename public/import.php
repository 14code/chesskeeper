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
        $raw = file_get_contents($_FILES['pgn_file']['tmp_name']);
        if (!mb_detect_encoding($raw, ['UTF-8'], true)) {
            $input = mb_convert_encoding($raw, 'UTF-8', 'Windows-1252'); // oder 'ISO-8859-1'
        } else {
            $input = $raw;
        }
    } elseif (!empty($_POST['pgn_text'])) {
        $input = $_POST['pgn_text'];
        $input = mb_convert_encoding($input, 'UTF-8', 'auto'); // optional, wenn du nichts riskieren willst

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
header('Location: /import');
exit;
