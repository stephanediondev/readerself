<div class="container-fluid">
	<div class="row-fluid">
		<div class="span2" id="sidebar">
			<div class="btn-toolbar">
				<a href="<?php echo base_url(); ?>subscribe" class="btn btn-small btn-inverse modal_call"><i class="icon-plus icon-white"></i> <?php echo $this->lang->line('subscribe'); ?></a>
			</div>
			<div class="well well-small sidebar-nav">
				<ul class="nav nav-list">
					<li class="active"><a id="load-all-items" href="<?php echo base_url(); ?>home/items/all"><?php echo $this->lang->line('items'); ?> (<span>0</span>)</a></li>
					<li><a id="load-starred-items" href="<?php echo base_url(); ?>home/items/starred"><?php echo $this->lang->line('starred_items'); ?> (<span>0</span>)</a></li>
					<li class="divider"></li>
					<li class="nav-header"><?php echo $this->lang->line('tags'); ?></li>
					<?php if($tags) { ?>
					<?php foreach($tags as $tag) { ?>
					<li><a id="load-tag-<?php echo $tag->tag_id; ?>-items" href="<?php echo base_url(); ?>home/items/tag/<?php echo $tag->tag_id; ?>"><?php echo $tag->tag_title; ?> (<span>0</span>)</a></li>
					<?php } ?>
					<?php } ?>
					<li><a id="load-notag-items" href="<?php echo base_url(); ?>home/items/notag"><em><?php echo $this->lang->line('no_tag'); ?></em> (<span>0</span>)</a></li>
					<li class="divider"></li>
					<li class="nav-header"><?php echo $this->lang->line('subscriptions'); ?></li>
					<li>
					<?php echo form_open(base_url().'home/subscriptions'); ?>
					<div class="input-append">
					<?php echo form_input('fed_title', set_value('fed_title'), 'id="fed_title" class="span9"'); ?>
						<button class="btn" type="submit"><i class="icon-search"></i></button>
					</div>
					<?php echo form_close(); ?>
					</li>
				</ul>
			</div>
		</div>
		<div class="span10" id="content">
			<div class="btn-toolbar" id="content-toolbar">
				<button id="refresh-items" class="btn btn-small"><i class="icon-refresh"></i> <?php echo $this->lang->line('refresh'); ?></button>
				<button id="full-screen" type="button" class="btn btn-small" data-toggle="button"><i class="icon-fullscreen"></i> <?php echo $this->lang->line('full_screen'); ?></button>
				<div class="btn-group" id="massive-read">
					<button class="btn btn-small dropdown-toggle" data-toggle="dropdown"><i class="icon-ok"></i> <?php echo $this->lang->line('mark_all_as_read'); ?>...</button>
					<ul class="dropdown-menu">
					<li><a class="history" href="<?php echo base_url(); ?>home/history/massive-read/all"><?php echo $this->lang->line('all_items'); ?></a></li>
					<li><a class="history" href="<?php echo base_url(); ?>home/history/massive-read/one-day"><?php echo $this->lang->line('items_older_than_a_day'); ?></a></li>
					<li><a class="history" href="<?php echo base_url(); ?>home/history/massive-read/one-week"><?php echo $this->lang->line('items_older_than_a_week'); ?></a></li>
					<li><a class="history" href="<?php echo base_url(); ?>home/history/massive-read/two-weeks"><?php echo $this->lang->line('items_older_than_two_weeks'); ?></a></li>
					</ul>
				</div>
			</div>
			<div id="items">
			</div>
		</div>
	</div>
</div>
