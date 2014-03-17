	<nav>
	</nav>
</header>
<main>
	<section>
		<section>
	<article class="title">
		<h2><i class="icon icon-signin"></i><?php echo $this->lang->line('login'); ?></h2>
	</article>

	<?php echo validation_errors(); ?>

	<?php echo form_open(current_url().'?u='.$this->input->get('u')); ?>

	<p>
	<?php echo form_label($this->lang->line('email_or_nickname'), 'mbr_email'); ?>
	<?php echo form_input('email_or_nickname', set_value('email_or_nickname'), 'id="email_or_nickname" class="required"'); ?>
	</p>

	<p>
	<?php echo form_label($this->lang->line('mbr_password'), 'mbr_password'); ?>
	<?php echo form_password('mbr_password', set_value('mbr_password'), 'id="mbr_password" class="required"'); ?>
	</p>

	<p>
	<button type="submit"><?php echo $this->lang->line('send'); ?></button>
	</p>

	<?php echo form_close(); ?>

		</section>
	</section>
</main>
