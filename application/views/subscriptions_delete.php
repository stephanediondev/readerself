<div id="modal-display">
	<h1><i class="icon-rss"></i> <?php echo $sub->fed_title; ?></h1>

	<?php if($sub->fed_description) { ?><p><?php echo $sub->fed_description; ?></p><?php } ?>

	<ul class="actions">
		<li><a href="<?php echo base_url(); ?>subscriptions/delete_confirm/<?php echo $sub->sub_id;?>" class="modal_show"><i class="icon-trash"></i><?php echo $this->lang->line('delete'); ?></a></li>
	</ul>
</div>
