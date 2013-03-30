<div id="sidebar">
	<ul class="actions">
		<li><a href="<?php echo base_url(); ?>subscribe" class="modal_show"><i class="icon-plus"></i><?php echo $this->lang->line('subscribe'); ?>...</a></li>
	</ul>
	<div class="sidebar-nav">
		<ul class="menu">
			<li class="active"><a id="load-all-items" href="<?php echo base_url(); ?>home/items/all"><i class="icon-asterisk"></i><?php echo $this->lang->line('all_items'); ?> (<span>0</span>)</a></li>
			<li><a id="load-starred-items" href="<?php echo base_url(); ?>home/items/starred"><i class="icon-star"></i><?php echo $this->lang->line('starred_items'); ?> (<span>0</span>)</a></li>
			<?php if($tags) { ?>
			<?php foreach($tags as $tag) { ?>
			<li><a id="load-tag-<?php echo $tag->tag_id; ?>-items" href="<?php echo base_url(); ?>home/items/tag/<?php echo $tag->tag_id; ?>"><i class="icon-tag"></i><?php echo $tag->tag_title; ?> (<span>0</span>)</a></li>
			<?php } ?>
			<?php } ?>
			<li><a id="load-notag-items" href="<?php echo base_url(); ?>home/items/notag"><i class="icon-tag"></i><em><?php echo $this->lang->line('no_tag'); ?></em> (<span>0</span>)</a></li>
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
		<li><a href="#" id="refresh-items"><i class="icon-refresh"></i><?php echo $this->lang->line('refresh'); ?></a></li>
		<li class="hide-phone"><a href="#"><i class="icon-ok"></i><?php echo $this->lang->line('mark_all_as_read'); ?>...</a></li>
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
