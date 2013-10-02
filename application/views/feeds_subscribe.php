	<nav>
		<ul class="actions">
			<li><a href="<?php echo base_url(); ?>feeds"><i class="icon icon-step-backward"></i><?php echo $this->lang->line('back'); ?></a></li>
		</ul>
	</nav>
</header>
<main>
	<section>
		<section>
	<article class="title">
		<h2><i class="icon icon-rss"></i><?php echo $this->lang->line('feeds'); ?></h2>
	</article>

	<article<?php if($fed->fed_direction) { ?> dir="<?php echo $fed->fed_direction; ?>"<?php } ?>>
		<h2><i class="icon icon-rss"></i><?php echo $fed->fed_title; ?></h2>
		<ul class="item-details">
			<?php if($fed->fed_lastcrawl) { ?><li><i class="icon icon-truck"></i><?php echo $fed->fed_lastcrawl; ?></li><?php } ?>
			<li><i class="icon icon-group"></i><?php echo $fed->subscribers; ?> <?php if($fed->subscribers > 1) { ?><?php echo mb_strtolower($this->lang->line('subscribers')); ?><?php } else { ?><?php echo mb_strtolower($this->lang->line('subscriber')); ?><?php } ?></li>
			<li class="hide-phone"><a href="<?php echo $fed->fed_link; ?>" target="_blank"><i class="icon icon-gear"></i><?php echo $fed->fed_link; ?></a></li>
			<?php if($fed->fed_url) { ?><li class="block"><a href="<?php echo $fed->fed_url; ?>" target="_blank"><i class="icon icon-external-link"></i><?php echo $fed->fed_url; ?></a></li><?php } ?>
			<?php if($this->config->item('tags') && $fed->categories) { ?>
			<li class="block hide-phone"><i class="icon icon-tags"></i><?php echo implode(', ', $fed->categories); ?></li>
			<?php } ?>
			<?php if($fed->fed_lasterror) { ?><li class="block"><i class="icon icon-bell"></i><?php echo $fed->fed_lasterror; ?></li><?php } ?>
		</ul>
		<div class="item-content">
			<?php echo $fed->fed_description; ?>
		</div>
	</article>

	<h2><i class="icon icon-bookmark-empty"></i><?php echo $this->lang->line('subscribe'); ?></h2>

	<?php echo validation_errors(); ?>

	<?php echo form_open(current_url()); ?>

	<?php if($this->config->item('folders')) { ?>
		<p>
		<?php echo form_label($this->lang->line('folder'), 'folder'); ?>
		<?php echo form_dropdown('folder', $folders, set_value('folder', ''), 'id="folder" class="select required numeric"'); ?>
		</p>
	<?php } ?>

	<p>
	<?php echo form_label($this->lang->line('priority'), 'priority'); ?>
	<?php echo form_dropdown('priority', array(0 => $this->lang->line('no'), 1 => $this->lang->line('yes')), set_value('priority', 0), 'id="priority" class="select numeric"'); ?>
	</p>

	<p>
	<?php echo form_label($this->lang->line('direction'), 'direction'); ?>
	<?php echo form_dropdown('direction', array('' => '-', 'ltr' => $this->lang->line('direction_ltr'), 'rtl' => $this->lang->line('direction_rtl')), set_value('direction', $fed->fed_direction), 'id="direction" class="select numeric"'); ?>
	</p>

	<p>
	<button type="submit"><?php echo $this->lang->line('send'); ?></button>
	</p>
	<?php echo form_close(); ?>
		</section>
	</section>
</main>
