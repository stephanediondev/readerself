<div id="modal-display">
	<h1><i class="icon-ok"></i> <?php echo $this->lang->line('mark_all_as_read'); ?></h1>

	<?php echo validation_errors(); ?>

	<?php echo form_open(current_url()); ?>

	<p>
	<?php echo form_label($this->lang->line('age'), 'age'); ?>
	<?php echo form_dropdown('age', array('all'=>$this->lang->line('all_items'), 'one-day'=>$this->lang->line('items_older_than_a_day'), 'one-week'=>$this->lang->line('items_older_than_a_week'), 'two-weeks'=>$this->lang->line('items_older_than_two_weeks') ), set_value('age'), 'id="age" class="select required numeric"'); ?>
	</p>

	<p>
	<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('send'); ?></button>
	</p>
	<?php echo form_close(); ?>
</div>
