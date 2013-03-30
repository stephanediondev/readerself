<div id="modal-display">
	<h3><?php echo $sub->fed_title; ?></h3>

	<p>
	<a href="<?php echo base_url(); ?>subscriptions/delete_confirm/<?php echo $sub->sub_id;?>" class="modal_show"><?php echo $this->lang->line('delete'); ?></a>
	</p>
</div>
