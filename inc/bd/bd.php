<?php
     /*
     if(!defined('BEZ_KEY'))
     {
         header("HTTP/1.1 404 Not Found");
         exit(file_get_contents('/404.html'));
     }
	 
	 */ 

     //Соединение с БД MySQL
	if (!class_exists('Krugozor\Database\Statement', false)) {
		require_once BEZ_DOCR . '/inc/database/Statement.php';
	}
	if (!class_exists('Krugozor\Database\Mysql', false)) {
		require_once BEZ_DOCR . '/inc/database/Mysql.php';
	}
	if (!class_exists('Krugozor\Database\MySqlException', false)) {
		require_once BEZ_DOCR . '/inc/database/MySqlException.php';
	}

	use Krugozor\Database\Mysql;
	use Krugozor\Database\Statement;
	use Krugozor\Database\MySqlException;

	$db_connect = Mysql::create(BEZ_DBSERVER, BEZ_DBUSER, BEZ_DBPASSWORD) or die(BEZ_ERROR_CONNECT);
    $db_connect
        // Язык вывода ошибок - русский
        ->setErrorMessagesLang('ru')
        // Выбор базы данных
        ->setDatabaseName(BEZ_DATABASE)
        // Выбор кодировки
        ->setCharset('utf8')
        // Включим хранение исполненных запросов для отчета/отладки/статистики
        ->setStoreQueries(true);

    $res_timezone = 0;
    $timezone_value = 0;
    $sel_timezone = $db_connect->query("
        SELECT number_value FROM options WHERE param_name = 'TIMEZONE';  
    ");
    $res_timezone = $sel_timezone->fetchAssoc();
    $timezone_value = $res_timezone['number_value'];

    define("TIMEZONE", $timezone_value);
	
	//$db_connect2 = mysqli_connect( BEZ_DBSERVER, BEZ_DBUSER, BEZ_DBPASSWORD ) or die(BEZ_ERROR_CONNECT);	
		
		//echo'<pre>'; print_r($db_connect); echo'</pre>';//die();


     //define('BEZ_CONNECT', $db_connect);
	 
	 
	 

     //mysql_select_db( BEZ_DATABASE, BEZ_CONNECT )or die(BEZ_NO_DB_SELECT);

     //Устанавливаем кодировку UTF8
     /*mysql_query ("SET NAMES utf8");
     mysql_query ("set character_set_client='utf8'");
     mysql_query ("set character_set_results='utf8'");
     mysql_query ("set collation_connection='utf8_general_ci'");*/
     ?>