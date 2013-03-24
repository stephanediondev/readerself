<!DOCTYPE html>
<html>
<head>
<title>(0)</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link href="<?php echo base_url(); ?>thirdparty/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="<?php echo base_url(); ?>styles/_html.css" rel="stylesheet" type="text/css">
<?php if(file_exists('styles/'.$this->router->class.'.css')) { ?>
<link href="<?php echo base_url(); ?>styles/<?php echo $this->router->class; ?>.css" rel="stylesheet" type="text/css">
<?php } ?>
</head>
<body>

<div class="navbar">
	<div class="navbar-inner">
		<?php if($this->session->userdata('logged_member')) { ?>
		<span class="brand"><?php echo $this->member->mbr_email; ?></span>
		<?php } ?>
		<ul class="nav">
		<?php if($this->session->userdata('logged_member')) { ?>
		<li><a href="<?php echo base_url(); ?>home"><?php echo $this->lang->line('home'); ?></a></li>
		<li class="dropdown">
			<a data-toggle="dropdown" class="dropdown-toggle" href="#"><?php echo $this->lang->line('settings'); ?>...</a>
			<ul class="dropdown-menu">
			<li><a href="<?php echo base_url(); ?>subscriptions"><?php echo $this->lang->line('subscriptions'); ?></a></li>
			<li><a href="<?php echo base_url(); ?>tags"><?php echo $this->lang->line('tags'); ?></a></li>
			<li><a href="<?php echo base_url(); ?>import"><?php echo $this->lang->line('import'); ?></a></li>
			</ul>
		</li>
		<li><a href="<?php echo base_url(); ?>profile"><?php echo $this->lang->line('profile'); ?></a></li>
		<li><a href="<?php echo base_url(); ?>logout"><?php echo $this->lang->line('logout'); ?></a></li>
		<?php } else { ?>
		<li><a href="<?php echo base_url(); ?>login"><?php echo $this->lang->line('login'); ?></a></li>
		<li><a href="<?php echo base_url(); ?>password"><?php echo $this->lang->line('password'); ?></a></li>
		<?php } ?>
		</ul>
		<?php if($this->session->userdata('alert')) { ?>
		<?php $alert = unserialize($this->session->userdata('alert')); ?>
		<div class="alert alert-<?php echo $alert['type']; ?>"><button data-dismiss="alert" class="close" type="button">×</button><?php echo $alert['message']; ?></div>
		<?php $this->session->unset_userdata('alert'); ?>
		<?php } ?>
	</div>
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
<script src="<?php echo base_url(); ?>thirdparty/jquery/jquery.inview.min.js"></script>

<script src="<?php echo base_url(); ?>thirdparty/bootstrap/js/bootstrap.min.js"></script>

<script src="<?php echo base_url(); ?>scripts/_html.js"></script>
<?php if(file_exists('scripts/'.$this->router->class.'.js')) { ?>
<script src="<?php echo base_url(); ?>scripts/<?php echo $this->router->class; ?>.js"></script>
<?php } ?>

<div id="modal_call" class="modal hide">
</div>

<?php echo $this->reader_library->get_debug(); ?>

</body>
</html>
