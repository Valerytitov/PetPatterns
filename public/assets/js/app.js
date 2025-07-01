jQuery(function($) {
	$(document).ready(function() {
		
		$('.hint').tooltip({
			tooltipClass: 'my_tooltip'
		});
		
		$(document).on('click', '#resetVfile', function() {
			$('.vfile_prop').each(function() {
				$(this).val($(this).data('default'));
			});
		});
		
	});
});