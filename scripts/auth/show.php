<?php
     //Ключ защиты
     if(!defined('BEZ_KEY'))
     {
         header("HTTP/1.1 404 Not Found");
         exit(file_get_contents('/404.html'));
     }

     //Проверяем зашел ли пользователь
     if($user === false)
        echo '<h3>Доступ закрыт, Вы не вошли в систему!</h3>'."\n";

     if($user === true)
        echo '<h3>Поздравляю, Вы вошли в систему!</h3>'."\n";
     ?>