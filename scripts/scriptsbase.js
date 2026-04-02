function check() {
	$("input:checkbox").prop("checked", true);
};
function uncheck() {
	$("input:checkbox").prop("checked", false);
};
$(document).ready(function () {
	$('#singleCheckbox1').click(function () {
		if ($("input:checkbox").prop("checked") == false) {
			uncheck($('#singleCheckbox2'));

		} else {
			check($('#singleCheckbox2'));
		}
	});
});

window.onload = function () {

	var checkbox;

	to_send.onclick = function () {

		checkbox = document.getElementsByName("list");

		var str = "";

		for (var i = 0; i < checkbox.length; i++) {

			if (checkbox[i].checked) { str += "'" + checkbox[i].value + "',"; }
			var usernames = $('#user_names').val();
		}

		$.ajax({
			method: "POST",
			//dataType: 'json',
			url: "updatebas-u.php",
			data: { unp_id: str, i_value: usernames },
			success: function (data) {
				//alert(data);
				location.reload();
			}
		});

		//alert($('#user_names').val());

	}

};


$(document).ready(function () {
	$('i#modclick').click(function () {
		var sqlquery = $(this).attr('data-id');
		var pId = sqlquery;
		$.ajax({
			method: "POST",
			dataType: 'json',
			url: "editbas-h.php",
			data: { unp_id: pId, i_value: 'v_data' },
			success: function (data) {
				$('#fio').val(data.fio);
				$('#phone_number').val(data.phone_number);
				$('#vopros').val(data.vopros);
				$('#partner').val(data.partner);
				$('#city').val(data.city);
				$('#status').val(data.status);
				$('#date_create').val(data.date_create);
				$('#timez').val(data.timez);
				$('#idval').val(pId);
				$('#user_name').val(data.user_name);
			}
		});


	});
});

$(document).ready(function () {
	$('i#modclick2').click(function () {

		var sqlquery = $(this).attr('data-id');
		var sqlquery2 = $(this).attr('data-oper-s');
		var sqlquery3 = $(this).attr('data-oper-e');
		var pId = sqlquery;
		var pId2 = sqlquery2;
		var pId3 = sqlquery3;
		$.ajax({
			method: "POST",
			dataType: 'json',
			url: "partner-h.php",
			data: { unp_id: pId, unp_id2: pId2, unp_id3: pId3, i_value: 'v_data' },
			success: function (data) {
				//alert(data.html);
				$('#tablepodr').html(data.html);
			}
		});
	});
});


$(document).ready(function () {
	$('#datatable1').dataTable({
		"scrollX": true,
		"bAutoWidth": false,
		"language": {
			"sProcessing": "Procesando...",
			"sLengthMenu": "Показывать _MENU_ записей",
			"sZeroRecords": "No se encontraron resultados",
			"sEmptyTable": "Нет данных",
			"sInfo": "Отображение записей от _START_ до _END_ из общего количества _TOTAL_ записей",
			"sInfoEmpty": "Отображение записей от 0 до 0 из общего количества 0 записей",
			"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix": "",
			"sSearch": "Поиск:",
			"sUrl": "",
			"sInfoThousands": ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst": "Предыдущий",
				"sLast": "Следующий",
				"sNext": "Далее",
				"sPrevious": "Назад"
			},
			"oAria": {
				"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}
		}

	});
});

function event_start(event_type, element, callback) {
    $(document).on(event_type, element, function () {
        callback($(this));
    });
}

$(document).ready(function () {

	event_start('change', '.change-operator', function (e) {
    const user_id = $(e).val();
    const request_id = $(e).closest('tr').attr('tr-id');
    const td = $(e).closest('tr').find('.otdel-id');
    const form_data = {
	    'params': [
		        {
		            'name': 'user_id',
		            'value': user_id,
		        }
	    ],
        'action': 'update_request',
        'id': request_id,
    }
    $.ajax({
        url: '/scripts/unprocessed_base/unprocessed_base.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            td[0].textContent = response['otdel_id'];
        	},
        error: function (error) {
            alert('Ошибка запроса:', error);
        	}
    	});
	});

});