	<nav>
		<ul class="actions">
			<li><a href="<?php echo base_url(); ?>folders/read/<?php echo $flr->flr_id; ?>"><i class="icon icon-step-backward"></i><?php echo $this->lang->line('back'); ?></a></li>
		</ul>
	</nav>
</header>
<main>
	<section>
		<section>
		<article class="title">
			<h2><i class="icon icon-folder-close"></i><?php echo $this->lang->line('folders'); ?></h2>
		</article>

		<article<?php if($flr->flr_direction) { ?> dir="<?php echo $flr->flr_direction; ?>"<?php } ?>>
			<ul class="actions">
				<li><a href="<?php echo base_url(); ?>folders/update/<?php echo $flr->flr_id; ?>"><i class="icon icon-wrench"></i><?php echo $this->lang->line('update'); ?></a></li>
			</ul>
			<h2><i class="icon icon-folder-close"></i><?php echo $flr->flr_title; ?></h2>
			<ul class="item-details">
				<li><i class="icon icon-bookmark"></i><?php echo $flr->subscriptions; ?> <?php if($flr->subscriptions > 1) { ?><?php echo mb_strtolower($this->lang->line('subscriptions')); ?><?php } else { ?><?php echo mb_strtolower($this->lang->line('subscription')); ?><?php } ?></li>
			</ul>
		</article>

	<h2><i class="icon icon-trash"></i><?php echo $this->lang->line('delete'); ?></h2>

	<?php echo validation_errors(); ?>

	<?php echo form_open(current_url()); ?>

	<p>
	<?php echo form_label($this->lang->line('confirm').' *', 'confirm'); ?>
	<?php echo form_checkbox('confirm', '1', FALSE, 'id="confirm" class="inputcheckbox"'); ?>
	</p>
	<p>
	<button type="submit"><?php echo $this->lang->line('send'); ?></button>
	</p>

	<?php echo form_close(); ?>

		</section>
	</section>
</main>
