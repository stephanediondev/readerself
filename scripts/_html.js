var result_subscriptions = [];
var lock_refresh = false;

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
					window.document.title = '(' + data_return.count.all + ')';
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

	_offset = $('main > section').offset();
	_height = _window_height - _offset.top;
	$('main > section').css({ 'height': _height});
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
		$('header nav:first-child ul:last-child').show();
	} else {
		$('aside').show();
		$('header nav:first-child ul:last-child').hide();
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
	//location.hash = anchor;
	$('main > section').scrollTo(anchor);
}

$(document).ready(function() {
	if($('aside').length == 0) {
		$('#toggle-sidebar').parent().remove();
	}
	if(is_logged) {
		refresh();
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
	$('#overlay').live('click', function(event) {
		event.preventDefault();
		modal_hide();
	});
	$('.modal_hide').live('click', function(event) {
		event.preventDefault();
		modal_hide();
	});
	$('.modal_show').live('click', function(event) {
		event.preventDefault();
		href = $(this).attr('href');
		modal_show(href);
	});

	$('#modal form').live('submit', function(event) {
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
					if(data_return.modal) {
						$('#modal').html(data_return.modal);
					}
				}
			},
			type: 'POST',
			url: ref.attr('action')
		});
	});

	$('a.priority').live('click', function(event) {
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
					refresh();
				}
			},
			type: 'POST',
			url: ref.attr('href')
		});
	});

	if($('aside').length > 0) {
		$(document).swipe({
			swipeLeft:function(event, direction, distance, duration, fingerCount) {
				if($('#toggle-sidebar').is(':visible')) {
					toggle_sidebar();
				}
			},
			threshold: 120
		});
	}

	$('.fullscreen').bind('click', function(event) {
		fullscreen();
	});
});
