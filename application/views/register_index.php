<div id="content">
	<h1><?php echo $this->lang->line('register'); ?></h1>

	<?php echo validation_errors(); ?>

	<?php echo form_open(current_url()); ?>

	<p>
	<?php echo form_label($this->lang->line('mbr_email'), 'mbr_email'); ?>
	<?php echo form_input('mbr_email', set_value('mbr_email'), 'id="mbr_email" class="valid_email required"'); ?>
	</p>

	<p>
	<?php echo form_label($this->lang->line('mbr_email_confirm'), 'mbr_email_confirm'); ?>
	<?php echo form_input('mbr_email_confirm', set_value('mbr_email_confirm'), 'id="mbr_email_confirm" class="valid_email required"'); ?>
	</p>

	<p>
	<?php echo form_label($this->lang->line('mbr_password'), 'mbr_password'); ?>
	<?php echo form_password('mbr_password', set_value('mbr_password'), 'id="mbr_password" class="required"'); ?>
	</p>

	<p>
	<?php echo form_label($this->lang->line('mbr_password_confirm'), 'mbr_password_confirm'); ?>
	<?php echo form_password('mbr_password_confirm', set_value('mbr_password_confirm'), 'id="mbr_password_confirm" class="required"'); ?>
	</p>

	<p>
	<button type="submit"><?php echo $this->lang->line('send'); ?></button>
	</p>

	<?php echo form_close(); ?>
</div>
