function build_preview() {
	var colors = [];
	$('.material_color').each(function(index) {
		var id = $(this).attr('id');
		var value = $(this).val();
		colors[id] = value;
	});
	var content = '';
	content += '<header class="mdl-layout__header mdl-color--' + colors['material-design/colors/background/header'] + '"><div class="mdl-layout__header-row">';
	content += '<div class="mdl-layout-spacer"></div>';
	content += '<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="#"><i class="material-icons md-24">android</i></a>';
	content += '</div></header>';

	content += '<main class="mdl-layout__content mdl-color--' + colors['material-design/colors/background/layout'] + '"><div class="mdl-grid">';

		content += '<div class="mdl-card mdl-color--' + colors['material-design/colors/background/card'] + ' mdl-cell mdl-cell--12-col">';
		content += '<div class="mdl-card__title mdl-color--' + colors['material-design/colors/background/card-title-highlight'] + ' mdl-color-text--' + colors['material-design/colors/text/card-title-highlight'] + '">';
		content += '<h1 class="mdl-card__title-text"><i class="material-icons md-18">android</i>Aliquam fermentum feugiat</h1>';
		content += '</div>';
		content += '</div>';

		content += '<div class="mdl-card mdl-color--' + colors['material-design/colors/background/card'] + ' mdl-cell mdl-cell--12-col">';
		content += '<div class="mdl-card__title mdl-color-text--' + colors['material-design/colors/text/card-title'] + '">';
		content += '<h1 class="mdl-card__title-text"><a class="mdl-color-text--' + colors['material-design/colors/text/link'] + '" href="#">Pharetra quis lectus</a>, molestie pretium tortor</h1>';
		content += '<div class="mdl-card__title-infos">';
		content += '<span class="mdl-navigation__link mdl-color-text--' + colors['material-design/colors/text/card-title'] + '"><i class="material-icons md-16">android</i>Fusce aliquam eleifend mattis</span>';
		content += '</div>';
		content += '</div>';

		content += '<div class="mdl-card__supporting-text mdl-color-text--' + colors['material-design/colors/text/content'] + '">';
		content += '<p>Nam condimentum tortor nisi, ut ornare nibh vehicula id. Fusce aliquam eleifend mattis. Ut laoreet, ligula sed sollicitudin ultrices, <a class="mdl-color-text--' + colors['material-design/colors/text/link'] + '" href="#">orci mi semper mi</a>, sit amet congue orci enim eu purus. Nulla id semper est. Ut ex nisi, vehicula sit amet ornare eget, pharetra quis lectus. Sed porta sapien mauris, bibendum suscipit diam commodo quis. Aliquam fermentum feugiat lorem, molestie pretium tortor. Lorem ipsum dolor sit amet, <a class="mdl-color-text--' + colors['material-design/colors/text/link'] + '" href="#">consectetur adipiscing elit</a>. Maecenas aliquet nisi ut arcu facilisis, id condimentum mi ultrices. Nunc dignissim lectus at tristique luctus.</p>';
		content += '<p>Cras sed auctor mauris. Quisque blandit lorem rhoncus erat facilisis, in posuere arcu suscipit. Suspendisse eu pellentesque nisl. Quisque vitae libero libero. Nam condimentum massa eu vulputate tincidunt. Sed eget dolor suscipit, bibendum mauris congue, eleifend enim. Integer in <a class="mdl-color-text--' + colors['material-design/colors/text/link'] + '" href="#">magna sit amet erat rutrum</a> cursus vel et mauris. Cras suscipit neque ante. Pellentesque sit amet dolor luctus, convallis justo sed, pretium felis.</p>';
		content += '<p><button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--' + colors['material-design/colors/background/button'] + ' mdl-color-text--' + colors['material-design/colors/text/button'] + '"><i class="material-icons md-24">android</i></button></p>';
		content += '</div>';

		content += '<div class="mdl-card__actions mdl-card--border mdl-color-text--' + colors['material-design/colors/text/card-actions'] + '">';
		content += '<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="#"><i class="material-icons md-18">android</i></a>';
		content += '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="preview_menu"><i class="material-icons md-18">more_vert</i></button>';
		content += '<ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--top-left mdl-color--' + colors['material-design/colors/background/menu'] + '" for="preview_menu">';
		content += '<li class="mdl-menu__item"><a class="mdl-color-text--' + colors['material-design/colors/text/link'] + '" href="#">Nunc dignissim lectus</a></li>';
		content += '<li class="mdl-menu__item"><a class="mdl-color-text--' + colors['material-design/colors/text/link'] + '" href="#">Sed porta sapien mauris</a></li>';
		content += '<li class="mdl-menu__item"><a class="mdl-color-text--' + colors['material-design/colors/text/link'] + '" href="#">Pellentesque sit amet</a></li>';
		content += '</ul>';
		content += '</div>';
		content += '</div>';

	content += '</div></div>';

	$('#preview').html(content);
	componentHandler.upgradeDom('MaterialMenu', 'mdl-menu');
}
$(document).ready(function() {
	build_preview();

	$('.material_color').bind('keyup', function(event) {
		build_preview();
	});

	$('#preview').on('click', 'a, .mdl-button', function(event) {
		event.preventDefault();
	});
});
