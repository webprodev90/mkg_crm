<table class="table m-0 table-actions-bar nowrap table-bordered" cellspacing="0" width="100%"
    id="datatable1">
    <thead>
        <tr class="table-head-tr">
            <th class="table-params">Названия строк</th>
            <th class="table-count">Количество по полю Телефон</th>
            <th class="table-percent">Сумма по полю Телефон2</th>
        </tr>
    </thead>

    <?php

    $busy_percent = 0;
    $cancel_percent = 0;
    $chanunavail_percent = 0;
    $congestion_percent = 0;
    $noanswer_percent = 0;
    $ао_percent = 0;
    $operator_answered_percent = 0;
    $sent_robot_percent = 0;
    $dozvon_percent = 0;
    $live_connect = 0;
    $conversion_lead_call = 0;
    $avg_dozvon = 0;

    if((int) $params_quantity['total'] !== 0) {
        $busy_percent = round($params_quantity['BUSY'] / $params_quantity['total'] * 100, 2);
        $cancel_percent = round($params_quantity['CANCEL'] / $params_quantity['total'] * 100, 2);
        $chanunavail_percent = round($params_quantity['CHANUNAVAIL'] / $params_quantity['total'] * 100, 2);
        $congestion_percent = round($params_quantity['CONGESTION'] / $params_quantity['total'] * 100, 2);
        $noanswer_percent = round($params_quantity['NOANSWER'] / $params_quantity['total'] * 100, 2);
        $ао_percent = round($params_quantity['АО'] / $params_quantity['total'] * 100, 2);
        $operator_answered_percent = round($params_quantity['operator_answered'] / $params_quantity['total'] * 100, 2);
        $sent_robot_percent = round($params_quantity['sent_robot'] / $params_quantity['total'] * 100, 2);
        $live_connect = round($dozvon / $params_quantity['total'] * 100, 2);
        $dozvon_percent = round($dozvon / $params_quantity['total'] * 100, 2);
    }

    if($dozvon !== 0) {
        $conversion_lead_call = round($total_count_leads / $dozvon * 100, 2);
    }

    if($caller !== 0) {
        $avg_dozvon = round($dozvon / $caller, 2);
    }

    ?>

    <tbody class="table-request">
        <tr>
            <td class="table-params" name="id">BUSY</td>
            <td class="table-count" name="id"><?= $params_quantity['BUSY']; ?></td>
            <td class="table-percent" name="id"><?= $busy_percent; ?>%</td>
        </tr>
        <tr>
            <td class="table-params" name="id">CANCEL</td>
            <td class="table-count" name="id"><?= $params_quantity['CANCEL']; ?></td>
            <td class="table-percent" name="id"><?= $cancel_percent; ?>%</td>
        </tr>  
        <tr>
            <td class="table-params" name="id">CHANUNAVAIL</td>
            <td class="table-count" name="id"><?= $params_quantity['CHANUNAVAIL']; ?></td>
            <td class="table-percent" name="id"><?= $chanunavail_percent; ?>%</td>
        </tr>
        <tr>
            <td class="table-params" name="id">CONGESTION</td>
            <td class="table-count" name="id"><?= $params_quantity['CONGESTION']; ?></td>
            <td class="table-percent" name="id"><?= $congestion_percent; ?>%</td>
        </tr>
        <tr>
            <td class="table-params" name="id">NOANSWER</td>
            <td class="table-count" name="id"><?= $params_quantity['NOANSWER']; ?></td>
            <td class="table-percent" name="id"><?= $noanswer_percent; ?>%</td>
        </tr>
        <tr>
            <td class="table-params" name="id">АО</td>
            <td class="table-count" name="id"><?= $params_quantity['АО']; ?></td>
            <td class="table-percent" name="id"><?= $ао_percent; ?>%</td>
        </tr>  
        <tr>
            <td class="table-params" name="id">Ответил оператор</td>
            <td class="table-count" name="id"><?= $params_quantity['operator_answered']; ?></td>
            <td class="table-percent" name="id"><?= $operator_answered_percent; ?>%</td>
        </tr>
        <tr>
            <td class="table-params" name="id">Ушло на робота</td>
            <td class="table-count" name="id"><?= $params_quantity['sent_robot']; ?></td>
            <td class="table-percent" name="id"><?= $sent_robot_percent; ?>%</td>
        </tr> 
        <tr class="table-info">
            <td class="table-params" name="id">Общий итог</td>
            <td class="table-count" name="id"><?= $params_quantity['total']; ?></td>
            <td class="table-percent" name="id">100%</td>
        </tr>   
        <tr>
            <td class="table-params" name="id">Дозвон</td>
            <td class="table-count" name="id"><?= $dozvon; ?></td>
            <td class="table-percent" name="id"><?= $dozvon_percent; ?>%</td>
        </tr> 
        <tr>
            <td class="table-params" name="id">Конверсия из отвеченных/в дозвоны</td>
            <td class="table-count" name="id"><?= $live_connect; ?>%</td>
            <td class="table-percent" name="id"></td>
        </tr> 
        <tr>
            <td class="table-params" name="id">Конверсия лид/дозвон</td>
            <td class="table-count" name="id"><?= $conversion_lead_call; ?>%</td>
            <td class="table-percent" name="id"></td>
        </tr> 
        <tr>
            <td class="table-params" name="id">Средний показатель дозвонов</td>
            <td class="table-count" name="id"><?= $avg_dozvon; ?></td>
            <td class="table-percent" name="id"></td>
        </tr> 

        <?php
        foreach($count_leads as $key => $value) {
        ?>
            <tr>
                <td class="table-params" name="id">Лидов (отдел <?= $key; ?>)</td>
                <td class="table-count" name="id"><?= $value; ?></td>
                <td class="table-percent" name="id"></td>
            </tr> 
        <?php            
        } 
        ?> 

        <tr>
            <td class="table-params" name="id">Сумма минут звонков</td>
            <td class="table-count" name="id"><?= $params_quantity['sum_minutes_сall']; ?></td>
            <td class="table-percent" name="id"></td>
        </tr>                        
    </tbody>
</table>