<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

class PartnersPlan
{
    private $db_connect;
    private $data;
    private $response;
    private $pf;

    function __construct() {
        global $db_connect;
        $this->db_connect = $db_connect;
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
            case 'get_partners_plan':
                $this->get_partners_plan();
                break;
            case 'get_details':
                $this->get_details();
                break; 
            case 'get_date_requests':
                $this->get_date_requests();
                break;         
            case 'get_partners':
                return $this->get_partners();  
            case 'update_request':
                $this->update_request($this->data);
                break;  
            case 'delete_plan':
                $this->delete_plan(); 
                break;    
        }
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

    public function get_details() {
        $where_dates = '';
        if($this->data['name'] === 'shipped1_details') {
            $where_dates = ' AND bez_partners_plan_dates.stage = 1 AND bez_partners_plan.date_start IS NOT NULL AND bez_partners_plan_dates.date BETWEEN bez_partners_plan.date_start AND IF(date_end1 IS NOT NULL, DATE(date_end1), CURDATE())';
        }
        if($this->data['name'] === 'shipped2_details') {
            $where_dates = ' AND bez_partners_plan_dates.stage = 2 AND bez_partners_plan.date_end1 IS NOT NULL AND bez_partners_plan_dates.date BETWEEN DATE(bez_partners_plan.date_end1) AND IF(date_end2 IS NOT NULL, DATE(date_end2), CURDATE())';            
        }

        $res = $this->db_connect->query("
            SELECT bez_partners_plan_dates.date AS date, count(bez_partners_plan_dates.request_id) AS count
            FROM bez_partners_plan_dates 
            JOIN bez_partners_plan ON bez_partners_plan.id = bez_partners_plan_dates.partner_plan_id
            WHERE partner_plan_id = {$this->data['id']} {$where_dates}
            GROUP BY bez_partners_plan_dates.date
            ORDER BY bez_partners_plan_dates.date
        ");

        $dates = NULL;
        while($row = $res->fetchAssoc()) {
            $dates[] = $row;
        }

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($dates);  
    }

    public function get_date_requests() {
        $where_dates = '';
        if($this->data['name'] === 'shipped1_details') {
            $where_dates = ' AND bez_partners_plan_dates.stage = 1';
        }
        if($this->data['name'] === 'shipped2_details') {
            $where_dates = ' AND bez_partners_plan_dates.stage = 2';            
        }

        $res = $this->db_connect->query("
            SELECT bez_unprocessed_base.id,
                    bez_unprocessed_base.fio,
                    CONCAT('7', bez_unprocessed_base.phone_number) AS phone_number,
                    bez_unprocessed_base.city,
                    bez_unprocessed_base.vopros
            FROM bez_partners_plan_dates JOIN bez_unprocessed_base 
            ON bez_partners_plan_dates.request_id = bez_unprocessed_base.id
            WHERE bez_partners_plan_dates.date = '{$this->data['date']}' AND bez_partners_plan_dates.partner_plan_id = {$this->data['id']} {$where_dates}
            ORDER BY bez_partners_plan_dates.request_id;
        ");

        $dates = NULL;
        while($row = $res->fetchAssoc()) {
            $dates[] = $row;
        }

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($dates);  
    }

    private function get_where_filter() {
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
                    $where_conditions[] = "{$key} {$comparison_operator} {$value}";
                }
            }
            return 'AND ' . implode(" {$logical_operator} ", $where_conditions);
        } else
            return '';
    }

    public function get_partners_plan() {

        $where_filter =  $this->get_where_filter();
        $order_by = isset($this->data['order_by']) ? $this->data['order_by'] : 'id DESC';

        $res = $this->db_connect->query("
            SELECT bez_partners_plan.*, st_partner_s.partner_name,
            IFNULL(COUNT(CASE WHEN stage = 1 AND date_start IS NOT NULL AND date BETWEEN date_start AND IF(date_end1 IS NOT NULL, DATE(date_end1), CURDATE()) THEN bez_partners_plan_dates.request_id END), 0) AS shipped1,
            IFNULL(COUNT(CASE WHEN stage = 2 AND date_end1 IS NOT NULL AND date BETWEEN DATE(date_end1) AND IF(date_end2 IS NOT NULL, DATE(date_end2), CURDATE()) THEN bez_partners_plan_dates.request_id END), 0) AS shipped2,
            IFNULL(total_quantity, 0) - IFNULL(COUNT(CASE WHEN stage = 1 AND date_start IS NOT NULL AND date BETWEEN date_start AND IF(date_end1 IS NOT NULL, DATE(date_end1), CURDATE()) THEN bez_partners_plan_dates.request_id END), 0) AS remainder1,
            IFNULL(otbrakovka, 0) - IFNULL(COUNT(CASE WHEN stage = 2 AND date_end1 IS NOT NULL AND date BETWEEN DATE(date_end1) AND IF(date_end2 IS NOT NULL, DATE(date_end2), CURDATE()) THEN bez_partners_plan_dates.request_id END), 0) AS remainder2
            FROM bez_partners_plan
            JOIN st_partner_s ON st_partner_s.id = bez_partners_plan.partner_id
            LEFT JOIN bez_partners_plan_dates ON bez_partners_plan_dates.partner_plan_id = bez_partners_plan.id
            WHERE 1 = 1 {$where_filter}
            GROUP BY bez_partners_plan.id
            ORDER BY {$order_by};   
        ");

        $results = NULL;
        while($row = $res->fetchAssoc()) {
            $results[] = $row;
        }

        if($results != NULL) {
            if(!empty($this->data['view_name'])) {
                $path = $_SERVER['DOCUMENT_ROOT'] . '/scripts/partners_plan/view/' . $this->data['view_name'] . '.php';
                include ($path);
            } else {
                return $results;
            }
        }
    }

    public function update_request($data) {

        $new_data = '';
        $query_params = '';
        $date_start = null;
        $partner_id = null;

        foreach($data['params'] as $item) {
            if(trim($item['name']) !== 'id') {
                if(in_array(trim($item['name']), ['partner_id', 'total_quantity', 'otbrakovka', 'date_start'])) {
                    if(trim($item['name']) !== 'date_start' and trim($item['value']) !== '') {
                        $query_params .= trim($item['name']) . " = " . trim($item['value']) . ", ";
                        if(trim($item['name']) === 'partner_id') {
                             $partner_id = trim($item['value']);
                        }  
                    }
                    else {
                        if(trim($item['name']) === 'date_start' and trim($item['value']) !== '') {
                            $query_params .= trim($item['name']) . " = '" . trim($item['value']) . "', ";     
                            $date_start = trim($item['value']);
                        }
                    }
                }
                else {
                    $query_params .= trim($item['name']) . " = '" . trim($item['value']) . "', "; 
                }
            }             
        }        

        $query_params = mb_substr($query_params, 0, -2);

        $query_request = "
            UPDATE {$this->pf}partners_plan SET {$query_params} WHERE id = {$data['id']}
        ";

        $this->db_connect->query($query_request);

        if($partner_id !== null and $date_start !== null) {
            $results_partners_plan = null;
            $res_partners_plan = $this->db_connect->query(" 
                SELECT *
                FROM bez_partners_plan 
                WHERE id = {$data['id']};
            ");   
            $results_partners_plan = $res_partners_plan->fetchAssoc();

            if($results_partners_plan['date_end1'] == null and $results_partners_plan['date_end2'] == null) {

                $res = $this->db_connect->query("
                    SELECT id AS id, date_create AS date 
                    FROM bez_unprocessed_base 
                    WHERE source = 'telegram' AND partner = {$partner_id} AND date_create BETWEEN '{$date_start}' and CURDATE() 
                    UNION ALL 
                    SELECT request_id AS id, DATE(date_time) AS date 
                    FROM bez_sale_request 
                    WHERE partner_id = {$partner_id} AND DATE(date_time) BETWEEN '{$date_start}' and CURDATE()
                    ORDER BY date;
                ");

                $results = NULL;
                while($row = $res->fetchAssoc()) {
                    $results[] = $row;
                }

                if($results != NULL) {
                    foreach($results as $row) {

                        $i_sql = "
                            INSERT INTO bez_partners_plan_dates (partner_plan_id, date, stage, request_id)
                            SELECT '{$data['id']}', '{$row['date']}', 1, '{$row['id']}' WHERE NOT EXISTS (
                            SELECT 1 FROM bez_partners_plan_dates WHERE partner_plan_id = '{$data['id']}' AND request_id = '{$row['id']}'AND  date = '{$row['date']}' AND stage = 1
                            );
                        "; 

                        $this->db_connect->query($i_sql);                     
                    }
                }
            }    
        }
 
        $this->data['filter'] = array(
            'bez_partners_plan.id' => $data['id']
        );
        $response = $this->get_partners_plan();
        $new_data = $response[0]; 
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($new_data);  
    }

    public function delete_plan() {
        $data = $this->data;

        $query_request = "
            DELETE FROM {$this->pf}partners_plan WHERE id = {$data['id']}
        ";

        $this->db_connect->query($query_request);

        http_response_code(200);
        
    }

}

if(!empty($_POST['action'])) {
    $action = $_POST['action'];
    unset($_POST['action']);
    $obj = new PartnersPlan();
    $obj->handle_action($action);
}