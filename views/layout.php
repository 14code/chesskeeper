<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Chesskeeper</title>
</head>
<body>
    <nav>
        <a href="/">Home</a> |
        <a href="/games">Games</a> |
        <a href="/players">Players</a> |
        <a href="/tournaments">Tournaments</a> |
        <a href="/assign">Assign</a> |
        <a href="/upload">Upload</a> |
        <a href="/import">Import</a> |
        <a href="/camimport">Kamera</a>
    </nav>
    
    
    <?php foreach ($messages as $message): ?>
        <p class="msg <?= htmlspecialchars($message['type']) ?>">
            <?= htmlspecialchars($message['text']) ?>
        </p>
    <?php endforeach; ?>
    
    <hr>
    
    <?php include $content; ?>
</body>
</html>
