<div id="content">
	<h1><?php echo $this->lang->line('subscriptions'); ?> (<span><?php echo count($subscriptions); ?></span>)</h1>

	<?php if($subscriptions) { ?>
	<div class="btn-toolbar" id="content-toolbar">
		<div class="btn-group" data-toggle="buttons-radio" id="filter-items">
			<button type="button" class="btn btn-small active" id="filter-all"><?php echo $this->lang->line('all'); ?></button>
			<button type="button" class="btn btn-small" id="filter-error"><?php echo $this->lang->line('error'); ?></button>
		</div>
	</div>
	<table class="table table-condensed table-hover">
	<thead>
	<tr><th><?php echo $this->lang->line('title'); ?></th><th><?php echo $this->lang->line('url'); ?></th><th><?php echo $this->lang->line('subscribers'); ?></th><th><?php echo $this->lang->line('tag'); ?></th><th>&nbsp;</th></tr>
	</thead>
	<tbody>
	<?php foreach($subscriptions as $sub) { ?>
	<tr class="<?php if($sub->fed_lasterror) { ?>line-error<?php } else { ?>line-all<?php } ?>" id="sub_<?php echo $sub->sub_id; ?>">
	<td><?php if($sub->fed_lasterror) { ?><span class="label label-important"><?php echo $this->lang->line('error'); ?></span> <?php } ?><?php echo $sub->fed_title; ?></td>
	<td><?php echo $sub->fed_link; ?></td>
	<td><?php echo $sub->subscribers; ?></td>
	<td class="tag-title"><?php if($sub->tag_title) { ?><?php echo $sub->tag_title; ?><?php } else { ?><em><?php echo $this->lang->line('no_tag'); ?></em><?php } ?></td>
	<th>
		<a class="modal_show" href="<?php echo base_url(); ?>subscriptions/tag/<?php echo $sub->sub_id; ?>"><i class="icon-tag"></i><?php echo $this->lang->line('tag'); ?>...</a>
<!--		<div class="btn-group">
			<button class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $this->lang->line('tag'); ?>...</button>
			<ul class="dropdown-menu">
			<li><a class="tag-toggle" href="<?php echo base_url(); ?>subscriptions/tag/<?php echo $sub->sub_id; ?>/0"><em><?php echo $this->lang->line('no_tag'); ?></em></a></li>
			<?php if($tags) { ?>
			<?php foreach($tags as $tag) { ?>
			<li><a class="tag-toggle" href="<?php echo base_url(); ?>subscriptions/tag/<?php echo $sub->sub_id; ?>/<?php echo $tag->tag_id; ?>"><?php echo $tag->tag_title; ?></a></li>
			<?php } ?>
			<?php } ?>
			</ul>
		</div>
-->		<a class="modal_show" href="<?php echo base_url(); ?>subscriptions/delete/<?php echo $sub->sub_id; ?>"><i class="icon-trash"></i><?php echo $this->lang->line('delete'); ?>...</a>
	</th>
	</tr>
	<?php } ?>
	</tbody>
	</table>
	<?php } ?>
</div>
