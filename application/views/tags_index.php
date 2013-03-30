<div id="content">
	<h1><?php echo $this->lang->line('tags'); ?> (<span><?php echo count($tags); ?></span>)</h1>

	<div class="actions">
		<ul>
			<li><a href="<?php echo base_url(); ?>tags/add" class="modal_show"><i class="icon-plus"></i><?php echo $this->lang->line('add'); ?>...</a></li>
		</ul>
	</div>

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
	<th><a class="modal_show" href="<?php echo base_url(); ?>tags/delete/<?php echo $tag->tag_id; ?>"><i class="icon-trash"></i><?php echo $this->lang->line('delete'); ?>...</a></th>
	</tr>
	<?php } ?>
	</tbody>
	</table>
	<?php } ?>
</div>
