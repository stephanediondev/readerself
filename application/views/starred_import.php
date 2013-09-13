</header>
<main>
	<section>
		<section>
		<article class="cell title">
			<h2><i class="icon icon-star"></i><?php echo $this->lang->line('starred_items'); ?></h2>
		</article>

	<h2><i class="icon icon-download-alt"></i><?php echo $this->lang->line('import'); ?></h2>

	<?php echo validation_errors(); ?>

	<?php echo form_open_multipart(current_url()); ?>

	<input type="hidden" name="hidden">

	<p>
	<?php echo form_label('starred.json', 'file'); ?>
	<?php echo form_upload('file', false, 'id="file" class="required"'); ?>
	</p>

	<p>
	<button type="submit"><?php echo $this->lang->line('send'); ?></button>
	</p>

	<?php echo form_close(); ?>

		</section>
	</section>
</main>
