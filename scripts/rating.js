let cur_department = 1;

function date_eng_format(date) {
    if (date) {
        return date.split('/').reverse().join('-')
    }
}

function get_period() {
    dates = $('#reportrange').val().split(' - ');
    return {
        'date_start': date_eng_format(dates[0]),
        'date_end': date_eng_format(dates[1]),
    }
}

function get_rating(date_start, date_end, department) {

    const form_data = {
        'action': 'get_rating',
        'department': department,
        'date_start': date_start,
        'date_end': date_end,
    }

    $.ajax({
        url: '/scripts/rating/rating.php',
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

$(document).ready(function (e) {
    $('#check-minutes').on('click', function (e) {
        e.preventDefault();
        const period = get_period();
        get_rating(period['date_start'], period['date_end'], cur_department);
    });

    $('#department_1').on('click', function () {
        cur_department = 1;
        const period = get_period();
        $('#department_1').css('text-decoration', 'underline');
        $('#department_2').css('text-decoration', 'none');
        $('#department_3').css('text-decoration', 'none');
        get_rating(period['date_start'], period['date_end'], cur_department);
    });

    $('#department_2').on('click', function () {
        cur_department = 2;
        const period = get_period();
        $('#department_1').css('text-decoration', 'none');
        $('#department_2').css('text-decoration', 'underline');
        $('#department_3').css('text-decoration', 'none');
        get_rating(period['date_start'], period['date_end'], cur_department);
    });

    $('#department_3').on('click', function () {
        cur_department = 3;
        const period = get_period();
        $('#department_1').css('text-decoration', 'none');
        $('#department_2').css('text-decoration', 'none');
        $('#department_3').css('text-decoration', 'underline');
        get_rating(period['date_start'], period['date_end'], cur_department);
    });


    const today = new Date();
    const adjustDay = today.getDay() === 0 ? 6 : today.getDay() - 1;
    const firstDayOfWeek = new Date(today.setDate(today.getDate() - adjustDay));
    const startDate = firstDayOfWeek.toISOString().slice(0, 10).split('-').reverse().join('/');
    const endDate = new Date().toISOString().slice(0, 10).split('-').reverse().join('/');
    const currentWeek = [startDate, endDate].join(' - ');
    $('#reportrange').val(currentWeek);
    const period = get_period();
    get_rating(period['date_start'], period['date_end'], cur_department);
});