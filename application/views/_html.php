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
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<meta name="HandheldFriendly" content="true">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">
<link href="<?php echo base_url(); ?>thirdparty/fontawesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
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

<header>
	<nav>
		<ul class="actions">
			<?php if($this->config->item('salt_password')) { ?>
				<?php if($this->session->userdata('mbr_id')) { ?>
					<li class="show-phone show-tablet"><a id="toggle-sidebar" href="#"><i class="icon icon-reorder"></i><?php echo $this->lang->line('sidebar'); ?></a></li>
					<?php if($this->router->class != 'home') { ?>
						<li class="show-phone"><a href="<?php echo base_url(); ?>home"><i class="icon icon-home"></i><?php echo $this->lang->line('home'); ?></a></li>
					<?php } ?>
					<li class="hide-phone"><a href="<?php echo base_url(); ?>home"><i class="icon icon-home"></i><?php echo $this->lang->line('home'); ?></a></li>
					<?php if($this->config->item('folders')) { ?>
						<li class="hide-phone"><a href="<?php echo base_url(); ?>folders"><i class="icon icon-folder-close"></i><?php echo $this->lang->line('folders'); ?></a></li>
					<?php } ?>
					<li class="hide-phone"><a href="<?php echo base_url(); ?>subscriptions"><i class="icon icon-bookmark"></i><?php echo $this->lang->line('subscriptions'); ?></a></li>
					<li class="hide-phone"><a href="<?php echo base_url(); ?>feeds"><i class="icon icon-rss"></i><?php echo $this->lang->line('feeds'); ?></a></li>
					<?php if($this->config->item('members_list')) { ?>
						<li class="hide-phone"><a href="<?php echo base_url(); ?>members"><i class="icon icon-group"></i><?php echo $this->lang->line('members'); ?></a></li>
					<?php } ?>
					<li class="hide-phone hide-tablet"><a href="<?php echo base_url(); ?>statistics"><i class="icon icon-bar-chart"></i><?php echo $this->lang->line('statistics'); ?></a></li>
					<?php if($this->router->class == 'home') { ?>
						<li class="hide-phone hide-tablet"><a id="link_shortcuts" class="modal_show" href="<?php echo base_url(); ?>home/shortcuts" title="<?php echo $this->lang->line('title_help'); ?>"><i class="icon icon-keyboard"></i><?php echo $this->lang->line('shortcuts'); ?></a></li>
					<?php } ?>
					<li class="hide-phone"><a href="<?php echo base_url(); ?>profile"><i class="icon icon-<?php if($this->member->mbr_administrator == 1) { ?>shield<?php } else { ?>user<?php } ?>"></i><?php if($this->member->mbr_nickname) { ?><?php echo $this->member->mbr_nickname; ?><?php } else { ?><?php echo $this->lang->line('profile'); ?><?php } ?></a></li>
					<?php if($this->member->mbr_administrator == 1) { ?>
						<li class="hide-phone"><a href="<?php echo base_url(); ?>settings"><i class="icon icon-gears"></i><?php echo $this->lang->line('settings'); ?></a></li>
					<?php } ?>
					<li><a href="<?php echo base_url(); ?>logout"><i class="icon icon-signout"></i><?php echo $this->lang->line('logout'); ?></a></li>
				<?php } else { ?>
					<li><a href="<?php echo base_url(); ?>login"><i class="icon icon-signin"></i><?php echo $this->lang->line('login'); ?></a></li>
					<li><a href="<?php echo base_url(); ?>password"><i class="icon icon-key"></i><?php echo $this->lang->line('password'); ?></a></li>
					<?php if($this->config->item('register_multi') && !$this->config->item('ldap')) { ?><li><a href="<?php echo base_url(); ?>register"><i class="icon icon-user"></i><?php echo $this->lang->line('register'); ?></a></li><?php } ?>
					<li class="hide-phone"><a target="_blank" href="http://readerself.com"><i class="icon icon-rss"></i>Reader Self</a></li>
				<?php } ?>
			<?php } ?>
		</ul>
	</nav>

<?php if(isset($content) == 1) { echo $content; } ?>

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
