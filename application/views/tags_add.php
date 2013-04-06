<div id="modal-display">
	<h1><i class="icon icon-tag"></i><?php echo $this->lang->line('add_tag'); ?></h1>

	<?php echo validation_errors(); ?>

	<?php echo form_open(current_url()); ?>

	<?php if($alert) { ?>
	<div class="alert alert-<?php echo $alert['type']; ?>"><button data-dismiss="alert" class="close" type="button">×</button><?php echo $alert['message']; ?></div>
	<?php } ?>

	<p>
	<?php echo form_label('Title', 'tag_title'); ?>
	<?php echo form_input('tag_title', set_value('tag_title'), 'id="tag_title" class="input-xlarge required"'); ?>
	</p>

	<p>
	<button type="submit"><?php echo $this->lang->line('send'); ?></button>
	</p>
	<?php echo form_close(); ?>
</div>
