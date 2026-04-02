<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

class LeadPicker
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
            case 'get_operators':
                return $this->get_operators();
            case 'update_request':
                $this->update_request($this->data);
                break;
            case 'get_statuses':
                return $this->get_statuses();
            case 'get_counters':
                return $this->get_counters();
            case 'delete_request':
                $this->delete_request(); 
                break;
            case 'create_comment':
                $this->create_comment(); 
                break;
            case 'update_comment':
                $this->update_comment(); 
                break;        
            case 'get_comments':
                return $this->get_comments();
            case 'delete_comment':
                $this->delete_comment(); 
                break;           
        }
    }

    public function get_statuses() {
        $res = $this->db_connect->query("
            SELECT
            {$this->pf}lead_picker_status.status_name,
            {$this->pf}lead_picker_status.status_id
            FROM
                {$this->pf}lead_picker_status
                WHERE {$this->pf}lead_picker_status.status_id
                ORDER BY {$this->pf}lead_picker_status.status_name ASC
        ");

        $statuses = NULL;
        while($row = $res->fetchAssoc()) {
            $statuses[] = $row;
        }

        return $statuses;
    }

    public function get_operators() {
        $res = $this->db_connect->query("
            SELECT
                {$this->pf}reg.id,
                {$this->pf}reg.name
            FROM
                {$this->pf}reg
            WHERE
                {$this->pf}reg.role = 11 OR {$this->pf}reg.role = 10 OR {$this->pf}reg.id = 119
        ");

        $operators = NULL;
        while($row = $res->fetchAssoc()) {
            $operators[] = $row;
        }

        return $operators;
    }

    public function create_comment() {
        $data = $this->data;

        $new_data = '';

        $query_request = "
            INSERT INTO {$this->pf}unprocessed_base2_comments (request2_id, comment, date_create) VALUES ('{$data['id']}', '{$data['comment']}', NOW())
        ";

        $this->db_connect->query($query_request);
        $this->data['filter'] = array(
            'id' => $data['id']
        );
        $response = $this->get_unprocessed_base();
        $new_data = $response[0];       
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($new_data);  
    }

    public function update_comment() {
        $data = $this->data;

        $new_data = '';

        $query_request = "
            UPDATE {$this->pf}unprocessed_base2_comments SET comment = '{$data['comment']}' WHERE id = {$data['comment_id']}
        ";

        $this->db_connect->query($query_request);
        $this->data['filter'] = array(
            'id' => $data['id']
        );
        $response = $this->get_unprocessed_base();
        $new_data = $response[0];       
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($new_data);  
    }

    public function update_request($data) {

        $new_data = '';
        $query_params = '';

        foreach($data['params'] as $item) {   
            if(trim($item['name']) != "new-comment" and trim($item['name']) != "updated-comment") {
                $query_params .= trim($item['name']) . " = '" . trim($item['value']) . "', ";     
            }                 
        }        

        $query_params = mb_substr($query_params, 0, -2);

        $query_request = "
            UPDATE {$this->pf}unprocessed_base2 SET {$query_params} WHERE id = {$data['id']}
        ";

        $this->db_connect->query($query_request);
        $this->data['filter'] = array(
            'id' => $data['id']
        );
        $response = $this->get_unprocessed_base();
        $new_data = $response[0];       
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($new_data);  
    }

    public function delete_request() {
        $data = $this->data;

        $query_request = "
            DELETE FROM {$this->pf}unprocessed_base2 WHERE id = {$data['id']}
        ";

        $this->db_connect->query($query_request);

        http_response_code(200);
        
    }

    public function delete_comment() {
        $data = $this->data;
        $new_data = '';

        $query_request = "
            DELETE FROM {$this->pf}unprocessed_base2_comments WHERE id = {$data['comment_id']}
        ";

        $this->db_connect->query($query_request);

        $this->data['filter'] = array(
            'id' => $data['id']
        );
        $response = $this->get_unprocessed_base();
        $new_data = $response[0];       
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($new_data);  
    }

    public function get_comments() {
        $data = $this->data;

        $queryset = $this->db_connect->query("
            SELECT id, request2_id, comment, DATE_ADD(date_create, INTERVAL {$this->tz} HOUR) AS date_create FROM {$this->pf}unprocessed_base2_comments WHERE request2_id = {$data['id']} ORDER BY date_create;
        ");

        $res = NULL;
        while($row = $queryset->fetchAssoc()) {
            $res[] = $row;
        }

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($res);
    }

    private function get_counters() {  # Вернет счетчики
        $date_filter_callings = " AND DATE({$this->pf}unprocessed_base2.date_time_status_change) = CURDATE()"; 
        $date_filter_all_leads = " AND DATE({$this->pf}unprocessed_base2.date_create) = CURDATE()";     
        $operator_where = '';
        $user_id = '';

        if(!empty($this->data['filter']['user_id'])) {
            $user_id = $this->data['filter']['user_id'];
            unset($this->data['filter']['user_id']);
        }

        $where_filter =  $this->get_where_filter('unprocessed_base2');

        if($user_id !== '') {
            $operator_where = " AND ({$this->pf}unprocessed_base2.user_id = '{$user_id}' OR {$this->pf}unprocessed_base2.created_by_user_id = '{$user_id}')";
        }

        $date_filter = "({$this->pf}unprocessed_base2.status = 12 AND DATE({$this->pf}unprocessed_base2.date_time_status_change) = CURDATE() {$where_filter}) OR ({$this->pf}unprocessed_base2.status = 4 AND DATE({$this->pf}unprocessed_base2.date_create) = CURDATE() {$where_filter}) OR ({$this->pf}unprocessed_base2.status = 2 AND DATE({$this->pf}unprocessed_base2.date_create) = CURDATE() {$where_filter}) OR ({$this->pf}unprocessed_base2.status = 3 AND DATE({$this->pf}unprocessed_base2.date_create) = CURDATE() {$where_filter}) OR ({$this->pf}unprocessed_base2.status = 7 AND DATE({$this->pf}unprocessed_base2.date_create) = CURDATE() {$where_filter}) OR ({$this->pf}unprocessed_base2.status = 10 AND DATE({$this->pf}unprocessed_base2.date_create) = CURDATE() {$where_filter}) OR ({$this->pf}unprocessed_base2.status IN(1, 2, 3, 4, 7, 12, 10, 19) AND DATE({$this->pf}unprocessed_base2.date_create) = CURDATE() {$where_filter})";

        if((isset($this->data['is_search']) and $this->data['is_search'] === 'true') or $user_id !== '') {
            $date_filter = "({$this->pf}unprocessed_base2.status = 12 {$where_filter}) OR ({$this->pf}unprocessed_base2.status = 4 {$where_filter}) OR ({$this->pf}unprocessed_base2.status = 2 {$where_filter}) OR ({$this->pf}unprocessed_base2.status = 3 {$where_filter}) OR ({$this->pf}unprocessed_base2.status = 7 {$where_filter}) OR ({$this->pf}unprocessed_base2.status = 10 {$where_filter}) OR ({$this->pf}unprocessed_base2.status IN(1, 2, 3, 4, 7, 12, 10, 19) {$where_filter})";
            $date_filter_callings = ''; 
            $date_filter_all_leads = ''; 
        }


        if(isset($this->data['date_start']) and isset($this->data['date_end'])) {
            $ds = $this->data['date_start'];
            $dend = $this->data['date_end'];
            $date_filter = "({$this->pf}unprocessed_base2.status = 12 AND DATE({$this->pf}unprocessed_base2.date_time_status_change) BETWEEN '{$ds}' AND '{$dend}' {$where_filter}) OR ({$this->pf}unprocessed_base2.status = 4 AND DATE({$this->pf}unprocessed_base2.date_create) BETWEEN '{$ds}' AND '{$dend}' {$where_filter}) OR ({$this->pf}unprocessed_base2.status = 2 AND DATE({$this->pf}unprocessed_base2.date_create) BETWEEN '{$ds}' AND '{$dend}' {$where_filter}) OR ({$this->pf}unprocessed_base2.status = 3 AND DATE({$this->pf}unprocessed_base2.date_create) BETWEEN '{$ds}' AND '{$dend}' {$where_filter}) OR ({$this->pf}unprocessed_base2.status = 7 AND DATE({$this->pf}unprocessed_base2.date_create) BETWEEN '{$ds}' AND '{$dend}' {$where_filter}) OR ({$this->pf}unprocessed_base2.status = 10 AND DATE({$this->pf}unprocessed_base2.date_create) BETWEEN '{$ds}' AND '{$dend}' {$where_filter}) OR ({$this->pf}unprocessed_base2.status IN(1, 2, 3, 4, 7, 12, 10, 19) AND DATE({$this->pf}unprocessed_base2.date_create) BETWEEN '{$ds}' AND '{$dend}' {$where_filter})";
            $date_filter_callings = " AND DATE({$this->pf}unprocessed_base2.date_time_status_change) BETWEEN '{$ds}' AND '{$dend}'"; 
            $date_filter_all_leads = " AND DATE({$this->pf}unprocessed_base2.date_create) BETWEEN '{$ds}' AND '{$dend}'"; 
        }

        if($_SESSION['login_role'] == 11 and $_SESSION['login_id'] != 347 and $_SESSION['login_id'] != 378) {
            $operator_where = " AND ({$this->pf}unprocessed_base2.user_id = {$_SESSION['login_id']} OR {$this->pf}unprocessed_base2.created_by_user_id = {$_SESSION['login_id']})";
        }

        $res = $this->db_connect->query("
            SELECT
                COUNT(CASE WHEN bez_unprocessed_base2.status = 12 {$date_filter_callings} THEN bez_unprocessed_base2.id END) AS callings,
                COUNT(CASE WHEN bez_unprocessed_base2.status = 4 THEN bez_unprocessed_base2.id END) AS non_calls,
                COUNT(CASE WHEN bez_unprocessed_base2.status = 2 THEN bez_unprocessed_base2.id END) AS rejections,
                COUNT(CASE WHEN bez_unprocessed_base2.status = 3 THEN bez_unprocessed_base2.id END) AS hung_up,
                COUNT(CASE WHEN bez_unprocessed_base2.status = 7 THEN bez_unprocessed_base2.id END) AS not_relevant,
                COUNT(CASE WHEN bez_unprocessed_base2.status = 10 THEN bez_unprocessed_base2.id END) AS new_leads,
                COUNT(CASE WHEN bez_unprocessed_base2.status IN(1, 2, 3, 4, 7, 12, 10, 19) {$date_filter_all_leads} THEN bez_unprocessed_base2.id END) AS all_leads
            FROM
                {$this->pf}unprocessed_base2
            WHERE
                ({$date_filter})
                {$operator_where}
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
            return 'AND (' . implode(" {$logical_operator} ", $where_conditions) . ')';
        } else
            return '';
    }

    public function get_unprocessed_base() {
        $status = '';
        $user_id = '';
        if(!empty($this->data['filter']['status']) and !empty($this->data['filter']['comparison_operator'])) {
            $status = $this->data['filter']['status'];
            unset($this->data['filter']['status']);
        }

        if(!empty($this->data['filter']['user_id'])) {
            $user_id = $this->data['filter']['user_id'];
            unset($this->data['filter']['user_id']);
        }

        $where_filter =  $this->get_where_filter('unprocessed_base2');

        $limit_start = isset($this->data['limit_start']) ? $this->data['limit_start'] : 0;
        $limit_end = isset($this->data['limit_end']) ? $this->data['limit_end'] : 100;
        $order_by = isset($this->data['order_by']) ? $this->data['order_by'] : 'id DESC';
        $order_by = $this->pf . 'unprocessed_base2.' . $order_by;

        if($status !== '') {
            $where_filter .= " AND {$this->pf}unprocessed_base2.status = '{$status}'";
        } 

        if(isset($this->data['date_start']) and isset($this->data['date_end'])) {
            $ds = $this->data['date_start'] . ' 00:00:00';
            $dend = $this->data['date_end'] . ' 23:59:59';

            if(!empty($this->data['filter']['status']) and $this->data['filter']['status'] == 12) {
                $where_filter .= " AND {$this->pf}unprocessed_base2.date_time_status_change BETWEEN '{$ds}' AND '{$dend}'";
            }    
            else {
                $where_filter .= " AND {$this->pf}unprocessed_base2.date_create BETWEEN '{$ds}' AND '{$dend}'";
            }        
        } else {
            if(!empty($this->data['filter']['status']) and $this->data['filter']['status'] == 12) {
                $where_filter .= " AND DATE({$this->pf}unprocessed_base2.date_time_status_change) = CURDATE()";
            }    
            else {
                $where_filter .= " AND DATE({$this->pf}unprocessed_base2.date_create) = CURDATE()";
            }
        }

        if($user_id !== '') {
            $where_filter .= " AND ({$this->pf}unprocessed_base2.user_id = '{$user_id}' OR {$this->pf}unprocessed_base2.created_by_user_id = '{$user_id}')";
        }

        if($_SESSION['login_role'] == 11 and $_SESSION['login_id'] != 347 and $_SESSION['login_id'] != 378) {
            $where_filter .= " AND ({$this->pf}unprocessed_base2.user_id = {$_SESSION['login_id']} OR {$this->pf}unprocessed_base2.created_by_user_id = {$_SESSION['login_id']})";
        }

        $res = $this->db_connect->query("
            SELECT
                {$this->pf}unprocessed_base2.id,
                {$this->pf}unprocessed_base2.fio,
                {$this->pf}unprocessed_base2.phone_number,
                {$this->pf}unprocessed_base2.vopros,
                {$this->pf}unprocessed_base2.city,
                {$this->pf}unprocessed_base2.status,
                {$this->pf}unprocessed_base2.date_time_status_change,
                {$this->pf}unprocessed_base2.date_create,
                {$this->pf}unprocessed_base2.user_id,
                {$this->pf}unprocessed_base2.сompany_name,
                {$this->pf}unprocessed_base2.sales_department,
                {$this->pf}unprocessed_base2.experience,
                {$this->pf}unprocessed_base2.have_crm,
                {$this->pf}unprocessed_base2.time_difference,
                {$this->pf}unprocessed_base2.job,
                {$this->pf}unprocessed_base2.created_by_user_id,
                {$this->pf}reg.name AS created_by_user,
                {$this->pf}lead_picker_status.status_name,
                IFNULL({$this->pf}unprocessed_base2_comments.comment, '') AS vopros,
                DATE_ADD({$this->pf}unprocessed_base2_comments.date_create, INTERVAL {$this->tz} HOUR) AS date_create_of_comment,
                {$this->pf}unprocessed_base2_comments.id AS comment_id
            FROM
                {$this->pf}unprocessed_base2
            LEFT JOIN
                {$this->pf}lead_picker_status ON {$this->pf}lead_picker_status.status_id = {$this->pf}unprocessed_base2.status
            LEFT JOIN
                {$this->pf}unprocessed_base2_comments ON {$this->pf}unprocessed_base2_comments.request2_id = {$this->pf}unprocessed_base2.id
            LEFT JOIN 
                {$this->pf}reg ON {$this->pf}unprocessed_base2.created_by_user_id =  {$this->pf}reg.id        
            WHERE
                {$this->pf}lead_picker_status.status_id IN(1, 2, 3, 4, 7, 12, 10, 19) {$where_filter}
            GROUP BY 
                {$this->pf}unprocessed_base2.id        
            ORDER BY 
                {$order_by}        
            LIMIT
                {$limit_start}, {$limit_end}
        ");

        $unprocessed_base_requests = NULL;
        while($row = $res->fetchAssoc()) {
            $unprocessed_base_requests[] = $row;
        }

        if($unprocessed_base_requests != NULL) {
            if(!empty($this->data['view_name'])) {
                $path = $_SERVER['DOCUMENT_ROOT'] . '/scripts/lead_picker/view/' . $this->data['view_name'] . '.php';
                include ($path);
            } else {
                return $unprocessed_base_requests;
            }
        }
    }
}

if(!empty($_POST['action'])) {
    $action = $_POST['action'];
    unset($_POST['action']);
    $obj = new LeadPicker();
    $obj->handle_action($action);
}