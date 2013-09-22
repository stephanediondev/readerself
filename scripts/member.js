var items_mode = 'read_and_unread';
var items_display = 'expand';
var pagination = 0;
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
				refresh();
			}
		},
		type: 'POST',
		url: url
	});
}
function item_swipe(selector) {
	$(selector).swipe('destroy');
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
					location.hash = '#' + $(selector).attr('id');
				}
			}
		}
	});
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
					location.hash = '#item_' + data_return.itm_id;
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
	location.hash = '#' + ref.attr('id');
}
function items_collapse() {
	var ref = $('.items_display');
	ref.find('.collapse').hide();
	ref.find('.expand').show();
	items_display = 'collapse';
	$.cookie('items_display', items_display, { expires: 30, path: '/' });
	load_items( $('aside ul').find('li.active').find('a').attr('href') );
}
function items_expand() {
	var ref = $('.items_display');
	ref.find('.expand').hide();
	ref.find('.collapse').show();
	items_display = 'expand';
	$.cookie('items_display', items_display, { expires: 30, path: '/' });
	load_items( $('aside ul').find('li.active').find('a').attr('href') );
}
$(document).ready(function() {
	load_items(base_url + 'items/get/member/' + mbr_nickname);

	$('.item .expand').live('click', function(event) {
		event.preventDefault();
		var ref = $(this);
		item_expand($(this));
	});

	$('.item .collapse').live('click', function(event) {
		event.preventDefault();
		var href = $(this).attr('href');
		item_collapse($(href));
	});

	$('.link-item-readability').live('click', function(event) {
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
							location.hash = '#item_' + data_return.itm_id;
						}
					}
				}
			},
			type: 'POST',
			url: ref.attr('href')
		});
	});

	$('.link-item-share').live('click', function(event) {
		event.preventDefault();
		var ref = $(this).attr('href');
		$(this).parent().remove();
		$(ref).find('.item-share').removeClass('hide');
	});

	$('.link-item-like').live('click', function(event) {
		event.preventDefault();
		var ref = $(this).attr('href');
		$(this).parent().remove();
		var url = $(this).data('url');
		var content = '<iframe style="width:110px;height:21px;" allowTransparency="true" frameborder="0" scrolling="no" src="https://www.facebook.com/plugins/like.php?href=' + url + '&amp;colorscheme=light&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;send=false"></iframe>';
		content += '<iframe style="width:110px;height:21px;" allowtransparency="true" frameborder="0" scrolling="no" src="https://plusone.google.com/_/+1/fastbutton?bsv&amp;size=medium&amp;url=' + url + '"></iframe>';
		content += '<iframe style="width:110px;height:21px;" allowtransparency="true" frameborder="0" scrolling="no" src="https://platform.twitter.com/widgets/tweet_button.html?url=' + url + '"></iframe>';
		$(ref).html(content);
		$(ref).show();
	});
});
