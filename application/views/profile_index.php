	<nav>
		<ul class="actions">
			<?php if($this->member->mbr_nickname) { ?><li><a href="<?php echo base_url(); ?>member/<?php echo $this->member->mbr_nickname; ?>"><i class="icon icon-unlock"></i><?php echo $this->lang->line('public_profile'); ?></a></li><?php } ?>
			<li><a href="<?php echo base_url(); ?>profile/connections"><i class="icon icon-signin"></i><?php echo $this->lang->line('active_connections'); ?></a></li>
		</ul>
	</nav>
</header>
<main>
	<section>
		<section>

	<article class="cell title">
		<h2><i class="icon icon-user"></i><?php if($this->member->mbr_nickname) { ?><?php echo $this->member->mbr_nickname; ?><?php } else { ?><?php echo $this->lang->line('profile'); ?><?php } ?></h2>
	</article>

	<h2><i class="icon icon-wrench"></i><?php echo $this->lang->line('update'); ?></h2>

	<?php echo validation_errors(); ?>

	<?php echo form_open(current_url()); ?>

	<p>
	<?php echo form_label($this->lang->line('mbr_email'), 'mbr_email'); ?>
	<?php echo form_input('mbr_email', set_value('mbr_email', $this->member->mbr_email), 'id="mbr_email" class="valid_email required"'); ?>
	</p>

	<p>
	<?php echo form_label($this->lang->line('mbr_email_confirm'), 'mbr_email_confirm'); ?>
	<?php echo form_input('mbr_email_confirm', set_value('mbr_email_confirm', $this->member->mbr_email), 'id="mbr_email_confirm" class="valid_email required"'); ?>
	</p>

	<p>
	<?php echo form_label($this->lang->line('mbr_password'), 'mbr_password'); ?>
	<?php echo form_password('mbr_password', set_value('mbr_password'), 'id="mbr_password"'); ?>
	</p>

	<p>
	<?php echo form_label($this->lang->line('mbr_password_confirm'), 'mbr_password_confirm'); ?>
	<?php echo form_password('mbr_password_confirm', set_value('mbr_password_confirm'), 'id="mbr_password_confirm"'); ?>
	</p>

	<p>
	<?php echo form_label($this->lang->line('mbr_nickname'), 'mbr_nickname'); ?>
	<?php echo form_input('mbr_nickname', set_value('mbr_nickname', $this->member->mbr_nickname), 'id="mbr_nickname"'); ?>
	</p>

	<p>
	<button type="submit"><?php echo $this->lang->line('send'); ?></button>
	</p>

	<?php echo form_close(); ?>
		</section>
	</section>
</main>
