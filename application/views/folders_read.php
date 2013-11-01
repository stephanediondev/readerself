	<nav>
		<ul class="actions">
			<li><a href="<?php echo base_url(); ?>folders"><i class="icon icon-step-backward"></i><?php echo $this->lang->line('back'); ?></a></li>
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
				<li><a href="<?php echo base_url(); ?>folders/delete/<?php echo $flr->flr_id; ?>"><i class="icon icon-trash"></i><?php echo $this->lang->line('delete'); ?></a></li>
			</ul>
			<h2><i class="icon icon-folder-close"></i><?php echo $flr->flr_title; ?></h2>
			<ul class="item-details">
				<li><i class="icon icon-bookmark"></i><?php echo $flr->subscriptions; ?> subscription(s)</li>
				<li><i class="icon icon-star"></i><?php echo $flr->starred_items; ?> starred item(s)</li>
				<li><i class="icon icon-heart"></i><?php echo $flr->shared_items; ?> shared item(s)</li>
			</ul>
		</article>

	<article>
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
