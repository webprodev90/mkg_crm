<?php
// Блок 1: Обработка Bearer-токена
if ($_SERVER["HTTP_AUTHORIZATION"] == 'Bearer eyJhbGciOiJdfghSUzI3NdfghiIsImtpZC34523sfgh565IjI2Y') {

    require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';


    function logError($db, $value, $text, $userId = null, $module = 'api_rest_telegram') {
        $value = $db->real_escape_string($value);
        $text = $db->real_escape_string($text);
        $userId = $userId ? $db->real_escape_string($userId) : 'NULL';
        $module = $db->real_escape_string($module);


        $sql = "INSERT INTO logs (value, text, user_id, modul) VALUES ('$value', '$text', $userId, '$module')";
        $db->query($sql);
    }

    function clean_phone_number($phone_number, $cod) {
        $cleaned_phone_number = preg_replace('/\D/', '', $phone_number);
        if (preg_match('/^[87]/', $cleaned_phone_number)) {
            $cleaned_phone_number = substr($cleaned_phone_number, 1);
        }
        return substr($cleaned_phone_number, -10);
    }


    $postData = file_get_contents('php://input');
    $data = json_decode($postData, true);

    if (!$data) {
        logError($db_connect, 'error', 'Неверные данные (пустой или некорректный JSON)', null, 'api_rest_telegram');
        http_response_code(400);
        echo 'Неверные данные (пустой или некорректный JSON)';
        exit;
    }

    $values = [];
    $columns = [];
    foreach ($data as $key => $value) {
        if (in_array($key, [
            'id', 'date', 'username', 'fio', 'tgID', 'initial_identifier',
            'otvet1', 'otvet2', 'otvet2_1', 'otvet2_2', 'otvet3', 'otvet3_1', 'otvet4', 'otvet5',
            'otvet5_1', 'otvet6', 'otvet7', 'otvet8', 'otvet9', 'otvet10'
        ]) && !empty($value)) {
            $columns[] = $key;
			//$values[] = "'" . $db_connect->real_escape_string($value) . "'";		
			$values[] = "'" . (string)$value . "'";	
        }
    }

    if (!empty($columns)) {
        if (isset($data['otvet9'])) {
            $data['otvet9'] = clean_phone_number($data['otvet9'], '7');
        }

        $sql = "INSERT INTO bez_leads_telegram (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ")";
        if (!$db_connect->query($sql)) {
            logError($db_connect, 'error', 'Ошибка при добавлении данных в bez_leads_telegram: ' . $db_connect->error, null, 'api_rest_telegram');
            http_response_code(500);
            echo 'Ошибка при добавлении данных в bez_leads_telegram: ' . $db_connect->error;
            exit;
        }
    } else {
        logError($db_connect, 'warning', 'Нет данных для вставки в bez_leads_telegram', null, 'api_rest_telegram');
        http_response_code(400);
        echo 'Нет данных для вставки в bez_leads_telegram';
        exit;
    }

    if (!empty($data['otvet9'])) {
		
        //$phone = $db_connect->real_escape_string(clean_phone_number($data['otvet9'], '7'));
        $phone = clean_phone_number($data['otvet9'], '7');

        $status = 15;
        $date_create = 'NOW()';
        $date_time_status_change = 'NOW()';
        $user_id = 497;
        $source = 'ТГ_Т';
        $id_otdel = 3;
        $date_time_of_last_save = 'NOW()';

        $debt_banks = null;
        $debt_mfo = null;
        $delays = null;
        $mortgage = null;
        $debt_zhkh = null;
        $taxes_fines = null;
        $city = null;
        $fio = null;

        if (!empty($data['otvet1'])) {
            $otvet1_arr = array_map('intval', explode(',', $data['otvet1']));
            if (in_array(1, $otvet1_arr)) {
                $debt_banks = !empty($data['otvet2']) ? (float)$data['otvet2'] : null;
                $debt_mfo = !empty($data['otvet2']) ? (float)$data['otvet2'] : null;
            }
            if (in_array(2, $otvet1_arr)) $debt_zhkh = 1;
            if (in_array(3, $otvet1_arr)) $taxes_fines = 1;
            if (in_array(4, $otvet1_arr)) {
                $debt_banks = !empty($data['otvet2']) ? (float)$data['otvet2'] : null;
                $debt_mfo = !empty($data['otvet2']) ? (float)$data['otvet2'] : null;
                $taxes_fines = !empty($data['otvet2_1']) ? (float)$data['otvet2_1'] : null;
                $debt_zhkh = !empty($data['otvet2_2']) ? (float)$data['otvet2_2'] : null;

            }
        }

        if (!empty($data['otvet3'])) {
            $delays = ($data['otvet3'] == 1) ? 'y' : 'n';
        }

        if (!empty($data['otvet4'])) {
            $otvet4_arr = array_map('intval', explode(',', $data['otvet4']));
            if (in_array(1, $otvet4_arr)) $mortgage = 's';
            elseif (in_array(2, $otvet4_arr)) $mortgage = 'n';
            elseif (in_array(3, $otvet4_arr)) $mortgage = 'm';
        }


        if (!empty($data['otvet7'])) {
            $city = $data['otvet7'];
        }

        if (!empty($data['otvet8'])) {
            $fio = $data['otvet8'];
        }

        $fields = [
            'phone_number' => "'$phone'",
            'status' => $status,
            'date_create' => $date_create,
            'date_time_status_change' => $date_time_status_change,
            'user_id' => $user_id,
            'source' => "'$source'",
            'id_otdel' => $id_otdel,
            'date_time_of_last_save' => $date_time_of_last_save,
        ];

        if ($debt_banks !== null) $fields['debt_banks'] = $debt_banks;
        if ($debt_mfo !== null) $fields['debt_mfo'] = $debt_mfo;
        if ($delays !== null) $fields['delays'] = "'$delays'";
        if ($mortgage !== null) $fields['mortgage'] = "'$mortgage'";
        if ($debt_zhkh !== null) $fields['debt_zhkh'] = $debt_zhkh;
        if ($taxes_fines !== null) $fields['taxes_fines'] = $taxes_fines;
        if ($city !== null) $fields['city'] = "'$city'";
        if ($fio !== null) $fields['fio'] = "'$fio'";

		// Формируем текст с вопросами и ответами для поля vopros
		$vopros = '';

		// Вопрос 1
		if (!empty($data['otvet1'])) {
			$otvet1_arr = array_map('intval', explode(',', $data['otvet1']));
			$answers1 = [];
			if (in_array(1, $otvet1_arr)) $answers1[] = 'Кредиты и займы';
			if (in_array(2, $otvet1_arr)) $answers1[] = 'Задолженность по ЖКХ';
			if (in_array(3, $otvet1_arr)) $answers1[] = 'Налоги и штрафы';
			if (in_array(4, $otvet1_arr)) $answers1[] = 'Другие долги';
			
			if (!empty($answers1)) {
				$vopros .= "1. " . implode(', ', $answers1) . ". ";
			}
		}

		// Вопрос 2 (если есть ответ на Вопрос 1 → «Кредиты и займы»)
		if (!empty($data['otvet1']) && strpos($data['otvet1'], '1') !== false && !empty($data['otvet2'])) {
			$vopros .= "2. " . htmlspecialchars($data['otvet2']) . ". ";
		}

		// Вопрос 2.1 (если есть ответ на Вопрос 1 → «Налоги и штрафы/другие долги»)
		if (!empty($data['otvet1']) && strpos($data['otvet1'], '3') !== false && strpos($data['otvet1'], '4') !== false && !empty($data['otvet2_1'])) {
			$vopros .= "2.1. " . htmlspecialchars($data['otvet2_1']) . ". ";
		}

		// Вопрос 2.2 (если есть ответ на Вопрос 1 → «Задолженность по ЖКХ»)
		if (!empty($data['otvet1']) && strpos($data['otvet1'], '2') !== false && !empty($data['otvet2_2'])) {
			$vopros .= "2.2. " . htmlspecialchars($data['otvet2_2']) . ". ";
		}

		// Вопрос 3
		if (!empty($data['otvet3'])) {
			$delays = ($data['otvet3'] == 1) ? 'Да, есть' : 'Нет, пока плачу';
			if ($data['otvet3'] == 3) $delays = 'Затрудняюсь ответить';
			$vopros .= "3. $delays. ";
		}

		// Вопрос 3.1 (если Вопрос 3 → «Да, есть»)
		if (!empty($data['otvet3']) && $data['otvet3'] == 1 && !empty($data['otvet3_1'])) {
			$vopros .= "3.1. " . htmlspecialchars($data['otvet3_1']) . ". ";
		}

		// Вопрос 4
		if (!empty($data['otvet4'])) {
			$otvet4_arr = array_map('intval', explode(',', $data['otvet4']));
			$mortgage = '';
			if (in_array(1, $otvet4_arr)) $mortgage = 'Да, есть ипотека/автокредит';
			if (in_array(2, $otvet4_arr)) $mortgage = 'Нет, таких кредитов нет';
			if (in_array(3, $otvet4_arr)) $mortgage = 'Да, передавал(а) имущество в залог';
			
			if (!empty($mortgage)) {
				$vopros .= "4. $mortgage. ";
			}
		}

		// Вопрос 5
		if (!empty($data['otvet5'])) {
			$answers5 = [];
			if (strpos($data['otvet5'], '1') !== false) $answers5[] = 'Квартира';
			if (strpos($data['otvet5'], '2') !== false) $answers5[] = 'Частный дом';
			if (strpos($data['otvet5'], '3') !== false) $answers5[] = 'Другое';
			if (strpos($data['otvet5'], '4') !== false) $answers5[] = 'Собственности нет';
			
			if (!empty($answers5)) {
				$vopros .= "5. " . implode(', ', $answers5) . ". ";
			}
		}

		// Вопрос 5.1
		if (!empty($data['otvet5_1'])) {
			$vopros .= "5.1. " . htmlspecialchars($data['otvet5_1']) . ". ";
		}

		// Вопрос 6
		if (!empty($data['otvet6'])) {
			$vopros .= "6. " . htmlspecialchars($data['otvet6']) . ". ";
		}

		// Вопрос 7 (география)
		if (!empty($data['otvet7'])) {
			$vopros .= "7. " . htmlspecialchars($data['otvet7']) . ". ";
		}

		// Вопрос 8 (имя)
		if (!empty($data['otvet8'])) {
			$vopros .= "8. " . htmlspecialchars($data['otvet8']) . ". ";
		}

		// Вопрос 9 (контакты)
		if (!empty($phone)) {
			$vopros .= "9. +7" . $phone . "\n\n";
		}

		// Вопрос 10 (имя)
		if (!empty($data['otvet10'])) {
			$vopros .= "10. " . htmlspecialchars($data['otvet10']) . ". ";
		}

		// Добавляем в поля для БД
		$fields['vopros'] = "'" . $vopros . "'";

        $checkSql = "SELECT id FROM bez_unprocessed_base WHERE phone_number = '$phone' LIMIT 1";
        $result = $db_connect->query($checkSql);

        if ($result && $result->num_rows > 0) {
            $updateParts = [];
            foreach ($fields as $field => $value) {
                $updateParts[] = "$field = $value";
            }
            $updateSql = "UPDATE bez_unprocessed_base SET " . implode(', ', $updateParts) . " WHERE phone_number = '$phone'";

            if (!$db_connect->query($updateSql)) {
                logError($db_connect, 'error', 'Ошибка при обновлении записи в bez_unprocessed_base: ' . $db_connect->error, null, 'api_rest_telegram');
                http_response_code(500);
                echo 'Ошибка при обновлении записи в bez_unprocessed_base: ' . $db_connect->error;
                exit;
            }
        } else {
            $insertFields = implode(', ', array_keys($fields));
            $insertValues = implode(', ', $fields);
            $insertSql = "INSERT INTO bez_unprocessed_base ($insertFields) VALUES ($insertValues)";
            if (!$db_connect->query($insertSql)) {
                logError($db_connect, 'error', 'Ошибка при добавлении записи в bez_unprocessed_base: ' . $db_connect->error, null, 'api_rest_telegram');
                http_response_code(500);
                echo 'Ошибка при добавлении записи в bez_unprocessed_base: ' . $db_connect->error;
                exit;
            }
        }

    }

    http_response_code(201);
    echo 'Данные успешно обработаны';
}

// Блок 2: Обработка второго Bearer-токена
if ($_SERVER["HTTP_AUTHORIZATION"] == 'Bearer eyJhbGciOiJSUzI1NiIsImtpZCI4IjI3Y') {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';


    $postData = file_get_contents('php://input');
    $data = json_decode($postData, true);


    if ($data) {
        $escapedData = json_encode($data);
        $sql = "INSERT INTO api_log (modul_api, text_api) VALUES ('partner', '$escapedData')";
        if (!$db_connect->query($sql)) {
            logError($db_connect, 'error', 'Ошибка при записи в api_log: ' . $db_connect->error, null, 'api_log');
        }
    } else {
        $sql = "INSERT INTO logs (value, modul, text) VALUES ('insert', 'api_log', 'Пустые данные')";
        if (!$db_connect->query($sql)) {
            logError($db_connect, 'error', 'Ошибка при записи в logs: ' . $db_connect->error, null, 'api_log');
        }
    }
}

// Блок 3: Обработка GET-запроса с ключом
if (isset($_GET['key']) && $_GET['key'] == "fa9sgwl456gjs9g456dsjlgjd456sjg45345789lsdlgs") {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';


    if (isset($_GET['status']) && isset($_GET['id'])) {
        $status = $_GET['status'];
        $id = $_GET['id'];

        $sql = "UPDATE `bez_unprocessed_base` SET is_double = '$status' WHERE id = '$id'";
        if (!$db_connect->query($sql)) {
            logError($db_connect, 'error', 'Ошибка при обновлении is_double: ' . $db_connect->error, null, 'bez_unprocessed_base');
            http_response_code(500);
            echo 'Ошибка при обновлении is_double: ' . $db_connect->error;
            exit;
        }
    } else {
        http_response_code(400);
        echo 'Недостаточно параметров (status или id)';
        exit;
    }
}

?>
