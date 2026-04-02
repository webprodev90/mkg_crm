<?php

function compareStatus($a, $b) {
    if($a["status"] == $b["status"]) {
        return 0;
    }
    if($a["status"] == "15") {
        return -1;
    }

    if($b["status"] == "15") {
        return 1;
    }

    return 0;
}

function get_background_tr($status) {
    $color_class = '';
    switch($status) {
        case 8:  # Недозвон
            $color_class = 'non-call-bg';
            break;
        case 11: # Отказ
            $color_class = 'rejection-bg';
            break;
        case 15:  # Лид
            $color_class = 'lead-bg';
            break;
        case 6:  # Созвон
            $color_class = 'calling-bg';
            break;
    }

    return $color_class;
}

function get_rus_format_date($date_time) {
    if(!empty($date_time) and $date_time != '0000-00-00 00:00:00') {
        return date('d.m.Y H:i', strtotime($date_time));
    }
}

$unprocessed_base_obj = new UnprocessedBase();
usort($unprocessed_base_requests, "compareStatus");
$operators = $unprocessed_base_obj->handle_action('get_operators');
$operators_options = '';
foreach($operators as $operator) {
    $operators_options .= "<option value=\"{$operator['id']}\">{$operator['id']}</option>";
}

$group_id = '';
$result = '';
$leads = 0;
?>

<?php foreach($unprocessed_base_requests as $row): ?>
    <?php
    $dt_status = $row['date_time_status_change'];
    $dt_status_rus = get_rus_format_date($dt_status);
    $vopros = $row['vopros'];
    if(mb_strlen($vopros) > 50) {
        $vopros = '<div class="comment">' . mb_substr($vopros, 0, 50) . '...';
        $vopros .= '<span class="cursor-pointer show-comment">Показать</span></div>';
    }
    if($row['status'] == 15) {
        $leads += 1;
    }
    ?>
    <?php
    $result .= '
    <tr tr-id="' . $row['id'] . '" class="' . get_background_tr($row['status']) . '">
        <td class="table-id" name="id">' . $row['id'] . '</td>
        <td class="table-source" name="source">' . $row['source'] . '</td>
        <td class="table-phone" name="phone_number">' . $row['phone_number'] . '</td>
        <td class="table-name" name="fio" attr-fio="' . $row['fio'] . '">' .
        (mb_strlen($row['fio']) >= 14 ? mb_substr($row['fio'], 0, 14) . '...' : $row['fio']) . '</td>
        <td class="table-city" name="city">' . $row['city'] . '</td>
        <td class="table-comment" full-vopros="' . $row['vopros'] . '" name="vopros">' . $vopros . '</td>
        <td class="table-operator" name="operator_id" attr-id="' . $row['operator_id'] . '">' . $row['operator_id'] . '
        </td>
        <td class="table-status" name="status_name" attr-id="' . $row['status'] . '">
            <div class="status_name">' . $row['status_name'] . '</div>
        </td>
        <td class="table-date_time_status_change" name="date_time_status_change">
            <div class="dt_status_change" attr-date="' . $dt_status . '">' . $dt_status_rus . '</div>
        </td>
    </tr>';
?>
<?php endforeach; ?>

<?php
$row['group_name'] = preg_replace('/\s\d+\sшт\.$/', '', $row['group_name']);
$all_requests = count($unprocessed_base_requests);
echo '
    <tr>
        <td colspan="10"
            class="colspan-request-group">' . $row['group_name'] . ' Всего: ' . $all_requests . '
            Лиды: ' . $leads . '
        </td>
    </tr>';
echo trim($result);
?>