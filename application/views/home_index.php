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
		</ul>
	</nav>
</div>

<main class="mdl-layout__content mdl-color--grey-100">
	<div class="mdl-grid">
	</div>
</main>
