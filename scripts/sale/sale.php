<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

class Sale
{
    private $db_connect;
    private $data;
    private $response;
    private $pf;

    function __construct() {
        global $db_connect;
        $this->db_connect = $db_connect;
        $this->pf = BEZ_DBPREFIX;
        $this->tz = TIMEZONE;
        if(!empty($_POST))
            $this->data = $_POST;
    }

    public function set($data) {
        return $this->data = $data;
    }

    public function handle_action($action) {
        # Основной метод, вызывающий событие из action

        switch($action) {
            case 'get_sale_requests':
                $this->get_sale_requests();
                break;
            case 'get_plan':
                return $this->get_plan();
            case 'set_plan':
                $this->set_plan();
                break;
            case 'partner_status':
                $this->partner_status();
                break;
            case 'reject_partner':
                $this->reject_partner();
                break;
            case 'get_fix_plan':
                return $this->get_fix_plan();    
            case 'check_city':
                return $this->check_city();    
            case 'check_is_audio':
                return $this->check_is_audio(); 				
            case 'get_audiorecording':
                return $this->get_audiorecording();   
            case 'check_audio':
                return $this->check_audio();     
            // case 'delete_plan':
            //     $this->delete_plan();
            //     break;
        }
    }

    public function partner_status() {
        $this->db_connect->query("
            UPDATE st_partner_s SET active = {$this->data['status']}
            WHERE id = {$this->data['id']}
        ");
    }

    public function get_plan() {
        $date = isset($this->data['date']) ? $this->data['date'] : date('Y-m-d');
        $hide_partner_411 = '';

        if($_SESSION['login_id'] != 132 && $_SESSION['login_id'] != 133 && $_SESSION['login_id'] != 252 && $_SESSION['login_id'] != 119 && $_SESSION['login_id'] != 306) {
            $hide_partner_411 = 'AND bez_plan.partner_id <> 411';
        }

        $query = $this->db_connect->query("
        SELECT 
            SubTable.partner_id,
            SubTable.count,
            SubTable.date,
            SubTable.partner_name,
            IF((DATE_ADD(CURTIME(), INTERVAL 1 HOUR) >= SubTable.time_start AND DATE_ADD(CURTIME(), INTERVAL 1 HOUR) <= SubTable.time_end AND SubTable.time_start <> '00:00:00' AND SubTable.time_end <> '00:00:00') OR
                (SubTable.time_start = '00:00:00' AND SubTable.time_end = '00:00:00') OR
                (SubTable.time_start = '00:00:00' AND SubTable.time_end <> '00:00:00' AND DATE_ADD(CURTIME(), INTERVAL 1 HOUR) <= SubTable.time_end) OR
                (DATE_ADD(CURTIME(), INTERVAL 1 HOUR) >= SubTable.time_start AND SubTable.time_start <> '00:00:00' AND SubTable.time_end = '00:00:00'), '', 'text-danger') AS is_working_hours,
            SUM(SubTable.sub_sold_request) as sold_request 
        FROM 
            (SELECT
                {$this->pf}plan.partner_id,
                {$this->pf}plan.count,
                {$this->pf}plan.date,
                {$this->pf}plan.time_start,
                {$this->pf}plan.time_end,
                st_partner_s.partner_name,
                COUNT({$this->pf}unprocessed_base.id) as sub_sold_request
             FROM
                {$this->pf}plan
             JOIN
                st_partner_s ON {$this->pf}plan.partner_id = st_partner_s.id
             LEFT JOIN
                {$this->pf}unprocessed_base
                ON {$this->pf}unprocessed_base.partner = {$this->pf}plan.partner_id
                AND {$this->pf}unprocessed_base.date_create = DATE('{$date}') 
                AND {$this->pf}unprocessed_base.source = 'telegram'
                AND {$this->pf}unprocessed_base.user_id <> ''
             WHERE
                {$this->pf}plan.date = DATE('{$date}') AND bez_plan.partner_id <> 516 {$hide_partner_411}
             GROUP BY
                {$this->pf}plan.partner_id
            UNION ALL     
             SELECT
                bez_plan.partner_id,
                bez_plan.count,
                bez_plan.date,
                {$this->pf}plan.time_start,
                {$this->pf}plan.time_end,
                st_partner_s.partner_name,
                COUNT(distinct bez_unprocessed_base.id) as sub_sold_request
             FROM
                bez_plan
             JOIN
                st_partner_s ON bez_plan.partner_id = st_partner_s.id
             JOIN
                bez_sale_request bsr1
                ON bsr1.partner_id = bez_plan.partner_id
                AND DATE(bsr1.date_time) = DATE('{$date}')
             JOIN bez_unprocessed_base
                ON bez_unprocessed_base.id = bsr1.request_id  
             WHERE
                bez_plan.date = DATE('{$date}') AND NOT EXISTS (SELECT * FROM bez_sale_request bsr2 WHERE bsr1.request_id = bsr2.request_id and bsr1.partner_id <> 65 and bsr2.partner_id = 65 and bsr2.date_time BETWEEN bsr1.date_time and DATE_FORMAT(bsr1.date_time, '%y-%m-%d 23:59:59') AND (NOT EXISTS (SELECT * FROM bez_sale_request bsr3 WHERE bsr1.id <> bsr3.id and bsr1.request_id = bsr3.request_id and bsr3.partner_id <> 65 and bsr3.date_time BETWEEN bsr1.date_time and bsr2.date_time))) AND bez_plan.partner_id <> 516 {$hide_partner_411}
             GROUP BY
                bez_plan.partner_id) AS SubTable
        GROUP BY
            SubTable.partner_id
        ORDER BY
            SubTable.partner_name;       
        ");

        $plan = NULL;
        while($row = $query->fetchAssoc()) {
            $plan[] = $row;
        }

        if(!empty($this->data['json'])) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($plan);
        } else {
            return $plan;
        }
    }

    public function get_fix_plan() {

        $query = $this->db_connect->query("
            SELECT st_partner_s.id, st_partner_s.partner_name, IFNULL({$this->pf}fix_plan.count, 0) AS count, IFNULL(TIME_FORMAT({$this->pf}fix_plan.time_start, '%H:%i'), '00:00') AS time_start, IFNULL(TIME_FORMAT({$this->pf}fix_plan.time_end, '%H:%i'), '00:00') AS time_end, IF({$this->pf}plan.partner_id IS NULL, 0, 1) AS is_added, {$this->pf}fix_plan.is_city, {$this->pf}fix_plan.is_audio 
            FROM st_partner_s 
            LEFT JOIN bez_fix_plan
            ON {$this->pf}fix_plan.partner_id = st_partner_s.id
            LEFT JOIN bez_plan
            ON {$this->pf}plan.partner_id = st_partner_s.id AND {$this->pf}plan.date = CURDATE()
            WHERE st_partner_s.active = 1;
        ");

        $plan = NULL;
        while($row = $query->fetchAssoc()) {
            $plan[] = $row;
        }

        if(!empty($this->data['json'])) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($plan);
        } else {
            return $plan;
        }
    }

    public function check_city() {

        $query = $this->db_connect->query("
            SELECT IF(is_city = 0 OR is_city IS NULL, 0, 1) AS is_city 
            FROM st_partner_s LEFT JOIN bez_fix_plan
            ON bez_fix_plan.partner_id = st_partner_s.id
            WHERE st_partner_s.id = {$this->data['partner_id']};
        ");

        $is_city = $query->fetchAssoc();

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($is_city);
    }

    public function set_plan() {
        $this->db_connect->query("DELETE FROM {$this->pf}plan WHERE date = CURDATE()");

        foreach($this->data['current_plan'] as $plan) {
            $partner_id = $plan['partner_id'];
            $count = $plan['count'];
            $time_start = $plan['time_start'];
            $time_end = $plan['time_end'];

            $this->db_connect->query(
                "
                INSERT INTO {$this->pf}plan (partner_id, count, date, time_start, time_end)
                VALUES ('{$partner_id}', '{$count}', CURDATE(), '{$time_start}', '{$time_end}')"
            );
        }

        $this->db_connect->query("DELETE FROM {$this->pf}fix_plan");

        foreach($this->data['fix_plan'] as $plan) {
			
            $partner_id = $plan['partner_id'];
            $count = $plan['count'];
            $time_start = $plan['time_start'];
            $time_end = $plan['time_end'];
            $is_city = $plan['is_city'];
            $is_audio = $plan['is_audio'];

            $this->db_connect->query(
                "
                INSERT INTO {$this->pf}fix_plan (partner_id, count, time_start, time_end, is_city, is_audio )
                VALUES ('{$partner_id}', '{$count}', '{$time_start}', '{$time_end}', '{$is_city}', '{$is_audio}')"
            );
        }
    }

    public function get_sale_requests() {
        $res = $this->db_connect->query("
            SELECT IFNULL(bsr1.id, bub2.id) AS id,
                bub2.id AS request_id,
                IFNULL(bsr1.partner_id, bub2.partner) AS partner_id,
                IFNULL(bsr1.price, 0) AS price,
                IFNULL(DATE_ADD(bsr1.date_time, INTERVAL {$this->tz} HOUR), bub2.date_create) AS date_time,
                IFNULL(stps2.partner_name, CONCAT(stps1.partner_name, ' ', '+')) AS partner_name,
                IF(EXISTS (SELECT * FROM bez_sale_request bsr2 WHERE bsr1.request_id = bsr2.request_id and bsr1.partner_id <> 65 and bsr2.partner_id = 65 and bsr2.date_time BETWEEN bsr1.date_time and DATE_FORMAT(bsr1.date_time, '%y-%m-%d 23:59:59') AND (NOT EXISTS (SELECT * FROM bez_sale_request bsr3 WHERE bsr1.id <> bsr3.id and bsr1.request_id = bsr3.request_id and bsr3.partner_id <> 65 and bsr3.date_time BETWEEN bsr1.date_time and bsr2.date_time))), 1, 0) AS defect,
				bub2.is_double as is_double
            FROM bez_unprocessed_base bub1 
            JOIN bez_unprocessed_base bub2 ON bub1.phone_number = bub2.phone_number
            JOIN st_partner_s stps1 ON stps1.id = bub2.partner
            LEFT JOIN bez_sale_request bsr1 ON bub2.id = bsr1.request_id 
            LEFT JOIN st_partner_s stps2 ON stps2.id = bsr1.partner_id
            WHERE (bub1.id = {$this->data['id']} AND (bsr1.partner_id <> 65 OR (bsr1.partner_id = 65 AND NOT EXISTS (SELECT * FROM bez_sale_request bsr3 WHERE bsr1.request_id = bsr3.request_id and bsr3.partner_id <> 65 and bsr3.date_time BETWEEN DATE_FORMAT(bsr1.date_time, '%y-%m-%d 00:00:00') and bsr1.date_time))) AND (bub2.source <> 'telegram' or bub2.source is not null)) OR (bub1.id = {$this->data['id']} AND (bub2.source = 'telegram' or bub2.source is null))
            ORDER BY date_time;
        ");

        $sale_requsests = NULL;
        while($row = $res->fetchAssoc()) {
            $sale_requsests[] = $row;
        }

        if(!empty($this->data['view_name']) and !empty($sale_requsests)) {
            $path = $_SERVER['DOCUMENT_ROOT'] . '/scripts/sale/view/' . $this->data['view_name'] . '.php';
            include ($path);
        } else {
            return $sale_requsests;
        }
    }

    public function reject_partner() {
        $id = $this->data['idval'];
        $date_start = $this->data['date_start'];
        $date_end = $this->data['date_end'];
        $selected_sale = $this->data['selected_sale'];
        $sales = $this->data['sales'];
        $response = null;

        if($selected_sale === 'yes') {
            if($sales === '') {
                $response = ['error' => 'Партнер не был выбран!'];
            }
            else {
                $sales_ids = explode(",", $sales);
                $response = ['sales' => $sales];
                for($i = 0; $i < count($sales_ids); $i++) {
                    $id_sale = $sales_ids[$i];
                    $res = $this->db_connect->query("
                        SELECT * FROM {$this->pf}sale_request 
                        WHERE id = {$id_sale};         
                    ");

                    $sale_requsests = NULL;
                    while($row = $res->fetchAssoc()) {
                        $sale_requsests[] = $row;
                    }

                    $one_sale = $sale_requsests[0];

                    $query_request1 = "
                    INSERT INTO bez_sale_request
                        (request_id, partner_id, price, date_time, user_id)
                    VALUES ({$one_sale["request_id"]}, 65, 0, '{$one_sale["date_time"]}', {$one_sale["user_id"]})
                    ";

                    $this->db_connect->query($query_request1);

                    $query_request2 = "
                        UPDATE {$this->pf}unprocessed_base
                            SET partner = 65
                        WHERE
                            id = " . $one_sale['request_id'] . " AND partner = " . $one_sale['partner_id'] . "
                    ";

                    $this->db_connect->query($query_request2);                     
                }                
            }            
        }
        else {
            $res = $this->db_connect->query("
                SELECT bsr1.* 
                FROM {$this->pf}unprocessed_base bub1 
                JOIN {$this->pf}unprocessed_base bub2 ON bub1.phone_number = bub2.phone_number
                JOIN {$this->pf}sale_request bsr1 ON bub2.id = bsr1.request_id 
                WHERE bub1.id = {$id} AND bsr1.partner_id <> 65 AND bsr1.date_time BETWEEN '{$date_start}' AND '{$date_end}' AND NOT EXISTS (SELECT * FROM {$this->pf}sale_request bsr2 WHERE bsr1.request_id = bsr2.request_id and bsr2.partner_id = 65 and bsr2.date_time BETWEEN bsr1.date_time and DATE_FORMAT(bsr1.date_time, '%y-%m-%d 23:59:59') AND (NOT EXISTS (SELECT * FROM {$this->pf}sale_request bsr3 WHERE bsr1.id <> bsr3.id and bsr1.request_id = bsr3.request_id and bsr3.partner_id <> 65 and bsr3.date_time BETWEEN bsr1.date_time and bsr2.date_time)));       
            ");

            $sale_requsests = NULL;
            while($row = $res->fetchAssoc()) {
                $sale_requsests[] = $row;
            }

            if($sale_requsests === null) {
                $response = ['error' => 'В таком диапазоне дат партнера нет!'];
            }
            elseif(count($sale_requsests) > 1) {
                $response = ['select' => 'yes', $sale_requsests];
            }
            else {
                $one_sale = $sale_requsests[0];

                $query_request1 = "
                INSERT INTO bez_sale_request
                    (request_id, partner_id, price, date_time, user_id)
                VALUES ({$one_sale["request_id"]}, 65, 0, '{$one_sale["date_time"]}', {$one_sale["user_id"]})
                ";

                $this->db_connect->query($query_request1);

                $query_request2 = "
                    UPDATE {$this->pf}unprocessed_base
                        SET partner = 65
                    WHERE
                        id = " . $one_sale['request_id'] . " AND partner = " . $one_sale['partner_id'] . "
                ";

                $this->db_connect->query($query_request2);
                $response = $one_sale;            
            }            
        }

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function get_audiorecording() {

        $res = $this->db_connect->query("
            SELECT *
            FROM audiorecordings
            WHERE phone_number = {$this->data['phone_number']};
        ");

        $audiorecordings = NULL;
        while($row = $res->fetchAssoc()) {
            $audiorecordings[] = $row;
        }

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($audiorecordings);
    }

    public function check_audio() {

        $query_request = "
            UPDATE {$this->pf}unprocessed_base
                SET is_audio_check = {$this->data['mark']}
            WHERE
                id = {$this->data['id']}
        ";

        $this->db_connect->query($query_request);

        http_response_code(200);
        
    }
    public function check_is_audio() {

        $query = $this->db_connect->query("
            SELECT IF(is_audio = 0 OR is_audio IS NULL, 0, 1) AS is_audio 
            FROM st_partner_s LEFT JOIN bez_fix_plan
            ON bez_fix_plan.partner_id = st_partner_s.id
            WHERE st_partner_s.id = {$this->data['partner_id']};
        ");

        $is_audio = $query->fetchAssoc();

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($is_audio);
    }
}

if(!empty($_POST['action'])) {
    $action = $_POST['action'];
    unset($_POST['action']);
    $obj = new Sale();
    $obj->handle_action($action);
}