<div class="mdl-tooltip" for="tip_back"><?php echo $this->lang->line('back'); ?></div>

<main class="mdl-layout__content mdl-color--<?php echo $this->config->item('material-design/colors/background/layout'); ?>">
	<div class="mdl-grid">
		<div class="mdl-card mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title-highlight'); ?> mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title-highlight'); ?>">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">folder</i><?php echo $this->lang->line('folders'); ?></h1>
			</div>
			<div class="mdl-card__actions mdl-card--border mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-actions'); ?>">
				<a id="tip_back" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>folders/read/<?php echo $flr->flr_id; ?>"><i class="material-icons md-18">arrow_back</i></a>
			</div>
		</div>

		<div<?php if($flr->flr_direction) { ?> dir="<?php echo $flr->flr_direction; ?>"<?php } ?> class="mdl-card mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
				<h1 class="mdl-card__title-text"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="<?php echo base_url(); ?>folders/read/<?php echo $folder->flr_id; ?>"><i class="icon icon-folder-close"></i><?php echo $flr->flr_title; ?></a></h1>
				<div class="mdl-card__title-infos">
					<span class="mdl-navigation__link"><i class="material-icons md-16">bookmark</i><?php echo $flr->subscriptions; ?> subscription(s)</span>
					<span class="mdl-navigation__link"><i class="material-icons md-16">star</i><?php echo $flr->starred_items; ?> starred item(s)</span>
					<span class="mdl-navigation__link"><i class="material-icons md-16">favorite</i><?php echo $flr->shared_items; ?> shared item(s)</span>
				</div>
			</div>
			<div class="mdl-card__actions mdl-card--border mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-actions'); ?>">
				<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>folders/delete/<?php echo $flr->flr_id; ?>"><i class="material-icons md-18">delete</i></a>
			</div>
		</div>

		<div class="mdl-card mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
				<h1 class="mdl-card__title-text"><?php echo $this->lang->line('update'); ?></h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
				<?php echo validation_errors(); ?>

				<?php echo form_open(current_url()); ?>

				<p>
				<?php echo form_label($this->lang->line('title'), 'flr_title'); ?>
				<?php echo form_input('flr_title', set_value('flr_title', $flr->flr_title), 'id="flr_title" class="inputtext required"'); ?>
				</p>

				<p>
				<?php echo form_label($this->lang->line('direction'), 'direction'); ?>
				<?php echo form_dropdown('direction', array('' => '-', 'ltr' => $this->lang->line('direction_ltr'), 'rtl' => $this->lang->line('direction_rtl')), set_value('direction', $flr->flr_direction), 'id="direction" class="select numeric"'); ?>
				</p>

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
