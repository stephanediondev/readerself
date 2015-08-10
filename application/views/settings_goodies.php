<main class="mdl-layout__content mdl-color--<?php echo $this->config->item('material-design/colors/background/layout'); ?>">
	<div class="mdl-grid">
		<div class="mdl-tabs mdl-js-ripple-effect">
			<div class="mdl-tabs__tab-bar">
				<a href="<?php echo base_url(); ?>settings" class="mdl-tabs__tab">Theme</a>
				<a href="<?php echo base_url(); ?>settings/goodies" class="mdl-tabs__tab is-active">Goodies</a>
				<?php if($this->member->mbr_administrator == 1) { ?>
					<a href="<?php echo base_url(); ?>settings/other" class="mdl-tabs__tab">Other</a>
				<?php } ?>
			</div>
		</div>

		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title-highlight'); ?> mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title-highlight'); ?>">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">settings</i>Goodies</h1>
			</div>
		</div>

		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col allow_notifications">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
				<h1 class="mdl-card__title-text"><?php echo $this->lang->line('allow_notifications'); ?></h1>
			</div>
			<div class="mdl-card__actions mdl-card--border mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-actions'); ?>">
				<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--<?php echo $this->config->item('material-design/colors/background/button'); ?> mdl-color-text--<?php echo $this->config->item('material-design/colors/text/button'); ?>" href="#"><i class="material-icons md-18">done</i></a></li>
			</div>
		</div>

		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
				<h1 class="mdl-card__title-text">Bookmarklet</h1>
			</div>
			<div class="mdl-card__actions mdl-card--border mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-actions'); ?>">
				<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--<?php echo $this->config->item('material-design/colors/background/button'); ?> mdl-color-text--<?php echo $this->config->item('material-design/colors/text/button'); ?>" href="javascript:void(function(){window.open('<?php echo base_url(); ?>?u='+encodeURIComponent(window.location.href),'_blank');}());"><i class="material-icons md-18">done</i></a></li>
			</div>
		</div>

		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col registerContentHandler">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
				<h1 class="mdl-card__title-text"><?php echo $this->lang->line('registerContentHandler'); ?></h1>
			</div>
			<div class="mdl-card__actions mdl-card--border mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-actions'); ?>">
				<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--<?php echo $this->config->item('material-design/colors/background/button'); ?> mdl-color-text--<?php echo $this->config->item('material-design/colors/text/button'); ?>" href="#"><i class="material-icons md-18">done</i></a></li>
			</div>
		</div>
	</div>
</main>
