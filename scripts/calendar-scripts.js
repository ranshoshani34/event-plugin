
jQuery(document).ready( function() {

	// This action will perform when next/previous month is clicked.
	jQuery(".rep-calendar-button").on('click', function(e) {
		e.preventDefault();

		let newMonth = jQuery(this).attr("month")
		let newYear = jQuery(this).attr("year")


		jQuery.ajax({
			type : "POST",
			dataType : "json",
			url : myAjax.ajaxurl,
			contentType: false,
			processData: false,
			data : {action: 'change_month', month: newMonth, year: newYear},
			success: function(response) {
				if(response.type == "success") {
					jQuery("#rep_calendar").html(response.calendar);
				}
				else {
					alert("There was a problem loading the new month")
				}
			}
		})

	})

})