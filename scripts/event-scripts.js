
jQuery(document).ready( function() {

	// This action will perform when the event creation form is submmited.
	jQuery(".js_create_event_form").on('submit', function(e) {
		e.preventDefault();

		let form = e.target;
		let formData = new FormData(form);
		let nonce = jQuery(this).attr("nonce")

		formData.append('action','process_form');
		formData.append('nonce',nonce);


		jQuery.ajax({
			type : "POST",
			dataType : "json",
			url : myAjax.ajaxurl,
			contentType: false,
			processData: false,
			data : formData,
			success: function(response) {
				if(response.type == "success") {
					jQuery("#rep_success_header").html(`<a href="${response.permalink}">Event link : ${formData.get('rep-title')}</a> `);
				}
				else {
					alert("Your event could not be added")
				}
			}
		})

	})

})