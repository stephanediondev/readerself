<main class="mdl-layout__content mdl-color--<?php echo $this->config->item('material-design/colors/background/layout'); ?>">
	<div class="mdl-grid">
		<div class="mdl-tabs mdl-js-ripple-effect">
			<div class="mdl-tabs__tab-bar">
				<a href="<?php echo base_url(); ?>settings" class="mdl-tabs__tab">Theme</a>
				<a href="<?php echo base_url(); ?>settings/goodies" class="mdl-tabs__tab">Goodies</a>
				<a href="<?php echo base_url(); ?>settings/other" class="mdl-tabs__tab">Other</a>
				<a href="<?php echo base_url(); ?>settings/update" class="mdl-tabs__tab is-active">Update</a>
			</div>
		</div>

		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title-highlight'); ?> mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title-highlight'); ?>">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">settings</i>Update</h1>
			</div>
		</div>

		<?php $last = false; ?>
		<?php foreach($entries->entry as $entry) { ?>
			<?php $installed = file_exists('update/'.$entry->title.'.txt'); ?>

			<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col">
				<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
					<h1 class="mdl-card__title-text"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="https://github.com<?php echo $entry->link->attributes()->href; ?>" target="_blank"><?php echo $entry->title; ?></a></h1>
					<div class="mdl-card__subtitle-text">
						<span class="mdl-navigation__link mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>"><i class="material-icons md-16">access_time</i><?php echo substr($entry->updated, 0, 10); ?></span>
					</div>
				</div>
				<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
					<p><?php echo $entry->content; ?></p>

					<?php if(!$installed && !$last) { ?>
						<?php $last = true; ?>
							<p><a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--<?php echo $this->config->item('material-design/colors/background/button'); ?> mdl-color-text--<?php echo $this->config->item('material-design/colors/text/button'); ?>" href="<?php echo base_url(); ?>settings/update/<?php echo $entry->title; ?>"><i class="material-icons md-18">file_download</i></a>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
	</div>
</main>
