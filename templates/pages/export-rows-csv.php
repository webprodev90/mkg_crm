<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';


if(isset($_POST['submit'])) {
	$select_rows = mb_substr($_POST['sel_rows'], 0, -1);
	$count_rows = $_POST['count_rows'];
	$rows_for_export = [];

   /*Если отметили хоть один чекбокс */
   if ($select_rows !== '') {

		$query1 = "SELECT * FROM bez_unprocessed_base
				    WHERE id in ({$select_rows})
				    ORDER BY id DESC";
		$res1 = $db_connect->query($query1);	
	
		while($row = $res1->fetchAssoc()) {
            $rows_for_export[] = $row;
        }						

   } /* Иначе по количеству записей */
   else {

		$query2 = "SELECT * FROM bez_unprocessed_base
					WHERE source IS NOT NULL AND source <> 'telegram' AND is_double IS NULL AND manual = ''
					ORDER BY id DESC 
					LIMIT {$count_rows}";
		$res2 = $db_connect->query($query2);	

		while($row = $res2->fetchAssoc()) {
            $rows_for_export[] = $row;
        }

   }

   	if(count($rows_for_export) !== 0) {
		$delimiter = ";"; 
		$buffer = fopen(__DIR__ . '/export/export_rows.csv', 'w'); 
		// Добавляет в начало файла метку BOM, благодаря этому файл откроется в Excel с нормальной кодировкой.
		fputs($buffer, chr(0xEF) . chr(0xBB) . chr(0xBF));
		// Данные в файл csv
		foreach($rows_for_export as $row){ 
			$lineData = array(trim($row['phone_number']), trim($row['fio']), trim($row['city']), trim($row['vopros'])); 
			fputcsv($buffer, $lineData, $delimiter); 
		} 
		fclose($buffer); 
		
	}

 	exit();
}
	/*			  
// Получаем URL предыдущей страницы
$redirect = $_SERVER['HTTP_REFERER'];

// Проверяем, что ссылка существует, и выполняем редирект
if($redirect) {
    header("Location: $redirect");
    exit();
}
*/
?>