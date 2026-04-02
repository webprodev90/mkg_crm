let date_start = '';
let date_end = '';
let time_start = 0;
let time_end = 24;

function date_eng_format(date) {
    if (date) {
        return date.split('/').reverse().join('-')
    }
}

function get_period() {
    let dates = $('#reportrange').val().split(' - ');
    return {
        date_start: date_eng_format(dates[0]),
        date_end: date_eng_format(dates[1]),
    }
}

function get_atc_statistics() {

    const form_data = {
        'action': 'get_atc_statistics',
        'date_start': date_start,
        'date_end': date_end,
        'time_start': time_start,
        'time_end': time_end,
    }
    
    $.ajax({
        url: '/scripts/atc_stat/atc_stat.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            if(response) {
                $('.table tbody').html(response);
            } else {
                let count_col = $('.table thead th').length;
                $('.table tbody').html(`<tr><td colspan="${count_col}">Нет данных</td></tr>`);
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

$(document).ready(function () {

    $('#check-minutes').on('click', function (e) {
        e.preventDefault();
        let period = get_period();
        date_start = period['date_start'];
        date_end = period['date_end']; 
        $('.select-start-time').val('');
        $('.select-end-time').val('');
        time_start = 0;
        time_end = 24;
        get_atc_statistics();
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
            get_atc_statistics();
        }
    });

    let period = get_period();
    date_start = period['date_start'];
    date_end = period['date_end']; 
    get_atc_statistics();

});