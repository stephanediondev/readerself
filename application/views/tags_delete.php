<div id="modal-display">
	<h1><i class="icon icon-tag"></i><?php echo $tag->tag_title; ?></h1>

	<ul class="actions">
		<li><a href="<?php echo base_url(); ?>tags/delete_confirm/<?php echo $tag->tag_id;?>" class="modal_show"><i class="icon icon-trash"></i><?php echo $this->lang->line('delete'); ?></a></li>
	</ul>
</div>
