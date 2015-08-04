<div class="mdl-tooltip" for="tip_add"><?php echo $this->lang->line('add'); ?></div>
<div class="mdl-tooltip" for="tip_import"><?php echo $this->lang->line('import'); ?></div>
<div class="mdl-tooltip" for="tip_export"><?php echo $this->lang->line('export'); ?></div>

<main class="mdl-layout__content mdl-color--grey-100">
	<div class="mdl-grid">
		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--white mdl-color--teal">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">bookmark</i><?php echo $this->lang->line('subscriptions'); ?> (<?php echo $position; ?>)</h1>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a id="tip_add" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>subscriptions/create"><i class="material-icons md-18">add</i></a>
				<a id="tip_import" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>subscriptions/import"><i class="material-icons md-18">file_download</i></a>
				<a id="tip_export" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>subscriptions/export"><i class="material-icons md-18">file_upload</i></a>
			</div>
		</div>

		<?php if($subscriptions) { ?>
			<?php foreach($subscriptions as $sub) { ?>
				<div<?php if($sub->sub_direction) { ?> dir="<?php echo $sub->sub_direction; ?>"<?php } else if($sub->fed_direction) { ?> dir="<?php echo $sub->fed_direction; ?>"<?php } ?> class="mdl-card mdl-cell mdl-cell--4-col">
					<div class="mdl-card__title">
						<h1 class="mdl-card__title-text"><a style="background-image:url(https://www.google.com/s2/favicons?domain=<?php echo $sub->fed_host; ?>&amp;alt=feed);" class="favicon" href="<?php echo base_url(); ?>subscriptions/read/<?php echo $sub->sub_id; ?>"><?php echo $sub->fed_title; ?><?php if($sub->sub_title) { ?> / <em><?php echo $sub->sub_title; ?></em><?php } ?></a></h1>
						<div class="mdl-card__title-infos">
							<?php if($sub->fed_url) { ?>
								<a class="mdl-navigation__link" href="<?php echo $sub->fed_url; ?>" target="_blank"><i class="material-icons md-16">open_in_new</i><?php echo $sub->fed_url; ?></a>
							<?php } ?>
							<?php if($this->config->item('folders')) { ?>
								<?php if($sub->flr_title) { ?><a class="mdl-navigation__link" href="<?php echo base_url(); ?>folders/read/<?php echo $sub->flr_id; ?>"><i class="material-icons md-16">folder</i><?php echo $sub->flr_title; ?></a><?php } ?>
							<?php } ?>
						</div>
					</div>
					<div class="mdl-card__supporting-text mdl-color-text--grey">
						<?php if($sub->fed_lasterror) { ?>
							<p><?php echo $sub->fed_lasterror; ?></p>
						<?php } ?>
						<?php if($this->config->item('tags') && $sub->categories) { ?>
							<p><?php echo implode(', ', $sub->categories); ?></p>
						<?php } ?>
						<p><?php echo $sub->fed_description; ?></p>
					</div>
					<div class="mdl-card__actions mdl-card--border">
						<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>subscriptions/update/<?php echo $sub->sub_id; ?>"><i class="material-icons md-18">mode_edit</i></a>
						<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon subscribe" href="<?php echo base_url(); ?>subscriptions/delete/<?php echo $sub->sub_id; ?>"><i class="material-icons md-18">delete</i></a>
						<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon priority" href="<?php echo base_url(); ?>subscriptions/priority/<?php echo $sub->sub_id; ?>"><?php if($sub->sub_priority == 0) { ?><i class="material-icons md-18">chat_bubble_outline</i><?php } ?><?php if($sub->sub_priority == 1) { ?><i class="material-icons md-18">announcement</i><?php } ?></a>
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
