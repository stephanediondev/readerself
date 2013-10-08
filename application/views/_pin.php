<!DOCTYPE html>
<html>
<head>
<title><?php echo $this->config->item('title'); ?></title>
<link rel="icon" href="<?php echo base_url(); ?>favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico" type="image/x-icon">
<link rel="apple-touch-icon" href="<?php echo base_url(); ?>medias/readerself_200x200.png">
<meta content="noindex, nofollow, noarchive" name="robots">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<meta name="HandheldFriendly" content="true">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">
<link href="<?php echo base_url(); ?>styles/_pin.css?modified=<?php echo filemtime('styles/_html.css'); ?>" rel="stylesheet" type="text/css">
</head>
<body<?php if(count($this->readerself_library->errors) > 0) { ?> class="error"<?php } ?>>
<div id="pin">
	<p><a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>medias/readerself_250x250.png" alt=""></a></p>
</div>

<?php echo $this->readerself_library->get_debug(); ?>

</body>
</html>
