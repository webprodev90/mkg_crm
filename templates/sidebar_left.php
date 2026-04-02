<?

$res = $db_connect->query('SELECT `' . BEZ_DBPREFIX . 'reg`.`id`,
							      `' . BEZ_DBPREFIX . 'reg`.`name`,
							      `' . BEZ_DBPREFIX . 'reg`.`login`,
							      `' . BEZ_DBPREFIX . 'reg`.`user`,
								  `' . BEZ_DBPREFIX . 'reg`.`role`,
								  `' . BEZ_DBPREFIX . 'role`.`name_role`
							 FROM `' . BEZ_DBPREFIX . 'reg`
							 LEFT
							 JOIN `' . BEZ_DBPREFIX . 'role`
							   ON `' . BEZ_DBPREFIX . 'role`.`role_id` = `' . BEZ_DBPREFIX . 'reg`.`role`
							WHERE `login` = "' . $login . '"
							  AND `status` = 1');


$row = $res->fetchAssoc();

$res2 = $db_connect->query('SELECT *
							 FROM `balance`
							WHERE `user_id` = "' . $row['id'] . '"
							  ');


$row2 = $res2->fetchAssoc();
$balance = 0;
if(isset($row2['amount'])) {
    $balance = $row2['amount'];
}

$res3 = $db_connect->query('SELECT * FROM options WHERE param_name = "EXCELON"');
$opt = $res3->fetchAssoc(); 
?>
<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">

    <div class="d-flex justify-content-between">


        <!-- LOGO
                    <div class="topbar-left">
                        <a href="/index.html" class="logo">
                            <span>
                                <img src="/assets/images/logo1.png" alt="" height="22">
                            </span>
                            <i>
                                <img src="/assets/images/logo_sm.png" alt="" height="28">
                            </i>
                        </a>
                    </div>-->


			

        <!-- User box -->
        <!--
        <div class="user-box">
            <h5><a href="/">
                    <? echo $row['name']; ?>
                </a> </h5>
            <p class="text-muted">
                <? if($row['role'] == '6') {
                    echo 'Рекламодатель';
                } else {
                    echo $row['name_role'];
                } ?>
            </p>
            <? if($row['role'] == '6' or $row['login'] == 'bratva1990@yandex.ru') { ?>
                <span><b>
                        <? echo 'Баланс: '; ?>
                    </b><i>
                        <? echo $balance; ?>
                    </i></span>
            <? } ?>
        </div>
        -->

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <ul class="metismenu d-flex" id="side-menu">

                <!--<li class="menu-title">Navigation</li>-->
                <? if($row['role'] == '1' or $row['role'] == '4' or $row['role'] == '5' or $row['role'] == '10' or $row['role'] == '11' or $row['role'] == '12' or $row['role'] == '13') { ?>
					<li class="menu-item-1-0" title="Заявки"><a href="#">Заявки</a></li>
                    <? if( $row['role'] !== '5' and $row['role'] !== '4' and $row['role'] !== '10' and $row['role'] !== '12') { ?>
						<? if($row['id'] !== '132') { ?>
							<li class="menu-item-1" title="Заявки Телеграм"><a href="/templates/pages/unprocessed-base.php?p=10"><i class="fi-location"></i></a></li>
						<? } ?>
                    <? } ?>
                    <? if($row['role'] !== '11' and $row['role'] !== '10') { ?>
                        <? if($row['role'] === '1') { ?>
                                <li class="menu-item-1" title="Заявки Excel"><a href="/templates/pages/unprocessed-base-excel.php?p=10"><i class="fi-paper-stack"></i></a></li>
                        <? } ?>
                        <li class="menu-item-1" title="Рабочая Панель Оператора"><a href="/templates/pages/unprocessed-base-7.php?p=10"><i class="fi-stack"></i></a></li>
                        <? if($row['role'] !== '5') { ?>
                            <li class="menu-item-1" title="Трафик"><a href="/templates/pages/unprocessed-base-5.php?p=10"><i class="fi-stack-2"></i></a></li>
                        <? } ?>
                        <? if(($row['role'] == '1' or $opt['number_value'] == 1) and $row['role'] !== '5') { ?>
                            <li class="menu-item-1" title="Заявки Excel"><a href="/templates/pages/unprocessed-base-6.php?p=10"><i class="fi-layers"></i></a></li>
                        <? } ?>
                        <!-- <li class="menu-item-1" title="Заявки Excel"><a href="/templates/pages/unprocessed-base-1.php?p=10"><i class="fi-paper"></i></a></li> -->
                        <li class="menu-item-1" title="Холды"><a href="/templates/pages/holds.php"><i class="fi-shuffle"></i></a></li>                         
                        <li class="menu-item-1" title="Ручной прозвон"><a href="/templates/pages/unprocessed-base-4.php?p=10"><i class="fi-paper"></i></a></li>							
                        <li class="menu-item-1" title="Вторичка"><a href="/templates/pages/unprocessed-base-3.php?p=10"><i class="fi-repeat"></i></a></li>
                        <li class="menu-item-1" title="Рейтинг"><a href="/templates/pages/rating.php"><i class="fi-bar-graph"></i></a></li> 
                    <? } ?>                               
                        <? if($row['role'] === '1' or $row['role'] === '4' or $row['role'] === '10' or $row['role'] === '11' or $row['role'] === '12' or $row['role'] === '13') { ?>
                            <li class="menu-item-1" title="Партнеры"><a href="/templates/pages/partners.php"><i class="fi-briefcase"></i></a></li>
                        <? } ?>
                <? } ?>

                <? if($row['id'] == '347' or $row['id'] == '289'): ?>
                    <li class="menu-item-1" title="Трафик"><a href="/templates/pages/unprocessed-base-5.php?p=10"><i class="fi-stack-2"></i></a></li>
                <?php endif; ?>  

                <? if($row['role'] == '1' or $row['role'] == '4' or $row['role'] == '12' or $row['role'] == '13'): ?>
                    <li class="menu-item-1" title="Продажа лидов">
                        <a href="/templates/pages/lead-sales.php">
                            <i class="fi-skip-forward"></i>
                        </a>
                    </li>
                    <li class="menu-item-1" title="Аналитика трафика">
                        <a href="/templates/pages/analize-group-requests.php">
                            <i class="fi-bar-graph-2"></i>
                        </a>
                    </li>
                    <li class="menu-item-1" title="атс статистика"><a href="/templates/pages/atc_stat.php"><i class="fi-archive"></i></a></li>
                    <? if($row['role'] === '1' or $row['role'] === '12') { ?>
                        <li class="menu-item-1" title="Отправка в Астериск"><a href="/templates/pages/sending-asterisk.php"><i class="fi-upload"></i></a></li>
                    <? } ?>
                    <? if($row['role'] === '1' or $row['role'] === '4' or $row['role'] === '12' or $row['role'] === '13') { ?>
                        <li class="menu-item-1" title="Пульс КЦ"><a href="/templates/pages/pulse-call-center.php"><i class="fi-watch"></i></a></li>
                        <? if($row['role'] === '1' or $row['role'] === '12' or $row['role'] === '13') { ?>
                            <li class="menu-item-1" title="Показатели звонков"><a href="/templates/pages/call-rates.php"><i class="fi-bar-graph-2"></i></a></li>
                        <? } ?>
                    <? } ?>
                <?php endif; ?>

                <? if($row['role'] == '5'): ?>
                    <li class="menu-item-1" title="атс статистика"><a href="/templates/pages/atc_stat.php"><i class="fi-archive"></i></a></li>
                <?php endif; ?>

                <? if($row['id'] == '347' or $row['id'] == '289' or $row['id'] == '565'): ?>
                    <li class="menu-item-1" title="Продажа лидов">
                        <a href="/templates/pages/lead-sales.php">
                            <i class="fi-skip-forward"></i>
                        </a>
                    </li>
                <?php endif; ?>     
                
                <!-- <? if($row['role'] == '6' or $row['role'] == '1' or $row['login'] == 'bratva1990@yandex.ru') { ?>
                    <li id="menu-title-2" class="menu-title">Заявки <span class="menu-arrow-2">▽</span></li>
                    <li class="menu-item-2 d-none"><a href="/templates/pages/offers.php"><i class="fi-paper"></i> <span> Офферы </span></a></li>
                    <li class="menu-item-2 d-none"><a href="/templates/pages/unprocessed-base.php?p=10"><i class="fi-paper"></i> <span> Поток заявок
                            </span></a></li>
                    <li class="menu-item-2 d-none"><a href="/templates/pages/price.php"><i class="fi-paper"></i> <span> Регионы и цены </span></a></li>

                <? } ?>
                -->
                <? if($row['role'] == '1' or $row['role'] == '2' or $row['role'] == '3' or $row['role'] == '9') { ?>
					<li class="menu-item-3-0" title="Приходы"><a href="#">Приходы</a></li>
                    <li class="menu-item-3" title="Все"><a href="/templates/pages/unprocessed.php?p=99"><i class="fi-layers"></i></a></li>
                    <li class="menu-item-3" title="Заявки"><a href="/templates/pages/unprocessed.php?p=1"><i class="fi-paper"></i></a>
                    </li>
                    <li class="menu-item-3" title="Запись"><a href="/templates/pages/unprocessed.php?p=2"><i class="fi-paper"></i></a>
                    </li>
                    <? if($row['role'] == '1' || $row['role'] == '9') { ?>
                        <li class="menu-item-3" title="Подтверждён"><a href="/templates/pages/unprocessed.php?p=3"><i class="fi-paper"></i></a></li>
                    <? } ?>
                    <li class="menu-item-3" title="Приход"><a href="/templates/pages/unprocessed.php?p=4"><i class="fi-paper"></i></a>
                    </li>
                    <li class="menu-item-3" title="Слив"><a href="/templates/pages/unprocessed.php?p=5"><i class="fi-paper"></i></a></li>
                    <li class="menu-item-3" title="Созвон"><a href="/templates/pages/unprocessed.php?p=6"><i class="fi-paper"></i></a>
                    </li>
                    <li class="menu-item-3" title="БК"><a href="/templates/pages/unprocessed.php?p=7"><i class="fi-paper"></i></a></li>
                    <li class="menu-item-3" title="Недозвон"><a href="/templates/pages/unprocessed.php?p=8"><i class="fi-paper"></i></a>
                    </li>
                    <li class="menu-item-3" title="Брак"><a href="/templates/pages/unprocessed.php?p=9"><i class="fi-paper"></i></a></li>
                <? } ?>
            
                <? if($row['role'] == '1' or $row['role'] == '4') { ?>
					<li class="menu-item-4-0" title="Админ"><a href="#">Админ</a></li>
                    <li class="menu-item-4" title="Добавить пользователя"><a href="/templates/pages/users.php"><i class="fi-head"></i></a></li>
                   <!-- <li class="menu-item-4"><a href="/templates/pages/city.php"><i class="fi-map"></i></a></li> -->
                   <!-- <li class="menu-item-4"><a href="/templates/pages/addres.php"><i class="fi-location-2"></i></a></li> -->
                   <? if($row['role'] == '1') { ?>
                        <li class="menu-item-4" title="Добавить партнера"><a href="/templates/pages/partner.php"><i class="fi-circle-plus"></i></a></li>
                       <!-- <li class="menu-item-4"><a href="/templates/pages/cash.php"><i class="fi-paper"></i></a></li> -->
                        <!-- <li class="menu-item-4" title="Удалить записи загрузки"><a href="/templates/pages/records.php"><i class="fi-circle-cross"></i></a></li> -->
                        <li class="menu-item-4" title="Статистика"><a href="/templates/pages/statistic.php"><i class="fi-bar-graph-2"></i></a></li>
                        <li class="menu-item-4" title="Логи"><a href="javascript: void(0);"><i class="fi-share"></i></a></li>
                    <? } ?>
                    <li class="menu-item-4" title="Учет рабочего времени"><a href="/templates/pages/report_user.php"><i class="fi-clock"></i></a></li>
                <? } ?>
                <? if($row['role'] == '13') { ?>
                    <li class="menu-item-4-0" title="Админ"><a href="#">Админ</a></li>
                    <li class="menu-item-4" title="Добавить партнера"><a href="/templates/pages/partner.php"><i class="fi-circle-plus"></i></a></li>
                <? } ?>    
                <? if($row['role'] == '1' or $row['role'] == '10' or $row['role'] == '11' or $row['role'] == '13') { ?>
					<li class="menu-item-5-0" title="Продажи"><a href="#">Продажи</a></li>
                    <li class="menu-item-5" title="Лиды"><a href="/templates/pages/unprocessed-base-2.php?p=10"><i class="fi-head"></i></a></li>   
                    <li class="menu-item-5" title="Лидоруб"><a href="/templates/pages/lead-picker.php?p=10"><i class="fi-speech-bubble"></i></a></li>     
                    <li class="menu-item-5" title="Партнеры"><a href="/templates/pages/partners-plan.php"><i class="fi-contract"></i></a></li> 
                    <li class="menu-item-5" title="Статистика"><a href="/templates/pages/lead-statistics.php"><i class="fi-bar-graph"></i></a></li>
                    <li class="menu-item-5" title="Отгрузки"><a href="/templates/pages/shipped-leads.php"><i class="fi-reload"></i></a></li>
                <? } ?>
                <? if($row['role'] == '1' or $row['role'] == '4' or $row['role'] == '5') { ?>
					<li class="menu-item-6-0" title="Обучение"><a href="#">Обучение</a></li>
                    <li class="menu-item-6" title="Начальный курс обучения"><a href="/templates/pages/initial-training-course.php"><i class="fi-pie-graph"></i></a></li> 
                    <? if($row['role'] == '1' or $row['role'] == '4') { ?>        
                        <li class="menu-item-6" title="Статистика"><a href="/templates/pages/learning-statistics.php"><i class="fi-bar-graph"></i></a></li>    
                    <? } ?>            
                    <li class="menu-item-6" title="Скрипт"><a href="/templates/pages/script.php"><i class="fi-stack-2"></i></a></li>
                    <li class="menu-item-6" title="Кодекс МКГ"><a href="/templates/pages/regulations.php"><i class="fi-folder"></i></a></li>
                    <li class="menu-item-6" title="Банкротство физических лиц"><a href="/templates/pages/bankruptcy-of-individuals.php"><i class="fi-stack"></i></a></li>
                    <li class="menu-item-6" title="Работа с возражениями"><a href="/templates/pages/working-with-objections.php"><i class="fi-book"></i></a></li>
                <? } ?>
            </ul>

        </div>
        <!-- Sidebar -->
        <div class="right-menu">
            <ul>
                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle nav-user" data-toggle="dropdown" href="#" role="button"
                        aria-haspopup="false" aria-expanded="false">
                        <img src="/assets/images/users/avatar-1.jpg" alt="user" class="rounded-circle"> <span class="ml-1">
                            <? echo $row['user']; ?> 
                            <? if($_SESSION['id_atc'] != 0) { ?>
                                (<? echo $_SESSION['id_atc']; ?>)
                            <? } ?>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown ">

                        <!-- item
                                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                                            <i class="fi-head"></i> <span>Аккаунт</span>
                                        </a>-->

                        <!-- item
                                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                                            <i class="fi-cog"></i> <span>Настройки</span>
                                        </a>-->

                        <!-- item-->
                        <a id="modclick31" data-toggle="modal" data-target="#signup-modal31"
                            class="dropdown-item notify-item">
                            <i class="fi-help"></i> <span>Поддержка</span>
                        </a>

                        <!-- item-->
                        <a href="/?logout" class="dropdown-item notify-item">
                            <i class="fi-power"></i> <span>Выйти</span>
                        </a>

                    </div>
                </li>
            </ul>
        </div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->