<div id="content">
	<h1><i class="icon icon-rss"></i><?php echo $this->lang->line('subscriptions'); ?> (<span><?php echo count($subscriptions); ?></span>)</h1>

	<?php if($subscriptions) { ?>
	<ul class="actions">
		<li><a href="#" id="filter-all"><i class="icon icon-asterisk"></i><?php echo $this->lang->line('all'); ?></a></li>
		<li><a href="#" id="filter-error"><i class="icon icon-bell"></i><?php echo $this->lang->line('error'); ?></a></li>
	</ul>

	<table class="table table-condensed table-hover">
	<thead>
	<tr><th><?php echo $this->lang->line('title'); ?></th><th><?php echo $this->lang->line('url'); ?></th><th><?php echo $this->lang->line('subscribers'); ?></th><th><?php echo $this->lang->line('tag'); ?></th><th>&nbsp;</th></tr>
	</thead>
	<tbody>
	<?php foreach($subscriptions as $sub) { ?>
	<tr class="<?php if($sub->fed_lasterror) { ?>line-error<?php } else { ?>line-all<?php } ?>" id="sub_<?php echo $sub->sub_id; ?>">
	<td><?php if($sub->fed_lasterror) { ?><i class="icon icon-bell"></i><?php } ?><?php echo $sub->fed_title; ?></td>
	<td><?php echo $sub->fed_link; ?></td>
	<td><?php echo $sub->subscribers; ?></td>
	<td class="tag-title"><?php if($sub->tag_title) { ?><?php echo $sub->tag_title; ?><?php } else { ?><em><?php echo $this->lang->line('no_tag'); ?></em><?php } ?></td>
	<th>
		<ul class="actions">
			<li><a class="modal_show" href="<?php echo base_url(); ?>subscriptions/tag/<?php echo $sub->sub_id; ?>"><i class="icon icon-tag"></i><?php echo $this->lang->line('tag'); ?>...</a></li>
			<li><a class="modal_show" href="<?php echo base_url(); ?>subscriptions/delete/<?php echo $sub->sub_id; ?>"><i class="icon icon-trash"></i><?php echo $this->lang->line('delete'); ?>...</a></li>
		</ul>
	</th>
	</tr>
	<?php } ?>
	</tbody>
	</table>
	<?php } ?>
</div>
