<?php
function get_background_tr($status) {
    $color_class = '';
    switch($status) {
        case 'new_lead':
            $color_class = 'lead-bg';
            break;
        case 'saled_lead':
            $color_class = 'saled-lead-bg';
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
    if(mb_strlen($vopros) > 80) {
        $vopros = '<div class="comment">' . mb_substr($vopros, 0, 40) . '...';
        $vopros .= '<span class="cursor-pointer show-comment">Показать</span></div>';
    }
    $new_lead = '';
    $status = 'saled_lead';
    if($row['new_lead']) {
        $new_lead = '<div class="new_lead">New</div>';
        $status = 'new_lead';
    } else {
		$new_lead = $row['partner_name'];
	}
	if ($row['source'] == 'ГМГ' and $row['status'] == 15) { $color_gmg = ' blue-lead-bg'; } else { $color_gmg = '';}
	if (($row['last_partner_id'] == '250' or $row['last_partner_id'] == '513') and $row['is_double'] == '1') { $color_dub = ' red-lead-bg'; } else { $color_dub = '';}
	if ($row['is_audio_check'] == 1) { 
		$color_iac = 'active-auduo'; 
	} else { 
		if ($_SESSION['login_role'] !== '5') { 
			$color_iac = 'no-active-auduo'; 
		} else {
			$color_iac = '';
		}	
	}	
    ?>
    <tr tr-id="<?= $row['id']; ?>" class="<?= get_background_tr($status); ?><?= $color_gmg?><?= $color_dub?>">
        <td class="table-id" name="id">
            <?= $row['id']; ?>
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
        <td class="table-comment" style="width:30%;" full-vopros="<?= $row['vopros']; ?>" name="vopros">
            <?= $vopros; ?>
        </td>
        <td class="table-new-lead" name="new-lead">
            <?= $new_lead; ?>
        </td>
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
        <td class="table-status d-none" name="status_name" attr-id="<?= $row['status'] ?>">
            <div class="status_name">
                <?= $row['status_name']; ?>
            </div>
            <div class="date_time_status_change" attr-date="<?= $dt_status ?>">
                <?= $dt_status_rus; ?>
            </div>
        </td>
        <td class="table-settings text-center">
            <i class="mdi mdi-border-color open-request" style="cursor: pointer;" data-id="<?= $row['id']; ?>"
                data-toggle="modal" data-target="#signup-modal"></i>
            <i class="fa fa-rub ml-2 sale-btn" aria-hidden="true" data-id="<?= $row['id']; ?>" data-toggle="modal"
                data-target="#sale-modal"></i>
            <i class="fa fa-microphone ml-2 listen-audio" aria-hidden="true" data-phone="<?= $row['phone_number']; ?>" data-toggle="modal"
                data-target="#audio-modal"></i>
			<i class="fa fa-check ml-2 check-audio-btn <?= $color_iac; ?>" aria-hidden="true" data-id="<?= $row['id']; ?>" data-phone="<?= $row['phone_number']; ?>"></i>
        </td>
    </tr>
<?php endforeach; ?>