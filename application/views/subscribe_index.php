<div id="modal-display">
	<h1><i class="icon icon-plus"></i><?php echo $this->lang->line('subscribe'); ?></h1>

	<?php echo validation_errors(); ?>

	<?php echo form_open(current_url()); ?>

	<?php if($error) { ?>
		<p><?php echo $error; ?></p>
	<?php } ?>

	<p>
	<?php echo form_label($this->lang->line('url_feed'), 'url'); ?>
	<?php echo form_input('url', set_value('url'), 'id="url" class="input-xlarge required"'); ?>
	</p>

	<?php if($this->config->item('folders')) { ?>
		<p>
		<?php echo form_label($this->lang->line('folder'), 'folder'); ?>
		<?php echo form_dropdown('folder', $folders, set_value('folder', ''), 'id="folder" class="select required numeric"'); ?>
		</p>
	<?php } ?>

	<p>
	<button type="submit"><?php echo $this->lang->line('send'); ?></button>
	</p>
	<?php echo form_close(); ?>
</div>
