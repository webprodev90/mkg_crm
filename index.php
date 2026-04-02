<?php

session_start();

//Устанавливаем кодировку и вывод всех ошибок
header('Content-Type: text/html; charset=UTF8');
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

ob_start();

$mode = isset($_GET['mode'])  ? $_GET['mode'] : false;
$user = isset($_SESSION['user']) ? $_SESSION['user'] : false;
$err = array();

define('BEZ_KEY', true);

require_once dirname(__FILE__) . '/inc/config.php';
require_once dirname(__FILE__) . '/inc/func/funct.php';
require_once dirname(__FILE__) . '/inc/bd/bd.php';

switch($mode)
{
    case 'reg':
    require_once dirname(__FILE__) . '/scripts/reg/reg.php';
    require_once dirname(__FILE__) . '/scripts/reg/reg_form.html';
    break;

    case 'auth':
    require_once dirname(__FILE__) . '/scripts/auth/auth.php';
    require_once dirname(__FILE__) . '/scripts/auth/auth_form.html';
    require_once dirname(__FILE__) . '/scripts/auth/show.php';
    break;

}

$content = ob_get_contents();
ob_end_clean();

	if (isset($_GET['logout'])) {
		session_destroy();
		header('location: /' );
	}
    if($user) {
		require_once dirname(__FILE__) . '/templates/index.php';
	} else {
		require_once dirname(__FILE__) . '/templates/pages/auth_form.php';
	}

?>
