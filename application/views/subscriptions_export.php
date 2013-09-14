<head>
<title><?php echo $this->member->mbr_email; ?> subscriptions</title>
</head>
<body>
<?php foreach($subscriptions as $folder => $sub_array) { ?>
<?php if($folder && $folder != '') { ?>
<outline title="<?php echo $folder; ?>" text="<?php echo $folder; ?>">
<?php } ?>
<?php foreach($sub_array as $sub) { ?>
<outline text="<?php echo str_replace('&', '&amp;', html_entity_decode($sub->fed_title)); ?>" title="<?php echo str_replace('&', '&amp;', html_entity_decode($sub->fed_title)); ?>" type="rss" xmlUrl="<?php echo str_replace('&', '&amp;', html_entity_decode($sub->fed_link)); ?>" htmlUrl="<?php echo str_replace('&', '&amp;', html_entity_decode($sub->fed_url)); ?>"/>
<?php } ?>
<?php if($folder && $folder != '') { ?>
</outline>
<?php } ?>
<?php } ?>
</body>
