function get_course() {

    const form_data = {
        'action': 'get_course',
    }

    $.ajax({
        url: '/scripts/education/education.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            $('.lessons').html(response);
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });
}

function run_lesson(block_id, action) {

    const form_data = {
        'action': action,
        'block_id': block_id,
    }

    $.ajax({
        url: '/scripts/education/education.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            if(response['warning']) {
                alert(response['warning']);
            }
            else {
                get_course();
                alert(response['successfully']);                
            }
        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });
}

function get_learning_statistic(department) {

    const form_data = {
        'action': 'get_learning_statistic',
        'department': department,
    }

    $.ajax({
        url: '/scripts/education/education.php',
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

    $('.start-lesson').on('click', function (e) {
        const lesson = $(e.target).closest('.lesson');
        const block_id = $(lesson).attr('data-block-id');
        run_lesson(block_id, 'start_lesson');
    });

    $('.repeat-lesson').on('click', function (e) {
        const lesson = $(e.target).closest('.lesson');
        const block_id = $(lesson).attr('data-block-id');
        run_lesson(block_id, 'repeat_lesson');
    });

    $('#department_1').on('click', function () {
        $('#department_1').css('text-decoration', 'underline');
        $('#department_2').css('text-decoration', 'none');
        get_learning_statistic(1);
    });

    $('#department_2').on('click', function () {
        $('#department_1').css('text-decoration', 'none');
        $('#department_2').css('text-decoration', 'underline');
        get_learning_statistic(2);
    });

    get_learning_statistic(1);

});