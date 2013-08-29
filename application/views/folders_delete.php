<div id="content">
	<h1><i class="icon icon-folder-close"></i><?php echo $folder->flr_title; ?></h1>
	<ul class="actions">
		<li><a href="<?php echo base_url(); ?>folders"><i class="icon icon-step-backward"></i><?php echo $this->lang->line('back'); ?></a></li>
	</ul>
	<?php echo form_open(current_url()); ?>
	<h2><?php echo $this->lang->line('delete'); ?></h2>
	<?php echo validation_errors(); ?>
	<p>
	<?php echo form_label($this->lang->line('confirm').' *', 'confirm'); ?>
	<?php echo form_checkbox('confirm', '1', FALSE, 'id="confirm" class="inputcheckbox"'); ?>
	</p>
	<p>
	<button type="submit"><?php echo $this->lang->line('send'); ?></button>
	</p>
	<?php echo form_close(); ?>
</div>
