<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

class UnprocessedBaseExcel
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
            case 'get_unprocessed_base_excel':
                $this->get_unprocessed_base_excel();
                break;
            case 'get_partners':
                return $this->get_partners();
            case 'get_sources':
                return $this->get_sources();
            case 'get_cities_group':
                return $this->get_cities_group();
            case 'get_operators':
                return $this->get_operators();
            case 'update_request':
                $this->update_request($this->data);
                break;
            case 'get_statuses':
                return $this->get_statuses();
            case 'get_counters':
                return $this->get_counters();
            case 'get_rate':
                return $this->get_rate();
            case 'get_departments':
                return $this->get_departments();                
            case 'get_filtering_sources':
                return $this->get_filtering_sources();
            case 'get_cities_group_sources':
                return $this->get_cities_group_sources();
        }
    }

    public function get_rate() {
        $data = $this->data;

        $queryset = $this->db_connect->query("
            SELECT bub.user_id,
                {$this->pf}reg.id as operator_id,
                {$this->pf}reg.id_otdel,
                {$this->pf}reg.name,
                COUNT(bub.id) as count_request
            FROM {$this->pf}unprocessed_base_excel bub
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
                WHERE {$this->pf}status.status_id IN(10, 11, 15, 16, 6, 8, 9, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30)
                ORDER BY {$this->pf}status.status_name ASC
        ");

        $statuses = NULL;
        while($row = $res->fetchAssoc()) {
            $statuses[] = $row;
        }

        return $statuses;
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
                {$this->pf}reg.name
            FROM
                {$this->pf}reg
            WHERE
                {$this->pf}reg.role = 5
        ");

        $operators = NULL;
        while($row = $res->fetchAssoc()) {
            $operators[] = $row;
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

    public function get_sources() {
        $res = $this->db_connect->query("
            SELECT source
                FROM
                    {$this->pf}unprocessed_base_excel
                GROUP BY source
            ORDER BY source ASC
        ");

        $result = NULL;
        while($row = $res->fetchAssoc()) {
            $result[] = $row;
        }

        return $result;
    }

    public function get_filtering_sources() {

        $date_filter = '';
        if(isset($this->data['date_start']) and isset($this->data['date_end'])) {
            $ds = $this->data['date_start'];
            $dend = $this->data['date_end'];
            $date_filter = " WHERE {$this->pf}unprocessed_base_excel.date_create BETWEEN '{$ds}' AND '{$dend}'";
        }

        $res = $this->db_connect->query("
            SELECT source
                FROM
                    {$this->pf}unprocessed_base_excel
                {$date_filter}    
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

    public function get_cities_group_sources() {

        $date_filter = '';
        if(isset($this->data['date_start']) and isset($this->data['date_end'])) {
            $ds = $this->data['date_start'];
            $dend = $this->data['date_end'];
            $date_filter = " WHERE {$this->pf}unprocessed_base_excel.date_create BETWEEN '{$ds}' AND '{$dend}'";
        }

        $res = $this->db_connect->query("
            SELECT {$this->pf}unprocessed_base_excel.auto_city_group AS city_group, {$this->pf}cities_group.name
            FROM {$this->pf}unprocessed_base_excel
            JOIN {$this->pf}cities_group 
            ON {$this->pf}unprocessed_base_excel.auto_city_group = {$this->pf}cities_group.id
            {$date_filter}
            GROUP BY {$this->pf}unprocessed_base_excel.auto_city_group
            ORDER BY {$this->pf}unprocessed_base_excel.auto_city_group ASC;
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

    public function update_request($data) {

        $new_data = '';
		/*$manual_r = '1';*/

        $res = $this->db_connect->query("
            SELECT {$this->pf}unprocessed_base_excel.date_time_status_change, {$this->pf}unprocessed_base_excel.status, IF({$this->pf}unprocessed_base_excel.date_time_status_change IS NULL OR DATE_FORMAT({$this->pf}unprocessed_base_excel.date_time_status_change, '%Y-%m-%d') != CURRENT_DATE() OR {$this->pf}unprocessed_base_excel.status != 15, true, false) as is_allow_editing,
                IF({$this->pf}unprocessed_base_excel.source = 'telegram', true, false) as is_source_telegram 
            FROM {$this->pf}unprocessed_base_excel 
            WHERE {$this->pf}unprocessed_base_excel.id = {$data['id']};  
        ");

        $result = $res->fetchAssoc();

        if($result['is_source_telegram'] and $_SESSION['login_role'] == 5) {
            $new_data = ['warning' => 'Редактирование заявок Телеграм запрещено!'];
        }
        elseif($result['is_allow_editing'] or $_SESSION['login_role'] != 5) {
			
            $query_params = '';
            $user_id = null;
            $otdel_id = null;
            $last_sale = null;
            $date_time_status_change = null;
            $new_status = null;

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
                    SELECT bez_reg.id_otdel FROM bez_reg WHERE id = {$user_id};  
                ");
                    
                $result2 = $res2->fetchAssoc();
                $otdel_id = $result2['id_otdel'];
                $query_params .= "user_id = {$user_id}";
                $query_params .= ', id_otdel = ' . $result2['id_otdel'];
            }

            $query_request = "
                UPDATE {$this->pf}unprocessed_base_excel SET {$query_params} WHERE id = {$data['id']}
            ";

            $this->db_connect->query($query_request);
            $this->data['filter'] = array(
                'id' => $data['id']
            ); 
            $response = $this->get_unprocessed_base_excel();
            if($otdel_id !== null) {
                $new_data = ['otdel_id' => $otdel_id];
            }
            else {
               $new_data = $response[0]; 
            }      
			//$new_data = $response;
        } else {
            $new_data = ['warning' => 'Редактирование и продажа запрещены, так как данный лид сегодня уже был продан!'];
        }
		    
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($new_data);  

    }

    private function get_counters() {  # Вернет счетчики
	
		$where_manual = '';
		$where_manual_sal = '';
		
        $where_double = " AND ({$this->pf}unprocessed_base_excel.is_double IS NULL or {$this->pf}unprocessed_base_excel.is_double = '1' )";
        $operator_where = " AND {$this->pf}unprocessed_base_excel.user_id = {$_SESSION['login_id']}";
        if(in_array($_SESSION['login_role'], [1, 4])) {
            $operator_where = '';
        }

        $date_filter = "(({$this->pf}unprocessed_base_excel.status = 6 AND DATE({$this->pf}unprocessed_base_excel.date_time_status_change) = CURDATE()) OR ({$this->pf}unprocessed_base_excel.status = 15 AND {$this->pf}sale_request.request_id IS NOT NULL AND {$this->pf}sale_request.partner_id <> 65 AND DATE({$this->pf}sale_request.date_time) = CURDATE()) OR ({$this->pf}unprocessed_base_excel.status = 10 AND {$this->pf}unprocessed_base_excel.source = 'telegram' AND DATE({$this->pf}unprocessed_base_excel.date_create) = CURDATE() AND {$this->pf}unprocessed_base_excel.partner <> 65))";
        if(isset($this->data['date_start']) and isset($this->data['date_end'])) {
            $ds = $this->data['date_start'];
            $dend = $this->data['date_end'];
            #$date_filter = " AND {$this->pf}unprocessed_base_excel.date_time_status_change BETWEEN '{$ds}' AND '{$dend}'";
            $date_filter = "(({$this->pf}unprocessed_base_excel.status = 6 AND {$this->pf}unprocessed_base_excel.date_time_status_change BETWEEN '{$ds}' AND '{$dend}') OR ({$this->pf}unprocessed_base_excel.status = 15 AND {$this->pf}sale_request.request_id IS NOT NULL AND {$this->pf}sale_request.partner_id <> 65 AND {$this->pf}sale_request.date_time BETWEEN '{$ds}' AND '{$dend}') OR ({$this->pf}unprocessed_base_excel.status = 10 AND {$this->pf}unprocessed_base_excel.source = 'telegram' AND {$this->pf}unprocessed_base_excel.date_create BETWEEN '{$ds}' AND '{$dend}' AND {$this->pf}unprocessed_base_excel.partner <> 65))";
        }


        if(!empty($this->data['is_double']) and $this->data['is_double'] == 'y') {
            $where_double = " AND {$this->pf}unprocessed_base_excel.is_double = 'y'";
        } 

        if(!empty($this->data['is_double']) and $this->data['is_double'] == 'n') {
            $where_double = " AND {$this->pf}unprocessed_base_excel.is_double IS NULL";
        } 

		if (!isset($this->data['manual_sal'])) {
			$this->data['manual_sal'] = '';
		}

        if(((!empty($this->data['manual']) and $this->data['manual'] == 'r') and (empty($this->data['manual_sal']) and $this->data['manual_sal'] !== '1'))) {
            $where_manual = " AND {$this->pf}unprocessed_base_excel.manual = 'r'";
        } elseif (((empty($this->data['manual']) or $this->data['manual'] == ' ') and (empty($this->data['manual_sal']) and $this->data['manual_sal'] !== '1'))) {
            $where_manual = " AND {$this->pf}unprocessed_base_excel.manual = ''";
        } 

        if((!empty($this->data['manual_sal']) and $this->data['manual_sal'] == '1')) {
            $where_manual_sal = " AND ({$this->pf}unprocessed_base_excel.manual = 'r' OR {$this->pf}unprocessed_base_excel.manual = '')";
        }

        $res = $this->db_connect->query("
            SELECT
                COUNT(DISTINCT(CASE WHEN bez_unprocessed_base_excel.status = 6 THEN bez_unprocessed_base_excel.id END)) AS callings,
                COUNT(DISTINCT(CASE WHEN bez_unprocessed_base_excel.status = 15 OR bez_unprocessed_base_excel.status = 10 THEN bez_unprocessed_base_excel.id END)) AS leads
            FROM
                {$this->pf}unprocessed_base_excel
            LEFT JOIN {$this->pf}sale_request
            ON {$this->pf}unprocessed_base_excel.id = {$this->pf}sale_request.request_id AND {$this->pf}sale_request.partner_id = {$this->pf}unprocessed_base_excel.partner
            WHERE
                {$date_filter}
                {$operator_where}
                {$where_double}
				{$where_manual}
				{$where_manual_sal}			
                AND {$this->pf}unprocessed_base_excel.source IS NOT NULL
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

    public function get_unprocessed_base_excel() {
        $where_filter = $this->get_where_filter('unprocessed_base_excel');

        $limit_start = isset($this->data['limit_start']) ? $this->data['limit_start'] : 0;
        $limit_end = isset($this->data['limit_end']) ? $this->data['limit_end'] : 100;
        $order_by = isset($this->data['order_by']) ? $this->data['order_by'] : 'id DESC';
        $order_by = $this->pf . 'unprocessed_base_excel.' . $order_by;

        $varrible_date = 'unprocessed_base_excel.date_create';
        $select_partner_id = "";
        $add_requests_telegram = "";
        $select_operator_id = "{$this->pf}reg.id as operator_id,";
        $select_new_lead = "";
        $where_sale_dates = "";
        $where_double = "";
        $where_manual = "";
        $where_manual_sal = "";
        $csv_filter = "";


        $query_request = "
            UPDATE {$this->pf}unprocessed_base_excel
            SET date_time_status_change = (SELECT date_time FROM {$this->pf}sale_request WHERE request_id = {$this->pf}unprocessed_base_excel.id ORDER BY date_time DESC LIMIT 1),
                status = past_status,
                user_id = IFNULL((SELECT user_id FROM {$this->pf}sale_request WHERE request_id = {$this->pf}unprocessed_base_excel.id ORDER BY date_time DESC LIMIT 1), user_id)
            WHERE status = 6 AND date_time_status_change IS NOT NULL AND date_time_status_change + interval 30 minute < now() + interval {$this->tz} hour AND past_status IS NOT NULL;
        ";

        $this->db_connect->query($query_request);        

        if(!empty($this->data['filter']['status']) and in_array($this->data['filter']['status'], [6, 15])) {
            $varrible_date = 'unprocessed_base_excel.date_time_status_change'; 
            if((int) $this->data['filter']['status'] === 15 and !empty($this->data['view_name']) and $this->data['view_name'] == 'unprocessed') {
               $varrible_date = 'sale_request.date_time';
            }            
            if(!in_array($_SESSION['login_role'], [1, 4]) and isset($this->data['defect_bg']) and $this->data['defect_bg'] === 'yes') {
                $where_filter .= " AND {$this->pf}unprocessed_base_excel.user_id = {$_SESSION['login_id']}";
                $add_requests_telegram = " OR (bez_unprocessed_base_excel.status = '10' AND bez_unprocessed_base_excel.source = 'telegram' AND bez_unprocessed_base_excel.partner <> 65 AND bez_unprocessed_base_excel.date_create BETWEEN '{$this->data['date_start']}' AND '{$this->data['date_end']}' AND bez_unprocessed_base_excel.user_id = {$_SESSION['login_id']})";
            }
            if(in_array($_SESSION['login_role'], [5]) and isset($this->data['defect_bg']) and $this->data['defect_bg'] === 'yes') {
                $select_partner_id = "{$this->pf}unprocessed_base_excel.partner AS partner_id,";
            }
        }

        if(isset($this->data['date_time_status_change']) and isset($this->data['comparison_operator'])) {
            $ds = $this->data['date_time_status_change'] . ' 00:00:00';
            $operator = $this->data['comparison_operator'];
            $where_filter .= " AND {$this->pf}unprocessed_base_excel.date_time_status_change {$operator} '{$ds}'";
        }

        if(isset($this->data['date_start']) and isset($this->data['date_end'])) {
            $ds = $this->data['date_start'] . ' 00:00:00';
            $dend = $this->data['date_end'] . ' 23:59:59';
            if(!empty($this->data['view_name']) and $this->data['view_name'] == 'sales') {
                $where_filter .= " AND (({$this->pf}unprocessed_base_excel.date_time_status_change BETWEEN '{$ds}' AND '{$dend}') OR ({$this->pf}sale_request.date_time BETWEEN '{$ds}' AND '{$dend}'))";
                $order_by .= ", {$this->pf}sale_request.date_time DESC";
            } 
            else {
                $where_filter .= " AND {$this->pf}{$varrible_date} BETWEEN '{$ds}' AND '{$dend}'";
            }    
        }

        if(
            $_SESSION['login_role'] == 4 and
            (!empty($this->data['view_name']) and $this->data['view_name'] == 'sales')
        ) {
            $where_filter .= " AND {$this->pf}unprocessed_base_excel.id_otdel = {$_SESSION['id_otdel']}";
        }

        if(!in_array($_SESSION['login_role'], [1, 4])) {
            $where_filter .= "
                AND (
                    {$this->pf}unprocessed_base_excel.status <> 6
                OR (
                    {$this->pf}unprocessed_base_excel.status = 6
                    AND {$this->pf}unprocessed_base_excel.user_id = {$_SESSION['login_id']})
                )
            ";
        }
		
		if (isset($this->data['filter2'])) {
			if(in_array($_SESSION['login_role'], [1]) and $this->data['filter2']['id_start'] !== '' and $this->data['filter2']['id_end'] !== '' ) {
				$id_start = $this->data['filter2']['id_start'];
				$id_end = $this->data['filter2']['id_end'];
				$id_status = $this->data['filter2']['id_status'];
				
				if( isset($this->data['filter2']['id_status']) and $this->data['filter2']['id_status'] !== '' and $this->data['filter2']['id_status'] !== 'Статус' ) {
					$csv_filter .= "
						AND {$this->pf}unprocessed_base_excel.id between {$id_start} and {$id_end}
						AND {$this->pf}unprocessed_base_excel.status = {$id_status}
					";				
				} else {
					$csv_filter .= "
						AND {$this->pf}unprocessed_base_excel.id between {$id_start} and {$id_end}
					";
				}
			}
		}

        $where_filter .= "
            AND ({$this->pf}unprocessed_base_excel.source IS NOT NULL AND {$this->pf}unprocessed_base_excel.source <> 'telegram')
        ";

        if(!empty($this->data['view_name']) and $this->data['view_name'] == 'sales') {
            $select_operator_id = "IF({$this->pf}sale_request.request_id IS NOT NULL AND {$this->pf}sale_request.date_time BETWEEN '{$ds}' AND '{$dend}', {$this->pf}sale_request.user_id, {$this->pf}unprocessed_base_excel.user_id) AS operator_id,";
            $select_new_lead = " AND DATE('{$ds}') = CURRENT_DATE() AND DATE('{$dend}') = CURRENT_DATE()";
            $where_sale_dates = " AND DATE({$this->pf}sale_request.date_time) BETWEEN DATE('{$ds}') AND DATE('{$dend}')";
        }

        if(!empty($this->data['is_double']) and $this->data['is_double'] == 'y') {
            $where_double = " AND {$this->pf}unprocessed_base_excel.is_double = 'y'";
        } 

        if(!empty($this->data['is_double']) and $this->data['is_double'] == 'n') {
            $where_double = " AND {$this->pf}unprocessed_base_excel.is_double IS NULL";
        } 

		if (!isset($this->data['manual_sal'])) {
			$this->data['manual_sal'] = '';
		}

        if(((!empty($this->data['manual']) and $this->data['manual'] == 'r') and (empty($this->data['manual_sal']) and $this->data['manual_sal'] !== '1'))) {
            $where_manual = " AND {$this->pf}unprocessed_base_excel.manual = 'r'";
        } elseif (((empty($this->data['manual']) or $this->data['manual'] == ' ') and (empty($this->data['manual_sal']) and $this->data['manual_sal'] !== '1'))) {
            $where_manual = " AND {$this->pf}unprocessed_base_excel.manual = ''";
        } 

        if((!empty($this->data['manual_sal']) and $this->data['manual_sal'] == '1')) {
            $where_manual_sal = " AND ({$this->pf}unprocessed_base_excel.manual = 'r' OR {$this->pf}unprocessed_base_excel.manual = '')";
        }


        $res = $this->db_connect->query("
            SELECT
                {$this->pf}unprocessed_base_excel.id,
                {$this->pf}unprocessed_base_excel.fio,
                CONCAT('7', {$this->pf}unprocessed_base_excel.phone_number) AS phone_number,
                {$this->pf}unprocessed_base_excel.vopros,
                {$this->pf}unprocessed_base_excel.city,
                {$this->pf}cities_group.name as city_group_name,
                {$this->pf}unprocessed_base_excel.status,
                CONCAT_WS(', ', p1.partner1, p2.partner2) AS partner,
				{$this->pf}unprocessed_base_excel.is_double,
                IF({$this->pf}unprocessed_base_excel.date_time_status_change IS NOT NULL and {$this->pf}unprocessed_base_excel.status = 15, DATE_ADD({$this->pf}unprocessed_base_excel.date_time_status_change, INTERVAL {$this->tz} HOUR), {$this->pf}unprocessed_base_excel.date_time_status_change) AS date_time_status_change,
                {$this->pf}unprocessed_base_excel.date_create,
                {$this->pf}unprocessed_base_excel.user_id,
                {$select_operator_id}
                {$select_partner_id}
                {$this->pf}unprocessed_base_excel.timez,
                {$this->pf}unprocessed_base_excel.source,
                {$this->pf}reg.name as operator_name,
                {$this->pf}status.status_name,
                IF({$this->pf}sale_request.request_id IS NOT NULL OR {$this->pf}unprocessed_base_excel.status = 15, 1, 0) AS is_lead,
                IF({$this->pf}unprocessed_base_excel.date_time_of_last_save IS NOT NULL AND DATE_FORMAT({$this->pf}unprocessed_base_excel.date_time_of_last_save, '%Y-%m-%d') = CURRENT_DATE(), 1, 0) AS is_save_today,
                CONCAT(
                        DATE_FORMAT({$this->pf}group_request.date, '%d.%m.%Y'), ' ', {$this->pf}group_request.name,
                        ' ', {$this->pf}group_request.count, ' шт.'
                ) AS group_name,
                DATE_FORMAT({$this->pf}group_request.date, '%d.%m.%Y') as group_date,
                {$this->pf}unprocessed_base_excel.group_source as group_source_id,
                CASE
                    WHEN {$this->pf}sale_request.request_id IS NULL THEN TRUE
                    WHEN {$this->pf}unprocessed_base_excel.date_time_status_change > {$this->pf}sale_request.date_time {$select_new_lead}
                    THEN TRUE
                    ELSE FALSE
                END as new_lead
            FROM
                {$this->pf}unprocessed_base_excel

            LEFT JOIN
                {$this->pf}status ON {$this->pf}status.status_id = {$this->pf}unprocessed_base_excel.status
            LEFT JOIN
                {$this->pf}reg ON {$this->pf}reg.id = {$this->pf}unprocessed_base_excel.user_id
            LEFT JOIN
                {$this->pf}sale_request ON {$this->pf}sale_request.request_id = {$this->pf}unprocessed_base_excel.id {$where_sale_dates}
            LEFT JOIN
                {$this->pf}group_request ON {$this->pf}group_request.id = {$this->pf}unprocessed_base_excel.group_source
            LEFT JOIN 
                (SELECT phone, GROUP_CONCAT(DISTINCT partners_request SEPARATOR ', ') AS partner1 FROM (SELECT sr.request_id, ub.phone_number AS phone, GROUP_CONCAT(DISTINCT sr.partner_id SEPARATOR ', ') AS partners_request FROM bez_sale_request sr JOIN bez_unprocessed_base_excel ub ON ub.id = sr.request_id GROUP BY sr.request_id) AS table_partners_request GROUP BY phone) p1 ON {$this->pf}unprocessed_base_excel.phone_number = p1.phone
            LEFT JOIN 
                (SELECT ub.phone_number AS phone, GROUP_CONCAT(DISTINCT ub.partner SEPARATOR ', ') AS partner2 FROM {$this->pf}unprocessed_base_excel ub WHERE ub.source = 'telegram' or ub.source is null GROUP BY phone) p2 
            ON {$this->pf}unprocessed_base_excel.phone_number = p2.phone
			LEFT JOIN {$this->pf}cities_group ON {$this->pf}cities_group.id = {$this->pf}unprocessed_base_excel.auto_city_group
            WHERE
                (({$this->pf}unprocessed_base_excel.source IS NOT NULL {$where_filter}) {$where_double}) 
                $add_requests_telegram
                $csv_filter
				$where_manual
				$where_manual_sal
            GROUP BY {$this->pf}unprocessed_base_excel.id
            ORDER BY
                {$order_by}
            LIMIT
                {$limit_start}, {$limit_end}
        ");

        $unprocessed_base_excel_requests = NULL;
        while($row = $res->fetchAssoc()) {
            $unprocessed_base_excel_requests[] = $row;
        }

        if($unprocessed_base_excel_requests != NULL) {
			
			$delimiter = ";"; 
			$buffer = fopen(__DIR__ . '/uploads/data_'.$_SESSION['login_id'].'.csv', 'w'); 
			// Добавляет в начало файла метку BOM, благодаря этому файл откроется в Excel с нормальной кодировкой.
			fputs($buffer, chr(0xEF) . chr(0xBB) . chr(0xBF));
			// Данные в файл csv
			foreach($unprocessed_base_excel_requests as $row){ 
				$lineData = array(trim($row['phone_number']), trim($row['fio']), trim($row['city']), trim($row['vopros'])); 
				fputcsv($buffer, $lineData, $delimiter); 
			} 
			fclose($buffer); 			
			
			
            if(!empty($this->data['view_name'])) {
                $path = $_SERVER['DOCUMENT_ROOT'] . '/scripts/unprocessed_base_excel/view/' . $this->data['view_name'] . '.php';
                include ($path);
            } else {
                return $unprocessed_base_excel_requests;
            }
        }
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

}

if(!empty($_POST['action'])) {
    $action = $_POST['action'];
    unset($_POST['action']);
    $obj = new UnprocessedBaseExcel();
    $obj->handle_action($action);
}