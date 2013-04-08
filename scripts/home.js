var lock_add_items = false;
var g_key = false;

function set_read(ref) {
	if(ref.hasClass('unread')) {
	} else if(ref.hasClass('read')) {
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
						ref.find('.history').find('.read').hide();
						ref.find('.history').find('.unread').show();
						refresh();
					}
				}
			},
			type: 'POST',
			url: ref.find('.history').attr('href')
		});
	}
}
function load_items(url) {
	$('#items-display').html('<div class="ajax-loader"><img src="' + base_url + 'medias/ajax-loader.gif"></div>');
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
				$('#items-display').html(content);
				$('.timeago').timeago();
				refresh();
			}
		},
		type: 'POST',
		url: url
	});
}
function add_items(url) {
	if(!lock_add_items) {
		lock_add_items = true;
		$('#items-display').append('<div class="ajax-loader"><img src="' + base_url + 'medias/ajax-loader.gif"></div>');
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
					$('#items-display').append(content);
					$('.timeago').timeago();
					lock_add_items = false;
					refresh();
				}
			},
			type: 'POST',
			url: url
		});
	}
}
function set_positions() {
	_window_height = $(window).height();

	_offset = $('#items').offset();
	_height = _window_height - _offset.top;
	$('#items').css({ 'height': _height});
	$('#items-display').css({ 'padding-bottom': _height});

	_offset = $('#sidebar').offset();
	_height = _window_height - _offset.top;
	$('#sidebar').css({ 'height': _height});
}
function display_alert(alert) {
	content = '<div class="alert alert-' + alert.type + '">';
	content += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
	content += alert.message;
	content += '</div>';
	$('.navbar-inner').find('.alert').remove();
	$('.navbar-inner').append(content);
}
$(document).ready(function() {
	set_positions();

	load_items($('#load-all-items').attr('href'));

	$(document).bind('keyup', function(event) {
		var keycode = event.which || event.keyCode;
		if($(event.target).parents('form').length == 0) {
			//g
			if(keycode == 71) {
				g_key = true;
			} else {
				g_key = false;
			}
		}
	});

	$(document).bind('keydown', function(event) {
		var keycode = event.which || event.keyCode;
		if($(event.target).parents('form').length == 0) {
			//g then a
			if(g_key && keycode == 65) {
				$('.menu li').removeClass('active');
				$('#load-all-items').parent().addClass('active');
				load_items( $('.menu').find('li.active').find('a').attr('href') );

			//g then s
			} else if(g_key && keycode == 83) {
				$('.menu li').removeClass('active');
				$('#load-starred-items').parent().addClass('active');
				load_items( $('.menu').find('li.active').find('a').attr('href') );

			} else if(keycode == 65) {
				//shift + a
				if(event.shiftKey) {
					modal_show($('#read_all').attr('href'));
				//a
				} else {
					modal_show($('#add_subscribe').attr('href'));
				}

			//r
			} else if(keycode == 82) {
				load_items( $('.menu').find('li.active').find('a').attr('href') );
			}
		}
	});

	$(window).bind('resize', function(event) {
		set_positions();
	});

	$('#items').bind('scroll', function(event) {
		$('#items').find('.item').not('.read').each(function(index) {
			var itm_id = $(this).attr('id');
			var ref = $('#' + itm_id);
			offset = $(this).offset()
			if(offset.top + ref.height() - 40 < 0) {
				set_read(ref);
				last = $('#items-display').find('.item:last').attr('id');
				if(last == itm_id) {
					add_items( $('.menu').find('li.active').find('a').attr('href') );
				}
				return true;
			} else {
				return false;
			}
		});
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
			last = $('#items-display').find('.item:last').attr('id');
			if(last == next) {
				add_items( $('.menu').find('li.active').find('a').attr('href') );
			}
		}
	});

	/*$('.item').live('click', function(event) {
		var ref = $(this);
		set_read(ref);
    });*/

	$('.menu a').live('click', function(event) {
		event.preventDefault();
		var ref = $(this);
		if(ref.attr('id') == 'load-starred-items') {
			$('#massive-read').hide();
		} else {
			$('#massive-read').show();
		}
		$('.menu li').removeClass('active');
		ref.parent().addClass('active');
		load_items(ref.attr('href'));
	});

	$('.item a.tag').live('click', function(event) {
		event.preventDefault();
		var ref = $(this);

		$('.menu li').removeClass('active');

		$('#load-tag-' + ref.data('tag_id') + '-items').parent().addClass('active');
		load_items(ref.attr('href'));
	});

	$('.item a.from').live('click', function(event) {
		event.preventDefault();
		var ref = $(this);

		$('.menu li').removeClass('active');

		$('#sidebar .menu').find('.result').remove();
		content = '<li class="result active"><a id="load-sub-' + ref.data('sub_id') + '-items" href="' + base_url + 'home/items/sub/' + ref.data('sub_id') + '"><i class="icon icon-rss"></i>' + ref.text() + ' (<span>0</span>)</a></li>';
		$('#sidebar .menu').append(content);

		load_items(ref.attr('href'));
	});

	$('#refresh-items').bind('click', function(event) {
		event.preventDefault();
		load_items( $('.menu').find('li.active').find('a').attr('href') );
	});

	$('.menu form').bind('submit', function(event) {
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
						$('#sidebar .menu').find('.result').remove();
						for(i in data_return.subscriptions) {
							sub = data_return.subscriptions[i];
							content = '<li class="result"><a id="load-sub-' + sub.sub_id + '-items" href="' + base_url + 'home/items/sub/' + sub.sub_id + '"><i class="icon icon-rss"></i>' + sub.fed_title + ' (<span>0</span>)</a></li>';
							$('#sidebar .menu').append(content);
							refresh();
						}
					}
				}
			},
			type: 'POST',
			url: ref.attr('action')
		});
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
						ref.find('.star').hide();
						ref.find('.unstar').show();
					}
					if(data_return.status == 'unstar') {
						ref.find('.unstar').hide();
						ref.find('.star').show();
					}
					refresh();
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
					if(data_return.mode == 'toggle') {
						if(data_return.status == 'read') {
							ref.find('.read').hide();
							ref.find('.unread').show();
							$('#item_' + data_return.itm_id).removeClass('unread');
							$('#item_' + data_return.itm_id).addClass('read');
						}
						if(data_return.status == 'unread') {
							ref.find('.unread').hide();
							ref.find('.read').show();
							$('#item_' + data_return.itm_id).removeClass('read');
							$('#item_' + data_return.itm_id).addClass('unread');
						}
					}
					refresh();
				}
			},
			type: 'POST',
			url: ref.attr('href')
		});
	});
});
