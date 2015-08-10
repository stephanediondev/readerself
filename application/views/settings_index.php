<div class="mdl-layout__drawer">
	<div class="mdl-grid" id="colors">
		<?php foreach($colors as $code => $hexa) { ?>
			<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $code; ?> mdl-cell mdl-cell--12-col" data-code="<?php echo $code; ?>" data-hexa="#<?php echo $hexa; ?>">
				<div class="mdl-card__supporting-text mdl-color-text--<?php if(in_array($code, $color_black_text)) { ?>black<?php } else { ?>white<?php } ?>">
					<?php echo ucwords(str_replace('-', ' ', $code)); ?><br>#<?php echo $hexa; ?>
				</div>
			</div>
		<?php } ?>
	</div>
</div>

<main class="mdl-layout__content mdl-color--<?php echo $this->config->item('material-design/colors/background/layout'); ?>">
	<div class="mdl-grid">
		<div class="mdl-tabs mdl-js-ripple-effect">
			<div class="mdl-tabs__tab-bar">
				<a href="<?php echo base_url(); ?>settings" class="mdl-tabs__tab is-active">Theme</a>
				<a href="<?php echo base_url(); ?>settings/goodies" class="mdl-tabs__tab">Goodies</a>
				<?php if($this->member->mbr_administrator == 1) { ?>
					<a href="<?php echo base_url(); ?>settings/other" class="mdl-tabs__tab">Other</a>
				<?php } ?>
			</div>
		</div>

		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title-highlight'); ?> mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title-highlight'); ?>">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">settings</i>Theme</h1>
			</div>
		</div>

		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--6-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
				<h1 class="mdl-card__title-text"><?php echo $this->lang->line('update'); ?></h1>
				<div class="mdl-card__subtitle-text" id="themes">
				</div>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
				<?php echo validation_errors('<p><i class="material-icons md-16">warning</i>', '</p>'); ?>

				<?php echo form_open(current_url()); ?>

				<?php foreach($settings_material as $stg) { ?>
					<p>
					<?php echo form_label($this->lang->line('stg_'.$stg->stg_code), str_replace('/', '_', $stg->stg_code)); ?>
					<?php if($stg->stg_code == 'material-design/colors/meta/theme') { ?>
						<?php echo form_input($stg->stg_code, set_value($stg->stg_code, $stg->stg_value), 'class="material_color material_color_hexa" id="'.str_replace('/', '_', $stg->stg_code).'"'); ?>
					<?php } else { ?>
						<?php echo form_input($stg->stg_code, set_value($stg->stg_code, $stg->stg_value), 'class="material_color" id="'.str_replace('/', '_', $stg->stg_code).'"'); ?>
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
		<div class="mdl-cell mdl-cell--6-col" id="preview">
		</div>
	</div>
</main>
