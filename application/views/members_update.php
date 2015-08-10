<div class="mdl-tooltip" for="tip_back"><?php echo $this->lang->line('back'); ?></div>

<main class="mdl-layout__content mdl-color--<?php echo $this->config->item('material-design/colors/background/layout'); ?>">
	<div class="mdl-grid">
		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title-highlight'); ?> mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title-highlight'); ?>">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">people</i><?php echo $this->lang->line('members'); ?></h1>
			</div>
			<div class="mdl-card__actions mdl-card--border mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-actions'); ?>">
				<a id="tip_back" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>members"><i class="material-icons md-18">arrow_back</i></a>
			</div>
		</div>

		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
				<?php if($mbr->mbr_nickname) { ?>
					<h1 class="mdl-card__title-text"><a href="<?php echo base_url(); ?>member/<?php echo $mbr->mbr_nickname; ?>"><i class="material-icons md-16">person</i><?php if($this->member->mbr_administrator == 1) { ?><?php echo $mbr->mbr_email; ?> / <?php } ?><?php echo $mbr->mbr_nickname; ?></a></h1>
				<?php } else { ?>
					<h1 class="mdl-card__title-text"><i class="material-icons md-16">person</i><?php echo $mbr->mbr_email; ?></h1>
				<?php } ?>
				<div class="mdl-card__subtitle-text">
					<?php if($mbr->subscriptions_common) { ?>
						<span class="mdl-navigation__link"><i class="material-icons md-16">bookmark</i><?php echo $mbr->subscriptions_common; ?> subscription(s) in common</span>
					<?php } ?>
					<span class="mdl-navigation__link"><i class="material-icons md-16">favorite</i><?php echo $mbr->shared_items; ?> shared item(s)</span>
				</div>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
				<?php if($mbr->mbr_description) { ?>
					<p><?php echo strip_tags($mbr->mbr_description); ?></p>
				<?php } ?>
			</div>
		</div>

		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
				<h1 class="mdl-card__title-text"><?php echo $this->lang->line('update'); ?></h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
				<?php echo validation_errors('<p><i class="material-icons md-16">warning</i>', '</p>'); ?>

				<?php echo form_open(current_url()); ?>

				<p>
				<?php echo form_label($this->lang->line('description'), 'mbr_description'); ?>
				<?php echo form_textarea('mbr_description', set_value('mbr_description', $mbr->mbr_description), 'id="mbr_description"'); ?>
				</p>

				<?php if($mbr->mbr_id != $this->member->mbr_id) { ?>
					<p>
					<?php echo form_label($this->lang->line('mbr_administrator'), 'mbr_administrator'); ?>
					<?php echo form_dropdown('mbr_administrator', array(0 => $this->lang->line('no'), 1 => $this->lang->line('yes')), set_value('mbr_administrator', $mbr->mbr_administrator), 'id="mbr_administrator" class="select numeric"'); ?>
					</p>
				<?php } ?>

				<p>
				<button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--<?php echo $this->config->item('material-design/colors/background/button'); ?> mdl-color-text--<?php echo $this->config->item('material-design/colors/text/button'); ?>">
					<i class="material-icons md-24">done</i>
				</button>
				</p>

				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</main>
