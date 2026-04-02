<?php
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
    if(!empty($date_time)) {
        return date('d.m.Y H:i', strtotime($date_time));
    }
}

$unprocessed_base_obj = new UnprocessedBase();
$operators = $unprocessed_base_obj->handle_action('get_operators');
$operators_options = '';
foreach($operators as $operator) {
    $operators_options .= "<option value=\"{$operator['id']}\">{$operator['id']}</option>";
}

?>

<?php foreach($unprocessed_base_requests as $row): ?>
    <?php
    $dt_status = $row['date_time_status_change'];
    $dt_status_rus = get_rus_format_date($dt_status);
    $vopros = $row['vopros'];
    if(mb_strlen($vopros) > 40) {
        $vopros = '<div class="comment">' . mb_substr($vopros, 0, 40) . '...' . '</div>';
        $vopros .= '<div class="cursor-pointer show-comment">Показать</div>';
    }
    ?>
    <tr tr-id="<?= $row['id']; ?>" class="<?= get_background_tr($row['status']); ?>">
        <td class="table-id" name="id">
            <?= $row['id']; ?>
        </td>
        <td class="table-phone" name="phone_number">
            <?= $row['phone_number']; ?>
        </td>
        <td class="table-name" name="fio">
            <?= $row['fio']; ?>
        </td>
        <td class="table-city" name="city">
            <?= $row['city']; ?>
        </td>
        <td class="table-comment" full-vopros="<?= $row['vopros']; ?>" name="vopros">
            <?= $vopros; ?>
        </td>
        <?php if($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 4): ?>
            <td class="table-operator" name="operator_id" attr-id="<?= $row['operator_id'] ?>">
                <select class="change-operator">
                    <option></option>
                    <?php foreach($operators as $operator): ?>
                        <?php $selected = ''; ?>
                        <?php if($operator['id'] == $row['operator_id']): ?>
                            <?php $selected = ' selected'; ?>
                            <?php endif; ?>
                        <option<?= $selected; ?> value="<?= $operator['id']; ?>"><?= $operator['id']; ?></option>
                        <?php endforeach; ?>
                </select>
            </td>
        <?php endif; ?>
        <td class="table-status" name="status_name" attr-id="<?= $row['status'] ?> 1">
            <div class="status_name"><?= $row['status_name']; ?></div>
            <div class="date_time_status_change" attr-date="<?= $dt_status ?>"><?= $dt_status_rus; ?></div>
        </td>
        <td class="table-settings">
            <i class="mdi mdi-border-color open-request" style="cursor: pointer;" data-id="<?= $row['id']; ?>"
                data-toggle="modal" data-target="#signup-modal"></i>
        </td>
    </tr>
<?php endforeach; ?>