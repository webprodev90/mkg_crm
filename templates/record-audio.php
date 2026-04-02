<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

function clean_phone_number($phone_number) {
	$cleaned_phone_number = preg_replace('/\D/', '', $phone_number);

	if(preg_match('/^[87]/', $cleaned_phone_number)) {
		$cleaned_phone_number = substr($cleaned_phone_number, 1);
	}
	
	$cleaned_phone_number = substr($cleaned_phone_number, -10);

	return $cleaned_phone_number;
}

// Функция для создания безопасного имени файла для Яндекс Диска
function get_safe_filename($filename) {
    // Заменяем все запрещенные символы на дефис
    $forbidden_сhars = ['/', '\\', ':', '*', '?', '"', '<', '>', '|'];
    $safe_name = str_replace($forbidden_сhars, '-', $filename);
    
    // Обрезаем имя файла до 255 символов (ограничение Яндекс Диска)
    if (strlen($safe_name) > 255) {
        $extension = pathinfo($safe_name, PATHINFO_EXTENSION);
        $name_without_ext = pathinfo($safe_name, PATHINFO_FILENAME);
        
        // Оставляем место для расширения (3 символа + точка)
        $max_name_length = 255 - strlen($extension) - 1;
        $safe_name = substr($name_without_ext, 0, $max_name_length) . '.' . $extension;
    }
    
    return $safe_name;
}

if(!empty($_POST['src_value']) and !empty($_POST['phone_number'])) {
    $src_value = $_POST['src_value'];
    $phone_number = clean_phone_number($_POST['phone_number']);
    $url = 'http://83.222.25.208/crm/' . $src_value; // Ссылка на аудиофайл
	$path = parse_url($url, PHP_URL_PATH);
	$originalName = basename($path);    
    $safe_filename = get_safe_filename(basename($originalName));
    $name_audio = pathinfo($safe_filename, PATHINFO_FILENAME); 

	$res = $db_connect->query('SELECT * FROM audiorecordings WHERE name = "' . $name_audio . '" LIMIT 1;');
		
	if($res->getNumRows() == 0) {

		$folder = "downloads/"; // Папка для сохранения (должна существовать)
		$maxSize = 10 * 1024 * 1024; // Макс. размер файла (10 МБ)

		// Проверяем папку
		if (!is_dir($folder)) {
		    if (!mkdir($folder, 0755, true)) {
		        die("Ошибка: Невозможно создать папку");
		    }
		}

		// Инициализируем cURL
		$ch = curl_init($url);
		$filePath = $folder . $safe_filename; 

		// Открываем файл для записи
		$fp = fopen($filePath, 'wb');

		// Настройки cURL
		curl_setopt_array($ch, [
		    CURLOPT_FILE => $fp,            // Сохранять вывод в файл
		    CURLOPT_FOLLOWLOCATION => true, // Следовать редиректам
		    CURLOPT_MAXFILESIZE => $maxSize,// Ограничение размера
		    CURLOPT_SSL_VERIFYPEER => true,// Проверять SSL сертификат
		    CURLOPT_TIMEOUT => 30,         // Таймаут 30 секунд
		    CURLOPT_USERAGENT => 'Mozilla/5.0' // User-Agent
		]);

		// Выполняем запрос
		curl_exec($ch);

		// Проверяем ошибки
		if (curl_errno($ch)) {
		    fclose($fp);
		    unlink($filePath); // Удаляем неполный файл
		    die("Ошибка скачивания: " . curl_error($ch));
		}

		// Проверяем HTTP-статус
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($httpCode !== 200) {
		    fclose($fp);
		    unlink($filePath);
		    die("HTTP ошибка: $httpCode");
		}

		// Закрываем ресурсы
		fclose($fp);
		curl_close($ch);

		// Проверяем MIME-тип
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $filePath);
		finfo_close($finfo);

		if (strpos($mime, 'wav') === false && $mime !== 'audio/x-wav') {
		    unlink($filePath);
		    die("Ошибка: Файл не является WAV-аудио (MIME: $mime)");
		}
		/*
		if (file_exists($filePath) && filesize($filePath) > 0) {
		    echo "Файл физически записан: " . filesize($filePath) . " байт";
		} else {
		    echo "ОШИБКА: Файл не обнаружен после записи";
		}
		*/
		echo "Файл успешно сохранён: " . basename($filePath);	

		//Загрузка файла на Яндекс Диск
		$token = 'y0__xD92oeqCBiktjkgivTRghT3aoiEWu-tyqnMoWjFNB4kaMIsOA';

		// Путь и имя файла на нашем сервере.
		$file = __DIR__ . '/downloads/' . basename($filePath);

		// 1. Формируем путь с датой
		$date_path = date('/Y/m/d'); // Получим /2024/07/15
		$base_path = '/phone_calls';
		$full_path = $base_path . $date_path . '/' . basename($filePath);

		// 2. Рекурсивно создаем папки по дате
		function createDateFolders($token, $base_path, $date_path) {
		    $parts = array_filter(explode('/', $date_path)); // Разбиваем путь на части
		    $current_path = $base_path;
		    
		    foreach ($parts as $part) {
		        $current_path .= '/' . $part;
		        
		        $ch = curl_init('https://cloud-api.yandex.net/v1/disk/resources?path=' . urlencode($current_path));
		        curl_setopt_array($ch, [
		            CURLOPT_HTTPHEADER => ['Authorization: OAuth ' . $token],
		            CURLOPT_RETURNTRANSFER => true,
		            CURLOPT_SSL_VERIFYPEER => false
		        ]);
		        
		        $response = curl_exec($ch);
		        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		        
		        // Если папка не существует - создаем
		        if ($http_code == 404) {
		            curl_setopt($ch, CURLOPT_URL, 'https://cloud-api.yandex.net/v1/disk/resources?path=' . urlencode($current_path));
		            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		            $create_response = curl_exec($ch);
		            
		            if (curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 201) {
		                curl_close($ch);
		                return false;
		            }
		        }
		        
		        curl_close($ch);
		    }
		    return true;
		}

		// Создаем папки года/месяца/дня
		if (!createDateFolders($token, $base_path, $date_path)) {
		    die("Ошибка создания папок по дате");
		}
		 
		// Запрашиваем URL для загрузки.
		$ch = curl_init('https://cloud-api.yandex.net/v1/disk/resources/upload?path=' . urlencode($full_path));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$res = curl_exec($ch);
		curl_close($ch);
		 
		$res = json_decode($res, true);
		if (empty($res['error'])) {
			// Если ошибки нет, то отправляем файл на полученный URL.
			$fp = fopen($file, 'r');
		 
		 	$ch = curl_init($res['href']);
			curl_setopt($ch, CURLOPT_PUT, true);
			curl_setopt($ch, CURLOPT_UPLOAD, true);
			curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file));
			curl_setopt($ch, CURLOPT_INFILE, $fp);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_exec($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
		 
			if ($http_code == 201) {
				echo 'Файл успешно загружен на Яндекс Диск.';
			}
		} 

		// Предоставление и настройка публичного доступа к ресурсу
		$file_path_ya = 'disk:' . $full_path; // Путь к файлу на Яндекс.Диске

		// Шаг 1: Публикация файла
		$ch = curl_init();
		curl_setopt_array($ch, [
		    CURLOPT_URL => 'https://cloud-api.yandex.net/v1/disk/resources/publish?path=' . urlencode($file_path_ya),
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_CUSTOMREQUEST => 'PUT',
		    CURLOPT_HTTPHEADER => [
		        'Authorization: OAuth ' . $token
		    ]
		]);

		$response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if ($http_code >= 400) {
		    die("Ошибка публикации: HTTP $http_code\n" . $response);
		}

		// Шаг 2: Получение публичной ссылки
		$response_data = json_decode($response, true);
		$public_url = null;

		if (isset($response_data['href'])) {
		    curl_setopt_array($ch, [
		        CURLOPT_URL => $response_data['href'],
		        CURLOPT_CUSTOMREQUEST => 'GET'
		    ]);
		    
		    $resource_info = json_decode(curl_exec($ch), true);
		    $public_url = $resource_info['public_url'] ?? null;
		}

		curl_close($ch);

		if ($public_url) {
		    echo "Файл опубликован. Публичная ссылка: " . $public_url;
		} else {
		    echo "Ошибка: не удалось получить публичную ссылку";
		}

		// Пытаемся удалить файл
		if (unlink($filePath)) {
		    // Очищаем кэш статуса файла
		    clearstatcache(true, $filePath);
		        
		    // Двойная проверка успешности удаления
		    if (file_exists($filePath)) {
		        echo 'Файл удалён, но всё ещё отображается в файловой системе';
		    } else {
		        echo 'Файл успешно удалён: ' . basename($filePath);
		    }
		} else {
		    echo 'Неизвестная ошибка при удалении';
		}

		$db_connect->query('INSERT INTO audiorecordings (name, phone_number, link)
							VALUES( "' . $name_audio . '",
							   	    "' . $phone_number . '",
								    "' . $public_url . '"
							)');
	} else {
		echo 0;
	}
	
} else {
    echo "Что-то пошло не так";
}



?>