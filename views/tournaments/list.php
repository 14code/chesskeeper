<h1>Tournaments</h1>

<?php foreach ($messages as $msg): ?>
    <p style="color:<?= $msg['type'] === 'error' ? 'red' : 'green' ?>">
        <?= htmlspecialchars($msg['text']) ?>
    </p>
<?php endforeach; ?>

<?php if (empty($tournaments)): ?>
    <p>No tournaments found.</p>
<?php else: ?>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Location</th>
            <th>Start Date</th>
        </tr>
        <?php foreach ($tournaments as $t): ?>
            <tr>
                <td><?= htmlspecialchars($t['id']) ?></td>
                <td><?= htmlspecialchars($t['name']) ?></td>
                <td><?= htmlspecialchars($t['location'] ?? '—') ?></td>
                <td><?= htmlspecialchars($t['start_date'] ?? '') ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
