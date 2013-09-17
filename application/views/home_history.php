<div id="modal-display">
	<ul class="actions">
		<li><a class="modal_hide" href="#" title="<?php echo $this->lang->line('title_esc'); ?>"><i class="icon icon-remove"></i><?php echo $this->lang->line('close'); ?></a></li>
	</ul>

	<h1><i class="icon icon-ok"></i><?php echo $this->lang->line('mark_all_as_read'); ?></h1>

	<?php echo validation_errors(); ?>

	<?php echo form_open(current_url()); ?>

	<p>
	<?php echo form_label('<i class="icon icon-'.$icon.'"></i>'.$title.' ('.$count.')', 'age'); ?>
	<?php echo form_dropdown('age', array('all'=>$this->lang->line('no_date_limit'), 'one-day'=>$this->lang->line('items_older_than_a_day'), 'one-week'=>$this->lang->line('items_older_than_a_week'), 'two-weeks'=>$this->lang->line('items_older_than_two_weeks') ), set_value('age'), 'id="age" class="select required numeric"'); ?>
	</p>

	<p>
	<button type="submit"><?php echo $this->lang->line('send'); ?></button>
	</p>
	<?php echo form_close(); ?>
</div>
