<div class="mdl-tooltip" for="tip_back"><?php echo $this->lang->line('back'); ?></div>

<main class="mdl-layout__content mdl-color--grey-100">
	<div class="mdl-grid">
		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--white mdl-color--teal">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">bookmark</i><?php echo $this->lang->line('subscriptions'); ?></h1>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a id="tip_back" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>subscriptions"><i class="material-icons md-18">arrow_back</i></a>
			</div>
		</div>

		<div class="mdl-card mdl-cell mdl-cell--4-col">
			<div class="mdl-card__title">
				<h1 class="mdl-card__title-text"><?php echo $this->lang->line('import'); ?></h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--grey">
				<?php echo validation_errors(); ?>

				<?php echo form_open_multipart(current_url()); ?>

				<input type="hidden" name="hidden">

				<p>
				<?php echo form_label($this->lang->line('opml_file'), 'file'); ?>
				<?php echo form_upload('file', false, 'id="file" class="required"'); ?>
				</p>

				<p>
				<button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--pink mdl-color-text--white">
					<i class="material-icons md-24">done</i>
				</button>
				</p>

				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</main>
