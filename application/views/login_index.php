<main class="mdl-layout__content mdl-color--<?php echo $this->config->item('material-design/colors/background/layout'); ?>">
	<div class="mdl-grid">
		<div class="mdl-card mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col">
			<div class="mdl-card__title mdl-color-text--white mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title'); ?>">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">power_settings_new</i><?php echo $this->lang->line('login'); ?></h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--black">
				<?php echo validation_errors(); ?>

				<?php echo form_open(current_url().'?u='.$this->input->get('u')); ?>

				<p>
				<?php echo form_label($this->lang->line('email_or_nickname'), 'mbr_email'); ?>
				<?php echo form_input('email_or_nickname', set_value('email_or_nickname', $this->input->get('email')), 'id="email_or_nickname" class="required"'); ?>
				</p>

				<p>
				<?php echo form_label($this->lang->line('mbr_password'), 'mbr_password'); ?>
				<?php echo form_password('mbr_password', set_value('mbr_password', $this->input->get('password')), 'id="mbr_password" class="required"'); ?>
				</p>

				<p>
				<button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--<?php echo $this->config->item('material-design/colors/background/button'); ?> mdl-color-text--white">
					<i class="material-icons md-24">done</i>
				</button>
				</p>

				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</main>
