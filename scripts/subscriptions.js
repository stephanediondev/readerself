var count_error = 0;
var count_all = 0;
function count_lines() {
	count_error = $('tr.line-error').length;
	count_all = $('tr.line-all').length + count_error;
}
$(document).ready(function() {
	count_lines();

	$('#filter-all').bind('click', function(event) {
		$('tr.line-all td, tr.line-all th').show();
		$('tr.line-error td, tr.line-error th').show();
		$('h1').find('span').html(count_all);
	});
	$('#filter-error').bind('click', function(event) {
		$('tr.line-all td, tr.line-all th').hide();
		$('tr.line-error td, tr.line-error th').show();
		$('h1').find('span').html(count_error);
	});
});
