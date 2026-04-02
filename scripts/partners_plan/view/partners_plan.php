<?php

function get_rus_format_date($date_time) {
    if(!empty($date_time) and $date_time != '0000-00-00 00:00:00' and $date_time != '0000-00-00') {
        return date('d.m.Y', strtotime($date_time));
    }
}

?>

<?php foreach($results as $row): ?>

    <tr tr-id="<?= $row['id']; ?>">
        <td class="table-id align-middle" name="id" style="padding-left: 5px !important;">
            <?= $row['id']; ?>
        </td>
        <td class="table-partner-name align-middle" name="partner_name" attr-id="<?= $row['partner_id']; ?>" style="padding-left: 5px !important;">
            <?= $row['partner_name']; ?>
        </td> 
        <td class="table-partner-city align-middle" name="city" style="padding-left: 5px !important;">
            <?= $row['city']; ?>
        </td>  
        <td class="table-total-quantity align-middle" name="total_quantity" style="padding-left: 5px !important;"> 
            <?= $row['total_quantity']; ?>
        </td> 
        <td class="table-quantity-per-day d-none" name="quantity_per_day" style="padding-left: 5px !important;">
            <?= $row['quantity_per_day']; ?>
        </td>  
        <td class="table-date-start align-middle" name="date_start" attr-date-start="<?= $row['date_start']; ?>" style="padding-left: 5px !important;">
            <?= get_rus_format_date($row['date_start']); ?>
        </td> 
        <td class="table-shipped1 align-middle" name="shipped1_details" style="padding-left: 5px !important;">
            <span name="shipped1"><?= $row['shipped1']; ?></span>
            <br>
            <a href="#" class="open-details" data-toggle="modal" data-target="#details-modal">Подробнее</a>
        </td>
        <td class="table-remainder1 align-middle" name="remainder1_date_end1" style="padding-left: 5px !important;">
            <span name="remainder1"><?= $row['remainder1']; ?></span>
            <br>
            <span name="date_end1"><?= get_rus_format_date($row['date_end1']); ?></span>
        </td>       
        <td class="table-otbrakovka table-active align-middle" name="otbrakovka" style="padding-left: 5px !important;">
            <?= $row['otbrakovka']; ?>
        </td>     
        <td class="table-shipped2 table-active align-middle" name="shipped2_details" style="padding-left: 5px !important;">
            <span name="shipped2"><?= $row['shipped2']; ?></span>
            <br>
            <a href="#" class="open-details" data-toggle="modal" data-target="#details-modal">Подробнее</a>
        </td>
        <td class="table-remainder2 table-active align-middle" name="remainder2" style="padding-left: 5px !important;">
            <?= $row['remainder2']; ?>
        </td>
        <td class="table-date-end2 table-active align-middle" name="date_end2" style="padding-left: 5px !important;">
            <?= get_rus_format_date($row['date_end2']); ?>
        </td>                      
        <td class="table-comment d-none" full-vopros="<?= $row['vopros']; ?>" name="vopros" style="padding-left: 5px !important;">
            <?= $vopros; ?>
        </td>   
        <td class="table-settings text-center table-active align-middle">
            <i class="mdi mdi-border-color open-request" style="cursor: pointer;" data-id="<?= $row['id']; ?>"
                data-toggle="modal" data-target="#signup-modal"></i>
        </td>
        <td class="table-settings text-center table-active align-middle">
            <i class="mdi mdi-delete delete-request" style="cursor: pointer;" data-id="<?= $row['id']; ?>"></i>
        </td>
    </tr>
<?php endforeach; ?>