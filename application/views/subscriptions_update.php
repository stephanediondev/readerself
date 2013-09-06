<div id="actions-main">
	<ul class="actions">
		<li><a href="<?php echo base_url(); ?>subscriptions/read/<?php echo $sub->sub_id; ?>"><i class="icon icon-step-backward"></i><?php echo $this->lang->line('back'); ?></a></li>
	</ul>
</div>
<main>
	<section>
		<section>
	<?php echo validation_errors(); ?>

	<?php echo form_open(current_url()); ?>

	<article class="cell title">
		<h2><i class="icon icon-rss"></i><?php echo $this->lang->line('subscriptions'); ?></h2>
	</article>

	<article class="cell">
		<ul class="actions">
			<li><a href="<?php echo base_url(); ?>subscriptions/delete/<?php echo $sub->sub_id; ?>"><i class="icon icon-trash"></i><?php echo $this->lang->line('delete'); ?></a></li>
		</ul>
		<h2><i class="icon icon-rss"></i><?php echo $sub->fed_title; ?><?php if($sub->sub_title) { ?> / <em><?php echo $sub->sub_title; ?></em><?php } ?></h2>
		<ul class="item-details">
			<?php if($sub->fed_lastcrawl) { ?><li><i class="icon icon-truck"></i><?php echo $sub->fed_lastcrawl; ?></li><?php } ?>
			<?php if($this->config->item('folders')) { ?>
				<li><?php if($sub->flr_title) { ?><a href="<?php echo base_url(); ?>folders/read/<?php echo $sub->flr_id; ?>"><i class="icon icon-folder-close"></i><?php echo $sub->flr_title; ?></a><?php } else { ?><i class="icon icon-folder-close"></i><em><?php echo $this->lang->line('no_folder'); ?></em><?php } ?></li>
			<?php } ?>
			<li><i class="icon icon-group"></i><?php echo $sub->subscribers; ?> <?php if($sub->subscribers > 1) { ?><?php echo mb_strtolower($this->lang->line('subscribers')); ?><?php } else { ?><?php echo mb_strtolower($this->lang->line('subscriber')); ?><?php } ?></li>
			<?php if($sub->fed_lasterror) { ?><li class="block"><i class="icon icon-bell"></i><?php echo $sub->fed_lasterror; ?></li><?php } ?>
			<li class="block"><a href="<?php echo $sub->fed_url; ?>" target="_blank"><i class="icon icon-external-link"></i><?php echo $sub->fed_url; ?></a></li>
		</ul>
		<div class="item-content">
			<?php echo $sub->fed_description; ?>
		</div>
	</article>

	<h2><i class="icon icon-pencil"></i><?php echo $this->lang->line('update'); ?></h2>

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
		</section>
	</section>
</main>
