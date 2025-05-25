<h1>Upload Game Sheet Images</h1>

<?php if (!empty($errors)): ?>
<div style="color: red;">
    <ul>
        <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<?php if (!empty($success)): ?>
<div style="color: green;">
    <p>Uploaded successfully:</p>
    <ul>
        <?php foreach ($success as $s): ?>
        <li><?= htmlspecialchars($s) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" action="/upload">
    <input type="file" name="images[]" multiple required>
    <button type="submit">Upload</button>
</form>
