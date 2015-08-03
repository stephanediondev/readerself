<div class="mdl-tooltip" for="tip_back"><?php echo $this->lang->line('back'); ?></div>

<main class="mdl-layout__content mdl-color--grey-100">
	<div class="mdl-grid">
		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--white mdl-color--teal">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">folder</i><?php echo $this->lang->line('folders'); ?></h1>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a id="tip_back" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>folders"><i class="material-icons md-18">arrow_back</i></a>
			</div>
		</div>

		<div<?php if($flr->flr_direction) { ?> dir="<?php echo $flr->flr_direction; ?>"<?php } ?> class="mdl-card mdl-cell mdl-cell--4-col">
			<div class="mdl-card__title">
				<h1 class="mdl-card__title-text"><a href="<?php echo base_url(); ?>folders/read/<?php echo $flr->flr_id; ?>"><i class="icon icon-folder-close"></i><?php echo $flr->flr_title; ?></a></h1>
				<div class="mdl-card__title-infos">
					<span class="mdl-navigation__link"><i class="material-icons md-16">bookmark</i><?php echo $flr->subscriptions; ?> subscription(s)</span>
					<span class="mdl-navigation__link"><i class="material-icons md-16">star</i><?php echo $flr->starred_items; ?> starred item(s)</span>
					<span class="mdl-navigation__link"><i class="material-icons md-16">favorite</i><?php echo $flr->shared_items; ?> shared item(s)</span>
				</div>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>folders/update/<?php echo $flr->flr_id; ?>"><i class="material-icons md-18">mode_edit</i></a>
				<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>folders/delete/<?php echo $flr->flr_id; ?>"><i class="material-icons md-18">delete</i></a>
			</div>
		</div>

		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title">
				<h1 class="mdl-card__title-text"><?php echo $this->lang->line('statistics'); ?></h1>
				<div class="mdl-card__title-infos">
					<span class="mdl-navigation__link">*<?php echo $this->lang->line('last_30_days'); ?></span>
				</div>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--grey">
				<?php echo $tables; ?>
			</div>
		</div>
	</div>
</main>
