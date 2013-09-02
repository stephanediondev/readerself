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
	<table class="table table-condensed table-hover">
		<thead>
		<tr>
		<?php $i = 0; ?>
		<?php $this->reader_library->display_column($this->router->class.'_subscriptions', $columns[$i++], $this->lang->line('title')); ?>
		<?php $this->reader_library->display_column($this->router->class.'_subscriptions', $columns[$i++], $this->lang->line('description')); ?>
		<?php $this->reader_library->display_column($this->router->class.'_subscriptions', $columns[$i++], $this->lang->line('url')); ?>
		<th><?php echo $this->lang->line('image'); ?></th>
		<?php $this->reader_library->display_column($this->router->class.'_subscriptions', $columns[$i++], $this->lang->line('subscribers')); ?>
		<?php if($this->config->item('folders')) { ?>
			<?php $this->reader_library->display_column($this->router->class.'_subscriptions', $columns[$i++], $this->lang->line('folder')); ?>
		<?php } ?>
		<?php $this->reader_library->display_column($this->router->class.'_subscriptions', $columns[$i++], $this->lang->line('last_crawl')); ?>
		<th>&nbsp;</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach($subscriptions as $sub) { ?>
		<tr>
		<td><a href="<?php echo base_url(); ?>subscriptions/read/<?php echo $sub->sub_id; ?>"><?php echo $sub->fed_title; ?></a><?php if($sub->sub_title) { ?><br> <em><?php echo $sub->sub_title; ?></em><?php } ?><?php if($sub->fed_lasterror) { ?> <i class="icon icon-bell"></i><?php } ?></td>
		<td><?php echo $sub->fed_description; ?></td>
		<td><?php echo $sub->fed_link; ?></td>
		<td><?php if($sub->fed_image) { ?><img src="<?php echo $sub->fed_image; ?>" alt=""><?php } else { ?>-<?php } ?></td>
		<td><?php echo $sub->subscribers; ?></td>
		<?php if($this->config->item('folders')) { ?>
			<td><?php if($sub->flr_title) { ?><a href="<?php echo base_url(); ?>folders/read/<?php echo $sub->flr_id; ?>"><?php echo $sub->flr_title; ?></a><?php } else { ?><em><?php echo $this->lang->line('no_folder'); ?></em><?php } ?></td>
		<?php } ?>
		<td><?php echo $sub->fed_lastcrawl; ?></td>
		<th>
		<ul class="actions">
			<li><a href="<?php echo base_url(); ?>subscriptions/update/<?php echo $sub->sub_id; ?>"><i class="icon icon-pencil"></i><?php echo $this->lang->line('update'); ?></a></li>
			<li><a href="<?php echo base_url(); ?>subscriptions/delete/<?php echo $sub->sub_id; ?>"><i class="icon icon-trash"></i><?php echo $this->lang->line('delete'); ?></a></li>
		</ul>
		</th>
		</tr>
		<?php } ?>
		</tbody>
	</table>
	<div class="paging">
		<?php echo $pagination; ?>
	</div>
	<?php } ?>
</div>
