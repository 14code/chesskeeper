<h1>Edit Game #<?= htmlspecialchars($game['id']) ?></h1>

<div style="display: flex; flex-wrap: wrap; gap: 20px; align-items: start;">
<?php
$rawDate = $game['date'] ?? '';
$isoDate = preg_match('/^\d{4}\.\d{2}\.\d{2}$/', $rawDate)
    ? str_replace('.', '-', $rawDate) // → '2024-05-31'
    : '';
?>

    <!-- Left Column: Metadata + Form -->
    <div style="flex: 1; min-width: 300px; max-width: 400px;">
        <form method="post" action="/game">
            <input type="hidden" name="id" value="<?= $game['id'] ?>">

            <label>Date:<br>
                <input type="date" name="date" value="<?= htmlspecialchars($isoDate) ?>">
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
                <textarea name="moves" id="pgnInput" rows="10" cols="50"><?= htmlspecialchars($game['moves']) ?></textarea>
            </label><br><br>

            <label for="tags">Tags (comma separated):</label><br>
            <input type="text" name="tags" id="tags"
                   value="<?= htmlspecialchars(implode(', ', $tags ?? [])) ?>"
                   placeholder="e.g. tactics, favorite, endgame"><br><br>

            <?php if (!empty($comments)): ?>
                <fieldset>
                    <legend>Comments</legend>
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
                <legend>New Comment</legend>
                <textarea name="comment" rows="4" placeholder="Add a comment..."></textarea>
            </fieldset>

            <button type="submit">Save</button>
        </form>
    </div>

    <!-- Right Column: PGN Recorder -->
    <div style="flex: 1; min-width: 300px;">
        <?php include __DIR__ . '/../partials/record-board.php'; ?>
    </div>

    <!-- Inline Viewer -->
    <div style="flex: 1;">
        <div id="viewer-container" style="max-width: 420px; height: 600px; border: 1px solid #ccc;">
            <ul id="image-list" style="display: none;">
                <?php foreach ($images as $img): ?>
                    <li>
                        <img
                                src="/image.php?file=<?= htmlspecialchars(basename($img['image_url'])) ?>"
                                alt="Position <?= $img['position'] ?>"
                                data-title="Position <?= $img['position'] ?>"
                                style="max-width: 100%;"
                        >
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    
</div>

<hr>

<h2>Associated Images</h2>

<div id="image-gallery" style="display: flex; flex-wrap: wrap; gap: 12px;">
    <?php foreach ($images as $img): ?>
        <div>
            <img
                    src="/image.php?file=<?= htmlspecialchars(basename($img['image_url'])) ?>"
                    data-title="Position <?= $img['position'] ?>"
                    style="max-width: 200px; cursor: zoom-in;"
            >
            <form method="post" action="/delete-image.php" onsubmit="return confirm('Delete this image?');">
                <input type="hidden" name="id" value="<?= $img['id'] ?>">
                <button type="submit" style="font-size: small;">🗑️</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<script src="/vendor/viewerjs/viewer.min.js"></script>
<link rel="stylesheet" href="/vendor/viewerjs/viewer.min.css">

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('viewer-container');
        const list = document.getElementById('image-list');

        const viewer = new Viewer(list, {
            inline: true,
            container: container,
            navbar: false,
            title: false,
            toolbar: {
                zoomIn: 1,
                zoomOut: 1,
                oneToOne: 1,
                reset: 1,
                prev: 1,
                next: 1,
                rotateLeft: 1,
                rotateRight: 1,
            },
        });
    });
</script>

<hr>
<form method="post" action="/delete-game.php" onsubmit="return confirm('Really delete this game?');">
    <input type="hidden" name="id" value="<?= $game['id'] ?>">
    <button type="submit" style="color: red;">Delete Game</button>
</form>
