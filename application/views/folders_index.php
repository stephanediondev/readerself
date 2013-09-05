<div id="content">
	<h1><i class="icon icon-folder-close"></i><?php echo $this->lang->line('folders'); ?> (<?php echo $position; ?>)</h1>
	<ul class="actions">
		<li><a href="<?php echo base_url(); ?>folders/create"><i class="icon icon-plus"></i><?php echo $this->lang->line('add'); ?></a></li>
	</ul>
	<?php echo form_open(current_url()); ?>
	<div class="filters">
		<div>
			<?php echo form_label($this->lang->line('title'), 'folders_flr_title'); ?>
			<?php echo form_input($this->router->class.'_folders_flr_title', set_value($this->router->class.'_folders_flr_title', $this->session->userdata($this->router->class.'_folders_flr_title')), 'id="folders_flr_title" class="inputtext"'); ?>
		</div>
		<div>
			<button type="submit"><?php echo $this->lang->line('send'); ?></button>
		</div>
	</div>
	<?php echo form_close(); ?>
	<?php if($folders) { ?>
		<?php foreach($folders as $folder) { ?>
		<div class="cell">
			<ul class="actions">
				<li><a href="<?php echo base_url(); ?>folders/update/<?php echo $folder->flr_id; ?>"><i class="icon icon-pencil"></i><?php echo $this->lang->line('update'); ?></a></li>
				<li><a href="<?php echo base_url(); ?>folders/delete/<?php echo $folder->flr_id; ?>"><i class="icon icon-trash"></i><?php echo $this->lang->line('delete'); ?></a></li>
			</ul>
			<h2><a href="<?php echo base_url(); ?>folders/read/<?php echo $folder->flr_id; ?>"><i class="icon icon-folder-close"></i><?php echo $folder->flr_title; ?></a></h2>
			<ul class="item-details">
				<li><i class="icon icon-rss"></i><?php echo $folder->subscriptions; ?> <?php if($folder->subscriptions > 1) { ?><?php echo mb_strtolower($this->lang->line('subscriptions')); ?><?php } else { ?><?php echo mb_strtolower($this->lang->line('subscription')); ?><?php } ?></li>
			</ul>
		</div>
		<?php } ?>
	<div class="paging">
		<?php echo $pagination; ?>
	</div>
	<?php } ?>
</div>
