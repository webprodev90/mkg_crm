$(document).ready(function () {
	//const menu1 = documеnt.querySelector('.menu-item-6');
	
	if ($('.menu-item-1').hasClass("active")){
		$('.menu-item-1-0').addClass('d-none');
		$('.menu-item-1').addClass('d-block');
	}
	if ($('.menu-item-3').hasClass("active")){
		$('.menu-item-3-0').addClass('d-none');
		$('.menu-item-3').addClass('d-block');
	}
	if ($('.menu-item-4').hasClass("active")){
		$('.menu-item-4-0').addClass('d-none');
		$('.menu-item-4').addClass('d-block');
	}
	if ($('.menu-item-5').hasClass("active")){
		$('.menu-item-5-0').addClass('d-none');
		$('.menu-item-5').addClass('d-block');
	}
	if ($('.menu-item-6').hasClass("active")){
		$('.menu-item-6-0').addClass('d-none');
		$('.menu-item-6').addClass('d-block');
	}	
	
	$('.menu-item-1-0').click(function () {
		$('.menu-item-1-0').addClass('d-none');
		$('.menu-item-1').addClass('d-block');
		$('.menu-item-3-0').removeClass('d-none');
		$('.menu-item-3').removeClass('d-block');
		$('.menu-item-4-0').removeClass('d-none');
		$('.menu-item-4').removeClass('d-block');
		$('.menu-item-5-0').removeClass('d-none');
		$('.menu-item-5').removeClass('d-block');
		$('.menu-item-6-0').removeClass('d-none');
		$('.menu-item-6').removeClass('d-block');		
		
	});
	$('.menu-item-3-0').click(function () {
		$('.menu-item-3-0').addClass('d-none');
		$('.menu-item-3').addClass('d-block');
		$('.menu-item-1-0').removeClass('d-none');
		$('.menu-item-1').removeClass('d-block');
		$('.menu-item-4-0').removeClass('d-none');
		$('.menu-item-4').removeClass('d-block');
		$('.menu-item-5-0').removeClass('d-none');
		$('.menu-item-5').removeClass('d-block');
		$('.menu-item-6-0').removeClass('d-none');
		$('.menu-item-6').removeClass('d-block');		

	});	
	$('.menu-item-4-0').click(function () {
		$('.menu-item-4-0').addClass('d-none');
		$('.menu-item-4').addClass('d-block');
		$('.menu-item-3-0').removeClass('d-none');
		$('.menu-item-3').removeClass('d-block');
		$('.menu-item-1-0').removeClass('d-none');
		$('.menu-item-1').removeClass('d-block');
		$('.menu-item-5-0').removeClass('d-none');
		$('.menu-item-5').removeClass('d-block');
		$('.menu-item-6-0').removeClass('d-none');
		$('.menu-item-6').removeClass('d-block');			

	});	
	$('.menu-item-5-0').click(function () {
		$('.menu-item-5-0').addClass('d-none');
		$('.menu-item-5').addClass('d-block');
		$('.menu-item-3-0').removeClass('d-none');
		$('.menu-item-3').removeClass('d-block');
		$('.menu-item-4-0').removeClass('d-none');
		$('.menu-item-4').removeClass('d-block');
		$('.menu-item-1-0').removeClass('d-none');
		$('.menu-item-1').removeClass('d-block');
		$('.menu-item-6-0').removeClass('d-none');
		$('.menu-item-6').removeClass('d-block');		

	});	
	$('.menu-item-6-0').click(function () {
		$('.menu-item-6-0').addClass('d-none');
		$('.menu-item-6').addClass('d-block');
		$('.menu-item-3-0').removeClass('d-none');
		$('.menu-item-3').removeClass('d-block');
		$('.menu-item-4-0').removeClass('d-none');
		$('.menu-item-4').removeClass('d-block');
		$('.menu-item-5-0').removeClass('d-none');
		$('.menu-item-5').removeClass('d-block');
		$('.menu-item-1-0').removeClass('d-none');
		$('.menu-item-1').removeClass('d-block');		

	});	

	
});