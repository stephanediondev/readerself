<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h3><?php echo $this->lang->line('tags'); ?></h3>
</div>
<div class="modal-body">
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
	<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('add'); ?></button>
	</p>
	<?php echo form_close(); ?>
</div>
<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo $this->lang->line('cancel'); ?></button>
</div>
