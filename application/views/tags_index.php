<div id="content">
	<h1><i class="icon icon-folder-close"></i><?php echo $this->lang->line('tags'); ?> (<span><?php echo count($tags); ?></span>)</h1>

	<ul class="actions">
		<li><a href="<?php echo base_url(); ?>tags/add" class="modal_show"><i class="icon icon-plus"></i><?php echo $this->lang->line('add'); ?>...</a></li>
	</ul>

	<?php if($tags) { ?>
	<table>
	<thead>
	<tr><th><?php echo $this->lang->line('title'); ?></th><th><?php echo $this->lang->line('subscriptions'); ?></th><th>&nbsp;</th></tr>
	</thead>
	<tbody>
	<?php foreach($tags as $tag) { ?>
	<tr id="tag_<?php echo $tag->tag_id; ?>">
	<td><?php echo $tag->tag_title; ?></td>
	<td><?php echo $tag->subscriptions; ?></td>
	<th>
		<ul class="actions">
			<li><a class="modal_show" href="<?php echo base_url(); ?>tags/delete/<?php echo $tag->tag_id; ?>"><i class="icon icon-trash"></i><?php echo $this->lang->line('delete'); ?>...</a></li>
		</ul>
	</th>
	</tr>
	<?php } ?>
	</tbody>
	</table>
	<?php } ?>
</div>
