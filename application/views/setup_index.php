<?php $form = TRUE; ?>

<main class="mdl-layout__content mdl-color--<?php echo $this->config->item('material-design/colors/background/layout'); ?>">
	<div class="mdl-grid">
		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title-highlight'); ?> mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title-highlight'); ?>">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">settings</i><?php echo $this->lang->line('setup'); ?></h1>
			</div>
		</div>

		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--3-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
				<h1 class="mdl-card__title-text">PHP <?php echo phpversion(); ?></h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
				<?php if(version_compare(phpversion(), '5.2.0', '<')) { ?>
					<?php $form = FALSE; ?>
					<p>Not supported</p>
				<?php } else { ?>
					<p>Supported version</p>
				<?php } ?>
			</div>
		</div>

		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--3-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
				<h1 class="mdl-card__title-text">/application/config/database.php</h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
				<?php if(!file_exists('application/config/database.php')) { ?>
					<?php $form = FALSE; ?>
					<p>File missing</p>
				<?php } else if(!is_writable('application/config/database.php')) { ?>
					<?php $form = FALSE; ?>
					<p>File not writable</p>
				<?php } else { ?>
					<p>File exists</p>
				<?php } ?>
			</div>
		</div>

		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--3-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
				<h1 class="mdl-card__title-text">/application/config/readerself_config.php</h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
				<?php if(!file_exists('application/config/readerself_config.php')) { ?>
					<?php $form = FALSE; ?>
					<p>File missing</p>
				<?php } else if(!is_writable('application/config/readerself_config.php')) { ?>
					<?php $form = FALSE; ?>
					<p>File not writable</p>
				<?php } else { ?>
					<p>File exists</p>
				<?php } ?>
			</div>
		</div>
	</div>

	<?php if($form) { ?>
		<?php echo form_open(current_url()); ?>
		<div class="mdl-grid">
			<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
				<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
					<h1 class="mdl-card__title-text"><?php echo $this->lang->line('database'); ?></h1>
				</div>
				<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
					<p>
					<?php echo form_label($this->lang->line('database_type'), 'database_type'); ?>
					<?php echo form_dropdown('database_type', $types, set_value('database_type', 'mysqli'), 'id="database_type" class="required"'); ?>
					</p>

					<p class="database_option">
					<?php echo form_label($this->lang->line('database_hostname'), 'database_hostname'); ?>
					<?php echo form_input('database_hostname', set_value('database_hostname', 'localhost'), 'id="database_hostname"'); ?>
					</p>

					<p class="database_option">
					<?php echo form_label($this->lang->line('database_username'), 'database_username'); ?>
					<?php echo form_input('database_username', set_value('database_username'), 'id="database_username"'); ?>
					</p>

					<p class="database_option">
					<?php echo form_label($this->lang->line('database_password'), 'database_password'); ?>
					<?php echo form_password('database_password', set_value('database_password'), 'id="database_password"'); ?>
					</p>

					<p class="database_option">
					<?php echo form_label($this->lang->line('database_name'), 'database_name'); ?>
					<?php echo form_input('database_name', set_value('database_name', 'readerself'), 'id="database_name"'); ?>
					</p>
				</div>
			</div>

			<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
				<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
					<h1 class="mdl-card__title-text"><?php echo $this->lang->line('user'); ?></h1>
				</div>
				<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
					<p>
					<?php echo form_label($this->lang->line('mbr_email'), 'mbr_email'); ?>
					<?php echo form_input('mbr_email', set_value('mbr_email'), 'id="mbr_email" class="valid_email required"'); ?>
					</p>

					<p>
					<?php echo form_label($this->lang->line('mbr_email_confirm'), 'mbr_email_confirm'); ?>
					<?php echo form_input('mbr_email_confirm', set_value('mbr_email_confirm'), 'id="mbr_email_confirm" class="valid_email required"'); ?>
					</p>

					<p>
					<?php echo form_label($this->lang->line('mbr_password'), 'mbr_password'); ?>
					<?php echo form_password('mbr_password', set_value('mbr_password'), 'id="mbr_password" class="required"'); ?>
					</p>

					<p>
					<?php echo form_label($this->lang->line('mbr_password_confirm'), 'mbr_password_confirm'); ?>
					<?php echo form_password('mbr_password_confirm', set_value('mbr_password_confirm'), 'id="mbr_password_confirm" class="required"'); ?>
					</p>

					<p>
					<button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--<?php echo $this->config->item('material-design/colors/background/button'); ?> mdl-color-text--<?php echo $this->config->item('material-design/colors/text/button'); ?>">
						<i class="material-icons md-24">done</i>
					</button>
					</p>

					<?php echo validation_errors('<p><i class="material-icons md-16">warning</i>', '</p>'); ?>
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	<?php } ?>
</main>
