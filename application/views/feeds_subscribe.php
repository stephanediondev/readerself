<div class="mdl-tooltip" for="tip_back"><?php echo $this->lang->line('back'); ?></div>

<main class="mdl-layout__content mdl-color--grey-100">
	<div class="mdl-grid">
		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--white mdl-color--teal">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">bookmark_border</i><?php echo $this->lang->line('feeds'); ?></h1>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a id="tip_back" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>feeds/read/<?php echo $fed->fed_id; ?>"><i class="material-icons md-18">arrow_back</i></a>
			</div>
		</div>

		<div<?php if($fed->fed_direction) { ?> dir="<?php echo $fed->fed_direction; ?>"<?php } ?> class="mdl-card mdl-cell mdl-cell--4-col">
			<div class="mdl-card__title">
				<h1 class="mdl-card__title-text"><a style="background-image:url(https://www.google.com/s2/favicons?domain=<?php echo $fed->fed_host; ?>&amp;alt=feed);" class="favicon" href="<?php echo base_url(); ?>feeds/read/<?php echo $fed->fed_id; ?>"><?php echo $fed->fed_title; ?></a></h1>
				<div class="mdl-card__title-infos">
					<?php if($fed->fed_url) { ?>
						<a class="mdl-navigation__link" href="<?php echo $fed->fed_url; ?>" target="_blank"><i class="material-icons md-16">open_in_new</i><?php echo $fed->fed_url; ?></a>
					<?php } ?>
				</div>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--grey">
				<?php if($fed->fed_lasterror) { ?>
					<p><?php echo $fed->fed_lasterror; ?></p>
				<?php } ?>
				<?php if($this->config->item('tags') && $fed->categories) { ?>
					<p><?php echo implode(', ', $fed->categories); ?></p>
				<?php } ?>
				<p><?php echo $fed->fed_description; ?></p>
			</div>
		</div>

		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title">
				<h1 class="mdl-card__title-text"><?php echo $this->lang->line('subscribe'); ?></h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--grey">
				<?php echo validation_errors(); ?>

				<?php echo form_open(current_url()); ?>

				<?php if($this->config->item('folders')) { ?>
					<p>
					<?php echo form_label($this->lang->line('folder'), 'folder'); ?>
					<?php echo form_dropdown('folder', $folders, set_value('folder', ''), 'id="folder" class="select required numeric"'); ?>
					</p>
				<?php } ?>

				<p>
				<?php echo form_label($this->lang->line('priority'), 'priority'); ?>
				<?php echo form_dropdown('priority', array(0 => $this->lang->line('no'), 1 => $this->lang->line('yes')), set_value('priority', 0), 'id="priority" class="select numeric"'); ?>
				</p>

				<p>
				<?php echo form_label($this->lang->line('direction'), 'direction'); ?>
				<?php echo form_dropdown('direction', array('' => '-', 'ltr' => $this->lang->line('direction_ltr'), 'rtl' => $this->lang->line('direction_rtl')), set_value('direction', $fed->fed_direction), 'id="direction" class="select numeric"'); ?>
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
