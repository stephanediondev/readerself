<div id="content">
	<h1><i class="icon icon-folder-close"></i><?php echo $flr->flr_title; ?></h1>
	<ul class="actions">
		<li><a href="<?php echo base_url(); ?>folders"><i class="icon icon-step-backward"></i><?php echo $this->lang->line('back'); ?></a></li>
		<li><a href="<?php echo base_url(); ?>folders/update/<?php echo $flr->flr_id; ?>"><i class="icon icon-pencil"></i><?php echo $this->lang->line('update'); ?></a></li>
		<li><a href="<?php echo base_url(); ?>folders/delete/<?php echo $flr->flr_id; ?>"><i class="icon icon-trash"></i><?php echo $this->lang->line('delete'); ?></a></li>
	</ul>

	<p>
	<span class="label"><?php echo $this->lang->line('title'); ?></span>
	<?php echo $flr->flr_title; ?>
	</p>

	<p>
	<span class="label"><?php echo $this->lang->line('subscriptions'); ?></span>
	<?php echo $flr->subscriptions; ?>
	</p>

	<h1><i class="icon icon-bar-chart"></i><?php echo $this->lang->line('statistics'); ?></h1>
	<div style="margin-top:20px;">
		<?php echo $tables; ?>
		<p>* the last 30 days</p>
	</div>

</div>
