<aside>
	<ul class="menu">
		<li><label for="explore_fed_title"><i class="icon icon-search"></i><?php echo $this->lang->line('search'); ?></label></li>
		<li><?php echo form_open(current_url()); ?>
			<?php echo form_input($this->router->class.'_explore_fed_title', set_value($this->router->class.'_explore_fed_title', $this->session->userdata($this->router->class.'_explore_fed_title')), 'id="explore_fed_title" class="inputtext"'); ?>
			<?php echo form_close(); ?></li>
	</ul>
</aside>
<main>
	<section>
		<section>
	<article class="cell title">
		<h2><i class="icon icon-cloud"></i><?php echo $this->lang->line('explore'); ?> (<?php echo $position; ?>)</h2>
	</article>

	<?php if($feeds) { ?>
		<?php foreach($feeds as $fed) { ?>
		<article class="cell">
			<ul class="actions">
				<li><a href="<?php echo base_url(); ?>explore/add/<?php echo $fed->fed_id; ?>"><i class="icon icon-plus"></i><?php echo $this->lang->line('add'); ?></a></li>
			</ul>
			<h2><?php echo $fed->fed_title; ?></h2>
			<ul class="item-details">
				<?php if($fed->fed_lastcrawl) { ?><li><i class="icon icon-truck"></i><?php echo $fed->fed_lastcrawl; ?></li><?php } ?>
				<li><i class="icon icon-group"></i><?php echo $fed->subscribers; ?> <?php if($fed->subscribers > 1) { ?><?php echo mb_strtolower($this->lang->line('subscribers')); ?><?php } else { ?><?php echo mb_strtolower($this->lang->line('subscriber')); ?><?php } ?></li>
				<?php if($fed->fed_lasterror) { ?><li class="block"><i class="icon icon-bell"></i><?php echo $fed->fed_lasterror; ?></li><?php } ?>
			</ul>
			<div class="item-content">
				<?php echo $fed->fed_description; ?>
				<?php //echo $fed->fed_link; ?>
			</div>
		</article>
		<?php } ?>
		<div class="paging">
			<?php echo $pagination; ?>
		</div>
	<?php } ?>
		</section>
	</section>
</main>
