<div id="sidebar">
	<ul class="actions">
		<li><a id="add_subscribe" href="<?php echo base_url(); ?>subscribe" class="modal_show"><i class="icon icon-plus"></i><?php echo $this->lang->line('subscribe'); ?>...</a></li>
	</ul>
	<ul class="menu">
		<li class="active"><a id="load-all-items" href="<?php echo base_url(); ?>home/items/all"><i class="icon icon-asterisk"></i><?php echo $this->lang->line('all_items'); ?> (<span>0</span>)</a></li>
		<li><a id="load-starred-items" href="<?php echo base_url(); ?>home/items/starred"><i class="icon icon-star"></i><?php echo $this->lang->line('starred_items'); ?> (<span>0</span>)</a></li>
		<?php if($tags && $this->config->item('tags')) { ?>
		<?php foreach($tags as $tag) { ?>
		<li><a id="load-tag-<?php echo $tag->tag_id; ?>-items" href="<?php echo base_url(); ?>home/items/tag/<?php echo $tag->tag_id; ?>"><i class="icon icon-folder-close"></i><?php echo $tag->tag_title; ?> (<span>0</span>)</a></li>
		<?php } ?>
		<li><a id="load-notag-items" href="<?php echo base_url(); ?>home/items/notag"><i class="icon icon-folder-close"></i><em><?php echo $this->lang->line('no_tag'); ?></em> (<span>0</span>)</a></li>
		<?php } ?>
		<li><?php echo $this->lang->line('subscriptions'); ?></li>
		<li>
		<?php echo form_open(base_url().'home/subscriptions'); ?>
		<?php echo form_input('fed_title', set_value('fed_title'), 'id="fed_title"'); ?>
		<?php echo form_close(); ?>
		</li>
	</ul>
</div>
<div id="actions-main">
	<ul class="actions">
		<li><a href="#" id="refresh-items"><i class="icon icon-refresh"></i><?php echo $this->lang->line('refresh'); ?></a></li>
		<li class="hide-phone"><a href="<?php echo base_url(); ?>home/history/dialog" id="read_all" class="modal_show"><i class="icon icon-ok"></i><?php echo $this->lang->line('mark_all_as_read'); ?>...</a></li>
		<li><a href="#" id="item-up"><i class="icon icon-chevron-up"></i><?php echo $this->lang->line('up'); ?></a></li>
		<li><a href="#" id="item-down"><i class="icon icon-chevron-down"></i><?php echo $this->lang->line('down'); ?></a></li>
	</ul>
</div>
<div id="content">
	<div id="items">
		<div id="items-display">
		</div>
	</div>
</div>
