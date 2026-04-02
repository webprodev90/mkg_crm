let source = null;
let department_id = null;
let city = null;
let is_sog = null;
let mobile_operator_id = null;
let touches_phone_number = null;
let dozvon = null;
let chist_kpd = null;
let count_to_send = 0;
let total_count_to_send = 0;

function date_eng_format(date) {
    if (date) {
        return date.split('/').reverse().join('-')
    }
}

function get_stats(date_start, date_end) {

    const form_data = {
        'action': 'get_stats',
        'date_start': date_start,
        'date_end': date_end,
    }

    if(department_id) {
        form_data.department_id = department_id;
    }

    if(source) {
        form_data.source = source;
    }

    if(city) {
        form_data.city = city;
    }

    if(is_sog) {
        form_data.is_sog = is_sog;
    }

    if(mobile_operator_id) {
        form_data.mobile_operator_id = mobile_operator_id;
    }

    if(dozvon) {
        form_data.dozvon = dozvon;
    }

    if(chist_kpd) {
        form_data.chist_kpd = chist_kpd;
    }

    if(touches_phone_number) {
        form_data.touches_phone_number = touches_phone_number;
    }

    $.ajax({
        url: '/scripts/sending_asterisk/sending_asterisk.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('#table-request').html(response);
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });

}

function get_departments_of_stats(date_start, date_end) {

    const form_data = {
        'action': 'get_departments_of_stats',
        'date_start': date_start,
        'date_end': date_end,
    }

    $.ajax({
        url: '/scripts/sending_asterisk/sending_asterisk.php',
        method: 'POST',
        data: form_data,
        dataType: 'json',
        success: function (response) {
            
            $('#select-department').html('');
            let options = '<option value="" selected="">Выбор отдела</option>';

            if(response) {
                response.forEach((item, index) => {
                    options += `<option value="${item['department_id']}">${item['name']}</option>`;
                });                   
            }

            $('#select-department').append(options);
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });
}

function get_sources_of_stats(date_start, date_end) {

    const form_data = {
        'action': 'get_sources_of_stats',
        'date_start': date_start,
        'date_end': date_end,
    }

    $.ajax({
        url: '/scripts/sending_asterisk/sending_asterisk.php',
        method: 'POST',
        data: form_data,
        dataType: 'json',
        success: function (response) {
            
            $('#select-source').html('');
            let options = '<option value="" selected="">Выбор источника</option>';

            if(response) {
                response.forEach((item, index) => {
                    options += `<option value="${item['name']}">${item['name']}</option>`;
                });                   
            }

            $('#select-source').append(options);
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });
}

function get_cities_of_stats(date_start, date_end) {

    const form_data = {
        'action': 'get_cities_of_stats',
        'date_start': date_start,
        'date_end': date_end,
    }

    $.ajax({
        url: '/scripts/sending_asterisk/sending_asterisk.php',
        method: 'POST',
        data: form_data,
        dataType: 'json',
        success: function (response) {
            
            $('#select-city').html('');
            let options = '<option value="" selected="">Выбор города</option>';

            if(response) {
                response.forEach((item, index) => {
                    options += `<option value="${item['filcity']}">${item['filcity']}</option>`;
                });                   
            }

            $('#select-city').append(options);
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });
}

function get_mobile_operators_of_stats(date_start, date_end) {

    const form_data = {
        'action': 'get_mobile_operators_of_stats',
        'date_start': date_start,
        'date_end': date_end,
    }

    $.ajax({
        url: '/scripts/sending_asterisk/sending_asterisk.php',
        method: 'POST',
        data: form_data,
        dataType: 'json',
        success: function (response) {
            
            $('#select-mobile-operator').html('');
            let options = '<option value="" selected="">Выбор оператора связи</option>';

            if(response) {
                response.forEach((item, index) => {
                    options += `<option value="${item['operator_id']}">${item['operator_name']}</option>`;
                });                   
            }

            $('#select-mobile-operator').append(options);
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });
}

function get_period() {
    dates = $('#reportrange').val().split(' - ');
    return {
        'date_start': date_eng_format(dates[0]),
        'date_end': date_eng_format(dates[1]),
    }
}

function check() {
    $("input:checkbox").prop("checked", true);
};

function uncheck() {
    $("input:checkbox").prop("checked", false);
};

function sum_count_to_send() {

    count_to_send = 0;
    $('.table-city-stat').each(function() {
        const checkbox1 = $(this).find('.table-chec input[type="checkbox"]');

        if(checkbox1.is(':checked')) {
            const count_td = $(this).find('.table-count-requests');
            const value_td = parseInt(count_td.text().trim());
            if(!isNaN(value_td)) {
                count_to_send += value_td;
            }
        }
    });

}

function update_button_to_send() {

    const submitBtn = $('#btn-to-send');
    total_count_to_send = count_to_send;
    $('#btn-to-send span').text(count_to_send);
    $('#count-rows').val(count_to_send);
    if(count_to_send > 0 && $('#select-campany').val()) {
        submitBtn.prop('disabled', false);
        submitBtn.removeClass('disabled');
    } else {
        submitBtn.prop('disabled', true);
        submitBtn.addClass('disabled');
    }

}

function send_contacts_to_asterisk() {

    let sources_cities = [];
    const period = get_period();
    let date_start = period['date_start']; 
    let date_end = period['date_end'];

    $('.table-city-stat').each(function() {
        const checkbox1 = $(this).find('.table-chec input[type="checkbox"]');

        if(checkbox1.is(':checked')) {
            let source = $(this).attr('tr-source');
            let city = $(this).find('.table-id').text();
            sources_cities.push({ 
                'source': source,
                'city': city
            });
        }
    });

    const form_data = {
        'action': 'send_contacts_to_asterisk',
        'date_start': date_start,
        'date_end': date_end,
        'sources_cities': sources_cities,
        'campany': $('#select-campany').val(),
    }

    if(department_id) {
        form_data.department_id = department_id;
    }

    if(count_to_send && count_to_send != 0) {
        form_data.count_to_send = count_to_send;
    }

    if(is_sog) {
        form_data.is_sog = is_sog;
    }

    if(mobile_operator_id) {
        form_data.mobile_operator_id = mobile_operator_id;
    }

    if(touches_phone_number) {
        form_data.touches_phone_number = touches_phone_number;
    }

    //console.log(form_data);

    $.ajax({
        url: '/scripts/sending_asterisk/sending_asterisk.php',
        method: 'POST',
        data: form_data,
        //dataType: 'json',
        success: function (response) {
            //console.log(response);
            uncheck();
            count_to_send = 0;
            update_button_to_send();
            alert('Выбранные контакты были успешно отправлены!');
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });

}

$(document).ready(function (e) {

    $('#select-campany').select2({
        language: {
            noResults: function() {
                return "Ничего не найдено";
            }
        },
        dropdownAutoWidth: false
    });

    $('#singleCheckbox1').on('click', function (e) {
        if($("input:checkbox").prop("checked") == false) {
            uncheck();
            count_to_send = 0;
            update_button_to_send();
        } else {
            check();
            sum_count_to_send();
            update_button_to_send();
        }
    });     

    $('#check-minutes').on('click', function (e) {
        e.preventDefault();
        const period = get_period();
        source = null;
        department_id = null;
        city = null;       
        is_sog = null;
        mobile_operator_id = null; 
        dozvon = null;  
        chist_kpd = null;   
        touches_phone_number = null;   
        get_stats(period['date_start'], period['date_end']);
        get_departments_of_stats(period['date_start'], period['date_end']);
        get_sources_of_stats(period['date_start'], period['date_end']);
        get_cities_of_stats(period['date_start'], period['date_end']);
        get_mobile_operators_of_stats(period['date_start'], period['date_end']);
        $('#select-is-sog').val('');
        $('#select-count-touches').val('');
        $('#select-dozvon').val('');
        $('#select-chist-kpd').val('');
        uncheck();
        count_to_send = 0;
        update_button_to_send();
    });

    $('#select-department').on('change', function () {
        department_id = $('#select-department').val();
        const period = get_period();          
        get_stats(period['date_start'], period['date_end']);
        uncheck();
        count_to_send = 0;
        update_button_to_send();
    });

    $('#select-source').on('change', function () {
        source = $('#select-source').val();
        const period = get_period();          
        get_stats(period['date_start'], period['date_end']);
        uncheck();
        count_to_send = 0;
        update_button_to_send();
    });

    $('#select-city').on('change', function () {
        city = $('#select-city').val();
        const period = get_period();          
        get_stats(period['date_start'], period['date_end']);
        uncheck();
        count_to_send = 0;
        update_button_to_send();
    });

    $('#select-is-sog').on('change', function() {
        is_sog = $('#select-is-sog').val();
        const period = get_period();          
        get_stats(period['date_start'], period['date_end']);
        uncheck();
        count_to_send = 0;
        update_button_to_send();
    });

    $('#select-dozvon').on('change', function() {
        dozvon = $('#select-dozvon').val();
        const period = get_period();          
        get_stats(period['date_start'], period['date_end']);
        uncheck();
        count_to_send = 0;
        update_button_to_send();
    });

    $('#select-chist-kpd').on('change', function() {
        chist_kpd = $('#select-chist-kpd').val();
        const period = get_period();          
        get_stats(period['date_start'], period['date_end']);
        uncheck();
        count_to_send = 0;
        update_button_to_send();
    });

    $('#select-count-touches').on('change', function() {
        touches_phone_number = $('#select-count-touches').val();
        const period = get_period();          
        get_stats(period['date_start'], period['date_end']);
        uncheck();
        count_to_send = 0;
        update_button_to_send();
    });

    $('#select-mobile-operator').on('change', function() {
        mobile_operator_id = $('#select-mobile-operator').val();
        const period = get_period();          
        get_stats(period['date_start'], period['date_end']);
        uncheck();
        count_to_send = 0;
        update_button_to_send();
    });

    $('#btn-sel-count-row').on('click', function() {
        $('#count-rows').val(count_to_send);
        $('#box-update-count-row--active').removeClass('d-none').addClass('d-flex');
        $('#box-update-count-row--inactive').addClass('d-none');
    });

    $('#btn-save-count-row').on('click', function() {
        if($('#count-rows').val() > total_count_to_send) {
            alert("Значение больше количества выбранных строк!");
        } else if($('#count-rows').val() < 0) {
            alert("Отрицательное значение запрещено!");
        } else if($('#count-rows').val() === "") {
            alert("Пустое поле запрещено!");
        } else {
            count_to_send = + $('#count-rows').val();
            const submitBtn = $('#btn-to-send');
            $('#btn-to-send span').text(count_to_send);
            $('#count-rows').val(count_to_send);
            if(count_to_send > 0 && $('#select-campany').val()) {
                submitBtn.prop('disabled', false);
                submitBtn.removeClass('disabled');
            } else {
                submitBtn.prop('disabled', true);
                submitBtn.addClass('disabled');
            }
            $('#box-update-count-row--active').addClass('d-none').removeClass('d-flex');
            $('#box-update-count-row--inactive').removeClass('d-none');            
        }
    });

    $(document).on('change', '.td-checkbox', function() {

        const tr = $(this).closest('tr');
        const count_td = tr.find('.table-count-requests');
        const value_td = parseInt(count_td.text().trim());

        if (this.checked) {
            count_to_send += value_td;
        } else {
            count_to_send -= value_td;
        }
        
        update_button_to_send();

    });

    $('#select-campany').on('change', function() {
        const submitBtn = $('#btn-to-send');
        if($(this).val() && count_to_send > 0) {
            $(submitBtn).prop('disabled', false);
            submitBtn.removeClass('disabled');
        } else {
            $(submitBtn).prop('disabled', true);
            submitBtn.addClass('disabled');
        }
    });

    $('#btn-to-send').on('click', function (e) {
        send_contacts_to_asterisk();
    });

});