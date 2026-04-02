<?php
$data = '';
$index = 0;
$all_stat9 = 0;
$all_stat10 = 0;
$all_stat11 = 0;
$all_stat16 = 0;
$all_stat21 = 0;
$all_stat22 = 0;
$all_stat23 = 0;
$all_stat24 = 0;
$all_stat25 = 0;
$all_stat26 = 0;
$all_stat27 = 0;
$all_stat28 = 0;
$all_stat29 = 0;
$all_stat30 = 0;
$all_stat31 = 0;
$all_stat32 = 0;
$all_leads = 0;
$all_not_calls = 0;
$all_rejection = 0;
$all_calling = 0;
$all_count_request = 0;
?>

<?php while($index < count($results)): ?>

    <?php 
        $data = $results[$index]['name'];
		$all_stat9 = 0;
		$all_stat10 = 0;
		$all_stat11 = 0;
		$all_stat16 = 0;
		$all_stat21 = 0;
		$all_stat22 = 0;
		$all_stat23 = 0;
		$all_stat24 = 0;
		$all_stat25 = 0;
		$all_stat26 = 0;
		$all_stat27 = 0;
		$all_stat28 = 0;
		$all_stat29 = 0;
		$all_stat30 = 0;
        $all_stat31 = 0;
        $all_stat32 = 0;
        $all_leads = 0;
        $all_not_calls = 0;
        $all_count_request = 0;    
        $all_rejection = 0;
        $all_calling = 0;    
     ?>
    <tr>
        <td colspan="31" class="colspan-request-group"><?= $data; ?></td>
    </tr>     
    <?php while($index < count($results) and $data == $results[$index]['name']): ?>
        <?php 
            $all_stat9 += (int) $results[$index]['stat9'];
            $all_stat10 += (int) $results[$index]['stat10'];
            $all_stat16 += (int) $results[$index]['stat16'];
            $all_stat21 += (int) $results[$index]['stat21'];
            $all_stat22 += (int) $results[$index]['stat22'];
            $all_stat23 += (int) $results[$index]['stat23'];
            $all_stat24 += (int) $results[$index]['stat24'];
            $all_stat25 += (int) $results[$index]['stat25'];
            $all_stat26 += (int) $results[$index]['stat26'];
            $all_stat27 += (int) $results[$index]['stat27'];
            $all_stat28 += (int) $results[$index]['stat28'];
            $all_stat29 += (int) $results[$index]['stat29'];
            $all_stat30 += (int) $results[$index]['stat30'];
			$all_stat31 += (int) $results[$index]['stat31'];
            $all_stat32 += (int) $results[$index]['stat32'];
            $all_leads += (int) $results[$index]['leads'];
            $all_not_calls += (int) $results[$index]['not_calls'];
            $all_rejection += (int) $results[$index]['rejection'];
            $all_calling += (int) $results[$index]['calling'];            
            $all_count_request += (int) $results[$index]['count_request'];
        ?>    
        <tr tr-id="<?= $results[$index]['id']; ?>">
            <td class="table-id" name="id"><?= $results[$index]['city_group']; ?></td>
            <td class="table-stat10" name="id"><?= $results[$index]['stat10']; ?></td>
            <td class="table-stat16" name="id"><?= $results[$index]['stat16']; ?></td>
            <td class="table-stat21" name="id"><?= $results[$index]['stat21']; ?></td>
            <td class="table-stat22" name="id"><?= $results[$index]['stat22']; ?></td>
            <td class="table-stat23" name="id"><?= $results[$index]['stat23']; ?></td>
            <td class="table-stat24" name="id"><?= $results[$index]['stat24']; ?></td>
            <td class="table-stat25" name="id"><?= $results[$index]['stat25']; ?></td>
            <td class="table-stat26" name="id"><?= $results[$index]['stat26']; ?></td>
            <td class="table-stat27" name="id"><?= $results[$index]['stat27']; ?></td>
            <td class="table-stat28" name="id"><?= $results[$index]['stat28']; ?></td>
            <td class="table-stat29" name="id"><?= $results[$index]['stat29']; ?></td>
            <td class="table-stat30" name="id"><?= $results[$index]['stat30']; ?></td>
            <td class="table-stat31" name="id"><?= $results[$index]['stat31']; ?></td>
            <td class="table-stat32" name="id"><?= $results[$index]['stat32']; ?></td>
            <td class="table-stat9" name="id"><?= $results[$index]['stat9']; ?></td>
            <td class="table-leads" name="id"><?= $results[$index]['leads']; ?></td>
            <td class="table-not-calls" name="id"><?= $results[$index]['not_calls']; ?></td>
            <td class="table-rejection" name="id"><?= $results[$index]['rejection']; ?></td>
            <td class="table-calling" name="id"><?= $results[$index]['calling']; ?></td>
            <td class="table-all-requests" name="id"><?= $results[$index]['count_request']; ?></td>
            <td class="table-kpd" name="id"><?= round($results[$index]['kpd'], 2) . '%'; ?></td>
        </tr> 
        <?php $index++; ?>   
    <?php endwhile; ?>
    <?php
        if($all_leads == 0 and $all_count_request - $all_stat10 == 0) {
            $all_kpd = 0;
        } 
        else {
            $all_kpd = round($all_leads / ($all_count_request - $all_stat10) * 100, 2);
        }
    ?>
      
    <tr class="table-info">
        <td class="table-total">Общее</td>
        <td class="table-all-stat10"><?= $all_stat10; ?></td>
        <td class="table-all-stat16"><?= $all_stat16; ?></td>
        <td class="table-all-stat21"><?= $all_stat21; ?></td>
        <td class="table-all-stat22"><?= $all_stat22; ?></td>
        <td class="table-all-stat23"><?= $all_stat23; ?></td>
        <td class="table-all-stat24"><?= $all_stat24; ?></td>
        <td class="table-all-stat25"><?= $all_stat25; ?></td>
        <td class="table-all-stat26"><?= $all_stat26; ?></td>
        <td class="table-all-stat27"><?= $all_stat27; ?></td>
        <td class="table-all-stat28"><?= $all_stat28; ?></td>
        <td class="table-all-stat29"><?= $all_stat29; ?></td>
        <td class="table-all-stat30"><?= $all_stat30; ?></td>
        <td class="table-all-stat31"><?= $all_stat31; ?></td>
        <td class="table-all-stat32"><?= $all_stat32; ?></td>
        <td class="table-all-stat9"><?= $all_stat9; ?></td>
        <td class="table-all-leads"><?= $all_leads; ?></td>
        <td class="table-all-not-calls"><?= $all_not_calls; ?></td>
        <td class="table-all-rejection"><?= $all_rejection; ?></td>
        <td class="table-all-calling"><?= $all_calling; ?></td>        
        <td class="table-all-count-requests"><?= $all_count_request; ?></td>
        <td class="table-all-kpd"><?= $all_kpd . '%'; ?></td>
    </tr>           

<?php endwhile; ?>