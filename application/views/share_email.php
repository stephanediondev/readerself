<!DOCTYPE html>
<html>
<head>
<title><?php echo $this->config->item('title'); ?></title>
<meta content="noindex, nofollow, noarchive" name="robots">
</head>
<body style="background-color:#FFFFFF;">

<?php if($this->input->post('email_message')) { ?>
	<div style="background-color:#FFFFFF;border:1px solid #E3E3E3;border-radius:3px 3px 3px 3px;color:#333333;font-family:Helvetica,Arial,sans-serif;font-size:14px;margin-bottom:0px;margin-left:10px;margin-right:10px;margin-top:10px;padding:10px 10px;">
		<?php echo nl2br( html_entity_decode( strip_tags($this->input->post('email_message')), ENT_QUOTES, 'UTF-8' ) ); ?>
	</div>
<?php } ?>

<div<?php if($itm->sub->direction) { ?> dir="<?php echo $itm->sub->direction; ?>"<?php } ?> style="background-color:#FFFFFF;border:1px solid #E3E3E3;border-radius:3px 3px 3px 3px;color:#333333;font-family:Helvetica,Arial,sans-serif;font-size:14px;margin-bottom:0px;margin-left:10px;margin-right:10px;margin-top:10px;padding:10px 10px;">
	<h2 style="font-size:14px;font-weight:bold;margin:5px 0px;"><a style="color:#777777;" href="<?php echo $itm->itm_link; ?>"><?php echo $itm->itm_title; ?></a></h2>
	<ul style="margin:5px 0px;padding:0px 20px;">
		<li><?php echo $itm->explode_date; ?></li>
		<li><?php echo $itm->explode_time; ?></li>
		<?php if($itm->itm_author) { ?>
			<li><?php echo $itm->itm_author; ?></li>
		<?php } ?>
		<li><?php echo $itm->sub->title; ?></li>
		<?php if($this->config->item('tags') && $itm->categories) { ?>
			<li><?php echo implode(', ', $itm->categories); ?></li>
		<?php } ?>
	</ul>
</div>

<div<?php if($itm->sub->direction) { ?> dir="<?php echo $itm->sub->direction; ?>"<?php } ?> style="background-color:#F5F5F5;border:1px solid #E3E3E3;border-radius:3px 3px 3px 3px;color:#333333;font-family:Helvetica,Arial,sans-serif;font-size:14px;margin-bottom:10px;margin-left:10px;margin-right:10px;margin-top:10px;padding:10px 10px;">
	<?php echo html_entity_decode($itm->itm_content, ENT_QUOTES, 'UTF-8'); ?>
</div>

</body>
</html>
