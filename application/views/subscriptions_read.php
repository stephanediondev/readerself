<div id="content">
	<h1><i class="icon icon-rss"></i><?php echo $sub->fed_title; ?></h1>
	<ul class="actions">
		<li><a href="<?php echo base_url(); ?>subscriptions"><i class="icon icon-step-backward"></i><?php echo $this->lang->line('back'); ?></a></li>
		<li><a href="<?php echo base_url(); ?>subscriptions/update/<?php echo $sub->sub_id; ?>"><i class="icon icon-pencil"></i><?php echo $this->lang->line('update'); ?></a></li>
		<li><a href="<?php echo base_url(); ?>subscriptions/delete/<?php echo $sub->sub_id; ?>"><i class="icon icon-trash"></i><?php echo $this->lang->line('delete'); ?></a></li>
	</ul>

	<p>
	<span class="label"><?php echo $this->lang->line('title'); ?></span>
	<?php echo $sub->fed_title; ?>
	</p>

	<p>
	<span class="label"><?php echo $this->lang->line('folder'); ?></span>
	<?php if($sub->flr_id) { ?>
		<a href="<?php echo base_url(); ?>folders/read/<?php echo $sub->flr_id; ?>"><?php echo $sub->flr_title; ?></a>
	<?php } else { ?>
		<?php echo $this->lang->line('no_folder'); ?>
	<?php } ?>
	</p>

	<p>
	<span class="label"><?php echo $this->lang->line('subscribers'); ?></span>
	<?php echo $sub->subscribers; ?>
	</p>

	<p>
	<span class="label"><?php echo $this->lang->line('url'); ?></span>
	<?php echo $sub->fed_link; ?>
	</p>

	<p>
	<span class="label"><?php echo $this->lang->line('url_site'); ?></span>
	<?php echo $sub->fed_url; ?>
	</p>

	<?php if($sub->fed_image) { ?>
		<p>
		<span class="label"><?php echo $this->lang->line('image'); ?></span>
		<img src="<?php echo $sub->fed_image; ?>" alt="">
		</p>
	<?php } ?>

	<?php if($sub->fed_description) { ?>
		<p>
		<span class="label"><?php echo $this->lang->line('description'); ?></span>
		<?php echo $sub->fed_description; ?>
		</p>
	<?php } ?>

	<?php if($sub->fed_lasterror) { ?>
		<p>
		<span class="label"><?php echo $this->lang->line('error'); ?></span>
		<?php echo $sub->fed_lasterror; ?>
		</p>
	<?php } ?>

	<?php if($sub->fed_lastcrawl) { ?>
		<p>
		<span class="label"><?php echo $this->lang->line('last_crawl'); ?></span>
		<?php echo $sub->fed_lastcrawl; ?>
		</p>
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
