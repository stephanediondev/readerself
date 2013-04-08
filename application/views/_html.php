<!DOCTYPE html>
<html>
<head>
<title>Reader</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link href="<?php echo base_url(); ?>thirdparty/fontawesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>styles/_html.css" rel="stylesheet" type="text/css">
<?php if(file_exists('styles/'.$this->router->class.'.css')) { ?>
<link href="<?php echo base_url(); ?>styles/<?php echo $this->router->class; ?>.css" rel="stylesheet" type="text/css">
<?php } ?>
</head>
<body>

<div id="nav">
	<ul class="actions">
		<?php if($this->session->userdata('logged_member')) { ?>
		<li class="hide-phone"><a href="<?php echo base_url(); ?>home"><i class="icon icon-home"></i><?php echo $this->lang->line('home'); ?></a></li>
		<li class="hide-phone"><a href="<?php echo base_url(); ?>subscriptions"><i class="icon icon-rss"></i><?php echo $this->lang->line('subscriptions'); ?></a></li>
		<li class="hide-phone"><a href="<?php echo base_url(); ?>tags"><i class="icon icon-tags"></i><?php echo $this->lang->line('tags'); ?></a></li>
		<li class="hide-phone hide-tablet"><a href="<?php echo base_url(); ?>import"><i class="icon icon-download-alt"></i><?php echo $this->lang->line('import'); ?></a></li>
		<li class="hide-phone"><a href="<?php echo base_url(); ?>profile"><i class="icon icon-user"></i><?php echo $this->lang->line('profile'); ?></a></li>
		<li><a href="<?php echo base_url(); ?>logout"><i class="icon icon-signout"></i><?php echo $this->lang->line('logout'); ?></a></li>
		<?php } else { ?>
		<li><a href="<?php echo base_url(); ?>login"><i class="icon icon-signin"></i><?php echo $this->lang->line('login'); ?></a></li>
		<li><a href="<?php echo base_url(); ?>password"><i class="icon icon-key"></i><?php echo $this->lang->line('password'); ?></a></li>
		<?php } ?>
		<li class="hide-phone hide-tablet"><a target="_blank" href="https://github.com/project29k/reader"><i class="icon icon-github"></i>GitHub</a></li>
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

<script src="<?php echo base_url(); ?>scripts/_html.js"></script>
<?php if(file_exists('scripts/'.$this->router->class.'.js')) { ?>
<script src="<?php echo base_url(); ?>scripts/<?php echo $this->router->class; ?>.js"></script>
<?php } ?>

<?php echo $this->reader_library->get_debug(); ?>

</body>
</html>
