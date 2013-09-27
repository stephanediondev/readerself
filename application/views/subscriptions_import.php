	<nav>
		<ul class="actions">
			<li><a href="<?php echo base_url(); ?>subscriptions"><i class="icon icon-step-backward"></i><?php echo $this->lang->line('back'); ?></a></li>
		</ul>
	</nav>
</header>
<main>
	<section>
		<section>
		<article class="title">
			<h2><i class="icon icon-bookmark"></i><?php echo $this->lang->line('subscriptions'); ?></h2>
		</article>

	<h2><i class="icon icon-download-alt"></i><?php echo $this->lang->line('import'); ?></h2>

	<?php echo validation_errors(); ?>

	<?php echo form_open_multipart(current_url()); ?>

	<input type="hidden" name="hidden">

	<p>
	<?php echo form_label($this->lang->line('opml_file'), 'file'); ?>
	<?php echo form_upload('file', false, 'id="file" class="required"'); ?>
	</p>

	<p>
	<button type="submit"><?php echo $this->lang->line('send'); ?></button>
	</p>

	<?php echo form_close(); ?>

		</section>
	</section>
</main>
