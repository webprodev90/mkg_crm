<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config2.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd2.php';

class AtcStat {
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
            case 'get_atc_statistics':
                $this->get_atc_statistics();
                break;          
        }
    } 

    public function get_atc_statistics() {

        $date_start = $this->data['date_start'];
        $date_end = $this->data['date_end'];

        $time_start = $this->data['time_start'];
        $time_end = $this->data['time_end'];

        if($_SESSION['login_role'] == 5) {
            $res = $this->db_connect->query('SELECT 
                                            max(u.id) as id,
                                            u.name,
                                            u.id_otdel,
                                            u.id_atc,
                                            SUM(CASE WHEN t.status_id = 6 or t.status_id = 35 THEN 1 ELSE 0 END) AS sozvon,
                                            SUM(CASE WHEN t.status_id = 22 or t.status_id = 36 THEN 1 ELSE 0 END) AS avtoot,
                                            SUM(CASE WHEN t.status_id = 8 THEN 1 ELSE 0 END) AS ndz,
                                            SUM(CASE WHEN t.status_id = 9 or t.status_id = 21 or t.status_id = 11 or t.status_id = 24 or t.status_id = 25 or t.status_id = 26 or t.status_id = 27 or t.status_id = 28 or t.status_id = 29 or t.status_id = 30 or t.status_id = 31 or t.status_id = 32 or t.status_id = 33 or t.status_id = 34 THEN 1 ELSE 0 END) AS otkaz,
                                            SUM(CASE WHEN t.status_id = 15 or t.status_id = 6 or t.status_id = 9 or t.status_id = 21 or t.status_id = 11 or t.status_id = 16 or t.status_id = 23 or t.status_id = 24 or t.status_id = 25 or t.status_id = 26 or t.status_id = 28 or t.status_id = 29 or t.status_id = 30 or t.status_id = 31 or t.status_id = 32 or t.status_id = 33 or t.status_id = 34 or t.status_id = 35 THEN 1 ELSE 0 END) AS dozvon,
                                            IF(SUM(CASE WHEN t.status_id = 15 or t.status_id = 16 or t.status_id = 6 or t.status_id = 35 or t.status_id = 22 or t.status_id = 36 or t.status_id = 8 or  t.status_id = 9 or t.status_id = 21 or t.status_id = 11 or t.status_id = 24 or t.status_id = 25 or t.status_id = 26 or t.status_id = 27 or t.status_id = 28 or t.status_id = 29 or t.status_id = 30 or t.status_id = 31 or t.status_id = 32 or t.status_id = 33 or t.status_id = 34 THEN 1 ELSE 0 END) = 0, IFNULL(Table2.count_request, 0), SUM(CASE WHEN t.status_id = 15 or t.status_id = 16 or t.status_id = 6 or t.status_id = 35 or t.status_id = 22 or t.status_id = 36 or t.status_id = 8 or  t.status_id = 9 or t.status_id = 21 or t.status_id = 11 or t.status_id = 24 or t.status_id = 25 or t.status_id = 26 or t.status_id = 27 or t.status_id = 28 or t.status_id = 29 or t.status_id = 30 or t.status_id = 31 or t.status_id = 32 or t.status_id = 33 or t.status_id = 34 THEN 1 ELSE 0 END)) AS total_sum,
                                            IFNULL(Table2.count_request, 0) AS lid
                                              FROM bez_reg u
                                              left 
                                              join (SELECT * FROM lg_user_event GROUP BY DATE(row_change_time), RIGHT(phone_number, 10)) t
                                                on t.user_id = u.id 
                                               and date(row_change_time) between date"'.$date_start.'" and date"'.$date_end.'" AND HOUR(row_change_time) >= '.$time_start.' AND HOUR(row_change_time) < '.$time_end.'
                                               left join (SELECT
                                                                bsr1.user_id,
                                                                COUNT(count_sale) as count_request
                                                            FROM (SELECT *, COUNT(bsr1.id) AS count_sale FROM bez_sale_request bsr1
                                                            WHERE DATE(bsr1.date_time) BETWEEN "'.$date_start.'" AND "'.$date_end.'" AND HOUR(bsr1.date_time) >= '.$time_start.' AND HOUR(bsr1.date_time) < '.$time_end.'
                                                            AND bsr1.partner_id <> 65
                                                            AND NOT EXISTS (SELECT * FROM bez_sale_request bsr2 WHERE bsr1.request_id = bsr2.request_id and bsr2.partner_id = 65 and bsr2.date_time BETWEEN bsr1.date_time and DATE_FORMAT(bsr1.date_time, "%y-%m-%d 23:59:59") AND (NOT EXISTS (SELECT * FROM bez_sale_request bsr3 WHERE bsr1.id <> bsr3.id and bsr1.request_id = bsr3.request_id and bsr3.partner_id <> 65 and bsr3.date_time BETWEEN bsr1.date_time and bsr2.date_time)))
                                                            AND user_id = '. $_SESSION['login_id'] . '
                                                            GROUP BY user_id, request_id, DATE_FORMAT(date_time, "%Y-%m-%d")) bsr1
                                                            JOIN bez_reg
                                                            ON bsr1.user_id = bez_reg.id) Table2
                                              on u.id = Table2.user_id
                                              where u.id = '. $_SESSION['login_id'] . '
                                              HAVING id IS NOT NULL');   
        } elseif($_SESSION['login_role'] == 4) {
            $res = $this->db_connect->query('with wt_main0 as (SELECT 
                                                            max(u.id) as id,
                                                            u.name,
                                                            u.id_otdel,
                                                            u.id_atc,
                                                            SUM(CASE WHEN t.status_id = 6 or t.status_id = 35 THEN 1 ELSE 0 END) AS sozvon,
                                                            SUM(CASE WHEN t.status_id = 22 or t.status_id = 36 THEN 1 ELSE 0 END) AS avtoot,
                                                            SUM(CASE WHEN t.status_id = 8 THEN 1 ELSE 0 END) AS ndz,
                                                            SUM(CASE WHEN t.status_id = 9 or t.status_id = 21 or t.status_id = 11 or t.status_id = 24 or t.status_id = 25 or t.status_id = 26 or t.status_id = 27 or t.status_id = 28 or t.status_id = 29 or t.status_id = 30 or t.status_id = 31 or t.status_id = 32 or t.status_id = 33 or t.status_id = 34 THEN 1 ELSE 0 END) AS otkaz,
                                                            SUM(CASE WHEN t.status_id = 15 or t.status_id = 6 or t.status_id = 9 or t.status_id = 21 or t.status_id = 11 or t.status_id = 16 or t.status_id = 23 or t.status_id = 24 or t.status_id = 25 or t.status_id = 26 or t.status_id = 28 or t.status_id = 29 or t.status_id = 30 or t.status_id = 31 or t.status_id = 32 or t.status_id = 33 or t.status_id = 34 or t.status_id = 35 THEN 1 ELSE 0 END) AS dozvon,
                                                            IF(SUM(CASE WHEN t.status_id = 15 or t.status_id = 16 or t.status_id = 6 or t.status_id = 35 or t.status_id = 22 or t.status_id = 36 or t.status_id = 8 or  t.status_id = 9 or t.status_id = 21 or t.status_id = 11 or t.status_id = 24 or t.status_id = 25 or t.status_id = 26 or t.status_id = 27 or t.status_id = 28 or t.status_id = 29 or t.status_id = 30 or t.status_id = 31 or t.status_id = 32 or t.status_id = 33 or t.status_id = 34 THEN 1 ELSE 0 END) = 0, IFNULL(Table2.count_request, 0), SUM(CASE WHEN t.status_id = 15 or t.status_id = 16 or t.status_id = 6 or t.status_id = 35 or t.status_id = 22 or t.status_id = 36 or t.status_id = 8 or  t.status_id = 9 or t.status_id = 21 or t.status_id = 11 or t.status_id = 24 or t.status_id = 25 or t.status_id = 26 or t.status_id = 27 or t.status_id = 28 or t.status_id = 29 or t.status_id = 30 or t.status_id = 31 or t.status_id = 32 or t.status_id = 33 or t.status_id = 34 THEN 1 ELSE 0 END)) AS total_sum,
                                                            IFNULL(Table2.count_request, 0) AS lid
                                                              FROM bez_reg u
                                                              left 
                                                              join (SELECT * FROM lg_user_event GROUP BY DATE(row_change_time), RIGHT(phone_number, 10)) t
                                                                on t.user_id = u.id 
                                                               and date(row_change_time) between date"'.$date_start.'" and date"'.$date_end.'" AND HOUR(row_change_time) >= '.$time_start.' AND HOUR(row_change_time) < '.$time_end.'
                                                               left join (SELECT
                                                                    bsr1.user_id,
                                                                    COUNT(count_sale) as count_request
                                                                FROM (SELECT *, COUNT(bsr1.id) AS count_sale FROM bez_sale_request bsr1
                                                                WHERE DATE(bsr1.date_time) BETWEEN "'.$date_start.'" AND "'.$date_end.'" AND HOUR(bsr1.date_time) >= '.$time_start.' AND HOUR(bsr1.date_time) < '.$time_end.'
                                                                AND bsr1.partner_id <> 65
                                                                AND NOT EXISTS (SELECT * FROM bez_sale_request bsr2 WHERE bsr1.request_id = bsr2.request_id and bsr2.partner_id = 65 and bsr2.date_time BETWEEN bsr1.date_time and DATE_FORMAT(bsr1.date_time, "%y-%m-%d 23:59:59") AND (NOT EXISTS (SELECT * FROM bez_sale_request bsr3 WHERE bsr1.id <> bsr3.id and bsr1.request_id = bsr3.request_id and bsr3.partner_id <> 65 and bsr3.date_time BETWEEN bsr1.date_time and bsr2.date_time)))
                                                                GROUP BY user_id, request_id, DATE_FORMAT(date_time, "%Y-%m-%d")) bsr1
                                                                JOIN bez_reg
                                                                ON bsr1.user_id = bez_reg.id
                                                                GROUP BY bsr1.user_id) Table2
                                                              on u.id = Table2.user_id
                                                              WHERE u.role = 5 
                                                              GROUP BY u.name
                                                              HAVING id IS NOT NULL AND total_sum > 0
                                                              ORDER BY u.id_otdel, u.name),
                                                          wt_main1 as (
                                                            SELECT 
                                                            user_id,
                                                            COUNT(*) as total_work_hours
                                                            FROM (
                                                            SELECT 
                                                            user_id,
                                                            DATE(row_change_time) as work_date,
                                                            HOUR(row_change_time) as work_hour,
                                                            COUNT(*) as records_count
                                                            FROM lg_user_event
                                                            WHERE DATE(row_change_time) BETWEEN "'.$date_start.'" AND "'.$date_end.'" AND HOUR(row_change_time) >= '.$time_start.' AND HOUR(row_change_time) < '.$time_end.'
                                                            GROUP BY 
                                                            user_id, 
                                                            DATE(row_change_time),
                                                            HOUR(row_change_time)
                                                            HAVING COUNT(*) >= 10
                                                            ) as hourly_stats
                                                            GROUP BY user_id
                                                            )
                                                        SELECT wt_main0.*,   
                                                            IFNULL(wt_main1.total_work_hours, 0) AS work_hours,
                                                            IFNULL(ROUND(wt_main0.dozvon / wt_main1.total_work_hours, 1),0) as dozvon_h,
                                                            IFNULL(ROUND((wt_main0.lid / wt_main0.dozvon)*100, 1),0) as dozvonlid_p
                                                        FROM wt_main0
                                                        LEFT JOIN wt_main1
                                                        ON wt_main0.id = wt_main1.user_id
                                                        ORDER BY id_otdel, name');   
        } else {
            $res = $this->db_connect->query('with wt_main0 as (
                                            SELECT      
                                            bsr1.user_id,
                                            COUNT(count_sale) as count_request,
                                            SUM(chist_lid) as total_chist_lid,
                                            SUM(hold) as total_hold,
                                            SUM(gr_hold) as total_gr_hold
                                            FROM (SELECT *,
                                            CASE WHEN NOT EXISTS (SELECT * FROM bez_unprocessed_base JOIN bez_sale_request ON bez_unprocessed_base.id = bez_sale_request.request_id WHERE bez_unprocessed_base.phone_number = table_leads.phone_number AND DATE(bez_sale_request.date_time) < DATE(table_leads.date_time)) THEN 1 ELSE 0 END AS chist_lid,
                                            CASE WHEN NOT EXISTS (SELECT * FROM bez_unprocessed_base JOIN bez_sale_request ON bez_unprocessed_base.id = bez_sale_request.request_id WHERE bez_unprocessed_base.phone_number = table_leads.phone_number AND DATE(bez_sale_request.date_time) BETWEEN DATE_SUB(DATE(table_leads.date_time), INTERVAL 30 DAY) AND DATE_SUB(DATE(table_leads.date_time), INTERVAL 1 DAY)) AND EXISTS (SELECT * FROM bez_unprocessed_base JOIN bez_sale_request ON bez_unprocessed_base.id = bez_sale_request.request_id WHERE bez_unprocessed_base.phone_number = table_leads.phone_number AND DATE(bez_sale_request.date_time) < DATE_SUB(DATE(table_leads.date_time), INTERVAL 30 DAY)) THEN 1 ELSE 0 END AS hold,
                                            CASE WHEN EXISTS (SELECT * FROM bez_unprocessed_base JOIN bez_sale_request ON bez_unprocessed_base.id = bez_sale_request.request_id WHERE bez_unprocessed_base.phone_number = table_leads.phone_number AND DATE(bez_sale_request.date_time) BETWEEN DATE_SUB(DATE(table_leads.date_time), INTERVAL 30 DAY) AND DATE_SUB(DATE(table_leads.date_time), INTERVAL 1 DAY)) THEN 1 ELSE 0 END AS gr_hold
                                            FROM (SELECT bsr1.*, bub1.phone_number, COUNT(bsr1.id) AS count_sale
                                                FROM bez_sale_request bsr1
                                                JOIN bez_unprocessed_base bub1
                                                ON bsr1.request_id = bub1.id
                                                WHERE bsr1.date_time BETWEEN "'.$date_start.' 00:00:00" AND "'.$date_end.' 23:59:59" AND HOUR(bsr1.date_time) >= '.$time_start.' AND HOUR(bsr1.date_time) < '.$time_end.'
                                                AND bsr1.partner_id <> 65
                                                AND NOT EXISTS (SELECT * FROM bez_sale_request bsr2 WHERE bsr1.request_id = bsr2.request_id and bsr2.partner_id = 65 and bsr2.date_time BETWEEN bsr1.date_time and DATE_FORMAT(bsr1.date_time, "%y-%m-%d 23:59:59") AND (NOT EXISTS (SELECT * FROM bez_sale_request bsr3 WHERE bsr1.id <> bsr3.id and bsr1.request_id = bsr3.request_id and bsr3.partner_id <> 65 and bsr3.date_time BETWEEN bsr1.date_time and bsr2.date_time)))
                                                GROUP BY bsr1.user_id, bsr1.request_id, DATE_FORMAT(date_time, "%Y-%m-%d")) table_leads) bsr1
                                            WHERE bsr1.date_time BETWEEN "'.$date_start.' 00:00:00" AND "'.$date_end.' 23:59:59" AND HOUR(bsr1.date_time) >= '.$time_start.' AND HOUR(bsr1.date_time) < '.$time_end.'
                                            GROUP BY bsr1.user_id
                                        ), wt_main as (
                                            SELECT 
                                            max(u.id) as id,
                                            u.name,
                                            u.id_otdel,
                                            u.id_atc,
                                            IFNULL((SELECT ROUND(AVG(duration_seconds)) FROM lead_filling_stats WHERE user_id = u.id and date(start_datetime) between date("'.$date_start.'") and date("'.$date_end.'") AND HOUR(start_datetime) >= '.$time_start.' AND HOUR(start_datetime) < '.$time_end.'), 0) AS avg_duration_seconds,
                                            SUM(CASE WHEN t.status_id = 6 or t.status_id = 35 THEN 1 ELSE 0 END) AS sozvon,
                                            SUM(CASE WHEN t.status_id = 22 or t.status_id = 36 THEN 1 ELSE 0 END) AS avtoot,
                                            SUM(CASE WHEN t.status_id = 8 THEN 1 ELSE 0 END) AS ndz,
                                            IFNULL(s.count_request, 0) AS lid,
                                            SUM(CASE WHEN t.status_id = 9 or t.status_id = 21 or t.status_id = 11 or t.status_id = 24 or t.status_id = 25 or t.status_id = 26 or t.status_id = 27 or t.status_id = 28 or t.status_id = 29 or t.status_id = 30 or t.status_id = 31 or t.status_id = 32 or t.status_id = 33 or t.status_id = 34 THEN 1 ELSE 0 END) AS otkaz,
                                            SUM(CASE WHEN t.status_id = 15 or t.status_id = 6 or t.status_id = 9 or t.status_id = 21 or t.status_id = 11 or t.status_id = 16 or t.status_id = 23 or t.status_id = 24 or t.status_id = 25 or t.status_id = 26 or t.status_id = 28 or t.status_id = 29 or t.status_id = 30 or t.status_id = 31 or t.status_id = 32 or t.status_id = 33 or t.status_id = 34 or t.status_id = 35 THEN 1 ELSE 0 END) AS dozvon,
                                            SUM(CASE WHEN t.status_id = 15 or t.status_id = 16 THEN 1 ELSE 0 END) AS all_leads,
                                            IFNULL(s.total_chist_lid, 0) AS chist_lid,
                                            IFNULL(s.total_hold, 0) AS hold,
                                            IFNULL(s.total_gr_hold, 0) AS gr_hold
                                              FROM bez_reg u
                                              left 
                                              join (SELECT * FROM lg_user_event GROUP BY DATE(row_change_time), RIGHT(phone_number, 10)) t
                                                on t.user_id = u.id
                                               and date(`row_change_time`) between date"'.$date_start.'" and date"'.$date_end.'" AND HOUR(row_change_time) >= '.$time_start.' AND HOUR(row_change_time) < '.$time_end.'
                                              left join wt_main0 s
                                                on s.user_id = u.id
                                              where u.role = 5
                                             group by u.name
                                        ), wt_main1 as (
                                            SELECT 
                                                user_id,
                                                COUNT(*) as total_work_hours
                                            FROM (
                                                SELECT 
                                                    user_id,
                                                    DATE(row_change_time) as work_date,
                                                    HOUR(row_change_time) as work_hour,
                                                    COUNT(*) as records_count
                                                FROM lg_user_event
                                                WHERE DATE(row_change_time) BETWEEN "'.$date_start.'" AND "'.$date_end.'" AND HOUR(row_change_time) >= '.$time_start.' AND HOUR(row_change_time) < '.$time_end.'
                                                GROUP BY 
                                                    user_id, 
                                                    DATE(row_change_time),
                                                    HOUR(row_change_time)
                                                HAVING COUNT(*) >= 10
                                            ) as hourly_stats
                                            GROUP BY user_id
                                        ), wt_main2 as (
                                            select 
                                                t.name,
                                                t.id_otdel,
                                                t.id_atc,
                                                t.avg_duration_seconds,
                                                t.sozvon,  
                                                t.lid, 
                                                t.avtoot, 
                                                t.ndz,
                                                t.otkaz,
                                                IFNULL(s.total_work_hours, 0) AS work_hours,    
                                                t.dozvon,
                                                IF(t.sozvon + t.avtoot + t.ndz + t.all_leads + t.otkaz = 0, t.lid, t.sozvon + t.avtoot + t.ndz + t.all_leads + t.otkaz) as total_sum,
                                                t.chist_lid,
                                                t.hold,
                                                t.gr_hold
                                            from wt_main t
                                              LEFT
                                              JOIN wt_main1 s
                                                ON s.user_id = t.id

                                        )
                                        select 
                                            t.name,
                                            t.id_otdel,
                                            t.id_atc,
                                            t.avg_duration_seconds,
                                            t.total_sum,
                                            t.avtoot, 
                                            t.ndz,
                                            t.otkaz,
                                            t.dozvon,
                                            t.sozvon,  
                                            t.lid, 
                                            t.work_hours,
                                            t.chist_lid,
                                            t.hold,
                                            t.gr_hold,
                                            IFNULL(ROUND(t.dozvon / t.work_hours, 1),0) as dozvon_h,
                                            IFNULL(ROUND(t.lid / t.work_hours, 1),0) as lid_h,
                                            IFNULL(ROUND(((t.avtoot + t.ndz) / t.total_sum)*100, 1),0) as ndz_p,
                                            IFNULL(ROUND((t.avtoot / t.total_sum)*100, 1),0) as ao_p,
                                            IFNULL(ROUND((t.lid / t.dozvon)*100, 1),0) as dozvonlid_p,
                                            IFNULL(ROUND((t.lid / t.total_sum)*100, 1),0) as contactlid_p,
                                            IFNULL(ROUND(t.total_sum / t.work_hours, 1),0) as zvon_hours,
                                            IFNULL(ROUND((t.hold / t.lid)*100, 1),0) as holdlid_p,
                                            IFNULL(ROUND((t.gr_hold / t.lid)*100, 1),0) as grholdlid_p
                                        from wt_main2 t
                                       where t.total_sum > 0
                                       order by t.id_otdel, t.name
                                        limit 500 ');   
        }

        $results = [];
        while($row = $res->fetchAssoc()) {
            $results[] = $row;
        }

        if($_SESSION['login_role'] != 4 AND $_SESSION['login_role'] != 5) {
            $res2 = $this->db_connect2->query("
                SELECT field_473 AS user_id, ROUND(AVG(TIMESTAMPDIFF(SECOND, FROM_UNIXTIME(field_230), FROM_UNIXTIME(field_231))), 0) AS call_duration
                FROM app_entity_21 
                WHERE field_473 <> '' AND field_473 <> 00901 AND DATE(FROM_UNIXTIME(field_383)) BETWEEN '{$date_start}' AND '{$date_end}' AND HOUR(FROM_UNIXTIME(field_383)) >= {$time_start} AND HOUR(FROM_UNIXTIME(field_383)) < {$time_end}
                GROUP BY field_473;
            ");

            $results2 = [];
            while($row = $res2->fetchAssoc()) {
                $results2[] = $row;
            }
        }

        if($results) {
            $path = $_SERVER['DOCUMENT_ROOT'] . '/scripts/atc_stat/view/table-atc-stat.php';
            include ($path);
        }

    }
 

}

if(!empty($_POST['action'])) {
    $action = $_POST['action'];
    unset($_POST['action']);
    $obj = new AtcStat();
    $obj->handle_action($action);
}