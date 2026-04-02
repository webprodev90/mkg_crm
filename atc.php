<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="https://widget.skorozvon.ru/phone.js"></script>
<style>
body {
margin: 0;
padding: 0;
height: auto;
width: auto;
overflow: hidden;
}
</style>
<script type="text/javascript">
Tube.ready = function() {
	phoneWidget = new Tube.Widgets.Phone({
		el: "#widget", // элемент на странице, в который размещается виджет
		waitActiveCall: false, // при значении true - активный звонок(или несколько) ждет пока оператор завершит разговор и сохранит карточку
		disallowNormalStateOnActiveCall: false, // при значении true - запрет на изменение статуса на
		//"Доступен" при открытой карточке (чтобы диалер не распределял на пользователя, пока открыта карточка )
	});
	phoneWidget.phoneModel.on('call:started', function(call) {
		// обработка события инициализации звонка
		//alert('инициализации звонка');
		alert(JSON.stringify(call.toJSON()));

	});
	phoneWidget.phoneModel.on('call:ringing', function(call) {
		// обработка события начала дозвона
		alert("Дозвон на номер: " + call.get('phone'));
		console.log("Дозвон на номер: " + call.get('phone'));
		console.log(JSON.stringify(call.toJSON()));
		
		if (call.get('phone') == '+79297722670' ) {
			alert("1");
		}

	});
	phoneWidget.phoneModel.on('call:connected', function(call) {
		// обработка события соединения звонка
		alert('соединения звонка');
	});
	phoneWidget.phoneModel.on('call:ended', function(call) {
		// обработка события завершения звонка
		alert('завершения звонка');
	});
	/// во всех случаях в аргумент call попадает Backbone-модель
	/// объекта звонка. Её атрибуты доступны с помощью метода .get(attr)
	/// call.get('lead_external_id') => получить external_id контакта верхнего уровня, которому совершается звонок
	document.getElementById("make_call").addEventListener("click", function() {
		/* makeCall(phone:number, params:{});
		объект params принимает ключи:
		lead_id: number|null, // id организации или контакта из скорозвона.
		deferred_transfer_phone: string|null // - валидный номер, который подставляется в поле номера для переадресации
		*/
		phoneWidget.makeCall("+79297722670");
	});
	/*
	пример перевода текущего вызова
	phoneWidget.transferCurrent({
	user_id: <integer>, - - перевод на определённого пользователя
	phone_user_group_id: <integer>, - - перевод на группу пользователей. Информация о
	существующих группах доступна в GET /api/v2/user_groups
	number: <string>, - - перевод на определённый номер
	consultation: <boolean> - - начать перевод с консультацией
	});
	в случае отсутствия текущего вызова попытка перевода не произойдёт
	*/
	phoneWidget.login({
		email: "al.funythecat@gmail.com",
		api_key: "7ca816ef859e6ceb045db04f2bcfbbb9769e3b44a1a275d6be6ab67e4bb7a6e0"
	});
	phoneWidget.show();
}
</script>
</head>
<body>
<div id="widget"></div>
<button id="make_call">Call</button>
</body>
</html>