<main class="mdl-layout__content mdl-color--<?php echo $this->config->item('material-design/colors/background/layout'); ?>">
	<div class="mdl-grid">
		<div class="mdl-card mdl-cell mdl-cell--4-col">
			<div class="mdl-card__title mdl-color-text--white mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title'); ?>">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">vpn_key</i><?php echo $this->lang->line('password'); ?></h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--black">
				<p>
				<?php echo $mbr_password; ?>
				</p>
			</div>
		</div>
	</div>
</main>
