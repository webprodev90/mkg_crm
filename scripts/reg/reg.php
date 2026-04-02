<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	


require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';
	
	
     //Ключ защиты
    /* if(!defined('BEZ_KEY'))
     {
         header("HTTP/1.1 404 Not Found");
         exit(file_get_contents('/404.html'));
     }*/

     //Выводим сообщение об удачной регистрации
     if(isset($_GET['status']) and $_GET['status'] == 'ok')
        echo '<b>Вы успешно зарегистрировались! Пожалуйста активируйте свой аккаунт!</b>';

     //Выводим сообщение об удачной регистрации
     if(isset($_GET['active']) and $_GET['active'] == 'ok')
        echo '<b>Ваш аккаунт на успешно активирован!</b>';

     //Производим активацию аккаунта
     if(isset($_GET['key']))
     {
        //Проверяем ключ
		
        //$sql = 'SELECT *
        //        FROM `'. BEZ_DBPREFIX .'reg`
        //        WHERE `active_hex` = "'. escape_str($_GET['key']) .'"';
        //$res = mysqlQuery($sql);
		
		$res = $db_connect->query('SELECT * 
									 FROM `'. BEZ_DBPREFIX .'reg` 
									WHERE `active_hex` = "?s"',  /*escape_str(*/$_GET['key']/*)*/ );

        if($res->getNumRows() == 0)
			if($res == 0)
            $err[] = 'Ключ активации не верен!';

        //Проверяем наличие ошибок и выводим пользователю
        if(count($err) > 0)
            echo showErrorMessage($err);
        else
        {
            //Получаем адрес пользователя
            $row = $res->fetchAssoc();
            $email = $row['login'];

            //Активируем аккаунт пользователя
            /*$sql = 'UPDATE `'. BEZ_DBPREFIX .'reg`
                    SET `status` = 1
                    WHERE `login` = "'. $email .'"';
            $res = mysqlQuery($sql);*/

			$res = $db_connect->query('UPDATE `'. BEZ_DBPREFIX .'reg`
									      SET `status` = 1
										WHERE `login` = "'. $email .'"');





            //Отправляем письмо для активации
            $title = 'Ваш аккаунт в crm успешно активирован';
            $message = 'Поздравляю Вас, Ваш аккаунт в crm успешно активирован';

            //sendMessageMail($email, BEZ_MAIL_AUTOR, $title, $message);

            //Перенаправляем пользователя на
            //нужную нам страницу
            header('Location:'. BEZ_HOST .'/scripts/reg/?mode=reg&active=ok');
            exit;
        }
     }
     //Если нажата кнопка на регистрацию,
     //начинаем проверку
     if(isset($_POST['submit']))
     {
        //Утюжим пришедшие данные
        if(empty($_POST['email'])){
            $err[] = 'Поле Email не может быть пустым!';
		}

        if(empty($_POST['pass']))
            $err[] = 'Поле Пароль не может быть пустым';

        if(empty($_POST['pass2']))
            $err[] = 'Поле Подтверждения пароля не может быть пустым';

        //Проверяем наличие ошибок и выводим пользователю
       /* if(count($err) > 0)
            echo print_r($err);
        else
        {
            //Продолжаем проверять введеные данные
            //Проверяем на совподение пароли
            if($_POST['pass'] != $_POST['pass2'])
                $err[] = 'Пароли не совподают';

            //Проверяем наличие ошибок и выводим пользователю
            if(count($err) > 0)
                echo print_r($err);
            else
            {*/
                //Проверяем существует ли у нас
                //такой пользователь в БД
			    $res = $db_connect->query('SELECT * 
										     FROM `bez_reg` 
										    WHERE `login` = "'. /*escape_str(*/$_POST['email']/*)*/ .'"' 
									      );
										  
                if($res->getNumRows() > 0)
                    $err[] = 'К сожалению Логин: <b>'. $_POST['email'] .'</b> занят!';

                //Проверяем наличие ошибок и выводим пользователю
               /* if(count($err) > 0)
                    echo print_r($err);
                else
                {*/
                    //Получаем ХЕШ соли
                    $salt = salt();

                    //Солим пароль
                    $pass = md5(md5($_POST['pass']).$salt);

                    //Если все хорошо, пишем данные в базу
                    $res = $db_connect->query('INSERT INTO `'. BEZ_DBPREFIX .'reg`
											   VALUES(
														"",
														"'. /*escape_str(*/$_POST['email']/*)*/ .'",
														"'. $pass .'",
														"'. $salt .'",
														"'. md5($salt) .'",
														0
														)');

                    //Отправляем письмо для активации
                    $url = BEZ_HOST .'/scripts/reg/?mode=reg&key='. md5($salt);
                    $title = 'Регистрация на http://bezramok-tlt.ru';
                    $message = 'Для активации Вашего акаунта пройдите по ссылке
                    <a href="'. $url .'">'. $url .'</a>';

                    sendMessageMail($_POST['email'], BEZ_MAIL_AUTOR, $title, $message);

                    //Сбрасываем параметры
                    header('Location:'. BEZ_HOST .'/scripts/reg/?mode=reg&status=ok');
                    exit;
              /*  }
            }
        }*/
     }

    ?>