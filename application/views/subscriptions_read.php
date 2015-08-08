<div class="mdl-tooltip" for="tip_back"><?php echo $this->lang->line('back'); ?></div>

<main class="mdl-layout__content mdl-color--<?php echo $this->config->item('material-design/colors/background/layout'); ?>">
	<div class="mdl-grid">
		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--white mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title'); ?>">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">bookmark</i><?php echo $this->lang->line('subscriptions'); ?></h1>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a id="tip_back" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>subscriptions"><i class="material-icons md-18">arrow_back</i></a>
			</div>
		</div>

		<div<?php if($sub->sub_direction) { ?> dir="<?php echo $sub->sub_direction; ?>"<?php } else if($sub->fed_direction) { ?> dir="<?php echo $sub->fed_direction; ?>"<?php } ?> class="mdl-card mdl-cell mdl-cell--4-col">
			<div class="mdl-card__title">
				<h1 class="mdl-card__title-text"><a style="background-image:url(https://www.google.com/s2/favicons?domain=<?php echo $sub->fed_host; ?>&amp;alt=feed);" class="favicon mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="<?php echo base_url(); ?>subscriptions/read/<?php echo $sub->sub_id; ?>"><?php echo $sub->fed_title; ?><?php if($sub->sub_title) { ?> / <em><?php echo $sub->sub_title; ?></em><?php } ?></a></h1>
				<div class="mdl-card__title-infos">
					<?php if($sub->fed_url) { ?>
						<a class="mdl-navigation__link" href="<?php echo $sub->fed_url; ?>" target="_blank"><i class="material-icons md-16">open_in_new</i><?php echo $sub->fed_url; ?></a>
					<?php } ?>
					<?php if($this->config->item('folders')) { ?>
						<?php if($sub->flr_title) { ?><a class="mdl-navigation__link" href="<?php echo base_url(); ?>folders/read/<?php echo $sub->flr_id; ?>"><i class="material-icons md-16">folder</i><?php echo $sub->flr_title; ?></a><?php } ?>
					<?php } ?>
				</div>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--black">
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

		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--white mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title'); ?>">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">insert_chart</i><?php echo $this->lang->line('statistics'); ?></h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--black">
				<p><em>*<?php echo $this->lang->line('last_30_days'); ?></em></p>
			</div>
		</div>

		<?php echo $tables; ?>
	</div>
</main>
