var count_error = 0;
var count_all = 0;
function count_lines() {
	count_error = $('tr.line-error').length;
	count_all = $('tr.line-all').length + count_error;
}
$(document).ready(function() {
	count_lines();

	$('.tag-toggle').bind('click', function(event) {
		event.preventDefault();
		var ref = $(this);
		params = [];
		params.push({'name': csrf_token_name, 'value': $.cookie(csrf_cookie_name)});
		$.ajax({
			async: true,
			cache: true,
			data: params,
			dataType: 'json',
			statusCode: {
				200: function(data_return, textStatus, jqXHR) {
					$('#sub_' + data_return.sub_id).find('.tag-title').html(ref.html());
				}
			},
			type: 'POST',
			url: ref.attr('href')
		});
	});

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
