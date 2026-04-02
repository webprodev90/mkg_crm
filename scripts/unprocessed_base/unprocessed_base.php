<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

class UnprocessedBase
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
            case 'get_unprocessed_base':
                $this->get_unprocessed_base();
                break;
            case 'get_unprocessed_base2':
                $this->get_unprocessed_base2();
                break;
            case 'get_client':
                $this->get_client();
            case 'get_partners':
                return $this->get_partners();
            case 'get_sources':
                return $this->get_sources();
            case 'get_cities_group':
                return $this->get_cities_group();
            case 'get_cities':
                return $this->get_cities();            
            case 'get_operators':
                return $this->get_operators();
            case 'update_request':
                $this->update_request($this->data);
                break;
            case 'get_statuses':
                return $this->get_statuses();
            case 'get_hold_statuses':
                return $this->get_hold_statuses();
            case 'get_counters':
                return $this->get_counters();
            case 'get_rate':
                return $this->get_rate();
            case 'get_departments':
                return $this->get_departments();
            case 'get_quantity_by_statuses':
                return $this->get_quantity_by_statuses();  
            case 'get_quantity_by_hold_statuses':
                return $this->get_quantity_by_hold_statuses();      
            case 'get_today_calls':
                return $this->get_today_calls();  
            case 'get_filtering_sources':
                return $this->get_filtering_sources(); 
            case 'get_filtering_cities':
                return $this->get_filtering_cities();
            case 'get_filtering_cities_group':
                return $this->get_filtering_cities_group();
            case 'get_filtering_operators':
                return $this->get_filtering_operators();
            case 'get_filtering_departments':
                return $this->get_filtering_departments(); 
            case 'start_lead_filling':
                $this->start_lead_filling();
                break;
            case 'delete_lead_filling':
                $this->delete_lead_filling();
                break;
            case 'call_phone':
                $this->call_phone();
                break;  
            case 'disable_dnd_mode':
                $this->disable_dnd_mode();
                break;
            case 'logging':
                $this->logging($this->data['value'], $this->data['modul'], $this->data['phone_number']);
                break;                              
        }
    }

    public function get_rate() {
        $data = $this->data;

        $queryset = $this->db_connect->query("
            SELECT user_id, operator_id, id_otdel, name, SUM(count_request) AS count_request FROM (
                SELECT
                bsr1.user_id,
                {$this->pf}reg.id as operator_id,
                {$this->pf}reg.id_otdel,
                {$this->pf}reg.name,
                COUNT(count_sale) as count_request
            FROM (SELECT *, COUNT(bsr1.id) AS count_sale FROM {$this->pf}sale_request bsr1
                    WHERE bsr1.date_time BETWEEN '{$data['date_start']}' AND '{$data['date_end']}'
                    AND bsr1.partner_id <> 65
                    AND NOT EXISTS (SELECT * FROM {$this->pf}sale_request bsr2 WHERE bsr1.request_id = bsr2.request_id and bsr2.partner_id = 65 and bsr2.date_time BETWEEN bsr1.date_time and DATE_FORMAT(bsr1.date_time, '%y-%m-%d 23:59:59') AND (NOT EXISTS (SELECT * FROM bez_sale_request bsr3 WHERE bsr1.id <> bsr3.id and bsr1.request_id = bsr3.request_id and bsr3.partner_id <> 65 and bsr3.date_time BETWEEN bsr1.date_time and bsr2.date_time)))
                    GROUP BY user_id, request_id, DATE_FORMAT(date_time, '%Y-%m-%d')) bsr1
            JOIN {$this->pf}reg
            ON bsr1.user_id = {$this->pf}reg.id
            GROUP BY bsr1.user_id
            UNION ALL 
            SELECT bub.user_id,
                {$this->pf}reg.id as operator_id,
                {$this->pf}reg.id_otdel,
                {$this->pf}reg.name,
                COUNT(bub.id) as count_request
            FROM {$this->pf}unprocessed_base bub
            JOIN {$this->pf}reg
            ON bub.user_id = {$this->pf}reg.id
            WHERE
                bub.date_create
                    BETWEEN '{$data['date_start']}' AND '{$data['date_end']}'
                AND bub.partner <> 65
                AND bub.source = 'telegram'
                AND bub.date_time_status_change IS NULL
                AND bub.status = 10
            GROUP BY bub.user_id) AS SubQuery
            GROUP BY operator_id
            ORDER BY id_otdel ASC, count_request DESC;
        ");

        $res = NULL;
        while($row = $queryset->fetchAssoc()) {
            $res[] = $row;
        }

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($res);
    }

    public function get_statuses() {
        $res = $this->db_connect->query("
            SELECT
            {$this->pf}status.status_name,
            {$this->pf}status.status_id
            FROM
                {$this->pf}status
                WHERE {$this->pf}status.status_id IN(10, 11, 15, 16, 6, 8, 9, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 36)
                ORDER BY FIELD({$this->pf}status.status_id, 15, 16, 32, 23, 25, 26, 24, 21, 11, 28, 19, 29, 8, 22, 30, 6, 27, 31, 10, 9, 36) ASC;
        ");

        $statuses = NULL;
        while($row = $res->fetchAssoc()) {
            $statuses[] = $row;
        }

        return $statuses;
    }

    public function get_hold_statuses() {
        $res = $this->db_connect->query("
            SELECT
            {$this->pf}status.status_name,
            {$this->pf}status.status_id
            FROM
                {$this->pf}status
                WHERE {$this->pf}status.status_id IN(33, 34, 35)
                ORDER BY {$this->pf}status.status_id;
        ");

        $hold_statuses = NULL;
        while($row = $res->fetchAssoc()) {
            $hold_statuses[] = $row;
        }

        return $hold_statuses;
    }

    public function get_partners() {
        $res = $this->db_connect->query("
            SELECT * FROM st_partner_s ORDER BY active desc
        ");

        $partners = NULL;
        while($row = $res->fetchAssoc()) {
            $partners[] = $row;
        }

        return $partners;
    }

    public function get_operators() {
        $res = $this->db_connect->query("
            SELECT
                {$this->pf}reg.id,
                {$this->pf}reg.name,
                {$this->pf}reg.id_otdel
            FROM
                {$this->pf}reg
            WHERE
                {$this->pf}reg.role = 5
        ");

        $operators = [];
        while($row = $res->fetchAssoc()) {
            $operators[] = $row;
        }

        if(!empty($this->data['get_json'])) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($operators);
            exit();
        }

        return $operators;
    }

    public function get_cities_group() {
        $res = $this->db_connect->query("
            SELECT id, name
                FROM
                {$this->pf}cities_group
            WHERE 1
                ORDER BY name ASC;
        ");

        $cities = NULL;
        while($row = $res->fetchAssoc()) {
            $cities[] = $row;
        }

        return $cities;
    }

    public function get_cities() {
        $res = $this->db_connect->query("
            SELECT city
                FROM
                    {$this->pf}unprocessed_base
                GROUP BY city
            ORDER BY city ASC
        ");

        $result = NULL;
        while($row = $res->fetchAssoc()) {
            $result[] = $row;
        }

        return $result;
    }

    public function get_sources() {
        $res = $this->db_connect->query("
            SELECT source
                FROM
                    {$this->pf}unprocessed_base
                GROUP BY source
            ORDER BY source ASC
        ");

        $result = NULL;
        while($row = $res->fetchAssoc()) {
            $result[] = $row;
        }

        return $result;
    }


    public function update_request($data) {

        $new_data = '';
		/*$manual_r = '1';*/

        foreach($data['params'] as $item) {
            if($item['name'] == 'phone_number') {
                $phone_number = $item['value'];
            }
        }        

        if($data['logging'] == 'y') {

            $status = $data['status'];

            if(!empty($this->data['holds']) and !empty($this->data['hold_status']) and $this->data['holds'] == 'y' and $this->data['hold_status'] != 0) {
                $status = $data['hold_status'];
            }

            $query_request_lg = "
                INSERT INTO lg_user_event (phone_number, status_id, user_id, row_change_time) VALUES ('{$phone_number}', '{$status}', '{$_SESSION['login_id']}', NOW());
            ";

            $this->db_connect->query($query_request_lg);       

        }

        $res = $this->db_connect->query("
            SELECT {$this->pf}unprocessed_base.user_id, {$this->pf}unprocessed_base.date_time_status_change, {$this->pf}unprocessed_base.date_time_of_first_touch, {$this->pf}unprocessed_base.date_time_of_last_save, {$this->pf}unprocessed_base.status, IF({$this->pf}unprocessed_base.date_time_status_change IS NULL OR DATE_FORMAT({$this->pf}unprocessed_base.date_time_status_change, '%Y-%m-%d') != CURRENT_DATE() OR {$this->pf}unprocessed_base.status != 15, true, false) as is_allow_editing,
                IF({$this->pf}unprocessed_base.source = 'telegram', true, false) as is_source_telegram 
            FROM {$this->pf}unprocessed_base 
            WHERE {$this->pf}unprocessed_base.id = {$data['id']};  
        ");

        $result = $res->fetchAssoc();

        if($result['is_source_telegram'] and $_SESSION['login_role'] == 5) {
            $new_data = ['warning' => 'Редактирование заявок Телеграм запрещено!'];
        }
        elseif($result['is_allow_editing'] or $_SESSION['login_role'] != 5 or ($_SESSION['login_id'] == 289 and $result['user_id'] == 289)) {

            if($_SESSION['login_role'] == 5 and $data['status'] == 15) {
                $query_request_lfs = "
                    UPDATE lead_filling_stats 
                    SET end_datetime = NOW(), 
                        duration_seconds = TIMESTAMPDIFF(SECOND, start_datetime, NOW())
                    WHERE user_id = '{$_SESSION['login_id']}' AND request_id = '{$data['id']}' AND end_datetime IS NULL;
                ";

                $this->db_connect->query($query_request_lfs);
            }
			
            $query_params = '';
            $user_id = null;
            $otdel_id = null;
            $last_sale = null;
            $date_time_status_change = null;
            $new_status = null;
            $user_name = null;

            if(isset($data['status']) and (int) $data['status'] === 16) {
                $res_sale = $this->db_connect->query("SELECT * FROM {$this->pf}sale_request WHERE request_id = {$data['id']} ORDER BY date_time DESC LIMIT 1;");
                $last_sale = $res_sale->fetchAssoc();
            }

            foreach($data['params'] as $item) {
                if($item['name'] !== 'partner' and $item['name'] !== 'user_id' and $item['name'] !== 'past_status') {
                    if($item['name'] === 'status') {
                        if((int) $item['value'] === 16) {
                            $item['value'] = 15;
                        }
                        $new_status = (int) $item['value'];
                    }
                    if(isset($data['status']) and (int) $data['status'] === 16 and $item['name'] === 'date_time_status_change') {
                        $date_time_status_change = trim($item['value']);
                        if(!empty($last_sale)) {
                            $item['value'] = $last_sale['date_time'];
                        }
                    }
                    if($item['name'] === 'date_time_of_last_save' and $result['date_time_of_last_save'] == null) {
                        $query_params .= "date_time_of_first_touch = '" . trim($item['value']) . "', ";
                    }
                    if($item['name'] === 'phone_number') {
                        $item['value'] = clean_phone_number_cod($item['value'], '');
                    }          
                    $query_params .= trim($item['name']) . " = '" . trim($item['value']) . "', ";
                }
                if($item['name'] === 'user_id') {
                    $user_id = trim($item['value']);
                } 
                if($item['name'] === 'past_status' and $new_status !== null and $new_status !== (int) trim($item['value'])) {
                    $query_params .= trim($item['name']) . " = '" . trim($item['value']) . "', ";
                }            
            }        

            $query_params = mb_substr($query_params, 0, -2);

            if($query_params === '' and !$result['is_source_telegram'] and (int) $result['status'] === 15) {
                $results4 = [];
                $res4 = $this->db_connect->query("
                    SELECT id FROM {$this->pf}sale_request WHERE request_id = {$data['id']} AND date_time = '{$result['date_time_status_change']}';
                ");

                while($row = $res4->fetchAssoc()) {
                    $results4[] = $row;
                }

                if(count($results4) !== 0) {
                    foreach ($results4 as $result4) {
                        $query_request2 = "
                            UPDATE {$this->pf}sale_request SET user_id = {$user_id} WHERE id = {$result4['id']}
                        ";         
                        $this->db_connect->query($query_request2);                 
                    }                     
                } 
            } 
 
            if($date_time_status_change === null and isset($data['status']) and (int) $data['status'] === 16) {
                if(!empty($last_sale)) {
                    $query_params .= ", date_time_status_change = '{$last_sale['date_time']}'";
                }                
            } 

            if(isset($data['status']) and (int) $data['status'] === 15) {
                $query_params .= ", is_audio_check = 0";
            }

            if(isset($data['status']) and (int) $data['status'] === 16 and !empty($last_sale) and !empty($last_sale['user_id'])) {

                $result3 = null;
                $res3 = $this->db_connect->query("
                    SELECT bez_reg.id_otdel FROM bez_reg WHERE id = {$last_sale['user_id']};  
                ");
                $result3 = $res3->fetchAssoc();
                
                $query_params .= ", user_id = {$last_sale['user_id']}";
                if($result3 !== null) {
                    $otdel_id = $result3['id_otdel'];
                    $query_params .= ', id_otdel = ' . $result3['id_otdel'];  
                }          
            }
            elseif($_SESSION['login_role'] == 5) {
                $query_params .= ", user_id = {$_SESSION['login_id']}";
                $query_params .= ', id_otdel = ' . $_SESSION['id_otdel'];   
            }
            elseif($user_id !== null) {            
                if($query_params !== '') {
                    $query_params .= ', ';
                }   
                $res2 = $this->db_connect->query("
                    SELECT bez_reg.id_otdel, bez_reg.name FROM bez_reg WHERE id = {$user_id};  
                ");
                    
                $result2 = $res2->fetchAssoc();
                $otdel_id = $result2['id_otdel'];
                $user_name = $result2['name'];
                $query_params .= "user_id = {$user_id}";
                $query_params .= ', id_otdel = ' . $result2['id_otdel'];
            }

            $query_request = "
                UPDATE {$this->pf}unprocessed_base SET {$query_params} WHERE id = {$data['id']}
            ";

            $this->db_connect->query($query_request);

            $this->db_connect->query("DELETE FROM request_real_estate WHERE request_id = {$data['id']}");
            $this->db_connect->query("DELETE FROM request_movables WHERE request_id = {$data['id']}");
            $this->db_connect->query("DELETE FROM request_early_action WHERE request_id = {$data['id']}");
            $this->db_connect->query("DELETE FROM request_messengers WHERE request_id = {$data['id']}");

            if(!empty($data['real_estate'])) {
                foreach($data['real_estate'] as $value) {
                    $this->db_connect->query("INSERT request_real_estate(request_id, real_estate_id) VALUES ({$data['id']}, {$value})");
                }
            }

            if(!empty($data['movables'])) {
                foreach($data['movables'] as $value) {
                    $this->db_connect->query("INSERT request_movables(request_id, movables_id) VALUES ({$data['id']}, {$value})");
                }
            }

            if(!empty($data['early_action'])) {
                foreach($data['early_action'] as $value) {
                    $this->db_connect->query("INSERT request_early_action(request_id, early_action_id) VALUES ({$data['id']}, {$value})");
                }
            }

            if(!empty($data['messengers'])) {
                foreach($data['messengers'] as $value) {
                    $this->db_connect->query("INSERT request_messengers(request_id, messenger_id) VALUES ({$data['id']}, {$value})");
                }
            }

            $this->data['filter'] = array(
                'id' => $data['id']
            ); 
            $response = $this->get_unprocessed_base();
            if($otdel_id !== null) {
                $new_data = ['otdel_id' => $otdel_id, 'user_name' => $user_name];
            }
            else {
                $new_data = $response[0]; 
            }      
			//$new_data = $response;
        } else {
            $new_data = ['warning' => 'Редактирование и продажа запрещены, так как данный лид сегодня уже был продан!'];
        }

        if(DEBUG == 'y') {
            $this->logging('update', 'atc_call', $phone_number, $data['status']);
        }

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($new_data);  

    }

    private function disable_dnd_mode() {
        
        $id_atc = $_SESSION['id_atc'];
        
        $ch = curl_init();

        $queryURL = 'http://83.222.25.208/crm/apitel/service/dnd.php?action=disable&exten=' . $id_atc . '&token=eea779f1-c870-463d-9bf3-888cc80219fa';

        curl_setopt($ch, CURLOPT_URL, $queryURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if(DEBUG == 'y') {
            $this->logging('curl', 'atc_dnd', '', '', $result);
        }
        
        //http_response_code($http_code);   
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit();
    }

    private function get_counters() {  # Вернет счетчики
	
		$where_manual = '';
		$where_manual_sal = '';
		
        $where_double = " AND ({$this->pf}unprocessed_base.is_double IS NULL or {$this->pf}unprocessed_base.is_double = '1' )";
        $operator_where = " AND {$this->pf}unprocessed_base.user_id = {$_SESSION['login_id']}";
        if(in_array($_SESSION['login_role'], [1, 4])) {
            $operator_where = '';
        }

        $date_filter = "(({$this->pf}unprocessed_base.status = 6 AND DATE({$this->pf}unprocessed_base.date_time_status_change) = CURDATE()) OR ({$this->pf}unprocessed_base.status = 15 AND {$this->pf}sale_request.request_id IS NOT NULL AND {$this->pf}sale_request.partner_id <> 65 AND DATE({$this->pf}sale_request.date_time) = CURDATE()) OR ({$this->pf}unprocessed_base.status = 10 AND {$this->pf}unprocessed_base.source = 'telegram' AND DATE({$this->pf}unprocessed_base.date_create) = CURDATE() AND {$this->pf}unprocessed_base.partner <> 65))";
        if(isset($this->data['date_start']) and isset($this->data['date_end'])) {
            $ds = $this->data['date_start'];
            $dend = $this->data['date_end'];
            #$date_filter = " AND {$this->pf}unprocessed_base.date_time_status_change BETWEEN '{$ds}' AND '{$dend}'";
            $date_filter = "(({$this->pf}unprocessed_base.status = 6 AND {$this->pf}unprocessed_base.date_time_status_change BETWEEN '{$ds}' AND '{$dend}') OR ({$this->pf}unprocessed_base.status = 15 AND {$this->pf}sale_request.request_id IS NOT NULL AND {$this->pf}sale_request.partner_id <> 65 AND {$this->pf}sale_request.date_time BETWEEN '{$ds}' AND '{$dend}') OR ({$this->pf}unprocessed_base.status = 10 AND {$this->pf}unprocessed_base.source = 'telegram' AND {$this->pf}unprocessed_base.date_create BETWEEN '{$ds}' AND '{$dend}' AND {$this->pf}unprocessed_base.partner <> 65))";
        }


        if(!empty($this->data['is_double']) and $this->data['is_double'] == 'y') {
            $where_double = " AND {$this->pf}unprocessed_base.is_double = 'y'";
        } 

        if(!empty($this->data['is_double']) and $this->data['is_double'] == 'n') {
            $where_double = " AND {$this->pf}unprocessed_base.is_double IS NULL";
        } 

		if (!isset($this->data['manual_sal'])) {
			$this->data['manual_sal'] = '';
		}

        if(((!empty($this->data['manual']) and $this->data['manual'] == 'r') and (empty($this->data['manual_sal']) and $this->data['manual_sal'] !== '1'))) {
            $where_manual = " AND {$this->pf}unprocessed_base.manual = 'r'";
        } elseif (((empty($this->data['manual']) or $this->data['manual'] == ' ') and (empty($this->data['manual_sal']) and $this->data['manual_sal'] !== '1'))) {
            $where_manual = " AND {$this->pf}unprocessed_base.manual = ''";
        } 

        if((!empty($this->data['manual_sal']) and $this->data['manual_sal'] == '1')) {
            $where_manual_sal = " AND ({$this->pf}unprocessed_base.manual = 'r' OR {$this->pf}unprocessed_base.manual = '')";
        }

        $res = $this->db_connect->query("
            SELECT
                COUNT(DISTINCT(CASE WHEN bez_unprocessed_base.status = 6 THEN bez_unprocessed_base.id END)) AS callings,
                COUNT(DISTINCT(CASE WHEN bez_unprocessed_base.status = 15 OR bez_unprocessed_base.status = 10 THEN bez_unprocessed_base.id END)) AS leads
            FROM
                {$this->pf}unprocessed_base
            LEFT JOIN {$this->pf}sale_request
            ON {$this->pf}unprocessed_base.id = {$this->pf}sale_request.request_id AND {$this->pf}sale_request.partner_id = {$this->pf}unprocessed_base.partner
            WHERE
                {$date_filter}
                {$operator_where}
                {$where_double}
				{$where_manual}
				{$where_manual_sal}			
                AND {$this->pf}unprocessed_base.source IS NOT NULL
        ");

        $result = $res->fetchAssoc();

        if(!empty($this->data['get_json'])) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($result);
            exit();
        }

        return $result;
    }

    private function get_where_filter($table) {
        $logical_operator = 'AND';
        $comparison_operator = '=';

        if(!empty($this->data['filter']['logical_operator'])) {
            $logical_operator = $this->data['filter']['logical_operator'];
            unset($this->data['filter']['logical_operator']);
        }
        if(!empty($this->data['filter']['comparison_operator'])) {
            $comparison_operator = $this->data['filter']['comparison_operator'];
            unset($this->data['filter']['comparison_operator']);
        }

        if(!empty($this->data['filter'])) {
            $where_conditions = [];

            foreach($this->data['filter'] as $key => $value) {
                if(!empty($value)) {
                    if($comparison_operator == 'IN') {
                        $value = "({$value})";
                    } else {
                        $value = "'{$value}'";
                    }
                    $where_conditions[] = "{$this->pf}{$table}.{$key} {$comparison_operator} {$value}";
                }
            }
			
            return 'AND ' . implode(" {$logical_operator} ", $where_conditions);
        } else
            return '';
    }

    public function get_unprocessed_base() {
        $where_filter = $this->get_where_filter('unprocessed_base');

        $limit_start = isset($this->data['limit_start']) ? $this->data['limit_start'] : 0;
        $limit_end = isset($this->data['limit_end']) ? $this->data['limit_end'] : 100;
        $order_by = isset($this->data['order_by']) ? $this->data['order_by'] : 'id DESC';
        $order_by = $this->pf . 'unprocessed_base.' . $order_by;

        $varrible_date = 'unprocessed_base.date_create';
        $select_partner_id = "";
        $add_requests_telegram = "";
        $select_operator_id = "{$this->pf}reg.id as operator_id,";
        $select_new_lead = "";
        $where_sale_dates = "";
        $where_double = "";
        $where_manual = "";
        $where_manual_sal = "";
        $csv_filter = "";
        $where_limit = "";

        if(!isset($this->data['is_limit'])) {
            $where_limit = "LIMIT {$limit_start}, {$limit_end}";
        }

        $query_request = "
            UPDATE {$this->pf}unprocessed_base
            SET date_time_status_change = (SELECT date_time FROM {$this->pf}sale_request WHERE request_id = {$this->pf}unprocessed_base.id ORDER BY date_time DESC LIMIT 1),
                status = past_status,
                user_id = IFNULL((SELECT user_id FROM {$this->pf}sale_request WHERE request_id = {$this->pf}unprocessed_base.id ORDER BY date_time DESC LIMIT 1), user_id)
            WHERE status = 6 AND date_time_status_change IS NOT NULL AND date_time_status_change + interval 30 minute < now() + interval {$this->tz} hour AND past_status IS NOT NULL;
        ";

        $this->db_connect->query($query_request);        

        if(!empty($this->data['filter']['status']) and in_array($this->data['filter']['status'], [6, 15]) and !isset($this->data['filter_by_status'])) {
            $varrible_date = 'unprocessed_base.date_time_status_change'; 
            if((int) $this->data['filter']['status'] === 15 and !empty($this->data['view_name']) and $this->data['view_name'] == 'unprocessed') {
               $varrible_date = 'sale_request.date_time';
            }            
            if(!in_array($_SESSION['login_role'], [1, 4]) and isset($this->data['defect_bg']) and $this->data['defect_bg'] === 'yes') {
                $where_filter .= " AND {$this->pf}unprocessed_base.user_id = {$_SESSION['login_id']}";
                $add_requests_telegram = " OR (bez_unprocessed_base.status = '10' AND bez_unprocessed_base.source = 'telegram' AND bez_unprocessed_base.partner <> 65 AND bez_unprocessed_base.date_create BETWEEN '{$this->data['date_start']}' AND '{$this->data['date_end']}' AND bez_unprocessed_base.user_id = {$_SESSION['login_id']})";
            }
            if(in_array($_SESSION['login_role'], [5]) and isset($this->data['defect_bg']) and $this->data['defect_bg'] === 'yes') {
                $select_partner_id = "{$this->pf}unprocessed_base.partner AS partner_id,";
            }
        }

        if(isset($this->data['date_time_status_change']) and isset($this->data['comparison_operator'])) {
            $ds = $this->data['date_time_status_change'] . ' 00:00:00';
            $operator = $this->data['comparison_operator'];
            $where_filter .= " AND {$this->pf}unprocessed_base.date_time_status_change {$operator} '{$ds}'";
        }

        if(isset($this->data['date_start']) and isset($this->data['date_end'])) {
            $ds = $this->data['date_start'] . ' 00:00:00';
            $dend = $this->data['date_end'] . ' 23:59:59';
            if(!empty($this->data['view_name']) and $this->data['view_name'] == 'sales') {
                $where_filter .= " AND (({$this->pf}unprocessed_base.date_time_status_change BETWEEN '{$ds}' AND '{$dend}') OR ({$this->pf}sale_request.date_time BETWEEN '{$ds}' AND '{$dend}'))";
                $order_by .= ", {$this->pf}sale_request.date_time DESC";
            } 
            else {
                if(!empty($this->data['is_double']) and $this->data['is_double'] == 'n' and empty($this->data['manual']) and empty($this->data['user_access'])) {
                    $where_filter .= " AND IF({$this->pf}unprocessed_base.date_time_of_last_save IS NULL, {$this->pf}unprocessed_base.date_create BETWEEN '{$ds}' AND '{$dend}', DATE({$this->pf}unprocessed_base.date_time_of_last_save) BETWEEN '{$ds}' AND '{$dend}')";
                }
                else {
                    $where_filter .= " AND {$this->pf}{$varrible_date} BETWEEN '{$ds}' AND '{$dend}'";
                }      
            }    
        }

        if(($_SESSION['login_role'] == 4 or ($_SESSION['login_role'] == 5 and $_SESSION['login_id'] == 289)) and (!empty($this->data['view_name']) and $this->data['view_name'] == 'sales')) {
            $where_filter .= " AND {$this->pf}unprocessed_base.id_otdel = {$_SESSION['id_otdel']}";
        }

        if(!in_array($_SESSION['login_role'], [1, 4])) {
            $where_filter .= "
                AND (
                    {$this->pf}unprocessed_base.status <> 6
                OR (
                    {$this->pf}unprocessed_base.status = 6
                    AND {$this->pf}unprocessed_base.user_id = {$_SESSION['login_id']})
                )
            ";
        } 

        if(!empty($this->data['is_double']) and $this->data['is_double'] == 'n' and empty($this->data['manual']) and empty($this->data['user_access'])) {
            if(in_array($_SESSION['login_role'], [1, 4]) and isset($this->data['filter']['status']) and $this->data['filter']['status'] == 10) {
                $where_filter .= " AND {$this->pf}unprocessed_base.user_id = ''";
            }              
        }         
		
		if (isset($this->data['filter2'])) {
			if(in_array($_SESSION['login_role'], [1]) and $this->data['filter2']['id_start'] !== '' and $this->data['filter2']['id_end'] !== '' ) {
				$id_start = $this->data['filter2']['id_start'];
				$id_end = $this->data['filter2']['id_end'];
				$id_status = $this->data['filter2']['id_status'];
				
				if( isset($this->data['filter2']['id_status']) and $this->data['filter2']['id_status'] !== '' and $this->data['filter2']['id_status'] !== 'Статус' ) {
					$csv_filter .= "
						AND {$this->pf}unprocessed_base.id between {$id_start} and {$id_end}
						AND {$this->pf}unprocessed_base.status = {$id_status}
					";				
				} else {
					$csv_filter .= "
						AND {$this->pf}unprocessed_base.id between {$id_start} and {$id_end}
					";
				}
			}
		}

        $where_filter .= "
            AND ({$this->pf}unprocessed_base.source IS NOT NULL AND {$this->pf}unprocessed_base.source <> 'telegram')
        ";

        if(!empty($this->data['view_name']) and $this->data['view_name'] == 'sales') {
            $select_operator_id = "IF({$this->pf}sale_request.request_id IS NOT NULL AND {$this->pf}sale_request.date_time BETWEEN '{$ds}' AND '{$dend}', {$this->pf}sale_request.user_id, {$this->pf}unprocessed_base.user_id) AS operator_id,";
            $select_new_lead = " AND DATE('{$ds}') = CURRENT_DATE() AND DATE('{$dend}') = CURRENT_DATE()";
            $where_sale_dates = " AND DATE({$this->pf}sale_request.date_time) BETWEEN DATE('{$ds}') AND DATE('{$dend}')";
        }

        if(!empty($this->data['is_double']) and $this->data['is_double'] == 'y') {
            $where_double = " AND {$this->pf}unprocessed_base.is_double = 'y'";
        } 

        if(!empty($this->data['is_double']) and $this->data['is_double'] == 'n') {
            $where_double = " AND {$this->pf}unprocessed_base.is_double IS NULL";
            if($_SESSION['login_role'] == 5 and empty($this->data['manual']) and empty($this->data['user_access'])) {
                $where_filter .= " AND {$this->pf}unprocessed_base.user_id = {$_SESSION['login_id']}";
            }
            if($_SESSION['login_role'] == 4 and empty($this->data['manual']) and empty($this->data['user_access'])) {
                $where_filter .= " AND {$this->pf}unprocessed_base.id_otdel = {$_SESSION['id_otdel']}";
            }
        } 

		if (!isset($this->data['manual_sal'])) {
			$this->data['manual_sal'] = '';
		}

        if(((!empty($this->data['manual']) and $this->data['manual'] == 'r') and (empty($this->data['manual_sal']) and $this->data['manual_sal'] !== '1'))) {
            $where_manual = " AND {$this->pf}unprocessed_base.manual = 'r'";
        } elseif (((empty($this->data['manual']) or $this->data['manual'] == ' ') and (empty($this->data['manual_sal']) and $this->data['manual_sal'] !== '1'))) {
            $where_manual = " AND {$this->pf}unprocessed_base.manual = ''";
        } 

        if((!empty($this->data['manual_sal']) and $this->data['manual_sal'] == '1')) {
            $where_manual_sal = " AND ({$this->pf}unprocessed_base.manual = 'r' OR {$this->pf}unprocessed_base.manual = '')";
        }




		if ($_SESSION['id_otdel'] == '4') {
			$limit_start = 0;
			$limit_end = 1;
		}

        $res = $this->db_connect->query("
            SELECT
                {$this->pf}unprocessed_base.id,
                {$this->pf}unprocessed_base.fio,
                CONCAT('7', {$this->pf}unprocessed_base.phone_number) AS phone_number,
                {$this->pf}unprocessed_base.vopros,
                {$this->pf}unprocessed_base.city,
                {$this->pf}unprocessed_base.status,
                CONCAT_WS(', ', p1.partner1, p2.partner2) AS partner,
				{$this->pf}unprocessed_base.is_double,
				{$this->pf}unprocessed_base.is_audio_check,
                {$this->pf}unprocessed_base.manual,
                IF({$this->pf}unprocessed_base.date_time_status_change IS NOT NULL and {$this->pf}unprocessed_base.status = 15, DATE_ADD({$this->pf}unprocessed_base.date_time_status_change, INTERVAL {$this->tz} HOUR), {$this->pf}unprocessed_base.date_time_status_change) AS date_time_status_change,
                {$this->pf}unprocessed_base.date_create,
                {$this->pf}unprocessed_base.user_id,
                {$select_operator_id}
                {$select_partner_id}
                {$this->pf}unprocessed_base.timez,
                {$this->pf}unprocessed_base.source,
                {$this->pf}unprocessed_base.id_otdel,
                {$this->pf}unprocessed_base.date_time_hold_calling,
                {$this->pf}unprocessed_base.hold_status_id,
                {$this->pf}reg.name as operator_name,
				part.partner_name as partner_name,
                status_table.status_name,
                hold_status_table.status_name AS hold_status_name,  
                {$this->pf}unprocessed_base.partner AS last_partner_id,      
                IF({$this->pf}sale_request.request_id IS NOT NULL OR {$this->pf}unprocessed_base.status = 15, 1, 0) AS is_lead,
                IF({$this->pf}unprocessed_base.date_time_lead_save IS NOT NULL AND DATE_FORMAT({$this->pf}unprocessed_base.date_time_lead_save, '%Y-%m-%d') = CURRENT_DATE() AND ({$this->pf}unprocessed_base.date_time_status_change IS NULL OR DATE_FORMAT({$this->pf}unprocessed_base.date_time_status_change, '%Y-%m-%d') <> CURRENT_DATE()), 1, 0) AS is_save_today,
                {$this->pf}unprocessed_base.group_source as group_source_id,
                CASE
                    WHEN {$this->pf}sale_request.request_id IS NULL THEN TRUE
                    WHEN DATE({$this->pf}unprocessed_base.date_time_status_change) > DATE({$this->pf}sale_request.date_time) {$select_new_lead}
                    THEN TRUE
                    ELSE FALSE
                END as new_lead
            FROM
                {$this->pf}unprocessed_base
            LEFT JOIN
                {$this->pf}status status_table ON status_table.status_id = {$this->pf}unprocessed_base.status
            LEFT JOIN
                {$this->pf}status hold_status_table ON hold_status_table.status_id = {$this->pf}unprocessed_base.hold_status_id
            LEFT JOIN
                {$this->pf}reg ON {$this->pf}reg.id = {$this->pf}unprocessed_base.user_id
            LEFT JOIN
                {$this->pf}sale_request ON {$this->pf}sale_request.request_id = {$this->pf}unprocessed_base.id {$where_sale_dates}
            LEFT JOIN 
                (SELECT phone, GROUP_CONCAT(DISTINCT partners_request SEPARATOR ', ') AS partner1 FROM (SELECT sr.request_id, ub.phone_number AS phone, GROUP_CONCAT(DISTINCT sr.partner_id SEPARATOR ', ') AS partners_request FROM bez_sale_request sr JOIN bez_unprocessed_base ub ON ub.id = sr.request_id GROUP BY sr.request_id) AS table_partners_request GROUP BY phone) p1 ON {$this->pf}unprocessed_base.phone_number = p1.phone
            LEFT JOIN 
                (SELECT ub.phone_number AS phone, GROUP_CONCAT(DISTINCT ub.partner SEPARATOR ', ') AS partner2 FROM {$this->pf}unprocessed_base ub WHERE ub.source = 'telegram' or ub.source is null GROUP BY phone) p2 
            ON {$this->pf}unprocessed_base.phone_number = p2.phone
			LEFT JOIN st_partner_s part ON part.id = {$this->pf}sale_request.partner_id
            WHERE
                (({$this->pf}unprocessed_base.source IS NOT NULL {$where_filter}) {$where_double}) 
                $add_requests_telegram
                $csv_filter
				$where_manual
				$where_manual_sal
            GROUP BY {$this->pf}unprocessed_base.id
            ORDER BY
                {$order_by}
            {$where_limit}
        ");

        $unprocessed_base_requests = NULL;
        while($row = $res->fetchAssoc()) {
            $unprocessed_base_requests[] = $row;
        }

        if($unprocessed_base_requests != NULL) {
			
			$delimiter = ";"; 
			$buffer = fopen(__DIR__ . '/uploads/data_'.$_SESSION['login_id'].'.csv', 'w'); 
			// Добавляет в начало файла метку BOM, благодаря этому файл откроется в Excel с нормальной кодировкой.
			fputs($buffer, chr(0xEF) . chr(0xBB) . chr(0xBF));
			// Данные в файл csv
			foreach($unprocessed_base_requests as $row){ 
				$lineData = array(trim($row['phone_number']), trim($row['fio']), trim($row['city']), trim($row['vopros'])); 
				fputcsv($buffer, $lineData, $delimiter); 
			} 
			fclose($buffer); 			
			
			
            if(!empty($this->data['view_name'])) {
                $path = $_SERVER['DOCUMENT_ROOT'] . '/scripts/unprocessed_base/view/' . $this->data['view_name'] . '.php';
                include ($path);
            } else {
                return $unprocessed_base_requests;
            }
        }
    }

    public function get_unprocessed_base2() {

        $where_filter = $this->get_where_filter('unprocessed_base');

        $order_by = isset($this->data['order_by']) ? $this->data['order_by'] : 'id DESC';
        $order_by = $this->pf . 'unprocessed_base.' . $order_by;
        $varrible_date = 'unprocessed_base.date_create';
        $draw = $this->data['draw'] ?? 1;
        $start = $this->data['start'] ?? 0;
        $length = $this->data['length'] ?? 25;
        $city_group_filter = $this->data['city_group_filter'] ?? '';
        $city_filter = $this->data['city_filter'] ?? '';
        $user_id_filter = $this->data['user_id_filter'] ?? '';
        $source_filter = $this->data['source_filter'] ?? '';
        $id_otdel_filter = $this->data['id_otdel_filter'] ?? '';
        $status_filter = $this->data['status_filter'] ?? '';
        $hold_status_filter = $this->data['hold_status_filter'] ?? '';
        $search_word = $this->data['search_word'] ?? '';
        $filter_operator_type = $this->data['filter_operator_type'] ?? '';
        $add_filters = '';
        $where_double = '';
        $where_manual = '';

        $query_request = "
            UPDATE {$this->pf}unprocessed_base
            SET date_time_status_change = (SELECT date_time FROM {$this->pf}sale_request WHERE request_id = {$this->pf}unprocessed_base.id ORDER BY date_time DESC LIMIT 1),
                status = past_status,
                user_id = IFNULL((SELECT user_id FROM {$this->pf}sale_request WHERE request_id = {$this->pf}unprocessed_base.id ORDER BY date_time DESC LIMIT 1), user_id)
            WHERE status = 6 AND date_time_status_change IS NOT NULL AND date_time_status_change + interval 30 minute < now() + interval {$this->tz} hour AND past_status IS NOT NULL;
        ";

        $this->db_connect->query($query_request);      

        if(!empty($this->data['holds']) and $this->data['holds'] == 'y') {
            $varrible_date = 'unprocessed_base.date_time_status_change';
        }

        if(empty($this->data['holds']) and empty($this->data['operator_requests'])) {
            $where_double = " AND {$this->pf}unprocessed_base.is_double IS NULL";
            $where_manual = " AND {$this->pf}unprocessed_base.manual = ''";            
        }

        //выбор диапазона дат для вкладок Трафик и Заявки Excel
        if(isset($this->data['date_start']) and isset($this->data['date_end']) and empty($search_word)) {
            $ds = $this->data['date_start'] . ' 00:00:00';
            $dend = $this->data['date_end'] . ' 23:59:59';
            if(!empty($this->data['is_double']) and $this->data['is_double'] == 'n' and empty($this->data['manual']) and empty($this->data['user_access']) and empty($this->data['holds'])and empty($this->data['operator_requests'])) {
                $where_filter .= " AND IF({$this->pf}unprocessed_base.date_time_of_last_save IS NULL, {$this->pf}unprocessed_base.date_create BETWEEN '{$ds}' AND '{$dend}', DATE({$this->pf}unprocessed_base.date_time_of_last_save) BETWEEN '{$ds}' AND '{$dend}')";
            } else if(!empty($this->data['is_today_calls']) and $this->data['is_today_calls'] == 'y') {
                $where_filter .= " AND DATE({$this->pf}unprocessed_base.date_time_hold_calling) = CURRENT_DATE()";
            } else if(!empty($this->data['operator_requests']) and $this->data['operator_requests'] == 'y') {
                $where_filter .= "";
            }
            else {
                $where_filter .= " AND DATE({$this->pf}{$varrible_date}) BETWEEN '{$ds}' AND '{$dend}'";
            }      
               
        }

        if(!empty($filter_operator_type) and $filter_operator_type == 'calls-today') {
            $where_filter .= " AND DATE({$this->pf}unprocessed_base.date_time_status_change) = CURRENT_DATE() AND {$this->pf}unprocessed_base.status = 6";
            if($_SESSION['login_role'] == 4) {
                $where_filter .= " AND {$this->pf}unprocessed_base.id_otdel = {$_SESSION['id_otdel']}";
            }
            $order_by = $this->pf . 'unprocessed_base.date_time_status_change';
        } else if(!empty($filter_operator_type) and $filter_operator_type == 'leads-today') {
            $where_filter .= " AND DATE({$this->pf}unprocessed_base.date_time_status_change) = CURRENT_DATE() AND {$this->pf}unprocessed_base.status = 15";
            if($_SESSION['login_role'] == 5) {
                $where_filter .= " AND {$this->pf}unprocessed_base.user_id = {$_SESSION['login_id']}";
            }
            if($_SESSION['login_role'] == 4) {
                $where_filter .= " AND {$this->pf}unprocessed_base.id_otdel = {$_SESSION['id_otdel']}";
            }            
            $order_by = $this->pf . 'unprocessed_base.date_time_status_change';
        } else if(!empty($filter_operator_type) and $filter_operator_type == 'calls-all') {
            $where_filter .= " AND {$this->pf}unprocessed_base.status = 6";
            if($_SESSION['login_role'] == 4) {
                $where_filter .= " AND {$this->pf}unprocessed_base.id_otdel = {$_SESSION['id_otdel']}";
            }
            $order_by = $this->pf . 'unprocessed_base.date_time_status_change';
        } else if(!empty($filter_operator_type) and $filter_operator_type == 'leads-all') {
            $where_filter .= " AND date_time_status_change IS NOT NULL AND {$this->pf}unprocessed_base.status = 15";
            if($_SESSION['login_role'] == 5) {
                $where_filter .= " AND {$this->pf}unprocessed_base.user_id = {$_SESSION['login_id']}";
            }
            if($_SESSION['login_role'] == 4) {
                $where_filter .= " AND {$this->pf}unprocessed_base.id_otdel = {$_SESSION['id_otdel']}";
            } 
            $order_by = $this->pf . 'unprocessed_base.date_time_status_change';  
        } else if(!empty($filter_operator_type) and $filter_operator_type == 'leads-saved') {
            $where_filter .= " AND date_time_lead_save IS NOT NULL AND {$this->pf}unprocessed_base.status = 15";
            if($_SESSION['login_role'] == 5) {
                $where_filter .= " AND {$this->pf}unprocessed_base.user_id = {$_SESSION['login_id']}";
            }
            if($_SESSION['login_role'] == 4) {
                $where_filter .= " AND {$this->pf}unprocessed_base.id_otdel = {$_SESSION['id_otdel']}";
            } 
            $order_by = $this->pf . 'unprocessed_base.date_time_lead_save DESC';  
        } else if(!empty($filter_operator_type) and $filter_operator_type == 'hung-up-target') {
            $where_filter .= " AND {$this->pf}unprocessed_base.status = 32";
            if($_SESSION['login_role'] == 5) {
                $where_filter .= " AND {$this->pf}unprocessed_base.user_id = {$_SESSION['login_id']}";
            }
            $order_by = $this->pf . 'unprocessed_base.date_time_of_last_save DESC';  
        }

        //показывать оператору только его созвоны
        if(!in_array($_SESSION['login_role'], [1, 4])) {
            $where_filter .= "
                AND (
                    {$this->pf}unprocessed_base.status <> 6
                OR (
                    {$this->pf}unprocessed_base.status = 6
                    AND {$this->pf}unprocessed_base.user_id = {$_SESSION['login_id']})
                )
            ";
        } 

        //в необработанных показывать только нераспределенные заявки
        if(!empty($this->data['is_double']) and $this->data['is_double'] == 'n' and empty($this->data['manual']) and empty($this->data['user_access']) and empty($this->data['holds']) and empty($this->data['operator_requests'])) {
            if(in_array($_SESSION['login_role'], [1, 4]) and isset($this->data['filter']['status']) and $this->data['filter']['status'] == 10) {
                $where_filter .= " AND {$this->pf}unprocessed_base.user_id = ''";
            }              
        }         

        //Показывать заявки только предназначенные оператору или отделу во вкладке Трафик
        if(!empty($this->data['is_double']) and $this->data['is_double'] == 'n') {
            if($_SESSION['login_role'] == 5 and empty($this->data['manual']) and empty($this->data['user_access']) and empty($this->data['holds']) and empty($this->data['operator_requests'])) {
                $where_filter .= " AND {$this->pf}unprocessed_base.user_id = {$_SESSION['login_id']}";
            }
            if($_SESSION['login_role'] == 4 and empty($this->data['manual']) and empty($this->data['user_access']) and empty($this->data['holds']) and empty($this->data['operator_requests'])) {
                $where_filter .= " AND {$this->pf}unprocessed_base.id_otdel = {$_SESSION['id_otdel']}";
            }
        } 

        if(!empty($this->data['holds']) and $this->data['holds'] == 'y') {
            $where_filter .= " AND {$this->pf}unprocessed_base.status = 15 AND CURRENT_DATE() > DATE_ADD(DATE({$this->pf}unprocessed_base.date_time_status_change), INTERVAL 31 DAY)";
        }

        if(!empty($city_group_filter)) {
            $add_filters .= " AND {$this->pf}unprocessed_base.auto_city_group = '{$city_group_filter}'";
        }

        if(!empty($city_filter)) {
            $add_filters .= " AND {$this->pf}unprocessed_base.city = '{$city_filter}'";
        }

        if(!empty($user_id_filter)) {
            $add_filters .= " AND {$this->pf}unprocessed_base.user_id = '{$user_id_filter}'";
        }

        if(!empty($source_filter)) {
            $add_filters .= " AND {$this->pf}unprocessed_base.source = '{$source_filter}'";
        }

        if(!empty($id_otdel_filter)) {
            $add_filters .= " AND {$this->pf}unprocessed_base.id_otdel = '{$id_otdel_filter}'";
        }

        if(!empty($status_filter)) {
            $add_filters .= " AND {$this->pf}unprocessed_base.status = '{$status_filter}'";
        }

        if(!empty($this->data['is_today_calls']) and $this->data['is_today_calls'] == 'y') {
            $add_filters .= " AND {$this->pf}unprocessed_base.hold_status_id = '35'";
        }
        else if(!empty($hold_status_filter)) {
            $add_filters .= " AND {$this->pf}unprocessed_base.hold_status_id = '{$hold_status_filter}'";
        }

        if(!empty($search_word)) {
            $add_filters .= " AND ({$this->pf}unprocessed_base.id LIKE '%{$search_word}%' OR {$this->pf}unprocessed_base.fio LIKE '%{$search_word}%' OR {$this->pf}unprocessed_base.phone_number LIKE '%{$search_word}%' OR CONCAT('7', {$this->pf}unprocessed_base.phone_number) LIKE '{$search_word}' OR {$this->pf}unprocessed_base.vopros LIKE '%{$search_word}%' OR {$this->pf}unprocessed_base.city LIKE '%{$search_word}%')";
        }

        $res = $this->db_connect->query("
            SELECT
                {$this->pf}unprocessed_base.id,
                {$this->pf}unprocessed_base.fio,
                CONCAT('7', {$this->pf}unprocessed_base.phone_number) AS phone_number,
                {$this->pf}unprocessed_base.vopros,
                {$this->pf}unprocessed_base.city,
                {$this->pf}unprocessed_base.status,
                CONCAT_WS(', ', p1.partner1, p2.partner2) AS partner,
                {$this->pf}unprocessed_base.is_double,
                {$this->pf}unprocessed_base.manual,
                IF({$this->pf}unprocessed_base.date_time_status_change IS NOT NULL and {$this->pf}unprocessed_base.status = 15, DATE_ADD({$this->pf}unprocessed_base.date_time_status_change, INTERVAL {$this->tz} HOUR), {$this->pf}unprocessed_base.date_time_status_change) AS date_time_status_change,
                {$this->pf}unprocessed_base.date_create,
                {$this->pf}unprocessed_base.user_id,
                {$this->pf}unprocessed_base.source,
                {$this->pf}unprocessed_base.id_otdel,
                {$this->pf}unprocessed_base.date_time_hold_calling,
                {$this->pf}unprocessed_base.hold_status_id,
                {$this->pf}reg.name as operator_name,
                status_table.status_name,
                hold_status_table.status_name AS hold_status_name,
                IF({$this->pf}sale_request.request_id IS NOT NULL OR {$this->pf}unprocessed_base.status = 15, 1, 0) AS is_lead,
                IF({$this->pf}unprocessed_base.date_time_lead_save IS NOT NULL AND DATE_FORMAT({$this->pf}unprocessed_base.date_time_lead_save, '%Y-%m-%d') = CURRENT_DATE() AND ({$this->pf}unprocessed_base.date_time_status_change IS NULL OR DATE_FORMAT({$this->pf}unprocessed_base.date_time_status_change, '%Y-%m-%d') <> CURRENT_DATE()), 1, 0) AS is_save_today,
                {$this->pf}unprocessed_base.group_source as group_source_id
            FROM
                {$this->pf}unprocessed_base

            LEFT JOIN
                {$this->pf}status status_table ON status_table.status_id = {$this->pf}unprocessed_base.status
            LEFT JOIN
                {$this->pf}status hold_status_table ON hold_status_table.status_id = {$this->pf}unprocessed_base.hold_status_id
            LEFT JOIN
                {$this->pf}reg ON {$this->pf}reg.id = {$this->pf}unprocessed_base.user_id
            LEFT JOIN
                {$this->pf}sale_request ON {$this->pf}sale_request.request_id = {$this->pf}unprocessed_base.id
            LEFT JOIN 
                (SELECT phone, GROUP_CONCAT(DISTINCT partners_request SEPARATOR ', ') AS partner1 FROM (SELECT sr.request_id, ub.phone_number AS phone, GROUP_CONCAT(DISTINCT sr.partner_id SEPARATOR ', ') AS partners_request FROM bez_sale_request sr JOIN bez_unprocessed_base ub ON ub.id = sr.request_id GROUP BY sr.request_id) AS table_partners_request GROUP BY phone) p1 ON {$this->pf}unprocessed_base.phone_number = p1.phone
            LEFT JOIN 
                (SELECT ub.phone_number AS phone, GROUP_CONCAT(DISTINCT ub.partner SEPARATOR ', ') AS partner2 FROM {$this->pf}unprocessed_base ub WHERE ub.source = 'telegram' or ub.source is null GROUP BY phone) p2 
            ON {$this->pf}unprocessed_base.phone_number = p2.phone
            WHERE
                ({$this->pf}unprocessed_base.source IS NOT NULL AND {$this->pf}unprocessed_base.source <> 'telegram') {$where_double} {$where_manual} {$where_filter} {$add_filters}
            GROUP BY {$this->pf}unprocessed_base.id
            ORDER BY
                {$order_by}
            LIMIT {$start}, {$length};
        ");

        $unprocessed_base_requests = [];
        while($row = $res->fetchAssoc()) {
            $unprocessed_base_requests[] = $row;
        }

        $res_total_count = $this->db_connect->query("
            SELECT
                COUNT({$this->pf}unprocessed_base.id) AS recordsTotal
            FROM
                {$this->pf}unprocessed_base
            LEFT JOIN
                {$this->pf}reg ON bez_reg.id = {$this->pf}unprocessed_base.user_id
            WHERE
                ({$this->pf}unprocessed_base.source IS NOT NULL AND {$this->pf}unprocessed_base.source <> 'telegram') {$where_double} {$where_manual} {$where_filter};
        ");

         $res_filtered_count = $this->db_connect->query("
            SELECT
                COUNT({$this->pf}unprocessed_base.id) AS recordsTotal
            FROM
                {$this->pf}unprocessed_base
            LEFT JOIN
                {$this->pf}reg ON bez_reg.id = {$this->pf}unprocessed_base.user_id
            WHERE
                ({$this->pf}unprocessed_base.source IS NOT NULL AND {$this->pf}unprocessed_base.source <> 'telegram') {$where_double} {$where_manual} {$where_filter} {$add_filters};
        ");

        $result_total_count = $res_total_count->fetchAssoc();
        $result_filtered_count = $res_filtered_count->fetchAssoc();
        $records_total = $result_total_count['recordsTotal'];
        $records_filtered = $result_filtered_count['recordsTotal'];

        $result = [ 
            "draw" => $draw,
            "recordsTotal" => $records_total,
            "recordsFiltered" => $records_filtered,
            "data" => $unprocessed_base_requests
        ];
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
        exit();

    }

    public function get_client() {
        $request_id = $this->data['request_id'];
        $res = $this->db_connect->query("SELECT {$this->pf}unprocessed_base.id, fio, phone_number, city, status, vopros, IFNULL({$this->pf}cities_group.name, '') AS region, debt_banks, debt_mfo, taxes_fines, debt_zhkh, owners, delays, mortgage, car_loan, additional_comment, hold_status_id, date_time_hold_calling, IFNULL(other_movables, '') AS other_movables, IFNULL(other_early_action, '') AS other_early_action, IFNULL(messenger_phone_number, '') AS messenger_phone_number, date_time_status_change, GROUP_CONCAT(DISTINCT request_real_estate.real_estate_id) AS selected_real_estate, GROUP_CONCAT(DISTINCT request_movables.movables_id) AS selected_movables, GROUP_CONCAT(DISTINCT request_early_action.early_action_id) AS selected_early_action, GROUP_CONCAT(DISTINCT request_messengers.messenger_id) AS selected_messengers, CONCAT_WS(', ', p1.partner1, p2.partner2) AS partner
                                        FROM {$this->pf}unprocessed_base 
                                        LEFT JOIN {$this->pf}cities_group
                                        ON {$this->pf}unprocessed_base.auto_city_group = {$this->pf}cities_group.id
                                        LEFT JOIN request_real_estate
                                        ON bez_unprocessed_base.id = request_real_estate.request_id
                                        LEFT JOIN request_movables
                                        ON bez_unprocessed_base.id = request_movables.request_id
                                        LEFT JOIN request_early_action
                                        ON bez_unprocessed_base.id = request_early_action.request_id
                                        LEFT JOIN request_messengers
                                        ON bez_unprocessed_base.id = request_messengers.request_id
                                        LEFT JOIN 
                                            (SELECT phone, GROUP_CONCAT(DISTINCT partners_request SEPARATOR ', ') AS partner1 FROM (SELECT sr.request_id, ub.phone_number AS phone, GROUP_CONCAT(DISTINCT sr.partner_id SEPARATOR ', ') AS partners_request FROM bez_sale_request sr JOIN bez_unprocessed_base ub ON ub.id = sr.request_id GROUP BY sr.request_id, ub.phone_number) AS table_partners_request GROUP BY phone) p1 
                                        ON {$this->pf}unprocessed_base.phone_number = p1.phone
                                        LEFT JOIN 
                                            (SELECT ub.phone_number AS phone, GROUP_CONCAT(DISTINCT ub.partner SEPARATOR ', ') AS partner2 FROM {$this->pf}unprocessed_base ub WHERE ub.source = 'telegram' or ub.source is null GROUP BY ub.phone_number) p2 
                                        ON {$this->pf}unprocessed_base.phone_number = p2.phone
                                        WHERE {$this->pf}unprocessed_base.id = {$request_id}
                                        GROUP BY {$this->pf}unprocessed_base.id;");
        $result = $res->fetchAssoc();

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
        exit();
    }    

    public function get_departments() {
        $res = $this->db_connect->query("
            SELECT name, department_id
            FROM {$this->pf}department
        ");

        $result = NULL;
        while($row = $res->fetchAssoc()) {
            $result[] = $row;
        }

        return $result;
    }

    public function get_quantity_by_statuses() {

        $where_manual = '';
        $where_manual_sal = '';
        $where_operator = '';
        $where_otdel = '';
        $where_unprocessed = '';
        $date_filter = '';

        unset($this->data['filter']['status']);
        $where_filter = $this->get_where_filter('unprocessed_base');
        
        $where_double = " AND ({$this->pf}unprocessed_base.is_double IS NULL or {$this->pf}unprocessed_base.is_double = '1' )"; 

        if(!empty($this->data['is_double']) and $this->data['is_double'] == 'n' and empty($this->data['manual']) and empty($this->data['user_access'])) {
            $date_filter = "IF({$this->pf}unprocessed_base.date_time_of_last_save IS NULL, {$this->pf}unprocessed_base.date_create = CURDATE(), DATE({$this->pf}unprocessed_base.date_time_of_last_save) = CURDATE())";
        } 
        else {
            $date_filter = "{$this->pf}unprocessed_base.date_create = CURDATE()";
        }
        if(isset($this->data['date_start']) and isset($this->data['date_end'])) {
            $ds = $this->data['date_start'];
            $dend = $this->data['date_end'];
            if(!empty($this->data['is_double']) and $this->data['is_double'] == 'n' and empty($this->data['manual']) and empty($this->data['user_access'])) {
                $date_filter = "IF({$this->pf}unprocessed_base.date_time_of_last_save IS NULL, {$this->pf}unprocessed_base.date_create BETWEEN '{$ds}' AND '{$dend}', DATE({$this->pf}unprocessed_base.date_time_of_last_save) BETWEEN '{$ds}' AND '{$dend}')";
            }
            else {
                $date_filter = "{$this->pf}unprocessed_base.date_create BETWEEN '{$ds}' AND '{$dend}'";
            }
        }

        if(!empty($this->data['is_double']) and $this->data['is_double'] == 'y') {
            $where_double = " AND {$this->pf}unprocessed_base.is_double = 'y'";
        } 

        if(!empty($this->data['is_double']) and $this->data['is_double'] == 'n') {
            $where_double = " AND {$this->pf}unprocessed_base.is_double IS NULL";
            if($_SESSION['login_role'] == 5 and empty($this->data['manual']) and empty($this->data['user_access'])) {
                $where_operator = " AND {$this->pf}unprocessed_base.user_id = {$_SESSION['login_id']}";
            }
            if($_SESSION['login_role'] == 4 and empty($this->data['manual']) and empty($this->data['user_access'])) {
                $where_otdel = " AND {$this->pf}unprocessed_base.id_otdel = {$_SESSION['id_otdel']}";
            }
        } 

        if (!isset($this->data['manual_sal'])) {
            $this->data['manual_sal'] = '';
        }

        if(((!empty($this->data['manual']) and $this->data['manual'] == 'r') and (empty($this->data['manual_sal']) and $this->data['manual_sal'] !== '1'))) {
            $where_manual = " AND {$this->pf}unprocessed_base.manual = 'r'";
        } elseif (((empty($this->data['manual']) or $this->data['manual'] == ' ') and (empty($this->data['manual_sal']) and $this->data['manual_sal'] !== '1'))) {
            $where_manual = " AND {$this->pf}unprocessed_base.manual = ''";
        } 

        if((!empty($this->data['manual_sal']) and $this->data['manual_sal'] == '1')) {
            $where_manual_sal = " AND ({$this->pf}unprocessed_base.manual = 'r' OR {$this->pf}unprocessed_base.manual = '')";
        }

        if(!empty($this->data['is_double']) and $this->data['is_double'] == 'n' and empty($this->data['manual']) and empty($this->data['user_access'])) {
            if(in_array($_SESSION['login_role'], [1, 4])) {
                $where_unprocessed = " AND (bez_status.status_id <> 10 OR bez_status.status_id = 10 AND bez_unprocessed_base.user_id = '')";
            }  
        }
        
        $res = $this->db_connect->query("
            SELECT {$this->pf}status.status_id, SUM(IF({$date_filter}{$where_double}{$where_manual}{$where_manual_sal}{$where_operator}{$where_otdel} {$where_filter}{$where_unprocessed} AND {$this->pf}unprocessed_base.source <> 'telegram', 1, 0)) AS count_status, {$this->pf}status.status_name 
            FROM {$this->pf}status
            LEFT JOIN {$this->pf}unprocessed_base
            ON {$this->pf}status.status_id = {$this->pf}unprocessed_base.status
            WHERE {$this->pf}status.status_id IN(10, 11, 15, 6, 8, 9, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 36)
            GROUP BY {$this->pf}status.id
            ORDER BY {$this->pf}status.status_id
        ");

        $result = NULL;
        while($row = $res->fetchAssoc()) {
            $result[] = $row;
        }

        if(!empty($this->data['get_json'])) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($result);
            exit();
        }

        return $result;        
    }

    public function get_quantity_by_hold_statuses() {

        $date_filter = '';

        unset($this->data['filter']['status']);
        $where_filter = $this->get_where_filter('unprocessed_base');
        
        $date_filter = "DATE({$this->pf}unprocessed_base.date_time_status_change) = CURDATE()";

        if(isset($this->data['date_start']) and isset($this->data['date_end'])) {
            $ds = $this->data['date_start'];
            $dend = $this->data['date_end'];
            $date_filter = "DATE({$this->pf}unprocessed_base.date_time_status_change) BETWEEN '{$ds}' AND '{$dend}'";
        }
        
        $res = $this->db_connect->query("
            SELECT {$this->pf}status.status_id, SUM(IF({$date_filter} {$where_filter} AND {$this->pf}unprocessed_base.source <> 'telegram', 1, 0)) AS count_status, {$this->pf}status.status_name 
            FROM {$this->pf}status
            LEFT JOIN {$this->pf}unprocessed_base
            ON {$this->pf}status.status_id = {$this->pf}unprocessed_base.hold_status_id
            WHERE {$this->pf}status.status_id IN(33, 34, 35)
            GROUP BY {$this->pf}status.id
            ORDER BY {$this->pf}status.status_id
        ");

        $result = NULL;
        while($row = $res->fetchAssoc()) {
            $result[] = $row;
        }

        if(!empty($this->data['get_json'])) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($result);
            exit();
        }

        return $result;        
    }

    public function get_today_calls() {

        unset($this->data['filter']['status']);
        $where_filter = $this->get_where_filter('unprocessed_base');
        
        $res = $this->db_connect->query("
            SELECT hold_status_id, SUM(IF(DATE(date_time_hold_calling) = CURRENT_DATE() {$where_filter} AND bez_unprocessed_base.source <> 'telegram', 1, 0)) AS calls_today
            FROM {$this->pf}unprocessed_base
            WHERE hold_status_id = 35
            GROUP BY hold_status_id;
        ");

        $result = NULL;
        $result = $res->fetchAssoc();

        if(!empty($this->data['get_json'])) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($result);
            exit();
        }

        return $result;        
    }

    public function get_filtering_sources() {

        $date_filter = '';
        if(isset($this->data['date_start']) and isset($this->data['date_end'])) {
            $ds = $this->data['date_start'];
            $dend = $this->data['date_end'];
            if(empty($this->data['user_access']) and empty($this->data['holds'])) {
                $date_filter = " AND IF({$this->pf}unprocessed_base.date_time_of_last_save IS NULL, {$this->pf}unprocessed_base.date_create BETWEEN '{$ds}' AND '{$dend}', DATE({$this->pf}unprocessed_base.date_time_of_last_save) BETWEEN '{$ds}' AND '{$dend}')";
            } 
            else if(!empty($this->data['user_access'])) {
                $date_filter = " AND {$this->pf}unprocessed_base.date_create BETWEEN '{$ds}' AND '{$dend}'";
            }
            else {
                $date_filter = " AND DATE({$this->pf}unprocessed_base.date_time_status_change) BETWEEN '{$ds}' AND '{$dend}'";
            }
        }

        $where_otdel = '';
        if($_SESSION['login_role'] == 4 and empty($this->data['user_access']) and empty($this->data['holds'])) {
            $where_otdel = " AND {$this->pf}unprocessed_base.id_otdel = {$_SESSION['id_otdel']}";
        }

        $where_holds = '';
        if(!empty($this->data['holds']) and $this->data['holds'] == 'y') {
            $where_holds = " AND {$this->pf}unprocessed_base.status = 15 AND CURRENT_DATE() > DATE_ADD(DATE({$this->pf}unprocessed_base.date_time_status_change), INTERVAL 31 DAY)";
        }

        $res = $this->db_connect->query("
            SELECT source
                FROM
                    {$this->pf}unprocessed_base
                WHERE {$this->pf}unprocessed_base.is_double IS NULL AND {$this->pf}unprocessed_base.manual = '' {$date_filter} {$where_otdel} {$where_holds} 
                GROUP BY source
            ORDER BY source ASC
        ");

        $result = NULL;
        while($row = $res->fetchAssoc()) {
            $result[] = $row;
        }

        if(!empty($this->data['get_json'])) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($result);
            exit();
        }

        return $result;
    }

    public function get_filtering_cities() {

        $date_filter = '';
        if(isset($this->data['date_start']) and isset($this->data['date_end'])) {
            $ds = $this->data['date_start'];
            $dend = $this->data['date_end'];
            if(empty($this->data['user_access']) and empty($this->data['holds'])) {
                $date_filter = " AND IF({$this->pf}unprocessed_base.date_time_of_last_save IS NULL, {$this->pf}unprocessed_base.date_create BETWEEN '{$ds}' AND '{$dend}', DATE({$this->pf}unprocessed_base.date_time_of_last_save) BETWEEN '{$ds}' AND '{$dend}')";
            } 
            else if(!empty($this->data['user_access'])) {
                $date_filter = " AND {$this->pf}unprocessed_base.date_create BETWEEN '{$ds}' AND '{$dend}'";
            }
            else {
                $date_filter = " AND DATE({$this->pf}unprocessed_base.date_time_status_change) BETWEEN '{$ds}' AND '{$dend}'";
            }
        }

        $where_otdel = '';
        if($_SESSION['login_role'] == 4 and empty($this->data['user_access']) and empty($this->data['holds'])) {
            $where_otdel = " AND {$this->pf}unprocessed_base.id_otdel = {$_SESSION['id_otdel']}";
        }        

        $where_user_id = '';
        if($_SESSION['login_role'] == 5 and empty($this->data['user_access']) and empty($this->data['holds'])) {
            $where_user_id = " AND {$this->pf}unprocessed_base.user_id = {$_SESSION['login_id']}";
        }

        $where_holds = '';
        if(!empty($this->data['holds']) and $this->data['holds'] == 'y') {
            $where_holds = " AND {$this->pf}unprocessed_base.status = 15 AND CURRENT_DATE() > DATE_ADD(DATE({$this->pf}unprocessed_base.date_time_status_change), INTERVAL 31 DAY)";
        }

        $res = $this->db_connect->query("
            SELECT city
                FROM
                    {$this->pf}unprocessed_base
                WHERE {$this->pf}unprocessed_base.is_double IS NULL AND {$this->pf}unprocessed_base.manual = '' {$date_filter} {$where_otdel} {$where_user_id} {$where_holds}
                GROUP BY city
            ORDER BY city ASC
        ");

        $result = NULL;
        while($row = $res->fetchAssoc()) {
            $result[] = $row;
        }

        if(!empty($this->data['get_json'])) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($result);
            exit();
        }

        return $result;
    }

    public function get_filtering_cities_group() {

        $date_filter = '';
        if(isset($this->data['date_start']) and isset($this->data['date_end'])) {
            $ds = $this->data['date_start'];
            $dend = $this->data['date_end'];
            if(empty($this->data['user_access']) and empty($this->data['holds'])) {
                $date_filter = " AND IF({$this->pf}unprocessed_base.date_time_of_last_save IS NULL, {$this->pf}unprocessed_base.date_create BETWEEN '{$ds}' AND '{$dend}', DATE({$this->pf}unprocessed_base.date_time_of_last_save) BETWEEN '{$ds}' AND '{$dend}')";
            } 
            else if(!empty($this->data['user_access'])) {
                $date_filter = " AND {$this->pf}unprocessed_base.date_create BETWEEN '{$ds}' AND '{$dend}'";
            }
            else {
                $date_filter = " AND DATE({$this->pf}unprocessed_base.date_time_status_change) BETWEEN '{$ds}' AND '{$dend}'";
            }
        }

        $where_otdel = '';
        if($_SESSION['login_role'] == 4 and empty($this->data['user_access']) and empty($this->data['holds'])) {
            $where_otdel = " AND {$this->pf}unprocessed_base.id_otdel = {$_SESSION['id_otdel']}";
        }

        $where_user_id = '';
        if($_SESSION['login_role'] == 5 and empty($this->data['user_access']) and empty($this->data['holds'])) {
            $where_user_id = " AND {$this->pf}unprocessed_base.user_id = {$_SESSION['login_id']}";
        }

        $where_holds = '';
        if(!empty($this->data['holds']) and $this->data['holds'] == 'y') {
            $where_holds = " AND {$this->pf}unprocessed_base.status = 15 AND CURRENT_DATE() > DATE_ADD(DATE({$this->pf}unprocessed_base.date_time_status_change), INTERVAL 31 DAY)";
        }

        $res = $this->db_connect->query("
            SELECT DISTINCT {$this->pf}cities_group.id, {$this->pf}cities_group.name
            FROM {$this->pf}unprocessed_base JOIN {$this->pf}cities_group
            ON {$this->pf}unprocessed_base.auto_city_group = {$this->pf}cities_group.id
            WHERE {$this->pf}unprocessed_base.is_double IS NULL AND {$this->pf}unprocessed_base.manual = '' {$date_filter} {$where_otdel} {$where_user_id} {$where_holds}
            ORDER BY name ASC;    
        ");

        $result = NULL;
        while($row = $res->fetchAssoc()) {
            $result[] = $row;
        }

        if(!empty($this->data['get_json'])) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($result);
            exit();
        }

        return $result;
    }

    public function get_filtering_operators() {

        $date_filter = '';
        if(isset($this->data['date_start']) and isset($this->data['date_end'])) {
            $ds = $this->data['date_start'];
            $dend = $this->data['date_end'];
            if(empty($this->data['user_access'])) {
                $date_filter = " AND IF({$this->pf}unprocessed_base.date_time_of_last_save IS NULL, {$this->pf}unprocessed_base.date_create BETWEEN '{$ds}' AND '{$dend}', DATE({$this->pf}unprocessed_base.date_time_of_last_save) BETWEEN '{$ds}' AND '{$dend}')";
            } 
            else {
                $date_filter = " AND {$this->pf}unprocessed_base.date_create BETWEEN '{$ds}' AND '{$dend}'";
            }
        }

        $where_otdel = '';
        if($_SESSION['login_role'] == 4 and empty($this->data['user_access'])) {
            $where_otdel = " AND {$this->pf}unprocessed_base.id_otdel = {$_SESSION['id_otdel']}";
        }

        $res = $this->db_connect->query("
            SELECT DISTINCT {$this->pf}reg.id, {$this->pf}reg.name
            FROM {$this->pf}unprocessed_base JOIN {$this->pf}reg
            ON {$this->pf}unprocessed_base.user_id = {$this->pf}reg.id
            WHERE {$this->pf}unprocessed_base.is_double IS NULL AND {$this->pf}unprocessed_base.manual = '' {$date_filter} {$where_otdel}  
            ORDER BY {$this->pf}reg.name ASC;    
        ");

        $result = NULL;
        while($row = $res->fetchAssoc()) {
            $result[] = $row;
        }

        if(!empty($this->data['get_json'])) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($result);
            exit();
        }

        return $result;
    }

    public function get_filtering_departments() {

        $date_filter = '';
        if(isset($this->data['date_start']) and isset($this->data['date_end'])) {
            $ds = $this->data['date_start'];
            $dend = $this->data['date_end'];
            if(empty($this->data['user_access'])) {
                $date_filter = " AND IF({$this->pf}unprocessed_base.date_time_of_last_save IS NULL, {$this->pf}unprocessed_base.date_create BETWEEN '{$ds}' AND '{$dend}', DATE({$this->pf}unprocessed_base.date_time_of_last_save) BETWEEN '{$ds}' AND '{$dend}')";
            } 
            else {
                $date_filter = " AND {$this->pf}unprocessed_base.date_create BETWEEN '{$ds}' AND '{$dend}'";
            }
        }

        $res = $this->db_connect->query("
            SELECT DISTINCT {$this->pf}department.department_id
            FROM {$this->pf}unprocessed_base JOIN {$this->pf}department
            ON {$this->pf}unprocessed_base.id_otdel = {$this->pf}department.department_id
            WHERE {$this->pf}unprocessed_base.is_double IS NULL AND {$this->pf}unprocessed_base.manual = '' {$date_filter}
            ORDER BY {$this->pf}department.department_id ASC;   
        ");

        $result = NULL;
        while($row = $res->fetchAssoc()) {
            $result[] = $row;
        }

        if(!empty($this->data['get_json'])) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($result);
            exit();
        }

        return $result;
    }

    public function call_phone() {
        $data = $this->data;

        if($_SESSION['login_role'] == 5) {
            $query_request = "
                INSERT INTO lg_user_atc (phone_number, status_id, user_id, row_change_time) VALUES ('{$data['phone_number']}', '{$data['status_id']}', '{$_SESSION['login_id']}', NOW());
            ";

            $this->db_connect->query($query_request);
        }

        $cleaned_phone_number = '8' . substr(preg_replace('/[^0-9]/', '', $data['phone_number']), -10);

        $ch = curl_init();

        $queryURL = 'http://83.222.25.208/crm/apitel/service/crmcall.php?exten=' . $_SESSION['id_atc'] . '&number=' . $cleaned_phone_number;

        curl_setopt($ch, CURLOPT_URL, $queryURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        http_response_code($http_code);          
    }

    public function logging($value, $modul, $phone_number = '', $status = '', $response = '') {
        $data = $this->data;
        $add_status = '';
        $add_phone_number = '';
        $add_response = '';
        if($status != '') {
            $add_status = "; status = {$status}"; 
        }
        if($phone_number != '') {
            $add_phone_number = "phone_number = {$phone_number}";
        }
        if($response != '') {
            $add_response = "response = {$response}"; 
        }

        $query_request = "
                INSERT INTO logs (row_change_time, value, modul, text, user_id) VALUES (NOW(), '{$value}', '{$modul}', '{$add_phone_number}{$add_status}{$add_response}', '{$_SESSION['id_atc']}');
            ";

        $this->db_connect->query($query_request);

        http_response_code(200);
    }

    public function start_lead_filling() {

        $data = $this->data;

        $is_completed = 1;
        if($_SESSION['id_atc'] > 100 and $_SESSION['id_atc'] < 200) {
            $is_completed = 0;
        }

        if($_SESSION['login_role'] == 5) {
            $query_request = "
                INSERT INTO lead_filling_stats (user_id, request_id, start_datetime, is_completed) VALUES ('{$_SESSION['login_id']}', '{$data['id']}', NOW(), '{$is_completed}');
            ";

            $this->db_connect->query($query_request);
        }

        http_response_code(200);

    }

    public function delete_lead_filling() {

        $data = $this->data;

        if($_SESSION['login_role'] == 5) {
            $query_request = "
                DELETE FROM lead_filling_stats 
                WHERE user_id = '{$_SESSION['login_id']}' AND request_id = '{$data['id']}' AND end_datetime IS NULL;
            ";

            $this->db_connect->query($query_request);
        }

        http_response_code(200);

    }
    
}

if(!empty($_POST['action'])) {
    $action = $_POST['action'];
    unset($_POST['action']);
    $obj = new UnprocessedBase();
    $obj->handle_action($action);
}