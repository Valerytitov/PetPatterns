jQuery(function($) {
	$(document).ready(function() {
		
		$('.hint').tooltip({
			tooltipClass: 'my_tooltip'
		});
		
		$(document).on('click', '#doVfile', function(e) {
			e.preventDefault();
			
			var vForm = $('#formVfile');
			
			$.ajax({
				
				url: vForm.attr('action'),
				type: 'post',
				data: vForm.serialize(),
				dataType: 'json',
				beforeSend: function() {
					
				},
				success: function(response) {
					
					if (response.status == 'ok') {
						
						window.location.href = response.payment;
						
					}
					
				}
				
			});
			
		});
		
		$(document).on('click', '#resetVfile', function() {
			
			$('.vfile_prop').each(function() {
				
				$(this).val($(this).data('default'));
				
			});
			
		});
		
	});
});