<?php foreach($results as $item): ?>
    <?= $is_full_norm = (int) $item['is_full_norm']; ?>

    <tr tr-id="<?= $item['id']; ?>" class="<?= $is_full_norm === 1 ? 'table-success': 'table-danger'; ?>">
        <td class="table-id" name="id"><?= $item['id']; ?></td>
        <td class="table-fio" name="id"><?= $item['name']; ?></td>
        <td class="table-requests" name="id"><?= $item['count_request']; ?></td>
        <!--
        <td class="table-zp" name="id"><?= $is_full_norm === 1 ? (int) $item['count_request'] * 150 + (int) $item['bonus'] * 250 : (int) $item['count_request'] * 100 + (int) $item['bonus'] * 250; ?></td>
        -->
        <td class="table-bonus" name="id"><?= $item['bonus']; ?></td>
    </tr>

<?php endforeach; ?>