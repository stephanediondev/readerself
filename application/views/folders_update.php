<div id="content">
	<h1><i class="icon icon-folder-close"></i><?php echo $folder->flr_title; ?></h1>

	<ul class="actions">
		<li><a href="<?php echo base_url(); ?>folders"><i class="icon icon-step-backward"></i><?php echo $this->lang->line('back'); ?></a></li>
	</ul>

	<?php echo validation_errors(); ?>

	<?php echo form_open(current_url()); ?>

	<p>
	<?php echo form_label($this->lang->line('title'), 'flr_title'); ?>
	<?php echo form_input('flr_title', set_value('flr_title', $folder->flr_title), 'id="flr_title" class="inputtext required"'); ?>
	</p>

	<p>
	<button type="submit"><?php echo $this->lang->line('send'); ?></button>
	</p>
	<?php echo form_close(); ?>
</div>
