$(document).ready(function() {
	$('.timeago').timeago();

	$('#mbr_email').focus();
	$('#mbr_password').attr('value', '');
	$('#mbr_password_confirm').attr('value', '');
});
