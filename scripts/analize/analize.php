<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

class Analize
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
            case 'get_groups_requests':
                $this->get_groups_requests();
                break;
            case 'get_stats':
                $this->get_stats();
                break;
            case 'get_stats_geo':
                $this->get_stats_geo();
                break;    
        }

        // switch($action) {
        //     case 'get_requests_from_group':
        //         $this->get_requests_from_group();
        //         break;
        // }
    }

    public function get_stats() {
        $where_group_id = "1 = 1";
        if(!empty($this->data['group_id'])) {
            $where_group_id = "{$this->pf}group_request.id = {$this->data['group_id']}";
        }

        $res = $this->db_connect->query("
            SELECT
                {$this->pf}group_request.id, {$this->pf}group_request.name,
                IF({$this->pf}cities_group.name <> '', {$this->pf}cities_group.name, IF({$this->pf}unprocessed_base.city = '', 'Город не определен', {$this->pf}unprocessed_base.city)) AS city_group,
                DATE_FORMAT({$this->pf}group_request.date, '%d.%m.%Y') AS date,
                COUNT({$this->pf}unprocessed_base.id) AS count_request,
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 6 THEN 1 ELSE 0 END) AS calling,				
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 8 THEN 1 ELSE 0 END) AS not_calls,
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 11 THEN 1 ELSE 0 END) AS rejection,
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 15 THEN 1 ELSE 0 END) AS leads,		
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 9 THEN 1 ELSE 0 END) AS stat9,	
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 10 THEN 1 ELSE 0 END) AS stat10,
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 16 THEN 1 ELSE 0 END) AS stat16,	
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 21 THEN 1 ELSE 0 END) AS stat21,	
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 22 THEN 1 ELSE 0 END) AS stat22,	
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 23 THEN 1 ELSE 0 END) AS stat23,	
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 24 THEN 1 ELSE 0 END) AS stat24,	
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 25 THEN 1 ELSE 0 END) AS stat25,	
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 26 THEN 1 ELSE 0 END) AS stat26,	
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 27 THEN 1 ELSE 0 END) AS stat27,	
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 28 THEN 1 ELSE 0 END) AS stat28,	
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 29 THEN 1 ELSE 0 END) AS stat29,	
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 30 THEN 1 ELSE 0 END) AS stat30,	
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 31 THEN 1 ELSE 0 END) AS stat31, 
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 32 THEN 1 ELSE 0 END) AS stat32, 
                IFNULL(SUM(CASE WHEN {$this->pf}unprocessed_base.status = 15 THEN 1 ELSE 0 END) / (COUNT({$this->pf}unprocessed_base.id) - SUM(CASE WHEN {$this->pf}unprocessed_base.status = 10 THEN 1 ELSE 0 END)) * 100, 0) AS kpd
            FROM
                {$this->pf}group_request,
                {$this->pf}unprocessed_base
            LEFT 
			JOIN {$this->pf}cities_group  
			  ON {$this->pf}cities_group.id = {$this->pf}unprocessed_base.auto_city_group
            WHERE
                {$where_group_id}
                AND {$this->pf}group_request.date BETWEEN
                    '{$this->data['date_start']} 00:00:00' AND '{$this->data['date_end']} 23:59:59'
                AND {$this->pf}unprocessed_base.group_source = {$this->pf}group_request.id
            GROUP BY {$this->pf}group_request.name, 
                    IF(bez_cities_group.name = '' AND bez_unprocessed_base.city <> '', bez_unprocessed_base.city, bez_cities_group.name)
            ORDER BY {$this->pf}group_request.name, kpd DESC
        ");

        $results = NULL;
        while($row = $res->fetchAssoc()) {
            $results[] = $row;
        }

        if($results) {
            $path = $_SERVER['DOCUMENT_ROOT'] . '/scripts/analize/view/table-requests.php';
            include ($path);
        }
    }

    public function get_stats_geo() {

        $res = $this->db_connect->query("
            SELECT
                {$this->pf}group_request.id, {$this->pf}cities_group.name AS city_group, {$this->pf}group_request.name, 
                SUM(CASE WHEN {$this->pf}unprocessed_base.status = 15 THEN 1 ELSE 0 END) AS leads,
                SUM(CASE WHEN {$this->pf}unprocessed_base.status <> 10 THEN 1 ELSE 0 END) AS processed,
                IFNULL(SUM(CASE WHEN {$this->pf}unprocessed_base.status = 15 THEN 1 ELSE 0 END) / (COUNT({$this->pf}unprocessed_base.id) - SUM(CASE WHEN {$this->pf}unprocessed_base.status = 10 THEN 1 ELSE 0 END)) * 100, 0) AS kpd
            FROM
                {$this->pf}group_request,
                {$this->pf}unprocessed_base
            LEFT JOIN {$this->pf}cities_group  ON {$this->pf}cities_group.id = {$this->pf}unprocessed_base.auto_city_group
            WHERE {$this->pf}group_request.date BETWEEN
                    '{$this->data['date_start']} 00:00:00' AND '{$this->data['date_end']} 23:59:59'
                AND {$this->pf}unprocessed_base.group_source = {$this->pf}group_request.id
            GROUP BY {$this->pf}cities_group.name, {$this->pf}group_request.name  
            ORDER BY city_group ASC
        ");

        $results = NULL;
        while($row = $res->fetchAssoc()) {
            $results[] = $row;
        }

        if($results) {
            $path = $_SERVER['DOCUMENT_ROOT'] . '/scripts/analize/view/table-requests-geo.php';
            include ($path);
        }
    }    

    public function get_groups_requests() {
        $res = $this->db_connect->query("
            SELECT
                {$this->pf}group_request.id,
                {$this->pf}group_request.name,
                {$this->pf}cities_group.name AS city_group,
                DATE_FORMAT({$this->pf}group_request.date, '%d.%m.%Y') AS date,
                COUNT({$this->pf}unprocessed_base.id) AS count_request
            FROM
                {$this->pf}group_request
            INNER JOIN
                {$this->pf}unprocessed_base ON {$this->pf}group_request.id = {$this->pf}unprocessed_base.group_source
            LEFT JOIN {$this->pf}cities_group ON {$this->pf}cities_group.id = {$this->pf}unprocessed_base.auto_city_group
            WHERE
                {$this->pf}group_request.date > '{$this->data["date_start"]} 00:00:00'
                AND {$this->pf}group_request.date < '{$this->data["date_end"]} 23:59:59'
            GROUP BY
                {$this->pf}group_request.id,
                {$this->pf}group_request.name,
                DATE_FORMAT({$this->pf}group_request.date, '%d.%m.%Y')
            HAVING
                count_request > 0
            ORDER BY
                {$this->pf}group_request.date DESC;
        ");

        $groups = NULL;
        while($row = $res->fetchAssoc()) {
            $groups[] = $row;
        }

        echo json_encode($groups);
    }
}

if(!empty($_POST['action'])) {
    $action = $_POST['action'];
    unset($_POST['action']);
    $obj = new Analize();
    $obj->handle_action($action);
}