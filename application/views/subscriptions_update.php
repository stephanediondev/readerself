<div id="content">
	<h1><i class="icon icon-rss"></i><?php echo $sub->fed_title; ?></h1>

	<?php if($sub->fed_description) { ?><p><?php echo $sub->fed_description; ?></p><?php } ?>
	<ul>
	<li><a class="index" href="<?php echo base_url(); ?>subscriptions"><?php echo $this->lang->line('subscriptions'); ?></a></li>
	</ul>

	<?php echo validation_errors(); ?>

	<?php echo form_open(current_url()); ?>

	<p>
	<?php echo form_label($this->lang->line('tag'), 'tag'); ?>
	<?php echo form_dropdown('tag', $tags, set_value('tag', $sub->tag_id), 'id="tag" class="select required numeric"'); ?>
	</p>

	<p>
	<button type="submit"><?php echo $this->lang->line('send'); ?></button>
	</p>
	<?php echo form_close(); ?>
</div>
