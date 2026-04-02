let date_start = '';
let date_end = '';
let cur_trunk = '';
let cur_state_operators = '';
let dozvoncontacts = [];
let count_calls = [];
let dozvonlid = [];
let time_labels = [];
let date_labels = [];
let lineChart1;
let lineChart2;
let infoAtcInterval = null;

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

// Функция для создания линии нормы
function createNormDataset(labels, name_label, norm_value, border_color) {
    return {
        label: name_label,
        data: labels.map(() => norm_value), // Одно и то же значение для всех точек
        borderColor: border_color,
        borderWidth: 2,
        borderDash: [15, 10], // Пунктирная линия: 15px черта, 10px пропуск
        tension: 0, // Прямая линия без изгибов
        fill: false,
        pointRadius: 0, // Убираем точки на линии нормы
        pointHoverRadius: 0
    };
}

function get_pulse_per_day() {

    const form_data = {
        'action': 'get_pulse_per_day',
        'date_start': date_start,
        'date_end': date_end,
    }

    if(cur_trunk) {
        form_data.trunk = cur_trunk;
    }

    $.ajax({
        url: '/scripts/pulse_call_center/pulse_call_center.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            if(response) {
                dozvoncontacts = [];
                count_calls = [];
                dozvonlid = [];
                time_labels = [];
                response.forEach(function(item) {
                    time_labels.push(item.time_period);
                    dozvoncontacts.push(item.dozvoncontacts);
                    count_calls.push(item.count_calls);
                    dozvonlid.push(item.dozvonlid);
                });

                const ctx1 = document.getElementById('myChart1').getContext('2d');
                const ctx2 = document.getElementById('myChart2').getContext('2d');

                if(lineChart1) {
                    lineChart1.destroy();
                }

                if(lineChart2) {
                    lineChart2.destroy();
                }

                lineChart1 = new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: time_labels,
                        datasets: [
                            {
                                label: '% дозвона',
                                data: dozvoncontacts, 
                                borderColor: 'rgb(54, 162, 235)',
                                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                                tension: 0.1,
                                fill: false
                            },
                            {
                                label: 'Среднее количество дозвонов',
                                data: count_calls,
                                borderColor: 'rgb(255, 159, 64)',
                                backgroundColor: 'rgba(255, 159, 64, 0.1)',
                                tension: 0.1,
                                fill: false
                            },
                            createNormDataset(time_labels, 'Норма % дозвона', 60, 'rgba(54, 162, 235, 0.9)'),
                            createNormDataset(time_labels, 'Норма cреднего количества дозвонов', 8, 'rgba(255, 159, 64, 0.9)')
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Время'
                                },
                                ticks: {
                                    autoSkip: false,
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            },
                            y: {
                                min: 0,
                                max: 100,
                                title: {
                                    display: true,
                                    text: 'Количество'
                                },
                                ticks: {
                                    stepSize: 2,
                                    autoSkip: false
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    title: function(context) {
                                        return 'Время: ' + context[0].label;
                                    },
                                    label: function(context) {
                                        return context.dataset.label + ': ' + context.parsed.y;
                                    }
                                }
                            }
                        }
                    }
                });

                lineChart2 = new Chart(ctx2, {
                    type: 'line',
                    data: {
                        labels: time_labels,
                        datasets: [
                            {
                                label: '% лид/дозвон',
                                data: dozvonlid,
                                borderColor: 'rgb(255, 99, 132)',
                                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                                tension: 0.1,
                                fill: false
                            },
                            createNormDataset(time_labels, 'Норма % лид/дозвон', 2, 'rgba(255, 99, 132, 0.9)')
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Время'
                                },
                                ticks: {
                                    autoSkip: false,
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            },
                            y: {
                                min: 0,
                                max: 25,
                                title: {
                                    display: true,
                                    text: 'Количество'
                                },
                                ticks: {
                                    stepSize: 0.5,
                                    autoSkip: false
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    title: function(context) {
                                        return 'Время: ' + context[0].label;
                                    },
                                    label: function(context) {
                                        return context.dataset.label + ': ' + context.parsed.y;
                                    }
                                }
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
            if (error.responseText) {
                alert("Ошибка: " + error.responseText);
            } else {
                alert("HTTP ошибка: " + error.status + " - " + error.statusText);
            }
        }
    });
}

function get_pulse_for_dates() {

    const form_data = {
        'action': 'get_pulse_for_dates',
        'date_start': date_start,
        'date_end': date_end,
    }

    if(cur_trunk) {
        form_data.trunk = cur_trunk;
    }

    $.ajax({
        url: '/scripts/pulse_call_center/pulse_call_center.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            if(response) {
                dozvoncontacts = [];
                count_calls = [];
                dozvonlid = [];
                date_labels = [];
                response.forEach(function(item) {
                    date_labels.push(item.date_day);
                    dozvoncontacts.push(item.dozvoncontacts);
                    count_calls.push(item.count_calls);
                    dozvonlid.push(item.dozvonlid);
                });
                let max_count_calls = Math.max(Math.max(...count_calls) + 5, 100);
                
                if(lineChart1) {
                    lineChart1.destroy();
                }

                if(lineChart2) {
                    lineChart2.destroy();
                }

                const ctx1 = document.getElementById('myChart1').getContext('2d');
                const ctx2 = document.getElementById('myChart2').getContext('2d');

                lineChart1 = new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: date_labels,
                        datasets: [
                            {
                                label: '% дозвона',
                                data: dozvoncontacts,
                                borderColor: 'rgb(54, 162, 235)',
                                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                                tension: 0.1,
                                fill: false,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            },
                            {
                                label: 'Среднее количество дозвонов',
                                data: count_calls,
                                borderColor: 'rgb(255, 159, 64)',
                                backgroundColor: 'rgba(255, 159, 64, 0.1)',
                                tension: 0.1,
                                fill: false,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Даты'
                                },
                                ticks: {
                                    autoSkip: false,
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            },
                            y: {
                                min: 0,
                                max: max_count_calls,
                                title: {
                                    display: true,
                                    text: 'Количество'
                                },
                                ticks: {
                                    stepSize: max_count_calls > 100 ? 5 : 2,
                                    autoSkip: false
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    title: function(context) {
                                        return 'Дата: ' + context[0].label;
                                    },
                                    label: function(context) {
                                        return context.dataset.label + ': ' + context.parsed.y;
                                    }
                                }
                            }
                        }
                    }
                });

                lineChart2 = new Chart(ctx2, {
                    type: 'line',
                    data: {
                        labels: date_labels,
                        datasets: [
                            {
                                label: '% лид/дозвон',
                                data: dozvonlid,
                                borderColor: 'rgb(255, 99, 132)',
                                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                                tension: 0.1,
                                fill: false,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Даты'
                                },
                                ticks: {
                                    autoSkip: false,
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            },
                            y: {
                                min: 0,
                                max: 25,
                                title: {
                                    display: true,
                                    text: 'Количество'
                                },
                                ticks: {
                                    stepSize: 0.5,
                                    autoSkip: false
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    title: function(context) {
                                        return 'Дата: ' + context[0].label;
                                    },
                                    label: function(context) {
                                        return context.dataset.label + ': ' + context.parsed.y;
                                    }
                                }
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
            if (error.responseText) {
                alert("Ошибка: " + error.responseText);
            } else {
                alert("HTTP ошибка: " + error.status + " - " + error.statusText);
            }
        }
    });
}

function startInfoAtcInterval() {

    if (infoAtcInterval) {
        clearInterval(infoAtcInterval);
    }
    
    get_info_atc();
    infoAtcInterval = setInterval(get_info_atc, 10000);

}

function get_info_atc() {

    const form_data = {
        'action': 'get_info_atc',
    }

    $.ajax({
        url: '/scripts/pulse_call_center/pulse_call_center.php',
        dataType: 'json',
        method: 'POST',
        data: form_data,
        success: function (response) {
            if(response) {
                $('.table-states tbody').empty();
                response.forEach((item) => {
                    if(item.status != 'Не в сети' || cur_state_operators != 'Кто в сети') {
                        let tr = `<tr>
                                    <td>${item.id_otdel}</td>
                                    <td>${item.name}</td>
                                    <td>${item.status}</td>
                                    <td>${item.last_call}</td>
                                    <td>${item.calls_taken}</td>
                                </tr>`;
                        $('.table-states tbody').append(tr);                        
                    }
                });

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

function get_mobile_operators_stats() {
    const form_data = {
        'action': 'get_mobile_operators_stats',
        'date_start': date_start,
        'date_end': date_end,
    }

    $.ajax({
        url: '/scripts/pulse_call_center/pulse_call_center.php',
        dataType: 'json',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('.table-operators-stats tbody').empty();
            if(response.length !== 0) {
                let mobile_operators = ['Билайн', 'Мегафон', 'МТС', 'Т2', 'Все остальное', 'Городской телефон', 'Оператор не определен'];
                //console.log(response);
                for(let key in response) {
                    //console.log(key); 
                    //console.log(response[key]); 
                    let tr_percent_dozvon = `<tr>
                                                <th rowspan="2" style="vertical-align: middle;">${key}</th>
                                                <th>%дозвона</th>`;
                    let tr_no_ao = `<tr>
                                        <th>Количество не АО</th>`;

                    mobile_operators.forEach((operator) => {
                        let percent_dozvon = 0;
                        let no_ao = 0;
                        if(operator in response[key]) {
                            percent_dozvon = response[key][operator].percent_dozvon;
                            no_ao = response[key][operator].no_ao;
                        }
                        tr_percent_dozvon += `<td>${percent_dozvon}%</td>`;
                        tr_no_ao += `<td>${no_ao}%</td>`;
                    });
                    tr_percent_dozvon += '</tr>';
                    tr_no_ao += '</tr>';
                    $('.table-operators-stats tbody').append(tr_percent_dozvon + tr_no_ao);                
                }                
            } else {
                $('.table-operators-stats tbody').append('<tr><td colspan="9">Нет данных</td></tr>'); 
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
        if(date_start == date_end) {
            get_pulse_per_day();
        } else {
            get_pulse_for_dates();
        }
        get_mobile_operators_stats();        
    });

    $('.select-trunk').on('change', function (e) {
        cur_trunk = $(this).val();
        if(date_start == date_end) {
            get_pulse_per_day();
        } else {
            get_pulse_for_dates();
        }
    });

    $('.select-state-operators').on('change', function (e) {
        cur_state_operators = $(this).val();
        startInfoAtcInterval();
    });

    let period = get_period();
    date_start = period['date_start'];
    date_end = period['date_end']; 
    if(date_start == date_end) {
        get_pulse_per_day();
    } else {
        get_pulse_for_dates();
    }
    get_mobile_operators_stats();
    startInfoAtcInterval();

});