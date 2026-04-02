<?php $total_count = 0; ?>
<?php $divider_conversion = 0; ?>
<?php $dividend_conversion = 0; ?>
<?php $conversion = 0; ?>

<?php foreach($results as $row): ?>

    <tr>
        <td>
            <?= $row['status_name']; ?>
        </td>
        <td>
            <?= $row['leads']; ?>
        </td> 
        <?php if($row['status_name'] == "В работе" or $row['status_name'] == "Потерялся" or $row['status_name'] == "Отказ" or $row['status_name'] == "Слив" or $row['status_name'] == "Договор" or $row['status_name'] == "Выставлен счет" or $row['status_name'] == "Не обработано" or $row['status_name'] == "Перезвон/Созвон" or $row['status_name'] == "Недозвон"):
                if($row['status_name'] == "Договор") {
                   $dividend_conversion += (int) $row['leads']; 
                }
                
                $divider_conversion += (int) $row['leads']; 
        ?>
            <td>
                <b><?= $row['leads']; ?></b>
            </td>
        <?php else: ?>
            <td>
                0
            </td>            
        <?php endif; ?>             
    </tr>

<?php $total_count += (int) $row['leads']; ?>

<?php endforeach; ?>

<?php $conversion = $divider_conversion === 0 ? 0 : round((float) $dividend_conversion / (float) $divider_conversion * 100, 2); ?>

<tr>
    <th>
        Всего
    </th>
    <th>
        <?= $total_count; ?>
    </th> 
    <th>
        (<?= $dividend_conversion; ?> / <?= $divider_conversion; ?>) * 100% = <span style="color: mediumblue;"><?= $conversion; ?>%</span>
    </th> 
</tr>