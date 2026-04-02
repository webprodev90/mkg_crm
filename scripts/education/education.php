<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

class Education
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
            case 'get_course':
                $this->get_course();
                break;
            case 'start_lesson':
                $this->start_lesson();
                break;
            case 'repeat_lesson':
                $this->repeat_lesson();
                break; 
            case 'get_learning_statistic':
                $this->get_learning_statistic();
                break;                 
        }

    }

    public function update_progress($where_user_id) {

        $res1 = $this->db_connect->query("
            SELECT progress_on_tasks_in_initial_course.id, IFNULL((
            SELECT SUM(count_request) AS count_request FROM (
                            SELECT
                            bsr1.user_id,
                            COUNT(count_sale) as count_request
                        FROM (SELECT *, COUNT(bsr1.id) AS count_sale FROM bez_sale_request bsr1
                                WHERE DATE(bsr1.date_time) = pbic1.start_date AND bsr1.user_id = pbic1.user_id
                                AND bsr1.partner_id <> 65
                                AND NOT EXISTS (SELECT * FROM bez_sale_request bsr2 WHERE bsr1.request_id = bsr2.request_id and bsr2.partner_id = 65 and bsr2.date_time BETWEEN bsr1.date_time and DATE_FORMAT(bsr1.date_time, '%y-%m-%d 23:59:59') AND (NOT EXISTS (SELECT * FROM bez_sale_request bsr3 WHERE bsr1.id <> bsr3.id and bsr1.request_id = bsr3.request_id and bsr3.partner_id <> 65 and bsr3.date_time BETWEEN bsr1.date_time and bsr2.date_time)))
                                GROUP BY user_id, request_id, DATE_FORMAT(date_time, '%Y-%m-%d')) bsr1
                        UNION ALL 
                        SELECT bub.user_id,
                            COUNT(bub.id) as count_request
                        FROM bez_unprocessed_base bub
                        WHERE
                            bub.date_create = pbic1.start_date AND bub.user_id = pbic1.user_id
                            AND bub.partner <> 65
                            AND bub.source = 'telegram'
                            AND bub.date_time_status_change IS NULL
                            AND bub.status = 10) AS SubQuery), 0) AS count_leads FROM progress_by_blocks_in_initial_course pbic1
            JOIN progress_on_tasks_in_initial_course
            ON pbic1.id = progress_on_tasks_in_initial_course.progress_by_block_id
            JOIN tasks_initial_course
            ON tasks_initial_course.id = progress_on_tasks_in_initial_course.task_id
            WHERE ((pbic1.status = 'Выполнен' AND pbic1.start_date = CURDATE()) OR (pbic1.status = 'В процессе')) AND tasks_initial_course.type_task = 'practice' {$where_user_id};
        ");
        
        $results1 = NULL;
        while($row = $res1->fetchAssoc()) {
            $results1[] = $row;
        }

        if($results1) {
            foreach ($results1 as $item) {
                $query_request1 = "
                    UPDATE progress_on_tasks_in_initial_course SET score = {$item['count_leads']} WHERE id = {$item['id']}
                ";         
                $this->db_connect->query($query_request1); 
            }
        }

        $res2 = $this->db_connect->query("
            SELECT * FROM progress_by_blocks_in_initial_course pbic1
            WHERE pbic1.status = 'В процессе' AND pbic1.start_date <= CURRENT_DATE() {$where_user_id} AND NOT EXISTS (
                SELECT * FROM progress_by_blocks_in_initial_course pbic2
                JOIN progress_on_tasks_in_initial_course 
                ON progress_on_tasks_in_initial_course.progress_by_block_id = pbic2.id 
                JOIN tasks_initial_course 
                ON tasks_initial_course.id = progress_on_tasks_in_initial_course.task_id
                WHERE ((tasks_initial_course.type_task = 'testing' AND (progress_on_tasks_in_initial_course.is_done <> 'сдано' OR progress_on_tasks_in_initial_course.is_done IS NULL)) OR (tasks_initial_course.type_task = 'practice' AND tasks_initial_course.passing_score > progress_on_tasks_in_initial_course.score)) AND pbic1.id = pbic2.id
            );
        ");
        
        $results2 = NULL;
        while($row = $res2->fetchAssoc()) {
            $results2[] = $row;
        }

        if($results2) {
            foreach ($results2 as $item) {
                $query_request2 = "
                    UPDATE progress_by_blocks_in_initial_course SET status = 'Выполнен', is_completed = 1 WHERE id = {$item['id']}
                ";         
                $this->db_connect->query($query_request2); 
            }
        }


        $res3 = $this->db_connect->query("
            SELECT * FROM progress_by_blocks_in_initial_course pbic1
            WHERE pbic1.status = 'В процессе' AND pbic1.start_date < CURRENT_DATE() {$where_user_id} AND EXISTS (
                SELECT * FROM progress_by_blocks_in_initial_course pbic2
                JOIN progress_on_tasks_in_initial_course 
                ON progress_on_tasks_in_initial_course.progress_by_block_id = pbic2.id 
                JOIN tasks_initial_course 
                ON tasks_initial_course.id = progress_on_tasks_in_initial_course.task_id
                WHERE ((tasks_initial_course.type_task = 'testing' AND (progress_on_tasks_in_initial_course.is_done <> 'сдано' OR progress_on_tasks_in_initial_course.is_done IS NULL)) OR (tasks_initial_course.type_task = 'practice' AND tasks_initial_course.passing_score > progress_on_tasks_in_initial_course.score)) AND pbic1.id = pbic2.id
            );
        ");
        
        $results3 = NULL;
        while($row = $res3->fetchAssoc()) {
            $results3[] = $row;
        }

        if($results3) {
            foreach ($results3 as $item) {
                $query_request3 = "
                    UPDATE progress_by_blocks_in_initial_course SET status = 'Не выполнен' WHERE id = {$item['id']}
                ";         
                $this->db_connect->query($query_request3); 
            }
        }

    }

    public function get_course() {

        $this->update_progress(" AND pbic1.user_id = {$_SESSION['login_id']}");

        $res = $this->db_connect->query("
            SELECT bic1.id, bic1.block_name, tasks_initial_course.description_task, tasks_initial_course.passing_score, tasks_initial_course.type_task, tasks_initial_course.test_id, progress_on_tasks_in_initial_course.id AS user_task_id, IF(tasks_initial_course.type_task = 'testing' AND progress_on_tasks_in_initial_course.is_done IS NULL AND pbbiic1.start_date = CURDATE(), 1, 0) AS is_available_test, IFNULL(pbbiic1.status, 'Не начат') AS status, IF(progress_on_tasks_in_initial_course.score IS NULL AND tasks_initial_course.passing_score IS NOT NULL, 0, progress_on_tasks_in_initial_course.score) AS score, is_done, link, IF(bic1.id = 1 OR EXISTS(SELECT * FROM progress_by_blocks_in_initial_course pbbiic2 WHERE pbbiic2.block_id = bic1.id - 1 AND pbbiic2.user_id = {$_SESSION['login_id']} AND pbbiic2.is_completed = 1), 1, 0) AS is_completed  
            FROM block_initial_course bic1
            JOIN tasks_initial_course
            ON tasks_initial_course.block_id = bic1.id 
            LEFT JOIN progress_by_blocks_in_initial_course pbbiic1
            ON pbbiic1.block_id = bic1.id AND pbbiic1.user_id = {$_SESSION['login_id']}
            LEFT JOIN progress_on_tasks_in_initial_course
            ON progress_on_tasks_in_initial_course.task_id = tasks_initial_course.id AND progress_on_tasks_in_initial_course.user_id = {$_SESSION['login_id']}
            ORDER BY bic1.id;
        ");

        $results = NULL;
        while($row = $res->fetchAssoc()) {
            $results[] = $row;
        }

        if($results) {
            $path = $_SERVER['DOCUMENT_ROOT'] . '/scripts/education/view/course.php';
            include ($path);
        }
    }

    public function check_can_start() {

        $result = null;

        $res = $this->db_connect->query("
            SELECT * FROM progress_by_blocks_in_initial_course WHERE user_id = {$_SESSION['login_id']} AND start_date = CURRENT_DATE();
        ");

        $result = $res->fetchAssoc();

        return $result;
    }

    public function start_lesson() {

        $new_data = '';
        if($this->check_can_start()) {
            $new_data = ['warning' => 'Запрещено проходить сразу несколько блоков в один день!'];
        }
        else {
            $i_sql1 = "
                INSERT INTO progress_by_blocks_in_initial_course (user_id, block_id, status, start_date)
                VALUES ({$_SESSION['login_id']}, {$this->data['block_id']}, 'В процессе', CURDATE());
            "; 

            $this->db_connect->query($i_sql1);

            $res_user_block = $this->db_connect->query("
                SELECT * FROM progress_by_blocks_in_initial_course
                WHERE user_id = {$_SESSION['login_id']}
                ORDER BY id DESC LIMIT 1;
            ");
            $last_user_block = $res_user_block->fetchAssoc();

            $res = $this->db_connect->query("
                SELECT id FROM tasks_initial_course WHERE block_id = {$this->data['block_id']} AND type_task <> 'reading';
            ");

            $results = NULL;
            while($row = $res->fetchAssoc()) {
                $results[] = $row;
            }

            if($results) {
                foreach ($results as $item) {
                    $i_sql2 = "
                        INSERT INTO progress_on_tasks_in_initial_course (user_id, task_id, score, progress_by_block_id)
                        VALUES ({$_SESSION['login_id']}, {$item['id']}, 0, {$last_user_block['id']});
                    "; 

                    $this->db_connect->query($i_sql2);
                }
            }    
            $new_data = ['successfully' => 'Для успешного прохождения блока все задания необходимо выполнить в этот день.'];           
        }
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($new_data);  

    }    

    public function repeat_lesson() {

        $new_data = '';

        if($this->check_can_start()) {
            $new_data = ['warning' => 'Запрещено проходить сразу несколько блоков в один день!'];
        }
        else {
            $res_user_block = $this->db_connect->query("
                SELECT id 
                FROM progress_by_blocks_in_initial_course 
                WHERE user_id = {$_SESSION['login_id']} AND block_id = {$this->data['block_id']} 
                LIMIT 1;
            ");
            $progress_by_block = $res_user_block->fetchAssoc();

            $query_request1 = "
                UPDATE progress_by_blocks_in_initial_course SET status = 'В процессе', start_date = CURDATE() WHERE id = {$progress_by_block['id']};
            ";         
            $this->db_connect->query($query_request1); 

            $query_request2 = "
                DELETE FROM progress_on_tasks_in_initial_course WHERE progress_by_block_id = {$progress_by_block['id']}
            ";

            $this->db_connect->query($query_request2);

            $res = $this->db_connect->query("
                SELECT id FROM tasks_initial_course WHERE block_id = {$this->data['block_id']} AND type_task <> 'reading';
            ");

            $results = NULL;
            while($row = $res->fetchAssoc()) {
                $results[] = $row;
            }

            if($results) {
                foreach ($results as $item) {
                    $i_sql2 = "
                        INSERT INTO progress_on_tasks_in_initial_course (user_id, task_id, score, progress_by_block_id)
                        VALUES ({$_SESSION['login_id']}, {$item['id']}, 0, {$progress_by_block['id']});
                    "; 

                    $this->db_connect->query($i_sql2);
                }
            }   

            $new_data = ['successfully' => 'Для успешного прохождения блока все задания необходимо выполнить в этот день.'];           
        }
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($new_data);  
    }

    public function get_learning_statistic() {

        $this->update_progress("");

        $res = $this->db_connect->query("
            SELECT bez_reg.id AS user_id, bez_reg.name, block_initial_course.id AS block_id, block_initial_course.block_name, IFNULL(progress_by_blocks_in_initial_course.status, 'Не начат') AS status
            FROM bez_reg
            CROSS JOIN block_initial_course
            LEFT JOIN progress_by_blocks_in_initial_course
            ON bez_reg.id = progress_by_blocks_in_initial_course.user_id AND progress_by_blocks_in_initial_course.block_id = block_initial_course.id
            WHERE bez_reg.id_otdel = {$this->data['department']} AND bez_reg.role = 5
            ORDER BY bez_reg.id, block_initial_course.id;
        ");

        $results = NULL;
        while($row = $res->fetchAssoc()) {
            $results[] = $row;
        }

        if($results) {
            $path = $_SERVER['DOCUMENT_ROOT'] . '/scripts/education/view/statistics.php';
            include ($path);
        }
    }    
}

if(!empty($_POST['action'])) {
    $action = $_POST['action'];
    unset($_POST['action']);
    $obj = new Education();
    $obj->handle_action($action);
}