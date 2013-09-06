<aside>
	<ul class="menu">
		<li><label for="subscriptions_fed_title"><i class="icon icon-search"></i><?php echo $this->lang->line('search'); ?></label></li>
		<li><?php echo form_open(current_url()); ?>
			<?php echo form_input($this->router->class.'_subscriptions_fed_title', set_value($this->router->class.'_subscriptions_fed_title', $this->session->userdata($this->router->class.'_subscriptions_fed_title')), 'id="subscriptions_fed_title" class="inputtext"'); ?>
			<?php echo form_close(); ?></li>
	</ul>
</aside>
<div id="actions-main">
	<ul class="actions">
		<li><a href="<?php echo base_url(); ?>import"><i class="icon icon-download-alt"></i><?php echo $this->lang->line('import'); ?></a></li>
		<li><a href="<?php echo base_url(); ?>export"><i class="icon icon-upload-alt"></i><?php echo $this->lang->line('export'); ?></a></li>
	</ul>
</div>
<main>
	<section>
		<section>
		<article class="cell">
			<h2><i class="icon icon-rss"></i><?php echo $this->lang->line('subscriptions'); ?> (<?php echo $position; ?>)</h2>
		</article>
	<?php if($subscriptions) { ?>
		<?php foreach($subscriptions as $sub) { ?>
		<article class="cell">
			<ul class="actions">
				<li><a href="<?php echo base_url(); ?>subscriptions/update/<?php echo $sub->sub_id; ?>"><i class="icon icon-pencil"></i><?php echo $this->lang->line('update'); ?></a></li>
				<li><a href="<?php echo base_url(); ?>subscriptions/delete/<?php echo $sub->sub_id; ?>"><i class="icon icon-trash"></i><?php echo $this->lang->line('delete'); ?></a></li>
			</ul>
			<h2><a href="<?php echo base_url(); ?>subscriptions/read/<?php echo $sub->sub_id; ?>"><i class="icon icon-rss"></i><?php echo $sub->fed_title; ?><?php if($sub->sub_title) { ?> / <em><?php echo $sub->sub_title; ?></em><?php } ?></a></h2>
			<ul class="item-details">
				<?php if($sub->fed_lastcrawl) { ?><li><i class="icon icon-truck"></i><?php echo $sub->fed_lastcrawl; ?></li><?php } ?>
				<?php if($this->config->item('folders')) { ?>
					<li><?php if($sub->flr_title) { ?><a href="<?php echo base_url(); ?>folders/read/<?php echo $sub->flr_id; ?>"><i class="icon icon-folder-close"></i><?php echo $sub->flr_title; ?></a><?php } else { ?><i class="icon icon-folder-close"></i><em><?php echo $this->lang->line('no_folder'); ?></em><?php } ?></li>
				<?php } ?>
				<li><i class="icon icon-group"></i><?php echo $sub->subscribers; ?> <?php if($sub->subscribers > 1) { ?><?php echo mb_strtolower($this->lang->line('subscribers')); ?><?php } else { ?><?php echo mb_strtolower($this->lang->line('subscriber')); ?><?php } ?></li>
				<?php if($sub->fed_lasterror) { ?><li class="block"><i class="icon icon-bell"></i><?php echo $sub->fed_lasterror; ?></li><?php } ?>
			</ul>
			<div class="item-content">
				<?php echo $sub->fed_description; ?>
				<?php //echo $sub->fed_link; ?>
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
