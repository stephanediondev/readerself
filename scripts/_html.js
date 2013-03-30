function debug(data) {
	if(window.console && console.debug) {
		console.debug(data);
	} else if(window.console && console.log) {
		console.log(data);
	}
}
function overlay_hide() {
	$('#overlay').fadeOut('slow');
}
function overlay_show() {
	window_width = $(window).width();
	window_height = $(window).height();
	$('#overlay').css({'height': window_height, 'width': window_width});
	$('#overlay').css({'opacity': 0.7});
	$('#overlay').fadeIn(800);
}
function modal_hide() {
	overlay_hide();
	$('#modal').fadeOut('slow', function() {
		$('#modal').html('');
	});
}
function modal_show(href) {
	debug(href);
	if($('#overlay').is(':visible')) {
	} else {
		overlay_show();
	}
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

					if($('#modal').is(':visible')) {
					} else {
						$('#modal').fadeIn(1200);
					}
				}
			},
			404: function(jqXHR, textStatus, errorThrown) {
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
	/*_top = $('#modal').data('top');
	if(_top) {
	} else {
		_top = document_top + (window_height / 2) - (height / 2);
	}
	if(_top < 0) {
		_top = 10;
	}*/
	_top = 10;
	margin_left = (window_width - width) / 2;
	if(margin_left < 0) {
		margin_left = 10;
	}
	if($('#modal').is(':visible')) {
		$('#modal').css({'margin-left': margin_left, 'top': _top});
	}
}
$(document).ready(function() {
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

	$('body').append('<div id="overlay"></div>');
	$('body').append('<div id="modal"></div>');

	$(document).bind('keydown', function(event) {
		if(event == null) {
			keycode = event.keyCode;
		} else {
			keycode = event.which;
		}
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
});
