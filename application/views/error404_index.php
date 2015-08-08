<div class="mdl-tooltip" for="tip_home"><?php echo $this->lang->line('home'); ?></div>

<main class="mdl-layout__content mdl-color--<?php echo $this->config->item('material-design/colors/background/layout'); ?>">
	<div class="mdl-grid">
		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--white mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title'); ?>">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">bug_report</i><?php echo $this->lang->line('error404'); ?></h1>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a id="tip_home" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>"><i class="material-icons md-18">home</i></a>
			</div>
		</div>
	</div>
</main>
