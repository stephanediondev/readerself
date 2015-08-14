<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col evernote_result" id="evernote_<?php echo $itm->itm_id; ?>">
	<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
		<h1 class="mdl-card__title-text"><i class="material-icons md-18">note_add</i>Send article to Evernote</h1>
		<div class="mdl-card__subtitle-text">
		</div>
	</div>
	<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
		<?php echo validation_errors('<p><i class="material-icons md-16">warning</i>', '</p>'); ?>

		<?php echo form_open(current_url(), array('data-itm_id'=>$itm->itm_id)); ?>

		<p>
		<?php echo form_label('Notebook'); ?>
		<?php echo form_dropdown('notebook', $notebooks, set_value('notebook', ''), 'id="notebook" class="required"'); ?>
		</p>

		<p>
		<button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--<?php echo $this->config->item('material-design/colors/background/button'); ?> mdl-color-text--<?php echo $this->config->item('material-design/colors/text/button'); ?>">
			<i class="material-icons md-24">done</i>
		</button>
		</p>

		<?php echo form_close(); ?>
	</div>
	<div class="mdl-card__actions mdl-card--border mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-actions'); ?>">
		<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon share_email_close" href="#evernote_<?php echo $itm->itm_id; ?>"><i class="material-icons md-18">close</i></a></li>
	</div>
</div>

