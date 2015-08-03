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

<?php if($this->session->userdata('mbr_id')) { ?>
<meta name="application-name" content="<?php echo $this->config->item('title'); ?>">
<meta name="msapplication-starturl" content="<?php echo base_url(); ?>">
<meta name="msapplication-TileImage" content="<?php echo base_url(); ?>medias/readerself_250x250.png">
<meta name="msapplication-TileColor" content="#FFFFFF">
<meta name="msapplication-square150x150logo" content="<?php echo base_url(); ?>medias/readerself_250x250.png">
<meta name="msapplication-badge" content="frequency=30;polling-uri=<?php echo base_url(); ?>msapplication/badge/<?php echo $this->member->token_msapplication; ?>">
<?php } ?>

<link rel="icon" href="<?php echo base_url(); ?>favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico" type="image/x-icon">
<link rel="apple-touch-icon" href="<?php echo base_url(); ?>medias/readerself_200x200.png">
<meta content="noindex, nofollow, noarchive" name="robots">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="HandheldFriendly" content="true">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">
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

<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header<?php if($this->router->class == 'home') { ?> mdl-layout--fixed-drawer<?php } ?>">
	<header class="mdl-layout__header mdl-color--teal">
		<div class="mdl-layout__header-row">
			<div class="mdl-layout-spacer">
			</div>
			<?php if($this->config->item('salt_password')) { ?>
				<?php if($this->router->class == 'member') { ?>
					<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon items_refresh" href="#" title="r"><i class="material-icons md-24">refresh</i></a>

					<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon item_up" href="#" id="item_up" title="<?php echo $this->lang->line('title_k'); ?>"><i class="material-icons md-24">keyboard_arrow_up</i></a>
					<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon item_down" href="#" id="item_down" title="<?php echo $this->lang->line('title_j'); ?>"><i class="material-icons md-24">keyboard_arrow_down</i></a>

				<?php } else if($this->session->userdata('mbr_id')) { ?>
					<?php if($this->router->class != 'home') { ?>
						<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>"><i class="material-icons md-24">home</i></a>
					<?php } else { ?>

						<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon items_refresh" href="#" title="r"><i class="material-icons md-24">refresh</i></a>

						<a href="#" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon items_mode"><span class="unread_only" title="maj. + 2"><i class="material-icons md-24">panorama_fish_eye</i></span><span class="read_and_unread" title="maj. + 1"><i class="material-icons md-24">radio_button_checked</i></span></a>

						<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="hdrbtn_done">
							<i class="material-icons md-24">done</i>
						</button>
						<ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="hdrbtn_done">
							<li class="mdl-menu__item"><a href="<?php echo base_url(); ?>items/read/all"><?php echo $this->lang->line('no_date_limit'); ?></a></li>
							<li class="mdl-menu__item"><a href="<?php echo base_url(); ?>items/read/one-day"><?php echo $this->lang->line('items_older_than_a_day'); ?></a></li>
							<li class="mdl-menu__item"><a href="<?php echo base_url(); ?>items/read/one-week"><?php echo $this->lang->line('items_older_than_a_week'); ?></a></li>
							<li class="mdl-menu__item"><a href="<?php echo base_url(); ?>items/read/two-weeks"><?php echo $this->lang->line('items_older_than_two_weeks'); ?></a></li>
						</ul>

						<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon item_up" href="#" id="item_up" title="<?php echo $this->lang->line('title_k'); ?>"><i class="material-icons md-24">keyboard_arrow_up</i></a>
						<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon item_down" href="#" id="item_down" title="<?php echo $this->lang->line('title_j'); ?>"><i class="material-icons md-24">keyboard_arrow_down</i></a>
					<?php } ?>

					<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="hdrbtn">
						<i class="material-icons md-24">more_vert</i>
					</button>
					<ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="hdrbtn">
						<li class="mdl-menu__item"><a href="<?php echo base_url(); ?>subscriptions"><?php echo $this->lang->line('subscriptions'); ?></a></li>
						<?php if($this->config->item('folders')) { ?>
							<li class="mdl-menu__item"><a href="<?php echo base_url(); ?>folders"><?php echo $this->lang->line('folders'); ?></a></li>
						<?php } ?>
						<li class="mdl-menu__item"><a href="<?php echo base_url(); ?>feeds"><?php echo $this->lang->line('feeds'); ?></a></li>
						<li class="mdl-menu__item"><a href="<?php echo base_url(); ?>profile"><?php echo $this->lang->line('profile'); ?></a></li>
						<?php if($this->member->mbr_administrator == 1) { ?>
							<li class="mdl-menu__item"><a href="<?php echo base_url(); ?>settings"><?php echo $this->lang->line('settings'); ?></a></li>
						<?php } ?>
						<li class="mdl-menu__item"><a id="link_shortcuts" class="modal_show" href="<?php echo base_url(); ?>home/shortcuts" title="<?php echo $this->lang->line('title_help'); ?>"><?php echo $this->lang->line('shortcuts'); ?></a></li>
						<li class="mdl-menu__item"><a href="<?php echo base_url(); ?>statistics"><?php echo $this->lang->line('statistics'); ?></a></li>
						<li class="mdl-menu__item"><a href="<?php echo base_url(); ?>logout"><?php echo $this->lang->line('logout'); ?></a></li>
					</ul>
				<?php } else { ?>
					<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>login"><i class="material-icons md-24">login</i></a>
					<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>password"><i class="material-icons md-24">password</i></a>
					<?php if($this->config->item('register_multi') && !$this->config->item('ldap')) { ?>
						<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>register"><?php echo $this->lang->line('register'); ?></a>
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
<?php if($this->session->userdata('mbr_id') && $this->input->cookie('token_connection')) { ?>
var is_logged = true;
<?php } else { ?>
var is_logged = false;
<?php } ?>
<?php if($this->session->userdata('timezone')) { ?>
var timezone = true;
<?php } else { ?>
var timezone = false;
<?php } ?>
var title = '<?php echo addslashes($this->config->item('title')); ?>';
var uri_string = '<?php echo $this->uri->uri_string(); ?>';
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
