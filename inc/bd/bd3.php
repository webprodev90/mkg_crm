<?php

     //Соединение с БД MySQL
	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/database/Statement.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/database/Mysql.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/database/MySqlException.php';

	use Krugozor\Database\Mysql;
	use Krugozor\Database\Statement;
	use Krugozor\Database\MySqlException;

	$db_connect3 = Mysql::create(BEZ_DBSERVER2, BEZ_DBUSER2, BEZ_DBPASSWORD2) or die(BEZ_ERROR_CONNECT2);
    $db_connect3
        // Язык вывода ошибок - русский
        ->setErrorMessagesLang('ru')
        // Выбор базы данных
        ->setDatabaseName(BEZ_DATABASE2)
        // Выбор кодировки
        ->setCharset('utf8')
        // Включим хранение исполненных запросов для отчета/отладки/статистики
        ->setStoreQueries(true);


     ?>