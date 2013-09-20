<!DOCTYPE html>
<html>
<head>
<title><?php echo $this->config->item('title'); ?></title>
<meta content="noindex, nofollow, noarchive" name="robots">
</head>
<body style="background-color:#FFFFFF;">

<div style="background-color:#FFFFFF;border:1px solid #E3E3E3;border-radius:3px 3px 3px 3px;color:#333333;font-family:Helvetica,Arial,sans-serif;font-size:14px;margin-bottom:10px;margin-left:10px;margin-right:10px;margin-top:10px;padding:5px 10px;">
	<h2 style="font-size:14px;font-weight:bold;margin:5px 0px;"><a style="color:#777777;" href="<?php echo base_url(); ?>password/token/<?php echo $token_password; ?>"><?php echo $this->lang->line('password'); ?></a></h2>
</div>

</body>
</html>
