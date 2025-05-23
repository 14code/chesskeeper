<h1>Select Images to Create New Game</h1>

<form method="post" action="/create-game.php">
  <div style="display: flex; flex-wrap: wrap; gap: 12px;">
    <?php foreach ($this->images as $image): ?>
      <div style="border: 1px solid #ccc; padding: 8px;">
        <label>
          <input type="checkbox" name="image_ids[]" value="<?= $image['id'] ?>">
          <img src="/image.php?file=<?= htmlspecialchars(basename($image['image_url'])) ?>" style="max-width: 150px; display: block;">
        </label>
      </div>
    <?php endforeach; ?>
  </div>
  <button type="submit">Create Game</button>
</form>
