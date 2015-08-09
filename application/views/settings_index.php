<main class="mdl-layout__content mdl-color--<?php echo $this->config->item('material-design/colors/background/layout'); ?>">
	<div class="mdl-grid">
		<div class="mdl-card mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title-highlight'); ?> mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title-highlight'); ?>">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">settings</i><?php echo $this->lang->line('settings'); ?></h1>
			</div>
		</div>

		<div class="mdl-card mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col allow_notifications">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
				<h1 class="mdl-card__title-text"><?php echo $this->lang->line('allow_notifications'); ?></h1>
			</div>
			<div class="mdl-card__actions mdl-card--border mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-actions'); ?>">
				<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--<?php echo $this->config->item('material-design/colors/background/button'); ?> mdl-color-text--<?php echo $this->config->item('material-design/colors/text/button'); ?>" href="#"><i class="material-icons md-18">done</i></a></li>
			</div>
		</div>

		<div class="mdl-card mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
				<h1 class="mdl-card__title-text">Bookmarklet</h1>
			</div>
			<div class="mdl-card__actions mdl-card--border mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-actions'); ?>">
				<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--<?php echo $this->config->item('material-design/colors/background/button'); ?> mdl-color-text--<?php echo $this->config->item('material-design/colors/text/button'); ?>" href="javascript:void(function(){window.open('<?php echo base_url(); ?>?u='+encodeURIComponent(window.location.href),'_blank');}());"><i class="material-icons md-18">done</i></a></li>
			</div>
		</div>

		<div class="mdl-card mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col registerContentHandler">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
				<h1 class="mdl-card__title-text"><?php echo $this->lang->line('registerContentHandler'); ?></h1>
			</div>
			<div class="mdl-card__actions mdl-card--border mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-actions'); ?>">
				<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--<?php echo $this->config->item('material-design/colors/background/button'); ?> mdl-color-text--<?php echo $this->config->item('material-design/colors/text/button'); ?>" href="#"><i class="material-icons md-18">done</i></a></li>
			</div>
		</div>
	</div>

	<?php if($this->member->mbr_administrator == 1) { ?>
		<?php echo form_open(current_url()); ?>
		<div class="mdl-grid">
			<div class="mdl-card mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--6-col">
				<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
					<h1 class="mdl-card__title-text">Material Design</h1>
				</div>
				<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
					<?php echo validation_errors(); ?>

					<?php foreach($settings_material as $stg) { ?>
						<p>
						<?php echo form_label($this->lang->line('stg_'.$stg->stg_code), $stg->stg_code); ?>
						<?php echo form_input($stg->stg_code, set_value($stg->stg_code, $stg->stg_value), 'class="material_color" id="'.$stg->stg_code.'"'); ?>
						<?php if($stg->stg_note) { ?><br><em><?php echo $stg->stg_note; ?></em><?php } ?>
						</p>
					<?php } ?>

					<p>
					<button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--<?php echo $this->config->item('material-design/colors/background/button'); ?> mdl-color-text--<?php echo $this->config->item('material-design/colors/text/button'); ?>">
						<i class="material-icons md-24">done</i>
					</button>
					</p>
				</div>
			</div>
			<div class="mdl-cell mdl-cell--6-col" id="preview">
			</div>

			<div class="mdl-card mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--6-col">
				<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
					<h1 class="mdl-card__title-text"><?php echo $this->lang->line('update'); ?></h1>
				</div>
				<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
					<?php echo validation_errors(); ?>

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
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	<?php } ?>
</main>
