<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col share_email_result" id="share_email_<?php echo $itm->itm_id; ?>">
	<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
		<h1 class="mdl-card__title-text"><i class="material-icons md-18">email</i><?php echo $this->lang->line('share_email'); ?></h1>
	</div>
	<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
		<?php echo validation_errors('<p><i class="material-icons md-16">warning</i>', '</p>'); ?>

		<?php echo form_open(current_url(), array('data-itm_id'=>$itm->itm_id)); ?>

		<p>
		<?php echo form_label($this->lang->line('email_subject')); ?>
		<?php echo form_input('email_subject', set_value('email_subject', $itm->itm_title), 'id="email_subject" class="required"'); ?>
		</p>

		<p>
		<?php echo form_label($this->lang->line('email_to')); ?>
		<?php echo form_input('email_to', set_value('email_to'), 'id="email_to" class="valid_email required"'); ?>
		</p>

		<p>
		<?php echo form_label($this->lang->line('email_message')); ?>
		<?php echo form_textarea('email_message', set_value('email_message', ''), 'id="email_message"'); ?>
		</p>

		<p>
		<button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--<?php echo $this->config->item('material-design/colors/background/button'); ?> mdl-color-text--<?php echo $this->config->item('material-design/colors/text/button'); ?>">
			<i class="material-icons md-24">done</i>
		</button>
		</p>

		<?php echo form_close(); ?>
	</div>
	<div class="mdl-card__actions mdl-card--border mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-actions'); ?>">
		<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon share_email_close" href="#share_email_<?php echo $itm->itm_id; ?>"><i class="material-icons md-18">close</i></a></li>
	</div>
</div>

