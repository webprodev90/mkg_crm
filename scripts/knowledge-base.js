document.ondragstart = noselect; // запрещает перетаскивание 
document.onselectstart = noselect; // запрещает выделение элементов страницы 
document.oncontextmenu = noselect; // запрещает выведение контекстного меню 

function noselect() {
    return false;
}


$(document).ready(function (e) {

    $(window).on("focus", function() {

    	const form_data = {
	        'action': 'check_access_to_theories',
	        'test_id': $('.education-title').attr('data-theory'),
    	}

	    $.ajax({
	        url: '/scripts/testing/testing.php',
	        method: 'POST',
	        data: form_data,
	        success: function (response) {
	        	if(response) {
	        		$('.education-theory').remove();
	        		if($('.education-warning').length === 0) {
					   $('.card-box').append('<div class="education-warning">Во время тестирования просмотр теории запрещен!</div>'); 
					}
					$(window).off("focus");
	        	} 
	        },
	        error: function (error) {
	            alert('Ошибка запроса:', error);
	        }
	    }); 

        
    });

});