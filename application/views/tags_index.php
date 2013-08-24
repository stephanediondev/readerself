<div id="content">
	<h1><i class="icon icon-folder-close"></i><?php echo $this->lang->line('tags'); ?> (<?php echo $position; ?>)</h1>
	<ul class="actions">
		<li><a href="<?php echo base_url(); ?>tags/create"><i class="icon icon-plus"></i><?php echo $this->lang->line('add'); ?></a></li>
	</ul>
	<?php echo form_open(current_url()); ?>
	<div class="filters">
		<div>
			<?php echo form_label($this->lang->line('title'), 'tags_tag_title'); ?>
			<?php echo form_input($this->router->class.'_tags_tag_title', set_value($this->router->class.'_tags_tag_title', $this->session->userdata($this->router->class.'_tags_tag_title')), 'id="tags_tag_title" class="inputtext"'); ?>
		</div>
		<div>
			<button type="submit"><?php echo $this->lang->line('send'); ?></button>
		</div>
	</div>
	<?php echo form_close(); ?>
	<?php if($tags) { ?>
	<table class="table table-condensed table-hover">
		<thead>
		<tr>
		<?php $i = 0; ?>
		<?php $this->reader_library->display_column($this->router->class.'_tags', $columns[$i++], $this->lang->line('title')); ?>
		<?php $this->reader_library->display_column($this->router->class.'_tags', $columns[$i++], $this->lang->line('subscriptions')); ?>
		<th>&nbsp;</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach($tags as $tag) { ?>
		<tr>
		<td><?php echo $tag->tag_title; ?></td>
		<td><?php echo $tag->subscriptions; ?></td>
		<th>
		<ul class="actions">
			<li><a href="<?php echo base_url(); ?>tags/update/<?php echo $tag->tag_id; ?>"><i class="icon icon-pencil"></i><?php echo $this->lang->line('update'); ?></a></li>
			<li><a href="<?php echo base_url(); ?>tags/delete/<?php echo $tag->tag_id; ?>"><i class="icon icon-trash"></i><?php echo $this->lang->line('delete'); ?></a></li>
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
