<?
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

$source_request = '';

if(!empty($_POST['source'])) {
	$source_request = $_POST['source'];
}

$region_request = '';

if(!empty($_POST['region'])) {
	$region_request = $_POST['region'];
}

$no_check_duplicates = false;

if(!empty($_POST['no-check-duplicates'])) {
	if(isset($_POST['no-check-duplicates'])) {
		$no_check_duplicates = true;
	}
}

// Название <input type="file">
$input_name = 'file';

// Разрешенные расширения файлов.
$allow = array();

// Запрещенные расширения файлов.
$deny = array(
	'phtml',
	'php',
	'php3',
	'php4',
	'php5',
	'php6',
	'php7',
	'phps',
	'cgi',
	'pl',
	'asp',
	'aspx',
	'shtml',
	'shtm',
	'htaccess',
	'htpasswd',
	'ini',
	'log',
	'sh',
	'js',
	'html',
	'htm',
	'css',
	'sql',
	'spl',
	'scgi',
	'fcgi'
);

// Директория куда будут загружаться файлы.
$path = $_SERVER['DOCUMENT_ROOT'] . '/basep/';

if(isset($_FILES[$input_name])) {
	// Проверим директорию для загрузки.
	if(!is_dir($path)) {
		mkdir($path, 0777, true);
	}

	// Преобразуем массив $_FILES в удобный вид для перебора в foreach.
	$files = array();
	$diff = count($_FILES[$input_name]) - count($_FILES[$input_name], COUNT_RECURSIVE);
	if($diff == 0) {
		$files = array($_FILES[$input_name]);
	} else {
		foreach($_FILES[$input_name] as $k => $l) {
			foreach($l as $i => $v) {
				$files[$i][$k] = $v;
			}
		}
	}

	foreach($files as $file) {
		$error = $success = '';

		// Проверим на ошибки загрузки.
		if(!empty($file['error']) || empty($file['tmp_name'])) {
			switch(@$file['error']) {
				case 1:
				case 2:
					$error = 'Превышен размер загружаемого файла.';
					break;
				case 3:
					$error = 'Файл был получен только частично.';
					break;
				case 4:
					$error = 'Файл не был загружен.';
					break;
				case 6:
					$error = 'Файл не загружен - отсутствует временная директория.';
					break;
				case 7:
					$error = 'Не удалось записать файл на диск.';
					break;
				case 8:
					$error = 'PHP-расширение остановило загрузку файла.';
					break;
				case 9:
					$error = 'Файл не был загружен - директория не существует.';
					break;
				case 10:
					$error = 'Превышен максимально допустимый размер файла.';
					break;
				case 11:
					$error = 'Данный тип файла запрещен.';
					break;
				case 12:
					$error = 'Ошибка при копировании файла.';
					break;
				default:
					$error = 'Файл не был загружен - неизвестная ошибка.';
					break;
			}
		} elseif($file['tmp_name'] == 'none' || !is_uploaded_file($file['tmp_name'])) {
			$error = 'Не удалось загрузить файл.';
		} else {
			// Оставляем в имени файла только буквы, цифры и некоторые символы.
			$pattern = "[^a-zа-яё0-9,~!@#%^-_\$\?\(\)\{\}\[\]\.]";
			$name = mb_eregi_replace($pattern, '-', $file['name']);
			$name = mb_ereg_replace('[-]+', '-', $name);

			// Т.к. есть проблема с кириллицей в названиях файлов (файлы становятся недоступны).
			// Сделаем их транслит:
			$converter = array(
				'а' => 'a',
				'б' => 'b',
				'в' => 'v',
				'г' => 'g',
				'д' => 'd',
				'е' => 'e',
				'ё' => 'e',
				'ж' => 'zh',
				'з' => 'z',
				'и' => 'i',
				'й' => 'y',
				'к' => 'k',
				'л' => 'l',
				'м' => 'm',
				'н' => 'n',
				'о' => 'o',
				'п' => 'p',
				'р' => 'r',
				'с' => 's',
				'т' => 't',
				'у' => 'u',
				'ф' => 'f',
				'х' => 'h',
				'ц' => 'c',
				'ч' => 'ch',
				'ш' => 'sh',
				'щ' => 'sch',
				'ь' => '',
				'ы' => 'y',
				'ъ' => '',
				'э' => 'e',
				'ю' => 'yu',
				'я' => 'ya',

				'А' => 'A',
				'Б' => 'B',
				'В' => 'V',
				'Г' => 'G',
				'Д' => 'D',
				'Е' => 'E',
				'Ё' => 'E',
				'Ж' => 'Zh',
				'З' => 'Z',
				'И' => 'I',
				'Й' => 'Y',
				'К' => 'K',
				'Л' => 'L',
				'М' => 'M',
				'Н' => 'N',
				'О' => 'O',
				'П' => 'P',
				'Р' => 'R',
				'С' => 'S',
				'Т' => 'T',
				'У' => 'U',
				'Ф' => 'F',
				'Х' => 'H',
				'Ц' => 'C',
				'Ч' => 'Ch',
				'Ш' => 'Sh',
				'Щ' => 'Sch',
				'Ь' => '',
				'Ы' => 'Y',
				'Ъ' => '',
				'Э' => 'E',
				'Ю' => 'Yu',
				'Я' => 'Ya',
			);

			$name = strtr($name, $converter);
			$parts = pathinfo($name);

			if(empty($name) || empty($parts['extension'])) {
				$error = 'Недопустимое тип файла';
			} elseif(!empty($allow) && !in_array(strtolower($parts['extension']), $allow)) {
				$error = 'Недопустимый тип файла';
			} elseif(!empty($deny) && in_array(strtolower($parts['extension']), $deny)) {
				$error = 'Недопустимый тип файла';
			} else {
				// Чтобы не затереть файл с таким же названием, добавим префикс.
				$i = 0;
				$prefix = '';
				while(is_file($path . $parts['filename'] . $prefix . '.' . $parts['extension'])) {
					$prefix = '(' . ++$i . ')';
				}
				$name = $parts['filename'] . $prefix . '.' . $parts['extension'];

				// Перемещаем файл в директорию.
				if(move_uploaded_file($file['tmp_name'], $path . $name)) {
					// Далее можно сохранить название файла в БД и т.п.
					$success = 'Файл «' . $name . '» успешно загружен.';
				} else {
					$error = 'Не удалось загрузить файл.';
				}
			}
		}

		// Выводим сообщение о результате загрузки.
		if(empty($success)) {
			echo '<p>' . $error . '</p>';
		}
	}
}


$fh = fopen($_SERVER['DOCUMENT_ROOT'] . '/basep/' . $name, "r");
//fgetcsv($fh, 0, ',');

// массив, в который данные будут сохраняться
$data = [];
while(($row = fgetcsv($fh, 0, ';')) !== false) {
	$row = mb_convert_encoding($row, 'UTF-8', 'windows-1251');
	$fio = $row[1] ?? '';
	$phone = $row[0] ?? '';
	$city = $row[2] ?? '';
	$comment = $row[3] ?? '';
	#$status = $row[3] ?? '';
	#$source = $row[4] ?? '';

	$data[] = [
		'fio' => $fio,
		'phone' => $phone,
		'city' => $city,
		'comment' => $comment,
		#'status' => $status,
		#'source' => $source,
	];
}

// теперь в массиве $data расположены все элементы из CSV-файла
$double_phones = [];
$unique_data = [];

/*foreach($data as $row) {

	$i_fio = trim($row['fio']);
	$i_phone_number = $row['phone'];
	$i_city = $row['city'];
	$comment = $row['comment'];
	# $i_status = $row['status'];
	#$i_source = $row['source'];
	$i_date_create = date("Y-m-d");

	$i_sql = 'INSERT INTO bez_unprocessed_base
		(fio, phone_number, vopros, city, status, source, date_create, city_group, group_source)
		VALUES (
		"' . $i_fio . '", "' . $i_phone_number . '", "' . $comment . '",
		"' . $i_city . '", "10", "' . $source_request . '", "' . $i_date_create . '",
		"' . $city_group . '", "' . $group_requests . '"
	)';

	# $db_connect->query($i_sql);
	if($no_check_duplicates) {
		$db_connect->query($i_sql);
	} else {
		$clean_phone = clean_phone_number($i_phone_number);
		$double = is_dublicate($clean_phone);			
		if($double != NULL) {
			$double_phones[] = $double;
		} else {
			$db_connect->query($i_sql);
		}		
	}

}*/


foreach($data as $row) {

	$i_fio = trim($row['fio']);
	$i_phone_number = $row['phone'];
	$i_city = $row['city'];
	$comment = $row['comment'];
	# $i_status = $row['status'];
	#$i_source = $row['source'];
	$i_date_create = date("Y-m-d");
	$clean_phone = clean_phone_number($i_phone_number);
	
	
	if($no_check_duplicates) {
		$city_group = get_city_group($region_request);
		$group_requests = get_group_request($source_request, count($data));	
		$i_sqld = 'INSERT INTO bez_unprocessed_base
			(fio, phone_number, vopros, city, status, source, date_create, city_group, group_source)
			VALUES (
			"' . $i_fio . '", "' . $clean_phone . '", "' . $comment . '",
			"' . $i_city . '", "10", "' . $source_request . '", "' . $i_date_create . '",
			"' . $city_group . '", "' . $group_requests . '"
		)';		
		$db_connect->query($i_sqld);
	} else {	
		$i_sql0 = 'INSERT INTO bez_unprocessed_base_dubl
			(fio, phone_number, vopros, city)
			VALUES (
			"' . $i_fio . '", "' . $clean_phone . '", "' . $comment . '", "' . $i_city . '"
			)';
		$db_connect->query($i_sql0);	
	}
}
	if($no_check_duplicates) {
	} else {	
		$city_group = get_city_group($region_request);
		$group_requests = get_group_request($source_request, count($data));	
		$double_phones = is_dublicate($clean_phone,$source_request,$city_group,$group_requests);
		$unique_data = is_unique($clean_phone,$source_request,$city_group,$group_requests);
	}

function clean_phone_number($phone_number) {
	$cleaned_phone_number = preg_replace('/\D/', '', $phone_number);

	if(preg_match('/^[87]/', $cleaned_phone_number)) {
		$cleaned_phone_number = substr($cleaned_phone_number, 1);
	}

	return $cleaned_phone_number;
}

function get_group_request($source, $count_requests) {
	global $db_connect;
	$count_requests += 1;
	$query = $db_connect->query("
			INSERT INTO bez_group_request
			(name, count)
			VALUES
			('{$source}', '{$count_requests}')
		");

	$query = $db_connect->query("
		SELECT MAX(id) as id FROM bez_group_request
	");

	$result = $query->fetchAssoc();
	return $result['id'];
}

function get_city_group($city) {
	global $db_connect;

	$query = $db_connect->query("
		SELECT id, name FROM bez_cities_group where name='{$city}' LIMIT 1
	");

	$result = $query->fetchAssoc();

	if($result == null) {
		$db_connect->query("
			INSERT INTO bez_cities_group
			(name)
			VALUES
			('{$city}')
		");

		$query = $db_connect->query("
			SELECT MAX(id) as max_id, name FROM bez_cities_group
		");

		$result = $query->fetchAssoc();
		return $result['max_id'];

	} else {
		return $result['id'];
	}
}

function is_dublicate($clean_phone,$source_request,$city_group,$group_requests) {
	global $db_connect;

	$double_phones1 = [];

	$filter = "
		WHEN LEFT(REGEXP_REPLACE(b.phone_number, '[^0-9]', ''), 1) IN ('8', '7')
		THEN SUBSTRING(REGEXP_REPLACE(b.phone_number, '[^0-9]', ''), 2)
		ELSE REGEXP_REPLACE(b.phone_number, '[^0-9]', '')";
		
	$query = "
	  SELECT b.fio, bd.phone_number as phone_number, b.source, b.vopros
		FROM bez_unprocessed_base_dubl bd
		JOIN bez_unprocessed_base b
		  on CASE
			{$filter}
			END = bd.phone_number
	   WHERE b.source IS NOT NULL
	   GROUP BY phone_number
	";

	$res = $db_connect->query($query);
	
	while($row = $res->fetchAssoc()) {
            $double_phones1[] = $row;
        
	/*$i_sql2 = 'INSERT INTO bez_unprocessed_base_dubl
		(fio, phone_number, vopros, city)
		VALUES (
		"' . $row['fio'] . '", "' . $row['phone_number'] . '", "' . $row['source'] . '", "' . $row['vopros'] . '"
     	)';
	$db_connect->query($i_sql2);*/
	}		

	return $double_phones1;
}

function is_unique($clean_phone,$source_request,$city_group,$group_requests) {
	global $db_connect;

	$unique_data1 = [];
	
	$query2 = "
			with wt_data as (
				  SELECT phone_number, max(fio) as fio, max(source) as source, max(vopros) as vopros, max(city) as city
                    FROM bez_unprocessed_base_dubl bd
                   GROUP BY phone_number
			), wt_data2 as (
				  SELECT bd.phone_number as phone_number
					FROM wt_data bd
					LEFT
					JOIN bez_unprocessed_base b
					  ON CASE
						  WHEN LEFT(REGEXP_REPLACE(b.phone_number, '[^0-9]', ''), 1) IN ('8', '7')
						  THEN SUBSTRING(REGEXP_REPLACE(b.phone_number, '[^0-9]', ''), 2)
						  ELSE REGEXP_REPLACE(b.phone_number, '[^0-9]', '')
						END = bd.phone_number
				   WHERE b.source IS NOT NULL
				   group by bd.phone_number
			)
			Select wb.fio, wb.phone_number, wb.source, wb.vopros, wb.city
		      from wt_data wb
              LEFT 
              JOIN wt_data2 wbd ON wbd.phone_number = wb.phone_number
             WHERE wbd.phone_number IS NULL  
	";

	$res2 = $db_connect->query($query2);

	while($row3 = $res2->fetchAssoc()) {

		$i_date_create = date("Y-m-d");

		$unique_data1[] = $row3;

		$i_sql = 'INSERT INTO bez_unprocessed_base
			(fio, phone_number, vopros, city, status, source, date_create, city_group, group_source)
			VALUES (
			"' . $row3['fio'] . '", "' . $row3['phone_number'] . '", "' . $row3['vopros'] . '",
			"' . $row3['city'] . '", "10", "' . $source_request . '", "' . $i_date_create . '",
			"' . $city_group . '", "' . $group_requests . '"
		)';

		$db_connect->query($i_sql);

	}
	$i_sql2 = 'TRUNCATE bez_unprocessed_base_dubl';
	
	$db_connect->query($i_sql2);	

	return $unique_data1;
}



$filepath = $_SERVER['DOCUMENT_ROOT'] . '/basep/' . $name;
unlink($filepath);
// foreach($double_phones as $row) {
// 	var_dump($row);
// 	exit;
// }
$dublicates_count = count($double_phones);
?>


	<h2>Загружено
		<?= count($unique_data);?> из <?= count($data); ?>
	</h2>
<?php if(!$no_check_duplicates): ?>		
	<h2>Дубликаты
		<?= $dublicates_count; ?>
	</h2>
<?php endif; ?>
<?php if(!$no_check_duplicates and count($double_phones) !== 0): ?>	
	<style>
		table {
			border-collapse: collapse;
		}

		table,
		th,
		td {
			border: 1px solid #ccc;
		}

		th,
		td {
			padding: 5px;
		}

		th {
			background-color: #4CAF50;
			color: white;
		}
	</style>

	<table>
		<thead>
			<tr>
				<th>Номер</th>
				<th>Имя</th>
				<th>Источник</th>
				<th>Вопрос</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($double_phones as $row): ?>
				<tr>
					<td>
						<?= $row['phone_number']; ?>
					</td>
					<td>
						<?= $row['fio']; ?>
					</td>
					<td>
						<?= $row['source']; ?>
					</td>
					<td>
						<?= $row['vopros']; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>

<?php 
	if(count($unique_data) !== 0) {
		$delimiter = ";"; 
		$buffer = fopen(__DIR__ . '/uploads/unique_data.csv', 'w'); 
		// Добавляет в начало файла метку BOM, благодаря этому файл откроется в Excel с нормальной кодировкой.
		fputs($buffer, chr(0xEF) . chr(0xBB) . chr(0xBF));
		// Данные в файл csv
		foreach($unique_data as $row){ 
			$lineData = array(trim('8'.$row['phone_number']), trim($row['fio']), trim($row['city']), trim($row['vopros'])); 
			fputcsv($buffer, $lineData, $delimiter); 
		} 
		fclose($buffer); 
		echo '<script>location.replace("https://crm.mkggroup.ru/basep/uploads/unique_data.csv");</script>';
		exit();
	}

?>