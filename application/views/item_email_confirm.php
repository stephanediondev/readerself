<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col share_email_result" id="share_email_<?php echo $itm->itm_id; ?>">
	<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
		<h1 class="mdl-card__title-text"><i class="material-icons md-18">email</i><?php echo $this->lang->line('share_email'); ?></h1>
	</div>
	<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
		<p><?php echo $this->lang->line('email_confirm'); ?></p>
	</div>
	<div class="mdl-card__actions mdl-card--border mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-actions'); ?>">
		<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon share_email_close" href="#share_email_<?php echo $itm->itm_id; ?>"><i class="material-icons md-18">close</i></a></li>
	</div>
</div>

