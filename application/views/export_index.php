<head>
<title><?php echo $this->member->mbr_email; ?> subscriptions</title>
</head>
<body>
<?php foreach($subscriptions as $tag => $sub_array) { ?>
<?php if($tag && $tag != '') { ?>
<outline title="<?php echo $tag; ?>" text="<?php echo $tag; ?>">
<?php } ?>
<?php foreach($sub_array as $sub) { ?>
<outline text="<?php echo str_replace('&', '&amp;', html_entity_decode($sub->fed_title)); ?>" title="<?php echo str_replace('&', '&amp;', html_entity_decode($sub->fed_title)); ?>" type="rss" xmlUrl="<?php echo $sub->fed_link; ?>" htmlUrl="<?php echo $sub->fed_url; ?>"/>
<?php } ?>
<?php if($tag && $tag != '') { ?>
</outline>
<?php } ?>
<?php } ?>
</body>
