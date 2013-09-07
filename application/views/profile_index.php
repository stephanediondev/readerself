	<nav>
		<ul class="actions">
			<li><a href="<?php echo base_url(); ?>profile/logout_purge"><i class="icon icon-signout"></i><?php echo $this->lang->line('logout_purge'); ?></a></li>
		</ul>
	</nav>
</header>
<main>
	<section>
		<section>

	<article class="cell title">
		<h2><i class="icon icon-user"></i><?php echo $this->lang->line('profile'); ?></h2>
	</article>

	<h2><i class="icon icon-pencil"></i><?php echo $this->lang->line('update'); ?></h2>

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
	<button type="submit"><?php echo $this->lang->line('send'); ?></button>
	</p>

	<?php echo form_close(); ?>

	<article class="cell title">
		<h2><i class="icon icon-signin"></i><?php echo $this->lang->line('connections'); ?></h2>
		<ul class="item-details">
			<li>* <?php echo $this->lang->line('current_connection'); ?></li>
		</ul>
	</article>

	<?php if(count($connections) > 1) { ?>
	<?php } ?>
		<?php foreach($connections as $cnt) { ?>
		<?php $ua_info = parse_user_agent($cnt->cnt_agent); ?>
		<?php list($date, $time) = explode(' ', $cnt->cnt_datecreated); ?>
			<article class="cell">
				<h2><i class="icon icon-signin"></i><?php echo $ua_info['platform']; ?> <?php echo $ua_info['browser']; ?> <?php echo $ua_info['version']; ?><?php if($this->member->token_connection == $cnt->token_connection) { ?> *<?php } ?></h2>
				<ul class="item-details">
					<li><i class="icon icon-bolt"></i><?php echo $cnt->cnt_ip; ?></li>
					<li><i class="icon icon-calendar"></i><?php echo $date; ?></li>
					<li><i class="icon icon-time"></i><?php echo $time; ?> (<span class="timeago" title="<?php echo $cnt->cnt_datecreated; ?>"></span>)</li>
					<li class="block"><?php echo $cnt->cnt_agent; ?></li>
				</ul>
			</article>
		<?php } ?>
		</section>
	</section>
</main>
