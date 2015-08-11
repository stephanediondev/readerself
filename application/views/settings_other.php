<main class="mdl-layout__content mdl-color--<?php echo $this->config->item('material-design/colors/background/layout'); ?>">
	<div class="mdl-grid">
		<div class="mdl-tabs mdl-js-ripple-effect">
			<div class="mdl-tabs__tab-bar">
				<a href="<?php echo base_url(); ?>settings" class="mdl-tabs__tab">Theme</a>
				<a href="<?php echo base_url(); ?>settings/goodies" class="mdl-tabs__tab">Goodies</a>
				<a href="<?php echo base_url(); ?>settings/other" class="mdl-tabs__tab is-active">Other</a>
				<a href="<?php echo base_url(); ?>settings/update" class="mdl-tabs__tab">Update</a>
			</div>
		</div>

		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title-highlight'); ?> mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title-highlight'); ?>">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">settings</i>Other</h1>
			</div>
		</div>

		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--6-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
				<h1 class="mdl-card__title-text"><?php echo $this->lang->line('update'); ?></h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
				<?php echo validation_errors('<p><i class="material-icons md-16">warning</i>', '</p>'); ?>

				<?php if($facebook_error) { ?>
					<p><i class="material-icons md-16">warning</i><?php echo $facebook_error; ?></p>
				<?php } ?>

				<?php if($readability_error) { ?>
					<p><i class="material-icons md-16">warning</i><?php echo $readability_error; ?></p>
				<?php } ?>

				<?php echo form_open(current_url()); ?>

				<?php foreach($settings as $stg) { ?>
					<p>
					<?php if($stg->stg_type == 'boolean') { ?>
						<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-<?php echo $stg->stg_code; ?>">
							<input type="checkbox" id="checkbox-<?php echo $stg->stg_code; ?>" name="<?php echo $stg->stg_code; ?>" value="1" class="mdl-checkbox__input"<?php if($stg->stg_value) { ?> checked<?php } ?>>
							<span class="mdl-checkbox__label"><?php echo $this->lang->line('stg_'.$stg->stg_code); ?></span>
						</label>
					<?php } else { ?>
						<?php echo form_label($this->lang->line('stg_'.$stg->stg_code), $stg->stg_code); ?>
						<?php echo form_input($stg->stg_code, set_value($stg->stg_code, $stg->stg_value), 'id="'.$stg->stg_code.'"'); ?>
					<?php } ?>
					<?php if($stg->stg_note) { ?><br><em><?php echo $stg->stg_note; ?></em><?php } ?>
					</p>
				<?php } ?>

				<p>
				<button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--<?php echo $this->config->item('material-design/colors/background/button'); ?> mdl-color-text--<?php echo $this->config->item('material-design/colors/text/button'); ?>">
					<i class="material-icons md-24">done</i>
				</button>
				</p>

				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</main>
