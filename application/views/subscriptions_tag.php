<div id="modal-display">
	<h1><i class="icon-rss"></i> <?php echo $sub->fed_title; ?></h1>

	<?php if($sub->fed_description) { ?><p><?php echo $sub->fed_description; ?></p><?php } ?>

	<?php echo validation_errors(); ?>

	<?php echo form_open(current_url()); ?>

	<p>
	<?php echo form_label($this->lang->line('tag'), 'tag'); ?>
	<?php echo form_dropdown('tag', $tags, set_value('tag', $sub->tag_id), 'id="tag" class="select required numeric"'); ?>
	</p>

	<p>
	<button type="submit" class="btn btn-primary"><i class="icon-save"></i> <?php echo $this->lang->line('save'); ?></button>
	</p>
	<?php echo form_close(); ?>
</div>
