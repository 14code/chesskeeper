<h1>Players</h1>

<?php foreach ($messages as $msg): ?>
    <p style="color:<?= $msg['type'] === 'error' ? 'red' : 'green' ?>">
        <?= htmlspecialchars($msg['text']) ?>
    </p>
<?php endforeach; ?>

<?php if (empty($players)): ?>
    <p>No players found.</p>
<?php else: ?>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>FIDE-ID</th>
        </tr>
        <?php foreach ($players as $player): ?>
            <tr>
                <td><?= htmlspecialchars($player['id']) ?></td>
                <td><?= htmlspecialchars($player['name']) ?></td>
                <td><?= htmlspecialchars($player['fide_id'] ?? '—') ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
