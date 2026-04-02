let order_by = '';
let filter = {};

function event_start(event_type, element, callback) {
    $(document).on(event_type, element, function () {
        callback($(this));
    });
}

function get_details(id, name) {

    const form_data = {
        'action': 'get_details',
        'id': id,
        'name': name,
    }

    $.ajax({
        url: '/scripts/partners_plan/partners_plan.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('#details-table tbody').empty();
            if (response) {
                let last_date = '';
                for(let i = 0; i < response.length; i++) {
                    if(i !== 0) {
                        let cur_day = new Date(response[i]['date']);
                        let day = cur_day.getDate() - 1;
                        cur_day.setDate(day);
                        let formattedDate = cur_day.toISOString().split('T')[0];
                        while(last_date !== formattedDate) {
                            cur_day = new Date(last_date);
                            day = cur_day.getDate() + 1;
                            cur_day.setDate(day);
                            last_date = cur_day.toISOString().split('T')[0];
                            const tr = $('<tr>');
                            tr.append($('<td>').text(last_date.split('-').reverse().join('.')));
                            tr.append($('<td>').text(0));
                            tr.append($('<td>').text(''));  
                            $('#details-table tbody').append(tr);                      
                        }                        
                    }
                    const tr = $('<tr>').attr('data-date', response[i]['date']);
                    tr.append($('<td>').text(response[i]['date'].split('-').reverse().join('.')));
                    tr.append($('<td>').text(response[i]['count']));                            
                    tr.append($('<td>').html('<a href="#" class="open-date-requests" data-toggle="modal" data-target="#date-requests-modal">Подробнее</a>'));
                    $('#details-table tbody').append(tr);
                    last_date = response[i]['date'];                 
                }
            } else {
                alert('Не существует данных');
            }
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    })
}

function get_date_requests(id, name, date) {

    const form_data = {
        'action': 'get_date_requests',
        'id': id,
        'date': date,
        'name': name,
    }

    $.ajax({
        url: '/scripts/partners_plan/partners_plan.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('#date-requests-table tbody').empty();
            if(response) {
                for(let i = 0; i < response.length; i++) {
                    const tr = $('<tr>');
                    tr.append($('<td>').text(response[i]['id']));
                    tr.append($('<td>').text(response[i]['fio']));
                    tr.append($('<td>').text(response[i]['phone_number']));
                    tr.append($('<td>').text(response[i]['city']));
                    tr.append($('<td>').text(response[i]['vopros']));
                    $('#date-requests-table tbody').append(tr);           
                }
            } else {
                alert('Не существует данных');
            }
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    })
}

function sort_table() {

    const form_data = {
        'action': 'get_partners_plan',
        'view_name': 'partners_plan',
        'order_by': order_by,
    }

    if (Object.keys(filter).length > 0) {
        form_data['filter'] = filter;
    }     

    $.ajax({
        url: '/scripts/partners_plan/partners_plan.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('#table-request').html(response);
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    })

}

$(document).ready(function () {

    event_start('click', '.open-request', function (e) {
        const request = $(e).closest('tr');
        let modal_request = $('.form-request');

        const get_value = (value) => {
            return $(request).find('[name="' + value + '"]').text();
        }

        const set_element = (key) => {
            $(modal_request).find('[name="' + key + '"]').val(get_value(key).trim());
        }

        set_element('id');
        set_element('city');
        set_element('quantity_per_day');
        set_element('otbrakovka');
        set_element('total_quantity');
        
        const partner_element = $(request).find('[name="partner_name"]');
        const partner_id = partner_element.attr('attr-id');
        $($(modal_request).find('[name="partner_id"]').html($('.partners').html())).val(partner_id);

        const date_start = $(request).find('[name="date_start"]').attr('attr-date-start');
        $(modal_request).find('[name="date_start"]').val(date_start.trim());

        const comment = $(request).find('[name="vopros"]').attr('full-vopros');
        $(modal_request).find('[name="vopros"]').val(comment.trim());

    });

    event_start('click', '.show-comment', function (e) {
        const full_comment = $(e).closest('td').attr('full-vopros');
        $(e).closest('td').html(full_comment);
    });

    event_start('click', '#save-request', function (e) {
        const form_data = {
            'params': $('.form-request').serializeArray(),
            'action': 'update_request',
            'id': $('#idval').val(),
        }

        $.ajax({
            url: '/scripts/partners_plan/partners_plan.php',
            method: 'POST',
            data: form_data,
            success: function (response) {
                    $('#signup-modal').modal('hide');
                    const id_request = $('#idval').val();
                    let td = $(`[tr-id="${id_request}"]`).find('td');
                    td.filter('[name]').each(function () {
                        const name = $(this).attr('name');
                        if (response.hasOwnProperty(name)) {
                            let elem = response[name];
                            $(this).html(elem);
                        }
                    });
                    td.filter('[name="partner_name"]').attr('attr-id', response.partner_id);
                    $(`[tr-id="${id_request}"]`).find('[name="date_start"]').attr('attr-date-start', response.date_start ? response.date_start : '');
                    $(`[tr-id="${id_request}"]`).find('[name="date_start"]').text(response.date_start ? response.date_start.split('-').reverse().join('.') : '');
                    $(`[tr-id="${id_request}"]`).find('[name="date_end2"]').text(response.date_end2 ? response.date_end2.slice(0, 10).split('-').reverse().join('.') : '');
                    $(`[tr-id="${id_request}"]`).find('span[name="remainder1"]').text(+response.total_quantity - +response.shipped1);
                    $(`[tr-id="${id_request}"]`).find('[name="remainder2"]').text(+response.otbrakovka - +response.shipped2);
                    $(`[tr-id="${id_request}"]`).find('span[name="shipped1"]').text(response.shipped1);
                    $(`[tr-id="${id_request}"]`).find('[name="vopros"]').attr('full-vopros', response.vopros);
                
            },
            error: function (error) {
                alert('Ошибка запроса:', error);
            }
        });

    });

    event_start('click', '.delete-request', function (e) {
        const id = $(e).attr('data-id');
        let isDelete = confirm("Вы точно хотите удалить?");
        
        if(isDelete) {
            const form_data = {
                'action': 'delete_plan',
                'view_name': 'partners_plan',
                'id': id,
            }
            $.ajax({
                url: '/scripts/partners_plan/partners_plan.php',
                method: 'POST',
                data: form_data,
                success: function (response) {
                    $(`tr[tr-id=${id}]`).remove();
                },
                error: function (error) {
                    alert('Ошибка запроса:', error);
                }
            });   
        }
    });

    event_start('click', '.open-details', function (e) {
        const id = $(e).closest('tr').attr('tr-id');
        const name = $(e).closest('td').attr('name');
        $('#details-table').attr('data-id', id);
        $('#details-table').attr('data-shipped', name);
        get_details(id, name);
    });

    event_start('click', '.open-date-requests', function (e) {
        const id = $('#details-table').attr('data-id');
        const name = $('#details-table').attr('data-shipped');
        const date = $(e).closest('tr').attr('data-date');
        get_date_requests(id, name, date);
    });

    event_start('click', '.sort-table', function (e) {
        order_by = $(e).attr('data-sort');
        sort_table();
    });

    event_start('click', '.search-plan-request', function (e) {

        const search_val = $('.search-plan-input').val();
        filter = {
                'st_partner_s.partner_name': `%${search_val}%`,
                'bez_partners_plan.city': `%${search_val}%`,
                'logical_operator': 'OR',
                'comparison_operator': 'LIKE',
        };

        const form_data = {
            'action': 'get_partners_plan',
            'view_name': 'partners_plan',
            'filter': filter,
        }

        if (order_by !== '') {
            form_data['order_by'] = order_by;
        }   

        $.ajax({
            url: '/scripts/partners_plan/partners_plan.php',
            method: 'POST',
            data: form_data,
            success: function (response) {
                $('#table-request').html(response);
            },
            error: function (error) {
                alert('Ошибка запроса:', error);
            }
        })
    });

});