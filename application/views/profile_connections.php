	<nav>
		<ul class="actions">
			<li><a href="<?php echo base_url(); ?>profile"><i class="icon icon-step-backward"></i><?php echo $this->lang->line('back'); ?></a></li>
			<li><a href="<?php echo base_url(); ?>profile/logout_purge"><i class="icon icon-signout"></i><?php echo $this->lang->line('logout_purge'); ?></a></li>
		</ul>
	</nav>
</header>
<main>
	<section>
		<section>

	<article class="cell title">
		<h2><i class="icon icon-signin"></i><?php echo $this->lang->line('connections'); ?></h2>
		<ul class="item-details">
			<li>* <?php echo $this->lang->line('current_connection'); ?></li>
		</ul>
	</article>

	<?php if($connections) { ?>
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
	<?php } ?>
		</section>
	</section>
</main>
