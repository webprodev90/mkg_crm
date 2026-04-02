<?
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(!isset($_SESSION)) {
    session_start();
}
if(!defined('BEZ_KEY')) {
    define('BEZ_KEY', true);
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

if(isset($_GET['logout'])) {
    session_destroy();
    header('location: /');
}

$login = isset($_SESSION['login']) ? $_SESSION['login'] : false;
if($login == 'bratva1990@yandex.ru') {
    $surer = 'N';
} else {
    $surer = 'N';
}
//echo '<pre>'; print_r($_SESSION);echo '</pre>'; dir();
$_SESSION['debug'] = 'N';
$db_connect->query("UPDATE bez_reg SET online='" . time() . "' WHERE login='" . $login . "'");



?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>CRM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="https://mkggroup.ru/assets/images/logo.png">

    <!-- Custom box css -->
    <link href="/plugins/custombox/css/custombox.min.css" rel="stylesheet">

    <!-- Plugins css -->
    <link href="/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <link href="/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="/plugins/clockpicker/css/bootstrap-clockpicker.min.css" rel="stylesheet">
    <link href="/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="/plugins/datatables/dataTables.dataTables.css" rel="stylesheet">
    <link href="/plugins/datatables/select.dataTables.css" rel="stylesheet">

    <!-- App css -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/metismenu.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/style.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/main.css" rel="stylesheet" type="text/css" />

	<link href="https://cdn.jsdelivr.net/gh/Alaev-Co/snowflakes/dist/snow.min.css" rel="stylesheet">
</head>

<body class="account-pages">