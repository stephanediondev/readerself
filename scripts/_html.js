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
function modal_hide() {
	$('#overlay').fadeOut('slow');
	$('#modal').fadeOut('slow', function() {
		$('#modal').html('');
	});
}
function modal_show(href) {
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
					$('#modal').html(data_return.modal);

					set_positions_modal();

					if($('#overlay').is(':visible')) {
					} else {
						document_height = $(document).height();
						document_top = $(document).scrollTop();
						$('#overlay').css({'height': document_height, 'width': window_width});
						$('#overlay').fadeIn(800);
					}

					if($('#modal').is(':visible')) {
					} else {
						$('#modal').fadeIn(1200);
					}
				}
			}
		},
		type: 'POST',
		url: href
	});
}
function set_positions_modal() {
	document_height = $(document).height();
	document_top = $(document).scrollTop();
	window_width = $(window).width();
	window_height = $(window).height();

	if($('#overlay').is(':visible')) {
		$('#overlay').css({'height': document_height, 'width': window_width});
	}

	width = $('#modal').width();
	height = $('#modal').height();
	_top = document_top + (window_height / 2) - (height / 2);
	if(_top < 0) {
		_top = 10;
	}
	margin_left = (window_width - width) / 2;
	if(margin_left < 0) {
		margin_left = 10;
	}
	$('#modal').css({'margin-left': margin_left, 'top': _top});
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
						if(window.external.msIsSiteMode()) {
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
		$('main section section').css({ 'padding-bottom': _height});
	}

	if($('aside').length > 0) {
		_offset = $('aside').offset();
		_height = _window_height - _offset.top;
		$('aside').css({ 'height': _height});
	}
}
function toggle_sidebar() {
	if($('aside').is(':visible')) {
		$('aside').hide();
	} else {
		$('aside').show();
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

$(document).ready(function() {
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

	if($('aside').length == 0) {
		$('#toggle-sidebar').parent().remove();
	}
	if(is_logged) {
		if(ci_controller != 'home') {
			refresh();
		}
		set_positions();
		setInterval(refresh, 10000*6*10);
	}

	set_positions_modal();

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

	$(window).bind('resize scroll', function(event) {
		set_positions_modal();
	});

	$(window).bind('resize', function(event) {
		set_positions();
	});

	$('#toggle-sidebar').bind('click', function(event) {
		event.preventDefault();
		toggle_sidebar();
	});

	$('body').append('<div id="overlay"></div>');
	$('body').append('<div id="modal"></div>');

	$(document).bind('keydown', function(event) {
		var keycode = event.which || event.keyCode;
		//esc
		if(keycode == 27) {
			modal_hide();
		}
	});
	$(document).on('click', '#overlay', function(event) {
		event.preventDefault();
		modal_hide();
	});
	$(document).on('click', '.modal_hide', function(event) {
		event.preventDefault();
		modal_hide();
	});
	$(document).on('click', '.modal_show', function(event) {
		event.preventDefault();
		href = $(this).attr('href');
		modal_show(href);
	});

	$(document).on('submit', '#modal form', function(event) {
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
					if(data_return.notification && notify.permissionLevel() == notify.PERMISSION_GRANTED) {
						create_notification(data_return.notification);
						modal_hide();
					} else if(data_return.modal) {
						$('#modal').html(data_return.modal);
					}
				}
			},
			type: 'POST',
			url: ref.attr('action')
		});
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
						ref.find('.not_priority').hide();
						ref.find('.priority').show();
						$('aside ul').find('#load-feed-' + data_return.fed_id + '-items').find('i').removeClass('icon-rss')
						$('aside ul').find('#load-feed-' + data_return.fed_id + '-items').find('i').addClass('icon-flag')
					}
					if(data_return.status == 'not_priority') {
						ref.find('.priority').hide();
						ref.find('.not_priority').show();
						$('aside ul').find('#load-feed-' + data_return.fed_id + '-items').find('i').removeClass('icon-flag')
						$('aside ul').find('#load-feed-' + data_return.fed_id + '-items').find('i').addClass('icon-rss')
					}
					refresh();
				}
			},
			type: 'POST',
			url: ref.attr('href')
		});
	});

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
