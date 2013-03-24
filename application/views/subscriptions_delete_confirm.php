<script type="text/javascript">
$('.modal-backdrop').remove();
$('#modal_call').hide();
$('tr#sub_<?php echo $sub->sub_id;?>').remove();
count_lines();
new_count = parseInt($('h2').find('span').text()) - 1;
$('h2').find('span').html(new_count);
</script>
