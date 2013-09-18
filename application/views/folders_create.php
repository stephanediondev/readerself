	<nav>
		<ul class="actions">
			<li><a href="<?php echo base_url(); ?>folders"><i class="icon icon-step-backward"></i><?php echo $this->lang->line('back'); ?></a></li>
		</ul>
	</nav>
</header>
<main>
	<section>
		<section>
	<article class="cell title">
		<h2><i class="icon icon-folder-close"></i><?php echo $this->lang->line('folders'); ?></h2>
	</article>

	<h2><i class="icon icon-plus"></i><?php echo $this->lang->line('add'); ?></h2>

	<?php echo validation_errors(); ?>

	<?php echo form_open(current_url()); ?>

	<p>
	<?php echo form_label($this->lang->line('title'), 'flr_title'); ?>
	<?php echo form_input('flr_title', set_value('flr_title'), 'id="flr_title" class="input-xlarge required"'); ?>
	</p>

	<p>
	<?php echo form_label($this->lang->line('direction'), 'direction'); ?>
	<?php echo form_dropdown('direction', array('' => '-', 'ltr' => $this->lang->line('direction_ltr'), 'rtl' => $this->lang->line('direction_rtl')), set_value('direction', ''), 'id="direction" class="select numeric"'); ?>
	</p>

	<p>
	<button type="submit"><?php echo $this->lang->line('send'); ?></button>
	</p>
	<?php echo form_close(); ?>

		</section>
	</section>
</main>
