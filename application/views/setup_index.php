	<nav>
	</nav>
</header>
<main>
	<section>
		<section>

<?php $form = TRUE; ?>

<article class="title">
	<h2><span><i class="fa fa-gears"></i><?php echo $this->lang->line('setup'); ?></span></h2>
	<ul>
	</ul>
</article>

<article>
	<h2><span>PHP <?php echo phpversion(); ?></span></h2>
	<?php if(version_compare(phpversion(), '5.2.0', '<')) { ?>
		<?php $form = FALSE; ?>
		<p><i class="fa fa-times"></i>Not supported</p>
	<?php } else { ?>
		<p><i class="fa fa-check"></i>Supported version</p>
	<?php } ?>
</article>

<article>
	<h2><span>/application/config/readerself_config.php</span></h2>
	<?php if(!file_exists('application/config/readerself_config.php')) { ?>
		<?php $form = FALSE; ?>
		<p><i class="fa fa-times"></i>File missing</p>
	<?php } else if(!is_writable('application/config/readerself_config.php')) { ?>
		<?php $form = FALSE; ?>
		<p><i class="fa fa-times"></i>File not writable</p>
	<?php } else { ?>
		<p><i class="fa fa-check"></i>File exists</p>
	<?php } ?>
</article>

<?php if($this->db->dbdriver == 'pdo' && $this->db->hostname == 'sqlite:application/database/readerself.sqlite') { ?>
	<article>
		<h2><span>/application/database/readerself.sqlite</span></h2>
		<?php if(!file_exists('application/database/readerself.sqlite')) { ?>
			<?php $form = FALSE; ?>
			<p><i class="fa fa-times"></i>File missing</p>
		<?php } else { ?>
			<p><i class="fa fa-check"></i>File exists</p>
		<?php } ?>
	</article>
<?php } ?>

<?php if($this->db->dbdriver == 'mysqli') { ?>
	<article>
		<h2><span>/INSTALLATION.sql</span></h2>
		<?php if(!file_exists('INSTALLATION.sql')) { ?>
			<?php $form = FALSE; ?>
			<p><i class="fa fa-times"></i>File missing</p>
		<?php } else { ?>
			<p><i class="fa fa-check"></i>File exists</p>
		<?php } ?>
	</article>
<?php } ?>

<article>
	<h2><span>/application/config/database.php</span></h2>
	<?php if($this->db->dbdriver == 'mysqli') { ?>
		<?php if($this->db->database == '' || $this->db->username == '' || $this->db->password == '') { ?>
			<?php $form = FALSE; ?>
			<p><i class="fa fa-times"></i>Define database, username and password</p>
		<?php } else { ?>
			<p><i class="fa fa-check"></i>Database, username and password defined</p>
		<?php } ?>
	<?php } ?>
	<?php if($this->db->dbdriver == 'mysql') { ?>
		<?php $form = FALSE; ?>
		<p>Recommended to use mysqli as driver instead of mysql</p>
	<?php } ?>
</article>

<?php if($form) { ?>
	<article>
		<?php echo form_open(current_url()); ?>
		<?php echo validation_errors(); ?>
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
		<button type="submit"><?php echo $this->lang->line('send'); ?></button>
		</p>

		<?php echo form_close(); ?>
	</article>
<?php } ?>

		</section>
	</section>
</main>
