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
	}
});
