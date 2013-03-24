<div class="container-fluid">
	<h2><?php echo $this->lang->line('password'); ?></h2>

	<?php echo validation_errors(); ?>

	<?php echo form_open(current_url()); ?>

	<p>
	<?php echo form_label($this->lang->line('mbr_email'), 'mbr_email'); ?>
	<?php echo form_input('mbr_email', set_value('mbr_email'), 'id="mbr_email" class="valid_email required"'); ?>
	</p>

	<p>
	<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('send'); ?></button>
	</p>

	<?php echo form_close(); ?>
</div>
