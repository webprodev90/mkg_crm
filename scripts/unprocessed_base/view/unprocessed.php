<?php

function get_background_tr($status, $is_save_today, $partner_id) {
    $color_class = '';
    switch(true) {
        case $status == 8:  # Недозвон
            $color_class = 'non-call-bg';
            break;
        case $status == 11: # Отказ
            $color_class = 'rejection-bg';
            break;
        case $status == 9:  # Брак
            $color_class = 'defect-bg';
            break; 
        case $status == 21:  # Долг менее 300 тысяч 
            $color_class = 'less-than-300-bg';
            break;   
        case $status == 22:  # Автоответчик
            $color_class = 'answering-machine-bg';
            break;   
        case $status == 23:  # Ипотека - единственное жилье
            $color_class = 'mortgage-only-housing-bg';
            break; 
        case $status == 24:  # Залог/Автокредит
            $color_class = 'collateral-car-loan-bg';
            break;
        case $status == 25:  # Ипотека + Имущество
            $color_class = 'mortgage-property-bg';
            break;   
        case $status == 26:  # Много имущества
            $color_class = 'lots-of-property-bg';
            break; 
        case $status == 27:  # Плохой контакт (битый номер)
            $color_class = 'bad-contact-bg';
            break;
        case $status == 28:  # Негатив/Неадыкват
            $color_class = 'negative-bg';
            break;   
        case $status == 29:  # Уже банкрот (Менее 5 лет)
            $color_class = 'already-bankrupt-bg';
            break; 
        case $status == 30:  # Бросил трубку
            $color_class = 'hung-up-bg';
            break;                                                     
        case $status == 15 and $partner_id != 65 and $is_save_today == 0:  # Лид
            $color_class = 'lead-bg';
            break;
        case $status == 15 and $partner_id != 65 and $is_save_today == 1:  # Лид сохранен сегодня
            $color_class = 'save-today-bg';
            break;    
        case $status == 15 and $partner_id == 65:  # Бракованный лид
            $color_class = 'defect-bg';
            break; 
        case $status == 6:  # Созвон
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
$operators = $unprocessed_base_obj->handle_action('get_operators');
$operators_options = '';
foreach($operators as $operator) {
    $operators_options .= "<option value=\"{$operator['id']}\">{$operator['id']}</option>";
}

// var_dump($unprocessed_base_requests); exit;
$group_id = '';
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
    ?>
    <?php
    /*
    if(in_array($_SESSION['login_role'], [1, 4]) and $group_id != $row['group_source_id']) {
        $group_id = $row['group_source_id'];
        $colspan_value = "10";
        if($row['is_double'] == NULL and $row['manual'] == '') {
            $colspan_value = "11"; 
        }
        echo '<tr><td colspan="' . $colspan_value . '" class="colspan-request-group">' . $row['group_name'] . '</td></tr>';
    } elseif(!in_array($_SESSION['login_role'], [1, 4]) and $group_id != $row['group_source_id']) {
        $group_id = $row['group_source_id'];
        echo '<tr><td colspan="11" class="colspan-request-group">' . $row['group_date'] . '</td></tr>';
    }
    */
	if ($row['source'] == 'ГМГ' and $row['status'] == 15) { $color_gmg = ' blue-lead-bg'; } else { $color_gmg = '';}
    
    ?>

    <tr tr-id="<?= $row['id']; ?>" class="<?= isset($row['partner_id']) ? get_background_tr($row['status'], $row['is_save_today'], $row['partner_id']) : get_background_tr($row['status'], $row['is_save_today'], 1); ?><?= $color_gmg?>">
        <?php if(($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 4 or $_SESSION['login_role'] == 12) and $row['is_double'] == NULL and $row['manual'] == ''): ?>
            <td class="table-chec"><input type="checkbox" id="singleCheckbox2" name="list" value="<?= $row['id']; ?>"></td>
        <?php endif; ?>
        <td class="table-id" name="id">
            <?= $row['id']; ?>
        </td>
        <?php if($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 4 or $_SESSION['login_role'] == 12): ?>
            <td class="table-source" name="source">
                <?= $row['source']; ?>
            </td>
        <?php endif; ?>
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
        <td class="table-partner" name="partner"  style="display: none;">
            <?= $row['partner']; ?>
        </td>   		
        <td class="table-comment" full-vopros="<?= $row['vopros']; ?>" name="vopros">
            <?= $vopros; ?>
        </td>
        <?php if($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 4 or $_SESSION['login_role'] == 12): ?>
            <?php if(($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 4 or $_SESSION['login_role'] == 12) and $row['is_double'] == NULL and $row['manual'] == '' and empty($this->data['user_access'])): ?>
                <td class="table-otdel" name="id_otdel" >
                    <?= $row['id_otdel']; ?>
                </td>
                <td class="table-user" name="user_name" >
                    <?= $row['operator_name']; ?>
                </td>
            <?php endif; ?>            
            <td class="table-operator" name="operator_id" attr-id="<?= $row['operator_id'] ?>">
                <select class="change-operator">
                    <option></option>
                    <?php foreach($operators as $operator): ?>
                        <?php $selected = ''; ?>
                        <?php if($operator['id'] == $row['operator_id']): ?>
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
            <div class="status_name" data-lead="<?= $row['is_lead'] ?>">
                <?= $row['status_name']; ?>
            </div>
        </td>
        <td class="table-date_time_status_change" name="date_time_status_change">
            <div class="dt_status_change" attr-date="<?= $dt_status ?>">
                <?= $dt_status_rus; ?>
            </div>
        </td>
        <td class="table-settings text-center">
            <i class="mdi mdi-border-color open-request" style="cursor: pointer;" data-id="<?= $row['id']; ?>"
                data-toggle="modal" data-target="#signup-modal"></i>
				<?php if($_SESSION['login_role'] == 1 OR $_SESSION['id_atc'] > 100) { ?>
			<i id="phone_call" class="mdi mdi-phone-in-talk open-request" style="cursor: pointer; font-size: 14px;" data-id="<?= $row['id']; ?>" data-phone="Y"
                data-toggle="modal" data-target="#signup-modal"></i>
				<?php } ?>
				<?php if($row['status'] == 11) { ?>
			<i class="fa fa-microphone ml-2 listen-audio" aria-hidden="true" data-phone="<?= $row['phone_number']; ?>" data-toggle="modal"
                data-target="#audio-modal"></i>	
				<?php } ?>
        </td>
    </tr>
<?php endforeach; ?>