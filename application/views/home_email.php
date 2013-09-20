<div id="modal-display">
	<ul class="actions">
		<li><a class="modal_hide" href="#" title="<?php echo $this->lang->line('title_esc'); ?>"><i class="icon icon-remove"></i><?php echo $this->lang->line('close'); ?></a></li>
	</ul>

	<h1><i class="icon icon-envelope"></i><?php echo $this->lang->line('share_email'); ?></h1>

	<?php echo validation_errors(); ?>

	<?php echo form_open(current_url()); ?>

	<p>
	<?php echo form_label($this->lang->line('email_subject')); ?>
	<?php echo form_input('email_subject', set_value('email_subject', $itm->itm_title), 'id="email_subject" class="required"'); ?>
	</p>

	<p>
	<?php echo form_label($this->lang->line('email_to')); ?>
	<?php echo form_input('email_to', set_value('email_to'), 'id="email_to" class="valid_email required"'); ?>
	</p>

	<p>
	<?php echo form_label($this->lang->line('email_message')); ?>
	<?php echo form_textarea('email_message', set_value('email_message', ''), 'id="email_message"'); ?>
	</p>

	<p>
	<button type="submit"><?php echo $this->lang->line('send'); ?></button>
	</p>
	<?php echo form_close(); ?>
</div>
