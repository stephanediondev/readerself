<script type="text/javascript">
$('.modal-backdrop').remove();
$('#modal_call').hide();
$('tr#tag_<?php echo $tag->tag_id;?>').remove();
new_count = parseInt($('h2').find('span').text()) - 1;
$('h2').find('span').html(new_count);
</script>
