$(document).ready(function() {
	$('.timeago').timeago();

	$('#mbr_password').attr('value', '');
	$('#mbr_password_confirm').attr('value', '');

	$('#mbr_email_confirm, #mbr_password_confirm').bind('paste', function(event) {
		event.preventDefault();
	});
});
