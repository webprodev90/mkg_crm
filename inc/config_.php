<?php
/*
     if(!defined('BEZ_KEY'))
     {
         header("HTTP/1.1 404 Not Found");
         exit(file_get_contents('/page-404.html'));
     }
*/
     //Адрес базы данных
     define('BEZ_DBSERVER','localhost');
     //Логин БД
     define('BEZ_DBUSER','u375143_crmdev');
     //Пароль БД
     define('BEZ_DBPASSWORD','mS0bM5wG5q');
     //БД
     define('BEZ_DATABASE','u375143_crmdev');
     //Префикс БД
     define('BEZ_DBPREFIX','bez_');
     //Errors
     define('BEZ_ERROR_CONNECT','Немогу соеденится с БД');
     //Errors
     define('BEZ_NO_DB_SELECT','Данная БД отсутствует на сервере');
     //Адрес хоста сайта
     define('BEZ_HOST','https://'. $_SERVER['HTTP_HOST'] .'/');
	 //Корневая директория
     define('BEZ_DOCR', $_SERVER['DOCUMENT_ROOT']);
     //Адрес почты от кого отправляем
     define('BEZ_MAIL_AUTOR','Регистрация на https://webprodev.ru <no-reply@webprodev.ru>');
     ?>
