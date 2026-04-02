var current_request = get_current_page().startsWith('unprocessed-base-excel.php?p=10') ? 5000 : 500;
var scroll_event = true;
var filter = {};
var filter2 = {};
var order_by_filter = '';
var date_start, date_end = '';
var view_name = 'unprocessed';
var defect_bg = 'no';
var is_search = false;
var user_id = undefined;
var table = '';
var filter_by_status = false;
var data_table = null
var operators = [];
var form_data_table = {};
var is_closed_by_button = false;
var isDaterangepickerOpen = false;
var is_fills_lead = false;
var is_table_loaded = false;
var filter_type = '';
var search_word_val = undefined;

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

function event_start(event_type, element, callback) {
    $(document).on(event_type, element, function () {
        callback($(this));
    });
}

function get_current_page() {
    const path = window.location.href;
    const parts = path.split('/');
    const page = parts[parts.length - 1];
    return page;
}

function date_eng_format(date) {
    if (date) {
        return date.split('/').reverse().join('-')
    }
}

function get_unprocessed_base() {
    scroll_event = false;

    if(get_current_page().startsWith('unprocessed-base-excel.php?p=10')) {
        table = '_excel';
    }

	const form_data = {
		'action': `get_unprocessed_base${table}`,
		'limit_start': current_request,
		'view_name': view_name,
		'filter': {},
		'filter2': {},
		'defect_bg': defect_bg,
	}

    if (filter) {
        form_data['filter'] = filter;
    }

    if (filter2) {
        form_data['filter2'] = filter2;
    }

    if (order_by_filter !== '') {
        form_data['order_by'] = order_by_filter;
    }

    if (date_start !== '' && date_end != '') {
        form_data['date_start'] = date_start;
        form_data['date_end'] = date_end;
    }

    if (get_current_page().startsWith('lead-sales.php')) {
        form_data['filter']['comparison_operator'] = 'IN';
        form_data['filter']['status'] = '15';
        form_data['manual_sal'] = '1';
    }

    if(get_current_page().startsWith('unprocessed-base-3.php?p=10')) {
        form_data['is_double'] = 'y';
    }

    if(get_current_page().startsWith('unprocessed-base-4.php?p=10')) {
        form_data['manual'] = 'r';
		form_data['is_double'] = 'n';
    }

    if(get_current_page().startsWith('unprocessed-base-1.php?p=10') || get_current_page().startsWith('unprocessed-base-excel.php?p=10') || get_current_page().startsWith('unprocessed-base-5.php?p=10') || get_current_page().startsWith('unprocessed-base-6.php?p=10')) {
        form_data['is_double'] = 'n';
    }

    if(get_current_page().startsWith('unprocessed-base-6.php?p=10')) {
        form_data['user_access'] = 'y';
    }

    if(get_current_page().startsWith('unprocessed-base-excel.php?p=10')) {
        if (date_start === undefined && date_end === '') {
            const now = moment();
            const cur_date = now.format('YYYY-MM-DD'); 
            form_data['date_start'] = cur_date;
            form_data['date_end'] = cur_date;
        }
    }

    if(get_current_page().startsWith('unprocessed-base-5.php?p=10') || get_current_page().startsWith('unprocessed-base-6.php?p=10') || get_current_page().startsWith('unprocessed-base-7.php?p=10') || get_current_page().startsWith('holds.php')) {
        if (date_start === undefined && date_end === '') {
            const datepicker = $('#reportrange').data('daterangepicker');
            form_data['date_start'] = date_eng_format(datepicker.startDate.format('DD/MM/YYYY'));
            form_data['date_end'] = date_eng_format(datepicker.endDate.format('DD/MM/YYYY'));
        }            
        form_data['is_limit'] = false;    
    }

    if(filter_by_status) {
        form_data['filter_by_status'] = 'y';
        filter_by_status = false;
    }

    if(get_current_page().startsWith('unprocessed-base-excel.php?p=10')) {
        current_request += 5000; 
    } else {
        current_request += 500; 
    }
    
    if(!get_current_page().startsWith('unprocessed-base-5.php?p=10') || !get_current_page().startsWith('unprocessed-base-6.php?p=10') || !get_current_page().startsWith('unprocessed-base-7.php?p=10') || !get_current_page().startsWith('holds.php')) {
        if(get_current_page().startsWith('unprocessed-base-excel.php?p=10')) {
            if (filter2) {
                form_data['limit_end'] = 5000;   
            } else {
                form_data['limit_end'] = 5000;
            }   
        } else {
            if (filter2) {
                form_data['limit_end'] = 500;   
            } else {
                form_data['limit_end'] = 500;
            }            
        }
    }    

    $.ajax({
        url: `/scripts/unprocessed_base${table}/unprocessed_base${table}.php`,
        method: 'POST',
        data: form_data,
        success: function (response) {
            scroll_event = true;
            $('#table-request').append(response); 
        },
        error: function (error) {
            if (error.responseText) {
                alert("Ошибка: " + error.responseText);
            } else {
                alert("HTTP ошибка: " + error.status + " - " + error.statusText);
            }
        }
    });
}

function get_unprocessed_base2() {
    scroll_event = false;
    view_name = 'unprocessed2';

    const form_data = {
        'action': 'get_unprocessed_base',
        'limit_start': current_request,
        'view_name': view_name,
        'filter': {},
    }

    if (filter) {
        form_data['filter'] = filter;
    }

    if (order_by_filter !== '') {
        form_data['order_by'] = order_by_filter;
    }

    if (date_start !== '' && date_end != '') {
        form_data['date_start'] = date_start;
        form_data['date_end'] = date_end;
    }

    current_request += 500;
    form_data['limit_end'] = 500;

    $.ajax({
        url: '/scripts/unprocessed_base2/unprocessed_base2.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            scroll_event = true;
            $('#table-request').append(response);
        },
        error: function (error) {
            if (error.responseText) {
                alert("Ошибка: " + error.responseText);
            } else {
                alert("HTTP ошибка: " + error.status + " - " + error.statusText);
            }
        }
    });
}

function get_operators() {

    const form_data = {
        'action': 'get_operators',
        'get_json': true,
    }

    $.ajax({
        url: '/scripts/unprocessed_base/unprocessed_base.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            operators = response;
        },
        error: function (error) {
            if (error.responseText) {
                alert("Ошибка: " + error.responseText);
            } else {
                alert("HTTP ошибка: " + error.status + " - " + error.statusText);
            }
        }
    });    
}

function get_counters() {
    let dates = $('#reportrange').val().split(' - ');
    date_start = dates[0].split('/').reverse().join('-') + ' 00:00:00';
    date_end = dates[1].split('/').reverse().join('-') + ' 23:59:59';

    const form_data = {
        'action': 'get_counters',
        'date_start': date_start,
        'date_end': date_end,
        'get_json': true,
    }

    if(get_current_page().startsWith('unprocessed-base-3.php?p=10')) {
        form_data['is_double'] = 'y';
    }

    if(get_current_page().startsWith('unprocessed-base-1.php?p=10') || get_current_page().startsWith('unprocessed-base-5.php?p=10') || get_current_page().startsWith('unprocessed-base-6.php?p=10')) {
        form_data['is_double'] = 'n';
    }

    if(get_current_page().startsWith('unprocessed-base-6.php?p=10')) {
        form_data['user_access'] = 'y';
    }

    if(get_current_page().startsWith('unprocessed-base-4.php?p=10')) {
        form_data['manual'] = 'r';
		form_data['is_double'] = 'n';
    }

    if(get_current_page().startsWith('unprocessed-base-excel.php?p=10')) {
        table = '_excel';
        form_data['is_double'] = 'n';
    }

    $.ajax({
        url: `/scripts/unprocessed_base${table}/unprocessed_base${table}.php`,
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('.count_callings').text(response['callings']);
            $('.count_leads').text(response['leads']);
        },
        error: function (error) {
            if (error.responseText) {
                alert("Ошибка: " + error.responseText);
            } else {
                alert("HTTP ошибка: " + error.status + " - " + error.statusText);
            }
        }
    });
}

function get_counters2() {

    const form_data = {
        'action': 'get_counters',
        'date_start': date_start,
        'date_end': date_end,        
        'is_search': is_search,
        'filter': {},
        'get_json': true,
        
    }

    if (filter) {
        form_data['filter'] = filter;
    }

    $.ajax({
        url: '/scripts/unprocessed_base2/unprocessed_base2.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('.count_callings').text(response['callings']);
            $('.count_non_calls').text(response['non_calls']);
            $('.count_rejections').text(response['rejections']);
            $('.count_defects').text(response['defects']);
            $('.count_contracts').text(response['contracts']);
            $('.count_all').text(response['all_leads']);
        },
        error: function (error) {
            if (error.responseText) {
                alert("Ошибка: " + error.responseText);
            } else {
                alert("HTTP ошибка: " + error.status + " - " + error.statusText);
            }
        }
    });
}

function get_filtering_sources() {
    let dates = $('#reportrange').val().split(' - ');
    date_start = dates[0].split('/').reverse().join('-') + ' 00:00:00';
    date_end = dates[1].split('/').reverse().join('-') + ' 23:59:59';

    const form_data = {
        'action': 'get_filtering_sources',
        'date_start': date_start,
        'date_end': date_end,
        'get_json': true,
    }

    $.ajax({
        url: '/scripts/unprocessed_base_excel/unprocessed_base_excel.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('.buttons-by-sources').html('');
            let buttons = '';

            if(response) {
                if($('.btn-sourcesf-all').length === 0) {
                    $('.buttons-by-statuses').prepend("<button class='btn btn-sourcesf btn-sourcesf-all lead-bg' data-sourcesf=''>Все</button>");
                }
                response.forEach((item, index) => {
                    buttons += `<button class='btn btn-sourcesf lead-bg' data-sourcesf="${item['source']}" >${item['source']}</button>`;
                });
                $('.buttons-by-sources').append(buttons);
            }
            else {
                $('.btn-sourcesf-all').remove();
            }    

        },
        error: function (error) {
            if (error.responseText) {
                alert("Ошибка: " + error.responseText);
            } else {
                alert("HTTP ошибка: " + error.status + " - " + error.statusText);
            }
        }
    });    
}

function get_cities_group_sources() {
    let dates = $('#reportrange').val().split(' - ');
    date_start = dates[0].split('/').reverse().join('-') + ' 00:00:00';
    date_end = dates[1].split('/').reverse().join('-') + ' 23:59:59';

    const form_data = {
        'action': 'get_cities_group_sources',
        'date_start': date_start,
        'date_end': date_end,
        'get_json': true,
    }

    $.ajax({
        url: '/scripts/unprocessed_base_excel/unprocessed_base_excel.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('.filter-region').html('');
            let options = '<option value="0" disabled selected>Регион</option>';

            if(response) {
                $('.filter-region').removeClass('d-none');
                response.forEach((item, index) => {
                    options += `<option value="${item['city_group']}">${item['name']}</option>`;
                });                   
            }
            else {
                $('.filter-region').addClass('d-none');
            }
 
            $('.filter-region').append(options);
        },
        error: function (error) {
            if (error.responseText) {
                alert("Ошибка: " + error.responseText);
            } else {
                alert("HTTP ошибка: " + error.status + " - " + error.statusText);
            }
        }
    });    
}

function get_cities_group_for_date() {
    let dates = $('#reportrange').val().split(' - ');
    date_start = dates[0].split('/').reverse().join('-') + ' 00:00:00';
    date_end = dates[1].split('/').reverse().join('-') + ' 23:59:59';

    const form_data = {
        'action': 'get_filtering_cities_group',
        'date_start': date_start,
        'date_end': date_end,
        'get_json': true,
    }

    if(get_current_page().startsWith('unprocessed-base-6.php?p=10')) {
        form_data['user_access'] = 'y';
    }

    if(get_current_page().startsWith('holds.php')) {
        form_data['holds'] = 'y';
    }

    $.ajax({
        url: '/scripts/unprocessed_base/unprocessed_base.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('.select-region').html('');
            let options = '<option value="" selected="">Выбор региона</option>';

            if(response) {
                response.forEach((item, index) => {
                    options += `<option value="${item['id']}">${item['name']}</option>`;
                });                   
            }

            $('.select-region').append(options);
        },
        error: function (error) {
            if (error.responseText) {
                alert("Ошибка: " + error.responseText);
            } else {
                alert("HTTP ошибка: " + error.status + " - " + error.statusText);
            }
        }
    });    
}

function get_cities_for_date() {
    let dates = $('#reportrange').val().split(' - ');
    date_start = dates[0].split('/').reverse().join('-') + ' 00:00:00';
    date_end = dates[1].split('/').reverse().join('-') + ' 23:59:59';

    const form_data = {
        'action': 'get_filtering_cities',
        'date_start': date_start,
        'date_end': date_end,
        'get_json': true,
    }

    if(get_current_page().startsWith('unprocessed-base-6.php?p=10')) {
        form_data['user_access'] = 'y';
    }

    if(get_current_page().startsWith('holds.php')) {
        form_data['holds'] = 'y';
    }

    $.ajax({
        url: '/scripts/unprocessed_base/unprocessed_base.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('.select-city').html('');
            let options = '<option value="" selected="">Выбор города</option>';

            if(response) {
                response.forEach((item, index) => {
                    options += `<option value="${item['city']}">${item['city']}</option>`;
                });                   
            }

            $('.select-city').append(options);
        },
        error: function (error) {
            if (error.responseText) {
                alert("Ошибка: " + error.responseText);
            } else {
                alert("HTTP ошибка: " + error.status + " - " + error.statusText);
            }
        }
    });    
}

function get_operators_for_date() {
    let dates = $('#reportrange').val().split(' - ');
    date_start = dates[0].split('/').reverse().join('-') + ' 00:00:00';
    date_end = dates[1].split('/').reverse().join('-') + ' 23:59:59';

    const form_data = {
        'action': 'get_filtering_operators',
        'date_start': date_start,
        'date_end': date_end,
        'get_json': true,
    }

    if(get_current_page().startsWith('unprocessed-base-6.php?p=10')) {
        form_data['user_access'] = 'y';
    }

    $.ajax({
        url: '/scripts/unprocessed_base/unprocessed_base.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('.select-operator').html('');
            let options = '<option value="" selected="">Выбор пользователя</option>';

            if(response) {
                response.forEach((item, index) => {
                    options += `<option value="${item['id']}">${item['name']}</option>`;
                });                   
            }

            $('.select-operator').append(options);
        },
        error: function (error) {
            if (error.responseText) {
                alert("Ошибка: " + error.responseText);
            } else {
                alert("HTTP ошибка: " + error.status + " - " + error.statusText);
            }
        }
    });    
}

function get_sources_for_date() {
    let dates = $('#reportrange').val().split(' - ');
    date_start = dates[0].split('/').reverse().join('-') + ' 00:00:00';
    date_end = dates[1].split('/').reverse().join('-') + ' 23:59:59';

    const form_data = {
        'action': 'get_filtering_sources',
        'date_start': date_start,
        'date_end': date_end,
        'get_json': true,
    }

    if(get_current_page().startsWith('unprocessed-base-6.php?p=10')) {
        form_data['user_access'] = 'y';
    }

    if(get_current_page().startsWith('holds.php')) {
        form_data['holds'] = 'y';
    }

    $.ajax({
        url: '/scripts/unprocessed_base/unprocessed_base.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('.select-source').html('');
            let options = '<option value="" selected="">Выбор источника</option>';

            if(response) {
                response.forEach((item, index) => {
                    options += `<option value="${item['source']}">${item['source']}</option>`;
                });                   
            }

            $('.select-source').append(options);
        },
        error: function (error) {
            if (error.responseText) {
                alert("Ошибка: " + error.responseText);
            } else {
                alert("HTTP ошибка: " + error.status + " - " + error.statusText);
            }
        }
    });    
}

function get_departments_for_date() {
    let dates = $('#reportrange').val().split(' - ');
    date_start = dates[0].split('/').reverse().join('-') + ' 00:00:00';
    date_end = dates[1].split('/').reverse().join('-') + ' 23:59:59';

    const form_data = {
        'action': 'get_filtering_departments',
        'date_start': date_start,
        'date_end': date_end,
        'get_json': true,
    }

    if(get_current_page().startsWith('unprocessed-base-6.php?p=10')) {
        form_data['user_access'] = 'y';
    }

    $.ajax({
        url: '/scripts/unprocessed_base/unprocessed_base.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('.select-department').html('');
            let options = '<option value="" selected="">Выбор отдела</option>';

            if(response) {
                response.forEach((item, index) => {
                    options += `<option value="${item['department_id']}">${item['department_id']}</option>`;
                });                   
            }

            $('.select-department').append(options);
        },
        error: function (error) {
            if (error.responseText) {
                alert("Ошибка: " + error.responseText);
            } else {
                alert("HTTP ошибка: " + error.status + " - " + error.statusText);
            }
        }
    });    
}

function get_rate() {
    let dates = $('#rate-date-range').val().split(' - ');
    date_start = dates[0].split('/').reverse().join('-') + ' 00:00:00';
    date_end = dates[1].split('/').reverse().join('-') + ' 23:59:59';

    const form_data = {
        'action': 'get_rate',
        'date_start': date_start,
        'date_end': date_end,
        'get_json': true,
    }

	if(get_current_page().startsWith('unprocessed-base-4.php?p=10')) {
        form_data['manual'] = 'r';
    }

    if(get_current_page().startsWith('unprocessed-base-excel.php?p=10')) {
        table = '_excel';
        form_data['is_double'] = 'n';
    }

    $.ajax({
        url: `/scripts/unprocessed_base${table}/unprocessed_base${table}.php`,
        method: 'POST',
        data: form_data,
        success: function (response) {
            if (response) {
                $('#rate-table tbody').empty();
                let current_otdel;
                let current_pos = 0;
                let sum_otdel = 0;
                let i = 0; 
                while(i < response.length) {
                    current_otdel = +response[i]['id_otdel'];
                    current_pos = i;
                    while(i < response.length && current_otdel === +response[i]['id_otdel']) {
                        const tr = $('<tr>');
                        if(current_pos === i) {
                            tr.append($('<th>').text(current_otdel).attr('id', `otdel-${current_otdel}`));
                        }
                        tr.append($('<td>').text(response[i]['operator_id']));
                        tr.append($('<td>').text(response[i]['name']));
                        tr.append($('<td>').text(response[i]['count_request']));
                        $('#rate-table tbody').append(tr);
                        sum_otdel += +response[i]['count_request'];
                        i++;
                    }
                    $(`#otdel-${current_otdel}`).attr('rowspan', i - current_pos);
                    const tr_itog = $('<tr>');
                    tr_itog.append($('<th>').text(`Количество по ${current_otdel} отделу`).attr('colspan', 3)); 
                    tr_itog.append($('<td>').text(sum_otdel).attr('colspan', 1));
                    $('#rate-table tbody').append(tr_itog); 
                    sum_otdel = 0;                        
                }

            } else {
                alert('Не существуют данных за выбранный период');
            }
        },
        error: function (error) {
            if (error.responseText) {
                alert("Ошибка: " + error.responseText);
            } else {
                alert("HTTP ошибка: " + error.status + " - " + error.statusText);
            }
        }
    })
}

function get_plan_for_date() {

    const form_data = {
        'action': 'get_plan',
        'date': date_end,
        'json': true,
    }

    $.ajax({
        url: '/scripts/sale/sale.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('.list-plan').empty();
            
            if (response) {
                let total_sold_request = 0;
                let total_count = 0;
                response.forEach((plan) => {
                    $('.list-plan').append(`<div class="d-flex justify-content-between">
                                        <div class="ml-1 plan-partner-name" title="Партнер">
                                            ${plan.partner_name}
                                        </div>
                                        <div class="d-flex">
                                            <div class="ml-1 plan-current-request" title="Продано">
                                                ${plan.sold_request}
                                            </div>/
                                            <div class="plan-max-request mr-2" title="Необходимо продать">
                                                ${plan.count}
                                            </div>
                                        </div>
                                    </div>`);
                    total_sold_request += +plan.sold_request;
                    total_count += +plan.count;                                   
                });
                $('.list-plan').append(`
                                <div class="d-flex justify-content-between">
                                    <div class="ml-1 plan-partner-name" title="Партнер">
                                        <u>Общее</u>
                                    </div>
                                    <div class="d-flex">
                                        <div class="ml-1 plan-current-request" title="Продано">
                                            ${total_sold_request}
                                        </div>/
                                        <div class="plan-max-request mr-2" title="Необходимо продать">
                                            ${total_count}
                                        </div>
                                    </div>    
                                </div>`);    
            }
        },
        error: function (error) {
            if (error.responseText) {
                alert("Ошибка: " + error.responseText);
            } else {
                alert("HTTP ошибка: " + error.status + " - " + error.statusText);
            }
        }
    })
}

function get_client(request) {

        const id = $(request).find('[name="id"]').text().trim();
        let modal_request = $('.form-request');

        const form_data = {
            'action': 'get_client',
            'request_id': id,
        }

        $.ajax({
            url: '/scripts/unprocessed_base/unprocessed_base.php',
            method: 'POST',
            data: form_data,
            success: function (response) {
                let login_id = $('#login_id').val();
                $(modal_request).find('[name="fio"]').val(response.fio).removeClass('is-invalid');
                $(modal_request).find('[name="phone_number"]').val('7' + response.phone_number);
                $(modal_request).find('[name="city"]').val(response.city).removeClass('is-invalid');
                $(modal_request).find('#auto-city').val(response.region);
                $(modal_request).find('[name="partner"]').val(response.partner);
                if(login_id != 479) {
                    if((get_current_page().startsWith('unprocessed-base-5.php?p=10') || get_current_page().startsWith('unprocessed-base-6.php?p=10') || get_current_page().startsWith('unprocessed-base-7.php?p=10')) && response.status == 15) {
                        $('#form-request-status-legend').text('Статус Холда');
                        $('#date-time-calling label').attr('for', 'date_time_hold_calling');
                        $('#date-time-calling #datepicker-calling').attr('name', 'date_time_hold_calling');
                        if($('#main_status_form').length === 0) {
                            $('#form-request-status-fieldset').before('<input id="main_status_form" type="hidden" name="status" value="15">');
                        }
                        $('#status_form').attr('name', 'hold_status_id').attr('data-status-type', 'lead');
                        $('.statuses-form').html($('.btn-hold-statuses').html()).css('grid-template-columns', 'repeat(auto-fit, minmax(125px, 1fr))');
                        if($('#dt_status_change_box').length === 0) {
                            $('#past_status').before('<div id="dt_status_change_box" class="row mt-2"><div class="col-md-12"><input type="text" name="date_time_status_change" class="form-control d-none"></div></div>');
                        }
                        if(get_current_page().startsWith('unprocessed-base-5.php?p=10') || get_current_page().startsWith('unprocessed-base-7.php?p=10')) {
                            $('#btn-quick-statuses').addClass('d-none');
                        }
                    }
                    
                    if((get_current_page().startsWith('unprocessed-base-5.php?p=10') || get_current_page().startsWith('unprocessed-base-6.php?p=10') || get_current_page().startsWith('unprocessed-base-7.php?p=10')) && response.status != 15) {
                        $('#form-request-status-legend').text('Статус');
                        $('#date-time-calling label').attr('for', 'date_time_status_change');
                        $('#date-time-calling #datepicker-calling').attr('name', 'date_time_status_change');
                        $('#main_status_form').remove();
                        $('#status_form').attr('name', 'status').attr('data-status-type', 'regular');
                        $('.statuses-form').html($('.btn-statuses').html()).css('grid-template-columns', 'repeat(auto-fit, minmax(130px, 1fr))');
                        $('#dt_status_change_box').remove();
                        if(get_current_page().startsWith('unprocessed-base-5.php?p=10') || get_current_page().startsWith('unprocessed-base-7.php?p=10')) {
                            $('#btn-quick-statuses').removeClass('d-none');
                        }
                    }

                    $(modal_request).find('[name="debt_banks"]').val(response.debt_banks).removeClass('is-invalid');
                    $(modal_request).find('[name="debt_mfo"]').val(response.debt_mfo).removeClass('is-invalid');
                    $(modal_request).find('[name="taxes_fines"]').val(response.taxes_fines).removeClass('is-invalid');
                    $(modal_request).find('[name="debt_zhkh"]').val(response.debt_zhkh).removeClass('is-invalid');
                    $(modal_request).find('[name="owners"]').val(response.owners).removeClass('is-invalid');
                    $(modal_request).find('[name="other_movables"]').val(response.other_movables).removeClass('is-invalid');
                    $(modal_request).find('[name="other_early_action"]').val(response.other_early_action).removeClass('is-invalid');
                    $(modal_request).find('[name="additional_comment"]').val(response.additional_comment).removeClass('is-invalid');
                    $(modal_request).find('[name="messenger_phone_number"]').val(response.messenger_phone_number);
                    $(modal_request).find('[name="vopros"]').val('');      

                    $('input[name="delays"]').prop('checked', false).removeClass('is-invalid');
                    $('input[name="mortgage"]').prop('checked', false).removeClass('is-invalid');
                    $('input[name="car_loan"]').prop('checked', false).removeClass('is-invalid');
                    if(response.delays) {
                        $(`input[name="delays"][value="${response.delays}"]`).prop('checked', true);
                    } 
                    if(response.mortgage) {
                        $(`input[name="mortgage"][value="${response.mortgage}"]`).prop('checked', true);
                    } 
                    if(response.car_loan) {
                        $(`input[name="car_loan"][value="${response.car_loan}"]`).prop('checked', true);
                    } 

                    $('input[name="real_estate[]"]').prop('checked', false).removeClass('is-invalid');
                    if(response.selected_real_estate) {
                        const real_estate = response.selected_real_estate.split(",");
                        $('input[name="real_estate[]"]').each(function(index, element) {
                            if(real_estate.includes($(element).val())) {
                                $(element).prop('checked', true);
                            }
                        });
                    }
                    $('input[name="movables[]"]').prop('checked', false).removeClass('is-invalid');
                    if(response.selected_movables) {
                        const movables = response.selected_movables.split(",");
                        $('input[name="movables[]"]').each(function(index, element) {
                            if(movables.includes($(element).val())) {
                                $(element).prop('checked', true);
                            }
                        });
                    }
                    $('input[name="early_action[]"]').prop('checked', false).removeClass('is-invalid');
                    if(response.selected_early_action) {
                        const early_action = response.selected_early_action.split(",");
                        $('input[name="early_action[]"]').each(function(index, element) {
                            if(early_action.includes($(element).val())) {
                                $(element).prop('checked', true);
                            }
                        });
                    }                
                    $('input[name="messengers[]"]').prop('checked', false);
                    if(response.selected_messengers) {
                        const messengers = response.selected_messengers.split(",");
                        $('input[name="messengers[]"]').each(function(index, element) {
                            if(messengers.includes($(element).val())) {
                                $(element).prop('checked', true);
                            }
                        });
                    }

                    $('.invalid-delays').addClass('d-none');
                    $('.invalid-real-estate').addClass('d-none');  
                    $('.invalid-movables').addClass('d-none');  
                    $('.invalid-city').addClass('d-none'); 
                    $('.invalid-fio').addClass('d-none');   
                    $('.invalid-debt').addClass('d-none');  
                    $('.invalid-mortgage').addClass('d-none');  
                    $('.invalid-car-loan').addClass('d-none');  
                    $('.invalid-early-action').addClass('d-none'); 
                    $('.invalid-owners').addClass('d-none'); 
                    $('.invalid-additional-comment').addClass('d-none'); 
                    $('.validation-warning').addClass('d-none');   
                    $('#fields-for-details').addClass('d-none');
                    $('.wrapper-show-all-fields').removeClass('d-none');
                }
                else {
                    $(modal_request).find('textarea[name="vopros"]').val(response.vopros);
                }
                
                $('.btn-status-form').removeClass('btn-success');
                $('#datepicker-calling').removeClass('is-invalid');

                if((get_current_page().startsWith('holds.php') || $('#status_form').attr('data-status-type') == 'lead') && response.hold_status_id) {
                    $(modal_request).find('[name="hold_status_id"]').val(response.hold_status_id);
                    $(`.btn-status-form[data-status-id=${response.hold_status_id}]`).addClass('btn-success');
                } else if(get_current_page().startsWith('holds.php') || $('#status_form').attr('data-status-type') == 'lead') {
                    $(modal_request).find('[name="hold_status_id"]').val('');
                } else {
                    $(modal_request).find('[name="status"]').val(response.status);
                    $(`.btn-status-form[data-status-id=${response.status}]`).addClass('btn-success');                    
                }
                
                if (response.status == 6 || response.hold_status_id == 35) {
                    let date_time = response.status == 6 ? response.date_time_status_change : response.date_time_hold_calling;
                    const dateObject = moment(date_time);
                    $('#datepicker-calling').data('daterangepicker').setStartDate(dateObject);
                    $('#datepicker-calling').val(dateObject.format('DD.MM.YYYY HH:mm'));
                    $('#date-time-calling').removeClass('d-none');
                } else {
                    $('#date-time-calling').addClass('d-none');
                    $('#datepicker-calling').val('Не установлено');
                }  

                $(modal_request).find('[name="past_status"]').val(response.status);
                $('.checkbox-update-status').prop("checked", true); 
                $('.invalid-date-time').addClass('d-none');

            },
            error: function (error) {
                if (error.responseText) {
                    alert("Ошибка: " + error.responseText);
                } else {
                    alert("HTTP ошибка: " + error.status + " - " + error.statusText);
                }
            }
        });   

}

function get_status_color(status) {
        let color_class = '';

        switch (parseInt(status)) {
            case 8:  // Недозвон
                color_class = 'non-call-bg';
                break;
            case 11: // Отказ
                color_class = 'rejection-bg';
                break;
            case 15:  // Лид
                color_class = 'save-today-bg';
                break;
            case 6:  // Созвон
                color_class = 'calling-bg';
                break;
            case 17:  // Договор
                color_class = 'in-work-bg';
                break;
            case 9:  //Брак
                color_class = 'defect-bg';
                break;
            case 18:  // В работе
                color_class = 'lead-bg';
                break; 
            case 19:  // Дубль
                color_class = 'double-bg';
                break; 
            case 20:  // Потерялся
                color_class = 'got-lost-bg';
                break;
            case 21:  // Долг менее 300 тысяч
                color_class = 'less-than-300-bg';
                break;    
            case 22:  // Автоответчик
                color_class = 'answering-machine-bg';
                break;  
            case 23:  // Ипотека - единственное жилье
                color_class = 'mortgage-only-housing-bg';
                break;    
            case 24:  // Залог/Автокредит
                color_class = 'collateral-car-loan-bg';
                break; 
            case 25:  // Ипотека + Имущество
                color_class = 'mortgage-property-bg';
                break;    
            case 26:  // Много имущества
                color_class = 'lots-of-property-bg';
                break;  
            case 27:  // Плохой контакт (битый номер)
                color_class = 'bad-contact-bg';
                break;    
            case 28:  // Негатив/Неадыкват
                color_class = 'negative-bg';
                break;    
            case 29:  // Уже банкрот (Менее 5 лет)
                color_class = 'already-bankrupt-bg';
                break;    
            case 30:  // Бросил трубку
                color_class = 'hung-up-bg';
                break; 
            case 31:  // Организация
                color_class = 'organization-bg';
                break;  
            case 32:  // Сброс-ЦЕЛЕВОЙ
                color_class = 'hung-up-target-bg';
                break; 
            case 36:  // Запрет МАВ
                color_class = 'ban-mav-bg';
                break;          
            case 5:  // Слив
                color_class = 'sliv-bg';
                break;    
            case 37:  // Выставлен счет
                color_class = 'invoice-issued-bg';
                break;                                   
        }
        
        return color_class;
}

function get_quantity_by_statuses() {
        let dates = $('#reportrange').val().split(' - ');
        date_start = dates[0].split('/').reverse().join('-') + ' 00:00:00';
        date_end = dates[1].split('/').reverse().join('-') + ' 23:59:59';

        const form_data = {
            'action': 'get_quantity_by_statuses',
            'date_start': date_start,
            'date_end': date_end,
            'get_json': true,
        }

        if (filter) {
            form_data['filter'] = filter;
        }

        if(get_current_page().startsWith('unprocessed-base-3.php?p=10')) {
            form_data['is_double'] = 'y';
        }

        if(get_current_page().startsWith('unprocessed-base-1.php?p=10') || get_current_page().startsWith('unprocessed-base-5.php?p=10') || get_current_page().startsWith('unprocessed-base-6.php?p=10')) {
            form_data['is_double'] = 'n';
        }

        if(get_current_page().startsWith('unprocessed-base-6.php?p=10')) {
            form_data['user_access'] = 'y';
        }

        if(get_current_page().startsWith('unprocessed-base-4.php?p=10')) {
            form_data['manual'] = 'r';
            form_data['is_double'] = 'n';
        }

        if(get_current_page().startsWith('unprocessed-base-excel.php?p=10')) {
            table = '_excel';
            form_data['is_double'] = 'n';
        }

        $.ajax({
            url: `/scripts/unprocessed_base${table}/unprocessed_base${table}.php`,
            method: 'POST',
            data: form_data,
            success: function (response) {
                $('.buttons-by-statuses').html('');

                let buttons = '';
                let count_all = 0;
                response.forEach((item, index) => {
                    count_all += +item['count_status'];
                    
                });
                buttons += `<button class='btn btn-status' data-status='' >Все <span class='badge badge-light'>${count_all}</span></button>`;
                response.forEach((item, index) => {
                    let status_id = item['status_id'];
                    if(item['status_id'] == 15) {
                        status_id = 18;
                    }
                    buttons += `<button class='btn btn-status ${get_status_color(status_id)}' data-status='${item['status_id']}' >${item['status_name']} <span class='badge badge-light'>${item['count_status']}</span></button>`;
                });    
                $('.buttons-by-statuses').append(buttons);
            },
            error: function (error) {
                if (error.responseText) {
                    alert("Ошибка: " + error.responseText);
                } else {
                    alert("HTTP ошибка: " + error.status + " - " + error.statusText);
                }
            }
        });    
}

function get_today_calls() {

        const form_data = {
            'action': 'get_today_calls',
            'get_json': true,
        }

        if (filter) {
            form_data['filter'] = filter;
        }

        $.ajax({
            url: `/scripts/unprocessed_base/unprocessed_base.php`,
            method: 'POST',
            data: form_data,
            success: function (response) {
                $('.btn-calls-today span').text(response.calls_today);
            },
            error: function (error) {
                if (error.responseText) {
                    alert("Ошибка: " + error.responseText);
                } else {
                    alert("HTTP ошибка: " + error.status + " - " + error.statusText);
                }
            }
        });    

}

function get_quantity_by_hold_statuses() {
        let dates = $('#reportrange').val().split(' - ');
        date_start = dates[0].split('/').reverse().join('-') + ' 00:00:00';
        date_end = dates[1].split('/').reverse().join('-') + ' 23:59:59';

        const form_data = {
            'action': 'get_quantity_by_hold_statuses',
            'date_start': date_start,
            'date_end': date_end,
            'get_json': true,
        }

        if (filter) {
            form_data['filter'] = filter;
        }

        $.ajax({
            url: `/scripts/unprocessed_base/unprocessed_base.php`,
            method: 'POST',
            data: form_data,
            success: function (response) {
                $('.buttons-by-statuses').html('');

                let buttons = '';
                buttons += `<button class='btn btn-status btn-secondary' data-status='' >Все</button>`;
                response.forEach((item, index) => {
                    buttons += `<button class='btn btn-status btn-info' data-status='${item['status_id']}' >${item['status_name']} <span class='badge badge-light'>${item['count_status']}</span></button>`;
                });    
                $('.buttons-by-statuses').append(buttons);
            },
            error: function (error) {
                if (error.responseText) {
                    alert("Ошибка: " + error.responseText);
                } else {
                    alert("HTTP ошибка: " + error.status + " - " + error.statusText);
                }
            }
        });    
}

function record_audio(phone_number, src_value) {

    $.ajax({
        method: "POST",
        url: "/templates/record-audio.php",
        data: {
                phone_number: phone_number,
                src_value: src_value,
              },
        success: function(data) {
            console.log(data);     
        }
    });

}

function save_audiorecording(phone_number) {

    $.ajax({
        method: "POST",
        url: "/templates/search-audio.php",
        data: {phone_number: phone_number},
        success: function(data) {
            console.log(data);
            if (data !== '0') {

                const src_match = data.field_214.match(/src\s*=\s*(?:"([^"]+)"|'([^']+)'|([^\s>]+))/i);
                let src_value = null;
                if (src_match) {
                    src_value = src_match[1] || src_match[2] || src_match[3];
                }
                console.log(src_value);

                record_audio(phone_number, src_value);
            }            
        }
    });

}

function validate_form(status) {

    let is_valid = false;
    let login_id = $('#login_id').val();

    if(login_id == 479) {
        return true;
    }

    if(status == 6 && $('.form-request').find('[name="date_time_status_change"]').val() == 'Не установлено') {

        $('.form-request').find('[name="date_time_status_change"]').addClass('is-invalid');
        $('.invalid-date-time').removeClass('d-none');

    } else if(status == 35 && $('.form-request').find('[name="date_time_hold_calling"]').val() == 'Не установлено') {

        $('.form-request').find('[name="date_time_hold_calling"]').addClass('is-invalid');
        $('.invalid-date-time').removeClass('d-none');

    } else if(status == 21 && +$('.form-request').find('[name="debt_banks"]').val() + +$('.form-request').find('[name="debt_mfo"]').val() + +$('.form-request').find('[name="taxes_fines"]').val() + +$('.form-request').find('[name="debt_zhkh"]').val() <= 0) {

        if(+$('.form-request').find('[name="debt_banks"]').val() + +$('.form-request').find('[name="debt_mfo"]').val() + +$('.form-request').find('[name="taxes_fines"]').val() + +$('.form-request').find('[name="debt_zhkh"]').val() <= 0) {
            $('.form-request').find('[name="debt_banks"]').addClass('is-invalid');
            $('.form-request').find('[name="debt_mfo"]').addClass('is-invalid');
            $('.form-request').find('[name="taxes_fines"]').addClass('is-invalid');
            $('.form-request').find('[name="debt_zhkh"]').addClass('is-invalid');
            $('.invalid-debt').removeClass('d-none');            
        }    

    } else if(status == 25 && ($('input[name="mortgage"]:checked').length === 0 || $('input[name="real_estate[]"]:checked').length === 0)) {

        if($('input[name="mortgage"]:checked').length === 0) {
            $('input[name="mortgage"]').addClass('is-invalid');
            $('.invalid-mortgage').removeClass('d-none');            
        }

        if($('input[name="real_estate[]"]:checked').length === 0) {
            $('input[name="real_estate[]"]').addClass('is-invalid');
            $('.invalid-real-estate').removeClass('d-none');            
        }

    } else if(status == 24 && ($('input[name="real_estate[]"]:checked').length === 0 && $('input[name="movables[]"]:checked').length === 0 && $('.form-request').find('[name="other_movables"]').val() == '')) {

        $('input[name="real_estate[]"]').addClass('is-invalid');
        $('.invalid-real-estate').removeClass('d-none');          
        $('input[name="movables[]"]').addClass('is-invalid');
        $('.form-request').find('[name="other_movables"]').addClass('is-invalid');
        $('.invalid-movables').removeClass('d-none');            

    } else if(status == 32 && $('.form-request').find('[name="additional_comment"]').val() == '') {

        $('.form-request').find('[name="additional_comment"]').addClass('is-invalid');
        $('.invalid-additional-comment').removeClass('d-none');

    } else if(status == 15 && ($('.form-request').find('[name="city"]').val() == '' || $('.form-request').find('[name="fio"]').val() == '' ||
           +$('.form-request').find('[name="debt_banks"]').val() + +$('.form-request').find('[name="debt_mfo"]').val() + +$('.form-request').find('[name="taxes_fines"]').val() + +$('.form-request').find('[name="debt_zhkh"]').val() <= 0 ||
           $('input[name="delays"]:checked').length === 0 || $('input[name="mortgage"]:checked').length === 0 || $('input[name="car_loan"]:checked').length === 0 ||
           ($('input[name="early_action[]"]:checked').length === 0 && $('.form-request').find('[name="other_early_action"]').val() == '') ||
           (+$('.form-request').find('[name="debt_zhkh"]').val() > 0 && +$('.form-request').find('[name="owners"]').val() === 0))) {
        
        if($('.form-request').find('[name="city"]').val() == '') {
            $('.form-request').find('[name="city"]').addClass('is-invalid');
            $('.invalid-city').removeClass('d-none');            
        }      

        if($('.form-request').find('[name="fio"]').val() == '') {
            $('.form-request').find('[name="fio"]').addClass('is-invalid');
            $('.invalid-fio').removeClass('d-none');            
        }   

        if(+$('.form-request').find('[name="debt_banks"]').val() + +$('.form-request').find('[name="debt_mfo"]').val() + +$('.form-request').find('[name="taxes_fines"]').val() + +$('.form-request').find('[name="debt_zhkh"]').val() <= 0) {
            $('.form-request').find('[name="debt_banks"]').addClass('is-invalid');
            $('.form-request').find('[name="debt_mfo"]').addClass('is-invalid');
            $('.form-request').find('[name="taxes_fines"]').addClass('is-invalid');
            $('.form-request').find('[name="debt_zhkh"]').addClass('is-invalid');
            $('.invalid-debt').removeClass('d-none');            
        }        

        if($('input[name="delays"]:checked').length === 0) {
            $('input[name="delays"]').addClass('is-invalid');
            $('.invalid-delays').removeClass('d-none');            
        }

        if($('input[name="mortgage"]:checked').length === 0) {
            $('input[name="mortgage"]').addClass('is-invalid');
            $('.invalid-mortgage').removeClass('d-none');            
        }

        if($('input[name="car_loan"]:checked').length === 0) {
            $('input[name="car_loan"]').addClass('is-invalid');
            $('.invalid-car-loan').removeClass('d-none');            
        }

        if($('input[name="early_action[]"]:checked').length === 0 && $('.form-request').find('[name="other_early_action"]').val() == '') {
            $('input[name="early_action[]"]').addClass('is-invalid');
            $('.form-request').find('[name="other_early_action"]').addClass('is-invalid');
            $('.invalid-early-action').removeClass('d-none');            
        }

        if(+$('.form-request').find('[name="debt_zhkh"]').val() > 0 && +$('.form-request').find('[name="owners"]').val() === 0) {
            $('input[name="owners"]').addClass('is-invalid');
            $('.invalid-owners').removeClass('d-none');  
        } 

        if(is_fills_lead == false) {
            is_fills_lead = true;
            start_lead_filling();
        }       

    } else if(+$('.form-request').find('[name="debt_zhkh"]').val() > 0 && +$('.form-request').find('[name="owners"]').val() === 0) {

        $('input[name="owners"]').addClass('is-invalid');
        $('.invalid-owners').removeClass('d-none');  

    } else {
        is_valid = true;
    }

    if(!is_valid) {
        $('#fields-for-details').removeClass('d-none');
        $('.wrapper-show-all-fields').addClass('d-none');          
        $('.validation-warning').removeClass('d-none');
    }

    return is_valid;
}

function save_request(status) {

    let login_id = $('#login_id').val();
    let hold_status = 0;
    let is_hold = 'n';

    if(get_current_page().startsWith('holds.php') || $('#status_form').attr('data-status-type') == 'lead') {
        if(status) {
           hold_status = status; 
        }
        status = 15;
        is_hold = 'y';
    }

    if($('.form-request').find('[name="past_status"]').val() == 15 && status != 15) {
        alert('Ошибка! Смена статуса запрещена, обратитесь к супервайзеру!');
    } else if((get_current_page().startsWith('holds.php') || $('#status_form').attr('data-status-type') == 'lead') && hold_status === 0) {
        alert('Невозможно сохранить! Не выбран подстатус Холда!');
    }
    else {    
        let verifiable_status = status;
        if(get_current_page().startsWith('holds.php') || $('#status_form').attr('data-status-type') == 'lead') {
            verifiable_status = hold_status;
        }

        if(validate_form(verifiable_status)) {
            let is_logging = 'n';
            if($('.form-request').find('[name="past_status"]').val() != status || $('.checkbox-update-status').is(':checked')) {
                is_logging = 'y';
            }  
            if(status == 15 && login_id != 479 && !get_current_page().startsWith('holds.php') && $('#status_form').attr('data-status-type') != 'lead') {
                const vopros = get_vopros();
                $('.form-request').find('[name="vopros"]').val(vopros);
            }
            const real_estate = [];
            const movables = [];
            const early_action = [];
            const messengers = [];
            const form_data = {
                'params': $('.form-request').serializeArray().filter(function (item) {
                    if (item.name === 'date_time_status_change' && status == 6) {
                        var momentDate = moment(item.value, 'DD.MM.YYYY HH:mm');
                        item.value = momentDate.format('YYYY-MM-DDTHH:mm:ss');
                    }

                    if (item.name === 'date_time_hold_calling' && hold_status == 35) {
                        var momentDate = moment(item.value, 'DD.MM.YYYY HH:mm');
                        item.value = momentDate.format('YYYY-MM-DDTHH:mm:ss');
                    }

                    if (item.name === 'date_time_lead_save' && status == 15) {
                        const now1 = moment();
                        item.value = now1.format('YYYY-MM-DD HH:mm:ss');
                    }

                    if(item.name === 'date_time_of_last_save') {
                        if($('.form-request').find('[name="past_status"]').val() != status || $('.checkbox-update-status').is(':checked')) {
                            const now2 = moment();
                            item.value = now2.format('YYYY-MM-DD HH:mm:ss');
                        }                    
                    }

                    if (item.name === 'status') {
                        item.value = status;
                    }

                    if (item.name === 'real_estate[]') {
                        real_estate.push(item.value);
                        item.value = '';
                    }                

                    if (item.name === 'movables[]') {
                        movables.push(item.value);
                        item.value = '';
                    }  

                    if (item.name === 'early_action[]') {
                        early_action.push(item.value);
                        item.value = '';
                    }  

                    if (item.name === 'messengers[]') {
                        messengers.push(item.value);
                        item.value = '';
                    }  

                    return item.value !== '' && item.value !== 'Не установлено';
                }),
                'action': 'update_request',
                'id': $('#idval').val(),
                'status': status,
                'logging': is_logging,
                'real_estate': real_estate,
                'movables': movables,
                'early_action': early_action,
                'messengers': messengers,
                'holds': is_hold,
                'hold_status': hold_status,
            }

            if(get_current_page().startsWith('unprocessed-base-4.php?p=10')) {
                form_data['manual'] = 'r';
            }
            
            if(get_current_page().startsWith('unprocessed-base-excel.php?p=10')) {
                table = '_excel';
                form_data['is_double'] = 'n';
            }

            $.ajax({
                url: `/scripts/unprocessed_base${table}/unprocessed_base${table}.php`,
                method: 'POST',
                data: form_data,
                success: function (response) {
                    
                    if(response['warning']) {
                        alert(response['warning']);
                    }
                    else {              
                        delete response['operator_id'];
                        const id_request = $('#idval').val();
                        let td = $(`[tr-id="${id_request}"]`).find('td');
                        td.filter('[name]').each(function () {
                            const name = $(this).attr('name');
                            if (response.hasOwnProperty(name)) {
                                let elem = response[name];
                                if (name === 'status_name' && !get_current_page().startsWith('lead-sales.php')) {
                                    elem = `<div class="status_name">${elem}</div>`;
                                }
                                else if (name === 'date_time_status_change' || name === 'date_time_hold_calling') {
                                    let date_status = name === 'date_time_status_change' ? response['date_time_status_change'] : response['date_time_hold_calling'];
                                    const date_object = moment(date_status);
                                    let rus_date = date_object.format('DD.MM.YYYY HH:mm');
                                    if (rus_date == 'Invalid date') {
                                        rus_date = '';
                                        date_status = '';
                                    }
                                    elem = name === 'date_time_status_change' ? `<div class="date_time_status_change" attr-date="${date_status}">${rus_date}</div>` : rus_date;
                                }
                                $(this).html(elem);
                            }
                        });
                        td.filter('[name="user_id"]').attr('attr-id', response.operator_id);
                        td.filter('[name="status_name"]').attr('attr-id', response.status);
                        td.filter('[name="vopros"]').attr('full-vopros', response.vopros);
                        td.find('.status_name').attr('data-lead', response.is_lead);
                        $(td).find('.change_operator').val(response.operator_id);
                        $($(td).closest('tr')).removeClass().addClass(get_status_color(response.status));

                        if(get_current_page().startsWith('holds.php')) {
                            get_quantity_by_hold_statuses();
                            get_today_calls();
                        } else if(!get_current_page().startsWith('unprocessed-base-7.php?p=10')) {
                            get_quantity_by_statuses();
                        }
                        is_closed_by_button = true;
                        if(is_fills_lead == true) {
                            is_fills_lead = false;
                            if(response.status != 15) {
                                delete_lead_filling();
                            }
                        }
                        $('#signup-modal').modal('hide');
                        //disable_dnd_mode2();
                        //console.log('Завершение сохранения и отправки запроса');
                        setTimeout(() => save_audiorecording(response.phone_number), 15000);
                    }
                },
                error: function (error) {
                    if (error.responseText) {
                        alert("Ошибка: " + error.responseText);
                    } else {
                        alert("HTTP ошибка: " + error.status + " - " + error.statusText);
                    }
                }
            });
        }        
    }
        
}

function disable_dnd_mode2() {

    console.log('Выполнение функции disable_dnd_mode');
    if($('#mode_dnd').length > 0 && $('#mode_dnd').val() == 'enable') {        
        $.ajax({
            url: '/scripts/unprocessed_base/unprocessed_base.php',
            method: 'POST',
            data: {
                'action': 'disable_dnd_mode',
            },
            success: function (response) {
                $('#mode_dnd').val('');
                console.log('Запрос выполнен');
                console.log(response);
            },
            error: function (error) {
                if (error.responseText) {
                    alert("Ошибка: " + error.responseText);
                } else {
                    alert("HTTP ошибка: " + error.status + " - " + error.statusText);
                }
            }
        });
    }
}

function get_vopros() {
    let debt_text = "";
    const debt_banks_val = $('.form-request').find('[name="debt_banks"]').val();
    if(debt_banks_val > 0) {
        debt_text += `банки - ${debt_banks_val}`;
    }
    const debt_mfo_val = $('.form-request').find('[name="debt_mfo"]').val();
    if(debt_mfo_val > 0) {
        if(debt_text.length > 0) {
            debt_text += ", ";
        }
        debt_text += `МФО - ${debt_mfo_val}`;
    }
    const taxes_fines_val = $('.form-request').find('[name="taxes_fines"]').val();
    if(taxes_fines_val > 0) {
        if(debt_text.length > 0) {
            debt_text += ", ";
        }
        debt_text += `налоги, штрафы - ${taxes_fines_val}`;
    }
    const debt_zhkh_val = $('.form-request').find('[name="debt_zhkh"]').val();
    if(debt_zhkh_val > 0) {
        if(debt_text.length > 0) {
            debt_text += ", ";
        }
        debt_text += `ЖКХ - ${debt_zhkh_val}`;
    }
    const owners_val = $('.form-request').find('[name="owners"]').val();
    if(owners_val > 0) {
        debt_text += `. Прописанных/собственников - ${owners_val}`;
    }

    const delays_val = $('input[name="delays"]:checked').val();
    let delays_text = "";
    if(delays_val) {
       delays_text = delays_val === 'y' ? 'да' : 'нет'; 
    }
    const mortgage_val = $('input[name="mortgage"]:checked').val();
    let mortgage_text = "";
    if(mortgage_val) {
        mortgage_text = mortgage_val === 'm' ? 'да+еще недвижимость' : mortgage_val === 's' ? 'да, единственная' : 'нет';
    }
    const car_loan_val = $('input[name="car_loan"]:checked').val();
    let car_loan_text = "";
    if(car_loan_val) {
        car_loan_text = car_loan_val === 'y' ? 'да' : 'нет';
    }

    let real_estate_text = [];                 
    $('input[name="real_estate[]"]').each(function(index, element) {
        if($(element).is(':checked')) {
            let id_elem = $(element).attr('id');
            let text_elem = $(`label[for=${id_elem}]`).text().trim().toLowerCase();
            real_estate_text.push(text_elem);
        }
    });
    if(real_estate_text.length == 0) {
        real_estate_text.push('нет');
    }
    
    let movables_text = [];                 
    $('input[name="movables[]"]').each(function(index, element) {
        if($(element).is(':checked')) {
            let id_elem = $(element).attr('id');
            let text_elem = $(`label[for=${id_elem}]`).text().trim().toLowerCase();
            movables_text.push(text_elem);
        }
    });
    let other_movables_text = $('.form-request').find('[name="other_movables"]').val();
    if(other_movables_text != '') {
        movables_text.push(other_movables_text);
    }
    if(movables_text.length == 0) {
        movables_text.push('нет');
    }

    let early_action_text = [];                 
    $('input[name="early_action[]"]').each(function(index, element) {
        if($(element).is(':checked')) {
            let id_elem = $(element).attr('id');
            let text_elem = $(`label[for=${id_elem}]`).text().trim().toLowerCase();
            early_action_text.push(text_elem);
        }
    });
    let other_early_action_text = $('.form-request').find('[name="other_early_action"]').val();
    if(other_early_action_text != '') {
        early_action_text.push(other_early_action_text);
    }

    let messengers_text = [];                 
    $('input[name="messengers[]"]').each(function(index, element) {
        if($(element).is(':checked')) {
            let id_elem = $(element).attr('id');
            let text_elem = $(`label[for=${id_elem}]`).text().trim().toLowerCase();
            messengers_text.push(text_elem);
        }
    });
    let messenger_phone_number_text = $('.form-request').find('[name="messenger_phone_number"]').val();
    if(messenger_phone_number_text != '' && messengers_text.length > 0) {
        messengers_text.push(`привязанный номер - ${messenger_phone_number_text}`);
    }
    if(messengers_text.length == 0) {
        messengers_text.push('нет');
    }

    let additional_comment = '';
    let additional_comment_text = $('.form-request').find('[name="additional_comment"]').val();
    if(additional_comment_text != '') {
        additional_comment = `\n9. Дополнительный комментарий: ${additional_comment_text}.`;
    }
        
    const vopros = `1. Долги: ${debt_text}.\n2. Просрочки: ${delays_text}.\n3. Ипотека: ${mortgage_text}.\n4. Автокредит: ${car_loan_text}.\n5. Дополнительная недвижимость: ${real_estate_text.join(", ")}.\n6. Движимое имущество: ${movables_text.join(", ")}.\n7. Что предпринимали: ${early_action_text.join(", ")}.\n8. Мессенджеры: ${messengers_text.join(", ")}.${additional_comment}`;
    return vopros;
}

function start_lead_filling() {

    $.ajax({
        url: '/scripts/unprocessed_base/unprocessed_base.php',
        method: 'POST',
        data: {
            'action': 'start_lead_filling',
            'id': $('#idval').val(),
        },
        success: function (response) {
        },
        error: function (error) {
            if (error.responseText) {
                alert("Ошибка: " + error.responseText);
            } else {
                alert("HTTP ошибка: " + error.status + " - " + error.statusText);
            }
        }
    });

}

function delete_lead_filling() {

    $.ajax({
        url: '/scripts/unprocessed_base/unprocessed_base.php',
        method: 'POST',
        data: {
            'action': 'delete_lead_filling',
            'id': $('#idval').val(),
        },
        success: function (response) {
        },
        error: function (error) {
            if (error.responseText) {
                alert("Ошибка: " + error.responseText);
            } else {
                alert("HTTP ошибка: " + error.status + " - " + error.statusText);
            }
        }
    });
    
}

function load_table() {
    get_operators();

    let columns_table = [];

    form_data_table = {
        "action": 'get_unprocessed_base2',
        "date_start": date_eng_format($('#reportrange').data('daterangepicker').startDate.format('DD/MM/YYYY')),
        "date_end": date_eng_format($('#reportrange').data('daterangepicker').endDate.format('DD/MM/YYYY')),
        "is_double": "n",
        "is_limit": false,
        "city_group_filter": $('.select-region').val(), 
        "city_filter": $('.select-city').val(),     
        "user_id_filter": $('.select-operator').val(),            
        "source_filter": $('.select-source').val(),
        "id_otdel_filter": $('.select-department').val(),
        "status_filter": undefined,
        "hold_status_filter": undefined,
        "search_word": search_word_val,
        "is_today_calls": "n",
        "filter_operator_type": filter_type,
    };

    if(get_current_page().startsWith('unprocessed-base-6.php?p=10')) {
        form_data_table.user_access = 'y';
    }

    if(get_current_page().startsWith('unprocessed-base-7.php?p=10')) {
        form_data_table.operator_requests = 'y';
    }

    if(get_current_page().startsWith('holds.php')) {
        form_data_table.holds = 'y';
    }

    if($('th').hasClass('table-chec')) {
        columns_table.push(
            { "data": null,
               "className": "table-chec",
               "render": function(data, type, row, meta) {
                // Добавляем HTML-разметку для ячейки
                    return `<input type="checkbox" id="singleCheckbox2" name="list" value="${data.id}">`;  
                }
            }
        );
    }

    columns_table.push(
        { "data": "id",
            "className": "table-id",
            "createdCell": function(td, cellData, rowData, row, col) {
                $(td).attr('name', 'id');
            }             
        }
    );

    if($('th').hasClass('table-source')) {
        columns_table.push(
            { "data": "source",
               "className": "table-source",
               "createdCell": function(td, cellData, rowData, row, col) {
                    $(td).attr('name', 'source');
                }                      
            }
        );
    }

    columns_table.push(
            { "data": "phone_number", 
               "className": "table-phone",
               "createdCell": function(td, cellData, rowData, row, col) {
                    $(td).attr('name', 'phone_number');
                }                      
            },
            { "data": null,
                "className": "table-name",
                "createdCell": function(td, cellData, rowData, row, col) {
                    $(td).attr('name', 'fio');
                    $(td).attr('attr-fio', rowData.fio);
                },
                "render": function(data, type, row, meta) {
                    return data.fio.length >= 14 ? data.fio.substr(0, 14) + '...' : data.fio;  
                } 
            },
            { "data": "city",
               "className": "table-city",
               "createdCell": function(td, cellData, rowData, row, col) {
                    $(td).attr('name', 'city');
                }    
             },
            { "data": "partner",
               "className": "table-partner d-none",
               "createdCell": function(td, cellData, rowData, row, col) {
                    $(td).attr('name', 'partner');
                }    
             },               
            { "data": null,
               "className": "table-comment",
               "createdCell": function(td, cellData, rowData, row, col) {
                    $(td).attr('name', 'vopros');
                    $(td).attr('full-vopros', rowData.vopros);
                },
               "render": function(data, type, row, meta) {
                    return data.vopros.length > 50 ? `<div class="comment">${data.vopros.substr(0, 50)}...<span class="cursor-pointer show-comment">Показать</span></div>` : data.vopros;  
                }  
            }
    );

    if($('th').hasClass('table-otdel')) {
        columns_table.push(
            { "data": "id_otdel",
               "className": "table-otdel",
               "createdCell": function(td, cellData, rowData, row, col) {
                    $(td).attr('name', 'id_otdel');
                }    
            }
        );
    }

    if($('th').hasClass('table-user')) {
        columns_table.push(
            { "data": "operator_name",
               "className": "table-user",
               "createdCell": function(td, cellData, rowData, row, col) {
                    $(td).attr('name', 'user_name');
                }    
            }
        );
    }

    if($('th').hasClass('table-operator')) {
        columns_table.push(
            { "data": null,
               "className": "table-operator",
                "createdCell": function(td, cellData, rowData, row, col) {
                    $(td).attr('name', 'operator_id');
                    $(td).attr('attr-id', rowData.user_id);
                },
                "render": function(data, type, row, meta) {
                    let selectOperatorsHTML = `<select class="change-operator">
                                                <option></option>`;
                    let selected = '';

                    operators.forEach((operator, index) => {
                        selected = '';
                        if(operator.id == data.user_id) {
                            selected = ' selected'
                        }
                        selectOperatorsHTML += `<option${selected} value="${operator.id}">
                                                    ${operator.id}
                                                </option>`;
                    });

                    selectOperatorsHTML += `</select>`;
                    return selectOperatorsHTML;  
                } 
            }
        );
    }

    columns_table.push(
        { "data": null,
           "className": "table-status",
            "createdCell": function(td, cellData, rowData, row, col) {
                $(td).attr('name', 'status_name');
                $(td).attr('attr-id', rowData.status);
            },
           "render": function(data, type, row, meta) {
                return `<div class="status_name" data-lead="${data.is_lead}">
                            ${data.status_name}
                        </div>`;  
            }  
         },
        { "data": null,
           "className": "table-date_time_status_change",
            "createdCell": function(td, cellData, rowData, row, col) {
                $(td).attr('name', 'date_time_status_change');
                $(td).attr('attr-date', rowData.date_time_status_change);
            },
           "render": function(data, type, row, meta) {
                return data.date_time_status_change && data.date_time_status_change != '0000-00-00 00:00:00' ? moment(data.date_time_status_change, 'YYYY-MM-DD HH:mm:ss').format('DD.MM.YYYY HH:mm') : '';  
            }  
        }
    );

    if(get_current_page().startsWith('holds.php')) {
        columns_table.push(
            { "data": "hold_status_name",
               "className": "table-hold-status",
               "createdCell": function(td, cellData, rowData, row, col) {
                    $(td).attr('name', 'hold_status_name');
                }   
            },
            { "data": null,
               "className": "table-date_time_hold_calling",
               "createdCell": function(td, cellData, rowData, row, col) {
                    $(td).attr('name', 'date_time_hold_calling');
                },
               "render": function(data, type, row, meta) {
                return data.date_time_hold_calling && data.date_time_hold_calling != '0000-00-00 00:00:00' ? moment(data.date_time_hold_calling, 'YYYY-MM-DD HH:mm:ss').format('DD.MM.YYYY HH:mm') : '';  
            }                         
            }                
        );
    }

    columns_table.push(
        { "data": null,
           "className": "table-settings text-center",
           "render": function(data, type, row, meta) {
                let icons_table = `<i class="mdi mdi-border-color open-request" style="cursor: pointer;" data-id="${data.id}"
                        data-toggle="modal" data-target="#signup-modal"></i>`;
                if($('.table-settings').attr('data-call') == 1) {
                    icons_table += `<i id="phone_call" class="mdi mdi-phone-in-talk ml-1 open-request" style="cursor: pointer;" data-id="${data.id}" data-phone="Y"
                        data-toggle="modal" data-target="#signup-modal"></i>`;
                }
                icons_table += `<i class="fa fa-microphone ml-2 listen-audio" aria-hidden="true" style="font-size: 14px;" data-phone="${data.phone_number}" data-toggle="modal"
        data-target="#audio-modal"></i> `;
                if(get_current_page().startsWith('holds.php')) {
                    icons_table += `<i class="fa fa-rub ml-1 sale-btn" style='font-size: 16px;' aria-hidden="true" data-id="${data.id}" data-toggle="modal" data-target="#sale-modal"></i>`;
                }
                return icons_table;  
            }                 
        }  
    );

    data_table = $('#datatable1').DataTable({
        "scrollX": true,
        "bAutoWidth": false,
        "iDisplayLength": 10,
        "lengthMenu": [[10, 30, 50, 100], [10, 30, 50, 100]],
        "processing": true,
        "serverSide": true,
        "ordering": false, // Отключает сортировку глобально
        "columnDefs": [
            {targets: '_all', orderable: false} // Запрет сортировки для всех столбцов
        ],
        "ajax": {
            "url": "/scripts/unprocessed_base/unprocessed_base.php",
            "type": "POST",
            "data": function(d) {
                        return $.extend({}, d, form_data_table);
                    },
        },
        "columns": columns_table,
        "rowCallback": function(row, data, index) {
            $(row).attr('tr-id', data.id);
            let background_tr = get_status_color(data.status);
            if(data.status == 15 && data.is_save_today == 0) {
                background_tr = 'lead-bg';
            }
            if(data.source == 'ГМГ' && data.status == 15) { 
                background_tr += ' blue-lead-bg'; 
            }
            $(row).addClass(background_tr);
        },  
        "language": {
            "sProcessing": "Загрузка...",
            "sLengthMenu": "Показывать _MENU_ записей",
            "sZeroRecords": "Ничего не найдено",
            "sEmptyTable": "Нет данных",
            "sInfo": "Отображение записей от _START_ до _END_ из общего количества _TOTAL_ записей",
            "sInfoEmpty": "Отображение записей от 0 до 0 из общего количества 0 записей",
            "sInfoFiltered": "(отфильтровано из _MAX_ записей)",
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

    $('.dataTables_filter').remove();
}

$(document).ready(function () {

    if(get_current_page().startsWith('unprocessed-base-5.php?p=10') || get_current_page().startsWith('unprocessed-base-6.php?p=10') || get_current_page().startsWith('holds.php')) {
        load_table();
    }

    event_start('click', '.open-request', function (e) {
        const request = $(e).closest('tr');
        const status_element = $(request).find('[name="status_name"]');
        const status_id = status_element.attr('attr-id');

        if($(e).attr('id') == 'phone_call') {
            const phone_number = $(request).find('[name="phone_number"]').text().trim();

            const form_data = {
                'action': 'call_phone',
                'phone_number': phone_number,            
                'status_id': status_id,
            }

            $.ajax({
                url: '/scripts/unprocessed_base/unprocessed_base.php',
                method: 'POST',
                data: form_data,
                success: function (response) {
                    get_client(request);
                },
                error: function (error) {
                    $('#signup-modal').modal('hide');
                    if (error.responseText) {
                        alert("Ошибка: " + error.responseText);
                    } else {
                        alert("HTTP ошибка: " + error.status + " - " + error.statusText);
                    }
                }
            }); 
        } else {
            get_client(request);
        }
        
    });

    event_start('click', '#create-comment', function (e) {
        $(e).addClass('d-none');
        $('.form-comment').removeClass('d-none');
        $('[name="new-comment"]').val('');
    });

    event_start('click', '#create-comment-cancel', function (e) {
        $('#create-comment').removeClass('d-none');
        $('.form-comment').addClass('d-none');
        $('[name="new-comment"]').val('');
    });    

    event_start('click', '.update-comment', function (e) {
        const comment = $(e).closest('.comment');
        $(comment).find('.comment-text').addClass('d-none');
        $(comment).find('.comment-buttons').removeClass('d-flex').addClass('d-none');
        $(comment).find('.form-updated-comment').removeClass('d-none');        
        const text = $(comment).find('.comment-text').text().trim();
        $(comment).find('[name="updated-comment"]').val(text);
    });

    event_start('click', '.update-comment-cancel', function (e) {
        const comment = $(e).closest('.comment');
        $(comment).find('.comment-text').removeClass('d-none');
        $(comment).find('.comment-buttons').addClass('d-flex').removeClass('d-none');
        $(comment).find('.form-updated-comment').addClass('d-none'); 
    });  

    event_start('click', '.save-comment', function (e) {
        const comment_el = $(e).closest('.comment');
        const comment = $(comment_el).find('[name="updated-comment"]').val();
        const comment_id = comment_el.attr('data-id');;
        const id = $('#idval').val();
        const form_data = {
            'action': 'update_comment',
            'id': id,            
            'comment_id': comment_id,
            'comment': comment,
        }
        $.ajax({
            url: '/scripts/unprocessed_base2/unprocessed_base2.php',
            method: 'POST',
            data: form_data,
            success: function (response) {
                let td = $(`[tr-id="${id}"]`).find('td');
                td.filter('[name="vopros"]').attr('full-vopros', response.vopros);
                td.filter('[name="vopros"]').text(response.vopros);
                $(comment_el).find('.comment-text').removeClass('d-none').text(comment);
                $(comment_el).find('.comment-buttons').addClass('d-flex').removeClass('d-none');
                $(comment_el).find('.form-updated-comment').addClass('d-none'); 
            },
            error: function (error) {
                if (error.responseText) {
                    alert("Ошибка: " + error.responseText);
                } else {
                    alert("HTTP ошибка: " + error.status + " - " + error.statusText);
                }
            }
        });  

    });  

    event_start('click', '.open-request2', function (e) {
        const request_id = $(e).closest('tr').attr('tr-id');
        const request = $(e).closest('tr');
        let modal_request = $('.form-request');

        const get_value = (value) => {
            return $(request).find('[name="' + value + '"]').text();
        }

        const set_element = (key) => {
            $(modal_request).find('[name="' + key + '"]').val(get_value(key).trim());
        }

        const operator_id = parseInt($(request).find('[name="user_id"]').attr('attr-id'));
        set_element('phone_number');
        set_element('city');
        set_element('partner');
        set_element('id');
        set_element('сompany_name');
        set_element('sales_department');
        set_element('experience');
        set_element('have_crm');
        set_element('time_difference');
        set_element('job');

        $(modal_request).find('[name="operator"]').val(operator_id);
        const comment = $(request).find('[name="vopros"]').attr('full-vopros');
        $(modal_request).find('[name="vopros"]').val(comment.trim());

        $($(modal_request).find('[name="user_id"]').html($('.hidden-operators').html())).val(
            $(request).find('[name="user_id"]').attr('attr-id')
        );

        const fio = $(request).find('[name="fio"]').attr('attr-fio');
        $(modal_request).find('[name="fio"]').val(fio);
        const status_element = $(request).find('[name="status_name"]');
        const status_id = status_element.attr('attr-id');
        const date_time_status_change = $(request).find('.dt_status_change').attr('attr-date');

        $($(modal_request).find('[name="status"]').html($('.statuses').html())).val(status_id);
        if (status_id == 6 || status_id == 18) {
            const dateObject = moment(date_time_status_change);
            $('#datepicker-calling').data('daterangepicker').setStartDate(dateObject);
            $('#datepicker-calling').val(dateObject.format('DD.MM.YYYY HH:mm'));
            $('#datepicker-calling, .back-to-status').removeClass('d-none');
            $('#status-modal').addClass('d-none');
        } else {
            $('#datepicker-calling, .back-to-status').addClass('d-none');
            $('#status-modal').removeClass('d-none');
            $('#datepicker-calling').val('Не установлено');
        }
        const created_by_user_id = $(request).find('[name="created_by_user"]').attr('data-created-user-id');
        $(modal_request).find('[name="created_by_user_id"]').val(created_by_user_id);

        $("#comments").empty();
        $('#create-comment').removeClass('d-none');
        $('.form-comment').addClass('d-none');
        if($('#show-comments').length) {
            $('#show-comments').remove();
        }

        const form_data = {
            'action': 'get_comments',
            'id': request_id,
        }

        $.ajax({
            url: '/scripts/unprocessed_base2/unprocessed_base2.php',
            method: 'POST',
            data: form_data,
            success: function (response) {
                if(response) {
                    response.forEach((comment, index) => {
                        let [date, time] = comment.date_create.split(' ');
                        let datetime = date.split('-').reverse().join('.') + " " + time.slice(0, 5);
                        $("#comments" ).append( `                    
                            <div class="comment mb-2 p-2 bg-light ${response.length - 1 !== index ? 'previous-comment d-none' : 'last-comment'}" data-id="${comment.id}">
                                <div class="comment-title d-flex justify-content-between">
                                    <div class="comment-number font-weight-bold">Комментарий <span>${index + 1}</span></div>
                                    <div class="comment-date-create">${datetime}</div>
                                </div>
                                <div class="comment-text">
                                    ${comment.comment}
                                </div>
                                <div class="comment-buttons d-flex justify-content-between">
                                    <a href="#" class="update-comment">Редактировать</a>
                                    <a href="#" class="delete-comment">Удалить</a>
                                </div>
                                <form class="form-horizontal form-updated-comment d-none">
                                    <div class="form-group">
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <textarea id="updated-comment" type="text" name="updated-comment" class="form-control bg-light">${comment.comment}</textarea>
                                            </div>
                                        </div> 
                                        <div class="row mt-2">
                                            <button type="button" class="save-comment btn btn-sm btn-success ml-3">Сохранить</button>
                                            <button type="button" class="update-comment-cancel btn btn-sm btn-danger ml-2">Отменить</button>
                                        </div>                
                                    </div>
                                </form>
                            </div>
                        ` );
                    });
                    if(response.length > 1) {
                        $("#comments").after('<button id="show-comments" class="btn btn-info btn-sm ml-3">Показать все</button>');
                    }                    
                }
                else {
                    $("#comments" ).text('Комментариев нет');
                }
            },
            error: function (error) {
                if (error.responseText) {
                    alert("Ошибка: " + error.responseText);
                } else {
                    alert("HTTP ошибка: " + error.status + " - " + error.statusText);
                }
            }
        });      

    });

    event_start('click', '#show-comments', function (e) {
        $(".comment.previous-comment").toggleClass('d-none');
        if(e.text() === 'Показать все') {
            e.text('Скрыть все');
        }
        else {
            e.text('Показать все');
        }    
    });

    event_start('click', '.delete-comment', function (e) {
        const comment_id = $(e).closest('.comment').attr('data-id');
        const id = $('#idval').val();
        let isDelete = confirm("Вы точно хотите удалить?");
        
        if(isDelete) {
            const form_data = {
                'action': 'delete_comment',
                'comment_id': comment_id,
                'id': id,
            }
            $.ajax({
                url: '/scripts/unprocessed_base2/unprocessed_base2.php',
                method: 'POST',
                data: form_data,
                success: function (response) {
                    const isLastComment = $(`.comment[data-id='${comment_id}']`);
                    $(`.comment[data-id='${comment_id}']`).remove();
                    if($('#comments').html().trim() == '') {
                        $("#comments" ).text('Комментариев нет');
                    }
                    else {
                        let index = 0;
                        $('.comment-number span').each((i, el) => {
                            index++;
                            $(el).text(index);
                        });
                        if(isLastComment) {
                            $('#comments .comment:last-child').addClass('last-comment');
                            $('#comments .comment:last-child').removeClass('previous-comment');
                            $('#comments .comment:last-child').removeClass('d-none');   
                        }                     
                    }
                    if($("#comments .comment").length <= 1) {
                        if($('#show-comments').length) {
                            $('#show-comments').remove();
                        }    
                    }
                    let td = $(`[tr-id="${id}"]`).find('td');
                    td.filter('[name="vopros"]').attr('full-vopros', response.vopros);
                    td.filter('[name="vopros"]').text(response.vopros);
                },
                error: function (error) {
                    if (error.responseText) {
                        alert("Ошибка: " + error.responseText);
                    } else {
                        alert("HTTP ошибка: " + error.status + " - " + error.statusText);
                    }
                }
            });            
        }

    });

    event_start('click', '.delete-request', function (e) {
        const request_id = $(e).closest('tr').attr('tr-id');
        let isDelete = confirm("Вы точно хотите удалить?");
        
        if(isDelete) {
            const form_data = {
                'action': 'delete_request',
                'id': request_id,
            }
            $.ajax({
                url: '/scripts/unprocessed_base2/unprocessed_base2.php',
                method: 'POST',
                data: form_data,
                success: function (response) {
                    $(`tr[tr-id=${request_id}]`).remove();
                    let index = 0;
                    $('td.table-id').each((i, el) => {
                        index++;
                        $(el).text(index);
                    });
                },
                error: function (error) {
                    if (error.responseText) {
                        alert("Ошибка: " + error.responseText);
                    } else {
                        alert("HTTP ошибка: " + error.status + " - " + error.statusText);
                    }
                }
            });            
        }

    });

    event_start('click', '#add-new-comment', function (e) {
        const comment = $('[name="new-comment"]').val();
        const id = $('#idval').val();

        const form_data = {
            'action': 'create_comment',
            'id': id,
            'comment': comment,
        }
        $.ajax({
            url: '/scripts/unprocessed_base2/unprocessed_base2.php',
            method: 'POST',
            data: form_data,
            success: function (response) {
                let td = $(`[tr-id="${id}"]`).find('td');
                td.filter('[name="vopros"]').attr('full-vopros', response.vopros);
                td.filter('[name="vopros"]').text(response.vopros);
                $('#create-comment').removeClass('d-none');
                $('.form-comment').addClass('d-none');
                let [date, time] = response.date_create_of_comment.split(' ');
                let datetime = date.split('-').reverse().join('.') + " " + time.slice(0, 5);
                if($("#comments .comment").length === 0) {
                    $("#comments").empty();   
                }
                $('#comments').append(`                    
                            <div class="comment mb-2 p-2 bg-light" data-id="${response.comment_id}">
                                <div class="comment-title d-flex justify-content-between">
                                    <div class="comment-number font-weight-bold">Комментарий <span>${$("#comments .comment").length + 1}</span></div>
                                    <div class="comment-date-create">${datetime}</div>
                                </div>
                                <div class="comment-text">
                                    ${response.vopros}
                                </div>
                                <div class="comment-buttons d-flex justify-content-between">
                                    <a href="#" class="update-comment">Редактировать</a>
                                    <a href="#" class="delete-comment">Удалить</a>
                                </div>
                                <form class="form-horizontal form-updated-comment d-none">
                                    <div class="form-group">
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <textarea id="updated-comment" type="text" name="updated-comment" class="form-control bg-light">${comment.comment}</textarea>
                                            </div>
                                        </div> 
                                        <div class="row mt-2">
                                            <button type="button" class="save-comment btn btn-sm btn-success ml-3">Сохранить</button>
                                            <button type="button" class="update-comment-cancel btn btn-sm btn-danger ml-2">Отменить</button>
                                        </div>                
                                    </div>
                                </form>
                            </div>
                ` );                
            },
            error: function (error) {
                if (error.responseText) {
                    alert("Ошибка: " + error.responseText);
                } else {
                    alert("HTTP ошибка: " + error.status + " - " + error.statusText);
                }
            }
        });  

    });     

    event_start('change', '.select-operator', function (e) {
        current_request = 0;
        let user_id = $(e).val();
        if(user_id) {
           filter['user_id'] = user_id;
        }
        else {
           delete filter['user_id']; 
        }
        form_data_table.user_id_filter = user_id;
        data_table.ajax.reload();
        if(get_current_page().startsWith('holds.php')) {
            get_quantity_by_hold_statuses();
            get_today_calls();
        } else {
            get_quantity_by_statuses();
        }
    });

    event_start('change', '.select-source', function (e) {
        current_request = 0;
        let source = $(e).val();
        if(source) {
           filter['source'] = source;
        }
        else {
           delete filter['source']; 
        }
        form_data_table.source_filter = source;
        data_table.ajax.reload();
        if(get_current_page().startsWith('holds.php')) {
            get_quantity_by_hold_statuses();
            get_today_calls();
        } else {
            get_quantity_by_statuses();
        }
    });

    event_start('change', '.select-region', function (e) {
        current_request = 0;
        let city_group = $(e).val();
        if(city_group) {
           filter['auto_city_group'] = city_group;
        }
        else {
           delete filter['auto_city_group']; 
        }
        form_data_table.city_group_filter = city_group;
        data_table.ajax.reload();
        if(get_current_page().startsWith('holds.php')) {
            get_quantity_by_hold_statuses();
            get_today_calls();
        } else {
            get_quantity_by_statuses();
        }
    });

    event_start('change', '.select-city', function (e) {
        current_request = 0;
        let city = $(e).val();
        if(city) {
           filter['city'] = city;
        }
        else {
           delete filter['city']; 
        }
        form_data_table.city_filter = city;
        data_table.ajax.reload();
        if(get_current_page().startsWith('holds.php')) {
            get_quantity_by_hold_statuses();
            get_today_calls();
        } else {
            get_quantity_by_statuses();
        }
    });

    event_start('change', '.select-department', function (e) {
        current_request = 0;
        let otdel = $(e).val();
        if(otdel) {
           filter['id_otdel'] = otdel;
        }
        else {
           delete filter['id_otdel']; 
        }
        form_data_table.id_otdel_filter = otdel;
        data_table.ajax.reload();
        get_quantity_by_statuses();
    });

    event_start('change', '.select-all-operators', function (e) {
        current_request = 0;
        let user_id = $(e).val();
        if(user_id) {
           filter['user_id'] = user_id;
        }
        else {
           delete filter['user_id']; 
        }
        form_data_table.user_id_filter = user_id;
        data_table.ajax.reload();
        get_quantity_by_hold_statuses();
        get_today_calls();
        
    });

    event_start('change', '.select-sales-operator', function (e) {
        current_request = 0;
        user_id = $(e).val();
        filter['user_id'] = $(e).val();
        $('#table-request').html('');
        get_unprocessed_base2();
        get_counters2();
    });

    event_start('change', '.change-operator', function (e) {
        const user_id = $(e).val();
        const request_id = $(e).closest('tr').attr('tr-id');
        const form_data = {
            'params': [{
                'name': 'user_id',
                'value': user_id,
            }],
            'action': 'update_request',
            'id': request_id,
        }
		if(get_current_page().startsWith('unprocessed-base-4.php?p=10')) {
			form_data['manual'] = 'r';
		}

        if(get_current_page().startsWith('unprocessed-base-excel.php?p=10')) {
            table = '_excel';
            form_data['is_double'] = 'n';
        }

        $.ajax({
            url: `/scripts/unprocessed_base${table}/unprocessed_base${table}.php`,
            method: 'POST',
            data: form_data,
            success: function (response) {
                $(`tr[tr-id="${request_id}"] .table-otdel`).text(response.otdel_id);
                $(`tr[tr-id="${request_id}"] .table-user`).text(response.user_name);
                if($('.select-operator').length !== 0) {
                    get_operators_for_date();
                }              
                if($('.select-department').length !== 0) {
                    get_departments_for_date();
                } 
            },
            error: function (error) {
                if (error.responseText) {
                    alert("Ошибка: " + error.responseText);
                } else {
                    alert("HTTP ошибка: " + error.status + " - " + error.statusText);
                }
            }
        });
    });

    event_start('change', '.change-operator2', function (e) {
        const user_id = $(e).val();
        const request_id = $(e).closest('tr').attr('tr-id');
        const form_data = {
            'params': [{
                'name': 'user_id',
                'value': user_id,
            }],
            'action': 'update_request',
            'id': request_id,
        }
        $.ajax({
            url: '/scripts/unprocessed_base2/unprocessed_base2.php',
            method: 'POST',
            data: form_data,
            success: function (response) {
                console.log(response);
            },
            error: function (error) {
                if (error.responseText) {
                    alert("Ошибка: " + error.responseText);
                } else {
                    alert("HTTP ошибка: " + error.status + " - " + error.statusText);
                }
            }
        });
    });    

    event_start('click', '.search-request', function (e) {
        current_request = 0;
        const search_val = $('.search-input').val();
        if(!get_current_page().startsWith('unprocessed-base-5.php?p=10') && !get_current_page().startsWith('unprocessed-base-6.php?p=10') && !get_current_page().startsWith('unprocessed-base-7.php?p=10') && !get_current_page().startsWith('holds.php')) {
            const search_choice = $('input[name=search]:checked').val();
            if(search_choice === 'go-to') {
                filter = {
                    'id': search_val,
                    'comparison_operator': '<=',
                }
            }
            if(search_choice === 'search') {
                filter = {
                    'id': `%${search_val}%`,
                    'fio': `%${search_val}%`,
                    'phone_number': `%${search_val}%`,
                    'vopros': `%${search_val}%`,
                    'city': `%${search_val}%`,
                    'logical_operator': 'OR',
                    'comparison_operator': 'LIKE',
                }
            }        
            $('#table-request').html('');
            get_unprocessed_base();            
        }
        else {

            if(get_current_page().startsWith('unprocessed-base-7.php?p=10')) {
                if(search_val) {
                    form_data_table.filter_operator_type = '';
                    if(is_table_loaded) {  
                        form_data_table.search_word = search_val;
                        data_table.ajax.reload();
                    } else {
                        is_table_loaded = true;
                        search_word_val = search_val;
                        load_table();
                    }    
                }            
            } else {
                filter = {
                    'id': `%${search_val}%`,
                    'fio': `%${search_val}%`,
                    'phone_number': `%${search_val}%`,
                    'vopros': `%${search_val}%`,
                    'city': `%${search_val}%`,
                    'logical_operator': 'OR',
                    'comparison_operator': 'LIKE',
                }
                if(search_val) {
                    form_data_table.search_word = search_val;
                    form_data_table.status_filter = '';
                    form_data_table.hold_status_filter = '';
                    form_data_table.city_group_filter = '';
                    form_data_table.city_filter = '';
                    form_data_table.user_id_filter = '';
                    form_data_table.source_filter = '';
                    form_data_table.id_otdel_filter = '';
                    form_data_table.is_today_calls = "n";
                }
                else {
                    form_data_table.search_word = undefined
                }
                data_table.ajax.reload();
                if(get_current_page().startsWith('holds.php')) {
                    get_quantity_by_hold_statuses();
                } else {
                    get_quantity_by_statuses();
                }                
            }

        }

    });

    event_start('click', '.csv-request', function (e) {
        current_request = 0;
        const id_start = $('.csv1-input').val();
        const id_end = $('.csv2-input').val();
        //const id_status = $($('.csv-requestf').find('[name="id-status"]').html($(".id-status").html())).val();
        const id_status = $('.csv-requestf').find('[name="id-status"]').val();
		
            filter2 = {
                'id_start': `${id_start}`,
                'id_end': `${id_end}`,		
                'id_status': `${id_status}`,
            }       
        $('#table-request').html('');
        get_unprocessed_base();
    });
	
	//var csv_dwn = document.getElementById('modclick3');
	
    event_start('click', '#modclick3', function (e) {
		//var csv_id = $(this).attr('data-csv-id');
        if(get_current_page().startsWith('unprocessed-base-excel.php?p=10')) {
            table = '_excel';
        }

		location.replace(`https://crm.mkggroup.ru/scripts/unprocessed_base${table}/uploads/data_132.csv`);
    });

    event_start('click', '#btn-export-csv', function (e) {

        console.log($('#sel_rows2').val());
        console.log($('#count_rows2').val());

        if($('#count_rows2').val() != '' || $('#sel_rows2').val() != undefined) {

            const form_data = {
                'submit': true,
                'sel_rows': $('#sel_rows2').val(),
                'count_rows': $('#count_rows2').val(), 
            }

            $.ajax({
                url: '/templates/pages/export-rows-csv.php',
                method: 'POST',
                data: form_data,
                success: function (response) {
                   location.replace("https://crm.mkggroup.ru/templates/pages/export/export_rows.csv");
                   $('#export-csv-modal').modal('hide');
                   $('#count_rows2').val('');
                   
                   let checkbox = document.getElementsByName("list");

                    for(let i = 0; i < checkbox.length; i++){

                        if(checkbox[i].checked) {
                            $(checkbox[i]).prop("checked", false);
                        }
                    
                    }   
                },
                error: function (error) {
                    if (error.responseText) {
                        alert("Ошибка: " + error.responseText);
                    } else {
                        alert("HTTP ошибка: " + error.status + " - " + error.statusText);
                    }
                }
            });
        }

    });

    event_start('click', '.search-request2', function (e) {
        current_request = 0;
        date_start = undefined; 
        date_end = '';
        is_search = true;
        const search_val = $('.search-input2').val();
        filter = {
                'fio': `%${search_val}%`,
                'phone_number': `%${search_val}%`,
                'city': `%${search_val}%`,
                'logical_operator': 'OR',
                'comparison_operator': 'LIKE',
        };
        $('#table-request').html('');
        get_unprocessed_base2();
        get_counters2();  
    });

    event_start('change', '.select-status', function (e) {
        current_request = 0;
        //filter = {};
        filter['status'] = $(e).val();
        defect_bg = 'no';
        $('#table-request').html('');
        get_unprocessed_base();
    });

    event_start('click', '.btn-status', function (e) {

        if(get_current_page().startsWith('holds.php')) {
            form_data_table.hold_status_filter = $(e).attr('data-status');
            form_data_table.is_today_calls = "n";
        } else {
            current_request = 0;
            var status = $(e).attr('data-status');
            if(status) {
               filter['status'] = status;
            }
            else {
               delete filter['status']; 
            }
            defect_bg = 'no';
            filter_by_status = true;        
            form_data_table.status_filter = status;            
        }
        data_table.ajax.reload();
      
    });

    event_start('click', '.btn-calls-today', function (e) {

        form_data_table.is_today_calls = "y";
        data_table.ajax.reload();
      
    });

    event_start('click', '.btn-operator-request', function (e) {

        filter_type = $(e).attr('data-filter-type');
        if(is_table_loaded) {
            form_data_table.filter_operator_type = filter_type;
            search_word_val = undefined;
            form_data_table.search_word = search_word_val;
            data_table.ajax.reload();
        } else {
            is_table_loaded = true;
            search_word_val = undefined;
            load_table();
        }
        
      
    });

    event_start('click', '.btn-status-form', function (e) {

        $('.btn-status-form').removeClass('btn-success');
        var status = $(e).attr('data-status-id');
        $('#status_form').val(status);
        $(`.btn-status-form[data-status-id=${status}]`).addClass('btn-success');
        if(status == 6 || status == 35) {
            $('#date-time-calling').removeClass('d-none');
        } else {
            $('#date-time-calling').addClass('d-none');
        }

        if(status == 15 && is_fills_lead == false) {
            is_fills_lead = true;
            start_lead_filling();
        }
      
    });

    event_start('click', '.show-all-fields', function (e) {

        $('#fields-for-details').removeClass('d-none');
        $('.wrapper-show-all-fields').addClass('d-none');
        if(is_fills_lead == false) {
            is_fills_lead = true;
            start_lead_filling();
        }
      
    });    
	
    event_start('click', '.btn-sourcesf', function (e) {

        current_request = 0;
        var sourcesf = $(e).attr('data-sourcesf');
        if(sourcesf) {
           filter['source'] = sourcesf;
        }
        else {
           delete filter['source']; 
        }
        defect_bg = 'no';        

        $('#table-request').html('');
        get_unprocessed_base();
      
    });




    event_start('click', '.show-comment', function (e) {
        const full_comment = $(e).closest('td').attr('full-vopros');
        $(e).closest('td').html(full_comment);
    });

    event_start('click', '.back-to-status', function (e) {
        $('#datepicker-calling, .back-to-status').addClass('d-none');
        $('#status-modal').removeClass('d-none');
    });

    event_start('change', '#status-modal', function (e) {
        const status_id = $(e).val();
        if (status_id == 6 || status_id == 18) {
            $('#datepicker-calling, .back-to-status').removeClass('d-none');
            $(e).addClass('d-none');
        }
    });

    $('#check-minutes').on('click', function (e) {
        e.preventDefault();

        if(get_current_page().startsWith('unprocessed-base-7.php?p=10')) {
            return;
        }

        current_request = 0;
        const datepicker = $('#reportrange').data('daterangepicker');
        date_start = date_eng_format(datepicker.startDate.format('DD/MM/YYYY'));
        date_end = date_eng_format(datepicker.endDate.format('DD/MM/YYYY'));

        if(get_current_page().startsWith('unprocessed-base-5.php?p=10')) {
            const date1 = new Date(date_start);
            const date2 = new Date(date_end);
            const diffDays = (date2 - date1) / (1000 * 60 * 60 * 24);
            if(diffDays > 30) {
                alert("Слишком большой диапазон дат!");
                return;
            } 
            else {
                $.ajax({
                    url: '/scripts/datepic.php',
                    method: 'POST',
                    data: {
                        'dateselector': $('input[name="dateselector"]').val(),
                    },
                    success: function (response) {

                    },
                    error: function (error) {
                        if (error.responseText) {
                            alert("Ошибка: " + error.responseText);
                        } else {
                            alert("HTTP ошибка: " + error.status + " - " + error.statusText);
                        }
                    }
                });
            }           
        }

        $('#table-request').html('');
        if(get_current_page().startsWith('unprocessed-base-2.php?p=10')) {
            get_unprocessed_base2();
            get_counters2(); 
        } else if (get_current_page().startsWith('unprocessed-base-excel.php?p=10')) {
            filter = {};
            get_unprocessed_base();
            get_counters();
            get_filtering_sources(); 
            get_cities_group_sources();         
        } else if(get_current_page().startsWith('unprocessed-base-5.php?p=10') || get_current_page().startsWith('unprocessed-base-6.php?p=10') || get_current_page().startsWith('holds.php')) {
            filter = {};           
            form_data_table.status_filter = '';
            form_data_table.hold_status_filter = '';
            form_data_table.city_group_filter = '';
            form_data_table.city_filter = '';
            form_data_table.user_id_filter = '';
            form_data_table.source_filter = '';
            form_data_table.id_otdel_filter = '';
            form_data_table.search_word = '';
            form_data_table.date_start = date_start;
            form_data_table.date_end = date_end;
            form_data_table.is_today_calls = "n";
            data_table.ajax.reload();
            get_counters(); 
            if(get_current_page().startsWith('holds.php')) {
                get_quantity_by_hold_statuses();
            } else {
                get_quantity_by_statuses();
            }
            if($('.select-region').length !== 0) {
                get_cities_group_for_date();
            }
            if($('.select-city').length !== 0) {
                get_cities_for_date();
            }            
            if($('.select-operator').length !== 0) {
                get_operators_for_date();
            }              
            if($('.select-source').length !== 0) {
                get_sources_for_date();
            }    
            if($('.select-department').length !== 0) {
                get_departments_for_date();
            }
            $('.search-input').val('');           
        } else {
            delete filter.status;            
            get_unprocessed_base();
            get_counters(); 
            get_quantity_by_statuses();
            if($('.select-region').length !== 0) {
                get_cities_group_for_date();
            }
            if($('.select-city').length !== 0) {
                get_cities_for_date();
            }            
            if($('.select-operator').length !== 0) {
                get_operators_for_date();
            }              
            if($('.select-source').length !== 0) {
                get_sources_for_date();
            }    
            if($('.select-department').length !== 0) {
                get_departments_for_date();
            }                            
        }

        if (get_current_page().startsWith('lead-sales.php')) {
            get_plan_for_date();
        }    
    });

    event_start('click', '.event_counter', function (e) {

        let dates = $('#reportrange').val().split(' - ');
        current_request = 0;

        if(!get_current_page().startsWith('unprocessed-base-2.php?p=10') || (is_search === false && user_id === undefined)) {
            date_start = dates[0].split('/').reverse().join('-') + ' 00:00:00';
            date_end = dates[1].split('/').reverse().join('-') + ' 23:59:59';
                        
            filter = {};
        }
        filter = {
            ...filter,
            'status': $(e).attr('status'),
        }
        defect_bg = 'yes';

        order_by_filter = 'date_time_status_change desc';

        $('#table-request').html('');
        if(get_current_page().startsWith('unprocessed-base-2.php?p=10')) {
            get_unprocessed_base2();
        } else {
            get_unprocessed_base();
        }     
        
    });

    event_start('click', '.table-settings', function (e) {
        const id_request = $(e).closest('tr').attr('tr-id');
        $('#idval').val(id_request);
    });

    event_start('click', '.table-update', function (e) {
        const id_request = $(e).closest('tr').attr('tr-id');
        $('#idval').val(id_request);
    });

    event_start('click', '#save-request', function (e) {
        const status = parseInt($('#status_form').val());
        save_request(status);
    });

    event_start('click', '#save-answering-machine', function (e) {
        $('#status_form').val(22);
        const status = 22;
        save_request(status);
    });

    event_start('click', '#save-hung-up', function (e) {
        $('#status_form').val(30);
        const status = 30;
        save_request(status);
    });

    event_start('click', '#save-request2', function (e) {
        const status = parseInt($('#status-modal').val());
        const form_data = {
            'params': $('.form-request').serializeArray().filter(function (item) {
                if (item.name === 'date_time_status_change' && (status == 6 || status == 17 || status == 18)) {
                    var momentDate = moment(item.value, 'DD.MM.YYYY HH:mm');
                    item.value = momentDate.format('YYYY-MM-DDTHH:mm:ss');

                    if (item.value == 'Не установлено' || status == 17) {
                        const now = moment();
                        item.value = now.format('YYYY-MM-DD HH:mm:ss');
                    }
                }

                return item.value !== '' && item.value !== 'Не установлено';
            }),
            'action': 'update_request',
            'id': $('#idval').val(),
            'status': status,
        }


        $.ajax({
            url: '/scripts/unprocessed_base2/unprocessed_base2.php',
            method: 'POST',
            data: form_data,
            success: function (response) {
                delete response['operator_id'];
                $('#signup-modal').modal('hide');
                const id_request = $('#idval').val();
                let td = $(`[tr-id="${id_request}"]`).find('td');
                td.filter('[name]').each(function () {
                    const name = $(this).attr('name');
                    if (response.hasOwnProperty(name)) {
                        let elem = response[name];
                        if (name === 'status_name' && !get_current_page().startsWith('lead-sales.php')) {
                            elem = `<div class="status_name">${elem}</div>`;
                        }
                        else if (name === 'date_time_status_change') {
                            let date_status = response['date_time_status_change'];
                            const date_object = moment(date_status);
                            let rus_date = date_object.format('DD.MM.YYYY HH:mm');
                            if (rus_date == 'Invalid date') {
                                rus_date = '';
                                date_status = '';
                            }
                            elem = `<div class="date_time_status_change" attr-date="${date_status}">${rus_date}</div>`;
                        }
                        $(this).html(elem);
                    }
                });
                td.filter('[name="user_id"]').attr('attr-id', response.operator_id);
                td.filter('[name="status_name"]').attr('attr-id', response.status);
                td.filter('[name="fio"]').attr('attr-fio', response.fio);
                td.filter('[name="created_by_user"]').attr('data-created-user-id', response.created_by_user_id);
                $(td).find('.change_operator').val(response.operator_id);
                $($(td).closest('tr')).removeClass().addClass(get_status_color(response.status));
            },
            error: function (error) {
                if (error.responseText) {
                    alert("Ошибка: " + error.responseText);
                } else {
                    alert("HTTP ошибка: " + error.status + " - " + error.statusText);
                }
            }
        });

    });

    event_start('click', '#send-sale-request', function (e) {
        $('#status_form').val(15);
        const status = 15;
        let is_logging = 'n';
        if($('.form-request').find('[name="past_status"]').val() != $('.form-request').find('[name="status"]').val() || $('.checkbox-update-status').is(':checked')) {
            is_logging = 'y';
        }  
        const real_estate = [];
        const movables = [];
        const early_action = [];
        const messengers = [];

        if(validate_form(status)) {
            
            let login_id = $('#login_id').val();
            if(login_id != 479) {
                const vopros = get_vopros();
                $('.form-request').find('[name="vopros"]').val(vopros);
            }

            const form_data = {
                'params': $('.form-request').serializeArray().filter(function (item) {
                    if (item.name === 'date_time_status_change' && status == 15) {
                        const now = moment();
                        item.value = now.format('YYYY-MM-DD HH:mm:ss');  
                    }

                    if(item.name === 'date_time_of_last_save') {
                        if($('.form-request').find('[name="past_status"]').val() != $('.form-request').find('[name="status"]').val() || $('.checkbox-update-status').is(':checked')) {
                            const now = moment();
                            item.value = now.format('YYYY-MM-DD HH:mm:ss');
                        }                    
                    }

                    if(item.name === 'hold_status_id') {
                        item.value = null;  
                    }

                    if(item.name === 'date_time_hold_calling') {
                        item.value = null;  
                    }

                    if (item.name === 'real_estate[]') {
                        real_estate.push(item.value);
                        item.value = '';
                    }                

                    if (item.name === 'movables[]') {
                        movables.push(item.value);
                        item.value = '';
                    }  

                    if (item.name === 'early_action[]') {
                        early_action.push(item.value);
                        item.value = '';
                    }  

                    if (item.name === 'messengers[]') {
                        messengers.push(item.value);
                        item.value = '';
                    }  

                    return item.value !== '' && item.value !== 'Не установлено';
                }),
                'action': 'update_request',
                'id': $('#idval').val(),
                'status': status,
                'logging': is_logging,
                'real_estate': real_estate,
                'movables': movables,
                'early_action': early_action,
                'messengers': messengers,
            }

			if(get_current_page().startsWith('unprocessed-base-4.php?p=10')) {
				form_data['manual'] = 'r';
			}

			if(get_current_page().startsWith('lead-sales.php')) {
				form_data['manual_sal'] = '1';
			}

            if(get_current_page().startsWith('unprocessed-base-excel.php?p=10')) {
                table = '_excel';
                form_data['is_double'] = 'n';
            }

            $.ajax({
                url: `/scripts/unprocessed_base${table}/unprocessed_base${table}.php`,
                method: 'POST',
                data: form_data,
                success: function (response) {
                    if(response['warning']) {
                        alert(response['warning']);
                    }
                    else {
                        delete response['operator_id'];
                        const id_request = $('#idval').val();
                        let td = $(`[tr-id="${id_request}"]`).find('td');
                        td.filter('[name]').each(function () {
                            const name = $(this).attr('name');
                            if (response.hasOwnProperty(name)) {
                                let elem = response[name];
                                if (name === 'status_name' && !get_current_page().startsWith('lead-sales.php')) {
                                    // const date_status = response['date_time_status_change'];
                                    // const date_object = moment(date_status);
                                    // const rus_date = date_object.format('DD.MM.YYYY HH:mm');
                                    // if (rus_date == 'Не установлено') {
                                    //     rus_date = '';
                                    //     date_status = ''; s
                                    // }
                                    elem = `<div class="status_name">${elem}</div>`;
                                }
                                else if (name === 'date_time_status_change' || name === 'date_time_hold_calling') {
                                    let date_status = name === 'date_time_status_change' ? response['date_time_status_change'] : response['date_time_hold_calling'];
                                    const date_object = moment(date_status);
                                    let rus_date = date_object.format('DD.MM.YYYY HH:mm');
                                    if (rus_date == 'Invalid date') {
                                        rus_date = '';
                                        date_status = '';
                                    }
                                    elem = name === 'date_time_status_change' ? `<div class="date_time_status_change" attr-date="${date_status}">${rus_date}</div>` : rus_date;
                                }
                                $(this).html(elem);
                            }
                        });
                        td.filter('[name="user_id"]').attr('attr-id', response.operator_id);
                        td.filter('[name="status_name"]').attr('attr-id', response.status);
                        $(td).find('.change_operator').val(response.operator_id);
                        $($(td).closest('tr')).removeClass().addClass('lead-bg');
                        if(get_current_page().startsWith('holds.php')) {
                            get_quantity_by_hold_statuses();
                        } else if(!get_current_page().startsWith('unprocessed-base-7.php?p=10')) {
                            get_quantity_by_statuses();
                        }
                        is_fills_lead = false;
                        $('#signup-modal').modal('hide');
                        setTimeout(() => save_audiorecording(response.phone_number), 15000);
                    }
                },
                error: function (error) {
                    if (error.responseText) {
                        alert("Ошибка: " + error.responseText);
                    } else {
                        alert("HTTP ошибка: " + error.status + " - " + error.statusText);
                    }
                }
            });    
        }      

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
        $(this).val('Не установлено');
    });

    $('#datepicker-calling').on('show.daterangepicker', function(ev, picker) {
        var currentValue = $(this).val();
        isDaterangepickerOpen = true;
        $('.daterangepicker').appendTo('#signup-modal');
    });

    $('#datepicker-calling').on('hide.daterangepicker', function(ev, picker) {
        isDaterangepickerOpen = false;
    });

    $('#datepicker-calling').on('click', function(e) {
        e.stopPropagation();
    });

    $(document).on('click', '.daterangepicker', function(e) {
        e.stopPropagation();
    });

    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() + 250 >= $(document).height() && scroll_event) {
            if(get_current_page().startsWith('unprocessed-base-2.php?p=10')) {
                get_unprocessed_base2();
            } else {
                if(!get_current_page().startsWith('unprocessed-base-5.php?p=10') && !get_current_page().startsWith('unprocessed-base-6.php?p=10') && !get_current_page().startsWith('unprocessed-base-7.php?p=10') && !get_current_page().startsWith('holds.php')) {
                    get_unprocessed_base();
                }
            }  
        }
    });

    if ($.fn.select2) {
        $('.source-import-csv').select2({
            placeholder: 'Выберите источник',
            maximumSelectionLength: 2,
            language: 'ru',
            tags: true,
            allowClear: true,
        });

        $('.region-import-csv').select2({
            placeholder: 'Выберите регион',
            maximumSelectionLength: 2,
            language: 'ru',
            tags: true,
            allowClear: true,
        });

        if(!get_current_page().startsWith('unprocessed-base-excel.php?p=10')) {

            $('.filter-region').select2({
                placeholder: 'Выберите регион',
                maximumSelectionLength: 2,
                language: 'ru',
                allowClear: true,
            });

        }

    }

    $('.filter-region').on('change', function (e) {
        const region = $(this).val();
        current_request = 0;
        //filter = {};
        $('#table-request').html('');

        if (region == 'Регион') {
            get_unprocessed_base();
            return false;
        }

        filter['auto_city_group'] = region
        get_unprocessed_base();
    });

    $('#signup-modal').modal({
        backdrop: 'static',
        keyboard: false,
        show: false
    });

    $('#signup-modal').on('click', function(e) {

        if(isDaterangepickerOpen) return;

        if($(e.target).is('.modal')) {
            if(confirm('Закрыть окно?')) {
                $(this).modal('hide');
                let phone_number = $('.form-request input[name="phone_number"]').val().slice(-10);
                if(get_current_page().startsWith('unprocessed-base-5.php?p=10') || get_current_page().startsWith('unprocessed-base-7.php?p=10')) {
                    if (window.DEBUG_MODE === 'y') {
                        $.ajax({
                            url: '/scripts/unprocessed_base/unprocessed_base.php',
                            method: 'POST',
                            data: {
                                'action': 'logging',
                                'phone_number': phone_number,
                                'value': 'close',
                                'modul': 'atc_call',
                            },
                            success: function (response) {
                            },
                            error: function (error) {
                                if (error.responseText) {
                                    alert("Ошибка: " + error.responseText);
                                } else {
                                    alert("HTTP ошибка: " + error.status + " - " + error.statusText);
                                }
                            }
                        });
                    }
                }
                if(is_fills_lead == true) {
                    is_fills_lead = false;
                    delete_lead_filling();
                }
                setTimeout(() => save_audiorecording(phone_number), 15000);
            }
        }
    });
/*
    $('#signup-modal').on('hidden.bs.modal', function (e) {
        console.log('Событие при закрытии окна');

        if(!is_closed_by_button) {
            disable_dnd_mode2();
            console.log('Завершение запроса при закрытии окна');            
        }

        is_closed_by_button = false;

    });
*/
    event_start('click', '#btn-rate', function (e) {
        get_rate();
    });

    $('#rate-date-range').daterangepicker({
        startDate: moment().format('DD/MM/YYYY'),
        endDate: moment().format('DD/MM/YYYY'),
        opens: 'left',
        buttonClasses: ['btn btn-default'],
        applyClass: 'btn-small btn-primary',
        cancelClass: 'btn-small',
        ranges: {
            'Сегодня': [moment(), moment()],
            'Вчера': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'За 7 дней': [moment().subtract(6, 'days'), moment()],
            'За 30 дней': [moment().subtract(29, 'days'), moment()],
            'Этот месяц': [moment().startOf('month'), moment().endOf('month')],
            'Прошлый месяц': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')
            ]
        },
        locale: {
            format: 'DD/MM/YYYY',
            applyLabel: 'Выбрать',
            cancelLabel: 'Отмена',
            fromLabel: 'от',
            toLabel: 'до',
            customRangeLabel: 'Другие даты',
            daysOfWeek: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
            monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            firstDay: 1
        },
        function(start, end) {
            $('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            startDate = start;
            endDate = end;
        }
    });

});