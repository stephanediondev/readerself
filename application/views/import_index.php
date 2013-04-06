<div id="content">
	<h1><i class="icon icon-download-alt"></i><?php echo $this->lang->line('import'); ?></h1>

	<?php echo validation_errors(); ?>

	<?php echo form_open_multipart(current_url()); ?>
	<input type="hidden" name="hidden">

	<p>
	<?php echo form_label($this->lang->line('opml_file'), 'file'); ?>
	<?php echo form_upload('file', false, 'id="file" class="required"'); ?>
	</p>

	<p>
	<button type="submit"><?php echo $this->lang->line('send'); ?></button>
	</p>

	<?php echo form_close(); ?>
</div>
