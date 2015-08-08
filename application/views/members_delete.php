<div class="mdl-tooltip" for="tip_back"><?php echo $this->lang->line('back'); ?></div>

<main class="mdl-layout__content mdl-color--<?php echo $this->config->item('material-design/colors/background/layout'); ?>">
	<div class="mdl-grid">
		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--white mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title'); ?>">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">people</i><?php echo $this->lang->line('members'); ?></h1>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a id="tip_back" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>members"><i class="material-icons md-18">arrow_back</i></a>
			</div>
		</div>

		<div class="mdl-card mdl-cell mdl-cell--4-col">
			<div class="mdl-card__title">
				<?php if($mbr->mbr_nickname) { ?>
					<h1 class="mdl-card__title-text"><a href="<?php echo base_url(); ?>member/<?php echo $mbr->mbr_nickname; ?>"><i class="material-icons md-16">person</i><?php if($this->member->mbr_administrator == 1) { ?><?php echo $mbr->mbr_email; ?> / <?php } ?><?php echo $mbr->mbr_nickname; ?></a></h1>
				<?php } else { ?>
					<h1 class="mdl-card__title-text"><i class="material-icons md-16">person</i><?php echo $mbr->mbr_email; ?></h1>
				<?php } ?>
				<div class="mdl-card__title-infos">
					<?php if($mbr->subscriptions_common) { ?>
						<span class="mdl-navigation__link"><i class="material-icons md-16">bookmark</i><?php echo $mbr->subscriptions_common; ?> subscription(s) in common</span>
					<?php } ?>
					<span class="mdl-navigation__link"><i class="material-icons md-16">favorite</i><?php echo $mbr->shared_items; ?> shared item(s)</span>
				</div>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--black">
				<?php if($mbr->mbr_description) { ?>
					<p><?php echo strip_tags($mbr->mbr_description); ?></p>
				<?php } ?>
			</div>
		</div>

		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title">
				<h1 class="mdl-card__title-text"><?php echo $this->lang->line('delete'); ?></h1>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--black">
				<?php echo validation_errors(); ?>

				<?php echo form_open(current_url()); ?>

				<p>
				<?php echo form_label($this->lang->line('confirm').' *', 'confirm'); ?>
				<?php echo form_checkbox('confirm', '1', FALSE, 'id="confirm" class="inputcheckbox"'); ?>
				</p>

				<p><i class="icon icon-signin"></i><?php echo $connections_total; ?> connection(s)</p>
				<p><i class="icon icon-bookmark"></i><?php echo $subscriptions_total; ?> subscription(s)</p>
				<p><i class="icon icon-folder-close"></i><?php echo $folders_total; ?> folder(s)</p>
				<p><i class="icon icon-circle"></i><?php echo $read_items_total; ?> read item(s)</p>
				<p><i class="icon icon-star"></i><?php echo $starred_items_total; ?> starred item(s)</p>
				<p><i class="icon icon-heart"></i><?php echo $shared_items_total; ?> shared item(s)</p>

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
