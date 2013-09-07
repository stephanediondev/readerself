	<nav>
		<ul class="actions">
			<li><a href="<?php echo base_url(); ?>subscriptions"><i class="icon icon-step-backward"></i><?php echo $this->lang->line('back'); ?></a></li>
		</ul>
	</nav>
</header>
<main>
	<section>
		<section>
	<article class="cell title">
		<h2><i class="icon icon-rss"></i><?php echo $this->lang->line('subscriptions'); ?></h2>
	</article>

	<h2><i class="icon icon-plus"></i><?php echo $this->lang->line('add'); ?></h2>

	<?php echo validation_errors(); ?>

	<?php echo form_open(current_url()); ?>

	<?php if($error) { ?>
		<p><?php echo $error; ?></p>
	<?php } ?>

	<p>
	<?php echo form_label($this->lang->line('url'), 'url'); ?>
	<?php if(count($feeds) > 0) { ?>
		<?php echo form_dropdown('url', $feeds, set_value('url', ''), 'id="url" class="select required numeric"'); ?>
		<?php echo form_hidden('analyze_done', '1'); ?>
	<?php } else { ?>
		<?php echo form_input('url', set_value('url'), 'id="url" class="input-xlarge required"'); ?>
	<?php } ?>
	</p>

	<?php if($this->config->item('folders')) { ?>
		<p>
		<?php echo form_label($this->lang->line('folder'), 'folder'); ?>
		<?php echo form_dropdown('folder', $folders, set_value('folder', ''), 'id="folder" class="select required numeric"'); ?>
		</p>
	<?php } ?>

	<p>
	<button type="submit"><?php echo $this->lang->line('send'); ?></button>
	</p>
	<?php echo form_close(); ?>
		</section>
	</section>
</main>
