<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

class AdminChecklist
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
            case 'get_checklist':
                $this->get_checklist();
                break;
            case 'get_user':
                $this->get_user();
                break;
            case 'get_admins':
                return $this->get_admins();        
            case 'create_task':
                $this->create_task();
                break;   
            case 'update_task':
                $this->update_task();
                break;                      
            case 'toggle_done_task':
                $this->toggle_done_task();
                break;      
            case 'delete_task':
                $this->delete_task();
                break;    
        }
    }

    public function get_checklist() {
        $res = $this->db_connect->query("
            SELECT *, IF(is_completed = 1 AND completion_date = CURDATE(), 1, 0) AS is_checked  
            FROM admin_tasks 
            WHERE user_id = {$this->data['user_id']};
        ");

        $admin_tasks = NULL;
        while($row = $res->fetchAssoc()) {
            $admin_tasks[] = $row;
        }

        if(!empty($this->data['view_name']) and !empty($admin_tasks)) {
            $path = $_SERVER['DOCUMENT_ROOT'] . '/scripts/admin_checklist/view/' . $this->data['view_name'] . '.php';
            include ($path);
        } else {
            return $admin_tasks;
        }
    }

    public function get_admins() {
        $admin_where = '';

        if($_SESSION['login_role'] == 4) {
            $admin_where = " AND bez_reg.id = {$_SESSION['login_id']}";
        }

        $res = $this->db_connect->query("
            SELECT bez_reg.id, bez_reg.name, IF(NOT EXISTS(SELECT * FROM admin_tasks WHERE user_id = bez_reg.id AND (is_completed = 0 OR completion_date <> CURDATE())) AND EXISTS(SELECT * FROM admin_tasks WHERE user_id = bez_reg.id), 1, 0) AS is_completed_tasks  
            FROM bez_reg
            WHERE bez_reg.role = 4 {$admin_where};
        ");

        $admins = NULL;
        while($row = $res->fetchAssoc()) {
            $admins[] = $row;
        }

        return $admins;
    }

    public function get_user() {
        
        $user = NULL;
        $res = $this->db_connect->query("
            SELECT * FROM bez_reg WHERE id = {$this->data['user_id']};  
        ");
        $user = $res->fetchAssoc();

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($user);  
    }

    public function create_task() {
        $query_request = "
            INSERT INTO admin_tasks (user_id, task_description) VALUES ('{$this->data['user_id']}', '{$this->data['task_description']}')
        ";

        $this->db_connect->query($query_request);

        http_response_code(200); 
    } 

    public function toggle_done_task() {
        $query_request = "
            UPDATE admin_tasks SET is_completed = '{$this->data['is_completed']}', completion_date = CURDATE() WHERE id = {$this->data['id']}
        ";

        $this->db_connect->query($query_request);

        http_response_code(200);
    }   

    public function update_task() {
        $query_request = "
            UPDATE admin_tasks SET task_description = '{$this->data['task_description']}' WHERE id = {$this->data['id']}
        ";

        $this->db_connect->query($query_request);

        http_response_code(200);
    }   

    public function delete_task() {
        $query_request = "
            DELETE FROM admin_tasks WHERE id = {$this->data['id']}
        ";

        $this->db_connect->query($query_request);

        http_response_code(200);
    }

}

if(!empty($_POST['action'])) {
    $action = $_POST['action'];
    unset($_POST['action']);
    $obj = new AdminChecklist();
    $obj->handle_action($action);
}