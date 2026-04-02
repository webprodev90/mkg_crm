<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config2.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd2.php';

class SendingAsterisk
{
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
            case 'get_stats':
                $this->get_stats();
                break; 
            case 'get_departments_of_stats':
                $this->get_departments_of_stats();
                break;  
            case 'get_sources_of_stats':
                $this->get_sources_of_stats();
                break;  
            case 'get_cities_of_stats':
                $this->get_cities_of_stats();
                break;     
            case 'get_mobile_operators_of_stats':
                $this->get_mobile_operators_of_stats();
                break;      
            case 'send_contacts_to_asterisk':
                $this->send_contacts_to_asterisk();
                break; 
            case 'get_campaigns':
                return $this->get_campaigns();        
         
        }

    }

    public function get_stats() {
        $where_department_id = "";
        $where_source = "";
        $where_city = "";
        $where_is_sog = "";        
        $where_mobile_operator_id = "";
        $where_touches_phone_number = "";
        $having_params = [];
        $having_sql = "";

        if(!empty($this->data['department_id'])) {
            $where_department_id = "AND {$this->pf}unprocessed_base.id_otdel = {$this->data['department_id']}";
        }

        if(!empty($this->data['source'])) {
            $where_source = "AND {$this->pf}group_request.name = '{$this->data['source']}'";
        }

        if(!empty($this->data['city'])) {
            //$where_city = "AND (({$this->pf}cities_group.name = '' AND {$this->pf}unprocessed_base.city = '{$this->data['city']}') OR ({$this->pf}cities_group.name = '{$this->data['city']}'))";
            $where_city = "AND IF({$this->pf}cities_group.name <> '', {$this->pf}cities_group.name, IF({$this->pf}unprocessed_base.city = '', 'Город не определен', {$this->pf}unprocessed_base.city)) = '{$this->data['city']}'";
        }

        if(!empty($this->data['is_sog'])) {
            $where_is_sog = "AND {$this->pf}unprocessed_base.is_sog = {$this->data['is_sog']}";
        }

        if(!empty($this->data['mobile_operator_id'])) {
            $where_mobile_operator_id = "AND {$this->pf}unprocessed_base.mobile_operator_id = {$this->data['mobile_operator_id']}";
        }

        if(!empty($this->data['touches_phone_number'])) {
            $touches_phone_number = explode("-", $this->data['touches_phone_number']);
            if(count($touches_phone_number) === 1) {
                $where_touches_phone_number = "AND touches_phone_number.count_touches >= {$touches_phone_number[0]}";
            } elseif(count($touches_phone_number) === 2) {
                if((int) $touches_phone_number[0] !== 0) {
                    $where_touches_phone_number = "AND (touches_phone_number.count_touches >= {$touches_phone_number[0]} AND touches_phone_number.count_touches <= {$touches_phone_number[1]})";                    
                } else {
                    $where_touches_phone_number = "AND (touches_phone_number.count_touches <= {$touches_phone_number[1]} OR touches_phone_number.count_touches IS NULL)";  
                }

            }
            
        }
 
        if(!empty($this->data['dozvon'])) {
            $dozvon = explode("-", $this->data['dozvon']);
            if(count($dozvon) === 1) {
                $having_params[] = "(1 - (SUM(CASE WHEN bez_unprocessed_base.status = 8 OR bez_unprocessed_base.status = 10 OR bez_unprocessed_base.status = 22 THEN 1 ELSE 0 END) / COUNT(bez_unprocessed_base.id))) * 100 >= {$dozvon[0]}";                
            } elseif(count($dozvon) === 2) {
                $having_params[] = "(1 - (SUM(CASE WHEN bez_unprocessed_base.status = 8 OR bez_unprocessed_base.status = 10 OR bez_unprocessed_base.status = 22 THEN 1 ELSE 0 END) / COUNT(bez_unprocessed_base.id))) * 100 BETWEEN {$dozvon[0]} AND {$dozvon[1]}";                  
            }
        }

        if(!empty($this->data['chist_kpd'])) {
            $chist_kpd = explode("-", $this->data['chist_kpd']);
            if(count($chist_kpd) === 1) {
                $having_params[] = "IFNULL(SUM(CASE WHEN {$this->pf}unprocessed_base.status = 15 THEN 1 ELSE 0 END) / (COUNT({$this->pf}unprocessed_base.id) - SUM(CASE WHEN {$this->pf}unprocessed_base.status = 8 THEN 1 ELSE 0 END) - SUM(CASE WHEN {$this->pf}unprocessed_base.status = 10 THEN 1 ELSE 0 END) - SUM(CASE WHEN {$this->pf}unprocessed_base.status = 22 THEN 1 ELSE 0 END)) * 100, 0) >= {$chist_kpd[0]}";                
            } elseif(count($chist_kpd) === 2) {
                $having_params[] = "IFNULL(SUM(CASE WHEN {$this->pf}unprocessed_base.status = 15 THEN 1 ELSE 0 END) / (COUNT({$this->pf}unprocessed_base.id) - SUM(CASE WHEN {$this->pf}unprocessed_base.status = 8 THEN 1 ELSE 0 END) - SUM(CASE WHEN {$this->pf}unprocessed_base.status = 10 THEN 1 ELSE 0 END) - SUM(CASE WHEN {$this->pf}unprocessed_base.status = 22 THEN 1 ELSE 0 END)) * 100, 0) BETWEEN {$chist_kpd[0]} AND {$chist_kpd[1]}";
            }
        }

        if(count($having_params) > 0) {
            $having_sql = implode(" AND ", $having_params);
            $having_sql = 'HAVING ' . $having_sql;
        }
        
        $res = $this->db_connect->query("
            SELECT
                {$this->pf}group_request.id, {$this->pf}group_request.name,
                IF({$this->pf}cities_group.name <> '', {$this->pf}cities_group.name, IF({$this->pf}unprocessed_base.city = '', 'Город не определен', {$this->pf}unprocessed_base.city)) AS city_group,
                DATE_FORMAT({$this->pf}group_request.date, '%d.%m.%Y') AS date,
                COUNT({$this->pf}unprocessed_base.id) AS total_count_request,    
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 8 OR {$this->pf}unprocessed_base.status = 10 OR {$this->pf}unprocessed_base.status = 22 THEN 1 ELSE 0 END) AS count_request,			
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 8 THEN 1 ELSE 0 END) AS not_calls,	
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 10 THEN 1 ELSE 0 END) AS not_processed,
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 22 THEN 1 ELSE 0 END) AS avtootvet,
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 15 THEN 1 ELSE 0 END) AS leads,
                (1 - (SUM(CASE WHEN bez_unprocessed_base.status = 8 OR bez_unprocessed_base.status = 10 OR bez_unprocessed_base.status = 22 THEN 1 ELSE 0 END) / COUNT(bez_unprocessed_base.id))) * 100 AS dozvon,
                COUNT(bez_unprocessed_base.id) - SUM(CASE WHEN bez_unprocessed_base.status = 8 OR bez_unprocessed_base.status = 10 OR bez_unprocessed_base.status = 22 THEN 1 ELSE 0 END) AS count_dozvon,
                IFNULL(SUM(CASE WHEN {$this->pf}unprocessed_base.status = 15 THEN 1 ELSE 0 END) / (COUNT({$this->pf}unprocessed_base.id) - SUM(CASE WHEN {$this->pf}unprocessed_base.status = 10 THEN 1 ELSE 0 END)) * 100, 0) AS kpd,
                IFNULL(SUM(CASE WHEN {$this->pf}unprocessed_base.status = 15 THEN 1 ELSE 0 END) / (COUNT({$this->pf}unprocessed_base.id) - SUM(CASE WHEN {$this->pf}unprocessed_base.status = 8 THEN 1 ELSE 0 END) - SUM(CASE WHEN {$this->pf}unprocessed_base.status = 10 THEN 1 ELSE 0 END) - SUM(CASE WHEN {$this->pf}unprocessed_base.status = 22 THEN 1 ELSE 0 END)) * 100, 0) AS chist_kpd,
                IFNULL(SUM(CASE WHEN {$this->pf}unprocessed_base.status = 15 THEN 1 ELSE 0 END) / COUNT({$this->pf}unprocessed_base.id) * 100, 0) AS gr_kpd
            FROM
                {$this->pf}group_request
            JOIN {$this->pf}unprocessed_base
              ON {$this->pf}unprocessed_base.group_source = {$this->pf}group_request.id
            LEFT 
			JOIN {$this->pf}cities_group  
			  ON {$this->pf}cities_group.id = {$this->pf}unprocessed_base.auto_city_group
            LEFT 
            JOIN touches_phone_number
              ON {$this->pf}unprocessed_base.phone_number = touches_phone_number.phone_number
            WHERE {$this->pf}group_request.date BETWEEN '{$this->data['date_start']} 00:00:00' AND '{$this->data['date_end']} 23:59:59' {$where_department_id} {$where_source} {$where_city} {$where_is_sog} {$where_mobile_operator_id} {$where_touches_phone_number}
            GROUP BY {$this->pf}group_request.name, 
                    IF(bez_cities_group.name = '' AND bez_unprocessed_base.city <> '', bez_unprocessed_base.city, bez_cities_group.name)
            {$having_sql}
            ORDER BY {$this->pf}group_request.name, chist_kpd DESC
        ");

        $results = NULL;
        while($row = $res->fetchAssoc()) {
            $results[] = $row;
        }

        if($results) {
            $path = $_SERVER['DOCUMENT_ROOT'] . '/scripts/sending_asterisk/view/table-requests.php';
            include ($path);
        }
    }  

    public function get_departments_of_stats() {

        $res = $this->db_connect->query("
            SELECT DISTINCT IFNULL({$this->pf}department.department_id, 0) AS department_id, IFNULL({$this->pf}department.department_id, 'Не назначен') AS name
            FROM {$this->pf}group_request
            JOIN {$this->pf}unprocessed_base
              ON {$this->pf}unprocessed_base.group_source = {$this->pf}group_request.id
            LEFT JOIN {$this->pf}department
            ON {$this->pf}unprocessed_base.id_otdel = {$this->pf}department.department_id
            WHERE {$this->pf}group_request.date BETWEEN '{$this->data['date_start']} 00:00:00' AND '{$this->data['date_end']} 23:59:59'
            ORDER BY {$this->pf}department.department_id ASC;   
        ");

        $departments = NULL;
        while($row = $res->fetchAssoc()) {
            $departments[] = $row;
        }

        echo json_encode($departments);
    }

    public function get_sources_of_stats() {

        $res = $this->db_connect->query("
            SELECT DISTINCT {$this->pf}group_request.name
            FROM {$this->pf}group_request
            JOIN {$this->pf}unprocessed_base
              ON {$this->pf}unprocessed_base.group_source = {$this->pf}group_request.id
            LEFT JOIN {$this->pf}department
            ON {$this->pf}unprocessed_base.id_otdel = {$this->pf}department.department_id
            WHERE {$this->pf}group_request.date BETWEEN '{$this->data['date_start']} 00:00:00' AND '{$this->data['date_end']} 23:59:59'
            ORDER BY {$this->pf}group_request.name ASC; 
        ");

        $sources = NULL;
        while($row = $res->fetchAssoc()) {
            $sources[] = $row;
        }

        echo json_encode($sources);
    }

    public function get_cities_of_stats() {

        $res = $this->db_connect->query("
            SELECT DISTINCT IF({$this->pf}cities_group.name <> '', {$this->pf}cities_group.name, IF({$this->pf}unprocessed_base.city = '', 'Город не определен', {$this->pf}unprocessed_base.city)) AS filcity
            FROM {$this->pf}group_request
            JOIN {$this->pf}unprocessed_base
              ON {$this->pf}unprocessed_base.group_source = {$this->pf}group_request.id
            LEFT JOIN {$this->pf}cities_group  
            ON {$this->pf}cities_group.id = {$this->pf}unprocessed_base.auto_city_group
            WHERE {$this->pf}group_request.date BETWEEN '{$this->data['date_start']} 00:00:00' AND '{$this->data['date_end']} 23:59:59'
            ORDER BY filcity ASC;
        ");

        $cities = NULL;
        while($row = $res->fetchAssoc()) {
            $cities[] = $row;
        }

        echo json_encode($cities);
    }

    public function get_mobile_operators_of_stats() {

        $res = $this->db_connect->query("
            SELECT DISTINCT mobile_operators.operator_id, mobile_operators.operator_name
            FROM {$this->pf}group_request
            JOIN {$this->pf}unprocessed_base
              ON {$this->pf}unprocessed_base.group_source = {$this->pf}group_request.id
            JOIN mobile_operators
            ON mobile_operators.operator_id = {$this->pf}unprocessed_base.mobile_operator_id
            WHERE {$this->pf}group_request.date BETWEEN '{$this->data['date_start']} 00:00:00' AND '{$this->data['date_end']} 23:59:59'
            ORDER BY mobile_operators.operator_id ASC;
        ");

        $mobile_operators = NULL;
        while($row = $res->fetchAssoc()) {
            $mobile_operators[] = $row;
        }

        echo json_encode($mobile_operators);
    }

    public function send_contacts_to_asterisk() {
        $where_department_id = "";
        $where_sources_cities = "";
        $where_mobile_operator_id = "";
        $where_is_sog = "";
        $where_source_city = [];
        $where_touches_phone_number = "";
        $campany_id = (int)$this->data['campany'];
        $limit = '';

        foreach($this->data['sources_cities'] as $item) {
            $where_source_city[] = "(bez_group_request.name = '{$item['source']}' AND IF(bez_cities_group.name <> '', bez_cities_group.name, IF(bez_unprocessed_base.city = '', 'Город не определен', bez_unprocessed_base.city)) = '{$item['city']}')";
        }    

        $where_sources_cities = implode(" OR ", $where_source_city);

        if(!empty($this->data['department_id'])) {
            $where_department_id = "AND {$this->pf}unprocessed_base.id_otdel = {$this->data['department_id']}";
        }

        if(!empty($this->data['mobile_operator_id'])) {
            $where_mobile_operator_id = "AND {$this->pf}unprocessed_base.mobile_operator_id = {$this->data['mobile_operator_id']}";
        }

        if(!empty($this->data['is_sog'])) {
            $where_mobile_operator_id = "AND {$this->pf}unprocessed_base.is_sog = {$this->data['is_sog']}";
        }

        if(!empty($this->data['count_to_send'])) {
            $limit = "LIMIT {$this->data['count_to_send']}";
        }

        if(!empty($this->data['touches_phone_number'])) {
            $touches_phone_number = explode("-", $this->data['touches_phone_number']);
            if(count($touches_phone_number) === 1) {
                $where_touches_phone_number = "AND touches_phone_number.count_touches >= {$touches_phone_number[0]}";
            } elseif(count($touches_phone_number) === 2) {
                if((int) $touches_phone_number[0] !== 0) {
                    $where_touches_phone_number = "AND (touches_phone_number.count_touches >= {$touches_phone_number[0]} AND touches_phone_number.count_touches <= {$touches_phone_number[1]})";                    
                } else {
                    $where_touches_phone_number = "AND (touches_phone_number.count_touches <= {$touches_phone_number[1]} OR touches_phone_number.count_touches IS NULL)";  
                }

            }
            
        }
        
        $res = $this->db_connect->query("
            SELECT {$this->pf}unprocessed_base.*
            FROM {$this->pf}group_request
            JOIN {$this->pf}unprocessed_base
            ON {$this->pf}unprocessed_base.group_source = {$this->pf}group_request.id
            LEFT JOIN {$this->pf}cities_group  
            ON {$this->pf}cities_group.id = {$this->pf}unprocessed_base.auto_city_group
            LEFT JOIN touches_phone_number
            ON {$this->pf}unprocessed_base.phone_number = touches_phone_number.phone_number
            WHERE ({$this->pf}unprocessed_base.status = 8 OR {$this->pf}unprocessed_base.status = 10 OR {$this->pf}unprocessed_base.status = 22) AND {$this->pf}group_request.date BETWEEN '{$this->data['date_start']} 00:00:00' AND '{$this->data['date_end']} 23:59:59' AND ({$where_sources_cities}) {$where_department_id} {$where_mobile_operator_id} {$where_is_sog} {$where_touches_phone_number} 
            {$limit};
        ");

        $contacts = [];
        while($row = $res->fetchAssoc()) {

            if($row['mobile_operator_id'] == 1) {
                $row['mobile_operator_id'] = 2508;
            } elseif($row['mobile_operator_id'] == 2) {
                $row['mobile_operator_id'] = 2511;
            } elseif($row['mobile_operator_id'] == 3) {
                $row['mobile_operator_id'] = 2510;
            } elseif($row['mobile_operator_id'] == 4) {
                $row['mobile_operator_id'] = 2507;
            } elseif($row['mobile_operator_id'] == 5) {
                $row['mobile_operator_id'] = 2515;
            } elseif($row['mobile_operator_id'] == 6) {
                $row['mobile_operator_id'] = 2517;
            } elseif($row['mobile_operator_id'] == 7) {
                $row['mobile_operator_id'] = 2516;
            }

            $contacts[] = "('8{$row['phone_number']}', 'true', '{$row['fio']}', {$campany_id}, {$row['mobile_operator_id']})";
            
        }

        $count_contacts = count($contacts);
        $add_contacts = implode(",", $contacts);

        $query_request_logs1 = "
            INSERT INTO logs (row_change_time, value, modul, text, user_id) VALUES (NOW(), 'insert', 'sending_asterisk', 'start sending. {$count_contacts} contacts sent. campaign id = {$campany_id}', '{$_SESSION['login_id']}');
        ";

        $this->db_connect->query($query_request_logs1);

        $query_request = "
            INSERT INTO app_entity_21 (field_229, field_382, field_423, field_479, field_526) VALUES {$add_contacts};
        ";

        $this->db_connect2->query($query_request);
        
        $query_request_logs2 = "
            INSERT INTO logs (row_change_time, value, modul, text, user_id) VALUES (NOW(), 'insert', 'sending_asterisk', 'end sending. {$count_contacts} contacts sent. campaign id = {$campany_id}', '{$_SESSION['login_id']}');
        ";

        $this->db_connect->query($query_request_logs2);

        http_response_code(200);
        
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

}

if(!empty($_POST['action'])) {
    $action = $_POST['action'];
    unset($_POST['action']);
    $obj = new SendingAsterisk();
    $obj->handle_action($action);
}