<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

class ShippedLeads {
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
            case 'get_partners':
                return $this->get_partners();
            case 'get_partner_statistics':
                $this->get_partner_statistics();
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

    public function get_partner_statistics() {

        $res = $this->db_connect->query("
            SELECT shipping_date, SUM(subCol) as col
            FROM 
                (SELECT DATE_FORMAT(bsr1.date_time, '%Y-%m-%d') AS shipping_date, count(distinct bez_unprocessed_base.id) as subCol
                FROM bez_sale_request bsr1
                JOIN bez_unprocessed_base
                ON bez_unprocessed_base.id = bsr1.request_id                                        
                WHERE bsr1.partner_id = {$this->data['partner_id']} AND bsr1.date_time BETWEEN '{$this->data['date_start']} 00:00:00' AND '{$this->data['date_end']} 23:59:59'         
                AND NOT EXISTS (SELECT * FROM bez_sale_request bsr2 WHERE bsr1.request_id = bsr2.request_id and bsr1.partner_id <> 65 and bsr2.partner_id = 65 and bsr2.date_time BETWEEN bsr1.date_time and DATE_FORMAT(bsr1.date_time, '%y-%m-%d 23:59:59') AND (NOT EXISTS (SELECT * FROM bez_sale_request bsr3 WHERE bsr1.id <> bsr3.id and bsr1.request_id = bsr3.request_id and bsr3.partner_id <> 65 and bsr3.date_time BETWEEN bsr1.date_time and bsr2.date_time)))
                GROUP BY DATE_FORMAT(bsr1.date_time, '%Y-%m-%d')
                UNION ALL
                SELECT bez_unprocessed_base.date_create AS shipping_date, count(distinct bez_unprocessed_base.id) as subCol
                FROM bez_unprocessed_base                                       
                WHERE bez_unprocessed_base.partner = {$this->data['partner_id']} AND bez_unprocessed_base.is_ship = '1' AND DATE_FORMAT(bez_unprocessed_base.date_create, '%Y-%m-%d') between DATE_FORMAT('{$this->data['date_start']}', '%Y-%m-%d') and DATE_FORMAT('{$this->data['date_end']}', '%Y-%m-%d') AND bez_unprocessed_base.source = 'telegram'
                GROUP BY bez_unprocessed_base.date_create) AS Table1
            GROUP BY shipping_date;
        ");

        $statistics = NULL;
        while($row = $res->fetchAssoc()) {
            $statistics[] = $row;
        }

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($statistics);  
    }    

}

if(!empty($_POST['action'])) {
    $action = $_POST['action'];
    unset($_POST['action']);
    $obj = new ShippedLeads();
    $obj->handle_action($action);
}