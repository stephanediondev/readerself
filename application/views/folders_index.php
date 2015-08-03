<div class="mdl-tooltip" for="tip_add"><?php echo $this->lang->line('add'); ?></div>

<main class="mdl-layout__content mdl-color--grey-100">
	<div class="mdl-grid">
		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--white mdl-color--teal">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">folder</i><?php echo $this->lang->line('folders'); ?> (<?php echo $position; ?>)</h2>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a id="tip_add" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>folders/create"><i class="material-icons md-18">add</i></a>
			</div>
		</div>

		<?php if($folders) { ?>
			<?php foreach($folders as $folder) { ?>
				<div<?php if($folder->flr_direction) { ?> dir="<?php echo $folder->flr_direction; ?>"<?php } ?> class="mdl-card mdl-cell mdl-cell--4-col">
					<div class="mdl-card__title">
						<h1 class="mdl-card__title-text"><a href="<?php echo base_url(); ?>folders/read/<?php echo $folder->flr_id; ?>"><i class="icon icon-folder-close"></i><?php echo $folder->flr_title; ?></a></h1>
						<div class="mdl-card__title-infos">
							<span class="mdl-navigation__link"><i class="material-icons md-16">bookmark</i><?php echo $folder->subscriptions; ?> subscription(s)</span>
							<span class="mdl-navigation__link"><i class="material-icons md-16">star</i><?php echo $folder->starred_items; ?> starred item(s)</span>
							<span class="mdl-navigation__link"><i class="material-icons md-16">favorite</i><?php echo $folder->shared_items; ?> shared item(s)</span>
						</div>
					</div>
					<div class="mdl-card__actions mdl-card--border">
						<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>folders/update/<?php echo $folder->flr_id; ?>"><i class="material-icons md-18">mode_edit</i></a>
						<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>folders/delete/<?php echo $folder->flr_id; ?>"><i class="material-icons md-18">delete</i></a>
					</div>
				</div>
			<?php } ?>
			<div class="mdl-card mdl-cell mdl-cell--12-col paging">
				<div class="mdl-card__supporting-text mdl-color-text--grey">
					<?php echo $pagination; ?>
				</div>
			</div>
		<?php } ?>
	</div>
</main>
