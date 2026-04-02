<?
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

$source_request = '';
//$start = microtime(true);

if(!empty($_POST['source'])) {
	$source_request = $_POST['source'];
}
/*
$region_request = '';

if(!empty($_POST['region'])) {
	$region_request = $_POST['region'];
}
*/
$no_check_duplicates = false;

if(!empty($_POST['no-check-duplicates'])) {
	if(isset($_POST['no-check-duplicates'])) {
		$no_check_duplicates = true;
	}
}

// Название <input type="file">
$input_name = 'file';

// Разрешенные расширения файлов.
$allow = array('csv');

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
$path = $_SERVER['DOCUMENT_ROOT'] . '/basep/download_excel/';

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


$fh = fopen($_SERVER['DOCUMENT_ROOT'] . '/basep/download_excel/' . $name, "r");
//fgetcsv($fh, 0, ',');

// массив, в который данные будут сохраняться
$data = [];
while(($row = fgetcsv($fh, 0, ';')) !== false) {
	$row = mb_convert_encoding($row, 'UTF-8', 'windows-1251');
	$city = $row[0] ?? '';
	$phone = $row[1] ?? '';
	$source = $row[2] ?? '';
	$status = $row[3] ?? '';
	$comment = $row[4] ?? '';
	
	

	$data[] = [
		'city' => $city,
		'phone' => $phone,
		'source' => $source,
		'status' => $status,
		'comment' => $comment
	];
}

// теперь в массиве $data расположены все элементы из CSV-файла
$double_phones = [];
$unique_data = [];
$phones_without_region = [];

// Записываем их в таблицу
foreach($data as $row) {
	$i_city = $row['city'];
	$i_phone_number = $row['phone'];
	$i_source = $row['source'];	
	$i_status = $row['status'];
	$comment = $row['comment'];
	$i_date_create = date("Y-m-d");


	$i_date_create = date("Y-m-d");
	$clean_phone = clean_phone_number($i_phone_number);
	global $phones_without_region;
	
    // Пропускаем запись, если номер некорректный
    if ($clean_phone === null) {
        error_log("Некорректный номер телефона: " . $i_phone_number);
        continue;
    }	

	$query_bez = "
			SELECT `bez_unprocessed_base`.id, `bez_unprocessed_base`.status
			  FROM `bez_unprocessed_base`
             WHERE `bez_unprocessed_base`.phone_number = " . $clean_phone;

	$res_bez = $db_connect->query($query_bez);

	while($res_bez1 = $res_bez->fetchAssoc()) {
		$i_sql0 = 'INSERT INTO bez_excel_update
			(unp_base_id, region, phone_number, source, status_id_in, status_id_out, comment, date_create)
			VALUES (
			"' . $res_bez1['id'] . '", "' . $i_city . '", "' . $clean_phone . '", "' . $i_source . '", "' . $res_bez1['status'] . '", "' . $i_status . '", "' . $comment . '", "' . $i_date_create . '"
			)';
		$db_connect->query($i_sql0);	
		$i_sql00 = 'UPDATE bez_unprocessed_base b SET b.status = "' . $i_status . '" WHERE b.id = ' . $res_bez1['id'];
		$db_connect->query($i_sql00);		
		
	}
	
	
	
}

//$unique_data = is_unique($clean_phone,$source_request,$group_requests);


function clean_phone_number($phone_number) {
    // Удаляем все нецифровые символы
    $cleaned = preg_replace('/\D/', '', $phone_number);
    
    // Если цифр нет — возвращаем null
    if (empty($cleaned)) {
        return null;
    }
    
    // Убираем префикс 8 или 7, если есть
    if (preg_match('/^[87]/', $cleaned)) {
        $cleaned = substr($cleaned, 1);
    }
    
    // Берём последние 10 цифр
    $cleaned = substr($cleaned, -10);
    
    // Если после всех операций номер пустой или не 10 цифр — возвращаем null
    if (strlen($cleaned) !== 10 || !ctype_digit($cleaned)) {
        return null;
    }
    
    return $cleaned;
}

function is_unique($clean_phone) {
	global $db_connect;

	$unique_data1 = [];
	
	$query2 = "
			with wt_data_0 as (
			SELECT f.phone_number, max(f.source) as source
			  FROM (
				  SELECT phone_number, source
                    FROM bez_unprocessed_base
				   UNION ALL
				  SELECT phone_number, source
                    FROM bez_unprocessed_base_excel
				) f	
               GROUP BY f.phone_number
			), wt_data as (
			     SELECT f.phone_number, max(f.fio) as fio, max(f.source) as source, max(f.vopros) as vopros, max(f.city) as city
                   FROM bez_unprocessed_base_dubl f
               GROUP BY f.phone_number
			), wt_data2 as (
				  SELECT bd.phone_number as phone_number, max(bd.source) as source
					FROM wt_data bd
					LEFT
					JOIN wt_data_0 b
					  ON CASE
						  WHEN LEFT(REGEXP_REPLACE(b.phone_number, '[^0-9]', ''), 1) IN ('8', '7')
						  THEN SUBSTRING(REGEXP_REPLACE(b.phone_number, '[^0-9]', ''), 2)
						  ELSE REGEXP_REPLACE(b.phone_number, '[^0-9]', '')
						END = CASE
						  WHEN LEFT(REGEXP_REPLACE(bd.phone_number, '[^0-9]', ''), 1) IN ('8', '7')
						  THEN SUBSTRING(REGEXP_REPLACE(bd.phone_number, '[^0-9]', ''), 2)
						  ELSE REGEXP_REPLACE(bd.phone_number, '[^0-9]', '')
						END
				   WHERE b.source IS NOT NULL
				   group by bd.phone_number
			)
			Select wb.fio, wb.phone_number, wbd.source, wb.vopros, wb.city
		      from wt_data wb
              LEFT 
              JOIN wt_data2 wbd ON wbd.phone_number = wb.phone_number
             WHERE wbd.phone_number IS NULL  
	";

	$res2 = $db_connect->query($query2);

	while($row3 = $res2->fetchAssoc()) {

		
		global $phones_without_region;

		$info_by_number = find_info_by_number($row3['phone_number']);

		$row3['operator_id'] = $info_by_number['operator_id'];
		$row3['operator_name'] = $info_by_number['operator_name'];
		$row3['region_name'] = $info_by_number['region_name'];

		if($info_by_number['city_id'] == 114) {
			$phones_without_region[] = $row3; 
		}

		$unique_data1[] = $row3;

		$i_sql = 'INSERT INTO bez_unprocessed_base_excel
			(fio, phone_number, vopros, city, status, source, date_create, auto_city_group, group_source, mobile_operator_id)
			VALUES (
			"' . $row3['fio'] . '", "' . $row3['phone_number'] . '", "' . $row3['vopros'] . '",
			"' . $row3['city'] . '", "10", "' . $source_request . '", "' . $i_date_create . '",
			"' . $info_by_number['city_id'] . '", "' . $group_requests . '", "' . $info_by_number['operator_id'] . '"
		)';

		$db_connect->query($i_sql);

	}
	

	return $unique_data1;
}



$filepath = $_SERVER['DOCUMENT_ROOT'] . '/basep/download_excel/' . $name;
unlink($filepath);

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();

?>







