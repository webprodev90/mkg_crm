<?

$token = '7681771309:AAF6wTex7SzNggl5QDvENutyv9GIR9onDBw'; // Токен телеграм бота, полученый у BotFather
$chat_name = 'sygurovmkg'; // Ссылка-приглашение (без t.me/) sygurovmkg_bot ИП Сыгуров / ООО МКГ / Лиды Самара
// Ссылка для проверки
// https://api.telegram.org/bot7720251812:AAH8ZC_f2lA1LJw7fmVzC8qptdl0s_Z3vM8/sendMessage?chat_id=-1002821313026&text=%3CText%3E
$data = file_get_contents('https://api.telegram.org/bot'.$token.'/getChat?chat_id=@'.$chat_name);
$data = json_decode($data, true);
 
if (!empty($data['result']['id'])) {
	$id = "Chat id: ". $data['result']['id'];
}
else $id = "error";
 
echo $id;

?>