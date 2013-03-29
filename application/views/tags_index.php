<div id="content">
	<h2><?php echo $this->lang->line('tags'); ?> (<span><?php echo count($tags); ?></span>)</h2>

	<div class="btn-toolbar">
		<a href="<?php echo base_url(); ?>tags/add" class="btn btn-small btn-inverse modal_call"><i class="icon-plus icon-white"></i> <?php echo $this->lang->line('add'); ?></a>
	</div>

	<?php if($tags) { ?>
	<table class="table table-condensed table-hover">
	<thead>
	<tr><th><?php echo $this->lang->line('title'); ?></th><th><?php echo $this->lang->line('subscriptions'); ?></th><th>&nbsp;</th></tr>
	</thead>
	<tbody>
	<?php foreach($tags as $tag) { ?>
	<tr id="tag_<?php echo $tag->tag_id; ?>">
	<td><?php echo $tag->tag_title; ?></td>
	<td><?php echo $tag->subscriptions; ?></td>
	<td><a class="btn btn-mini modal_call" href="<?php echo base_url(); ?>tags/delete/<?php echo $tag->tag_id; ?>"><i class="icon-trash"></i> <?php echo $this->lang->line('delete'); ?></a></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
	<?php } ?>
</div>
