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

function get_lead_statistics(date_start, date_end) {

    const form_data = {
        'action': 'get_lead_statistics',
        'date_start': date_start,
        'date_end': date_end,
        'view_name': 'lead_statistics',
    }

    $.ajax({
        url: '/scripts/lead_statistics/lead_statistics.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('#table-lead-statistics').html(response);
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
        get_lead_statistics(period['date_start'], period['date_end']);
    });

});