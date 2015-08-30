<?php $form = TRUE; ?>

<div class="mdl-tooltip" for="tip_back"><?php echo $this->lang->line('back'); ?></div>

<main class="mdl-layout__content mdl-color--<?php echo $this->config->item('material-design/colors/background/layout'); ?>">
	<div class="mdl-grid">
		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title-highlight'); ?> mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title-highlight'); ?>">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">link</i>Connect to Evernote</h1>
			</div>
			<div class="mdl-card__actions mdl-card--border mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-actions'); ?>">
				<a id="tip_back" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>settings/goodies"><i class="material-icons md-18">arrow_back</i></a>
			</div>
		</div>

		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--3-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
				<h1 class="mdl-card__title-text"><?php if(class_exists('OAuth')) { ?><i class="material-icons md-18">done</i><?php } ?>PHP OAuth Extension</h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
				<?php if(!class_exists('OAuth')) { ?>
					<?php $form = FALSE; ?>
					<p>Missing</p>
				<?php } else { ?>
					<p>Installed</p>
				<?php } ?>
			</div>
		</div>

		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--3-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
				<h1 class="mdl-card__title-text"><?php if(class_exists('Tidy')) { ?><i class="material-icons md-18">done</i><?php } ?>PHP Tidy Extension</h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
				<?php if(!class_exists('Tidy')) { ?>
					<?php $form = FALSE; ?>
					<p>Missing</p>
				<?php } else { ?>
					<p>Installed</p>
				<?php } ?>
			</div>
		</div>

		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
				<h1 class="mdl-card__title-text"><?php if($this->config->item('evernote/consumer_key') && $this->config->item('evernote/consumer_secret')) { ?><i class="material-icons md-18">done</i><?php } ?>API key</h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
				<?php if(!$this->config->item('evernote/consumer_key') || !$this->config->item('evernote/consumer_secret')) { ?>
					<?php $form = FALSE; ?>
					<?php if($this->member->mbr_administrator == 1) { ?>
						<p>Before connecting you must <a href="<?php echo base_url(); ?>settings/other">edit settings</a> and fill in "Evernote / Consumer Key" and "Evernote / Consumer Secret" with the values that you received from Evernote.</p>
						<p>If you do not have an API key, you can request one from <a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="https://dev.evernote.com/">https://dev.evernote.com/</a></p>
					<?php } else { ?>
						<p>Contact administrator</p>
					<?php } ?>
				<?php } else { ?>
					<p>Installed</p>
				<?php } ?>
			</div>
		</div>

		<?php if($form) { ?>
			<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col">
				<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
					<h1 class="mdl-card__title-text"><?php if($token) { ?><i class="material-icons md-18">done</i><?php } ?>Authorize to access your account</h1>
				</div>
				<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
					<?php if($token) { ?>
						<p>Token saved</p>
					<?php } else { ?>
						<p><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="<?php echo base_url(); ?>evernote/authorize">Click here</a> to authorize this application to access your Evernote account. You will be directed to evernote.com to authorize access, then returned to this application after authorization is complete.</p>
					<?php } ?>
				</div>
			</div>
		<?php } ?>

		<?php if($this->member->mbr_administrator == 1) { ?>
			<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
				<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
					<h1 class="mdl-card__title-text"><i class="material-icons md-18">help_outline</i>Tutorial</h1>
				</div>
				<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
					<p>You need to install on your server <a href="http://php.net/manual/en/book.oauth.php" target="_blank">PHP OAuth Extension</a> and <a href="http://php.net/manual/en/book.tidy.php" target="_blank">PHP Tidy Extension</a>.</p>

					<p>Go to <a href="https://dev.evernote.com/" target="_blank">https://dev.evernote.com/</a> and get an API Key.</p>
					<p><img src="medias/evernote_get_api_key.png"></p>

					<p><a href="<?php echo base_url(); ?>settings/other">Edit settings</a>, check "Evernote Enabled" and fill in "Evernote / Consumer Key" and "Evernote / Consumer Secret" with the values that you received from Evernote.</p>
					<p><img src="medias/evernote_settings.png"></p>

					<p>Go to <a href="https://dev.evernote.com/support/" target="_blank">https://dev.evernote.com/support/</a> and activate your API Key (you will receive confirmation by email within a few hours).</p>
					<p><img src="medias/evernote_activate_api_key.png"></p>
				</div>
			</div>
		<?php } ?>
	</div>
</main>
