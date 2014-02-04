	<nav>
		<ul class="actions">
			<li class="hide-phone hide-tablet allow_notifications"><a href="#"><i class="icon icon-bullhorn"></i><?php echo $this->lang->line('allow_notifications'); ?></a></li>
			<li class="hide-phone"><a href="#" title="<?php echo $this->lang->line('title_shift_f'); ?>" class="fullscreen"><i class="icon icon-resize-full"></i><i class="icon icon-resize-small"></i><?php echo $this->lang->line('fullscreen'); ?></a></li>
			<li><a href="#" title="r" class="items_refresh"><i class="icon icon-refresh"></i><?php echo $this->lang->line('refresh'); ?></a></li>
			<?php if($this->input->cookie('items_mode') == 'read_and_unread') { ?>
				<li class="hide-phone"><a href="#" class="items_mode"><span class="unread_only" title="<?php echo $this->lang->line('title_shift_2'); ?>" style="display:inline-block;"><i class="icon icon-circle-blank"></i><?php echo $this->lang->line('unread_only'); ?></span><span class="read_and_unread" title="<?php echo $this->lang->line('title_shift_1'); ?>" style="display:none;"><i class="icon icon-circle"></i><i class="icon icon-circle-blank"></i><?php echo $this->lang->line('read_and_unread'); ?></span></a></li>
			<?php } else { ?>
				<li class="hide-phone"><a href="#" class="items_mode"><span class="unread_only" title="<?php echo $this->lang->line('title_shift_2'); ?>"><i class="icon icon-circle-blank"></i><?php echo $this->lang->line('unread_only'); ?></span><span class="read_and_unread" title="<?php echo $this->lang->line('title_shift_1'); ?>"><i class="icon icon-circle"></i><i class="icon icon-circle-blank"></i><?php echo $this->lang->line('read_and_unread'); ?></span></a></li>
			<?php } ?>
			<?php if($this->input->cookie('items_display') == 'collapse') { ?>
				<li><a href="#" class="items_display"><span class="expand" title="2" style="display:inline-block;"><i class="icon icon-collapse"></i><?php echo $this->lang->line('expand'); ?></span><span class="collapse" title="1" style="display:none;"><i class="icon icon-collapse-top"></i><?php echo $this->lang->line('collapse'); ?></span></a></li>
			<?php } else { ?>
			<li><a href="#" class="items_display"><span class="expand" title="2"><i class="icon icon-collapse"></i><?php echo $this->lang->line('expand'); ?></span><span class="collapse" title="1"><i class="icon icon-collapse-top"></i><?php echo $this->lang->line('collapse'); ?></span></a></li>
			<?php } ?>
			<li class="hide-phone"><a href="<?php echo base_url(); ?>items/read" title="<?php echo $this->lang->line('title_shift_a'); ?>" id="items_read" class="items_read modal_show"><i class="icon icon-ok"></i><?php echo $this->lang->line('mark_all_as_read'); ?>...</a></li>
			<li><a href="#" class="item_up" id="item_up" title="<?php echo $this->lang->line('title_k'); ?>"><i class="icon icon-chevron-up"></i><?php echo $this->lang->line('up'); ?></a></li>
			<li><a href="#" class="item_down" id="item_down" title="<?php echo $this->lang->line('title_j'); ?>"><i class="icon icon-chevron-down"></i><?php echo $this->lang->line('down'); ?></a></li>
		</ul>
	</nav>
</header>
<aside>
	<ul>
		<li><a id="load-all-items" class="menu" href="<?php echo base_url(); ?>items/get/all" title="<?php echo $this->lang->line('title_g_a'); ?>"><i class="icon icon-asterisk"></i><?php echo $this->lang->line('all_items'); ?> (<span>0</span>)</a></li>
		<li><a id="load-priority-items" class="menu" href="<?php echo base_url(); ?>items/get/priority" title="<?php echo $this->lang->line('title_g_p'); ?>"><i class="icon icon-flag"></i><?php echo $this->lang->line('priority_items'); ?> (<span>0</span>)</a></li>
		<?php if($this->config->item('starred_items')) { ?>
			<li><a id="load-starred-items" class="menu" href="<?php echo base_url(); ?>items/get/starred" title="<?php echo $this->lang->line('title_g_s'); ?>"><i class="icon icon-star"></i><?php echo $this->lang->line('starred_items'); ?> {<span>0</span>}</a></li>
		<?php } ?>
		<?php if($this->config->item('shared_items')) { ?>
			<li><a id="load-shared-items" class="menu" href="<?php echo base_url(); ?>items/get/shared" title="<?php echo $this->lang->line('title_g_shift_s'); ?>"><i class="icon icon-heart"></i><?php echo $this->lang->line('shared_items'); ?> {<span>0</span>}</a></li>
		<?php } ?>

		<?php if($this->config->item('members_list')) { ?>
			<li><a id="load-following-items" class="menu" href="<?php echo base_url(); ?>items/get/following" title="<?php echo $this->lang->line('title_g_f'); ?>"><i class="icon icon-link"></i><?php echo $this->lang->line('following_items'); ?> (<span>0</span>)</a></li>
		<?php } ?>

		<?php if($this->config->item('menu_geolocation_items')) { ?>
			<li><a id="load-geolocation-items" class="menu" href="<?php echo base_url(); ?>items/get/geolocation"><i class="icon icon-map-marker"></i><?php echo $this->lang->line('geolocation_items'); ?> (<span>0</span>)</a></li>
		<?php } ?>
		<?php if($this->config->item('menu_audio_items')) { ?>
			<li><a id="load-audio-items" class="menu" href="<?php echo base_url(); ?>items/get/audio"><i class="icon icon-volume-up"></i><?php echo $this->lang->line('audio_items'); ?> (<span>0</span>)</a></li>
		<?php } ?>
		<?php if($this->config->item('menu_video_items')) { ?>
			<li><a id="load-video-items" class="menu" href="<?php echo base_url(); ?>items/get/video"><i class="icon icon-youtube-play"></i><?php echo $this->lang->line('video_items'); ?> (<span>0</span>)</a></li>
		<?php } ?>
		<li><a id="load-cloud-authors-items" class="menu" href="<?php echo base_url(); ?>items/get/cloud/authors"><i class="icon icon-pencil"></i><?php echo $this->lang->line('authors'); ?></a></li>
		<?php if($this->config->item('tags')) { ?>
			<li><a id="load-cloud-tags-items" class="menu" href="<?php echo base_url(); ?>items/get/cloud/tags"><i class="icon icon-tags"></i><?php echo $this->lang->line('tags'); ?></a></li>
		<?php } ?>
		<?php if($this->config->item('folders')) { ?>
			<?php if($folders) { ?>
				<?php foreach($folders as $folder) { ?>
					<li><a class="folder" href="<?php echo base_url(); ?>subscriptions/get/folder/<?php echo $folder->flr_id; ?>" title="<?php echo $this->lang->line('open_close'); ?>"><i class="icon icon-folder-close"></i></a><a<?php if($folder->flr_direction) { ?> dir="<?php echo $folder->flr_direction; ?>"<?php } ?> id="load-folder-<?php echo $folder->flr_id; ?>-items" class="menu" href="<?php echo base_url(); ?>items/get/folder/<?php echo $folder->flr_id; ?>"><?php echo $folder->flr_title; ?> (<span>0</span>)</a><ul></ul></li>
				<?php } ?>
			<?php } ?>
			<li><a class="folder" href="<?php echo base_url(); ?>subscriptions/get/nofolder" title="<?php echo $this->lang->line('open_close'); ?>"><i class="icon icon-folder-close"></i></a><a id="load-nofolder-items" class="menu" href="<?php echo base_url(); ?>items/get/nofolder"><em><?php echo $this->lang->line('no_folder'); ?></em> (<span>0</span>)</a><ul></ul></li>
		<?php } ?>
		<li class="static">
			<?php echo form_open(base_url().'items/get/search', array('id'=>'search_items_form')); ?>
				<p>
				<?php echo form_label('<i class="icon icon-file-text-alt"></i>'.$this->lang->line('items'), 'search_items'); ?>
				<?php echo form_input('search_items', '', 'id="search_items" title="/"'); ?>
				</p>
			<?php echo form_close(); ?>
		</li>
		<li class="static">
			<?php echo form_open(base_url().'subscriptions/search', array('id'=>'search_subscriptions_form')); ?>
				<p>
				<?php echo form_label('<i class="icon icon-bookmark"></i>'.$this->lang->line('subscriptions'), 'fed_title'); ?>
				<?php echo form_input('fed_title', '', 'id="fed_title"'); ?>
				</p>
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
