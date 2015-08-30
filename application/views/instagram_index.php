<?php $form = TRUE; ?>

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
				<h1 class="mdl-card__title-text"><?php if($this->config->item('instagram/client_id') && $this->config->item('instagram/client_secret')) { ?><i class="material-icons md-18">done</i><?php } ?>Client id</h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
				<?php if(!$this->config->item('instagram/client_id') || !$this->config->item('instagram/client_secret')) { ?>
					<?php $form = FALSE; ?>
					<?php if($this->member->mbr_administrator == 1) { ?>
						<p>Before connecting you must <a href="<?php echo base_url(); ?>settings/other">edit settings</a> and fill in "Instagram / Client id" and "Instagram / Client secret" with the values that you received from Instagram.</p>
						<p>If you do not have an Client id, you can request one from <a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="https://instagram.com/developer/clients/register/">https://instagram.com/developer/clients/register/</a></p>
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
					<?php if($this->config->item('instagram/access_token')) { ?>
						<p>Access token saved</p>
					<?php } ?>
					<p><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="<?php echo base_url(); ?>instagram/authorize">Click here</a> to authorize this application to access your Instagram account. You will be directed to instagram.com to authorize access, then returned to this application after authorization is complete.</p>
				</div>
			</div>
		<?php } ?>
	</div>
</main>
