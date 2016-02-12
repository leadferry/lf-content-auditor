jQuery( document ).ready( function() {



	jQuery( "#report_generator" ).submit(function ( e ) {
		e.preventDefault();

		jQuery( "#report_generator" ).append('<div class="update-nag"> <p><strong>Report generation has been scheduled. You can now navigate away from this page. Once the report generation is complete, you will receive an email. Based on the number of posts/pages and the metrics you have selected, this could take several minutes.</strong>.</p></div>');
		var ajax_data = {
			action: 'report_generator',
		}
		
		jQuery.ajax({
			url: data.url,
			type: 'POST',
			data: ajax_data,
		});
	});
});