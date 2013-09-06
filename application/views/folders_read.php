<div id="actions-main">
	<ul class="actions">
		<li><a href="<?php echo base_url(); ?>folders"><i class="icon icon-step-backward"></i><?php echo $this->lang->line('back'); ?></a></li>
	</ul>
</div>
<main>
	<section>
		<section>
		<article class="cell">
			<ul class="actions">
				<li><a href="<?php echo base_url(); ?>folders/update/<?php echo $flr->flr_id; ?>"><i class="icon icon-pencil"></i><?php echo $this->lang->line('update'); ?></a></li>
				<li><a href="<?php echo base_url(); ?>folders/delete/<?php echo $flr->flr_id; ?>"><i class="icon icon-trash"></i><?php echo $this->lang->line('delete'); ?></a></li>
			</ul>
			<h2><i class="icon icon-folder-close"></i><?php echo $flr->flr_title; ?></h2>
			<ul class="item-details">
				<li><i class="icon icon-rss"></i><?php echo $flr->subscriptions; ?> <?php if($flr->subscriptions > 1) { ?><?php echo mb_strtolower($this->lang->line('subscriptions')); ?><?php } else { ?><?php echo mb_strtolower($this->lang->line('subscription')); ?><?php } ?></li>
			</ul>
		</article>

	<article class="cell">
	<h2><i class="icon icon-bar-chart"></i><?php echo $this->lang->line('statistics'); ?></h2>
		<?php echo $tables; ?>
		<p>*<?php echo $this->lang->line('last_30_days'); ?></p>
	</article>
		</section>
	</section>
</main>
