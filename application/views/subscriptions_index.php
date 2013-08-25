<div id="content">
	<h1><i class="icon icon-rss"></i><?php echo $this->lang->line('subscriptions'); ?> (<?php echo $position; ?>)</h1>

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
		<?php if($this->config->item('tags')) { ?>
			<?php $this->reader_library->display_column($this->router->class.'_subscriptions', $columns[$i++], $this->lang->line('tag')); ?>
		<?php } ?>
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
		<?php if($this->config->item('tags')) { ?>
			<td><?php if($sub->tag_title) { ?><?php echo $sub->tag_title; ?><?php } else { ?><em><?php echo $this->lang->line('no_tag'); ?></em><?php } ?></td>
		<?php } ?>
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
