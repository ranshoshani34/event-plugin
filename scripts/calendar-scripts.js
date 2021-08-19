
jQuery(document).ready( function() {

	// This action will perform when the month is changed by user (calendar view).
	jQuery(".rep_month_picker").on('submit', function(e) {
		e.preventDefault();

		let form = e.target;
		let formData = new FormData(form);

		formData.append('action','change_month');

		jQuery.ajax({
			type : "POST",
			dataType : "json",
			url : myAjax.ajaxurl,
			contentType: false,
			processData: false,
			data : formData,
			success: function(response) {
				if(response.type == "success") {
					jQuery("#rep_calendar").html(response.calendar);
				}
				else {
					alert("Your event could not be added")
				}
			}
		})

	})

})