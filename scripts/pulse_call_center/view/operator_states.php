
<?php foreach($info_operator_states as $row): ?>
    <tr>
        <td><?= $row['id_otdel']; ?></td>
        <td><?= $row['name']; ?></td>
        <td><?= $row['status']; ?></td>
        <td><?= $row['last_call']; ?></td>
        <td><?= $row['calls_taken']; ?></td>
    </tr>
<?php endforeach; ?>