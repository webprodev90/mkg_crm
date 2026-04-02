<?php
     //Ключ защиты
     if(!defined('BEZ_KEY'))
     {
         header("HTTP/1.1 404 Not Found");
         exit(file_get_contents('/404.html'));
     }
	 
     //Если нажата кнопка то обрабатываем данные
     if(isset($_POST['submit']))
     {
        if(empty($_POST['email']))
            $err[] = 'Не введен Логин';

        if(empty($_POST['pass']))
            $err[] = 'Не введен Пароль';

        //Проверяем наличие ошибок и выводим пользователю
        if(count($err) > 0)
			$_SESSION['err'] = $err;
        else
        {
            //Создаем запрос на выборку из базы
            //данных для проверки подлиности пользователя
			$res = $db_connect->query('SELECT * 
										 FROM `'. BEZ_DBPREFIX .'reg` 
										WHERE `user` = "'. /*escape_str(*/$_POST['email']/*)*/ .'" 
										  AND `status` = 1');		


			//print_r($res->getNumRows());die();

            //Если логин совподает, проверяем пароль
            if($res->getNumRows() > 0)
            {
                //Получаем данные из таблицы
                $row = $res->fetchAssoc();

                if(md5(md5($_POST['pass']).$row['salt']) == $row['pass'])
                {
                    $_SESSION['user'] = $row['user'];
                    $_SESSION['login'] = $row['login'];
                    $_SESSION['login_id'] = $row['id'];
                    $_SESSION['login_role'] = $row['role'];
                    $_SESSION['id_otdel'] = $row['id_otdel'];
                    $_SESSION['id_atc'] = $row['id_atc'];
					$oper_date_start = date("Y-m-d");
					$oper_date_end = date("Y-m-d");	
                    if($row['id_atc'] > 0) {
                        require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config3.php';
                        require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd3.php';

                        $res2 = $db_connect3->query('SELECT `number` FROM `connections` WHERE vnum = "' . $row['id_atc'] . '" ORDER BY `id` DESC LIMIT 1');
                        $row2 = $res2->fetchAssoc();
                        if($res2->getNumRows() > 0) { 
                            $_SESSION['phone_number'] = $row2['number'];
                        } else {
                            $_SESSION['phone_number'] = '0';
                        }   
                    }
					
					$db_connect->query('update `settings` 
												 set  date_value = "' . $oper_date_start . '" 
												WHERE name_value = "oper_date_start"
												  AND login_id = "' . $_SESSION['login_id'] . '" '
											
											  );

					$db_connect->query('update `settings` 
												 set  date_value = "' . $oper_date_end . '" 
												WHERE name_value = "oper_date_end"
												  AND login_id = "' . $_SESSION['login_id'] . '" '
											
											  );
											  
                    //Перебрасываем на главную страницу в зависимости от роли
					if ($_SESSION['login_role'] == "2" OR $_SESSION['login_role'] == "3") {
						header('Location:'. BEZ_HOST . 'templates/pages/unprocessed.php?p=1');
					} elseif ($_SESSION['login_role'] == "4" OR $_SESSION['login_role'] == "5" OR $_SESSION['login_role'] == "12") {	
						header('Location:'. BEZ_HOST . 'templates/pages/unprocessed-base-7.php?p=10');
					} elseif ($_SESSION['login_role'] == "10") {   
                        header('Location:'. BEZ_HOST . 'templates/pages/partners.php');
                    }  else {
						header('Location:'. BEZ_HOST . 'templates/pages/unprocessed-base.php?p=10');	
					}
                    exit;
                }
                else
					//$_SESSION['err'] = 'Неверный пароль!';
					showError('Неверный пароль!');
					
					header('Location:'. BEZ_HOST .'templates/pages/auth_form.php/?err=1');
					
            }
            else
				//$_SESSION['err'] = 'Логин <strong>'. $_POST['email'] .'</strong> не найден!';
				showError('Логин не найден!');
				header('Location:'. BEZ_HOST .'templates/pages/auth_form.php/?err=2');
        }




     }

    ?>