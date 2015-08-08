<!DOCTYPE html>
<html>
<head>
<?php if($this->router->class == 'member') { ?>
<?php if($member) { ?>
<title><?php echo $member->mbr_nickname; ?> - <?php echo $this->config->item('title'); ?></title>
<?php if($member->mbr_description) { ?>
<meta content="<?php echo $member->mbr_description; ?>" name="description">
<meta content="<?php echo $member->mbr_description; ?>" property="og:description">
<?php } ?>
<meta property="og:image" content="<?php echo base_url(); ?>medias/readerself_250x250.png">
<meta property="og:site_name" content="Reader Self - Google Reader alternative">
<meta property="og:title" content="<?php echo $member->mbr_nickname; ?> - <?php echo $this->config->item('title'); ?>">
<meta property="og:type" content="profile">
<meta property="og:url" content="<?php echo base_url(); ?>member/<?php echo $member->mbr_nickname; ?>">
<link rel="alternate" type="application/atom+xml" title="<?php echo $member->mbr_nickname; ?> - <?php echo $this->lang->line('shared_items'); ?>" href="<?php echo base_url(); ?>share/<?php echo $member->token_share; ?>">
<?php } ?>
<?php } else { ?>
<title><?php echo $this->config->item('title'); ?></title>
<?php } ?>

<?php if($this->axipi_session->userdata('mbr_id')) { ?>
<meta name="application-name" content="<?php echo $this->config->item('title'); ?>">
<meta name="msapplication-starturl" content="<?php echo base_url(); ?>">
<meta name="msapplication-TileImage" content="<?php echo base_url(); ?>medias/readerself_250x250.png">
<meta name="msapplication-TileColor" content="#FFFFFF">
<meta name="msapplication-square150x150logo" content="<?php echo base_url(); ?>medias/readerself_250x250.png">
<meta name="msapplication-badge" content="frequency=30;polling-uri=<?php echo base_url(); ?>msapplication/badge/<?php echo $this->member->token_msapplication; ?>">
<?php } ?>
<link rel="icon" sizes="16x16" href="<?php echo base_url(); ?>medias/readerself_16x16.png">
<link rel="icon" sizes="128x128" href="<?php echo base_url(); ?>medias/readerself_128x128.png">
<link rel="icon" sizes="200X200" href="<?php echo base_url(); ?>medias/readerself_200x200.png">
<link rel="icon" sizes="250X250" href="<?php echo base_url(); ?>medias/readerself_250X250.png">
<link rel="apple-touch-icon" sizes="200X200" href="<?php echo base_url(); ?>medias/readerself_200x200.png">
<link rel="apple-touch-icon-precomposed" sizes="200X200" href="<?php echo base_url(); ?>medias/readerself_200x200.png">
<meta content="noindex, nofollow, noarchive" name="robots">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="HandheldFriendly" content="true">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">
<!--<meta name="theme-color" content="#009688">-->
<link rel="manifest" href="manifest.json">
<link href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="<?php echo base_url(); ?>thirdparty/mdl/material.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>styles/_html.css?modified=<?php echo filemtime('styles/_html.css'); ?>" rel="stylesheet" type="text/css">
<?php if(file_exists('styles/'.$this->router->class.'.css')) { ?>
<link href="<?php echo base_url(); ?>styles/<?php echo $this->router->class; ?>.css?modified=<?php echo filemtime('styles/'.$this->router->class.'.css'); ?>" rel="stylesheet" type="text/css">
<?php } ?>
<?php if(file_exists('styles/'.$this->router->class.'_'.$this->router->method.'.css')) { ?>
<link href="<?php echo base_url(); ?>styles/<?php echo $this->router->class; ?>_<?php echo $this->router->method; ?>.css?modified=<?php echo filemtime('styles/'.$this->router->class.'_'.$this->router->method.'.css'); ?>" rel="stylesheet" type="text/css">
<?php } ?>
<?php if(file_exists('styles/custom.css')) { ?>
<link href="<?php echo base_url(); ?>styles/custom.css?modified=<?php echo filemtime('styles/custom.css'); ?>" rel="stylesheet" type="text/css">
<?php } ?>
</head>
<body<?php if(count($this->readerself_library->errors) > 0) { ?> class="error"<?php } ?>>

<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header<?php if($this->router->class == 'home' || ($this->router->class == 'subscriptions' && $this->router->method == 'index') || ($this->router->class == 'feeds' && $this->router->method == 'index') || ($this->router->class == 'feeds' && $this->router->method == 'feedly') || ($this->router->class == 'folders' && $this->router->method == 'index')) { ?> mdl-layout--fixed-drawer<?php } ?>">
	<header class="mdl-layout__header mdl-color--<?php echo $this->config->item('material-design/colors/background/header'); ?>">
		<div class="mdl-layout__header-row">
			<div class="mdl-layout-spacer">
			</div>
			<?php if($this->config->item('salt_password')) { ?>
				<?php if($this->router->class == 'member') { ?>
					<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon items_refresh" href="#" title="r"><i class="material-icons md-24">refresh</i></a>

					<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon item_up" href="#" id="item_up" title="<?php echo $this->lang->line('title_k'); ?>"><i class="material-icons md-24">keyboard_arrow_up</i></a>
					<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon item_down" href="#" id="item_down" title="<?php echo $this->lang->line('title_j'); ?>"><i class="material-icons md-24">keyboard_arrow_down</i></a>

				<?php } else if($this->axipi_session->userdata('mbr_id')) { ?>
					<?php if($this->router->class != 'home') { ?>
						<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>"><i class="material-icons md-24">home</i></a>
					<?php } else { ?>

						<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon items_refresh" href="#" title="r"><i class="material-icons md-24">refresh</i></a>

						<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="hdrbtn_mode">
							<i class="material-icons md-24">visibility</i>
						</button>
						<ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="hdrbtn_mode">
							<li class="mdl-menu__item"><a class="items_mode mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="unread_only" title="<?php echo $this->lang->line('title_shift_2'); ?>"><?php echo $this->lang->line('unread_only'); ?></a></li>
							<li class="mdl-menu__item"><a class="items_mode mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="read_and_unread" title="<?php echo $this->lang->line('title_shift_1'); ?>"><?php echo $this->lang->line('read_and_unread'); ?></a></li>
						</ul>

						<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="hdrbtn_display">
							<i class="material-icons md-24">view_compact</i>
						</button>
						<ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="hdrbtn_display">
							<li class="mdl-menu__item"><a class="items_display mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="expand" title="2"><?php echo $this->lang->line('expand_all'); ?></a></li>
							<li class="mdl-menu__item"><a class="items_display mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="collapse" title="1"><?php echo $this->lang->line('collapse_all'); ?></a></li>
						</ul>

						<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="hdrbtn_done">
							<i class="material-icons md-24">done</i>
						</button>
						<ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="hdrbtn_done">
							<li class="mdl-menu__item"><a class="items_read mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="<?php echo base_url(); ?>items/read/all"><?php echo $this->lang->line('no_date_limit'); ?></a></li>
							<li class="mdl-menu__item"><a class="items_read mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="<?php echo base_url(); ?>items/read/one-day"><?php echo $this->lang->line('items_older_than_a_day'); ?></a></li>
							<li class="mdl-menu__item"><a class="items_read mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="<?php echo base_url(); ?>items/read/one-week"><?php echo $this->lang->line('items_older_than_a_week'); ?></a></li>
							<li class="mdl-menu__item"><a class="items_read mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="<?php echo base_url(); ?>items/read/two-weeks"><?php echo $this->lang->line('items_older_than_two_weeks'); ?></a></li>
						</ul>

						<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon item_up" href="#" id="item_up" title="<?php echo $this->lang->line('title_k'); ?>"><i class="material-icons md-24">keyboard_arrow_up</i></a>
						<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon item_down" href="#" id="item_down" title="<?php echo $this->lang->line('title_j'); ?>"><i class="material-icons md-24">keyboard_arrow_down</i></a>
					<?php } ?>

					<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="hdrbtn">
						<i class="material-icons md-24">more_vert</i>
					</button>
					<ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="hdrbtn">
						<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="<?php echo base_url(); ?>subscriptions"><?php echo $this->lang->line('subscriptions'); ?></a></li>
						<?php if($this->config->item('folders')) { ?>
							<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="<?php echo base_url(); ?>folders"><?php echo $this->lang->line('folders'); ?></a></li>
						<?php } ?>
						<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="<?php echo base_url(); ?>feeds"><?php echo $this->lang->line('feeds'); ?></a></li>
						<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="<?php echo base_url(); ?>profile"><?php echo $this->lang->line('profile'); ?></a></li>
						<?php if($this->config->item('members_list')) { ?>
							<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="<?php echo base_url(); ?>members"><?php echo $this->lang->line('members'); ?></a></li>
						<?php } ?>
						<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="<?php echo base_url(); ?>settings"><?php echo $this->lang->line('settings'); ?></a></li>
						<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="<?php echo base_url(); ?>shortcuts"><?php echo $this->lang->line('shortcuts'); ?></a></li>
						<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="<?php echo base_url(); ?>statistics"><?php echo $this->lang->line('statistics'); ?></a></li>
						<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="<?php echo base_url(); ?>logout"><?php echo $this->lang->line('logout'); ?></a></li>
					</ul>
				<?php } else { ?>
					<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>login"><i class="material-icons md-24">power_settings_new</i></a>
					<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>password"><i class="material-icons md-24">vpn_key</i></a>
					<?php if($this->config->item('register_multi') && !$this->config->item('ldap')) { ?>
						<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>register"><i class="material-icons md-24">person_add</i></a>
					<?php } ?>
				<?php } ?>
			<?php } ?>
		</div>
	</header>
	<?php if(isset($content) == 1) { echo $content; } ?>
</div>

<script>
var base_url = '<?php echo base_url(); ?>';
var csrf_token_name = '<?php echo $this->config->item('csrf_token_name'); ?>';
var csrf_cookie_name = '<?php echo $this->config->item('csrf_cookie_name'); ?>';
var current_url = '<?php echo current_url(); ?>';
var ci_controller = '<?php echo $this->router->class; ?>';
<?php if($this->axipi_session->userdata('mbr_id') && $this->input->cookie('token_connection')) { ?>
var is_logged = true;
<?php } else { ?>
var is_logged = false;
<?php } ?>
<?php if($this->axipi_session->userdata('timezone')) { ?>
var timezone = true;
<?php } else { ?>
var timezone = false;
<?php } ?>
var title = '<?php echo addslashes($this->config->item('title')); ?>';
var uri_string = '<?php echo $this->uri->uri_string(); ?>';
var material_design_colors_text_link = '<?php echo $this->config->item('material-design/colors/text/link'); ?>';
</script>

<script src="<?php echo base_url(); ?>thirdparty/mdl/material.min.js"></script>
<script src="<?php echo base_url(); ?>thirdparty/jquery/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>thirdparty/jquery/jquery.cookie.min.js"></script>
<!--<script src="<?php echo base_url(); ?>thirdparty/jquery/jquery.touchswipe.min.js"></script>-->
<script src="<?php echo base_url(); ?>thirdparty/jquery/jquery.scrollto.min.js"></script>
<script src="<?php echo base_url(); ?>thirdparty/jquery/jquery.timeago.js"></script>
<script src="<?php echo base_url(); ?>thirdparty/jquery/jquery.timeago.<?php echo $this->config->item('language'); ?>.js"></script>
<script src="<?php echo base_url(); ?>thirdparty/html5-desktop-notifications/desktop-notify-min.js"></script>

<script src="<?php echo base_url(); ?>scripts/_html.js?modified=<?php echo filemtime('scripts/_html.js'); ?>"></script>
<?php if(file_exists('scripts/'.$this->router->class.'.js')) { ?>
<script src="<?php echo base_url(); ?>scripts/<?php echo $this->router->class; ?>.js?modified=<?php echo filemtime('scripts/'.$this->router->class.'.js'); ?>"></script>
<?php } ?>
<?php if(file_exists('scripts/'.$this->router->class.'_'.$this->router->method.'.js')) { ?>
<script src="<?php echo base_url(); ?>scripts/<?php echo $this->router->class; ?>_<?php echo $this->router->method; ?>.js?modified=<?php echo filemtime('scripts/'.$this->router->class.'_'.$this->router->method.'.js'); ?>"></script>
<?php } ?>

<?php echo $this->readerself_library->get_debug(); ?>

</body>
</html>
