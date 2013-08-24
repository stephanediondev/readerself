<div id="content">
	<h1><i class="icon icon-rss"></i><?php echo $sub->fed_title; ?></h1>
	<?php if($sub->fed_description) { ?><p><?php echo $sub->fed_description; ?></p><?php } ?>
	<ul>
	<li><a class="index" href="<?php echo base_url(); ?>subscriptions"><?php echo $this->lang->line('subscriptions'); ?></a></li>
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
