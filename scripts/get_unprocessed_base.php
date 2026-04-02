<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

function get_unprocessed_base() {
    global $db_connect;

    $res = $db_connect->query('SELECT `' . BEZ_DBPREFIX . 'unprocessed_base`.`id`,
        `' . BEZ_DBPREFIX . 'unprocessed_base`.`fio`,
        `' . BEZ_DBPREFIX . 'unprocessed_base`.`phone_number`,
        `' . BEZ_DBPREFIX . 'unprocessed_base`.`vopros`,
        `' . BEZ_DBPREFIX . 'unprocessed_base`.`city`,
        `' . BEZ_DBPREFIX . 'unprocessed_base`.`partner`,
        `' . BEZ_DBPREFIX . 'unprocessed_base`.`status`,
        `' . BEZ_DBPREFIX . 'unprocessed_base`.`date_create`,
        `' . BEZ_DBPREFIX . 'unprocessed_base`.`user_id`,
        `' . BEZ_DBPREFIX . 'unprocessed_base`.`timez`,
        `' . BEZ_DBPREFIX . 'reg`.`name` as operator_name,
        `' . BEZ_DBPREFIX . 'reg`.`id` as operator_id,
        `st_partner_s`.`partner_name`,
        `st_partner_s`.`id` as `partner_id`,
        `price`.`amount` as amount,
        `' . BEZ_DBPREFIX . 'status`.`status_name`

        FROM `' . BEZ_DBPREFIX . 'unprocessed_base` 							 
        LEFT
        JOIN `st_partner_s`
        ON `st_partner_s`.`id` = `' . BEZ_DBPREFIX . 'unprocessed_base`.`partner`	
        LEFT
        JOIN `price`
        ON `price`.`user_id` = `' . BEZ_DBPREFIX . 'unprocessed_base`.`partner`	
        AND `price`.`city_id` = (select `st_city_s`.id from `st_city_s` WHERE REPLACE(REPLACE(REPLACE(REPLACE(`' . BEZ_DBPREFIX . 'unprocessed_base`.`city`, " ", "" ), " ", "" ), "\r", ""), "\n", "") = REPLACE(REPLACE(REPLACE(REPLACE(`st_city_s`.`name_city`, " ", "" ), " ", "" ), "\r", ""), "\n", "")) 
        LEFT
        JOIN `' . BEZ_DBPREFIX . 'status`
        ON `' . BEZ_DBPREFIX . 'status`.`status_id` = `' . BEZ_DBPREFIX . 'unprocessed_base`.`status`								   
        LEFT
        JOIN `' . BEZ_DBPREFIX . 'reg`
        ON `' . BEZ_DBPREFIX . 'reg`.`id` = `' . BEZ_DBPREFIX . 'unprocessed_base`.`user_id`									   
        WHERE 1
        ORDER BY `' . BEZ_DBPREFIX . 'unprocessed_base`.`id` DESC LIMIT 1000
    ');

}