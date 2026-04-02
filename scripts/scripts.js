		function check() {
			$("input:checkbox").prop("checked", true);
		};
		function uncheck() {
			$("input:checkbox").prop("checked", false);
		};
		$(document).ready(function(){
			$('#singleCheckbox1').click(function(){	
			   if ($("input:checkbox").prop("checked")==false) {
				   uncheck($('#singleCheckbox2'));
				   
			   } else {
				   check($('#singleCheckbox2'));
			   }
			});		
		});		


		window.onload = function() {

		var checkbox;

		to_send.onclick = function() {

				checkbox = document.getElementsByName("list");

				var str = "";

				for(var i=0; i<checkbox.length; i++){

				if(checkbox[i].checked) {str+="'"+checkbox[i].value+"',";}
				var usernames = $('#user_names').val();
				}

				$.ajax({
                    method: "POST",
					//dataType: 'json',
                    url: "update-u.php",
                    data: {unp_id: str, i_value: usernames},
                    success: function(data) {
						//alert(data);
						location.reload();
                    }
                });
				
				//alert($('#user_names').val());

			}

		};


		$(document).ready(function(){
			$('i#modclick').click(function(){		
				var sqlquery = $(this).attr('data-id');
				var pId = sqlquery;
				$.ajax({
                    method: "POST",
					dataType: 'json',
                    url: "edit-h.php",
                    data: {unp_id: pId, i_value: 'v_data'},
                    success: function(data) {
						$('#fio').val(data.fio);
						$('#phone_number').val(data.phone_number);
						$('#vopros').val(data.vopros);
						$('#address').val(data.address);
						$('#city').val(data.city);
						$('#status').val(data.status);					
						$('#idval').val(pId);						
						$('#user_name').val(data.user_name);
						$('#date_time_status_change').val(data.date_time_status_change);

                    }
                });				
				
				
			});		
			
            $(document).ready(function () {
                $('#datatable1').dataTable({	
				    "scrollX": true,
					"language": {
						"sProcessing":    "Procesando...",
						"sLengthMenu":    "Показывать _MENU_ записей",
						"sZeroRecords":   "No se encontraron resultados",
						"sEmptyTable":    "Нет данных",
						"sInfo":          "Отображение записей от _START_ до _END_ из общего количества _TOTAL_ записей",
						"sInfoEmpty":     "Отображение записей от 0 до 0 из общего количества 0 записей",
						"sInfoFiltered":  "(filtrado de un total de _MAX_ registros)",
						"sInfoPostFix":   "",
						"sSearch":        "Поиск:",
						"sUrl":           "",
						"sInfoThousands":  ",",
						"sLoadingRecords": "Cargando...",
						"oPaginate": {
							"sFirst":    "Предыдущий",
							"sLast":     "Следующий",
							"sNext":     "Далее",
							"sPrevious": "Назад"
						},
						"oAria": {
							"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
							"sSortDescending": ": Activar para ordenar la columna de manera descendente"
						}	
					}
					
				});
            });

		    $('#datepicker-calling').daterangepicker({
		        format: 'DD.MM.YYYY HH:mm',
		        startDate: moment().startOf('day').add(9, 'hours'),
		        endDate: moment().startOf('day').add(9, 'hours'),
		        minDate: moment(),
		        autoUpdateInput: false,
		        singleDatePicker: true,
		        timePicker: true,
		        timePicker24Hour: true,
		        icons: {
		            time: 'fa fa-clock',
		            date: 'fa fa-calendar',
		            up: 'fa fa-chevron-up',
		            down: 'fa fa-chevron-down',
		            previous: 'fa fa-chevron-left',
		            next: 'fa fa-chevron-right',
		            today: 'fa fa-calendar-check-o',
		            clear: 'fa fa-trash',
		            close: 'fa fa-times'
		        },
		        locale: {
		            format: 'DD.MM.YYYY HH:mm',
		            applyLabel: 'Выбрать',
		            cancelLabel: 'Отмена',
		            fromLabel: 'От',
		            toLabel: 'До',
		            customRangeLabel: 'Другие даты',
		            daysOfWeek: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
		            monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
		            firstDay: 1
		        }
		    });

		    $('#datepicker-calling').on('apply.daterangepicker', function (ev, picker) {
		        $(this).val(picker.startDate.format('DD.MM.YYYY HH:mm'));
		    });

		    $('#datepicker-calling').on('cancel.daterangepicker', function (ev, picker) {
		        $(this).val('');
		    });

}); 			