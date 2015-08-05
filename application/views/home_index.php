<div class="mdl-layout__drawer">
	<nav class="mdl-navigation">
		<ul>
			<li><a id="load-all-items" class="mdl-navigation__link" href="<?php echo base_url(); ?>items/get/all" title="<?php echo $this->lang->line('title_g_a'); ?>"><i class="material-icons md-18">public</i><?php echo $this->lang->line('all_items'); ?> (<span>0</span>)</a></li>
			<li><a id="load-priority-items" class="mdl-navigation__link" href="<?php echo base_url(); ?>items/get/priority" title="<?php echo $this->lang->line('title_g_p'); ?>"><i class="material-icons md-18">announcement</i><?php echo $this->lang->line('priority_items'); ?> (<span>0</span>)</a></li>
			<?php if($this->config->item('starred_items')) { ?>
				<li><a id="load-starred-items" class="mdl-navigation__link" href="<?php echo base_url(); ?>items/get/starred" title="<?php echo $this->lang->line('title_g_s'); ?>"><i class="material-icons md-18">star</i><?php echo $this->lang->line('starred_items'); ?> {<span>0</span>}</a></li>
			<?php } ?>
			<?php if($this->config->item('shared_items')) { ?>
				<li><a id="load-shared-items" class="mdl-navigation__link" href="<?php echo base_url(); ?>items/get/shared" title="<?php echo $this->lang->line('title_g_shift_s'); ?>"><i class="material-icons md-18">favorite</i><?php echo $this->lang->line('shared_items'); ?> {<span>0</span>}</a></li>
			<?php } ?>
			<?php if($this->config->item('menu_geolocation_items')) { ?>
				<li><a id="load-geolocation-items" class="mdl-navigation__link" href="<?php echo base_url(); ?>items/get/geolocation"><i class="material-icons md-18">place</i><?php echo $this->lang->line('geolocation_items'); ?> (<span>0</span>)</a></li>
			<?php } ?>
			<?php if($this->config->item('folders')) { ?>
				<?php if($folders) { ?>
					<?php foreach($folders as $folder) { ?>
						<li>
							<a<?php if($folder->flr_direction) { ?> dir="<?php echo $folder->flr_direction; ?>"<?php } ?> id="load-folder-<?php echo $folder->flr_id; ?>-items" class="mdl-navigation__link" href="<?php echo base_url(); ?>items/get/folder/<?php echo $folder->flr_id; ?>"><i class="material-icons md-18">folder</i><?php echo $folder->flr_title; ?> (<span>0</span>)</a>
							<a class="mdl-navigation__link folder" href="<?php echo base_url(); ?>subscriptions/get/folder/<?php echo $folder->flr_id; ?>" title="<?php echo $this->lang->line('open_close'); ?>"><i class="material-icons md-24">arrow_drop_down</i></a>
							<ul></ul>
						</li>
					<?php } ?>
				<?php } ?>
				<?php if($count_nofolder > 0) { ?>
					<li>
						<a id="load-nofolder-items" class="mdl-navigation__link" href="<?php echo base_url(); ?>items/get/nofolder"><i class="material-icons md-18">folder</i><em><?php echo $this->lang->line('no_folder'); ?></em> (<span>0</span>)</a>
						<a class="mdl-navigation__link folder" href="<?php echo base_url(); ?>subscriptions/get/nofolder" title="<?php echo $this->lang->line('open_close'); ?>"><i class="material-icons md-24">arrow_drop_down</i></a>
						<ul></ul>
					</li>
				<?php } ?>
			<?php } ?>
			<li>
				<?php echo form_open(base_url().'items/get/search', array('id'=>'search_items_form')); ?>
					<p>
					<?php echo form_label('<i class="icon icon-file-text-alt"></i>'.$this->lang->line('items'), 'search_items'); ?>
					<?php echo form_input('search_items', '', 'id="search_items" title="/"'); ?>
					</p>
				<?php echo form_close(); ?>
			</li>
			<li>
				<?php echo form_open(base_url().'subscriptions/search', array('id'=>'search_subscriptions_form')); ?>
					<p>
					<?php echo form_label('<i class="icon icon-bookmark"></i>'.$this->lang->line('subscriptions'), 'fed_title'); ?>
					<?php echo form_input('fed_title', '', 'id="fed_title"'); ?>
					</p>
				<?php echo form_close(); ?>
			</li>
		</ul>
	</nav>
</div>

<main class="mdl-layout__content mdl-color--grey-100">
	<div class="mdl-grid">
	</div>
</main>
