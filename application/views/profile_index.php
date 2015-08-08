<div class="mdl-tooltip" for="tip_delete"><?php echo $this->lang->line('delete'); ?></div>
<div class="mdl-tooltip" for="tip_connections"><?php echo $this->lang->line('active_connections'); ?></div>
<div class="mdl-tooltip" for="tip_public"><?php echo $this->lang->line('public_profile'); ?></div>

<main class="mdl-layout__content mdl-color--<?php echo $this->config->item('material-design/colors/background/layout'); ?>">
	<div class="mdl-grid">
		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--white mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title'); ?>">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">person</i><?php if($this->member->mbr_nickname) { ?><?php echo $this->member->mbr_nickname; ?><?php } else { ?><?php echo $this->lang->line('profile'); ?><?php } ?></h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--black">
				<?php if($this->member->mbr_description) { ?>
					<p><?php echo strip_tags($this->member->mbr_description); ?></p>
				<?php } ?>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a id="tip_delete" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>profile/delete"><i class="material-icons md-18">delete</i></a>
				<a id="tip_connections" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>profile/connections"><i class="material-icons md-18">wifi</i></a>
				<?php if($this->member->mbr_nickname) { ?>
					<a id="tip_public" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>member/<?php echo $this->member->mbr_nickname; ?>"><i class="material-icons md-18">link</i></a>
				<?php } ?>
			</div>
		</div>

		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title">
				<h1 class="mdl-card__title-text"><?php echo $this->lang->line('update'); ?></h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--black">
				<?php echo validation_errors(); ?>

				<?php echo form_open(current_url()); ?>

				<?php if(!$this->config->item('ldap')) { ?>
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
				<?php } ?>

				<p>
				<?php echo form_label($this->lang->line('mbr_nickname'), 'mbr_nickname'); ?>
				<?php echo form_input('mbr_nickname', set_value('mbr_nickname', $this->member->mbr_nickname), 'id="mbr_nickname"'); ?>
				</p>

				<?php if($this->config->item('gravatar')) { ?>
					<p>
					<?php echo form_label($this->lang->line('gravatar'), 'mbr_gravatar'); ?>
					<?php echo form_input('mbr_gravatar', set_value('mbr_gravatar', $this->member->mbr_gravatar), 'id="mbr_gravatar" class="valid_email"'); ?>
					</p>
				<?php } ?>

				<p>
				<?php echo form_label($this->lang->line('description'), 'mbr_description'); ?>
				<?php echo form_textarea('mbr_description', set_value('mbr_description', $this->member->mbr_description), 'id="mbr_description"'); ?>
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
