<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col" id="evernote_<?php echo $itm->itm_id; ?>">
	<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
		<h1 class="mdl-card__title-text"><i class="material-icons md-18">note_add</i>evernote</h1>
		<div class="mdl-card__subtitle-text">
		</div>
	</div>
	<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
		<?php if($status == 'no_token') { ?>
			<p><a href="<?php echo base_url(); ?>evernote">Click here</a> to connect your account</a></p>
		<?php } ?>

		<?php if($status == 'note_added') { ?>
			<p>Note added</p>
		<?php } ?>

		<?php if($status == 'error') { ?>
			<p><?php echo $message; ?></p>
		<?php } ?>
	</div>
	<div class="mdl-card__actions mdl-card--border mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-actions'); ?>">
		<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon share_email_close" href="#evernote_<?php echo $itm->itm_id; ?>"><i class="material-icons md-18">close</i></a></li>
	</div>
</div>

