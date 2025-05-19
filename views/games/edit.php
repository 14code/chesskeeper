<h1>Edit Game #<?= htmlspecialchars($game['id']) ?></h1>

<form method="post" action="/save-game.php">
    <input type="hidden" name="id" value="<?= $game['id'] ?>">

    <label>Date:<br>
        <input type="date" name="date" value="<?= htmlspecialchars($game['date']) ?>">
    </label><br><br>

    <label>White Player ID:<br>
        <input type="number" name="white_player_id" value="<?= htmlspecialchars($game['white_player_id']) ?>">
    </label><br><br>

    <label>Black Player ID:<br>
        <input type="number" name="black_player_id" value="<?= htmlspecialchars($game['black_player_id']) ?>">
    </label><br><br>

    <label>Result:<br>
        <select name="result">
            <option value="1" <?= $game['result'] == 1 ? 'selected' : '' ?>>1–0</option>
            <option value="0.5" <?= $game['result'] == 0.5 ? 'selected' : '' ?>>½–½</option>
            <option value="-1" <?= $game['result'] == -1 ? 'selected' : '' ?>>0–1</option>
        </select>
    </label><br><br>

    <label>Tournament ID:<br>
        <input type="number" name="tournament_id" value="<?= htmlspecialchars($game['tournament_id']) ?>">
    </label><br><br>

    <label>PGN:<br>
        <textarea name="pgn" rows="10" cols="80"><?= htmlspecialchars($game['pgn']) ?></textarea>
    </label><br><br>

    <button type="submit">Save</button>
</form>

<hr>

<h2>Associated Images</h2>
<div style="display: flex; flex-wrap: wrap; gap: 12px;">
    <?php foreach ($images as $img): ?>
        <div style="border: 1px solid #ccc; padding: 4px;">
            <img src="/image.php?file=<?= htmlspecialchars(basename($img['image_url'])) ?>" style="max-width: 200px;">
            <div style="text-align: center;">Position <?= $img['position'] ?></div>
        </div>
    <?php endforeach; ?>
</div>
