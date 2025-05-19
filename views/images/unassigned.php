<h1>Unassigned Images</h1>

<?php if (empty($images)): ?>
    <p>No unassigned images found.</p>
<?php else: ?>
    <div style="display: flex; flex-wrap: wrap; gap: 12px;">
        <?php foreach ($images as $image): ?>
            <div style="border: 1px solid #ccc; padding: 8px;">
                <img src="/image.php?file=<?= urlencode(basename($image['image_url'])) ?>" style="max-width: 150px; display: block;">
                <small><?= htmlspecialchars(basename($image['image_url'])) ?></small>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

