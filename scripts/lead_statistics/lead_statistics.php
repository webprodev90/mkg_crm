<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

class LeadStatistics
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
            case 'get_lead_statistics':
                $this->get_lead_statistics();
                break;     
        }
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

    public function get_lead_statistics() {

        $date_filter = '';
        if(isset($this->data['date_start']) and isset($this->data['date_end'])) {
            $ds = $this->data['date_start'];
            $dend = $this->data['date_end'];
            $date_filter = " AND bez_unprocessed_base2.date_create BETWEEN '{$ds}' AND '{$dend}'";
        }    

        $res = $this->db_connect->query("
            SELECT bs.status_name, COUNT(bub2.status) AS leads
            FROM bez_status bs
            LEFT JOIN (SELECT status FROM bez_unprocessed_base2 WHERE 1 = 1 {$date_filter}) bub2
            ON bub2.status = bs.status_id
            WHERE bs.status_id IN (5, 6, 8, 9, 10, 11, 17, 18, 19, 20, 37)
            GROUP BY bs.status_name
            ORDER by bs.status_name;
        ");

        $results = NULL;
        while($row = $res->fetchAssoc()) {
            $results[] = $row;
        }

        if($results != NULL) {
            if(!empty($this->data['view_name'])) {
                $path = $_SERVER['DOCUMENT_ROOT'] . '/scripts/lead_statistics/view/' . $this->data['view_name'] . '.php';
                include ($path);
            } else {
                return $results;
            }
        }
    }

}

if(!empty($_POST['action'])) {
    $action = $_POST['action'];
    unset($_POST['action']);
    $obj = new LeadStatistics();
    $obj->handle_action($action);
}