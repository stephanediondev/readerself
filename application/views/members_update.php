	<nav>
		<ul class="actions">
			<li><a href="<?php echo base_url(); ?>members"><i class="icon icon-step-backward"></i><?php echo $this->lang->line('back'); ?></a></li>
		</ul>
	</nav>
</header>
<main>
	<section>
		<section>
		<article class="title<?php if($mbr->mbr_id == $this->member->mbr_id) { ?> item-selected<?php } ?>">
			<ul class="actions">
				<?php if($mbr->mbr_nickname) { ?><li><a href="<?php echo base_url(); ?>member/<?php echo $mbr->mbr_nickname; ?>"><i class="icon icon-unlock"></i><?php echo $this->lang->line('public_profile'); ?></a></li><?php } ?>
			</ul>
			<h2><i class="icon icon-user"></i><?php if($mbr->mbr_nickname) { ?><?php echo $mbr->mbr_nickname; ?> / <?php } ?><?php echo $mbr->mbr_email; ?></h2>
			<?php if($this->config->item('gravatar') && $mbr->mbr_gravatar) { ?>
				<p><img alt="" src="http://www.gravatar.com/avatar/<?php echo md5(strtolower($mbr->mbr_gravatar)); ?>?rating=<?php echo $this->config->item('gravatar_rating'); ?>&amp;size=<?php echo $this->config->item('gravatar_size'); ?>&amp;default=<?php echo $this->config->item('gravatar_default'); ?>">
			<?php } ?>
			<?php if($mbr->mbr_description) { ?>
				<p><?php echo strip_tags($mbr->mbr_description); ?></p>
			<?php } ?>
		</article>

		<h2><i class="icon icon-wrench"></i><?php echo $this->lang->line('update'); ?></h2>

		<?php echo validation_errors(); ?>

		<?php echo form_open(current_url()); ?>

		<p>
		<?php echo form_label($this->lang->line('description'), 'mbr_description'); ?>
		<?php echo form_textarea('mbr_description', set_value('mbr_description', $mbr->mbr_description), 'id="mbr_description"'); ?>
		</p>

		<?php if($mbr->mbr_id != $this->member->mbr_id) { ?>
			<p>
			<?php echo form_label($this->lang->line('mbr_administrator'), 'mbr_administrator'); ?>
			<?php echo form_dropdown('mbr_administrator', array(0 => $this->lang->line('no'), 1 => $this->lang->line('yes')), set_value('mbr_administrator', $mbr->mbr_administrator), 'id="mbr_administrator" class="select numeric"'); ?>
			</p>
		<?php } ?>

		<p>
		<button type="submit"><?php echo $this->lang->line('send'); ?></button>
		</p>

		<?php echo form_close(); ?>

		</section>
	</section>
</main>
