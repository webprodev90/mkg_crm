<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config2.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd2.php';

class CallRates
{
    private $db_connect;
    private $db_connect2;
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
            case 'get_financial_stats':
                $this->get_financial_stats();
                break;    
            case 'get_call_stats':
                $this->get_call_stats();
                break; 
            case 'get_campaigns':
                return $this->get_campaigns();
            case 'get_operators':
                return $this->get_operators();      
        }

    }

    public function get_financial_stats() {

        $query_request_logs1 = "
            INSERT INTO logs (row_change_time, value, modul, text, user_id) VALUES (NOW(), 'select', 'financial_stats', 'start. date_start = {$this->data['date_start']}, date_end = {$this->data['date_end']}', '{$_SESSION['login_id']}');
        ";

        $this->db_connect->query($query_request_logs1);

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
                    IFNULL(app_global_lists_choices.name, '') AS campaign, 
                    DATE(FROM_UNIXTIME(field_383)) AS date_time,
                    FROM_UNIXTIME(field_383) AS full_date_time,
                    IF(field_473 <> '' AND field_473 <> 00901, 1, 0) AS C,
                    IF(field_232 LIKE 'ANSWER%', 1, 0) AS DE,
                    IF(field_232 LIKE 'ANSWER%', TIMESTAMPDIFF(SECOND, FROM_UNIXTIME(field_230), FROM_UNIXTIME(field_231)), 0) AS F,
                    IF(field_232 LIKE 'ANSWER%', 
                        CASE
                        WHEN LOWER(field_521) LIKE '%modem%' 
                            THEN IF(TIMESTAMPDIFF(SECOND, FROM_UNIXTIME(field_230), FROM_UNIXTIME(field_231)) >= 4, CEILING(TIMESTAMPDIFF(SECOND, FROM_UNIXTIME(field_230), FROM_UNIXTIME(field_231)) / 60) * 0.6, 0)
                        WHEN LOWER(field_521) LIKE '%rostelecom%' 
                            THEN TIMESTAMPDIFF(SECOND, FROM_UNIXTIME(field_230), FROM_UNIXTIME(field_231)) / 60 * 2
                        WHEN LOWER(field_521) LIKE '%beeline%' 
                            THEN TIMESTAMPDIFF(SECOND, FROM_UNIXTIME(field_230), FROM_UNIXTIME(field_231)) / 60 * 1.34
                        WHEN LOWER(field_521) LIKE '%incognito%' 
                            THEN TIMESTAMPDIFF(SECOND, FROM_UNIXTIME(field_230), FROM_UNIXTIME(field_231)) / 60 * 1.55
                        ELSE TIMESTAMPDIFF(SECOND, FROM_UNIXTIME(field_230), FROM_UNIXTIME(field_231)) / 60 * 1.44
                        END, 
                        0) AS J
            FROM app_entity_21
            LEFT JOIN app_global_lists_choices
            ON app_global_lists_choices.id = app_entity_21.field_479
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
            '-' AS campaign, 
            DATE(FROM_UNIXTIME(field_209)) AS date_time,
            FROM_UNIXTIME(field_209) AS full_date_time,
            1 AS C,
            IF(field_335 = 100, 1, 0) AS DE,
            IF(field_335 = 100 AND field_218 > 0, field_218, 0) AS F,
            IF(field_335 = 100 AND field_218 > 0, 
                CASE
                WHEN LOWER(field_333) LIKE '%modem%' 
                    THEN IF(field_218 >= 4, CEILING(field_218 / 60) * 0.6, 0)
                WHEN LOWER(field_333) LIKE '%rostelecom%' 
                    THEN field_218 / 60 * 2
                WHEN LOWER(field_333) LIKE '%beeline%' 
                    THEN field_218 / 60 * 1.34
                WHEN LOWER(field_333) LIKE '%incognito%' 
                    THEN field_218 / 60 * 1.55
                ELSE field_218 / 60 * 1.44
                END, 0) AS J
            FROM app_entity_25
            WHERE field_333 <> '' AND DATE(FROM_UNIXTIME(field_209)) BETWEEN '{$this->data['date_start']}' AND '{$this->data['date_end']}' AND field_211 REGEXP '^[0-9]+$'
            ORDER BY full_date_time DESC;
        ");

        $query_request_logs2 = "
            INSERT INTO logs (row_change_time, value, modul, text, user_id) VALUES (NOW(), 'select', 'financial_stats', 'end. date_start = {$this->data['date_start']}, date_end = {$this->data['date_end']}', '{$_SESSION['login_id']}');
        ";

        $this->db_connect->query($query_request_logs2);

        $phones_atc = [];
        while($row = $res->fetchAssoc()) {
            $phones_atc[] = $row;
        }

        $res2 = $this->db_connect->query("
            SELECT
            RIGHT(bez_unprocessed_base.phone_number, 10) AS phone_number,
            DATE(bsr1.date_time) AS date_time
            FROM (SELECT *, COUNT(bsr1.id) AS count_sale FROM bez_sale_request bsr1
                WHERE DATE(bsr1.date_time) BETWEEN '{$this->data['date_start']}' AND '{$this->data['date_end']}'
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

        $res3 = $this->db_connect->query("
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
        while($row = $res3->fetchAssoc()) {
            $key = $row['phone_number'] . '|' . $row['date_time'];
            if(!isset($phones_dozvon[$key])) {
                $phones_dozvon[$key] = 1;
            }
        }

        $sources = [];
        $regions = [];
        $campaigns = [];
        $trunks = [];
        $phones_leads_sources = $phones_leads;
        $phones_leads_regions = $phones_leads;
        $phones_leads_campaigns = $phones_leads;
        $phones_leads_trunks = $phones_leads;
        foreach($phones_atc as $row) {

            $res1 = $this->db_connect->query("
                SELECT max(bez_unprocessed_base.id), bez_unprocessed_base.source, bez_cities_group.name AS region
                FROM bez_unprocessed_base 
                JOIN bez_cities_group
                ON bez_unprocessed_base.auto_city_group = bez_cities_group.id
                WHERE phone_number = {$row['phone_number']};
            ");                

            $region = '';
            $source = '';
            if($res1->getNumRows() > 0) {
                $result1 = $res1->fetchAssoc();  
                $source = $result1['source'];
                $region = $result1['region'];
            }

            //Отчет для источника    

            if(array_key_exists($source, $sources)) {
                $sources[$source]['B'] += 1;
                $sources[$source]['C'] += $row['C'];
                $sources[$source]['F'] += $row['F'];
                $sources[$source]['J'] += $row['J'];
                if($row['trunk'] == 'Инкогнито') {
                    $sources[$source]['incognito_trunk'] += 1;
                } else {
                    $sources[$source]['other_trunk'] += 1;
                }
            } else {
                $sources[$source] = ['B' => 1, 
                                    'C' => $row['C'], 
                                    'D' => 0, 
                                    'E' => 0, 
                                    'F' => $row['F'], 
                                    'G' => 0, 
                                    'H' => 0, 
                                    'I' => 0, 
                                    'J' => $row['J'], 
                                    'K' => 0, 
                                    'L' => 0,
                                    'M' => 0,
                                    'N' => 0,
                                    'O' => 0,
                                    'incognito_trunk' => 0,
                                    'other_trunk' => 0
                                    ]; 
            }

            if($row['DE'] == 1) {
                $key = $row['phone_number'] . '|' . $row['date_time'];
                if(isset($phones_dozvon[$key])) { 
                    $sources[$source]['D'] += 1;
                }        
                if(isset($phones_leads_sources[$key])) { 
                    $sources[$source]['E'] += 1;
                    unset($phones_leads_sources[$key]);
                }
            }

            //Отчет для региона

            if(array_key_exists($region, $regions)) {
                $regions[$region]['B'] += 1;
                $regions[$region]['C'] += $row['C'];
                $regions[$region]['F'] += $row['F'];
                $regions[$region]['J'] += $row['J'];
                if($row['trunk'] == 'Инкогнито') {
                    $regions[$region]['incognito_trunk'] += 1;
                } else {
                    $regions[$region]['other_trunk'] += 1;
                }
            } else {
                $regions[$region] = ['B' => 1, 
                                    'C' => $row['C'], 
                                    'D' => 0, 
                                    'E' => 0, 
                                    'F' => $row['F'], 
                                    'G' => 0, 
                                    'H' => 0, 
                                    'I' => 0, 
                                    'J' => $row['J'], 
                                    'K' => 0, 
                                    'L' => 0,
                                    'M' => 0,
                                    'N' => 0,
                                    'O' => 0,
                                    'incognito_trunk' => 0,
                                    'other_trunk' => 0
                                    ]; 
            }

            if($row['DE'] == 1) {
                $key = $row['phone_number'] . '|' . $row['date_time'];
                if(isset($phones_dozvon[$key])) { 
                    $regions[$region]['D'] += 1;
                }        
                if(isset($phones_leads_regions[$key])) { 
                    $regions[$region]['E'] += 1;
                    unset($phones_leads_regions[$key]);
                }
            }

            //Отчет для транка
            $trunk = $row['trunk'];
            if(array_key_exists($trunk, $trunks)) {
                $trunks[$trunk]['B'] += 1;
                $trunks[$trunk]['C'] += $row['C'];
                $trunks[$trunk]['F'] += $row['F'];
                $trunks[$trunk]['J'] += $row['J'];
            } else {
                $trunks[$trunk] = ['B' => 1, 
                                    'C' => $row['C'], 
                                    'D' => 0, 
                                    'E' => 0, 
                                    'F' => $row['F'], 
                                    'G' => 0, 
                                    'H' => 0, 
                                    'I' => 0, 
                                    'J' => $row['J'], 
                                    'K' => 0, 
                                    'L' => 0,
                                    'M' => 0,
                                    'N' => 0,
                                    'O' => 0
                                    ]; 
            }

            if($row['DE'] == 1) {
                $key = $row['phone_number'] . '|' . $row['date_time'];
                if(isset($phones_dozvon[$key])) { 
                    $trunks[$trunk]['D'] += 1;
                }        
                if(isset($phones_leads_campaigns[$key])) { 
                    $trunks[$trunk]['E'] += 1;
                    unset($phones_leads_campaigns[$key]);
                }
            }

            //Отчет для кампании
            $campaign = $row['campaign'];
            if(array_key_exists($campaign, $campaigns)) {
                $campaigns[$campaign]['B'] += 1;
                $campaigns[$campaign]['C'] += $row['C'];
                $campaigns[$campaign]['F'] += $row['F'];
                $campaigns[$campaign]['J'] += $row['J'];
                if($row['trunk'] == 'Инкогнито') {
                    $campaigns[$campaign]['incognito_trunk'] += 1;
                } else {
                    $campaigns[$campaign]['other_trunk'] += 1;
                }
            } else {
                $campaigns[$campaign] = ['B' => 1, 
                                    'C' => $row['C'], 
                                    'D' => 0, 
                                    'E' => 0, 
                                    'F' => $row['F'], 
                                    'G' => 0, 
                                    'H' => 0, 
                                    'I' => 0, 
                                    'J' => $row['J'], 
                                    'K' => 0, 
                                    'L' => 0,
                                    'M' => 0,
                                    'N' => 0,
                                    'O' => 0,
                                    'incognito_trunk' => 0,
                                    'other_trunk' => 0
                                    ]; 
            }

            if($row['DE'] == 1) {
                $key = $row['phone_number'] . '|' . $row['date_time'];
                if(isset($phones_dozvon[$key])) { 
                    $campaigns[$campaign]['D'] += 1;
                }        
                if(isset($phones_leads_trunks[$key])) { 
                    $campaigns[$campaign]['E'] += 1;
                    unset($phones_leads_trunks[$key]);
                }
            }

        }

        // Отчет для источника

        foreach($sources as $key => $value) {

            $sources[$key]['J'] = round($sources[$key]['J']);
            $sources[$key]['K'] = round($sources[$key]['incognito_trunk'] * 0 + $sources[$key]['other_trunk'] * 0.6);
            $sources[$key]['O'] = $sources[$key]['J'] + $sources[$key]['K'];
            if($sources[$key]['D']) {
                $sources[$key]['G'] = round($sources[$key]['E'] / $sources[$key]['D'] * 100, 1);
            }
            if($sources[$key]['C']) {
                $sources[$key]['H'] = round($sources[$key]['D'] / $sources[$key]['C'] * 100, 1);
            }
            if($sources[$key]['B']) {
                $sources[$key]['I'] = round($sources[$key]['D'] / $sources[$key]['B'] * 100, 1);        
            }
            if($sources[$key]['E']) {
                $sources[$key]['L'] = round($sources[$key]['J'] / $sources[$key]['E']);
                $sources[$key]['M'] = round($sources[$key]['K'] / $sources[$key]['E']);    
                $sources[$key]['N'] = round(($sources[$key]['J'] + $sources[$key]['K']) / $sources[$key]['E']);            
            }

        }

        // Отчет для региона

        foreach($regions as $key => $value) {

            $regions[$key]['J'] = round($regions[$key]['J']);
            $regions[$key]['K'] = round($regions[$key]['incognito_trunk'] * 0 + $regions[$key]['other_trunk'] * 0.6);
            $regions[$key]['O'] = $regions[$key]['J'] + $regions[$key]['K'];
            if($regions[$key]['D']) {
                $regions[$key]['G'] = round($regions[$key]['E'] / $regions[$key]['D'] * 100, 1);
            }
            if($regions[$key]['C']) {
                $regions[$key]['H'] = round($regions[$key]['D'] / $regions[$key]['C'] * 100, 1);
            }
            if($regions[$key]['B']) {
                $regions[$key]['I'] = round($regions[$key]['D'] / $regions[$key]['B'] * 100, 1);        
            }
            if($regions[$key]['E']) {
                $regions[$key]['L'] = round($regions[$key]['J'] / $regions[$key]['E']);
                $regions[$key]['M'] = round($regions[$key]['K'] / $regions[$key]['E']);    
                $regions[$key]['N'] = round(($regions[$key]['J'] + $regions[$key]['K']) / $regions[$key]['E']);            
            }

        }

        // Отчет для транка

        foreach($trunks as $key => $value) {

            $trunks[$key]['J'] = round($trunks[$key]['J']);
            $coef = 0.6;
            if($key == 'Инкогнито') {
                $coef = 0;
            }
            $trunks[$key]['K'] = round($trunks[$key]['B'] * $coef);
            $trunks[$key]['O'] = $trunks[$key]['J'] + $trunks[$key]['K'];
            if($trunks[$key]['D']) {
                $trunks[$key]['G'] = round($trunks[$key]['E'] / $trunks[$key]['D'] * 100, 1);
            }
            if($trunks[$key]['C']) {
                $trunks[$key]['H'] = round($trunks[$key]['D'] / $trunks[$key]['C'] * 100, 1);
            }
            if($trunks[$key]['B']) {
                $trunks[$key]['I'] = round($trunks[$key]['D'] / $trunks[$key]['B'] * 100, 1);        
            }
            if($trunks[$key]['E']) {
                $trunks[$key]['L'] = round($trunks[$key]['J'] / $trunks[$key]['E']);
                $trunks[$key]['M'] = round($trunks[$key]['K'] / $trunks[$key]['E']);    
                $trunks[$key]['N'] = round(($trunks[$key]['J'] + $trunks[$key]['K']) / $trunks[$key]['E']);            
            }

        }

        // Отчет для кампаний

        foreach($campaigns as $key => $value) {

            $campaigns[$key]['J'] = round($campaigns[$key]['J']);
            $campaigns[$key]['K'] = round($campaigns[$key]['incognito_trunk'] * 0 + $campaigns[$key]['other_trunk'] * 0.6);
            $campaigns[$key]['O'] = $campaigns[$key]['J'] + $campaigns[$key]['K'];
            if($campaigns[$key]['D']) {
                $campaigns[$key]['G'] = round($campaigns[$key]['E'] / $campaigns[$key]['D'] * 100, 1);
            }
            if($campaigns[$key]['C']) {
                $campaigns[$key]['H'] = round($campaigns[$key]['D'] / $campaigns[$key]['C'] * 100, 1);
            }
            if($campaigns[$key]['B']) {
                $campaigns[$key]['I'] = round($campaigns[$key]['D'] / $campaigns[$key]['B'] * 100, 1);        
            }
            if($campaigns[$key]['E']) {
                $campaigns[$key]['L'] = round($campaigns[$key]['J'] / $campaigns[$key]['E']);
                $campaigns[$key]['M'] = round($campaigns[$key]['K'] / $campaigns[$key]['E']);    
                $campaigns[$key]['N'] = round(($campaigns[$key]['J'] + $campaigns[$key]['K']) / $campaigns[$key]['E']);            
            }

        }

        ksort($sources);
        ksort($regions);
        ksort($trunks);
        ksort($campaigns);

        $path = $_SERVER['DOCUMENT_ROOT'] . '/scripts/call_rates/view/tables-requests.php';
        include ($path);

    }   

    public function get_call_stats() {

        $time_start = $this->data['time_start'];
        $time_end = $this->data['time_end'];
        $local_time_start = (int) $time_start + 1;
        $local_time_end = (int) $time_end + 1;
        $where_filter = '';
        $query_manual_calls = "UNION ALL
                                SELECT RIGHT(field_211, 10) AS phone_number,
                                       DATE(FROM_UNIXTIME(field_209)) AS date_time,
                                       IF(field_335 = 102, 1, 0) AS BUSY,
                                       IF(field_335 = 105, 1, 0) AS CANCEL,
                                       IF(field_335 = 107, 1, 0) AS CHANUNAVAIL,
                                       IF(field_335 = 106, 1, 0) AS CONGESTION,
                                       IF(field_335 = 101, 1, 0) AS NOANSWER,
                                       IF(field_335 = 100, 1, 0) AS АО,
                                       1 AS operator_answered,
                                       0 AS sent_robot,
                                       IF(field_335 = 100 AND field_218 > 0, field_218, 0) AS second_сall
                                FROM app_entity_25
                                WHERE field_333 <> '' AND DATE(FROM_UNIXTIME(field_209)) BETWEEN '{$this->data['date_start']}' AND '{$this->data['date_end']}' AND HOUR(FROM_UNIXTIME(field_209)) >= {$local_time_start} AND HOUR(FROM_UNIXTIME(field_209)) < {$local_time_end}";

        if(!empty($this->data['trunk_filter'])) {
            if($this->data['trunk_filter'] == 'other') {
                $where_filter .= " AND (field_521 NOT LIKE '%multifon%' AND field_521 NOT LIKE '%modem%' AND field_521 NOT LIKE '%rostelecom%' AND field_521 NOT LIKE '%beeline%' AND field_521 NOT LIKE '%incognito%')";
                $query_manual_calls .= " AND (field_333 NOT LIKE '%multifon%' AND field_333 NOT LIKE '%modem%' AND field_333 NOT LIKE '%rostelecom%' AND field_333 NOT LIKE '%beeline%' AND field_333 NOT LIKE '%incognito%')";

            } else {
                $where_filter .= " AND field_521 LIKE '%{$this->data['trunk_filter']}%'";
                $query_manual_calls  .= " AND field_333 LIKE '%{$this->data['trunk_filter']}%'";
            }
        }

        if(!empty($this->data['campaign_filter'])) {
            $where_filter .= " AND field_479 = {$this->data['campaign_filter']}";
            $query_manual_calls = "";
        }

        if(!empty($this->data['operator_filter'])) {
            $where_filter .= " AND field_473 = {$this->data['operator_filter']}";
            $query_manual_calls .= " AND field_210 = {$this->data['operator_filter']}";
        }

        if(!empty($this->data['type_autodialer_filter'])) {
            $where_filter .= " AND field_477 = {$this->data['type_autodialer_filter']}";
            $query_manual_calls = "";
        }

        $res1 = $this->db_connect->query("
            SELECT
            RIGHT(bez_unprocessed_base.phone_number, 10) AS phone_number,
            DATE(bsr1.date_time) AS date_time,
            bez_reg.id_otdel
            FROM (SELECT *, COUNT(bsr1.id) AS count_sale FROM bez_sale_request bsr1
                WHERE DATE(bsr1.date_time) BETWEEN '{$this->data['date_start']}' AND '{$this->data['date_end']}' AND HOUR(bsr1.date_time) >= {$time_start} AND HOUR(bsr1.date_time) < {$time_end}
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
        while($row = $res1->fetchAssoc()) {
            $key = $row['phone_number'] . '|' . $row['date_time'];
            if(!isset($phones_leads[$key])) {
                $phones_leads[$key] = $row['id_otdel'];
            }
        }

        $res2 = $this->db_connect->query("
            SELECT RIGHT(phone_number, 10) AS phone_number, DATE(row_change_time) AS date_time 
            FROM (SELECT * FROM lg_user_event GROUP BY DATE(row_change_time), RIGHT(phone_number, 10)) t 
            WHERE date(row_change_time) between '{$this->data['date_start']}' AND '{$this->data['date_end']}' and HOUR(row_change_time) >= {$time_start} and HOUR(row_change_time) < {$time_end} AND (t.status_id = 15 or t.status_id = 6 or t.status_id = 9 or t.status_id = 21 or t.status_id = 11 or t.status_id = 16 or t.status_id = 23 or t.status_id = 24 or t.status_id = 25 or t.status_id = 26 or t.status_id = 28 or t.status_id = 29 or t.status_id = 30 or t.status_id = 31 or t.status_id = 32 or t.status_id = 33 or t.status_id = 34 or t.status_id = 35);
        ");

        $phones_dozvon = [];
        while($row = $res2->fetchAssoc()) {
            $key = $row['phone_number'] . '|' . $row['date_time'];
            if(!isset($phones_dozvon[$key])) {
                $phones_dozvon[$key] = 1;
            }
        }

        $res3 = $this->db_connect2->query("
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
            WHERE DATE(FROM_UNIXTIME(field_383)) BETWEEN '{$this->data['date_start']}' AND '{$this->data['date_end']}' AND HOUR(FROM_UNIXTIME(field_383)) >= {$local_time_start} AND HOUR(FROM_UNIXTIME(field_383)) < {$local_time_end} {$where_filter}
            {$query_manual_calls};
        ");

        $params_quantity = ['BUSY' => 0,
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

        while($row = $res3->fetchAssoc()) {
            $params_quantity['BUSY'] += (int) $row['BUSY'];
            $params_quantity['CANCEL'] += (int) $row['CANCEL'];
            $params_quantity['CHANUNAVAIL'] += (int) $row['CHANUNAVAIL'];
            $params_quantity['CONGESTION'] += (int) $row['CONGESTION'];
            $params_quantity['NOANSWER'] += (int) $row['NOANSWER'];
            $params_quantity['АО'] += (int) $row['АО'];
            $params_quantity['operator_answered'] += (int) $row['operator_answered'];
            $params_quantity['sent_robot'] += (int) $row['sent_robot'];
            $params_quantity['total'] += 1;
            $params_quantity['sum_minutes_сall'] += (int) $row['second_сall'];

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

        $params_quantity['sum_minutes_сall'] = ceil($params_quantity['sum_minutes_сall'] / 60);

        $res4 = $this->db_connect->query("
            SELECT COUNT(DISTINCT t.user_id) AS caller
            FROM (SELECT * FROM lg_user_event GROUP BY DATE(row_change_time), RIGHT(phone_number, 10)) t
            WHERE date(row_change_time) between '{$this->data['date_start']}' AND '{$this->data['date_end']}' and HOUR(row_change_time) >= {$time_start} and HOUR(row_change_time) < {$time_end};
            ");

        $caller = 0;
        if($res4->getNumRows() > 0) {
            $row = $res4->fetchAssoc();
            $caller = (int) $row['caller'];
        }

        $path = $_SERVER['DOCUMENT_ROOT'] . '/scripts/call_rates/view/tables-requests2.php';
        include ($path);

    }

    public function get_campaigns() {

        $res = $this->db_connect2->query("
            SELECT id, name FROM app_global_lists_choices WHERE lists_id = 1 ORDER BY id ASC;
        ");

        $lists_choices = null;
        while($row = $res->fetchAssoc()) {
            $lists_choices[] = $row;
        }

        return $lists_choices;

    }  

    public function get_operators() {

        $res = $this->db_connect->query("
            SELECT id_atc, name FROM bez_reg WHERE id_atc >= 100 AND id_atc < 200 ORDER BY id_atc;
        ");

        $operators = null;
        while($row = $res->fetchAssoc()) {
            $operators[] = $row;
        }

        return $operators;

    } 

}

if(!empty($_POST['action'])) {
    $action = $_POST['action'];
    unset($_POST['action']);
    $obj = new CallRates();
    $obj->handle_action($action);
}