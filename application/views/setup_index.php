<?php $form = TRUE; ?>

<main class="mdl-layout__content mdl-color--grey-100">
	<div class="mdl-grid">
		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--white mdl-color--teal">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">settings</i><?php echo $this->lang->line('setup'); ?></h1>
			</div>
		</div>

		<div class="mdl-card mdl-cell mdl-cell--3-col">
			<div class="mdl-card__title">
				<h1 class="mdl-card__title-text">PHP <?php echo phpversion(); ?></h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--black">
				<?php if(version_compare(phpversion(), '5.2.0', '<')) { ?>
					<?php $form = FALSE; ?>
					<p>Not supported</p>
				<?php } else { ?>
					<p>Supported version</p>
				<?php } ?>
			</div>
		</div>

		<div class="mdl-card mdl-cell mdl-cell--3-col">
			<div class="mdl-card__title">
				<h1 class="mdl-card__title-text">/application/config/readerself_config.php</h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--black">
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

		<?php if($this->db->dbdriver == 'pdo' && stristr($this->db->hostname, 'sqlite:application/database/readerself.sqlite')) { ?>
			<div class="mdl-card mdl-cell mdl-cell--3-col">
				<div class="mdl-card__title">
					<h1 class="mdl-card__title-text">/application/database/readerself.sqlite</h1>
				</div>
				<div class="mdl-card__supporting-text mdl-color-text--black">
					<?php if(!file_exists('application/database/readerself.sqlite')) { ?>
						<?php $form = FALSE; ?>
						<p>File missing</p>
					<?php } else { ?>
						<p>File exists</p>
					<?php } ?>
				</div>
			</div>

			<div class="mdl-card mdl-cell mdl-cell--3-col">
				<div class="mdl-card__title">
					<h1 class="mdl-card__title-text">/application/database/installation-sqlite.sql</h1>
				</div>
				<div class="mdl-card__supporting-text mdl-color-text--black">
					<?php if(!file_exists('application/database/installation-sqlite.sql')) { ?>
						<?php $form = FALSE; ?>
						<p>File missing</p>
					<?php } else { ?>
						<p>File exists</p>
					<?php } ?>
				</div>
			</div>
		<?php } ?>

		<?php if($this->db->dbdriver == 'mysqli') { ?>
			<div class="mdl-card mdl-cell mdl-cell--3-col">
				<div class="mdl-card__title">
					<h1 class="mdl-card__title-text">/application/database/installation-mysql.sql</h1>
				</div>
				<div class="mdl-card__supporting-text mdl-color-text--black">
					<?php if(!file_exists('application/database/installation-mysql.sql')) { ?>
						<?php $form = FALSE; ?>
						<p>File missing</p>
					<?php } else { ?>
						<p>File exists</p>
					<?php } ?>
				</div>
			</div>
		<?php } ?>

		<div class="mdl-card mdl-cell mdl-cell--3-col">
			<div class="mdl-card__title">
				<h1 class="mdl-card__title-text">/application/config/database.php</h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--black">
				<?php if($this->db->dbdriver == 'pdo' && stristr($this->db->hostname, 'sqlite:application/database/readerself.sqlite')) { ?>
					<?php if($this->db->database != '') { ?>
						<?php $form = FALSE; ?>
						<p>Don't set database value with sqlite</p>
					<?php } else { ?>
						<p>Hostname defined</p>
					<?php } ?>
				<?php } ?>
				<?php if($this->db->dbdriver == 'mysqli') { ?>
					<?php if($this->db->database == '' || $this->db->username == '' || $this->db->password == '') { ?>
						<?php $form = FALSE; ?>
						<p>Define database, username and password</p>
					<?php } else { ?>
						<p>Database, username and password defined</p>
					<?php } ?>
				<?php } ?>
				<?php if($this->db->dbdriver == 'mysql') { ?>
					<?php $form = FALSE; ?>
					<p>Recommended to use mysqli as driver instead of mysql</p>
				<?php } ?>
			</div>
		</div>

		<?php if($form) { ?>
			<div class="mdl-card mdl-cell mdl-cell--12-col">
				<div class="mdl-card__supporting-text mdl-color-text--black">
					<?php echo validation_errors(); ?>

					<?php echo form_open(current_url()); ?>
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
					<button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--pink mdl-color-text--white">
						<i class="material-icons md-24">done</i>
					</button>
					</p>

					<?php echo form_close(); ?>
				</div>
			</div>
		<?php } ?>

	</div>
</main>
