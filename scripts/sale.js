var request_for_sale = false;

function sale_request() {
    const id = $('#idval').val();

    const form_data = {
        'idval': id,
        'partner_id': $('#partner-id').val(),
    }

    if(request_for_sale) {
        request_for_sale = false;
        $.ajax({
            url: '/templates/pages/sale-request.php',
            method: 'POST',
            data: form_data,
            success: function (response) {
                if(response['warning']) {
                    request_for_sale = true;
                    alert(response['warning']);
                } 
                else {
                    $('#sale-modal').modal('hide');
                    const tr = $('[tr-id="' + id + '"]');
                    $(tr).find('.new_lead').addClass('d-none');
                    $(tr).addClass('saled-lead-bg');
                }
            },
            error: function (error) {
                alert('Не заполнен город и не указана стоимость заявки. Проверьте данные и повторите попытку');
            }
        });
    }
}

function get_sales(id) {
    const form_data = {
        'action': 'get_sale_requests',
        'id': id,
        'view_name': 'sale_request',
    }

    $.ajax({
        url: '/scripts/sale/sale.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('.table-sales').html(response);
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });
}

function partner_status(e) {
    const form_data = {
        'action': 'partner_status',
        'id': $(e).attr('id'),
        'status': $(e).val()
    }

    $.ajax({
        url: '/scripts/sale/sale.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            console.log(response);
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });
}

function check_city(partner_id) {
    const form_data = {
        'action': 'check_city',
        'partner_id': partner_id
    }

    $.ajax({
        url: '/scripts/sale/sale.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            if(Boolean(+response.is_city)) {
                let is_сonfirm_сity = confirm('Проверьте город и нажмите "ОК"');
                if(is_сonfirm_сity) {
                    sale_request();
                }            
            }
            else {
                sale_request();
            }
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });
}

function set_plan() {
    let fix_plan = [];
    let current_plan = [];

    $('tbody.table-plan tr').each(function () {
        if(parseInt($(this).find('input[name="count"]').val()) > 0 || $(this).find('input[name="time_start"]').val() !== "00:00" || $(this).find('input[name="time_end"]').val() !== "00:00" || $(this).find('input[name="city_check"]').is(':checked') || $(this).find('input[name="audio_is_check"]').is(':checked')) {
            fix_plan.push({
                'partner_id': $(this).attr('partner_id'),
                'count': parseInt($(this).find('input[name="count"]').val()),
                'time_start': $(this).find('input[name="time_start"]').val(),
                'time_end': $(this).find('input[name="time_end"]').val(),
                'is_city': Number($(this).find('input[name="city_check"]').is(':checked')),
                'is_audio': Number($(this).find('input[name="audio_is_check"]').is(':checked'))
            });
        }
        if($(this).find('input[name="added-plan"]').is(':checked')) {
            current_plan.push({
                'partner_id': $(this).attr('partner_id'),
                'count': parseInt($(this).find('input[name="count"]').val()),
                'time_start': $(this).find('input[name="time_start"]').val(),
                'time_end': $(this).find('input[name="time_end"]').val()
            });            
        }
    });


    const form_data = {
        'action': 'set_plan',
        'fix_plan': fix_plan,
        'current_plan': current_plan,
    }

    $('#plan-modal').modal('hide');

    $.ajax({
        url: '/scripts/sale/sale.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            console.log(response);
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });

}

function reject_partner() {
    const id = $('#idval').val();
    let dates = $('#reportrange').val().split(' - ');
    let date_start = dates[0].split('/').reverse().join('-') + ' 00:00:00';
    let date_end = dates[1].split('/').reverse().join('-') + ' 23:59:59';
    let selected_sale = 'no';

    let table = document.querySelector('.table-sales');
    let checked = document.querySelector('.selected-sale');
    let contains = table.contains(checked);
    let sales = [];
    if(contains) {
        selected_sale = 'yes';
        let rows = $('.table-sales tr');
        rows.each((index, row) => {
            if($(row).children().length === 4) {
                let checkbox = $(row).find('.selected-sale');
                if(checkbox[0].checked) {
                    let id = + $(row).attr('tr-id');
                    sales.push(id);
                }
            }
        });
    }

    const form_data = {
        'action': 'reject_partner',
        'idval': id,
        'date_start': date_start,
        'date_end': date_end,
        'selected_sale': selected_sale,
        'sales': sales.join(','),
    }

    $.ajax({
        url: '/scripts/sale/sale.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            if(response['error']) {
               alert(response['error']); 
            }
            else if(response['select']) {
               alert('В таком диапазоне дат партнеров несколько!'); 
               let id_sales = [];
               response[0].forEach((sale) => {
                    id_sales.push(+sale.id);
               });
               let rows = $('.table-sales tr');
               rows.each((index, row) => {
                    let id = + $(row).attr('tr-id');
                    if(id_sales.includes(id)) {
                        $(row).append('<td><input class="form-check-input selected-sale" type="checkbox" style="transform: scale(1.2)"></td>'); 
                    }
               });
            }
            else {
               $('#sale-modal').modal('hide'); 
            }
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });
    
}

function get_audiorecording(phone_number) {
    const form_data = {
        'action': 'get_audiorecording',
        'phone_number': phone_number,
    }

    $.ajax({
        url: '/scripts/sale/sale.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            //console.log(response);
            $('.audio-links').html('');
            if(response) {
                response.forEach((item, index) => {
                    $('.audio-links').append(`<div class="audio-link mt-2">
                                               <a href="${item.link}" target="_blank">${item.name}</a>
                                            </div>`);                             
                });                
            } else {
                $('.audio-links').append('<div class="text-center">Ничего не найдено</div>');
            }

        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });
}

function check_audio(id, mark) {
    const form_data = {
        'action': 'check_audio',
        'id': id,
        'mark': mark
    }

    $.ajax({
        url: '/scripts/sale/sale.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            if(mark) {
                $(`.check-audio-btn[data-id='${id}']`).addClass('active-auduo');
                $(`.check-audio-btn[data-id='${id}']`).removeClass('no-active-auduo');
            }
            else {
                $(`.check-audio-btn[data-id='${id}']`).removeClass('active-auduo');
                $(`.check-audio-btn[data-id='${id}']`).addClass('no-active-auduo');
            }
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });
}

$(document).ready(function () {
    scroll_event = false;
    event_start('click', '#sale-request', function (e) {
        const partner_id = $('#partner-id').val();
        check_city(partner_id);
    });

    if(document.querySelector('#partner-id') !== null) {
        document.querySelector('#partner-id').addEventListener("change", function() {
            if (this.value == '408') {
                $('.warning-part').append('<div class="warning-part-info text-center">Ногинск<br>Дризна<br>Куровское<br>Электросталь<br>Черноголовка<br>Орехово Зуево<br>Павловский Посад<br>Серигиев Посад<br>Ликино-Дулево Покров Электрогорск<br>Шатура<br>Востряково<br>Бронницы<br>Воскресенск<br>Егорьевск<br>Коломна<br>Красноармейск<br>Карабаново</div>');
            } else if(this.value == '410') {
                $('.warning-part').append('<div class="warning-part-info text-center">Долг 500+ 10 областей<br>Тюменская область<br>Иркутской область<br>ХМАО<br>Челябинская область<br>Самарская область<br>Республика Татарстан<br>Республика Башкортостан<br>Крым<br>Свердловская область<br>Удмуртская Республика</div>');
            } else if (this.value == '424') {
                $('.warning-part').append('<div class="warning-part-info text-center">Красноярский край<br>Забайкальский край<br>ДНР, ЛНР, Херсонская и Запорожская области<br>Чеченская республика, Дагестан, Ингушетия, Северная Осетия-Алания, Республика Тыва, Курская и Белгородская области</div>');
            } else {
                if ($('.warning-part')){
                    $('.warning-part-info').remove();
                }
            }
        });        
    }

    event_start('change', '.partner-status-patch', function (e) {
        partner_status(e);
    });

    event_start('click', '.sale-btn', function (e) {
        const id = $(e).attr('data-id');
        request_for_sale = true;
        get_sales(id);
    });

    event_start('click', '#save-plan', function (e) {
        set_plan();
    });

    event_start('click', '#reject-partner', function (e) {
        reject_partner();
    });

    event_start('click', '.listen-audio', function (e) {
        const phone_number = $(e).attr('data-phone').slice(-10);
        //console.log(phone_number);
        get_audiorecording(phone_number);
    });

    event_start('click', '.check-audio-btn', function (e) {
        const id = $(e).attr('data-id');
        const mark = $(e).hasClass('active-auduo') ? 0 : 1;
        check_audio(id, mark);
    });

    order_by_filter = 'date_time_status_change desc';
    filter = {
        'status': 15,
    }

    view_name = 'sales';
});