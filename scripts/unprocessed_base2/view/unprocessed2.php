<?php

function get_background_tr($status) {
    $color_class = '';
    switch(true) {
        case $status == 8:  # Недозвон
            $color_class = 'non-call-bg';
            break;
        case $status == 11: # Отказ
            $color_class = 'rejection-bg';
            break;
        case $status == 17:  # Договор
            $color_class = 'in-work-bg';
            break;
        case $status == 6:  # Созвон
            $color_class = 'calling-bg';
            break;
        case $status == 9:  # Брак
            $color_class = 'defect-bg';
            break;
        case $status == 18:  # В работе
            $color_class = 'lead-bg';
            break; 
        case $status == 19:  # Дубль
            $color_class = 'double-bg';
            break;
        case $status == 20:  # Потерялся
            $color_class = 'got-lost-bg';
            break; 
        case $status == 5:  # Слив
            $color_class = 'sliv-bg';
            break;
        case $status == 37:  # Выставлен счет
            $color_class = 'invoice-issued-bg';
            break;                                
    }

    return $color_class;
}

function get_rus_format_date_time($date_time) {
    if(!empty($date_time) and $date_time != '0000-00-00 00:00:00') {
        return date('d.m.Y H:i', strtotime($date_time));
    }
}

function get_rus_format_date($date) {
    if(!empty($date) and $date != '0000-00-00') {
        return date('d.m.Y', strtotime($date));
    }
}

$unprocessed_base_obj = new UnprocessedBase2();
$operators = $unprocessed_base_obj->handle_action('get_operators');
$operators_options = '';
if(isset($operators)) {
    foreach($operators as $operator) {
        $operators_options .= "<option value=\"{$operator['id']}\">{$operator['id']}</option>";
    }    
}


?>
<?php
    $index = 1;
?>
<?php foreach($unprocessed_base_requests as $row): ?>
    <?php
    $dt_status = $row['date_time_status_change'];
    $dt_status_rus = get_rus_format_date_time($dt_status);
    $date_create = get_rus_format_date($row['date_create']);
    $vopros = $row['vopros'];
    if(mb_strlen($vopros) > 90) {
        $vopros = '<div class="comment">' . mb_substr($vopros, 0, 90) . '...';
    }
    ?>

    <tr tr-id="<?= $row['id']; ?>" class="<?= get_background_tr($row['status']); ?>">
        <td class="table-id">
            <?= $index++; ?>
        </td>
        <td class="table-created-by-user" name="created_by_user" data-created-user-id="<?= $row['created_by_user_id']; ?>">
            <?= $row['created_by_user']; ?>
        </td>
        <td class="table-date-create">
            <?= $date_create; ?>
        </td>
        <td class="table-phone" name="phone_number">
            <?= $row['phone_number']; ?>
        </td>
        <td class="table-name" name="fio" attr-fio="<?= $row['fio']; ?>">
            <?php
            if(mb_strlen($row['fio']) >= 14) {
                echo mb_substr($row['fio'], 0, 14) . '...';
            } else {
                echo $row['fio'];
            }
            ?>
        </td>
        <td class="table-city" name="city">
            <?= $row['city']; ?>
        </td>  		
        <td class="table-comment" full-vopros="<?= $row['vopros']; ?>" name="vopros">
            <?= $vopros; ?>
        </td>
        <?php if($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 10 or $_SESSION['login_role'] == 11): ?>
            <td class="table-operator" name="operator_id" attr-id="<?= $row['user_id'] ?>">
                <select class="change-operator2">
                    <option></option>
                    <?php foreach($operators as $operator): ?>
                        <?php $selected = ''; ?>
                        <?php if($operator['id'] == $row['user_id']): ?>
                            <?php $selected = ' selected'; ?>
                        <?php endif; ?>
                        <option<?= $selected; ?> value="<?= $operator['id']; ?>">
                            <?= $operator['id']; ?>
                            </option>
                        <?php endforeach; ?>
                </select>
            </td>
        <?php endif; ?>
        <td class="table-status" name="status_name" attr-id="<?= $row['status'] ?>">
            <div class="status_name">
                <?= $row['status_name']; ?>
            </div>
        </td>
        <td class="table-date_time_status_change" name="date_time_status_change">
            <div class="dt_status_change" attr-date="<?= $dt_status ?>">
                <?= $dt_status_rus; ?>
            </div>
        </td>
        <td class="table-update text-center">
            <i class="mdi mdi-border-color open-request2" style="cursor: pointer;" data-id="<?= $row['id']; ?>"
                data-toggle="modal" data-target="#signup-modal"></i>
        </td>
        <?php if($_SESSION['login_role'] == 1): ?>
            <td class="table-delete text-center">
                <i class="mdi mdi-delete delete-request" style="cursor: pointer;" data-id="<?= $row['id']; ?>"></i>
            </td>
        <?php endif; ?>
        <td class="table-сompany-name d-none" name="сompany_name">
            <?= $row['сompany_name']; ?>
        </td> 
        <td class="table-sales-department d-none" name="sales_department">
            <?= $row['sales_department']; ?>
        </td>  
        <td class="table-experience d-none" name="experience">
            <?= $row['experience']; ?>
        </td> 
        <td class="table-have-crm d-none" name="have_crm">
            <?= $row['have_crm']; ?>
        </td> 
        <td class="table-time-difference d-none" name="time_difference">
            <?= $row['time_difference']; ?>
        </td> 
        <td class="table-job d-none" name="job">
            <?= $row['job']; ?>
        </td> 
    </tr>
<?php endforeach; ?>