function refresh() {
	params = [];
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
				}
				window.document.title = '(' + data_return.count.all + ')';
			}
		},
		type: 'POST',
		url: base_url + 'refresh/client'
	});
}
function set_read(ref) {
	if(ref.hasClass('unread')) {
	} else if(ref.hasClass('read')) {
	} else {
		params = [];
		params.push({'name': csrf_token_name, 'value': $.cookie(csrf_cookie_name)});
		$.ajax({
			async: true,
			cache: true,
			data: params,
			dataType: 'json',
			statusCode: {
				200: function(data_return, textStatus, jqXHR) {
					if(data_return.status == 'read') {
						ref.addClass('read');
						ref.find('.history').find('i').removeClass('icon-ok');
						ref.find('.history').find('i').addClass('icon-remove');
						ref.find('.history').find('.read').hide();
						ref.find('.history').find('.unread').show();
					}
				}
			},
			type: 'POST',
			url: ref.find('.history').attr('href')
		});
	}
}
function load_items(url) {
	$('#items').html('<div class="ajax-loader"><img src="' + base_url + 'medias/ajax-loader.gif"></div>');
	params = [];
	params.push({'name': csrf_token_name, 'value': $.cookie(csrf_cookie_name)});
	$.ajax({
		async: true,
		cache: true,
		data: params,
		dataType: 'json',
		statusCode: {
			200: function(data_return, textStatus, jqXHR) {
				content = '';
				if(data_return.total > 0) {
					for(i in data_return.items) {
						itm = data_return.items[i];
						if($('#item_' + itm.itm_id).length == 0) {
							content += itm.itm_content;
						}
					}
				} else {
					content += data_return.noitems;
				}
				$('#items').html(content);
				$('.timeago').timeago();
			}
		},
		type: 'POST',
		url: url
	});
}
function add_items(url) {
	$('#items').append('<div class="ajax-loader"><img src="' + base_url + 'medias/ajax-loader.gif"></div>');
	params = [];
	params.push({'name': csrf_token_name, 'value': $.cookie(csrf_cookie_name)});
	$.ajax({
		async: true,
		cache: true,
		data: params,
		dataType: 'json',
		statusCode: {
			200: function(data_return, textStatus, jqXHR) {
				content = '';
				for(i in data_return.items) {
					itm = data_return.items[i];
					if($('#item_' + itm.itm_id).length == 0) {
						content += itm.itm_content;
					}
				}
				$('.ajax-loader').remove();
				$('#items').append(content);
				$('.timeago').timeago();
			}
		},
		type: 'POST',
		url: url
	});
}
function set_positions() {
	_position = $('#items').position();
	_height = $(window).height() - _position.top;
	$('#items').css({ 'height': _height});

	_position = $('.sidebar-nav').position();
	_height = $(window).height() - _position.top - 40;
	$('.sidebar-nav').css({ 'height': _height});
}
function display_alert(alert) {
	content = '<div class="alert alert-' + alert.type + '">';
	content += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
	content += alert.message;
	content += '</div>';
	$('.navbar-inner').find('.alert').remove();
	$('.navbar-inner').append(content);
}
function set_fullscreen_off() {
	$('.navbar').show();
	$('#sidebar').show();
	$('#content').addClass('span10');
	$('#content-toolbar .btn').removeClass('btn-mini');
	//$('.item .btn').removeClass('btn-mini');
	set_positions();
}
function set_fullscreen_on() {
	$('.navbar').hide();
	$('#sidebar').hide();
	$('#content').removeClass('span10');
	$('#content-toolbar .btn').addClass('btn-mini');
	//$('.item .btn').addClass('btn-mini');
	set_positions();
}
$(document).ready(function() {
	refresh();
	setInterval(refresh, 5000);

	_window_width = $(window).width();
	if(_window_width < 1024) {
		set_fullscreen_on();
		$('#full-screen').hide();
	}

	set_positions();

	load_items($('#load-all-items').attr('href'));

	$(window).bind('resize', function(event) {
		set_positions();
	});

	$('.detect-visible').live('inview', function(event, visible) {
		if(visible) {
			var itm_id = $(this).data('itm_id');
			var ref = $('#' + itm_id);
			if($('#items').scrollTop() > 0) {
				set_read(ref);
				last = $('#items').find('.item:last').attr('id');
				if(last == itm_id) {
					add_items( $('.sidebar-nav').find('li.active').find('a').attr('href') );
				}
			}
		}
    });

	$('.item-up').live('click', function(event) {
		event.preventDefault();
		var itm_id = $(this).data('itm_id');
		prev = $('#' + itm_id).prev().attr('id');
		if(prev) {
			location.hash = '#' + prev;
		}
	});

	$('.item-down').live('click', function(event) {
		event.preventDefault();
		var itm_id = $(this).data('itm_id');
		next = $('#' + itm_id).next().attr('id');
		if(next) {
			set_read($('#' + next));
			location.hash = '#' + next;
			last = $('#items').find('.item:last').attr('id');
			if(last == next) {
				add_items( $('.sidebar-nav').find('li.active').find('a').attr('href') );
			}
		}
	});

	$('.item').live('click', function(event) {
		var ref = $(this);
		set_read(ref);
    });

	$('.sidebar-nav a').live('click', function(event) {
		event.preventDefault();
		var ref = $(this);
		if(ref.attr('id') == 'load-starred-items') {
			$('#massive-read').hide();
			$('#filter-items').hide();
		} else {
			$('#massive-read').show();
			$('#filter-items').show();
		}
		$('.sidebar-nav li').removeClass('active');
		ref.parent().addClass('active');
		load_items(ref.attr('href'));
	});

	$('.item a.tag').live('click', function(event) {
		event.preventDefault();
		var ref = $(this);

		$('.sidebar-nav li').removeClass('active');

		$('#load-tag-' + ref.data('tag_id') + '-items').parent().addClass('active');
		load_items(ref.attr('href'));
	});

	$('.item a.from').live('click', function(event) {
		event.preventDefault();
		var ref = $(this);

		$('.sidebar-nav li').removeClass('active');

		$('.nav-list').find('.result').remove();
		content = '<li class="result active"><a id="load-sub-' + ref.data('sub_id') + '-items" href="' + base_url + 'home/items/sub/' + ref.data('sub_id') + '">' + ref.text() + ' (<span>0</span>)</a></li>';
		$('.nav-list').append(content);

		load_items(ref.attr('href'));
	});

	$('#full-screen').bind('click', function(event) {
		if($(this).hasClass('active')) {
			set_fullscreen_off();
		} else {
			set_fullscreen_on();
		}
	});

	$('#refresh-items').bind('click', function(event) {
		event.preventDefault();
		load_items( $('.sidebar-nav').find('li.active').find('a').attr('href') );
	});

	$('.sidebar-nav form').bind('submit', function(event) {
		event.preventDefault();
		var ref = $(this);
		params = [];
		params.push({'name': 'fed_title', 'value': $('#fed_title').val()});
		params.push({'name': csrf_token_name, 'value': $.cookie(csrf_cookie_name)});
		$.ajax({
			async: true,
			cache: true,
			data: params,
			dataType: 'json',
			statusCode: {
				200: function(data_return, textStatus, jqXHR) {
					if(data_return.subscriptions) {
						$('.nav-list').find('.result').remove();
						for(i in data_return.subscriptions) {
							sub = data_return.subscriptions[i];
							content = '<li class="result"><a id="load-sub-' + sub.sub_id + '-items" href="' + base_url + 'home/items/sub/' + sub.sub_id + '">' + sub.fed_title + ' (<span>0</span>)</a></li>';
							$('.nav-list').append(content);
						}
					}
				}
			},
			type: 'POST',
			url: ref.attr('action')
		});
	});

	$('.share-facebook').live('click', function(event) {
		event.preventDefault();
		var ref = $(this);
		fb_share(ref.attr('href'));
	});

	$('.star').live('click', function(event) {
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
					if(data_return.status == 'star') {
						ref.find('i').removeClass('icon-star-empty');
						ref.find('i').addClass('icon-star');
					}
					if(data_return.status == 'unstar') {
						ref.find('i').removeClass('icon-star');
						ref.find('i').addClass('icon-star-empty');
					}
				}
			},
			type: 'POST',
			url: ref.attr('href')
		});
	});

	$('.history').live('click', function(event) {
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
					if(data_return.alert) {
						display_alert(data_return.alert)
					}
					if(data_return.mode == 'massive-read') {
						load_items( $('.sidebar-nav').find('li.active').find('a').attr('href') );
					}
					if(data_return.mode == 'toggle') {
						if(data_return.status == 'read') {
							ref.find('i').removeClass('icon-ok');
							ref.find('i').addClass('icon-remove');
							ref.find('.read').hide();
							ref.find('.unread').show();
							$('#item_' + data_return.itm_id).removeClass('unread');
							$('#item_' + data_return.itm_id).addClass('read');
						}
						if(data_return.status == 'unread') {
							ref.find('i').removeClass('icon-remove');
							ref.find('i').addClass('icon-ok');
							ref.find('.unread').hide();
							ref.find('.read').show();
							$('#item_' + data_return.itm_id).removeClass('read');
							$('#item_' + data_return.itm_id).addClass('unread');
						}
					}
				}
			},
			type: 'POST',
			url: ref.attr('href')
		});
	});
});
