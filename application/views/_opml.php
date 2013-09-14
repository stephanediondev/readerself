<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<opml version="2.0">
<?php if(isset($content) == 1) { echo $content; } ?>
<?php echo $this->reader_library->get_debug(); ?>
</opml>
