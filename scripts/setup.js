function change_database_type() {
	var value_database = $('#database_type').val();
	if(value_database == 'mysql') {
		$('.database_option').show();
	} else {
		$('.database_option').hide();
	}
}
$(document).ready(function() {
	change_database_type();

	$('#database_type').bind('change', function(event) {
		change_database_type();
	});
});
