<div id="sidebar">
	<ul class="actions">
		<li><a href="<?php echo base_url(); ?>subscribe" class="btn btn-small btn-inverse modal_call"><i class="icon-plus icon-white"></i> <?php echo $this->lang->line('subscribe'); ?></a></li>
	</ul>
	<div class="sidebar-nav">
		<ul class="menu">
			<li class="active"><a id="load-all-items" href="<?php echo base_url(); ?>home/items/all"><?php echo $this->lang->line('all_items'); ?> (<span>0</span>)</a></li>
			<li><a id="load-starred-items" href="<?php echo base_url(); ?>home/items/starred"><?php echo $this->lang->line('starred_items'); ?> (<span>0</span>)</a></li>
			<?php if($tags) { ?>
			<?php foreach($tags as $tag) { ?>
			<li><a id="load-tag-<?php echo $tag->tag_id; ?>-items" href="<?php echo base_url(); ?>home/items/tag/<?php echo $tag->tag_id; ?>"><?php echo $tag->tag_title; ?> (<span>0</span>)</a></li>
			<?php } ?>
			<?php } ?>
			<li><a id="load-notag-items" href="<?php echo base_url(); ?>home/items/notag"><em><?php echo $this->lang->line('no_tag'); ?></em> (<span>0</span>)</a></li>
			<li><?php echo $this->lang->line('subscriptions'); ?></li>
			<li>
			<?php echo form_open(base_url().'home/subscriptions'); ?>
			<?php echo form_input('fed_title', set_value('fed_title'), 'id="fed_title"'); ?>
			<?php echo form_close(); ?>
			</li>
		</ul>
	</div>
</div>
<div id="content">
	<ul class="actions">
		<li><a href="#" id="refresh-items" class="btn btn-small"><?php echo $this->lang->line('refresh'); ?></a></li>
		<li><a href="#" class="btn btn-small dropdown-toggle" data-toggle="dropdown"><?php echo $this->lang->line('mark_all_as_read'); ?>...</a></li>
		<!--<ul class="dropdown-menu">-->
		<!--<li><a class="history" href="<?php echo base_url(); ?>home/history/massive-read/all"><?php echo $this->lang->line('all_items'); ?></a></li>-->
		<!--<li><a class="history" href="<?php echo base_url(); ?>home/history/massive-read/one-day"><?php echo $this->lang->line('items_older_than_a_day'); ?></a></li>-->
		<!--<li><a class="history" href="<?php echo base_url(); ?>home/history/massive-read/one-week"><?php echo $this->lang->line('items_older_than_a_week'); ?></a></li>-->
		<!--<li><a class="history" href="<?php echo base_url(); ?>home/history/massive-read/two-weeks"><?php echo $this->lang->line('items_older_than_two_weeks'); ?></a></li>-->
		<!--</ul>-->
	</ul>
	<div id="items">
		<div id="items-display">
		</div>
	</div>
</div>
