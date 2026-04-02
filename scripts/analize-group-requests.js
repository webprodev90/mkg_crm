function date_eng_format(date) {
    if (date) {
        return date.split('/').reverse().join('-')
    }
}

var view_name = 'analize_group';

function get_stats(date_start, date_end, group_id = null) {

    const form_data = {
        'action': 'get_stats',
        'date_start': date_start,
        'date_end': date_end,
        'group_id': group_id,
    }

    $.ajax({
        url: '/scripts/analize/analize.php',
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

function get_stats_geo(date_start, date_end) {

    const form_data = {
        'action': 'get_stats_geo',
        'date_start': date_start,
        'date_end': date_end,
    }

    $.ajax({
        url: '/scripts/analize/analize.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('#datatable2').html(response);
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });
}

function get_groups(date_start, date_end) {
    const form_data = {
        'action': 'get_groups_requests',
        'date_start': date_start,
        'date_end': date_end,
    }

    $.ajax({
        url: '/scripts/analize/analize.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            let data = JSON.parse(response);
            options = '';
            for (let i = 0; i < data.length; i++) {
                options += `
                    <option value="${data[i]['id']}">
                        ${data[i]['name']} | ${data[i]['city_group']} | ${data[i]['date']} |
                        ${data[i]['count_request']} шт.
                    </option>
                `;
            }

            $('#groups_requests').html(options);
            //const group_id = $('#groups_requests option:first').val();
            //get_stats(group_id);
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
        get_groups(period['date_start'], period['date_end']);
        get_stats(period['date_start'], period['date_end']);
        get_stats_geo(period['date_start'], period['date_end']);
    });

    $('#event_source').on('click', function () {
        const period = get_period();
        $('#datatable1').removeClass('d-none');
        $('#datatable2').addClass('d-none');
        $('#event_source').css('text-decoration', 'underline');
        $('#event_kpd_geo').css('text-decoration', 'none');
        get_stats(period['date_start'], period['date_end']);
    });

    $('#event_kpd_geo').on('click', function () {
        const period = get_period();
        $('#datatable1').addClass('d-none');
        $('#datatable2').removeClass('d-none');
        $('#event_source').css('text-decoration', 'none');
        $('#event_kpd_geo').css('text-decoration', 'underline');
        get_stats(period['date_start'], period['date_end']);
    });

    $('#groups_requests').on('change', function () {
        const period = get_period();
        const group_id = $(this).val();
        $('#datatable1').removeClass('d-none');
        $('#datatable2').addClass('d-none');    
        $('#event_source').css('text-decoration', 'underline');
        $('#event_kpd_geo').css('text-decoration', 'none');            
        get_stats(period['date_start'], period['date_end'], group_id);
    });

    const period = get_period();
    get_groups(period['date_start'], period['date_end']);
});