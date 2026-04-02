let is_financial_table = true;
let is_call_table = false;
let date_start = '';
let date_end = '';
let time_start = 0;
let time_end = 24;
let trunk_filter = '';
let campaign_filter = '';
let operator_filter = '';
let type_autodialer_filter = '';

function date_eng_format(date) {
    if (date) {
        return date.split('/').reverse().join('-')
    }
}

function get_financial_stats() {

    const form_data = {
        'action': 'get_financial_stats',
        'date_start': date_start,
        'date_end': date_end,
    }

    $.ajax({
        url: '/scripts/call_rates/call_rates.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('#financial_table').html(response);
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });

}

function get_call_stats() {

    if(date_start == '' && date_end == '') {
        const period = get_period();
        date_start = period['date_start'];
        date_end = period['date_end'];
    }

    const form_data = {
        'action': 'get_call_stats',
        'date_start': date_start,
        'date_end': date_end,
        'time_start': time_start,
        'time_end': time_end,
        'trunk_filter': trunk_filter,
        'campaign_filter': campaign_filter,
        'operator_filter': operator_filter,
        'type_autodialer_filter': type_autodialer_filter,
    }

    $.ajax({
        url: '/scripts/call_rates/call_rates.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('#call_table').html(response);
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

$(document).ready(function (e) {

    $('#check-minutes').on('click', function (e) {
        e.preventDefault();
        const period = get_period();
        date_start = period['date_start'];
        date_end = period['date_end'];
        if(is_financial_table) {
            get_financial_stats();
        } else if(is_call_table) {
            get_call_stats();
        }
        
    });

    $('#btn_financial_stat').on('click', function () {
        $('#financial_content').removeClass('d-none');
        $('#call_content').addClass('d-none');
        $('#btn_financial_stat').css('text-decoration', 'underline');
        $('#btn_call_stat').css('text-decoration', 'none');
        is_financial_table = true;
        is_call_table = false;
    });

    $('#btn_call_stat').on('click', function () {
        $('#financial_content').addClass('d-none');
        $('#call_content').removeClass('d-none');
        $('#btn_financial_stat').css('text-decoration', 'none');
        $('#btn_call_stat').css('text-decoration', 'underline');
        is_financial_table = false;
        is_call_table = true;
    });

    $('.select-trunk').on('change', function () {
        trunk_filter = $('.select-trunk').val();
        get_call_stats();
    });

    $('.select-campaign').on('change', function () {
        campaign_filter = $('.select-campaign').val();
        get_call_stats();
    });

    $('.select-operator').on('change', function () {
        operator_filter = $('.select-operator').val();
        get_call_stats();
    });

    $('.select-type-autodialer').on('change', function () {
        type_autodialer_filter = $('.select-type-autodialer').val();
        get_call_stats();
    });

    $('.btn-time-filter').on('click', function (e) {
        if($('.select-start-time').val() !== '') {
            time_start = + $('.select-start-time').val() - 1;
        } else {
            time_start = 0;
        }

        if($('.select-end-time').val() !== '') {
            time_end = + $('.select-end-time').val() - 1;
        } else {
            time_end = 24;
        }
        
        if(time_start > time_end || time_start === time_end) {
            alert('Некорректно выбрано время!');
        } else if(date_start != date_end) {
            alert('Фильтр только для одного дня!');
        } else {
            get_call_stats();
        }
    });

});