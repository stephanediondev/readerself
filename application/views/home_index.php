	<nav>
		<ul class="actions">
			<li><a href="#" title="r" class="refresh-items" id="refresh-items"><i class="icon icon-refresh"></i><?php echo $this->lang->line('refresh'); ?></a></li>
			<?php if($this->input->cookie('mode-items') == 'read_and_unread') { ?>
				<li class="hide-phone"><a href="#" id="mode-items" class="mode-items"><span class="unread_only" title="<?php echo $this->lang->line('title_shift_2'); ?>" style="display:inline-block;"><i class="icon icon-eye-close"></i><?php echo $this->lang->line('unread_only'); ?></span><span class="read_and_unread" title="<?php echo $this->lang->line('title_shift_1'); ?>" style="display:none;"><i class="icon icon-eye-open"></i><?php echo $this->lang->line('read_and_unread'); ?></span></a></li>
			<?php } else { ?>
				<li class="hide-phone"><a href="#" id="mode-items" class="mode-items"><span class="unread_only" title="<?php echo $this->lang->line('title_shift_2'); ?>"><i class="icon icon-eye-close"></i><?php echo $this->lang->line('unread_only'); ?></span><span class="read_and_unread" title="<?php echo $this->lang->line('title_shift_1'); ?>"><i class="icon icon-eye-open"></i><?php echo $this->lang->line('read_and_unread'); ?></span></a></li>
			<?php } ?>
			<?php if($this->input->cookie('display-items') == 'collapse') { ?>
				<li><a href="#" class="display-items" id="display-items"><span class="expand" title="2" style="display:inline-block;"><i class="icon icon-collapse"></i><?php echo $this->lang->line('expand'); ?></span><span class="collapse" title="1" style="display:none;"><i class="icon icon-collapse-top"></i><?php echo $this->lang->line('collapse'); ?></span></a></li>
			<?php } else { ?>
			<li><a href="#" class="display-items" id="display-items"><span class="expand" title="2"><i class="icon icon-collapse"></i><?php echo $this->lang->line('expand'); ?></span><span class="collapse" title="1"><i class="icon icon-collapse-top"></i><?php echo $this->lang->line('collapse'); ?></span></a></li>
			<?php } ?>
			<li class="hide-phone"><a href="<?php echo base_url(); ?>items/read" title="<?php echo $this->lang->line('title_shift_a'); ?>" id="read_all" class="read_all modal_show"><i class="icon icon-ok"></i><?php echo $this->lang->line('mark_all_as_read'); ?>...</a></li>
			<li><a href="#" class="item-up" id="item-up" title="<?php echo $this->lang->line('title_k'); ?>"><i class="icon icon-chevron-up"></i><?php echo $this->lang->line('up'); ?></a></li>
			<li><a href="#" class="item-down" id="item-down" title="<?php echo $this->lang->line('title_j'); ?>"><i class="icon icon-chevron-down"></i><?php echo $this->lang->line('down'); ?></a></li>
		</ul>
	</nav>
</header>
<aside>
	<ul>
		<li><a id="load-all-items" href="<?php echo base_url(); ?>items/get/all" title="<?php echo $this->lang->line('title_g_a'); ?>"><i class="icon icon-asterisk"></i><?php echo $this->lang->line('all_items'); ?> (<span>0</span>)</a></li>
		<li><a id="load-priority-items" href="<?php echo base_url(); ?>items/get/priority" title="<?php echo $this->lang->line('title_g_p'); ?>"><i class="icon icon-flag"></i><?php echo $this->lang->line('priority_items'); ?> (<span>0</span>)</a></li>
		<?php if($this->config->item('star')) { ?>
			<li><a id="load-starred-items" href="<?php echo base_url(); ?>items/get/starred" title="<?php echo $this->lang->line('title_g_s'); ?>"><i class="icon icon-star"></i><?php echo $this->lang->line('starred_items'); ?> {<span>0</span>}</a></li>
		<?php } ?>
		<?php if($this->config->item('share')) { ?>
			<li><a id="load-shared-items" href="<?php echo base_url(); ?>items/get/shared" title="<?php echo $this->lang->line('title_g_shift_s'); ?>"><i class="icon icon-heart"></i><?php echo $this->lang->line('shared_items'); ?> {<span>0</span>}</a></li>
		<?php } ?>
		<?php if($this->config->item('tags')) { ?>
			<li><a id="load-cloud-tags-items" href="<?php echo base_url(); ?>items/get/cloud/tags"><i class="icon icon-tags"></i><?php echo $this->lang->line('tags'); ?></a></li>
		<?php } ?>
		<li><a id="load-cloud-authors-items" href="<?php echo base_url(); ?>items/get/cloud/authors"><i class="icon icon-group"></i><?php echo $this->lang->line('authors'); ?></a></li>
		<?php if($this->config->item('menu_geolocation_items')) { ?>
			<li><a id="load-geolocation-items" href="<?php echo base_url(); ?>items/get/geolocation"><i class="icon icon-map-marker"></i><?php echo $this->lang->line('geolocation_items'); ?> (<span>0</span>)</a></li>
		<?php } ?>
		<?php if($this->config->item('menu_audio_items')) { ?>
			<li><a id="load-audio-items" href="<?php echo base_url(); ?>items/get/audio"><i class="icon icon-volume-up"></i><?php echo $this->lang->line('audio_items'); ?> (<span>0</span>)</a></li>
		<?php } ?>
		<?php if($folders && $this->config->item('folders')) { ?>
			<?php foreach($folders as $folder) { ?>
				<li><a<?php if($folder->flr_direction) { ?> dir="<?php echo $folder->flr_direction; ?>"<?php } ?> id="load-folder-<?php echo $folder->flr_id; ?>-items" href="<?php echo base_url(); ?>items/get/folder/<?php echo $folder->flr_id; ?>"><i class="icon icon-folder-close"></i><?php echo $folder->flr_title; ?> (<span>0</span>)</a></li>
			<?php } ?>
			<li><a id="load-nofolder-items" href="<?php echo base_url(); ?>items/get/nofolder"><i class="icon icon-folder-close"></i><em><?php echo $this->lang->line('no_folder'); ?></em> (<span>0</span>)</a></li>
		<?php } ?>
		<li><label for="search_items"><i class="icon icon-file-text-alt"></i><?php echo $this->lang->line('items'); ?></label></li>
		<li>
			<?php echo form_open(base_url().'items/get/search', array('id'=>'search_items_form')); ?>
			<?php echo form_input('search_items', '', 'id="search_items" title="/"'); ?>
			<?php echo form_close(); ?>
		</li>
		<li><label for="fed_title"><i class="icon icon-rss"></i><?php echo $this->lang->line('subscriptions'); ?></label></li>
		<li>
			<?php echo form_open(base_url().'subscriptions/search', array('id'=>'search_subscriptions_form')); ?>
			<?php echo form_input('fed_title', '', 'id="fed_title"'); ?>
			<?php echo form_close(); ?>
		</li>
	</ul>
</aside>
<main>
	<section>
		<section>
		</section>
	</section>
</main>
