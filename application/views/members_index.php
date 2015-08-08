<main class="mdl-layout__content mdl-color--<?php echo $this->config->item('material-design/colors/background/layout'); ?>">
	<div class="mdl-grid">
		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--white mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title'); ?>">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">people</i><?php echo $this->lang->line('members'); ?> (<?php echo $position; ?>)</h2>
			</div>
		</div>

		<?php if($members) { ?>
			<?php foreach($members as $mbr) { ?>
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
					<div class="mdl-card__actions mdl-card--border">
						<?php if($this->member->mbr_administrator == 1) { ?>
							<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>members/update/<?php echo $mbr->mbr_id; ?>"><i class="material-icons md-18">mode_edit</i></a>
							<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>members/delete/<?php echo $mbr->mbr_id; ?>"><i class="material-icons md-18">delete</i></a>
						<?php } ?>
						<?php if($mbr->mbr_nickname) { ?>
							<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>member/<?php echo $mbr->mbr_nickname; ?>"><i class="material-icons md-18">link</i></a></li>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
			<div class="mdl-card mdl-cell mdl-cell--12-col paging">
				<div class="mdl-card__supporting-text mdl-color-text--black">
					<?php echo $pagination; ?>
				</div>
			</div>
		<?php } ?>
	</div>
</main>
