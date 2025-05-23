<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PGN Import – Chesskeeper</title>
</head>
<body>
    <h1>Import PGN</h1>

    <form action="/import.php" method="post" enctype="multipart/form-data">
        <label for="pgn_text">Paste PGN:</label><br>
        <textarea name="pgn_text" id="pgn_text" rows="15" cols="80"></textarea><br><br>

        <label for="pgn_file">Or upload PGN file:</label><br>
        <input type="file" name="pgn_file" id="pgn_file" accept=".pgn"><br><br>

        <button type="submit">Import</button>
    </form>
</body>
</html>
