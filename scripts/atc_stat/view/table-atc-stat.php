<?php

$all_total_sum = 0;
$all_avtoot = 0;
$all_ndz = 0;
$all_otkaz = 0;
$all_dozvon = 0;
$all_sozvon = 0;
$all_lid = 0;
$all_chist_lid = 0;
$all_hold = 0;
$all_gr_hold = 0;
$all_work_hours = 0;
$all_dozvon_h = 0;
$all_lid_h = 0;
$all_ndz_p = 0;
$all_ao_p = 0;
$all_dozvonlid_p = 0;
$all_contactlid_p = 0;
$all_holdlid_p = 0;
$all_grholdlid_p = 0;
$all_zvon_hours = 0;
$display_all_tr = false;

$otdel_total_sum = 0;
$otdel_avtoot = 0;
$otdel_ndz = 0;
$otdel_otkaz = 0;
$otdel_dozvon = 0;
$otdel_sozvon = 0;
$otdel_lid = 0;
$otdel_chist_lid = 0;
$otdel_hold = 0;
$otdel_gr_hold = 0;
$otdel_work_hours = 0;
$otdel_dozvon_h = 0;
$otdel_lid_h = 0;
$otdel_ndz_p = 0;
$otdel_ao_p = 0;
$otdel_dozvonlid_p = 0;
$otdel_contactlid_p = 0;
$otdel_holdlid_p = 0;
$otdel_grholdlid_p = 0;
$otdel_zvon_hours = 0;
$current_otdel = 1;
$i = 0;

$quantity_otdel = 0;
$duration_seconds_otdel = 0;
$avg_duration_seconds_otdel = '-';
$quantity_all_otdel = 0;
$duration_seconds_all_otdel = 0;
$avg_duration_seconds_all_otdel = '-';

$quantity_callers_otdel = 0;
$call_duration_seconds_otdel = 0;
$avg_call_duration_otdel = '-';
$quantity_callers_all_otdel = 0;
$call_duration_seconds_all_otdel = 0;
$avg_call_duration_all_otdel = '-';

while($i < count($results)) {

	$current_otdel = $results[$i]['id_otdel'];
    while($i < count($results) and $current_otdel == $results[$i]['id_otdel']) {

		if($_SESSION['login_role'] != 5) {

	    	$all_total_sum += $results[$i]['total_sum'];
			$all_avtoot += $results[$i]['avtoot'];
			$all_ndz += $results[$i]['ndz'];
			$all_otkaz += $results[$i]['otkaz'];
			$all_dozvon += $results[$i]['dozvon'];
			$all_sozvon += $results[$i]['sozvon'];
			$all_lid += $results[$i]['lid'];
			if($_SESSION['login_role'] != 4) {
				$all_chist_lid += $results[$i]['chist_lid'];
				$all_hold += $results[$i]['hold'];
				$all_gr_hold += $results[$i]['gr_hold'];
			}
			$all_work_hours += $results[$i]['work_hours'];
			$display_all_tr = true;

			$otdel_total_sum += $results[$i]['total_sum'];
			$otdel_avtoot += $results[$i]['avtoot'];
			$otdel_ndz += $results[$i]['ndz'];
			$otdel_otkaz += $results[$i]['otkaz'];
			$otdel_dozvon += $results[$i]['dozvon'];
			$otdel_sozvon += $results[$i]['sozvon'];
			$otdel_lid += $results[$i]['lid'];
			if($_SESSION['login_role'] != 4) {
				$otdel_chist_lid += $results[$i]['chist_lid'];
				$otdel_hold += $results[$i]['hold'];
				$otdel_gr_hold += $results[$i]['gr_hold'];
			}
			$otdel_work_hours += $results[$i]['work_hours'];

			if($_SESSION['login_role'] != 4) {
				$avg_duration_seconds = $results[$i]['avg_duration_seconds'] ? $results[$i]['avg_duration_seconds'] : '-';

				$call_duration = '-';

				if($results2) {
					$key = array_search($results[$i]['id_atc'], array_column($results2, 'user_id'));
					if($key !== false) {
						if($results2[$key]['call_duration'] != 0) {
							$call_duration = $results2[$key]['call_duration'];
							$quantity_callers_otdel++;
							$call_duration_seconds_otdel += $call_duration;
							$quantity_callers_all_otdel++;
							$call_duration_seconds_all_otdel += $call_duration;
						}   
					} 			
				}


				$duration_seconds_otdel += $results[$i]['avg_duration_seconds'];
				$duration_seconds_all_otdel += $results[$i]['avg_duration_seconds'];

				if($results[$i]['avg_duration_seconds'] != 0) {
					$quantity_otdel++;	
					$quantity_all_otdel++;			
				}
			}

		}

		if($_SESSION['login_role'] == 5) {
			echo '<tr>' .
					'<td>' . $results[$i]['id_otdel'] . '</td>' .
					'<td>' . $results[$i]['name'] . '</td>' .
					'<td>' . $results[$i]['total_sum'] . '</td>' .
					'<td>' . $results[$i]['avtoot'] . '</td>' .
					'<td>' . $results[$i]['ndz'] . '</td>' .
					'<td>' . $results[$i]['otkaz'] . '</td>' .
					'<td>' . $results[$i]['dozvon'] . '</td>' .
					'<td>' . $results[$i]['sozvon'] . '</td>' .
					'<td>' . $results[$i]['lid'] . '</td>' .
			      '</tr>';			
		} elseif($_SESSION['login_role'] == 4) {
			echo '<tr>' .
					'<td>' . $results[$i]['id_otdel'] . '</td>' .
					'<td>' . $results[$i]['name'] . '</td>' .
					'<td>' . $results[$i]['total_sum'] . '</td>' .
					'<td>' . $results[$i]['avtoot'] . '</td>' .
					'<td>' . $results[$i]['ndz'] . '</td>' .
					'<td>' . $results[$i]['otkaz'] . '</td>' .
					'<td>' . $results[$i]['dozvon'] . '</td>' .
					'<td>' . $results[$i]['sozvon'] . '</td>' .
					'<td>' . $results[$i]['lid'] . '</td>' .
					'<td>' . $results[$i]['dozvon_h'] . '</td>' .
					'<td>' . $results[$i]['dozvonlid_p'] . ' %</td>' .
			      '</tr>';	
		} else {
			echo '<tr>' .
					'<td>' . $results[$i]['id_otdel'] . '</td>' .
					'<td>' . $results[$i]['name'] . '</td>' .
					'<td>' . $results[$i]['total_sum'] . '</td>' .
					'<td>' . $results[$i]['avtoot'] . '</td>' .
					'<td>' . $results[$i]['ndz'] . '</td>' .
					'<td>' . $results[$i]['otkaz'] . '</td>' .
					'<td>' . $results[$i]['dozvon'] . '</td>' .
					'<td>' . $results[$i]['sozvon'] . '</td>' .
					'<td>' . $results[$i]['lid'] . '</td>' .
					'<td>' . $results[$i]['chist_lid'] . '</td>' .
					'<td>' . $results[$i]['hold'] . '</td>' .
					'<td>' . $results[$i]['gr_hold'] . '</td>' .		
					'<td>' . $results[$i]['work_hours'] . '</td>' .
					'<td>' . $results[$i]['dozvon_h'] . '</td>' .
					'<td>' . $results[$i]['lid_h'] . '</td>' .
					'<td>' . $results[$i]['ndz_p'] . ' %</td>' .
					'<td>' . $results[$i]['ao_p'] . ' %</td>' .
					'<td>' . $results[$i]['dozvonlid_p'] . ' %</td>' .
					'<td>' . $results[$i]['contactlid_p'] . ' %</td>' .
					'<td>' . $results[$i]['holdlid_p'] . ' %</td>' .
					'<td>' . $results[$i]['grholdlid_p'] . ' %</td>' .		
					'<td>' . $results[$i]['zvon_hours'] . '</td>' .
					'<td>' . $avg_duration_seconds . '</td>' .
					'<td>' . $call_duration . '</td>' .
			      '</tr>';				
		}


        $i++;
    }

    if($_SESSION['login_role'] != 5) {
	    if($otdel_dozvon != 0 and $otdel_work_hours != 0) {
			$otdel_dozvon_h = round($otdel_dozvon / $otdel_work_hours, 1);
		}

		if($otdel_lid != 0 and $otdel_dozvon != 0) {
			$otdel_dozvonlid_p = round(($otdel_lid / $otdel_dozvon)*100, 1);
		}		

		if($_SESSION['login_role'] != 4) {

			if($otdel_lid != 0 and $otdel_work_hours != 0) {
				$otdel_lid_h = round($otdel_lid / $otdel_work_hours, 1);
			}

			if($otdel_avtoot + $otdel_ndz != 0 and $otdel_total_sum != 0) {
				$otdel_ndz_p = round((($otdel_avtoot + $otdel_ndz) / $otdel_total_sum)*100, 1);
			}

			if($otdel_avtoot != 0 and $otdel_total_sum != 0) {
				$otdel_ao_p = round(($otdel_avtoot / $otdel_total_sum)*100, 1);
			}

			if($otdel_lid != 0 and $otdel_total_sum != 0) {
				$otdel_contactlid_p = round(($otdel_lid / $otdel_total_sum)*100, 1);
			}

			if($otdel_hold != 0 and $otdel_lid != 0) {
				$otdel_holdlid_p = round(($otdel_hold / $otdel_lid)*100, 1);
			}

			if($otdel_gr_hold != 0 and $otdel_lid != 0) {
				$otdel_grholdlid_p = round(($otdel_gr_hold / $otdel_lid)*100, 1);
			}

			if($otdel_total_sum != 0 and $otdel_work_hours != 0) {
				$otdel_zvon_hours = round($otdel_total_sum / $otdel_work_hours, 1);
			}

			if($quantity_otdel != 0 and $duration_seconds_otdel != 0) {
				$avg_duration_seconds_otdel = round($duration_seconds_otdel / $quantity_otdel, 0);
			}

			if($quantity_callers_otdel != 0 and $call_duration_seconds_otdel != 0) {
				$avg_call_duration_otdel = round($call_duration_seconds_otdel / $quantity_callers_otdel, 0);
			}

		}

		if($_SESSION['login_role'] == 4) {
			echo '<tr class="table-info">' .
					'<th colspan="2">Итого по ' . $current_otdel . ' отделу</th>' .
					'<th>' . $otdel_total_sum . '</th>' .
					'<th>' . $otdel_avtoot . '</th>' .
					'<th>' . $otdel_ndz . '</th>' .
					'<th>' . $otdel_otkaz . '</th>' .
					'<th>' . $otdel_dozvon . '</th>' .
					'<th>' . $otdel_sozvon . '</th>' .
					'<th>' . $otdel_lid . '</th>' .
					'<th>' . $otdel_dozvon_h . '</th>' .
					'<th>' . $otdel_dozvonlid_p . ' %</th>' .
			      '</tr>';	
		} else {
			echo '<tr class="table-info">' .
					'<th colspan="2">Итого по ' . $current_otdel . ' отделу</th>' .
					'<th>' . $otdel_total_sum . '</th>' .
					'<th>' . $otdel_avtoot . '</th>' .
					'<th>' . $otdel_ndz . '</th>' .
					'<th>' . $otdel_otkaz . '</th>' .
					'<th>' . $otdel_dozvon . '</th>' .
					'<th>' . $otdel_sozvon . '</th>' .
					'<th>' . $otdel_lid . '</th>' .
					'<th>' . $otdel_chist_lid . '</th>' .
					'<th>' . $otdel_hold . '</th>' .
					'<th>' . $otdel_gr_hold . '</th>' .		
					'<th>-</th>' .
					'<th>' . $otdel_dozvon_h . '</th>' .
					'<th>' . $otdel_lid_h . '</th>' .
					'<th>' . $otdel_ndz_p . ' %</th>' .
					'<th>' . $otdel_ao_p . ' %</th>' .
					'<th>' . $otdel_dozvonlid_p . ' %</th>' .
					'<th>' . $otdel_contactlid_p . ' %</th>' .
					'<th>' . $otdel_holdlid_p . ' %</th>' .
					'<th>' . $otdel_grholdlid_p . ' %</th>' .		
					'<th>' . $otdel_zvon_hours . '</th>' .
					'<th>' . $avg_duration_seconds_otdel . '</th>' .
					'<th>' . $avg_call_duration_otdel . '</th>' .
			      '</tr>';			      	
		}
	    
		$otdel_total_sum = 0;
		$otdel_avtoot = 0;
		$otdel_ndz = 0;
		$otdel_otkaz = 0;
		$otdel_dozvon = 0;
		$otdel_sozvon = 0;
		$otdel_lid = 0;
		$otdel_chist_lid = 0;
		$otdel_hold = 0;
		$otdel_gr_hold = 0;
		$otdel_work_hours = 0;
		$otdel_dozvon_h = 0;
		$otdel_lid_h = 0;
		$otdel_ndz_p = 0;
		$otdel_ao_p = 0;
		$otdel_dozvonlid_p = 0;
		$otdel_contactlid_p = 0;
		$otdel_holdlid_p = 0;
		$otdel_grholdlid_p = 0;
		$otdel_zvon_hours = 0;
		$quantity_otdel = 0;
		$duration_seconds_otdel = 0;
		$avg_duration_seconds_otdel = '-';
		$quantity_callers_otdel = 0;
		$call_duration_seconds_otdel = 0;
		$avg_call_duration_otdel = '-';    	
    }

}

if($display_all_tr and $_SESSION['login_role'] != 5) {

	if($all_dozvon != 0 and $all_work_hours != 0) {
		$all_dozvon_h = round($all_dozvon / $all_work_hours, 1);
	}

	if($all_lid != 0 and $all_dozvon != 0) {
		$all_dozvonlid_p = round(($all_lid / $all_dozvon)*100, 1);
	}

	if($_SESSION['login_role'] != 4) {	

		if($all_lid != 0 and $all_work_hours != 0) {
			$all_lid_h = round($all_lid / $all_work_hours, 1);
		}

		if($all_avtoot + $all_ndz != 0 and $all_total_sum != 0) {
			$all_ndz_p = round((($all_avtoot + $all_ndz) / $all_total_sum)*100, 1);
		}

		if($all_avtoot != 0 and $all_total_sum != 0) {
			$all_ao_p = round(($all_avtoot / $all_total_sum)*100, 1);
		}

		if($all_lid != 0 and $all_total_sum != 0) {
			$all_contactlid_p = round(($all_lid / $all_total_sum)*100, 1);
		}

		if($all_hold != 0 and $all_lid != 0) {
			$all_holdlid_p = round(($all_hold / $all_lid)*100, 1);
		}

		if($all_gr_hold != 0 and $all_lid != 0) {
			$all_grholdlid_p = round(($all_gr_hold / $all_lid)*100, 1);
		}

		if($all_total_sum != 0 and $all_work_hours != 0) {
			$all_zvon_hours = round($all_total_sum / $all_work_hours, 1);
		}

		if($quantity_all_otdel != 0 and $duration_seconds_all_otdel != 0) {
			$avg_duration_seconds_all_otdel = round($duration_seconds_all_otdel / $quantity_all_otdel, 0);
		}

		if($quantity_callers_all_otdel != 0 and $call_duration_seconds_all_otdel != 0) {
			$avg_call_duration_all_otdel = round($call_duration_seconds_all_otdel / $quantity_callers_all_otdel, 0);
		}

	}

	if($_SESSION['login_role'] == 4) {
		echo '<tr class="table-success">' .
				'<th colspan="2">Итого суммарно</th>' .
				'<th>' . $all_total_sum . '</th>' .
				'<th>' . $all_avtoot . '</th>' .
				'<th>' . $all_ndz . '</th>' .
				'<th>' . $all_otkaz . '</th>' .
				'<th>' . $all_dozvon . '</th>' .
				'<th>' . $all_sozvon . '</th>' .
				'<th>' . $all_lid . '</th>' .
				'<th>' . $all_dozvon_h . '</th>' .
				'<th>' . $all_dozvonlid_p . ' %</th>' .
		      '</tr>';		
    } else {
		echo '<tr class="table-success">' .
				'<th colspan="2">Итого суммарно</th>' .
				'<th>' . $all_total_sum . '</th>' .
				'<th>' . $all_avtoot . '</th>' .
				'<th>' . $all_ndz . '</th>' .
				'<th>' . $all_otkaz . '</th>' .
				'<th>' . $all_dozvon . '</th>' .
				'<th>' . $all_sozvon . '</th>' .
				'<th>' . $all_lid . '</th>' .
				'<th>' . $all_chist_lid . '</th>' .
				'<th>' . $all_hold . '</th>' .
				'<th>' . $all_gr_hold . '</th>' .		
				'<th>-</th>' .
				'<th>' . $all_dozvon_h . '</th>' .
				'<th>' . $all_lid_h . '</th>' .
				'<th>' . $all_ndz_p . ' %</th>' .
				'<th>' . $all_ao_p . ' %</th>' .
				'<th>' . $all_dozvonlid_p . ' %</th>' .
				'<th>' . $all_contactlid_p . ' %</th>' .
				'<th>' . $all_holdlid_p . ' %</th>' .
				'<th>' . $all_grholdlid_p . ' %</th>' .		
				'<th>' . $all_zvon_hours . '</th>' .
				'<th>' . $avg_duration_seconds_all_otdel . '</th>' .
				'<th>' . $avg_call_duration_all_otdel . '</th>' .
		      '</tr>';	
    }

}			 
		 
?>					
