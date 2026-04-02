<?php
$data = '';
$index = 0;
$all_not_processed = 0;
$all_avtootvet = 0;
$all_not_calls = 0;
$all_leads = 0;
$all_count_request = 0;
$all_total_count_request = 0;
$all_count_dozvon = 0;
?>

<?php while($index < count($results)): ?>

    <?php 
        $data = $results[$index]['name'];
        $all_not_processed = 0;
        $all_avtootvet = 0;
        $all_not_calls = 0;
        $all_leads = 0;
        $all_count_request = 0;  
        $all_total_count_request = 0; 
        $all_count_dozvon = 0; 
     ?>
    <tr>
        <td colspan="13" class="colspan-request-group"><?= $data; ?></td>
    </tr>     
    <?php while($index < count($results) and $data == $results[$index]['name']): ?>
        <?php 
            $all_not_processed += (int) $results[$index]['not_processed'];
            $all_avtootvet += (int) $results[$index]['avtootvet'];
            $all_not_calls += (int) $results[$index]['not_calls'];      
            $all_leads += (int) $results[$index]['leads']; 
            $all_count_request += (int) $results[$index]['count_request'];
            $all_total_count_request += (int) $results[$index]['total_count_request'];
            $all_count_dozvon += (int) $results[$index]['count_dozvon'];
        ?>    
        <tr class="table-city-stat" tr-id="<?= $results[$index]['id']; ?>" tr-source="<?= $results[$index]['name']; ?>">
            <td class="table-chec"><input type="checkbox" class="td-checkbox" id="singleCheckbox2" name="list" value="<?= $results[$index]['id']; ?>"></td>
            <td class="table-id" name="id"><?= $results[$index]['city_group']; ?></td>
            <td class="table-not-processed" name="id"><?= $results[$index]['not_processed']; ?></td>
            <td class="table-avtootvet" name="id"><?= $results[$index]['avtootvet']; ?></td>
            <td class="table-not-calls" name="id"><?= $results[$index]['not_calls']; ?></td>
            <td class="table-count-requests" name="id"><?= $results[$index]['count_request']; ?></td>
            <td class="table-total-count-requests" name="id"><?= $results[$index]['total_count_request']; ?></td>
            <td class="table-count-dozvon" name="id"><?= $results[$index]['count_dozvon']; ?></td>
            <td class="table-leads" name="id"><?= $results[$index]['leads']; ?></td>
            <td class="table-dozvon" name="id"><?= round($results[$index]['dozvon'], 2) . '%'; ?></td>
            <td class="table-kpd" name="id"><?= round($results[$index]['kpd'], 2) . '%'; ?></td>
            <td class="table-chist-kpd" name="id"><?= round($results[$index]['chist_kpd'], 2) . '%'; ?></td>
            <td class="table-gr-kpd" name="id"><?= round($results[$index]['gr_kpd'], 2) . '%'; ?></td>
        </tr> 
        <?php $index++; ?>   
    <?php endwhile; ?>
    <?php
        if($all_leads == 0 or $all_total_count_request - $all_not_processed == 0) {
            $all_kpd = 0;
        } 
        else {
            $all_kpd = round($all_leads / ($all_total_count_request - $all_not_processed) * 100, 2);
        }

        if($all_leads == 0 or $all_total_count_request - $all_not_processed - $all_not_calls - $all_avtootvet == 0) {
            $all_chist_kpd = 0;
        } 
        else {
            $all_chist_kpd = round($all_leads / ($all_total_count_request - $all_not_processed - $all_not_calls - $all_avtootvet) * 100, 2);
        }

        if($all_leads == 0 or $all_total_count_request == 0) {
            $all_gr_kpd = 0;
        } 
        else {
            $all_gr_kpd = round($all_leads / $all_total_count_request * 100, 2);
        }

        if($all_not_processed + $all_not_calls + $all_avtootvet == 0 or $all_total_count_request == 0) {
            $all_dozvon = 0;
        } 
        else {
            $all_dozvon = round((1 - ($all_not_processed + $all_not_calls + $all_avtootvet) / $all_total_count_request) * 100, 2);
        }
    ?>
      
    <tr class="table-info">
        <td class="table-chec"></td>
        <td class="table-total">Общее</td>
        <td class="table-all-not-processed"><?= $all_not_processed; ?></td>
        <td class="table-all-avtootvet"><?= $all_avtootvet; ?></td>
        <td class="table-all-not-calls"><?= $all_not_calls; ?></td>     
        <td class="table-all-count-requests"><?= $all_count_request; ?></td>
        <td class="table-all-total-count-requests"><?= $all_total_count_request; ?></td>
        <td class="table-all-count-dozvon"><?= $all_count_dozvon; ?></td>
        <td class="table-all-leads"><?= $all_leads; ?></td>        
        <td class="table-all-dozvon"><?= $all_dozvon . '%'; ?></td>
        <td class="table-all-kpd"><?= $all_kpd . '%'; ?></td>
        <td class="table-all-chist-kpd"><?= $all_chist_kpd . '%'; ?></td>
        <td class="table-all-gr-kpd"><?= $all_gr_kpd . '%'; ?></td>
    </tr>           

<?php endwhile; ?>