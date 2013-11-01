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
				<li><a href="<?php echo base_url(); ?>folders/delete/<?php echo $flr->flr_id; ?>"><i class="icon icon-trash"></i><?php echo $this->lang->line('delete'); ?></a></li>
			</ul>
			<h2><i class="icon icon-folder-close"></i><?php echo $flr->flr_title; ?></h2>
			<ul class="item-details">
				<li><i class="icon icon-bookmark"></i><?php echo $flr->subscriptions; ?> subscription(s)</li>
				<li><i class="icon icon-star"></i><?php echo $flr->starred_items; ?> starred item(s)</li>
				<li><i class="icon icon-heart"></i><?php echo $flr->shared_items; ?> shared item(s)</li>
			</ul>
		</article>

	<h2><i class="icon icon-wrench"></i><?php echo $this->lang->line('update'); ?></h2>

	<?php echo validation_errors(); ?>

	<?php echo form_open(current_url()); ?>

	<p>
	<?php echo form_label($this->lang->line('title'), 'flr_title'); ?>
	<?php echo form_input('flr_title', set_value('flr_title', $flr->flr_title), 'id="flr_title" class="inputtext required"'); ?>
	</p>

	<p>
	<?php echo form_label($this->lang->line('direction'), 'direction'); ?>
	<?php echo form_dropdown('direction', array('' => '-', 'ltr' => $this->lang->line('direction_ltr'), 'rtl' => $this->lang->line('direction_rtl')), set_value('direction', $flr->flr_direction), 'id="direction" class="select numeric"'); ?>
	</p>

	<p>
	<button type="submit"><?php echo $this->lang->line('send'); ?></button>
	</p>

	<?php echo form_close(); ?>

		</section>
	</section>
</main>
