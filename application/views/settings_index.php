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
		<div class="mdl-grid">
			<div class="mdl-card mdl-color--red mdl-cell mdl-cell--2-col" title="Red"><div class="mdl-card__supporting-text mdl-color-text--white">red<br>#F44336</div></div>
			<div class="mdl-card mdl-color--pink mdl-cell mdl-cell--2-col" title="Pink"><div class="mdl-card__supporting-text mdl-color-text--white">pink<br>#E91E63</div></div>
			<div class="mdl-card mdl-color--purple mdl-cell mdl-cell--2-col" title="Purple"><div class="mdl-card__supporting-text mdl-color-text--white">purple<br>#9C27B0</div></div>
			<div class="mdl-card mdl-color--deep-purple mdl-cell mdl-cell--2-col" title="Deep Purple"><div class="mdl-card__supporting-text mdl-color-text--white">deep-purple<br>#673AB7</div></div>
			<div class="mdl-card mdl-color--indigo mdl-cell mdl-cell--2-col" title="Indigo"><div class="mdl-card__supporting-text mdl-color-text--white">indigo<br>#3F51B5</div></div>
			<div class="mdl-card mdl-color--blue mdl-cell mdl-cell--2-col" title="Blue"><div class="mdl-card__supporting-text mdl-color-text--white">blue<br>#2196F3</div></div>
			<div class="mdl-card mdl-color--light-blue mdl-cell mdl-cell--2-col" title="Light Blue"><div class="mdl-card__supporting-text mdl-color-text--black">light-blue<br>#03A9F4</div></div>
			<div class="mdl-card mdl-color--cyan mdl-cell mdl-cell--2-col" title="Cyan"><div class="mdl-card__supporting-text mdl-color-text--black">cyan<br>#00BCD4</div></div>
			<div class="mdl-card mdl-color--teal mdl-cell mdl-cell--2-col" title="Teal"><div class="mdl-card__supporting-text mdl-color-text--white">teal<br>#009688</div></div>
			<div class="mdl-card mdl-color--green mdl-cell mdl-cell--2-col" title="Green"><div class="mdl-card__supporting-text mdl-color-text--black">green<br>#4CAF50</div></div>
			<div class="mdl-card mdl-color--light-green mdl-cell mdl-cell--2-col" title="Light Green"><div class="mdl-card__supporting-text mdl-color-text--black">light-green<br>#8BC34A</div></div>
			<div class="mdl-card mdl-color--lime mdl-cell mdl-cell--2-col" title="Lime"><div class="mdl-card__supporting-text mdl-color-text--black">lime<br>#CDDC39</div></div>
			<div class="mdl-card mdl-color--yellow mdl-cell mdl-cell--2-col" title="Yellow"><div class="mdl-card__supporting-text mdl-color-text--black">yellow<br>#FFEB3B</div></div>
			<div class="mdl-card mdl-color--amber mdl-cell mdl-cell--2-col" title="Amber"><div class="mdl-card__supporting-text mdl-color-text--black">amber<br>#FFC107</div></div>
			<div class="mdl-card mdl-color--orange mdl-cell mdl-cell--2-col" title="Orange"><div class="mdl-card__supporting-text mdl-color-text--black">orange<br>#FF9800</div></div>
			<div class="mdl-card mdl-color--deep-orange mdl-cell mdl-cell--2-col" title="Deep Orange"><div class="mdl-card__supporting-text mdl-color-text--white">deep-orange<br>#FF5722</div></div>
			<div class="mdl-card mdl-color--brown mdl-cell mdl-cell--2-col" title="Brown"><div class="mdl-card__supporting-text mdl-color-text--white">brown<br>#795548</div></div>
			<div class="mdl-card mdl-color--grey mdl-cell mdl-cell--2-col" title="Grey"><div class="mdl-card__supporting-text mdl-color-text--black">grey<br>#9E9E9E</div></div>
			<div class="mdl-card mdl-color--blue-grey mdl-cell mdl-cell--2-col" title="Blue Grey"><div class="mdl-card__supporting-text mdl-color-text--white">blue-grey<br>#607D8B</div></div>
			<div class="mdl-card mdl-color--black mdl-cell mdl-cell--2-col" title="Black"><div class="mdl-card__supporting-text mdl-color-text--white">black<br>#000000</div></div>
			<div class="mdl-card mdl-color--white mdl-cell mdl-cell--2-col" title="White"><div class="mdl-card__supporting-text mdl-color-text--black">white<br>#FFFFFF</div></div>
		</div>

		<?php echo form_open(current_url()); ?>
		<div class="mdl-grid">
			<div class="mdl-card mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--6-col">
				<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
					<h1 class="mdl-card__title-text">Material Design</h1>
				</div>
				<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
					<?php echo validation_errors(); ?>

					<div id="themes">
					</div>

					<?php foreach($settings_material as $stg) { ?>
						<p>
						<?php echo form_label($this->lang->line('stg_'.$stg->stg_code), str_replace('/', '_', $stg->stg_code)); ?>
						<?php echo form_input($stg->stg_code, set_value($stg->stg_code, $stg->stg_value), 'class="material_color" id="'.str_replace('/', '_', $stg->stg_code).'"'); ?>
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
