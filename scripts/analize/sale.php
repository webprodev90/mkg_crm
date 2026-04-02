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
        }
    }

    public function get_groups_requests() {
        $res = $this->db_connect->query("
            SELECT *
            FROM
                {$this->pf}group_request
            WHERE
                date > \'{$this->data['date_start']}\' AND date < \'{$this->data['date_end']}\'
        ");

        $groups = NULL;
        while($row = $res->fetchAssoc()) {
            $groups[] = $row;
        }

        return $groups;
    }
}

if(!empty($_POST['action'])) {
    $action = $_POST['action'];
    unset($_POST['action']);
    $obj = new Sale();
    $obj->handle_action($action);
}