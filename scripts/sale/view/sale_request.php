<?php

function get_rus_format_date($date_time) {
    if(!empty($date_time)) {
        return date('d.m.Y H:i', strtotime($date_time));
    }
}

?>

<?php foreach($sale_requsests as $row): ?>
    <tr tr-id="<?= $row['id']; ?>">
        <th scope="row">
		    <?if (($row['is_double'] == '1' and $row['partner_id'] == '250') OR ($row['is_double'] == '1' and $row['partner_id'] == '513')) { 
			  echo "{$row['partner_name']} <span class='badge badge-danger'>Дубль</span>";
			} else { ?>
            <?= $row['defect'] ? "{$row['partner_name']} <span class='badge badge-danger'>Брак</span>" : $row['partner_name']; ?>     
			<? } ?>
        </th>
        <td><?= $row['price']; ?></td>
        <td><?= get_rus_format_date($row['date_time']); ?></td>
    </tr>
<?php endforeach; ?>