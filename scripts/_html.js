function debug(data) {
	if(window.console && console.debug) {
		console.debug(data);
	} else if(window.console && console.log) {
		console.log(data);
	}
}
function set_modal_call(content) {
	$('#modal_call').html(content);
	$('#modal_call').modal();
}
$(document).ready(function() {
	if(timezone == false) {
		d = new Date();
		params = [];
		params.push({'name': 'timezone', 'value': -d.getTimezoneOffset() / 60});
		params.push({'name': csrf_token_name, 'value': $.cookie(csrf_cookie_name)});
		$.ajax({
			async: true,
			cache: true,
			data: params,
			dataType: 'json',
			statusCode: {
				200: function(data_return, textStatus, jqXHR) {
				}
			},
			type: 'POST',
			url: base_url + 'home/timezone'
		});
	}

	$('.modal_call').live('click', function(event) {
		event.preventDefault();
		/*var ref = $(this);
		params = [];
		params.push({'name': csrf_token_name, 'value': $.cookie(csrf_cookie_name)});
		$.ajax({
			async: true,
			cache: true,
			data: params,
			dataType: 'json',
			statusCode: {
				200: function(data_return, textStatus, jqXHR) {
					if(data_return.modal) {
						set_modal_call(data_return.modal);
					}
				}
			},
			type: 'POST',
			url: ref.attr('href')
		});*/
	});

	$('#modal_call form').live('submit', function(event) {
		event.preventDefault();
		var ref = $(this);
		var params = ref.serializeArray();
		$.ajax({
			async: true,
			cache: true,
			data: params,
			dataType: 'json',
			statusCode: {
				200: function(data_return, textStatus, jqXHR) {
					if(data_return.result_subscribe) {
						$('#modal_call').modal('hide');
						$('.sidebar-nav li').removeClass('active');

						$('.nav-list').find('.result').remove();
						content = '<li class="result active"><a id="load-sub-' + data_return.result_subscribe.sub_id + '-items" href="' + base_url + 'home/items/sub/' + data_return.result_subscribe.sub_id + '">' + data_return.result_subscribe.fed_title + ' (<span>0</span>)</a></li>';
						$('.nav-list').append(content);

						load_items(base_url + 'home/items/sub/' + data_return.result_subscribe.sub_id);
					}
					if(data_return.modal) {
						set_modal_call(data_return.modal);
					}
				}
			},
			type: 'POST',
			url: ref.attr('action')
		});
	});
});
