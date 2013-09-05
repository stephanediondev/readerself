<div id="content">
	<div class="cell">
		<ul class="actions">
			<li><a href="<?php echo base_url(); ?>subscriptions"><i class="icon icon-step-backward"></i><?php echo $this->lang->line('back'); ?></a></li>
			<li><a href="<?php echo base_url(); ?>subscriptions/update/<?php echo $sub->sub_id; ?>"><i class="icon icon-pencil"></i><?php echo $this->lang->line('update'); ?></a></li>
			<li><a href="<?php echo base_url(); ?>subscriptions/delete/<?php echo $sub->sub_id; ?>"><i class="icon icon-trash"></i><?php echo $this->lang->line('delete'); ?></a></li>
		</ul>
		<h2><a href="<?php echo $sub->fed_link; ?>" target="_blank"><i class="icon icon-rss"></i><?php echo $sub->fed_title; ?></a></h2>
		<ul class="item-details">
			<?php if($sub->fed_lastcrawl) { ?><li><i class="icon icon-truck"></i><?php echo $sub->fed_lastcrawl; ?></li><?php } ?>
			<?php if($this->config->item('folders')) { ?>
				<li><?php if($sub->flr_title) { ?><a href="<?php echo base_url(); ?>folders/read/<?php echo $sub->flr_id; ?>"><i class="icon icon-folder-close"></i><?php echo $sub->flr_title; ?></a><?php } else { ?><i class="icon icon-folder-close"></i><em><?php echo $this->lang->line('no_folder'); ?></em><?php } ?></li>
			<?php } ?>
			<li><i class="icon icon-group"></i><?php echo $sub->subscribers; ?> <?php if($sub->subscribers > 1) { ?><?php echo mb_strtolower($this->lang->line('subscribers')); ?><?php } else { ?><?php echo mb_strtolower($this->lang->line('subscriber')); ?><?php } ?></li>
			<?php if($sub->fed_lasterror) { ?><li class="error"><i class="icon icon-bell"></i><?php echo $sub->fed_lasterror; ?></li><?php } ?>
			<li class="error"><a href="<?php echo $sub->fed_url; ?>" target="_blank"><i class="icon icon-external-link"></i><?php echo $sub->fed_url; ?></a></li>
		</ul>
		<div class="item-content">
			<?php echo $sub->fed_description; ?>
		</div>
	</div>

	<?php if($sub->fed_image) { ?>
		<p><img src="<?php echo $sub->fed_image; ?>" alt=""></p>
	<?php } ?>

	<?php if($sub->sub_title) { ?>
		<p>
		<span class="label"><?php echo $this->lang->line('title_alternative'); ?></span>
		<?php echo $sub->sub_title; ?>
		</p>
	<?php } ?>

	<h1><i class="icon icon-bar-chart"></i><?php echo $this->lang->line('statistics'); ?></h1>
	<div style="margin-top:20px;">
		<?php echo $tables; ?>
		<p>*<?php echo $this->lang->line('last_30_days'); ?></p>
	</div>

</div>
