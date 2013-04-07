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
	debug(href);
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
$(document).ready(function() {
	if(is_logged) {
		refresh();
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
