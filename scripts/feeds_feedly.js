$(document).ready(function() {
	$('section section article.title a').bind('click', function(event) {
		event.preventDefault();
		href = $(this).attr('href');
		if($(href).is(':visible')) {
			$(href).hide();
		} else {
			$(href).show();
		}
	});
});
