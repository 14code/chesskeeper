<h1>Games</h1>

<?php if (empty($games)): ?>
    <p>No games found.</p>
<?php else: ?>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>White vs Black</th>
            <th>Result</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($games as $game): ?>
            <tr>
                <td><?= htmlspecialchars($game['id']) ?></td>
                <td><?= htmlspecialchars($game['date']) ?></td>
                <td><?= htmlspecialchars($game['white_player_id'] ?? '?') ?> vs <?= htmlspecialchars($game['black_player_id'] ?? '?') ?></td>
                <td><?= $game['result'] == 1 ? '1–0' : ($game['result'] == 0.5 ? '½–½' : '0–1') ?></td>
                <td>
                    <a href="/edit-game.php?id=<?= $game['id'] ?>">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
