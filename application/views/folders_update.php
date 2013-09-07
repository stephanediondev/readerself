<div id="actions-main">
	<ul class="actions">
		<li><a href="<?php echo base_url(); ?>folders/read/<?php echo $folder->flr_id; ?>"><i class="icon icon-step-backward"></i><?php echo $this->lang->line('back'); ?></a></li>
	</ul>
</div>
<main>
	<section>
		<section>
		<article class="cell title">
			<h2><i class="icon icon-folder-close"></i><?php echo $this->lang->line('folders'); ?></h2>
		</article>

		<article class="cell">
			<ul class="actions">
				<li><a href="<?php echo base_url(); ?>folders/delete/<?php echo $folder->flr_id; ?>"><i class="icon icon-trash"></i><?php echo $this->lang->line('delete'); ?></a></li>
			</ul>
			<h2><i class="icon icon-folder-close"></i><?php echo $folder->flr_title; ?></h2>
			<ul class="item-details">
				<li><i class="icon icon-rss"></i><?php echo $folder->subscriptions; ?> <?php if($folder->subscriptions > 1) { ?><?php echo mb_strtolower($this->lang->line('subscriptions')); ?><?php } else { ?><?php echo mb_strtolower($this->lang->line('subscription')); ?><?php } ?></li>
			</ul>
		</article>

	<h2><i class="icon icon-pencil"></i><?php echo $this->lang->line('update'); ?></h2>

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

		</section>
	</section>
</main>
