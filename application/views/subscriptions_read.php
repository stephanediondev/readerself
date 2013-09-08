	<nav>
		<ul class="actions">
			<li><a href="<?php echo base_url(); ?>subscriptions"><i class="icon icon-step-backward"></i><?php echo $this->lang->line('back'); ?></a></li>
		</ul>
	</nav>
</header>
<aside>
	<ul>
		<?php if($errors) { ?>
		<li><h2><i class="icon icon-bell"></i><?php echo $this->lang->line('errors'); ?></h2></li>
			<?php foreach($errors as $error) { ?>
			<li><a href="<?php echo base_url(); ?>subscriptions/read/<?php echo $error->sub_id; ?>"><i class="icon icon-rss"></i><?php echo $error->fed_title; ?></a></li>
			<?php } ?>
		<?php } ?>
		<?php if($last_added) { ?>
		<li><h2><i class="icon icon-bookmark-empty"></i><?php echo $this->lang->line('last_added'); ?></h2></li>
			<?php foreach($last_added as $added) { ?>
			<li><a href="<?php echo base_url(); ?>subscriptions/read/<?php echo $added->sub_id; ?>"><i class="icon icon-rss"></i><?php echo $added->fed_title; ?></a></li>
			<?php } ?>
		<?php } ?>
	</ul>
</aside>
<main>
	<section>
		<section>

	<article class="cell title">
		<h2><i class="icon icon-rss"></i><?php echo $this->lang->line('subscriptions'); ?></h2>
	</article>

	<article class="cell">
		<ul class="actions">
			<li><a href="<?php echo base_url(); ?>subscriptions/update/<?php echo $sub->sub_id; ?>"><i class="icon icon-pencil"></i><?php echo $this->lang->line('update'); ?></a></li>
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

	<article class="cell">
	<h2><i class="icon icon-bar-chart"></i><?php echo $this->lang->line('statistics'); ?></h2>
	<ul class="item-details">
		<li>*<?php echo $this->lang->line('last_30_days'); ?></li>
	</ul>
	<div class="item-content">
		<?php echo $tables; ?>
	</div>
	</article>
		</section>
	</section>
</main>
