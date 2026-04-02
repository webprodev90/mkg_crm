<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config2.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd2.php';

class PulseCallCenter {
    private $db_connect;
    private $data;
    private $response;
    private $pf;

    function __construct() {
        global $db_connect;
        global $db_connect2;
        $this->db_connect = $db_connect;
        $this->db_connect2 = $db_connect2;
        $this->pf = BEZ_DBPREFIX;
        if(!empty($_POST))
            $this->data = $_POST;
    }

    public function set($data) {
        return $this->data = $data;
    }

    public function handle_action($action) {
        # Основной метод, вызывающий событие из action

        switch($action) {      
            case 'get_pulse_per_day':
                $this->get_pulse_per_day();
                break;
            case 'get_pulse_for_dates':
                $this->get_pulse_for_dates();
                break;  
            case 'get_info_atc':
                $this->get_info_atc();
                break;    
            case 'get_trunks':
                return $this->get_trunks();  
            case 'get_mobile_operators_stats':
                $this->get_mobile_operators_stats();
                break;               
        }
    }

    public function get_pulse_per_day() {

        $where_trunk = '';

        if(isset($this->data['trunk'])) {
            $trunk = $this->data['trunk'];
            if($trunk == 'Не указано') {
                $where_trunk = 'AND lg_user_event.trunk IS NULL';
            } else {
                $where_trunk = 'AND lg_user_event.trunk = "' . $trunk . '"';
            }
        }

        $res = $this->db_connect->query("
                WITH RECURSIVE time_intervals AS (
                    SELECT 
                        '08:00' as time_interval,
                        CAST('07:50' AS TIME) as start_time,
                        CAST('08:00' AS TIME) as end_time
                    
                    UNION ALL
                    
                    SELECT 
                        DATE_FORMAT(ADDTIME(end_time, '00:10:00'), '%H:%i'),
                        ADDTIME(start_time, '00:10:00'),
                        ADDTIME(end_time, '00:10:00')
                    FROM time_intervals 
                    WHERE start_time < '19:50'
                ), wt_main as (
                    SELECT 
                        time_interval as time_period,
                        SUM(CASE WHEN status_id = 6 or status_id = 8 or status_id = 9 or status_id = 21 or status_id = 11 or status_id = 22 or status_id = 24 or status_id = 25 or status_id = 26 or status_id = 27 or status_id = 28 or status_id = 29 or status_id = 30 or status_id = 31 or status_id = 32 THEN 1 ELSE 0 END) AS contacts,
                        SUM(CASE WHEN status_id = 15 or status_id = 6 or status_id = 9 or status_id = 21 or status_id = 11 or status_id = 16 or status_id = 23 or status_id = 24 or status_id = 25 or status_id = 26 or status_id = 28 or status_id = 29 or status_id = 30 or status_id = 31 or status_id = 32 THEN 1 ELSE 0 END) AS dozvon,
                        COUNT(DISTINCT user_id) AS caller
                    FROM time_intervals ti
                    LEFT JOIN (SELECT lue0.* FROM (SELECT lg_user_event.* FROM lg_user_event WHERE DATE(lg_user_event.row_change_time) = '{$this->data['date_start']}' {$where_trunk} GROUP BY DATE(lg_user_event.row_change_time), RIGHT(lg_user_event.phone_number, 10)) lue0  LEFT JOIN bez_reg ON bez_reg.id = lue0.user_id WHERE bez_reg.role = 5) lue ON 
                        DATE_ADD(CAST(lue.row_change_time AS TIME), INTERVAL 1 HOUR) >= ti.start_time 
                        AND DATE_ADD(CAST(lue.row_change_time AS TIME), INTERVAL 1 HOUR) < ti.end_time
                        AND lue.user_id <> 0
                    GROUP BY ti.time_interval, ti.start_time, ti.end_time
                    ORDER BY ti.start_time
                ), wt_leads as (
                    SELECT time_interval as time_period, COUNT(count_sale) as leads
                    FROM time_intervals ti 
                    LEFT JOIN (SELECT sl0.phone_number, lue.row_change_time AS date_time, sl0.count_sale
                            FROM (SELECT bsr1.*, bub1.phone_number, COUNT(bsr1.id) AS count_sale
                            FROM bez_sale_request bsr1
                            JOIN bez_unprocessed_base bub1
                            ON bsr1.request_id = bub1.id
                            WHERE bsr1.date_time BETWEEN '{$this->data['date_start']} 00:00:00' AND '{$this->data['date_end']} 23:59:59'
                            AND bsr1.partner_id <> 65
                            AND NOT EXISTS (SELECT * FROM bez_sale_request bsr2 WHERE bsr1.request_id = bsr2.request_id and bsr2.partner_id = 65 and bsr2.date_time BETWEEN bsr1.date_time and DATE_FORMAT(bsr1.date_time, '%y-%m-%d 23:59:59') AND (NOT EXISTS (SELECT * FROM bez_sale_request bsr3 WHERE bsr1.id <> bsr3.id and bsr1.request_id = bsr3.request_id and bsr3.partner_id <> 65 and bsr3.date_time BETWEEN bsr1.date_time and bsr2.date_time)))
                            GROUP BY bsr1.user_id, bsr1.request_id, DATE_FORMAT(date_time, '%Y-%m-%d')) sl0 
                            LEFT JOIN (SELECT RIGHT(phone_number, 10) AS phone_number, row_change_time, status_id FROM lg_user_event WHERE status_id = 15 {$where_trunk} GROUP BY DATE(row_change_time), RIGHT(phone_number, 10)) lue ON DATE(sl0.date_time) = DATE(lue.row_change_time) AND sl0.phone_number = RIGHT(lue.phone_number, 10)) sl
                    ON DATE_ADD(CAST(sl.date_time AS TIME), INTERVAL 1 HOUR) >= ti.start_time 
                        AND DATE_ADD(CAST(sl.date_time AS TIME), INTERVAL 1 HOUR) < ti.end_time
                        AND DATE(sl.date_time) = '{$this->data['date_start']}'
                    GROUP BY ti.time_interval, ti.start_time, ti.end_time
                    ORDER BY ti.start_time
                )
                    SELECT wt_main.time_period,
                    ROUND(IFNULL(wt_main.dozvon / (wt_main.contacts + wt_leads.leads) * 100, 0), 0) AS dozvoncontacts,
                    ROUND(IFNULL(wt_main.dozvon / wt_main.caller, 0), 0) AS count_calls,
                    ROUND(IFNULL((wt_leads.leads / wt_main.dozvon) * 100, 0), 1) AS dozvonlid
                    FROM wt_main JOIN wt_leads
                    ON wt_main.time_period = wt_leads.time_period;
        ");

        $statistics = NULL;
        while($row = $res->fetchAssoc()) {
            $statistics[] = $row;
        }

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($statistics);  
    }    

    public function get_pulse_for_dates() {

        $where_trunk = '';

        if(isset($this->data['trunk'])) {
            $trunk = $this->data['trunk'];
            if($trunk == 'Не указано') {
                $where_trunk = 'WHERE lg_user_event.trunk IS NULL';
            } else {
                $where_trunk = 'WHERE lg_user_event.trunk = "' . $trunk . '"';
            }
        }

        $res = $this->db_connect->query("
                    WITH RECURSIVE date_range AS (
                        SELECT 
                            CAST('{$this->data['date_start']}' AS DATE) as date_day  

                        UNION ALL

                        SELECT 
                            DATE_ADD(date_day, INTERVAL 1 DAY)
                        FROM date_range 
                        WHERE date_day < CAST('{$this->data['date_end']}' AS DATE)  
                    ), wt_main as (
                    SELECT 
                        date_day,
                        SUM(CASE WHEN status_id = 6 or status_id = 8 or status_id = 9 or status_id = 21 or status_id = 11 or status_id = 22 or status_id = 24 or status_id = 25 or status_id = 26 or status_id = 27 or status_id = 28 or status_id = 29 or status_id = 30 or status_id = 31 or status_id = 32 THEN 1 ELSE 0 END) AS contacts,
                        SUM(CASE WHEN status_id = 15 or status_id = 6 or status_id = 9 or status_id = 21 or status_id = 11 or status_id = 16 or status_id = 23 or status_id = 24 or status_id = 25 or status_id = 26 or status_id = 28 or status_id = 29 or status_id = 30 or status_id = 31 or status_id = 32 THEN 1 ELSE 0 END) AS dozvon,
                        COUNT(DISTINCT user_id) AS caller
                    FROM date_range dr
                    LEFT JOIN (SELECT lue0.* FROM (SELECT lg_user_event.* FROM lg_user_event {$where_trunk} GROUP BY DATE(lg_user_event.row_change_time), RIGHT(lg_user_event.phone_number, 10)) lue0 LEFT JOIN bez_reg ON bez_reg.id = lue0.user_id WHERE bez_reg.role = 5) lue ON DATE(lue.row_change_time) = dr.date_day AND lue.user_id <> 0
                    GROUP BY dr.date_day
                    ORDER BY dr.date_day
                ), wt_leads as (
                    SELECT date_day, COUNT(count_sale) as leads
                    FROM date_range dr
                    LEFT JOIN (SELECT bsr1.*, bub1.phone_number, COUNT(bsr1.id) AS count_sale
                    FROM bez_sale_request bsr1
                    JOIN bez_unprocessed_base bub1
                    ON bsr1.request_id = bub1.id
                    WHERE bsr1.date_time BETWEEN '{$this->data['date_start']} 00:00:00' AND '{$this->data['date_end']} 23:59:59'
                    AND bsr1.partner_id <> 65
                    AND NOT EXISTS (SELECT * FROM bez_sale_request bsr2 WHERE bsr1.request_id = bsr2.request_id and bsr2.partner_id = 65 and bsr2.date_time BETWEEN bsr1.date_time and DATE_FORMAT(bsr1.date_time, '%y-%m-%d 23:59:59') AND (NOT EXISTS (SELECT * FROM bez_sale_request bsr3 WHERE bsr1.id <> bsr3.id and bsr1.request_id = bsr3.request_id and bsr3.partner_id <> 65 and bsr3.date_time BETWEEN bsr1.date_time and bsr2.date_time)))
                    GROUP BY bsr1.user_id, bsr1.request_id, DATE_FORMAT(date_time, '%Y-%m-%d')) sl
                    ON DATE(sl.date_time) = dr.date_day
                    GROUP BY dr.date_day
                    ORDER BY dr.date_day
                )
                    SELECT DATE_FORMAT(wt_main.date_day, '%d.%m.%Y') AS date_day,
                    ROUND(IFNULL(wt_main.dozvon / (wt_main.contacts + wt_leads.leads) * 100, 0), 0) AS dozvoncontacts,
                    ROUND(IFNULL(wt_main.dozvon / wt_main.caller, 0), 0) AS count_calls,
                    ROUND(IFNULL((wt_leads.leads / wt_main.dozvon) * 100, 0), 1) AS dozvonlid
                    FROM wt_main JOIN wt_leads
                    ON wt_main.date_day = wt_leads.date_day;
        ");

        $statistics = NULL;
        while($row = $res->fetchAssoc()) {
            $statistics[] = $row;
        }

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($statistics);  
    }  

    public function get_info_atc() {

        $info_operator_states = [];

        $ch = curl_init();

        $queryURL = 'http://83.222.25.208/crm/apitel/service/dfb1f9gn4w984fbfad8f4.php?queue=1901';

        curl_setopt($ch, CURLOPT_URL, $queryURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result1 = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $result1 = json_decode($result1, true);

        $query_request1 = "
            DELETE FROM lead_filling_stats 
            WHERE end_datetime IS NULL AND start_datetime < DATE_SUB(NOW(), INTERVAL 10 MINUTE);
        ";

        $this->db_connect->query($query_request1);

        $res = $this->db_connect->query("
                SELECT id, name, id_otdel, id_atc 
                FROM bez_reg 
                WHERE role = 5 AND id_atc > 100 AND id_atc < 200 
                ORDER BY bez_reg.id_otdel, bez_reg.name ASC;
        ");

        $result2 = NULL;
        while($row = $res->fetchAssoc()) {
            $result2[] = $row;
        }

        if($result2) {
            foreach($result2 as $item) {

                $foundElement = array_filter($result1, function($var) use($item) {
                    return $var['number'] == $item['id_atc'];
                });

                if (!empty($foundElement)) {
                    $firstFound = reset($foundElement);

                    if(isset($firstFound['calls_taken'])) {
                        $calls_taken = $firstFound['calls_taken'];
                    } else {
                        $calls_taken = '-';
                    }

                    if(isset($firstFound['last_call'])) {
                        $last_call = $firstFound['last_call'];
                    } else {
                        $last_call = '-';
                    }

                    if(isset($firstFound['status'])) {
                        $status = $firstFound['status'];
                    } else {
                        $status = '-';
                    }
                } else {
                    $calls_taken = '-';
                    $last_call = '-';
                    $status = '-';
                }

                if(strtolower($status) != 'not in use') {

                    $query_request2 = "
                        UPDATE lead_filling_stats 
                        SET is_completed = 1
                        WHERE user_id = '{$item['id']}' AND end_datetime IS NOT NULL AND is_completed = 0;
                    ";

                    $this->db_connect->query($query_request2);

                }

                if(strtolower($status) == 'in call' || strtolower($status) == 'ringing' || strtolower($status) == 'in use') {
                    $status = 'Разговаривает';
                    $last_call = '-';
                } elseif(strtolower($status) == 'busy') {
                    $status = 'Занят';
                } elseif($status == '-') {
                    $status = 'Не в сети';
                } elseif(strtolower($status) == 'not in use') {

                    $res3 = $this->db_connect->query("
                        SELECT COUNT(CASE WHEN end_datetime IS NULL THEN lead_filling_stats.id END) as count_filled_lead,
                               COUNT(CASE WHEN end_datetime IS NOT NULL AND is_completed = 0 THEN lead_filling_stats.id END) as count_waiting_call
                        FROM lead_filling_stats
                        WHERE user_id = {$item['id']};
                    ");

                    $result3 = $res3->fetchAssoc();

                    if($result3['count_filled_lead'] > 0) {

                        $status = 'Заполняет лид'; 
                        $last_call = '-';

                    } elseif($result3['count_waiting_call'] > 0) {

                        $duration_seconds = 0;

                        $res4 = $this->db_connect->query("
                            SELECT id, duration_seconds
                            FROM lead_filling_stats 
                            WHERE user_id = {$item['id']} AND end_datetime IS NOT NULL AND is_completed = 0 
                            ORDER BY id DESC 
                            LIMIT 1;
                        ");

                        if($res4->getNumRows() > 0) {
                            $row4 = $res4->fetchAssoc();
                            $duration_seconds = $row4['duration_seconds'];
                        }

                        $status = 'Ожидает звонка';
                        $last_call_num = (int) preg_replace('/[^0-9]/', '', $last_call);
                        $last_call = $last_call_num - (int) $duration_seconds;
                        $last_call = $last_call . ' secs ago';

                    } else {

                        $status = 'Нет звонков'; 

                   }
                    
                }

                $info_operator_states[] = [
                    "id_otdel" => $item['id_otdel'],
                    "name" => $item['name'],
                    "calls_taken" => $calls_taken,
                    "last_call" => $last_call,
                    "status" => $status
                ]; 
            } 
        }


        if(!empty($this->data['view_name'])) {
            $path = $_SERVER['DOCUMENT_ROOT'] . '/scripts/pulse_call_center/view/' . $this->data['view_name'] . '.php';
            include($path);
        } else {
            http_response_code($http_code);
            header('Content-Type: application/json');
            echo json_encode($info_operator_states);              
        }

    }

    public function get_trunks() {
        $res = $this->db_connect->query("
            SELECT IFNULL(trunk, 'Не указано') AS trunk_name
            FROM lg_user_event 
            GROUP BY trunk;
        ");

        $trunks = NULL;
        while($row = $res->fetchAssoc()) {
            $trunks[] = $row;
        }

        return $trunks;
    }  

    public function get_operator($operator) {
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

    public function get_mobile_operators_stats() {
        $trunks_operators = [];

        $res = $this->db_connect2->query("
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
            WHERE DATE(FROM_UNIXTIME(field_383)) BETWEEN '{$this->data['date_start']}' AND '{$this->data['date_end']}' AND field_229 REGEXP '^[0-9]+$'
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
            WHERE field_333 <> '' AND DATE(FROM_UNIXTIME(field_209)) BETWEEN '{$this->data['date_start']}' AND '{$this->data['date_end']}' AND field_211 REGEXP '^[0-9]+$';
        ");

        $phones_atc = NULL;
        while($row = $res->fetchAssoc()) {
            $phones_atc[] = $row;
        }

        $res2 = $this->db_connect->query("
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
                WHERE DATE(row_change_time) BETWEEN '{$this->data['date_start']}' AND '{$this->data['date_end']}'
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

        foreach($phones_atc as $row) {

            $res3 = $this->db_connect->query('SELECT mobile_operator
                                        FROM def_codes 
                                        WHERE LEFT("' . $row['phone_number'] . '", 3) = defcode AND RIGHT("' . $row['phone_number'] . '", 7) BETWEEN from_code AND to_code;');

            if ($res3->getNumRows() > 0) {  
                $row3 = $res3->fetchAssoc();
                $mobile_operator = $this->get_operator($row3['mobile_operator']);
            } else {
                $mobile_operator = 'Оператор не определен';
            }

            $trunk = $row['trunk'];
            if(!isset($trunks_operators[$trunk][$mobile_operator])) {
                $trunks_operators[$trunk][$mobile_operator] = ['count_phones' => 0, 'count_dozvon' => 0,'count_operator_answered' => 0, 'no_ao' => 0, 'percent_dozvon' => 0];
            }
                
            $trunks_operators[$trunk][$mobile_operator]['count_phones'] += 1;
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

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($trunks_operators);  

    }   

}

if(!empty($_POST['action'])) {
    $action = $_POST['action'];
    unset($_POST['action']);
    $obj = new PulseCallCenter();
    $obj->handle_action($action);
}