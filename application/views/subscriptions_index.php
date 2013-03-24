<div class="container-fluid">
	<h2><?php echo $this->lang->line('subscriptions'); ?> (<span><?php echo count($subscriptions); ?></span>)</h2>

	<?php if($subscriptions) { ?>
	<div class="btn-toolbar" id="content-toolbar">
		<div class="btn-group" data-toggle="buttons-radio" id="filter-items">
			<button type="button" class="btn btn-small active" id="filter-all"><?php echo $this->lang->line('all'); ?></button>
			<button type="button" class="btn btn-small" id="filter-error"><?php echo $this->lang->line('error'); ?></button>
		</div>
	</div>
	<table class="table table-condensed table-hover">
	<thead>
	<tr><th><?php echo $this->lang->line('title'); ?></th><th><?php echo $this->lang->line('url'); ?></th><th><?php echo $this->lang->line('subscribers'); ?></th><th>&nbsp;</th></tr>
	</thead>
	<tbody>
	<?php foreach($subscriptions as $sub) { ?>
	<tr class="<?php if($sub->fed_lasterror) { ?>line-error<?php } else { ?>line-all<?php } ?>" id="sub_<?php echo $sub->sub_id; ?>">
	<td><?php if($sub->fed_lasterror) { ?><span class="label label-important"><?php echo $this->lang->line('error'); ?></span> <?php } ?><?php echo $sub->fed_title; ?></td>
	<td><?php echo $sub->fed_link; ?></td>
	<td><?php echo $sub->subscribers; ?></td>
	<td><a class="btn btn-mini" href="#"><i class="icon-tags"></i> <?php echo $this->lang->line('tags'); ?></a> <a class="btn btn-mini modal_call" href="<?php echo base_url(); ?>subscriptions/delete/<?php echo $sub->sub_id; ?>"><i class="icon-trash"></i> <?php echo $this->lang->line('delete'); ?></a></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
	<?php } ?>
</div>
