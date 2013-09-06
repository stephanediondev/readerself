<main>
	<section>
		<section>
	<h1><i class="icon icon-group"></i><?php echo $this->lang->line('explore'); ?> (<?php echo $position; ?>)</h1>

	<?php echo form_open(current_url()); ?>
	<div class="filters">
		<div>
			<?php echo form_label($this->lang->line('title'), 'explore_fed_title'); ?>
			<?php echo form_input($this->router->class.'_explore_fed_title', set_value($this->router->class.'_explore_fed_title', $this->session->userdata($this->router->class.'_explore_fed_title')), 'id="subscriptions_fed_title" class="inputtext"'); ?>
		</div>
		<div>
			<button type="submit"><?php echo $this->lang->line('send'); ?></button>
		</div>
	</div>
	<?php echo form_close(); ?>
	<?php if($feeds) { ?>
	<table class="table table-condensed table-hover">
		<thead>
		<tr>
		<?php $i = 0; ?>
		<?php $this->reader_library->display_column($this->router->class.'_explore', $columns[$i++], $this->lang->line('title')); ?>
		<?php $this->reader_library->display_column($this->router->class.'_explore', $columns[$i++], $this->lang->line('description')); ?>
		<?php $this->reader_library->display_column($this->router->class.'_explore', $columns[$i++], $this->lang->line('url')); ?>
		<th><?php echo $this->lang->line('image'); ?></th>
		<?php $this->reader_library->display_column($this->router->class.'_explore', $columns[$i++], $this->lang->line('subscribers')); ?>
		<th>&nbsp;</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach($feeds as $fed) { ?>
		<tr>
		<td><?php echo $fed->fed_title; ?></a></td>
		<td><?php echo $fed->fed_description; ?></td>
		<td><?php echo $fed->fed_link; ?></td>
		<td><?php if($fed->fed_image) { ?><img src="<?php echo $fed->fed_image; ?>" alt=""><?php } else { ?>-<?php } ?></td>
		<td><?php echo $fed->subscribers; ?></td>
		<th>
		<ul class="actions">
			<li><a href="<?php echo base_url(); ?>explore/add/<?php echo $fed->fed_id; ?>"><i class="icon icon-plus"></i><?php echo $this->lang->line('add'); ?></a></li>
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
		</section>
	</section>
</main>
