var notification_count = 0;
var result_subscriptions = [];
var lock_refresh = false;
var first_refresh = false;

function debug(data) {
	if(window.console && console.debug) {
		console.debug(data);
	} else if(window.console && console.log) {
		console.log(data);
	}
}
function create_notification(title) {
	notification = notify.createNotification(title, {
		body: '',
		icon: base_url + 'medias/readerself_200x200.png',
		tag: 'notification_count'
	});
	notification.close();
}
function refresh() {
	if(!lock_refresh && ci_controller != 'member') {
		params = [];
		if($('#last_crawl').length > 0) {
			params.push({'name': 'last_crawl', 'value': true});
		}
		params.push({'name': 'subscriptions', 'value': result_subscriptions.join(',')});
		params.push({'name': csrf_token_name, 'value': $.cookie(csrf_cookie_name)});
		$.ajax({
			async: true,
			cache: true,
			data: params,
			dataType: 'json',
			statusCode: {
				200: function(data_return, textStatus, jqXHR) {
					if( (is_logged == true && data_return.is_logged == false) || (is_logged == false && data_return.is_logged == true) ) {
						window.location.href = base_url;
					}
					is_logged = data_return.is_logged;
					for(i in data_return.count) {
						$('#load-' + i + '-items').find('span').html(data_return.count[i]);
						$('#intro-load-' + i + '-items').html(data_return.count[i]);
					}
					window.document.title = '(' + data_return.count.all + ') ' + title;

					if(first_refresh && data_return.count.all > notification_count) {
						if(notify.permissionLevel() == notify.PERMISSION_GRANTED) {
							create_notification(data_return.count.all + ' unread items');
						}
					}
					notification_count = data_return.count.all;
					first_refresh = true;

					try {
						if(window.external.msSiteModeRefreshBadge()) {
							try {
								window.external.msSiteModeRefreshBadge();
							}
							catch (err) {
							}
						}
					}
					catch (err) { 
					}

					if($('#last_crawl').length > 0 && data_return.last_crawl) {
						$('#last_crawl').html(data_return.last_crawl);
						$('#last_crawl').find('.timeago').timeago();
					}
				}
			},
			type: 'POST',
			url: base_url + 'refresh/client'
		});
	}
}
function set_positions() {
	_window_height = $(window).height();

	_offset = $('.mdl-layout__content').offset();
	_height = _window_height - _offset.top;
	$('.mdl-layout__content').css({ 'height': _height});
	if(ci_controller == 'home' || ci_controller == 'member') {
		$('.mdl-grid').css({ 'padding-bottom': _height});
	}

	if($('aside').length > 0) {
		_offset = $('aside').offset();
		_height = _window_height - _offset.top;
		$('aside').css({ 'height': _height});
	}
}
function fullscreen() {
	if($('body').hasClass('fullscreen_body')) {
		$('body').removeClass('fullscreen_body');
	} else {
		$('body').addClass('fullscreen_body');
	}
	set_positions();
	if($('.item-selected').length > 0) {
		scroll_to('#' + $('.item-selected').attr('id'));
	}
}
function scroll_to(anchor) {
	$('.mdl-layout__content').scrollTo(anchor);
}

function registerContentHandler() {
	try {
		window.navigator.registerContentHandler('application/rss+xml', base_url + '?u=%s', title);
	} catch (e) {
		debug(e.message || e);
	}
}

$(document).ready(function() {
	if (!!window.navigator.registerContentHandler) {
		$('.registerContentHandler').css({'display': 'inline-block'});
		$('.registerContentHandler a').bind('click', function(event) {
			event.preventDefault();
			registerContentHandler();
		});
	}

	if(notify.isSupported) {
		if(notify.permissionLevel() != notify.PERMISSION_GRANTED && notify.permissionLevel() != notify.PERMISSION_DENIED) {
			$('.allow_notifications').css({'display': 'inline-block'});
			$('.allow_notifications a').bind('click', function(event) {
				notify.requestPermission(function() {
					$('.allow_notifications').hide();
				});
			});
		}
	}

	if(is_logged) {
		if(ci_controller != 'setup') {
			if(ci_controller != 'home') {
				refresh();
			}
			set_positions();
			setInterval(refresh, 10000*6*10);
		}
	}

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

	$(window).bind('resize', function(event) {
		set_positions();
	});

	$(document).on('click', 'a.priority', function(event) {
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
						ref.html('<i class="material-icons md-18">announcement</i>');
					}
					if(data_return.status == 'not_priority') {
						ref.html('<i class="material-icons md-18">chat_bubble_outline</i>');
					}
					refresh();
				}
			},
			type: 'POST',
			url: ref.attr('href')
		});
	});

	/*$(document).on('click', 'a.subscribe', function(event) {
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
					if(data_return.status == 'subscribe') {
						ref.html('<i class="material-icons md-18">bookmark</i>');
					}
					if(data_return.status == 'not_subscribe') {
						ref.html('<i class="material-icons md-18">bookmark_border</i>');
					}
					refresh();
				}
			},
			type: 'POST',
			url: ref.attr('href')
		});
	});*/

	$(document).on('click', 'a.follow', function(event) {
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
					if(data_return.status == 'follow') {
						ref.find('.unfollow').hide();
						ref.find('.follow').show();
					}
					if(data_return.status == 'unfollow') {
						ref.find('.follow').hide();
						ref.find('.unfollow').show();
					}
					refresh();
				}
			},
			type: 'POST',
			url: ref.attr('href')
		});
	});

	$('.fullscreen').bind('click', function(event) {
		fullscreen();
	});
});
