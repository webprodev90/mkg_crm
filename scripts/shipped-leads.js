let partner_id = 0;
let date_start = '';
let date_end = '';
let barChart;

function date_eng_format(date) {
    if (date) {
        return date.split('/').reverse().join('-')
    }
}

function get_dates(start, end) {
    const dates = {};
    while(start <= end) {
        dates[new Date(start).toISOString().split('T')[0].split('-').reverse().join('.')] = 0;
        start.setDate(start.getDate() + 1);
    }
    return dates;
}

function get_period() {
    dates = $('#reportrange').val().split(' - ');
    return {
        date_start: date_eng_format(dates[0]),
        date_end: date_eng_format(dates[1]),
    }
}

function get_partner_statistics() {

    const form_data = {
        'action': 'get_partner_statistics',
        'partner_id': partner_id,
        'date_start': date_start,
        'date_end': date_end,
    }

    $.ajax({
        url: '/scripts/shipped_leads/shipped_leads.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            if(response) {
                let dates = get_dates(new Date(date_start), new Date(date_end));  

                response.forEach((item) => {
                    if(item.shipping_date.split('-').reverse().join('.') in dates) {
                        dates[item.shipping_date.split('-').reverse().join('.')] = +item.col;
                    } 
                });

                const ctx = document.getElementById('myChart');

                if(barChart) {
                    barChart.destroy();
                }
                  
                barChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                      labels: Object.keys(dates),
                      datasets: [{
                        label: 'Количество лидов',
                        data: Object.values(dates),
                        borderWidth: 1
                      }]
                    },
                    options: {
                      scales: {
                        y: {
                          beginAtZero: true
                        }
                      }
                    }
                });


            }
            else {
                alert('Не существует данных за текущий период!');
            }
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });
}





$(document).ready(function () {

    $('.change-partner').on('change', function () {    
        partner_id = $(this).val();
        if(partner_id !== 0) {
            get_partner_statistics(); 
        }
        else {
            alert("Не выбран партнер!");
        }
    });

    $('#check-minutes').on('click', function (e) {
        e.preventDefault();
        let period = get_period();
        date_start = period['date_start'];
        date_end = period['date_end']; 
        if(partner_id !== 0) {
            get_partner_statistics(); 
        }
        else {
            alert("Не выбран партнер!");
        }
    });

    let period = get_period();
    date_start = period['date_start'];
    date_end = period['date_end']; 
});