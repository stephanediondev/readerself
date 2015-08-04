<div class="mdl-tooltip" for="tip_back"><?php echo $this->lang->line('back'); ?></div>

<main class="mdl-layout__content mdl-color--grey-100">
	<div class="mdl-grid">
		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--white mdl-color--teal">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">bookmark</i><?php echo $this->lang->line('subscriptions'); ?></h1>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a id="tip_back" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>subscriptions/read/<?php echo $sub->sub_id; ?>"><i class="material-icons md-18">arrow_back</i></a>
			</div>
		</div>

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
		</div>

		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title">
				<h1 class="mdl-card__title-text"><?php echo $this->lang->line('unsubscribe'); ?></h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--grey">
				<?php echo validation_errors(); ?>

				<?php echo form_open(current_url()); ?>

				<p>
				<?php echo form_label($this->lang->line('confirm').' *', 'confirm'); ?>
				<?php echo form_checkbox('confirm', '1', FALSE, 'id="confirm" class="inputcheckbox"'); ?>
				</p>

				<p>
				<button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--pink mdl-color-text--white">
					<i class="material-icons md-24">done</i>
				</button>
				</p>

				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</main>
