$(document).ready(function() {
	$('#side-bar-icon').click(function(){
		if($('#side-bar').css('display')=='none'){
			$('#side-bar').stop().fadeIn(2000);
		}else{
			$('#side-bar').stop().fadeOut(2000);
		}
	});
	$('.side-link').click(function(){
		if($(this).siblings().css('display')!='none'){
			$(this).siblings().slideUp(500);
		}else{
			$('.child-options').stop().slideUp(500);
			$(this).siblings().stop().slideDown(500);
		}
		$(this).blur();
		return false;
	});
	$( "#started_at" ).datepicker({
		dateFormat : "yy-mm-dd",
		changeMonth: true,
		changeYear: true
	});
	$('#save_btn').click(function(){
		$(this).addClass("disabled");
	});
	// $('#side-bar-icon').blur(function(){
	// 	$('#side-bar').stop().fadeOut(2000);
	// });
});