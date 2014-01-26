var url = base_url + 'items/get/public_profile/' + mbr_nickname
var items_mode = 'read_and_unread';
if($.cookie('items_display') == 'collapse') {
	var items_display = 'collapse';
} else {
	var items_display = 'expand';
}
$.cookie('items_display', items_display, { expires: 30, path: '/' });
var pagination = 0;
var lock_add_items = false;
var lock_no_more_items = false;

function load_items(url) {
	url = url + '/?items_mode=' + items_mode + '&items_display=' + items_display;
	$('main > section').scrollTop(0);
	$('main section section').html('<div class="ajax-loader"><img src="' + base_url + 'medias/ajax-loader.gif"></div>');
	var params = [];
	params.push({'name': csrf_token_name, 'value': $.cookie(csrf_cookie_name)});
	pagination = 0;
	params.push({'name': 'pagination', 'value': pagination});
	$.ajax({
		async: true,
		cache: true,
		data: params,
		dataType: 'json',
		statusCode: {
			200: function(data_return, textStatus, jqXHR) {
				for(i in data_return.nav) {
					if(data_return.nav[i]) {
						$('.' + i).parent().removeClass('hide');
					} else {
						$('.' + i).parent().addClass('hide');
					}
				}
				var content = '';
				if(data_return.begin) {
					content += data_return.begin;
				}
				if(data_return.result_type == 'items') {
					if(data_return.total > 0) {
						lock_no_more_items = false;
						for(i in data_return.items) {
							var itm = data_return.items[i];
							if($('#item_' + itm.itm_id).length == 0) {
								content += itm.itm_content;
							}
						}
					}
				}
				$('main section section').html(content);
				if(data_return.result_type == 'items') {
					for(i in data_return.items) {
						itm = data_return.items[i];
						item_swipe('#item_' + itm.itm_id);
					}
					$('.timeago').timeago();
				}
			}
		},
		type: 'POST',
		url: url
	});
}
function add_items(url) {
	url = url + '/?items_mode=' + items_mode + '&items_display=' + items_display;
	if(!lock_add_items && !lock_no_more_items) {
		lock_add_items = true;
		lock_refresh = true;
		$('main section section').append('<div class="ajax-loader"><img src="' + base_url + 'medias/ajax-loader.gif"></div>');
		var params = [];
		params.push({'name': csrf_token_name, 'value': $.cookie(csrf_cookie_name)});
		pagination = pagination + 10;
		params.push({'name': 'pagination', 'value': pagination});
		$.ajax({
			async: true,
			cache: true,
			data: params,
			dataType: 'json',
			statusCode: {
				200: function(data_return, textStatus, jqXHR) {
					var content = '';
					if(data_return.total > 0) {
						for(i in data_return.items) {
							var itm = data_return.items[i];
							if($('#item_' + itm.itm_id).length == 0) {
								content += itm.itm_content;
							}
						}
					} else {
						lock_no_more_items = true;
					}
					if(data_return.end) {
						content += data_return.end;
					}
					$('.ajax-loader').remove();
					$('main section section').append(content);
					for(i in data_return.items) {
						itm = data_return.items[i];
						item_swipe('#item_' + itm.itm_id);
					}
					$('.timeago').timeago();
					lock_add_items = false;
					lock_refresh = false;
				}
			},
			type: 'POST',
			url: url
		});
	}
}
function item_swipe(selector) {
	/*$(selector).swipe('destroy');
	$(selector).swipe({
		swipeRight:function(event, direction, distance, duration, fingerCount) {
			if(direction == 'right' && distance > 120) {
				if($(selector).hasClass('collapse')) {
					item_expand($(selector).find('.expand'));
				} else {
					$(selector).find('.item-content').hide();
					$(selector).find('.item-content').html('');
					$(selector).find('.collapse').parent().hide();
					$(selector).find('.expand').parent().show();
					$(selector).addClass('collapse');
					scroll_to('#' + $(selector).attr('id'));
				}
			}
		}
	});*/
}
function item_up() {
	var itm_id = $('main > section .item-selected').attr('id');
	var prev = $('#' + itm_id).prev().attr('id');
	if(prev) {
		scroll_to('#' + prev);
	}
}
function item_down() {
	if($('main > section .item-selected').length == 0) {
		var itm_id = $('main section section').find('.item:first').attr('id');
		var next = $('#' + itm_id).attr('id');
		$('#' + itm_id).addClass('item-selected');
	} else {
		var itm_id = $('main > section .item-selected').attr('id');
		var next = $('#' + itm_id).next().attr('id');
	}
	if(next) {
		scroll_to('#' + next);
		if($('#' + next).hasClass('item')) {
			var last = $('main section section').find('.item:last').attr('id');
			if(last == next) {
				add_items(url);
			}
		}
	}
}
function item_expand(ref) {
	var params = [];
	params.push({'name': csrf_token_name, 'value': $.cookie(csrf_cookie_name)});
	$.ajax({
		async: true,
		cache: true,
		data: params,
		dataType: 'json',
		statusCode: {
			200: function(data_return, textStatus, jqXHR) {
				if(data_return.itm_content) {
					$('#item_' + data_return.itm_id).find('.expand').parent().hide();
					$('#item_' + data_return.itm_id).find('.collapse').parent().show();
					$('#item_' + data_return.itm_id).find('.item-content').html(data_return.itm_content);
					$('#item_' + data_return.itm_id).find('.item-content').show();
					$('#item_' + data_return.itm_id).removeClass('collapse');
					scroll_to('#item_' + data_return.itm_id);
				}
			}
		},
		type: 'POST',
		url: ref.attr('href')
	});
}
function item_collapse(ref) {
	ref.find('.item-content').hide();
	ref.find('.item-content').html('');
	ref.find('.collapse').parent().hide();
	ref.find('.expand').parent().show();
	ref.addClass('collapse');
	scroll_to('#' + ref.attr('id'));
}
function items_collapse() {
	var ref = $('.items_display');
	ref.find('.collapse').hide();
	ref.find('.expand').show();
	items_display = 'collapse';
	$.cookie('items_display', items_display, { expires: 30, path: '/' });
	load_items(url);
}
function items_expand() {
	var ref = $('.items_display');
	ref.find('.expand').hide();
	ref.find('.collapse').show();
	items_display = 'expand';
	$.cookie('items_display', items_display, { expires: 30, path: '/' });
	load_items(url);
}
$(document).ready(function() {
	set_positions();

	load_items(url);

	$(document).bind('keydown', function(event) {
		var keycode = event.which || event.keyCode;
		if($(event.target).parents('form').length == 0) {
			//shift + f
			if(event.shiftKey && keycode == 70) {
				event.preventDefault();
				fullscreen();

			//1
			} else if(keycode == 49) {
				event.preventDefault();
				var ref = $('.items_display');
				if(items_display == 'expand') {
					items_collapse();
				}

			//2
			} else if(keycode == 50) {
				event.preventDefault();
				var ref = $('.items_display');
				if(items_display == 'collapse') {
					items_expand();
				}

			//v
			} else if(keycode == 86) {
				var href = $('main > section .item-selected').find('h2').find('a').attr('href');
				var name = $('main > section .item-selected').attr('id');
				window.open(href, 'window_' + name);

			//o or enter
			} else if(keycode == 79 || keycode == 13) {
				if($('main > section .item-selected').length > 0) {
					ref = $('main > section .item-selected');
					if(ref.hasClass('collapse')) {
						item_expand(ref.find('.expand'));
					} else {
						item_collapse(ref);
					}
				}

			//k or p or shift + space
			} else if(keycode == 75 || keycode == 80 || (keycode == 32 && event.shiftKey)) {
				item_up();

			//j or n or space
			} else if(keycode == 74 || keycode == 78|| keycode == 32) {
				item_down();

			//r
			} else if(keycode == 82) {
				load_items(url);
			}
		}
	});

	$('main > section').bind('scroll', function(event) {
		$('main > section').find('.item').each(function(index) {
			var itm_id = $(this).attr('id');
			var ref = $('#' + itm_id);

			$('main > section .item-selected').removeClass('item-selected');
			ref.addClass('item-selected');

			var last = $('main > section section').find('.item:last').attr('id');
			if(last == itm_id) {
				add_items(url);
			}

			var offset = $(this).offset()
			if(offset.top + ref.height() - 60 < 0) {
				return true;
			} else {
				return false;
			}
		});
    });

	$('header').on('click', '#item_up', function(event) {
		event.preventDefault();
		item_up();
	});

	$('header').on('click', '#item_down', function(event) {
		event.preventDefault();
		item_down();
	});

	$('.items_refresh').bind('click', function(event) {
		event.preventDefault();
		load_items(url);
	});

	$('.items_display').bind('click', function(event) {
		event.preventDefault();
		var ref = $(this);
		if(items_display == 'collapse') {
			items_expand();

		} else if(items_display == 'expand') {
			items_collapse();
		}
	});

	$(document).on('click', '.item .expand', function(event) {
		event.preventDefault();
		var ref = $(this);
		item_expand($(this));
	});

	$(document).on('click', '.item .collapse', function(event) {
		event.preventDefault();
		var href = $(this).attr('href');
		item_collapse($(href));
	});

	$(document).on('click', '.link-item-share', function(event) {
		event.preventDefault();
		var ref = $(this).attr('href');
		$(this).parent().remove();
		$(ref).find('.item-share').removeClass('hide');
	});

	$(document).on('click', '.link-item-readability', function(event) {
		event.preventDefault();
		var ref = $(this);
		var params = [];
		params.push({'name': csrf_token_name, 'value': $.cookie(csrf_cookie_name)});
		$.ajax({
			async: true,
			cache: true,
			data: params,
			dataType: 'json',
			statusCode: {
				200: function(data_return, textStatus, jqXHR) {
					if(data_return.readability) {
						if(data_return.readability.content) {
							$('#item_' + data_return.itm_id).find('.item-content-result').html(data_return.readability.content);
							scroll_to('#item_' + data_return.itm_id);
						}
					}
				}
			},
			type: 'POST',
			url: ref.attr('href')
		});
	});

	$(document).on('click', '.link-item-like', function(event) {
		event.preventDefault();
		var ref = $(this).attr('href');
		$(this).parent().remove();
		var url = $(this).data('url');
		$.ajax({
			async: false,
			cache: true,
			dataType: 'json',
			statusCode: {
				200: function(data_return, textStatus, jqXHR) {
					debug(data_return);
					var content = '<p>';
					content += 'Delicious (' + data_return.Delicious + ') ';
					content += 'Facebook (' + data_return.Facebook.total_count + ') ';
					content += 'Google (' + data_return.GooglePlusOne + ') ';
					content += 'Reddit (' + data_return.Reddit + ') ';
					content += 'Twitter (' + data_return.Twitter + ') ';
					content += '</p>';
					$(ref).html(content);
					$(ref).show();
				}
			},
			type: 'GET',
			url: '//' + (location.protocol == 'https:' ? 'sharedcount.appspot' : 'api.sharedcount') + '.com/?url=' + url,
		});
	});
});
