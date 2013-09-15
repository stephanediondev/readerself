function registerContentHandler() {
	try {
		window.navigator.registerContentHandler('application/rss+xml', base_url + '?u=%s', title);
	} catch (e) {
		debug(e.message || e);
	}
}
$(document).ready(function() {
	if (!!window.navigator.registerContentHandler) {
		$('#registerContentHandler').css({'display': 'inline-block'});

		$('#registerContentHandler').bind('click', function(event) {
			event.preventDefault();
			registerContentHandler();
		});

		$('a.priority').bind('click', function(event) {
			event.preventDefault();
			ref = $(this);
			params = [];
			params.push({'name': csrf_token_name, 'value': $.cookie(csrf_cookie_name)});
			$.ajax({
				async: true,
				cache: true,
				data: params,
				dataType: 'json',
				statusCode: {
					200: function(data_return, textStatus, jqXHR) {
						if(data_return.status == 'priority') {
							ref.find('.not_priority').hide();
							ref.find('.priority').show();
						}
						if(data_return.status == 'not_priority') {
							ref.find('.priority').hide();
							ref.find('.not_priority').show();
						}
					}
				},
				type: 'POST',
				url: ref.attr('href')
			});
		});
	}
});
