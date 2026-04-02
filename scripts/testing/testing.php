<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

class Testing
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
            case 'get_test':
                return $this->get_test();
            case 'get_test_name':
                return $this->get_test_name();
            case 'get_available_test':
                return $this->get_available_test(); 
            case 'check_access_to_theories':
                return $this->check_access_to_theories();     
            case 'save_result':
                $this->save_result();
                break;                
        }

    }

    public function get_test() {
        $test_id = $this->data['test_id'];

        $queryset = $this->db_connect->query("
            SELECT question.id, question.name_question, answer.name_answer, answer.is_correct 
            FROM testing JOIN question ON testing.id = question.testing_id JOIN answer ON question.id = answer.question_id 
            WHERE testing.id = {$test_id}
            ORDER BY question.id ASC, answer.id ASC;
        ");
        $res = NULL;
        while($row = $queryset->fetchAssoc()) {
            $res[] = $row;
        }

        $_SESSION['deny_access'] = $test_id;

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($res);
    }

    public function get_test_name() {
        $test_id = $this->data['test_id'];

        $res = $this->db_connect->query("
            SELECT * FROM testing WHERE id = {$test_id} LIMIT 1;
        ");

        $test = NULL;
        $test = $res->fetchAssoc();

        return $test['name'];
    }

    public function get_available_test() {
        $test_id = $this->data['test_id'];

        $res = $this->db_connect->query("
            SELECT progress_on_tasks_in_initial_course.id AS id FROM tasks_initial_course
            JOIN progress_on_tasks_in_initial_course 
            ON tasks_initial_course.id = progress_on_tasks_in_initial_course.task_id
            JOIN progress_by_blocks_in_initial_course 
            ON progress_on_tasks_in_initial_course.progress_by_block_id = progress_by_blocks_in_initial_course.id
            WHERE tasks_initial_course.test_id = {$test_id} AND progress_by_blocks_in_initial_course.start_date = CURDATE() AND progress_on_tasks_in_initial_course.user_id = {$_SESSION['login_id']} AND progress_on_tasks_in_initial_course.is_done IS NULL;
        ");

        $available_test = NULL;
        $available_test_id = NULL;
        $available_test = $res->fetchAssoc();
        if($available_test) {
            $available_test_id = $available_test['id'];
        }

        return $available_test_id;
    }

    public function save_result() {
        $user_test_id = $this->data['user_test_id'];
        $score = $this->data['score'];
        $is_done = $this->data['is_done'];

        $query_request = "
            UPDATE progress_on_tasks_in_initial_course SET score = {$score}, is_done = '{$is_done}' WHERE id = {$user_test_id}
        ";

        $this->db_connect->query($query_request);

        unset($_SESSION['deny_access']); 

        http_response_code(200);

    }

    public function check_access_to_theories() {
        $res = null;
        $test_id = $this->data['test_id'];

        if(isset($_SESSION['deny_access']) AND $_SESSION['deny_access'] == $test_id){
            $res = ["deny_access" => $_SESSION['deny_access']];
        }

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($res);

    }

}

if(!empty($_POST['action'])) {
    $action = $_POST['action'];
    unset($_POST['action']);
    $obj = new Testing();
    $obj->handle_action($action);
}