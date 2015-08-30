<div class="mdl-tooltip" for="tip_back"><?php echo $this->lang->line('back'); ?></div>

<main class="mdl-layout__content mdl-color--<?php echo $this->config->item('material-design/colors/background/layout'); ?>">
	<div class="mdl-grid">
		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title-highlight'); ?> mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title-highlight'); ?>">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">link</i>Connect to Instagram</h1>
			</div>
			<div class="mdl-card__actions mdl-card--border mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-actions'); ?>">
				<a id="tip_back" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>settings/goodies"><i class="material-icons md-18">arrow_back</i></a>
			</div>
		</div>

		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
				<h1 class="mdl-card__title-text"><?php if($this->config->item('instagram/client_id') && $this->config->item('instagram/client_secret')) { ?><i class="material-icons md-18">done</i><?php } ?>Client id and secret</h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
				<?php if(!$this->config->item('instagram/client_id') || !$this->config->item('instagram/client_secret')) { ?>
					<p>Client id and secret not defined</p>
				<?php } else { ?>
					<p>Client id and secret defined</p>
				<?php } ?>
			</div>
		</div>

		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
				<h1 class="mdl-card__title-text"><?php if($this->config->item('instagram/access_token')) { ?><i class="material-icons md-18">done</i><?php } ?>Access token</h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
				<?php if(!$this->config->item('instagram/access_token')) { ?>
					<p>Access token not defined</p>
				<?php } else { ?>
					<p>Access token defined</p>
				<?php } ?>
			</div>
		</div>

		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">help_outline</i>Tutorial</h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
				<p>Go to <a href="https://instagram.com/developer/clients/register/" target="_blank">https://instagram.com/developer/clients/register/</a> and register a new Client ID.</p>
				<p>Fill the form with your valid redirect URI: <?php echo base_url(); ?>instagram/callback</p>
				<p>Validate registration</p>
				<p><img src="medias/instagram_register_details.png"></p>
				<p><img src="medias/instagram_register_security.png"></p>

				<p><a href="<?php echo base_url(); ?>settings/other">Update settings</a> and fill in "Instagram / Client id" and "Instagram / Client secret" with the values that you received from Instagram (check "Instagram enabled" also).</p>
				<p><img src="medias/instagram_settings.png"></p>

				<p><a href="<?php echo base_url(); ?>instagram/authorize">Authorize</a> your application with your Instagram account (the access token will be saved automatically on callback)</p>
			</div>
		</div>
	</div>
</main>
