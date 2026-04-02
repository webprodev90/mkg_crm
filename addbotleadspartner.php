<?//addbotleadspartner.php
/*Для регистрации нового бота нужно написать «папе ботов» @BotFather команду /newbot 
Ввести имя бота например AddLeadsParner_bot*/
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/html; charset=utf-8'); // на всякий случай досообщим PHP, что все в кодировке UTF-8
//https://api.telegram.org/bot6003129427:AAGssOhFKH58S0gKi-2Cef2iGkleaS0JysY/setWebhook?url=https://dev1.webprodev.ru/addbotleadspartner.php
$site_dir = dirname(dirname(__FILE__)).'/'; // корень сайта
$bot_token = '6003129427:AAGssOhFKH58S0gKi-2Cef2iGkleaS0JysY'; // токен вашего бота
$data = file_get_contents('php://input'); // весь ввод перенаправляем в $data
$data = json_decode($data, true); // декодируем json-закодированные-текстовые данные в PHP-массив

$order_chat_id = '-1001842069681';  //chat_id менеджера компании для заявок
$bot_state = ''; // состояние бота, по-умолчанию пустое

// Для отладки, добавим запись полученных декодированных данных в файл message.txt, 
// который можно смотреть и понимать, что происходит при запросе к боту
// Позже, когда все будет работать закомментируйте эту строку:
file_put_contents(__DIR__ . '/lmessage_p.txt', print_r($data, true));

// Основной код: получаем сообщение, что юзер отправил боту и 
// заполняем переменные для дальнейшего использования

if (!empty($data['message']['text'])) {
    $chat_id = $data['message']['from']['id'];
    $user_name = $data['message']['from']['username'];
    $first_name = $data['message']['from']['first_name'];
    $last_name = $data['message']['from']['last_name'];
    $text = trim($data['message']['text']);
    $text_array = explode(" ", $text);


	$pos_r1p = strpos($data['message']['text'], 'r1p:'); 
	$pos_r2p = strpos($data['message']['text'], 'r2p:'); 
	$pos_r3p = strpos($data['message']['text'], 'r3p:'); 
	$pos_r4p = strpos($data['message']['text'], 'r4p:'); 
	
	$r1p = substr($data['message']['text'],$pos_r1p + 4, $pos_r2p - $pos_r1p - 4);	
	$r2p = substr($data['message']['text'],$pos_r2p + 4, $pos_r3p - $pos_r2p - $pos_r1p - 4);	
	$r3p = substr($data['message']['text'],$pos_r3p + 4, $pos_r4p - $pos_r3p - 4);	
	$r4p = substr($data['message']['text'],$pos_r4p + 4);	

	$texts = $data['message']['text'];
/*
	preg_match('/(8|\+\d+)?\s*(\(\d+\))?([\s-]?\d+)+/', $texts, $phone_matches); 
	$new_phone = str_replace(str_split('-()'), '', $phone_matches[0]);
	
	$new_text = preg_replace("/(8|\+\d+)?\s*(\(\d+\))?([\s-]?\d+)+/", '', $text);
*/

    if (substr($texts,0,1) == '8')	{ $pos = 11; } else { $pos = 12; }
	
	$r1p = substr($texts,0,$pos);
	$r4p = substr($texts,$pos);


	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/func/funct.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/bd/bd.php';

    if ($pos > 0) {
		$i_fio = '';
		$i_phone_number = $r1p;
		$i_vopros = $r4p;
		$i_address = '4';
		$i_city = '';
		$i_status = 1;
		$i_date_create = date("Y-m-d");
		$i_date_zagruzki = date("Y-m-d");


		$cnt = $db_connect->query('SELECT * 
									 FROM `bez_unprocessed` 
									WHERE `phone_number` = ' . $i_phone_number);		

		if($cnt->getNumRows() > 0)
		{
			$row = $cnt->fetchAssoc();
			$i_sql = 'UPDATE `bez_unprocessed` SET `is_dubl`= "Y" WHERE id = ' . $row['id'];	
			$res = $db_connect->query($i_sql);
			if ($res === TRUE) {
				$text_m = "Номер $i_phone_number обновлен в CRM!";
				message_to_telegram($bot_token, $order_chat_id, $text_m);
			} else {
				$text_m = "Лид не обновлен в CRM";
				message_to_telegram($bot_token, $order_chat_id, $text_m);		
			}				
		} else {
			$i_sql1 = 'INSERT INTO `bez_unprocessed` (`fio`, `phone_number`, `vopros`, `address`, `city`, `status`, `date_create`, `date_zagruzki`, `is_dubl`,`istochnik`) VALUES ("' . $i_fio . '", "' . $i_phone_number . '", "' .  $i_vopros . '", "' . $i_address . '", "' . $i_city . '", "' . $i_status . '", "' . $i_date_create . '", "' . $i_date_zagruzki . '", "N", "part1")';	
			$res1 = $db_connect->query($i_sql1);
			if ($res1 === TRUE) {
				$text_m = "Номер $i_phone_number добавлен в CRM!";
				message_to_telegram($bot_token, $order_chat_id, $text_m);
			} else {
				$text_m = "Лид не добавлен в CRM";
				message_to_telegram($bot_token, $order_chat_id, $text_m);		
			}				
		}			
		

	}
/*
    print_r($r1p); echo '<br>';echo '<br>';
    print_r($r2p); echo '<br>';echo '<br>';
    print_r($r3p); echo '<br>';echo '<br>';
    print_r($r4p); echo '<br>';echo '<br>';
	
	echo $data['message']['text'];
	*/ 
	// получим текущее состояние бота, если оно есть
	$bot_state = get_bot_state ($order_chat_id);

    // если текущее состояние бота отправка заявки, то отправим заявку менеджеру компании на $order_chat_id
    if (substr($bot_state, 0, 6) == '/order') {
        $text_return = "
Заявка от @$user_name:
   ФИО: $i_fio
   Телефон: $i_phone_number
   Вопрос: $i_vopros
   Дата заявки: $i_date_create
";	 
        message_to_telegram($bot_token, $order_chat_id, $text_return);
        set_bot_state ($chat_id, ''); // не забудем почистить состояние на пустоту, после отправки заявки
    }
	
    if ($text == '/help') {
        $text_return = "Привет, $first_name $last_name, вот команды, что я понимаю: 
/help - список команд
/about - о нас
";
        message_to_telegram($bot_token, $order_chat_id, $text_return);
    }
    elseif ($text == '/about') {
        $text_return = "";
        message_to_telegram($bot_token, $order_chat_id, $text_return);
    } 
	elseif ($text == '/order') {
            $text_return = "$first_name $last_name, для подтверждения Заявки введите текст вашей заявки и нажмите отправить. 
";
            message_to_telegram($bot_token, $order_chat_id, $text_return);
            set_bot_state ($order_chat_id, '/order');
        }

}

// функция отправки сообщени в от бота в диалог с юзером
function message_to_telegram($bot_token, $chat_id, $text, $reply_markup = '')
{
    $ch = curl_init();
    $ch_post = [
        CURLOPT_URL => 'https://api.telegram.org/bot' . $bot_token . '/sendMessage',
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_POSTFIELDS => [
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'text' => $text,
            'reply_markup' => $reply_markup,
        ]
    ];

    curl_setopt_array($ch, $ch_post);
    curl_exec($ch);
}
// сохранить состояние бота для пользователя
function set_bot_state ($chat_id, $data)
{
    file_put_contents(__DIR__ . '/users/'.$chat_id.'.txt', $data);
}

// получить текущее состояние бота для пользователя
function get_bot_state ($chat_id)
{
    if (file_exists(__DIR__ . '/users/'.$chat_id.'.txt')) {
        $data = file_get_contents(__DIR__ . '/users/'.$chat_id.'.txt');
        return $data;
    }
    else {
        return '';
    }
}