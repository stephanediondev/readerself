<div id="content">
	<h1><i class="icon icon-rss"></i><?php echo $this->lang->line('subscriptions'); ?> (<?php echo $position; ?>)</h1>
	<ul class="actions">
		<li><a href="<?php echo base_url(); ?>import"><i class="icon icon-download-alt"></i><?php echo $this->lang->line('import'); ?></a></li>
		<li><a href="<?php echo base_url(); ?>export"><i class="icon icon-upload-alt"></i><?php echo $this->lang->line('export'); ?></a></li>
	</ul>

	<?php echo form_open(current_url()); ?>
	<div class="filters">
		<div>
			<?php echo form_label($this->lang->line('title'), 'subscriptions_fed_title'); ?>
			<?php echo form_input($this->router->class.'_subscriptions_fed_title', set_value($this->router->class.'_subscriptions_fed_title', $this->session->userdata($this->router->class.'_subscriptions_fed_title')), 'id="subscriptions_fed_title" class="inputtext"'); ?>
		</div>
		<div>
			<button type="submit"><?php echo $this->lang->line('send'); ?></button>
		</div>
	</div>
	<?php echo form_close(); ?>
	<?php if($subscriptions) { ?>
		<div class="paging">
			<?php echo $pagination; ?>
		</div>
		<?php foreach($subscriptions as $sub) { ?>
		<div class="cell">
			<ul class="actions">
				<li><a href="<?php echo base_url(); ?>subscriptions/update/<?php echo $sub->sub_id; ?>"><i class="icon icon-pencil"></i><?php echo $this->lang->line('update'); ?></a></li>
				<li><a href="<?php echo base_url(); ?>subscriptions/delete/<?php echo $sub->sub_id; ?>"><i class="icon icon-trash"></i><?php echo $this->lang->line('delete'); ?></a></li>
			</ul>
			<h2><a href="<?php echo base_url(); ?>subscriptions/read/<?php echo $sub->sub_id; ?>"><i class="icon icon-rss"></i><?php echo $sub->fed_title; ?></a><?php if($sub->sub_title) { ?><br> <em><?php echo $sub->sub_title; ?></em><?php } ?><?php if($sub->fed_lasterror) { ?> <i class="icon icon-bell"></i><?php } ?></h2>
			<ul class="item-details">
				<?php if($sub->fed_lastcrawl) { ?><li><i class="icon icon-truck"></i><?php echo $sub->fed_lastcrawl; ?></li><?php } ?>
				<?php if($this->config->item('folders')) { ?>
					<li><i class="icon icon-folder-close"></i><?php if($sub->flr_title) { ?><a href="<?php echo base_url(); ?>folders/read/<?php echo $sub->flr_id; ?>"><?php echo $sub->flr_title; ?></a><?php } else { ?><em><?php echo $this->lang->line('no_folder'); ?></em><?php } ?></li>
				<?php } ?>
				<li><i class="icon icon-group"></i><?php echo $sub->subscribers; ?> <?php if($sub->subscribers > 1) { ?><?php echo mb_strtolower($this->lang->line('subscribers')); ?><?php } else { ?><?php echo mb_strtolower($this->lang->line('subscriber')); ?><?php } ?></li>
			</ul>
			<div class="item-content">
				<?php echo $sub->fed_description; ?>
				<?php //echo $sub->fed_link; ?>
			</div>
		</div>
		<?php } ?>
		<div class="paging">
			<?php echo $pagination; ?>
		</div>
	<?php } ?>
</div>
