<div id="modal-display">
	<h1>Subscribe</h1>

	<?php echo validation_errors(); ?>

	<?php echo form_open(current_url()); ?>

	<?php if($alert) { ?>
	<div class="alert alert-<?php echo $alert['type']; ?>"><button data-dismiss="alert" class="close" type="button">×</button><?php echo $alert['message']; ?></div>
	<?php } ?>

	<p>
	<?php echo form_label($this->lang->line('url'), 'url'); ?>
	<?php echo form_input('url', set_value('url'), 'id="url" class="input-xlarge required"'); ?>
	</p>

	<p>
	<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('send'); ?></button>
	</p>
	<?php echo form_close(); ?>
</div>
