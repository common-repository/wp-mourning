jQuery('document').ready(function($){
	var gray_percent = $('#grayscale_text').val();
	$('#grayscale_percent').slider({ value: gray_percent});
	$('span.gray_percent').text(gray_percent);
	$('.gray_image').css('filter', 'grayscale('+gray_percent+'%)');

	$('#grayscale_percent').on('slidechange', function( event, ui ) {
		var gray_percent = $('#grayscale_percent').slider('value');
		$('#grayscale_text').val(gray_percent);	
		$('span.gray_percent').text(gray_percent);	
		$('.gray_image').css('filter', 'grayscale('+gray_percent+'%)');
		$('.gray_image').css('-moz-filter', 'grayscale('+gray_percent+'%)');
		$('.gray_image').css('-webkit-filter', 'grayscale('+gray_percent+'%)');
	});

	$('.datepicker').datepicker({dateFormat: 'yy-mm-dd'});

});
