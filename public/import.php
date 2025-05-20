<?php

require_once __DIR__ . '/../config/config.php';

use Chesskeeper\Controllers\PGNImportController;

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
            $controller = new PGNImportController($db);
            $importedIds = $controller->import($input);
            $success = count($importedIds) . " game(s) imported successfully.";
        } catch (Exception $e) {
            $error = "Error during import: " . $e->getMessage();
        }
    } else {
        $error = "No PGN input provided.";
    }
}


$content = __DIR__ . '/../views/import/form.php';
include __DIR__ . '/../views/layout.php';
