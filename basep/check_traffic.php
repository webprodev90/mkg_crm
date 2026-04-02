<?
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

// Название <input type="file">
$input_name = 'file';

// Разрешенные расширения файлов.
$allow = array();

// Запрещенные расширения файлов.
$deny = array(
	'xlsx',
	'xls',
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

foreach($data as $row) {

	$i_fio = trim($row['fio']);
	$i_phone_number = $row['phone'];
	$i_city = $row['city'];
	$comment = $row['comment'];
	# $i_status = $row['status'];
	#$i_source = $row['source'];
	$i_date_create = date("Y-m-d");

	# $db_connect->query($i_sql);
	$clean_phone = clean_phone_number($i_phone_number);
	$i_sql = 'INSERT INTO bez_unprocessed_base_dubl
		(fio, phone_number, vopros, city)
		VALUES (
		"' . $i_fio . '", "' . $clean_phone . '", "' . $comment . '", "' . $i_city . '"
     	)';
	$db_connect->query($i_sql);	
	
	/*$double = is_dublicate($clean_phone);
	if($double != NULL) {
		$double_phones[] = $double;
	} */
}

$double_phones = is_dublicate($clean_phone);
$unique_data = is_unique($clean_phone);

function clean_phone_number($phone_number) {
	$cleaned_phone_number = preg_replace('/\D/', '', $phone_number);

	if(preg_match('/^[87]/', $cleaned_phone_number)) {
		$cleaned_phone_number = substr($cleaned_phone_number, 1);
	}

	return $cleaned_phone_number;
}
/*
function is_dublicate($clean_phone) {
	global $db_connect;

	$query = "
		SELECT
			id, fio, phone_number, source, vopros
		FROM bez_unprocessed_base
		WHERE
			phone_number like '%{$clean_phone}'
			AND bez_unprocessed_base.source IS NOT NULL
			LIMIT 1
	";

	$res = $db_connect->query($query);

	return $res->fetchAssoc();
}
*/
function is_dublicate($clean_phone) {
	global $db_connect;
	$double_phones1 = [];
	$filter = "
		WHEN LEFT(REGEXP_REPLACE(b.phone_number, '[^0-9]', ''), 1) IN ('8', '7')
		THEN SUBSTRING(REGEXP_REPLACE(b.phone_number, '[^0-9]', ''), 2)
		ELSE REGEXP_REPLACE(b.phone_number, '[^0-9]', '')";
	$filter2 = "
		WHEN LEFT(REGEXP_REPLACE(bd.phone_number, '[^0-9]', ''), 1) IN ('8', '7')
		THEN SUBSTRING(REGEXP_REPLACE(bd.phone_number, '[^0-9]', ''), 2)
		ELSE REGEXP_REPLACE(bd.phone_number, '[^0-9]', '')";		
	$query = "
		with wt_data_0 as (
			SELECT f.phone_number, max(f.source) as source, f.status_name
			  FROM (
				  SELECT phone_number, source, IFNULL(status_name, 'Не назначен') AS status_name
                    FROM bez_unprocessed_base
                    LEFT JOIN bez_status
                    ON bez_unprocessed_base.status = bez_status.status_id
				   UNION ALL
				  SELECT phone_number, source, IFNULL(status_name, 'Не назначен') AS status_name
                    FROM bez_unprocessed_base_excel
                    LEFT JOIN bez_status
                    ON bez_unprocessed_base_excel.status = bez_status.status_id
				) f	
               GROUP BY f.phone_number
			), wt_data as (
			     SELECT f.phone_number, max(f.fio) as fio, max(f.source) as source, max(f.vopros) as vopros, max(f.city) as city
                   FROM bez_unprocessed_base_dubl f
               GROUP BY f.phone_number   
			)	
	  SELECT bd.fio, bd.phone_number as phone_number, b.source, bd.vopros, bd.city, b.status_name
		FROM wt_data bd
		JOIN wt_data_0 b
		  on CASE
			{$filter}
			END = CASE
			{$filter2}
			END
	   WHERE b.source IS NOT NULL
	   GROUP BY phone_number
	";

	$res = $db_connect->query($query);
	
	while($row = $res->fetchAssoc()) {
            $double_phones1[] = $row;
	}
	
	return $double_phones1;
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
				  SELECT bd.phone_number as phone_number
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
			Select wb.fio, wb.phone_number, wb.source, wb.vopros, wb.city
		      from wt_data wb
              LEFT 
              JOIN wt_data2 wbd ON wbd.phone_number = wb.phone_number
             WHERE wbd.phone_number IS NULL  
	";

	$res2 = $db_connect->query($query2);

	while($row3 = $res2->fetchAssoc()) {

		$unique_data1[] = $row3;


	}
	$i_sql2 = 'TRUNCATE bez_unprocessed_base_dubl';
	
	$db_connect->query($i_sql2);	

	return $unique_data1;
}

$filepath = $_SERVER['DOCUMENT_ROOT'] . '/basep/' . $name;
unlink($filepath);

$dublicates_count = count($double_phones);
$dublicates_count_file = count($data) - (count($unique_data) + $dublicates_count);

?>
<?php 
	if(count($unique_data) !== 0) {
		$delimiter = ";"; 
		$buffer = fopen(__DIR__ . '/uploads/unique_data2.csv', 'w'); 
		// Добавляет в начало файла метку BOM, благодаря этому файл откроется в Excel с нормальной кодировкой.
		fputs($buffer, chr(0xEF) . chr(0xBB) . chr(0xBF));
		// Данные в файл csv
		foreach($unique_data as $row){ 
			$lineData = array(trim('8'.$row['phone_number']), trim($row['fio']), trim($row['city']), trim($row['vopros'])); 
			fputcsv($buffer, $lineData, $delimiter); 
		} 
		fclose($buffer); 
	}
	if(count($double_phones) !== 0) {
		$delimiter2 = ";"; 
		$buffer2 = fopen(__DIR__ . '/uploads/double_data2.csv', 'w'); 
		// Добавляет в начало файла метку BOM, благодаря этому файл откроется в Excel с нормальной кодировкой.
		fputs($buffer2, chr(0xEF) . chr(0xBB) . chr(0xBF));
		// Данные в файл csv
		foreach($double_phones as $row){ 
			$lineData2 = array(trim('8'.$row['phone_number']), trim($row['fio']), trim($row['source']), trim($row['city']), trim($row['vopros']), trim($row['status_name'])); 
			fputcsv($buffer2, $lineData2, $delimiter2); 
		} 
		fclose($buffer2); 
	}


?>

	<h2>Загружено
		<?= count($data); ?>
	</h2>
	<h2>Уникальных
		<?= count($unique_data);?>
	</h2>
		
<?php if(count($double_phones) !== 0): ?>		
	<h2>Дубликаты
		<?= $dublicates_count; ?>
	</h2>
<?php endif; ?>
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
		  /* скрываем все input[type="radio"], расположенные в .tab
		  .tab > input[type="radio"] {
			display: none;
		  }

		  /* скрываем все .tab-content */
		  .tab-content {
			display: none;
		  }

		  /* отображаем только тот контент, который соответствует отмеченной радоикнопки */
		  #tab-btn-1:checked~#content-1,
		  #tab-btn-2:checked~#content-2,
		  #tab-btn-3:checked~#content-3 {
			display: block;
		  }		
		  
.tab {
  display: flex;
  flex-wrap: wrap;
}

.tab > input[type="radio"] {
  display: none;
}

.tab-content {
  display: none;
  width: 100%;
  margin-top: 1rem;
}

#tab-btn-1:checked~#content-1,
#tab-btn-2:checked~#content-2,
#tab-btn-3:checked~#content-3 {
  display: block;
}

.tab > label {
  display: block;
  padding: 0.5rem 1rem;
  cursor: pointer;
  transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out;
  text-decoration: none;
  color: #0d6efd;
  border: 0;
  border-radius: 0.375rem;
  background: 0 0;
}

.tab > input[type="radio"]:checked + label {
  cursor: default;
  color: #fff;
  background-color: #0d6efd;
}		  
		  
	</style>


<div class="tab">
<?php if(count($double_phones) !== 0) {?>
  <input checked id="tab-btn-1" name="tab-btn" type="radio" value="">
  <label for="tab-btn-1">Дубликаты</label>
<?php }  
if(count($unique_data) !== 0) {?>
  <input id="tab-btn-2" name="tab-btn" type="radio" value="">
  <label for="tab-btn-2">Уникальные</label>
<?php } 
if(count($double_phones) !== 0) {?>  
  <div class="tab-content" id="content-1">
	<p><a href="/basep/uploads/double_data2.csv" download="double_data2.csv" > <span> скачать дубликаты </span></a></p>
	<table>
		<thead>
			<tr>
				<th>Номер</th>
				<th>Имя</th>
				<th>Источник</th>
				<th>Вопрос</th>
				<th>Статус</th>
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
					<td>
						<?= $row['status_name']; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
  </div>

<?php
}
if(count($unique_data) !== 0) {?>
  <div class="tab-content" id="content-2">
  <p><a href="/basep/uploads/unique_data2.csv" download="unique_data2.csv" > <span> скачать уникальные </span></a></p>
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
			<?php foreach($unique_data as $row): ?>
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
  </div>
 <?php }?>

</div>