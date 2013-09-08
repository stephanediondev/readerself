var mode_items = 'unread_only';
var pagination = 0;
var no_more_items = false;
var lock_add_items = false;
var g_key = false;

function set_read(ref) {
	if(ref.hasClass('unread')) {
	} else if(ref.hasClass('read')) {
	} else {
		ref.addClass('read');
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
	url = url + '/?mode-items=' + mode_items;
	$('main > section').scrollTop(0);
	$('main section section').html('<div class="ajax-loader"><img src="' + base_url + 'medias/ajax-loader.gif"></div>');
	params = [];
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
				content = '';
				if(data_return.begin) {
					content += data_return.begin;
				}
				if(data_return.total > 0) {
					no_more_items = false;
					for(i in data_return.items) {
						itm = data_return.items[i];
						if($('#item_' + itm.itm_id).length == 0) {
							content += itm.itm_content;
						}
					}
				}
				if(data_return.end) {
					content += data_return.end;
				}
				$('main section section').html(content);
				if($('#display-items').find('.expand').is(':visible')) {
					$('.item').addClass('collapse');
				}
				$('.timeago').timeago();
				refresh();
			}
		},
		type: 'POST',
		url: url
	});
}
function add_items(url) {
	if($('#search_items').val() != '') {
		url = $('aside ul #search_items_form').attr('action') + '/' + encodeURI( $('#search_items').val() ) ;
	}
	url = url + '/?mode-items=' + mode_items;
	if(!lock_add_items && !no_more_items) {
		lock_add_items = true;
		lock_refresh = true;
		$('main section section').append('<div class="ajax-loader"><img src="' + base_url + 'medias/ajax-loader.gif"></div>');
		params = [];
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
					}
					if(data_return.end) {
						content += data_return.end;
					}
					$('.ajax-loader').remove();
					$('main section section').append(content);
					if($('#display-items').find('.expand').is(':visible')) {
						$('.item').addClass('collapse');
					}
					$('.timeago').timeago();
					lock_add_items = false;
					lock_refresh = false;
					refresh();
				}
			},
			type: 'POST',
			url: url
		});
	}
}
function item_up() {
	var itm_id = $('main > section .item-selected').attr('id');
	prev = $('#' + itm_id).prev().attr('id');
	if(prev) {
		location.hash = '#' + prev;
	}
}
function item_down() {
	if($('main > section .item-selected').length == 0) {
		var itm_id = $('main section section').find('.item:first').attr('id');
		next = $('#' + itm_id).attr('id');
		$('#' + itm_id).addClass('item-selected');
	} else {
		var itm_id = $('main > section .item-selected').attr('id');
		next = $('#' + itm_id).next().attr('id');
	}
	if(next) {
		location.hash = '#' + next;
		if($('#' + next).hasClass('item')) {
			set_read($('#' + next));
			last = $('main section section').find('.item:last').attr('id');
			if(last == next) {
				add_items( $('aside ul').find('li.active').find('a').attr('href') );
			}
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
function item_share(ref) {
	params = [];
	params.push({'name': csrf_token_name, 'value': $.cookie(csrf_cookie_name)});
	$.ajax({
		async: true,
		cache: true,
		data: params,
		dataType: 'json',
		statusCode: {
			200: function(data_return, textStatus, jqXHR) {
				if(data_return.status == 'share') {
					ref.find('.share').hide();
					ref.find('.unshare').show();
				}
				if(data_return.status == 'unshare') {
					ref.find('.unshare').hide();
					ref.find('.share').show();
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
function collapse() {
	ref = $('#display-items');
	ref.find('.collapse').hide();
	ref.find('.expand').show();
	$('.item').addClass('collapse');
}
function expand() {
	ref = $('#display-items');
	ref.find('.expand').hide();
	ref.find('.collapse').show();
	$('.item').removeClass('collapse');
}
$(document).ready(function() {
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
				$('aside ul li').removeClass('active');
				$('#load-all-items').parent().addClass('active');
				load_items( $('aside ul').find('li.active').find('a').attr('href') );

			//g then shift + s
			} else if(g_key && event.shiftKey && keycode == 83) {
				$('aside ul li').removeClass('active');
				$('#load-shared-items').parent().addClass('active');
				load_items( $('aside ul').find('li.active').find('a').attr('href') );

			//g then s
			} else if(g_key && keycode == 83) {
				$('aside ul li').removeClass('active');
				$('#load-starred-items').parent().addClass('active');
				load_items( $('aside ul').find('li.active').find('a').attr('href') );

			//shift + 1
			} else if(event.shiftKey && keycode == 49) {
				event.preventDefault();
				if($('.mode-items').find('.read_and_unread').is(':visible')) {
					$('.mode-items').find('.read_and_unread').hide();
					$('.mode-items').find('.unread_only').show();
					mode_items = 'read_and_unread';
					load_items( $('aside ul').find('li.active').find('a').attr('href') );
				}

			//shift + 2
			} else if(event.shiftKey && keycode == 50) {
				event.preventDefault();
				if($('.mode-items').find('.unread_only').is(':visible')) {
					$('.mode-items').find('.unread_only').hide();
					$('.mode-items').find('.read_and_unread').show();
					mode_items = 'unread_only';
					load_items( $('aside ul').find('li.active').find('a').attr('href') );
				}

			//1
			} else if(keycode == 49) {
				event.preventDefault();
				ref = $('#display-items');
				if(ref.find('.collapse').is(':visible')) {
					collapse();
				}

			//2
			} else if(keycode == 50) {
				event.preventDefault();
				ref = $('#display-items');
				if(ref.find('.expand').is(':visible')) {
					expand();
				}

			//v
			} else if(keycode == 86) {
				href = $('main > section .item-selected').find('h2').find('a').attr('href');
				name = $('main > section .item-selected').attr('id');
				window.open(href, 'window_' + name);

			//m
			} else if(keycode == 77) {
				if($('main > section .item-selected').length > 0) {
					item_history($('main > section .item-selected').find('.history'));
				}

			//shift + s
			} else if(event.shiftKey && keycode == 83) {
				if($('main > section .item-selected').length > 0) {
					item_share($('main > section .item-selected').find('.share'));
				}

			//s
			} else if(keycode == 83) {
				if($('main > section .item-selected').length > 0) {
					item_star($('main > section .item-selected').find('.star'));
				}

			//h or ?
			} else if(keycode == 72 || keycode == 188) {
				modal_show($('#link_shortcuts').attr('href'));

			//o or enter
			} else if(keycode == 79 || keycode == 13) {
				if($('main > section .item-selected').length > 0) {
					ref = $('main > section .item-selected');
					if(ref.hasClass('collapse')) {
						ref.removeClass('collapse');
					} else {
						ref.addClass('collapse');
					}
				}

			} else if(keycode == 65) {
				//shift + a
				if(event.shiftKey) {
					modal_show($('#read_all').attr('href'));
				//a
				} else {
					window.location.href = base_url + 'subscriptions/create';
				}

			//k or p or shift + space
			} else if(keycode == 75 || keycode == 80 || (keycode == 32 && event.shiftKey)) {
				item_up();

			//j or n or space
			} else if(keycode == 74 || keycode == 78|| keycode == 32) {
				item_down();

			//r
			} else if(keycode == 82) {
				load_items( $('aside ul').find('li.active').find('a').attr('href') );
			}
		}
	});

	$('main > section').bind('scroll', function(event) {
		$('main > section').find('.item').each(function(index) {
			var itm_id = $(this).attr('id');
			var ref = $('#' + itm_id);

			$('main > section .item-selected').removeClass('item-selected');
			ref.addClass('item-selected');

			last = $('main > section section').find('.item:last').attr('id');
			if(last == itm_id) {
				add_items( $('aside ul').find('li.active').find('a').attr('href') );
			}

			//$('aside input').val(itm_id);
			offset = $(this).offset()
			if(offset.top + ref.height() - 60 < 0) {
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

	$('aside ul a').live('click', function(event) {
		event.preventDefault();
		$('#search_items').val('');
		var ref = $(this);
		$('aside ul li').removeClass('active');
		ref.parent().addClass('active');
		load_items(ref.attr('href'));
		if($('aside').css('position') == 'absolute') {
			$('aside').hide();
		}
	});

	$('.item a.folder').live('click', function(event) {
		event.preventDefault();
		$('#search_items').val('');
		var ref = $(this);

		$('aside ul li').removeClass('active');

		$('#load-folder-' + ref.data('flr_id') + '-items').parent().addClass('active');
		load_items(ref.attr('href'));
	});

	$('.item a.author').live('click', function(event) {
		event.preventDefault();
		$('#search_items').val('');
		var ref = $(this);

		$('aside ul li').removeClass('active');

		$('aside ul').find('.result').remove();
		content = '<li class="result active"><a id="load-author-' + ref.data('itm_id') + '-items" href="' + base_url + 'home/items/author/' + ref.data('itm_id') + '"><i class="icon icon-user"></i>' + ref.text() + '</a></li>';
		$('aside ul').append(content);

		load_items(ref.attr('href'));
	});

	$('.item a.from').live('click', function(event) {
		event.preventDefault();
		$('#search_items').val('');
		var ref = $(this);

		$('aside ul li').removeClass('active');

		$('aside ul').find('.result').remove();
		content = '<li class="result active"><a id="load-sub-' + ref.data('sub_id') + '-items" href="' + base_url + 'home/items/subscription/' + ref.data('sub_id') + '"><i class="icon icon-rss"></i>' + ref.text() + ' (<span>0</span>)</a></li>';
		$('aside ul').append(content);

		load_items(ref.attr('href'));
	});

	$('.item a.category').live('click', function(event) {
		event.preventDefault();
		$('#search_items').val('');
		var ref = $(this);

		$('aside ul li').removeClass('active');

		$('aside ul').find('.result').remove();
		content = '<li class="result active"><a id="load-category-' + ref.data('dat_id') + '-items" href="' + base_url + 'home/items/category/' + ref.data('cat_id') + '"><i class="icon icon-tag"></i>' + ref.text() + '</a></li>';
		$('aside ul').append(content);

		load_items(ref.attr('href'));
	});

	$('#refresh-items').bind('click', function(event) {
		event.preventDefault();
		load_items( $('aside ul').find('li.active').find('a').attr('href') );
	});

	$('.mode-items').bind('click', function(event) {
		event.preventDefault();
		if($('.mode-items').find('.unread_only').is(':visible')) {
			$('.mode-items').find('.unread_only').hide();
			$('.mode-items').find('.read_and_unread').show();
			mode_items = 'unread_only';

		} else if($('.mode-items').find('.read_and_unread').is(':visible')) {
			$('.mode-items').find('.read_and_unread').hide();
			$('.mode-items').find('.unread_only').show();
			mode_items = 'read_and_unread';
		}
		load_items( $('aside ul').find('li.active').find('a').attr('href') );
	});

	$('#display-items').bind('click', function(event) {
		event.preventDefault();
		ref = $(this);
		if(ref.find('.expand').is(':visible')) {
			expand();

		} else if(ref.find('.collapse').is(':visible')) {
			collapse();
		}
	});

	$('aside ul #search_items_form').bind('submit', function(event) {
		event.preventDefault();
		if($('#search_items').val() != '') {
			var ref = $(this);
			$('#search_items').blur();
			load_items( ref.attr('action') + '/' + encodeURIComponent( $('#search_items').val() ) );
			if($('aside').css('position') == 'absolute') {
				$('aside').hide();
			}
		}
	});

	$('aside ul #search_subscriptions_form').bind('submit', function(event) {
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
						$('aside ul').find('.result').remove();
						for(i in data_return.subscriptions) {
							sub = data_return.subscriptions[i];
							if(sub.sub_title) {
								title = sub.sub_title;
							} else {
								title = sub.fed_title;
							}
							content = '<li class="result"><a id="load-sub-' + sub.sub_id + '-items" href="' + base_url + 'home/items/subscription/' + sub.sub_id + '"><i class="icon icon-rss"></i>' + title + ' (<span>0</span>)</a></li>';
							$('aside ul').append(content);
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

	$('.share').live('click', function(event) {
		event.preventDefault();
		item_share($(this));
	});

	$('.history').live('click', function(event) {
		event.preventDefault();
		var ref = $(this);
		item_history($(this));
	});

	$('.link-item-share').live('click', function(event) {
		event.preventDefault();
		ref = $(this).attr('href');
		$(this).parent().remove();
		$(ref).find('.item-share').css({'display': 'inline-block'});
	});

	$('.link-item-like').live('click', function(event) {
		event.preventDefault();
		ref = $(this).attr('href');
		$(this).parent().remove();
		url = $(this).data('url');
		var content = '<iframe style="width:110px;height:21px;" allowTransparency="true" frameborder="0" scrolling="no" src="https://www.facebook.com/plugins/like.php?href=' + url + '&amp;colorscheme=light&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;send=false"></iframe>';
		content += '<iframe style="width:110px;height:21px;" allowtransparency="true" frameborder="0" scrolling="no" src="https://plusone.google.com/_/+1/fastbutton?bsv&amp;size=medium&amp;url=' + url + '>"></iframe>';
		content += '<iframe style="width:110px;height:21px;" allowtransparency="true" frameborder="0" scrolling="no" src="https://platform.twitter.com/widgets/tweet_button.html?url=' + url + '"></iframe>';
		$(ref).html(content);
		$(ref).show();
	});
});
