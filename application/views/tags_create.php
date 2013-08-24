<div id="content">
	<h1><i class="icon icon-folder-close"></i><?php echo $this->lang->line('add_tag'); ?></h1>
	<ul class="actions">
		<li><a href="<?php echo base_url(); ?>tags"><i class="icon icon-step-backward"></i><?php echo $this->lang->line('back'); ?></a></li>
	</ul>
	<?php echo validation_errors(); ?>

	<?php echo form_open(current_url()); ?>

	<p>
	<?php echo form_label($this->lang->line('title'), 'tag_title'); ?>
	<?php echo form_input('tag_title', set_value('tag_title'), 'id="tag_title" class="input-xlarge required"'); ?>
	</p>

	<p>
	<button type="submit"><?php echo $this->lang->line('send'); ?></button>
	</p>
	<?php echo form_close(); ?>
</div>
