

function get_audiorecording(phone_number) {
    const form_data = {
        'action': 'get_audiorecording',
        'phone_number': phone_number,
    }

    $.ajax({
        url: '/scripts/sale/sale.php',
        method: 'POST',
        data: form_data,
        success: function (response) {
            //console.log(response);
            $('.audio-links').html('');
            if(response) {
                response.forEach((item, index) => {
                    $('.audio-links').append(`<div class="audio-link mt-2">
                                               <a href="${item.link}" target="_blank">${item.name}</a>
                                            </div>`);                             
                });                
            } else {
                $('.audio-links').append('<div class="text-center">Ничего не найдено</div>');
            }

        },
        error: function (error) {
            alert('Ошибка запроса:', error);
        }
    });
}

$(document).ready(function () {
    event_start('click', '.listen-audio', function (e) {
        const phone_number = $(e).attr('data-phone').slice(-10);
        //console.log(phone_number);
        get_audiorecording(phone_number);
    });
});