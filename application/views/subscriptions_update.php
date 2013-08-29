<div id="content">
	<h1><i class="icon icon-rss"></i><?php echo $sub->fed_title; ?></h1>
	<ul class="actions">
		<li><a href="<?php echo base_url(); ?>subscriptions"><i class="icon icon-step-backward"></i><?php echo $this->lang->line('back'); ?></a></li>
	</ul>

	<?php echo validation_errors(); ?>

	<?php echo form_open(current_url()); ?>

	<p>
	<span class="label"><?php echo $this->lang->line('title'); ?></span>
	<?php echo $sub->fed_title; ?>
	</p>

	<p>
	<span class="label"><?php echo $this->lang->line('url'); ?></span>
	<?php echo $sub->fed_link; ?>
	</p>

	<p>
	<span class="label"><?php echo $this->lang->line('url_site'); ?></span>
	<?php echo $sub->fed_url; ?>
	</p>

	<?php if($sub->fed_description) { ?>
		<p>
		<span class="label"><?php echo $this->lang->line('description'); ?></span>
		<?php echo $sub->fed_description; ?>
		</p>
	<?php } ?>

	<?php if($sub->fed_lasterror) { ?>
		<p>
		<span class="label"><?php echo $this->lang->line('error'); ?></span>
		<?php echo $sub->fed_lasterror; ?>
		</p>
	<?php } ?>

	<p>
	<?php echo form_label($this->lang->line('title_alternative'), 'sub_title'); ?>
	<?php echo form_input('sub_title', set_value('sub_title', $sub->sub_title), 'id="sub_title" class="inputtext required"'); ?>
	</p>

	<p>
	<?php echo form_label($this->lang->line('folder'), 'folder'); ?>
	<?php echo form_dropdown('folder', $folders, set_value('folder', $sub->flr_id), 'id="folder" class="select required numeric"'); ?>
	</p>

	<p>
	<button type="submit"><?php echo $this->lang->line('send'); ?></button>
	</p>
	<?php echo form_close(); ?>
</div>
