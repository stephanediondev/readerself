<!DOCTYPE html>
<html>
<head>
<title><?php echo $this->config->item('title'); ?></title>
<link rel="apple-touch-icon" href="<?php echo base_url(); ?>medias/readerself_200x200.png">
<meta content="noindex, nofollow, noarchive" name="robots">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<link href="<?php echo base_url(); ?>thirdparty/fontawesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>styles/_html.css" rel="stylesheet" type="text/css">
<?php if(file_exists('styles/'.$this->router->class.'.css')) { ?>
<link href="<?php echo base_url(); ?>styles/<?php echo $this->router->class; ?>.css" rel="stylesheet" type="text/css">
<?php } ?>
</head>
<body<?php if(count($this->reader_library->errors) > 0) { ?> class="error"<?php } ?>>

<div id="nav">
	<ul class="actions">
		<?php if($this->session->userdata('logged_member')) { ?>
		<li class="show-phone show-tablet"><a id="toggle-sidebar" href="#"><i class="icon icon-reorder"></i><?php echo $this->lang->line('sidebar'); ?></a></li>
		<li class="hide-phone"><a href="<?php echo base_url(); ?>home"><i class="icon icon-home"></i><?php echo $this->lang->line('home'); ?></a></li>
		<li class="hide-phone"><a href="<?php echo base_url(); ?>subscriptions"><i class="icon icon-rss"></i><?php echo $this->lang->line('subscriptions'); ?></a></li>
		<?php if($this->config->item('folders')) { ?><li class="hide-phone"><a href="<?php echo base_url(); ?>folders"><i class="icon icon-folder-close"></i><?php echo $this->lang->line('folders'); ?></a></li><?php } ?>
		<?php if($this->config->item('register_multi')) { ?><li class="hide-phone"><a href="<?php echo base_url(); ?>explore"><i class="icon icon-group"></i><?php echo $this->lang->line('explore'); ?></a></li><?php } ?>
		<li class="hide-phone hide-tablet"><a href="<?php echo base_url(); ?>statistics"><i class="icon icon-bar-chart"></i><?php echo $this->lang->line('statistics'); ?></a></li>
		<li class="hide-phone"><a href="<?php echo base_url(); ?>profile"><i class="icon icon-user"></i><?php echo $this->lang->line('profile'); ?></a></li>
		<?php } else { ?>
		<li><a href="<?php echo base_url(); ?>login"><i class="icon icon-signin"></i><?php echo $this->lang->line('login'); ?></a></li>
		<li><a href="<?php echo base_url(); ?>password"><i class="icon icon-key"></i><?php echo $this->lang->line('password'); ?></a></li>
		<?php if($this->config->item('register_multi')) { ?><li><a href="<?php echo base_url(); ?>register"><i class="icon icon-user"></i><?php echo $this->lang->line('register'); ?></a></li><?php } ?>
		<li class="hide-phone hide-tablet"><a target="_blank" href="https://github.com/readerself"><i class="icon icon-github"></i>GitHub</a></li>
		<li class="hide-phone hide-tablet"><a target="_blank" href="https://www.facebook.com/readerself"><i class="icon icon-facebook-sign"></i>Facebook</a></li>
		<?php } ?>
		<?php if($this->router->class == 'home') { ?><li class="hide-phone hide-tablet"><a class="modal_show" href="<?php echo base_url(); ?>home/shortcuts"><i class="icon icon-keyboard"></i><?php echo $this->lang->line('shortcuts'); ?></a></li><?php } ?>
	</ul>
</div>

<?php if(isset($content) == 1) { echo $content; } ?>

<script>
var base_url = '<?php echo base_url(); ?>';
var csrf_token_name = '<?php echo $this->config->item('csrf_token_name'); ?>';
var csrf_cookie_name = '<?php echo $this->config->item('csrf_cookie_name'); ?>';
var current_url = '<?php echo current_url(); ?>';

<?php if($this->session->userdata('logged_member')) { ?>
var is_logged = true;
<?php } else { ?>
var is_logged = false;
<?php } ?>

<?php if($this->session->userdata('timezone')) { ?>
var timezone = true;
<?php } else { ?>
var timezone = false;

<?php } ?>
var uri_string = '<?php echo $this->uri->uri_string(); ?>';
</script>

<script src="<?php echo base_url(); ?>thirdparty/jquery/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>thirdparty/jquery/jquery.cookie.min.js"></script>
<script src="<?php echo base_url(); ?>thirdparty/jquery/jquery.timeago.js"></script>
<script src="<?php echo base_url(); ?>thirdparty/jquery/jquery.timeago.<?php echo $this->config->item('language'); ?>.js"></script>

<script src="<?php echo base_url(); ?>scripts/_html.js"></script>
<?php if(file_exists('scripts/'.$this->router->class.'.js')) { ?>
<script src="<?php echo base_url(); ?>scripts/<?php echo $this->router->class; ?>.js"></script>
<?php } ?>

<?php echo $this->reader_library->get_debug(); ?>

</body>
</html>
