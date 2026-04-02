<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config2.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd2.php';
/*
$total_contacts = 0;
$contacts = [];
$res = $db_connect2->query("
    SELECT RIGHT(field_229, 10) AS phone_number,
            CASE
                WHEN LOWER(field_521) LIKE '%multifon%' 
                    THEN 'Мегафон'
                WHEN LOWER(field_521) LIKE '%modem%' 
                    THEN 'Модем'
                WHEN LOWER(field_521) LIKE '%rostelecom%' 
                    THEN 'Ростелеком'
                WHEN LOWER(field_521) LIKE '%beeline%' 
                    THEN 'Билайн'
                ELSE 'Все остальное'
            END AS trunk, 
            IFNULL(app_global_lists_choices.name, '') AS campaign, 
            DATE(FROM_UNIXTIME(field_383)) AS date_time,
            IF(field_473 <> '' AND field_473 <> 00901, 1, 0) AS C,
            IF(field_232 LIKE 'ANSWER%', 1, 0) AS DE,
            IF(field_232 LIKE 'ANSWER%', TIMESTAMPDIFF(SECOND, FROM_UNIXTIME(field_230), FROM_UNIXTIME(field_231)), 0) AS F,
            IF(field_232 LIKE 'ANSWER%', TIMESTAMPDIFF(SECOND, FROM_UNIXTIME(field_230), FROM_UNIXTIME(field_231)) / 60 * 1.44, 0) AS J
    FROM app_entity_21
    LEFT JOIN app_global_lists_choices
    ON app_global_lists_choices.id = app_entity_21.field_479
    WHERE DATE(FROM_UNIXTIME(field_383)) = '2026-02-19';
");

$phones_atc = NULL;
while($row = $res->fetchAssoc()) {
    $phones_atc[] = $row;
}

$res2 = $db_connect->query("
    SELECT
    RIGHT(bez_unprocessed_base.phone_number, 10) AS phone_number,
    DATE(bsr1.date_time) AS date_time
    FROM (SELECT *, COUNT(bsr1.id) AS count_sale FROM bez_sale_request bsr1
        WHERE DATE(bsr1.date_time) = '2026-02-19'
        AND bsr1.partner_id <> 65
        AND NOT EXISTS (SELECT * FROM bez_sale_request bsr2 WHERE bsr1.request_id = bsr2.request_id and bsr2.partner_id = 65 and bsr2.date_time BETWEEN bsr1.date_time and DATE_FORMAT(bsr1.date_time, '%y-%m-%d 23:59:59') AND (NOT EXISTS (SELECT * FROM bez_sale_request bsr3 WHERE bsr1.id <> bsr3.id and bsr1.request_id = bsr3.request_id and bsr3.partner_id <> 65 and bsr3.date_time BETWEEN bsr1.date_time and bsr2.date_time)))
        GROUP BY user_id, request_id, DATE_FORMAT(date_time, '%Y-%m-%d')) bsr1
    JOIN bez_unprocessed_base
    ON bsr1.request_id = bez_unprocessed_base.id
    GROUP BY DATE(bsr1.date_time), RIGHT(bez_unprocessed_base.phone_number, 10);
");

$phones_leads = [];
while($row = $res2->fetchAssoc()) {
    $key = $row['phone_number'] . '|' . $row['date_time'];
    if(!isset($phones_leads[$key])) {
        $phones_leads[$key] = 1;
    }
}

foreach($phones_atc as $row) {
    $key = $row['phone_number'] . '|' . $row['date_time'];     
    if(isset($phones_leads[$key])) { 
        $total_contacts += 1;
        $phone = $row['phone_number'];
        if(isset($contacts[$phone])) {
            $contacts[$phone] += 1;
        } else {
            $contacts[$phone] = 1;
        }
    }
}

echo '<pre>';
print_r($contacts);
echo '</pre>';

$res5 = $db_connect2->query("
    SELECT field_335, COUNT(*)
    FROM app_entity_25
    GROUP BY field_335;
");
*/
/*
$res5 = $db_connect2->query("
SELECT RIGHT(field_211, 10) AS phone_number
            FROM app_entity_25
            WHERE DATE(FROM_UNIXTIME(field_209)) BETWEEN '2026-03-05' AND '2026-03-05'
            GROUP BY RIGHT(field_211, 10)
            ORDER BY RIGHT(field_211, 10);
");

$phones_atc = NULL;
while($row = $res5->fetchAssoc()) {
    $phones_atc[] = $row;
}

echo '<pre>';
print_r($phones_atc);
echo '</pre>';
*/


/*
$res5 = $db_connect2->query("
    SELECT *
    FROM app_entity_21
    WHERE FROM_UNIXTIME(field_383) BETWEEN '2026-03-10 15:38:00' AND '2026-03-10 15:39:00' AND field_232 <> 'CHANUNAVAIL' AND field_232 <> 'CONGESTION';
");
*/

function get_operator($operator) {
    $operator_name = 'Оператор не определен';

    switch($operator) {
        case $operator == 'Вымпелком Пао':  # Билайн
            $operator_name = 'Билайн';
            break;
        case $operator == 'Мегафон Пао': # Мегафон
            $operator_name = 'Мегафон'; 
            break;
        case $operator == 'Мобильные Телесистемы Пао':  # МТС
            $operator_name = 'МТС'; 
            break; 
        case $operator == 'Т2 Мобайл Ооо':  # Т2
            $operator_name = 'Т2'; 
            break;   
        case $operator == 'Городской телефон':  # Городской телефон'
            $operator_name = 'Городской телефон'; 
            break;   
        case $operator == 'Оператор не определен':  # Оператор не определен
            $operator_name = 'Оператор не определен'; 
            break; 
        default: # Все остальное
            $operator_name = 'Все остальное'; 
            break;
    }

    return $operator_name;
}

$trunks_operators = [];

$res = $db_connect2->query("
    SELECT RIGHT(field_229, 10) AS phone_number,
            CASE
                WHEN LOWER(field_521) LIKE '%multifon%' 
                    THEN 'Мегафон'
                WHEN LOWER(field_521) LIKE '%modem%' 
                    THEN 'Модем'
                WHEN LOWER(field_521) LIKE '%rostelecom%' 
                    THEN 'Ростелеком'
                WHEN LOWER(field_521) LIKE '%beeline%' 
                    THEN 'Билайн'
                WHEN LOWER(field_521) LIKE '%incognito%' 
                    THEN 'Инкогнито'
                ELSE 'Все остальное'
            END AS trunk, 
            DATE(FROM_UNIXTIME(field_383)) AS date_time,
            IF(field_473 <> '' AND field_473 <> 00901, 1, 0) AS operator_answered,
            IF(field_232 LIKE 'ANSWER%', 1, 0) AS dozvon_atc
    FROM app_entity_21
    WHERE DATE(FROM_UNIXTIME(field_383)) BETWEEN '2026-03-11' AND '2026-03-11' AND field_229 REGEXP '^[0-9]+$'
    UNION ALL
    SELECT RIGHT(field_211, 10) AS phone_number,
            CASE
                WHEN LOWER(field_333) LIKE '%multifon%' 
                    THEN 'Мегафон'
                WHEN LOWER(field_333) LIKE '%modem%' 
                    THEN 'Модем'
                WHEN LOWER(field_333) LIKE '%rostelecom%' 
                    THEN 'Ростелеком'
                WHEN LOWER(field_333) LIKE '%beeline%' 
                    THEN 'Билайн'
                WHEN LOWER(field_333) LIKE '%incognito%' 
                    THEN 'Инкогнито'
                ELSE 'Все остальное'
            END AS trunk, 
            DATE(FROM_UNIXTIME(field_209)) AS date_time,
            1 AS operator_answered,
            IF(field_335 = 100, 1, 0) AS dozvon_atc
    FROM app_entity_25
    WHERE field_333 <> '' AND DATE(FROM_UNIXTIME(field_209)) BETWEEN '2026-03-11' AND '2026-03-11' AND field_211 REGEXP '^[0-9]+$';
");

$phones_atc = NULL;
while($row = $res->fetchAssoc()) {
    $phones_atc[] = $row;
}
/*
echo '<pre>';
print_r($phones_atc);
echo '</pre>';
*/

$res2 = $db_connect->query("
    SELECT 
    RIGHT(lue.phone_number, 10) AS phone_number,
    DATE(lue.row_change_time) AS date_time
    FROM lg_user_event lue
    JOIN (
        SELECT 
            DATE(row_change_time) AS date_time,
            RIGHT(phone_number, 10) AS phone_short,
            MIN(row_change_time) AS first_time
        FROM lg_user_event
        WHERE DATE(row_change_time) BETWEEN '2026-03-11' AND '2026-03-11'
        GROUP BY DATE(row_change_time), RIGHT(phone_number, 10)
    ) AS first_records 
    ON DATE(lue.row_change_time) = first_records.date_time
       AND RIGHT(lue.phone_number, 10) = first_records.phone_short
       AND lue.row_change_time = first_records.first_time
    WHERE lue.status_id NOT IN (22, 10, 36, 8);
");


$phones_dozvon = [];
while($row = $res2->fetchAssoc()) {
    $key = $row['phone_number'] . '|' . $row['date_time'];
    if(!isset($phones_dozvon[$key])) {
        $phones_dozvon[$key] = 1;
    }
}
/*
echo '<pre>';
print_r($phones_dozvon);
echo '</pre>';
*/

foreach($phones_atc as $row) {

    $res3 = $db_connect->query('SELECT mobile_operator
                                FROM def_codes 
                                WHERE LEFT("' . $row['phone_number'] . '", 3) = defcode AND RIGHT("' . $row['phone_number'] . '", 7) BETWEEN from_code AND to_code;');

    if ($res3->getNumRows() > 0) {  
        $row3 = $res3->fetchAssoc();
        $mobile_operator = get_operator($row3['mobile_operator']);
    } else {
        $mobile_operator = 'Оператор не определен';
    }

    $trunk = $row['trunk'];
    if(!isset($trunks_operators[$trunk][$mobile_operator])) {
        $trunks_operators[$trunk][$mobile_operator] = ['count_phones' => 0, 'count_dozvon' => 0,'count_operator_answered' => 0, 'no_ao' => 0, 'percent_dozvon' => 0];
    }
        
    $trunks_operators[$trunk][$mobile_operator]['count_phones'] += 1;
    //$trunks_operators[$trunk][$mobile_operator]['count_dozvon'] += (int) $row['dozvon_atc'];
    $trunks_operators[$trunk][$mobile_operator]['count_operator_answered'] += (int) $row['operator_answered'];

    if($row['dozvon_atc'] == 1) {
        $key = $row['phone_number'] . '|' . $row['date_time'];
        if(isset($phones_dozvon[$key])) { 
            $trunks_operators[$trunk][$mobile_operator]['count_dozvon'] += 1;
        }   
    }

}

foreach($trunks_operators as $trunk => $mobile_operators) {
    
    foreach ($mobile_operators as $mobile_operator => $params) {

        if((int) $trunks_operators[$trunk][$mobile_operator]['count_operator_answered'] !== 0) {
            $trunks_operators[$trunk][$mobile_operator]['no_ao'] = round((int) $trunks_operators[$trunk][$mobile_operator]['count_dozvon'] / (int) $trunks_operators[$trunk][$mobile_operator]['count_operator_answered'] * 100, 1);
        }
        
        if((int) $trunks_operators[$trunk][$mobile_operator]['count_phones'] !== 0) {
            $trunks_operators[$trunk][$mobile_operator]['percent_dozvon'] = round((int) $trunks_operators[$trunk][$mobile_operator]['count_dozvon'] / (int) $trunks_operators[$trunk][$mobile_operator]['count_phones'] * 100, 1);
        }

    }
}

echo '<pre>';
print_r($trunks_operators);
echo '</pre>';

/*
$res3 = $db_connect->query("
    SELECT
    RIGHT(bez_unprocessed_base.phone_number, 10) AS phone_number,
    DATE(bsr1.date_time) AS date_time
    FROM (SELECT *, COUNT(bsr1.id) AS count_sale FROM bez_sale_request bsr1
        WHERE DATE(bsr1.date_time) BETWEEN '2026-02-19' AND '2026-02-19'
        AND bsr1.partner_id <> 65
        AND NOT EXISTS (SELECT * FROM bez_sale_request bsr2 WHERE bsr1.request_id = bsr2.request_id and bsr2.partner_id = 65 and bsr2.date_time BETWEEN bsr1.date_time and DATE_FORMAT(bsr1.date_time, '%y-%m-%d 23:59:59') AND (NOT EXISTS (SELECT * FROM bez_sale_request bsr3 WHERE bsr1.id <> bsr3.id and bsr1.request_id = bsr3.request_id and bsr3.partner_id <> 65 and bsr3.date_time BETWEEN bsr1.date_time and bsr2.date_time)))
        GROUP BY user_id, request_id, DATE_FORMAT(date_time, '%Y-%m-%d')) bsr1
    JOIN bez_unprocessed_base
    ON bsr1.request_id = bez_unprocessed_base.id
    GROUP BY DATE(bsr1.date_time), RIGHT(bez_unprocessed_base.phone_number, 10);
");

$phones_leads = [];
$total_contacts = 0;

    $res4 = $db_connect2->query("
        SELECT RIGHT(field_229, 10) AS phone_number,
                count(*) as count_contacts
        FROM app_entity_21
        WHERE DATE(FROM_UNIXTIME(field_383)) = '2026-02-19' AND RIGHT(field_229, 10) = '9509556846';
    ");

    if($res4->getNumRows() > 0) {
        $row2 = $res4->fetchAssoc();
        $total_contacts += (int) $row2['count_contacts'];
    }



$res = $db_connect2->query("
    SELECT *
    FROM app_entity_21
    WHERE field_232 LIKE 'ANSWER%' AND (field_521 NOT LIKE '%multifon%' AND field_521 NOT LIKE '%modem%' AND field_521 NOT LIKE '%rostelecom%' AND field_521 NOT LIKE '%beeline%')
    LIMIT 3;
");

$phones_atc = NULL;
while($row = $res->fetchAssoc()) {
    $phones_atc[] = $row;
}

echo '<pre>';
print_r($phones_atc);
echo '</pre>';
*/
/*
$start = microtime(true);
$res2 = $db_connect2->query("
    SELECT IFNULL(SUM(IF(field_232 = 'BUSY', 1, 0)), 0) AS BUSY,
    	   IFNULL(SUM(IF(field_232 = 'CANCEL', 1, 0)), 0) AS CANCEL,
    	   IFNULL(SUM(IF(field_232 = 'CHANUNAVAIL', 1, 0)), 0) AS CHANUNAVAIL,
    	   IFNULL(SUM(IF(field_232 = 'CONGESTION', 1, 0)), 0) AS CONGESTION,
    	   IFNULL(SUM(IF(field_232 = 'NOANSWER', 1, 0)), 0) AS NOANSWER,
    	   IFNULL(SUM(IF(field_232 LIKE 'ANSWER%', 1, 0)), 0) AS АО,
    	   IFNULL(SUM(IF(field_232 LIKE 'ANSWER%' AND field_473 <> 00901, 1, 0)), 0) AS operator_answered,
    	   IFNULL(SUM(IF(field_232 LIKE 'ANSWER%' AND field_473 = 00901, 1, 0)), 0) AS sent_robot,
    	   COUNT(id) AS total,
    	   IFNULL(CEILING(SUM(IF(field_230 <> 0 AND field_231 <> 0, TIMESTAMPDIFF(SECOND, FROM_UNIXTIME(field_230), FROM_UNIXTIME(field_231)), 0)) / 60), 0) AS sum_minutes_сall
    FROM app_entity_21
    WHERE DATE(FROM_UNIXTIME(field_383)) BETWEEN '2026-02-16' AND '2026-02-16';
");

$phones_atc2 = NULL;
while($row = $res2->fetchAssoc()) {
    $phones_atc2[] = $row;
}

echo '<pre>';
print_r($phones_atc2);
echo '</pre>';



//модифицированный код

$res3 = $db_connect->query("
    SELECT
    RIGHT(bez_unprocessed_base.phone_number, 10) AS phone_number,
    DATE(bsr1.date_time) AS date_time,
    bez_reg.id_otdel
    FROM (SELECT *, COUNT(bsr1.id) AS count_sale FROM bez_sale_request bsr1
        WHERE DATE(bsr1.date_time) BETWEEN '2026-02-16' AND '2026-02-16'
        AND bsr1.partner_id <> 65
        AND NOT EXISTS (SELECT * FROM bez_sale_request bsr2 WHERE bsr1.request_id = bsr2.request_id and bsr2.partner_id = 65 and bsr2.date_time BETWEEN bsr1.date_time and DATE_FORMAT(bsr1.date_time, '%y-%m-%d 23:59:59') AND (NOT EXISTS (SELECT * FROM bez_sale_request bsr3 WHERE bsr1.id <> bsr3.id and bsr1.request_id = bsr3.request_id and bsr3.partner_id <> 65 and bsr3.date_time BETWEEN bsr1.date_time and bsr2.date_time)))
        GROUP BY user_id, request_id, DATE_FORMAT(date_time, '%Y-%m-%d')) bsr1
    JOIN bez_unprocessed_base
    ON bsr1.request_id = bez_unprocessed_base.id
    JOIN bez_reg
    ON bsr1.user_id = bez_reg.id
    GROUP BY DATE(bsr1.date_time), RIGHT(bez_unprocessed_base.phone_number, 10);
");

$phones_leads = [];
while($row = $res3->fetchAssoc()) {
    $key = $row['phone_number'] . '|' . $row['date_time'];
    if(!isset($phones_leads[$key])) {
        $phones_leads[$key] = $row['id_otdel'];
    }
}
*/
/*
echo '<pre>';
print_r($phones_leads);
echo '</pre>';
*/
/*
$res5 = $db_connect->query("
    SELECT RIGHT(phone_number, 10) AS phone_number, DATE(row_change_time) AS date_time 
    FROM (SELECT * FROM lg_user_event GROUP BY DATE(row_change_time), RIGHT(phone_number, 10)) t 
    WHERE date(row_change_time) between '2026-02-16' and '2026-02-16' and HOUR(row_change_time) >= 0 and HOUR(row_change_time) < 24 AND (t.status_id = 15 or t.status_id = 6 or t.status_id = 9 or t.status_id = 21 or t.status_id = 11 or t.status_id = 16 or t.status_id = 23 or t.status_id = 24 or t.status_id = 25 or t.status_id = 26 or t.status_id = 28 or t.status_id = 29 or t.status_id = 30 or t.status_id = 31 or t.status_id = 32 or t.status_id = 33 or t.status_id = 34 or t.status_id = 35);
");

$phones_dozvon = [];
while($row = $res5->fetchAssoc()) {
    $key = $row['phone_number'] . '|' . $row['date_time'];
    if(!isset($phones_dozvon[$key])) {
        $phones_dozvon[$key] = 1;
    }
}
*/
/*
echo '<pre>';
print_r($phones_dozvon);
echo '</pre>';
*/
/*
$res21 = $db_connect2->query("
    SELECT RIGHT(field_229, 10) AS phone_number,
           DATE(FROM_UNIXTIME(field_383)) AS date_time,
           IF(field_232 = 'BUSY', 1, 0) AS BUSY,
           IF(field_232 = 'CANCEL', 1, 0) AS CANCEL,
           IF(field_232 = 'CHANUNAVAIL', 1, 0) AS CHANUNAVAIL,
           IF(field_232 = 'CONGESTION', 1, 0) AS CONGESTION,
           IF(field_232 = 'NOANSWER', 1, 0) AS NOANSWER,
           IF(field_232 LIKE 'ANSWER%', 1, 0) AS АО,
           IF(field_232 LIKE 'ANSWER%' AND field_473 <> 00901, 1, 0) AS operator_answered,
           IF(field_232 LIKE 'ANSWER%' AND field_473 = 00901, 1, 0) AS sent_robot,
           IF(field_230 <> 0 AND field_231 <> 0, TIMESTAMPDIFF(SECOND, FROM_UNIXTIME(field_230), FROM_UNIXTIME(field_231)), 0) AS second_сall
    FROM app_entity_21
    WHERE DATE(FROM_UNIXTIME(field_383)) BETWEEN '2026-02-16' AND '2026-02-16';
");

$phones_atc21 = ['BUSY' => 0,
                 'CANCEL' => 0,
                 'CHANUNAVAIL' => 0,
                 'CONGESTION' => 0,
                 'NOANSWER' => 0,
                 'АО' => 0,
                 'operator_answered' => 0,
                 'sent_robot' => 0,
                 'total' => 0,
                 'sum_minutes_сall' => 0];

$total_count_leads = 0;
$count_leads = [];

$dozvon = 0;

while($row = $res21->fetchAssoc()) {
    $phones_atc21['BUSY'] += (int) $row['BUSY'];
    $phones_atc21['CANCEL'] += (int) $row['CANCEL'];
    $phones_atc21['CHANUNAVAIL'] += (int) $row['CHANUNAVAIL'];
    $phones_atc21['CONGESTION'] += (int) $row['CONGESTION'];
    $phones_atc21['NOANSWER'] += (int) $row['NOANSWER'];
    $phones_atc21['АО'] += (int) $row['АО'];
    $phones_atc21['operator_answered'] += (int) $row['operator_answered'];
    $phones_atc21['sent_robot'] += (int) $row['sent_robot'];
    $phones_atc21['total'] += 1;
    $phones_atc21['sum_minutes_сall'] += (int) $row['second_сall'];

    $key = $row['phone_number'] . '|' . $row['date_time'];
    if(isset($phones_leads[$key])) { 
        $otdel = $phones_leads[$key];
        $total_count_leads += 1;
        if(isset($count_leads[$otdel])) {
            $count_leads[$otdel] += 1;
        } else {
            $count_leads[$otdel] = 1;
        }
        unset($phones_leads[$key]);
    }  

    if(isset($phones_dozvon[$key])) { 
        $dozvon += 1;
        unset($phones_dozvon[$key]);
    }  
}

$phones_atc21['sum_minutes_сall'] = ceil($phones_atc21['sum_minutes_сall'] / 60);

echo '<pre>';
print_r($phones_atc21);
echo '</pre>';

echo '<pre>';
print_r($count_leads);
echo '</pre>';

$res4 = $db_connect->query("
    SELECT COUNT(DISTINCT t.user_id) AS caller
    FROM (SELECT * FROM lg_user_event GROUP BY DATE(row_change_time), RIGHT(phone_number, 10)) t
    WHERE date(row_change_time) between '2026-02-16' and '2026-02-16';
    ");

$caller = 0;
if($res4->getNumRows() > 0) {
    $row = $res4->fetchAssoc();
    $caller = $row['caller'];
}

$end = microtime(true);
echo "Время выполнения: " . ($end - $start) . " сек.";
*/
?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<br>
Данные АТС
<br>
<!--
 <?= $total_contacts; ?>
<br>
Количество лидов:
 <?= $total_count_leads; ?>
<br>
Количество дозвонов:
 <?= $dozvon; ?>
<br>
Количество операторов:
 <?= $caller; ?>
-->
</body>
</html>


