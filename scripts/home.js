var no_more_items = false;
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
			url: ref.find('.history').attr('href') + '/auto'
		});
	}
}
function load_items(url) {
	$('#items').scrollTop(0);
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
					no_more_items = false;
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
	if(!lock_add_items && !no_more_items) {
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
					if(data_return.total > 0) {
						for(i in data_return.items) {
							itm = data_return.items[i];
							if($('#item_' + itm.itm_id).length == 0) {
								content += itm.itm_content;
							}
						}
					} else {
						no_more_items = true;
						content += data_return.noitems;
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
function item_up() {
	var itm_id = $('#items .item-selected').attr('id');
	prev = $('#' + itm_id).prev().attr('id');
	if(prev) {
		location.hash = '#' + prev;
	}
}
function item_down() {
	if($('#items .item-selected').length == 0) {
		var itm_id = $('#items-display').find('.item:first').attr('id');
		next = $('#' + itm_id).attr('id');
		$('#' + itm_id).addClass('item-selected');
	} else {
		var itm_id = $('#items .item-selected').attr('id');
		next = $('#' + itm_id).next().attr('id');
	}
	if(next) {
		set_read($('#' + next));
		location.hash = '#' + next;
		last = $('#items-display').find('.item:last').attr('id');
		if(last == next) {
			add_items( $('.menu').find('li.active').find('a').attr('href') );
		}
	}
}
function item_star(ref) {
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
}
function item_history(ref) {
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

			//m
			} else if(keycode == 77) {
				if($('#items .item-selected').length > 0) {
					item_history($('#items .item-selected').find('.history'));
				}

			//s
			} else if(keycode == 83) {
				if($('#items .item-selected').length > 0) {
					item_star($('#items .item-selected').find('.star'));
				}

			//o or enter
			} else if(keycode == 79 || keycode == 13) {
				if($('#items .item-selected').length > 0) {
					ref = $('#items .item-selected').find('.item-content');
					if(ref.is(':visible')) {
						ref.hide();
					} else {
						ref.show();
					}
				}

			} else if(keycode == 65) {
				//shift + a
				if(event.shiftKey) {
					modal_show($('#read_all').attr('href'));
				//a
				} else {
					modal_show($('#add_subscribe').attr('href'));
				}

			//k or p or shift + space
			} else if(keycode == 75 || keycode == 80 || (keycode == 32 && event.shiftKey)) {
				item_up();

			//j or n or space
			} else if(keycode == 74 || keycode == 78|| keycode == 32) {
				item_down();

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
		$('#items').find('.item').each(function(index) {
			var itm_id = $(this).attr('id');
			var ref = $('#' + itm_id);

			$('#items .item-selected').removeClass('item-selected');
			ref.addClass('item-selected');

			last = $('#items-display').find('.item:last').attr('id');
			if(last == itm_id) {
				add_items( $('.menu').find('li.active').find('a').attr('href') );
			}

			//$('#sidebar input').val(itm_id);
			offset = $(this).offset()
			if(offset.top + ref.height() - 50 < 0) {
				set_read(ref);
				return true;
			} else {
				return false;
			}
		});
    });

	$('#item-up').live('click', function(event) {
		event.preventDefault();
		item_up();
	});

	$('#item-down').live('click', function(event) {
		event.preventDefault();
		item_down();
	});

	$('.item h2 a, .item-content a').live('click', function(event) {
		var ref = $(this).parents('.item');
		set_read(ref);
    });

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
		item_star($(this));
	});

	$('.history').live('click', function(event) {
		event.preventDefault();
		var ref = $(this);
		item_history($(this));
	});
});
