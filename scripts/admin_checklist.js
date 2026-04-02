function get_checklist(user_id) {
    const form_data = {
        'action': 'get_checklist',
        'user_id': user_id,
        'view_name': 'admin_tasks',
    }

    $.ajax({
        url: '/scripts/admin_checklist/admin_checklist.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            if(response) {
                $('#checklist').html(response); 
            }
            else {
                $('#checklist').html('<h4 class="text-center font-weight-normal">Задач нет</h4>');
            }
            $('.btn-create-admin-task').removeClass('d-none');
            $('.form-admin-task').addClass('d-none');
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });
}

function get_user(user_id) {
    const form_data = {
        'action': 'get_user',
        'user_id': user_id,
    }

    $.ajax({
        url: '/scripts/admin_checklist/admin_checklist.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('#checklist-user-name').text(response.name);
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });
}

function toggle_done_task(id, is_completed) {
    const form_data = {
        'action': 'toggle_done_task',
        'id': id,
        'is_completed': is_completed,
    }

    $.ajax({
        url: '/scripts/admin_checklist/admin_checklist.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });
}

function create_task(user_id, task_description) {
    const form_data = {
        'action': 'create_task',
        'user_id': user_id,
        'task_description': task_description,
    }

    $.ajax({
        url: '/scripts/admin_checklist/admin_checklist.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            get_checklist(user_id);
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    }); 
}

function update_task(id, task_description, admin_task) {
    const form_data = {
        'action': 'update_task',
        'id': id,
        'task_description': task_description,
    }

    $.ajax({
        url: '/scripts/admin_checklist/admin_checklist.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $(admin_task).find('.wrapper-admin-task').removeClass('d-none').addClass('d-flex');
            $(admin_task).find('.form-updated-task').addClass('d-none');
            const task_description_block = $(admin_task).find('.task_description')[0];
            $(task_description_block).text(task_description);

        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    }); 
}

function delete_task(id) {
    const form_data = {
        'action': 'delete_task',
        'id': id,
    }

    $.ajax({
        url: '/scripts/admin_checklist/admin_checklist.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            if($('#checklist .admin-task').length === 1) {
                $('#checklist').html('<h4 class="text-center font-weight-normal">Задач нет</h4>');
            } 
            else {
                $(`.admin-task[data-id='${id}']`).remove();
            }
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    }); 
}

$(document).ready(function () {

    event_start('click', '.checklist-btn', function (e) {
        const user_id = $(e).attr('data-user-id');
        $('#checklist').attr('data-user-id', user_id);
        get_checklist(user_id);
        get_user(user_id);
    });

    event_start('click', '.delete-admin-task', function (e) {
        const task_id = $(e).closest('.admin-task').attr('data-id');
        let isDelete = confirm("Вы точно хотите удалить?");
        
        if(isDelete) {
           delete_task(task_id);
        }
    });

    event_start('click', 'input[name="is_completed"]', function (e) {
        const task_id = $(e).closest('.admin-task').attr('data-id');
        const is_checked = $(e).is(':checked');
        const is_completed = Number(is_checked);
        toggle_done_task(task_id, is_completed);
    });

    event_start('click', '#create-admin-task', function (e) {
        $('.btn-create-admin-task').addClass('d-none');
        $('.form-admin-task').removeClass('d-none');
        $('[name="new-task"]').val('');
    });

    event_start('click', '#create-task-cancel', function (e) {
        $('.btn-create-admin-task').removeClass('d-none');
        $('.form-admin-task').addClass('d-none');
    });

    event_start('click', '#add-new-task', function (e) {
        const user_id = $('#checklist').attr('data-user-id');
        const task_description = $('#new-task').val().trim();
        create_task(user_id, task_description);
    });

    event_start('click', '.update-admin-task', function (e) {
        const admin_task = $(e).closest('.admin-task');
        $(admin_task).find('.wrapper-admin-task').removeClass('d-flex').addClass('d-none');
        $(admin_task).find('.form-updated-task').removeClass('d-none');
    });

    event_start('click', '.update-admin-task-cancel', function (e) {
        const admin_task = $(e).closest('.admin-task');
        $(admin_task).find('.wrapper-admin-task').removeClass('d-none').addClass('d-flex');
        $(admin_task).find('.form-updated-task').addClass('d-none');
    });

    event_start('click', '.save-admin-task', function (e) {
        const task_id = $(e).closest('.admin-task').attr('data-id');
        const admin_task = $(e).closest('.admin-task');
        const task_description = $(admin_task).find('#updated-admin-task').val().trim();
        update_task(task_id, task_description, admin_task);
    });

});