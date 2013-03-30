<script type="text/javascript">
modal_hide();
$('tr#tag_<?php echo $tag->tag_id;?>').remove();
new_count = parseInt($('h1').find('span').text()) - 1;
$('h1').find('span').html(new_count);
</script>
