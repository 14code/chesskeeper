<h1>Edit Game #<?= htmlspecialchars($game['id']) ?></h1>

<form method="post" action="/game">
  <input type="hidden" name="id" value="<?= $game['id'] ?>">

  <label>Date:<br>
    <input type="date" name="date" value="<?= htmlspecialchars($game['date']) ?>">
  </label><br><br>

  <label>White Player:<br>
    <input type="text" name="white" value="<?= htmlspecialchars($game['white_name'] ?? '') ?>">
  </label><br><br>

  <label>Black Player:<br>
    <input type="text" name="black" value="<?= htmlspecialchars($game['black_name'] ?? '') ?>">
  </label><br><br>

  <label>Result:<br>
    <select name="result">
      <option value="0" <?= $game['result'] == 0 ? 'selected' : '' ?>>?</option>
      <option value="1" <?= $game['result'] == 1 ? 'selected' : '' ?>>1–0</option>
      <option value="0.5" <?= $game['result'] == 0.5 ? 'selected' : '' ?>>½–½</option>
      <option value="-1" <?= $game['result'] == -1 ? 'selected' : '' ?>>0–1</option>
    </select>
  </label><br><br>

  <label>Tournament Name:<br>
    <input type="text" name="tournament" value="<?= htmlspecialchars($game['tournament_name'] ?? '') ?>">
  </label><br><br>

  <label>Round:<br>
    <input type="number" name="round" value="<?= htmlspecialchars($game['round'] ?? '') ?>">
  </label><br><br>


  <label>Moves:<br>
    <textarea name="moves" rows="10" cols="80"><?= htmlspecialchars($game['moves']) ?></textarea>
  </label><br><br>

    <label for="tags">Tags (kommagetrennt):</label><br>
    <input type="text" name="tags" id="tags"
           value="<?= htmlspecialchars(implode(', ', $tags ?? [])) ?>"
           placeholder="z. B. Taktik, Favorit, Endspiel"><br><br>


    <?php if (!empty($comments)): ?>
        <fieldset>
            <legend>Bisherige Kommentare</legend>
            <ul>
                <?php foreach ($comments as $comment): ?>
                    <li>
                        <small><?= htmlspecialchars($comment['created']) ?></small><br>
                        <?= nl2br(htmlspecialchars($comment['content'])) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </fieldset>
    <?php endif; ?>

    <fieldset>
        <legend>Neuer Kommentar</legend>
        <textarea name="comment" rows="4" placeholder="Kommentar hinzufügen..."></textarea>
    </fieldset>



    <button type="submit">Save</button>
</form>

<hr>

<h2>Associated Images</h2>
<div style="display: flex; flex-wrap: wrap; gap: 12px;">
  <?php foreach ($images as $img): ?>
    <div style="border: 1px solid #ccc; padding: 4px;">
      <a href="/image.php?file=<?= htmlspecialchars(basename($img['image_url'])) ?>" target="_blank">
        <img src="/image.php?file=<?= htmlspecialchars(basename($img['image_url'])) ?>" style="max-width: 200px;">
      </a>
      <div style="text-align: center;">Position <?= $img['position'] ?></div>
    </div>
      <form method="post" action="/delete-image.php" onsubmit="return confirm('Delete this image?');">
          <input type="hidden" name="id" value="<?= $img['id'] ?>">
          <button type="submit" style="font-size: small;">🗑️</button>
      </form>

  <?php endforeach; ?>
</div>

<hr>
<form method="post" action="/delete-game.php" onsubmit="return confirm('Really delete this game?');">
  <input type="hidden" name="id" value="<?= $game['id'] ?>">
  <button type="submit" style="color: red;">Delete Game</button>
</form>
