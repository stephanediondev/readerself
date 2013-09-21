<!DOCTYPE html>
<html>
<head>
<title><?php echo $this->config->item('title'); ?></title>
<meta content="noindex, nofollow, noarchive" name="robots">
</head>
<body style="background-color:#FFFFFF;-webkit-text-size-adjust:none;">

<?php if($this->input->post('email_message')) { ?>
	<div style="background-color:#FFFFFF;border:1px solid #E3E3E3;border-radius:3px 3px 3px 3px;color:#333333;font-family:Helvetica,Arial,sans-serif;font-size:14px;margin-bottom:0px;margin-left:10px;margin-right:10px;margin-top:10px;padding:10px 10px;-webkit-text-size-adjust:none;">
		<?php echo nl2br( html_entity_decode( strip_tags($this->input->post('email_message')), ENT_QUOTES, 'UTF-8' ) ); ?>
	</div>
<?php } ?>

<div<?php if($itm->sub->direction) { ?> dir="<?php echo $itm->sub->direction; ?>"<?php } ?> style="background-color:#FFFFFF;border:1px solid #E3E3E3;border-radius:3px 3px 3px 3px;color:#333333;font-family:Helvetica,Arial,sans-serif;font-size:14px;margin-bottom:0px;margin-left:10px;margin-right:10px;margin-top:10px;padding:10px 10px;-webkit-text-size-adjust:none;">
	<h2 style="font-size:14px;font-weight:bold;margin:5px 0px;"><a style="color:#777777;" href="<?php echo $itm->itm_link; ?>"><?php echo $itm->itm_title; ?></a></h2>
	<p style="margin:5px 0px;"><?php echo $itm->explode_date; ?> / <?php echo $itm->explode_time; ?><?php if($itm->itm_author) { ?> / <?php echo $itm->itm_author; ?><?php } ?> / <?php echo $itm->sub->title; ?><?php if($this->config->item('tags') && $itm->categories) { ?><br><?php echo implode(', ', $itm->categories); ?><?php } ?></p>
</div>

<div<?php if($itm->sub->direction) { ?> dir="<?php echo $itm->sub->direction; ?>"<?php } ?> style="background-color:#F5F5F5;border:1px solid #E3E3E3;border-radius:3px 3px 3px 3px;color:#333333;font-family:Helvetica,Arial,sans-serif;font-size:14px;margin-bottom:10px;margin-left:10px;margin-right:10px;margin-top:10px;overflow:hidden;padding:10px 10px;-webkit-text-size-adjust:none;">
	<?php echo html_entity_decode(str_replace('<img ', '<img style="max-width:100%;" ', $itm->itm_content), ENT_QUOTES, 'UTF-8'); ?>
</div>

</body>
</html>
