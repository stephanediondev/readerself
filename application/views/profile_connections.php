<div class="mdl-tooltip" for="tip_back"><?php echo $this->lang->line('back'); ?></div>
<div class="mdl-tooltip" for="tip_logout"><?php echo $this->lang->line('logout_purge'); ?></div>

<main class="mdl-layout__content mdl-color--grey-100">
	<div class="mdl-grid">
		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--white mdl-color--teal">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">wifi</i><?php echo $this->lang->line('active_connections'); ?></h1>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a id="tip_back" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>profile"><i class="material-icons md-18">arrow_back</i></a>
				<a id="tip_logout" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>profile/logout_purge"><i class="material-icons md-18">stop</i></a>
			</div>
		</div>

		<?php if($connections) { ?>
			<?php foreach($connections as $cnt) { ?>
				<div class="mdl-card mdl-cell mdl-cell--4-col">
					<div class="mdl-card__title">
						<h1 class="mdl-card__title-text"><?php if($this->member->token_connection == $cnt->token_connection) { ?><strong><?php echo $cnt->cnt_agent; ?></strong><?php } else { ?><?php echo $cnt->cnt_agent; ?><?php } ?></a></h1>
						<div class="mdl-card__title-infos">
							<span class="mdl-navigation__link"><i class="material-icons md-16">settings_ethernet</i><?php echo $cnt->cnt_ip; ?></span>
							<span class="mdl-navigation__link"><i class="material-icons md-16">access_time</i><span class="timeago" title="<?php echo $this->readerself_library->timezone_datetime($cnt->cnt_datecreated); ?>"></span></span>
						</div>
					</div>
					<div class="mdl-card__supporting-text mdl-color-text--black">
					</div>
				</div>
			<?php } ?>
		<?php } ?>
	</div>
</main>
