<div id="modal-display">
	<h1><?php echo $tag->tag_title; ?></h1>

	<p>
	<a href="<?php echo base_url(); ?>tags/delete_confirm/<?php echo $tag->tag_id;?>" class="modal_show"><?php echo $this->lang->line('delete'); ?></a>
	</p>
</div>
