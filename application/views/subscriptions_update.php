<div class="mdl-tooltip" for="tip_back"><?php echo $this->lang->line('back'); ?></div>

<main class="mdl-layout__content mdl-color--<?php echo $this->config->item('material-design/colors/background/layout'); ?>">
	<div class="mdl-grid">
		<div class="mdl-card mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--white mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title'); ?>">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">bookmark</i><?php echo $this->lang->line('subscriptions'); ?></h1>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a id="tip_back" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>subscriptions/read/<?php echo $sub->sub_id; ?>"><i class="material-icons md-18">arrow_back</i></a>
			</div>
		</div>

		<div<?php if($sub->sub_direction) { ?> dir="<?php echo $sub->sub_direction; ?>"<?php } else if($sub->fed_direction) { ?> dir="<?php echo $sub->fed_direction; ?>"<?php } ?> class="mdl-card mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col">
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
			<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
				<?php if($sub->fed_lasterror) { ?>
					<p><?php echo $sub->fed_lasterror; ?></p>
				<?php } ?>
				<?php if($this->config->item('tags') && $sub->categories) { ?>
					<p><?php echo implode(', ', $sub->categories); ?></p>
				<?php } ?>
				<p><?php echo $sub->fed_description; ?></p>
			</div>
		</div>

		<div class="mdl-card mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title">
				<h1 class="mdl-card__title-text"><?php echo $this->lang->line('update'); ?></h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
				<?php echo validation_errors(); ?>

				<?php echo form_open(current_url()); ?>

				<?php if($this->member->mbr_administrator == 1) { ?>
					<p>
					<?php echo form_label($this->lang->line('url'), 'fed_link'); ?>
					<?php echo form_input('fed_link', set_value('fed_link', $sub->fed_link), 'id="fed_link" class="inputtext required"'); ?>
					</p>
				<?php } ?>

				<p>
				<?php echo form_label($this->lang->line('title_alternative'), 'sub_title'); ?>
				<?php echo form_input('sub_title', set_value('sub_title', $sub->sub_title), 'id="sub_title" class="inputtext required"'); ?>
				</p>

				<?php if($this->config->item('folders')) { ?>
					<p>
					<?php echo form_label($this->lang->line('folder'), 'folder'); ?>
					<?php echo form_dropdown('folder', $folders, set_value('folder', $sub->flr_id), 'id="folder" class="select required numeric"'); ?>
					</p>
				<?php } ?>

				<p>
				<?php echo form_label($this->lang->line('priority'), 'priority'); ?>
				<?php echo form_dropdown('priority', array(0 => $this->lang->line('no'), 1 => $this->lang->line('yes')), set_value('priority', $sub->sub_priority), 'id="priority" class="select numeric"'); ?>
				</p>

				<p>
				<?php echo form_label($this->lang->line('direction'), 'direction'); ?>
				<?php echo form_dropdown('direction', array('' => '-', 'ltr' => $this->lang->line('direction_ltr'), 'rtl' => $this->lang->line('direction_rtl')), set_value('direction', $sub->sub_direction), 'id="direction" class="select numeric"'); ?>
				</p>

				<p>
				<button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--<?php echo $this->config->item('material-design/colors/background/button'); ?> mdl-color-text--white">
					<i class="material-icons md-24">done</i>
				</button>
				</p>

				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</main>
