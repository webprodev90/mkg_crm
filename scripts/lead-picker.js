var current_request = 500;
var scroll_event = true;
var filter = {};
var order_by_filter = '';
var date_start, date_end = '';
var view_name = 'unprocessed';
var defect_bg = 'no';
var is_search = false;
var user_id = undefined;
var isDaterangepickerOpen = false;

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
        url: '/scripts/lead_picker/lead_picker.php',
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
        url: '/scripts/lead_picker/lead_picker.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('.count_callings').text(response['callings']);
            $('.count_non_calls').text(response['non_calls']);
            $('.count_rejections').text(response['rejections']);
            $('.count_hung_up').text(response['hung_up']);
            $('.count_not_relevant').text(response['not_relevant']);
            $('.count_new_leads').text(response['new_leads']);
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

function get_status_color(status) {
        let color_class = '';

        switch (parseInt(status)) {
            case 4:  // Недозвон
                color_class = 'non-call-bg';
                break;
            case 2: // Отказ
                color_class = 'rejection-bg';
                break;
            case 12:  // Созвон
                color_class = 'calling-bg';
                break;
            case 10:  // Новый лид
                color_class = 'in-work-bg';
                break;
            case 7:  // Не актуально
                color_class = 'defect-bg';
                break;
            case 19:  // Дубль
                color_class = 'double-bg';
                break;  
            case 3:  // Сброс
                color_class = 'hung-up-bg';
                break;                                 
        }
        
        return color_class;
}

$(document).ready(function () {

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
            url: '/scripts/lead_picker/lead_picker.php',
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
        if (status_id == 12) {
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
            url: '/scripts/lead_picker/lead_picker.php',
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
                url: '/scripts/lead_picker/lead_picker.php',
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
                url: '/scripts/lead_picker/lead_picker.php',
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
            url: '/scripts/lead_picker/lead_picker.php',
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

    event_start('change', '.select-sales-operator', function (e) {
        current_request = 0;
        user_id = $(e).val();
        filter['user_id'] = $(e).val();
        $('#table-request').html('');
        get_unprocessed_base2();
        get_counters2();
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
            url: '/scripts/lead_picker/lead_picker.php',
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
        if (status_id == 12) {
            $('#datepicker-calling, .back-to-status').removeClass('d-none');
            $(e).addClass('d-none');
        }
    });

    $('#check-minutes').on('click', function (e) {
        e.preventDefault();

        current_request = 0;
        const datepicker = $('#reportrange').data('daterangepicker');
        date_start = date_eng_format(datepicker.startDate.format('DD/MM/YYYY'));
        date_end = date_eng_format(datepicker.endDate.format('DD/MM/YYYY'));

        $('#table-request').html('');
        get_unprocessed_base2();
        get_counters2(); 
          
    });

    event_start('click', '.event_counter', function (e) {

        let dates = $('#reportrange').val().split(' - ');
        current_request = 0;

        filter = {
            ...filter,
            'status': $(e).attr('status'),
        }
        defect_bg = 'yes';

        order_by_filter = 'date_time_status_change desc';

        $('#table-request').html('');
        get_unprocessed_base2();   
        
    });

    event_start('click', '.table-settings', function (e) {
        const id_request = $(e).closest('tr').attr('tr-id');
        $('#idval').val(id_request);
    });

    event_start('click', '.table-update', function (e) {
        const id_request = $(e).closest('tr').attr('tr-id');
        $('#idval').val(id_request);
    });

    event_start('click', '#save-request2', function (e) {
        const status = parseInt($('#status-modal').val());
        const form_data = {
            'params': $('.form-request').serializeArray().filter(function (item) {
                if (item.name === 'date_time_status_change' && status == 12) {
                    var momentDate = moment(item.value, 'DD.MM.YYYY HH:mm');
                    item.value = momentDate.format('YYYY-MM-DDTHH:mm:ss');

                    if (item.value == 'Не установлено') {
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
            url: '/scripts/lead_picker/lead_picker.php',
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
                get_counters2();
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
            get_unprocessed_base2();
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
            }
        }
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