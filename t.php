<?

$token = '8701678046:AAG6rAqLwSxfv_YVSbT1HFLlxzYaZVi27FU'; // Токен телеграм бота, полученый у BotFather
$chat_name = 'ipgorlinleads'; // Ссылка-приглашение (без t.me/)
 
$data = file_get_contents('https://api.telegram.org/bot'.$token.'/getChat?chat_id=@'.$chat_name);
$data = json_decode($data, true);
 
if (!empty($data['result']['id'])) {
	$id = "Chat id: ". $data['result']['id'];
}
else $id = "error";
 
echo $id;

?>