<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

class Rating
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
            case 'get_rating':
                $this->get_rating();
                break;  
        }

    }

    public function get_rating() {
        $department = 1;
        $working_days = 1;
        if(!empty($this->data['department'])) {
            $department = $this->data['department'];
        }

        $res0 = $this->db_connect->query("
            WITH RECURSIVE DateSeq AS (
              SELECT '{$this->data['date_start']}' as date
              UNION ALL
              SELECT DATE_ADD(date, INTERVAL 1 DAY)
              FROM DateSeq
              WHERE date < '{$this->data['date_end']}'
            )
            SELECT COUNT(*) as working_days
            FROM DateSeq
            WHERE DAYOFWEEK(date) BETWEEN 2 AND 6;
        ");
                    
        $result0 = $res0->fetchAssoc();
        $working_days = $result0['working_days'];

        $res = $this->db_connect->query("
            SELECT bez_reg.id, bez_reg.id_otdel, bez_reg.name, SUM(IF(SubQuery.user_id IS NOT NULL, count_request, 0)) AS count_request, IF(SUM(IF(SubQuery.user_id IS NOT NULL, count_request, 0)) >= 11 * {$working_days}, 1, 0) AS is_full_norm, IF(SubQuery.user_id IS NOT NULL, bonus, 0) AS bonus
            FROM bez_reg LEFT JOIN (SELECT user_id, operator_id, id_otdel, name, SUM(count_request_date) AS count_request, SUM(IF(count_request_date >= 15, 1, 0)) AS bonus
                FROM (SELECT user_id, operator_id, id_otdel, name, SUM(count_request) AS count_request_date, cur_date
                    FROM (SELECT
                bsr1.user_id,
                bez_reg.id as operator_id,
                bez_reg.id_otdel,
                bez_reg.name,
                COUNT(count_sale) as count_request,
                DATE_FORMAT(date_time, '%Y-%m-%d') AS cur_date
                    FROM (SELECT *, COUNT(bsr1.id) AS count_sale FROM bez_sale_request bsr1
                        WHERE bsr1.date_time BETWEEN '{$this->data['date_start']} 00:00:00' AND '{$this->data['date_end']} 23:59:59'
                        AND bsr1.partner_id <> 65
                        AND NOT EXISTS (SELECT * FROM bez_sale_request bsr2 WHERE bsr1.request_id = bsr2.request_id and bsr2.partner_id = 65 and bsr2.date_time BETWEEN bsr1.date_time and DATE_FORMAT(bsr1.date_time, '%y-%m-%d 23:59:59') AND (NOT EXISTS (SELECT * FROM bez_sale_request bsr3 WHERE bsr1.id <> bsr3.id and bsr1.request_id = bsr3.request_id and bsr3.partner_id <> 65 and bsr3.date_time BETWEEN bsr1.date_time and bsr2.date_time)))
                        GROUP BY user_id, request_id, DATE_FORMAT(date_time, '%Y-%m-%d')) bsr1
                JOIN bez_reg
                ON bsr1.user_id = bez_reg.id
                GROUP BY cur_date, bsr1.user_id
                UNION ALL 
                SELECT bub.user_id,
                    bez_reg.id as operator_id,
                    bez_reg.id_otdel,
                    bez_reg.name,
                    COUNT(bub.id) as count_request,
                    DATE_FORMAT(date_create, '%Y-%m-%d') AS cur_date
                FROM bez_unprocessed_base bub
                JOIN bez_reg
                ON bub.user_id = bez_reg.id
                WHERE
                    bub.date_create BETWEEN '{$this->data['date_start']}' AND '{$this->data['date_end']}'
                    AND bub.partner <> 65
                    AND bub.source = 'telegram'
                    AND bub.date_time_status_change IS NULL
                    AND bub.status = 10
                GROUP BY cur_date, bub.user_id) AS Table1
                GROUP BY cur_date, user_id) AS Table2
                GROUP BY user_id) AS SubQuery ON SubQuery.user_id = bez_reg.id
            WHERE bez_reg.id_otdel = {$department} AND bez_reg.role = 5
            GROUP BY bez_reg.id
            ORDER BY SUM(IF(SubQuery.user_id IS NOT NULL, count_request, 0)) DESC, IF(SubQuery.user_id IS NOT NULL, bonus, 0) DESC;
        ");

        $results = NULL;
        while($row = $res->fetchAssoc()) {
            $results[] = $row;
        }

        if($results) {
            $path = $_SERVER['DOCUMENT_ROOT'] . '/scripts/rating/view/table-rating.php';
            include ($path);
        }
    }

}

if(!empty($_POST['action'])) {
    $action = $_POST['action'];
    unset($_POST['action']);
    $obj = new Rating();
    $obj->handle_action($action);
}