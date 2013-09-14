<head>
<title>Explore from <?php echo $this->config->item('title'); ?></title>
<docs>http://dev.opml.org/spec2.html</docs>
</head>
<body>
<?php foreach($feeds as $folder => $fed) { ?>
<outline text="<?php echo str_replace('&', '&amp;', html_entity_decode($fed->fed_title)); ?>" title="<?php echo str_replace('&', '&amp;', html_entity_decode($fed->fed_title)); ?>" type="rss" xmlUrl="<?php echo str_replace('&', '&amp;', html_entity_decode($fed->fed_link)); ?>" htmlUrl="<?php echo str_replace('&', '&amp;', html_entity_decode($fed->fed_url)); ?>"/>
<?php } ?>
</body>
