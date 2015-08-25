<div class="mdl-tooltip" for="tip_add">Feedly Essentials</div>
<div class="mdl-tooltip" for="tip_export"><?php echo $this->lang->line('export'); ?></div>

<div class="mdl-layout__drawer">
	<nav class="mdl-navigation">
		<ul>
			<li>
				<?php echo form_open(current_url()); ?>
				<p>
				<?php echo form_label($this->lang->line('title'), 'feeds_fed_title'); ?>
				<?php echo form_input($this->router->class.'_feeds_fed_title', set_value($this->router->class.'_feeds_fed_title', $this->axipi_session->userdata($this->router->class.'_feeds_fed_title')), 'id="feeds_fed_title" class="inputtext"'); ?>
				</p>
				<?php if($errors > 0) { ?>
					<p>
					<?php echo form_label($this->lang->line('errors').' ('.$errors.')', 'feeds_fed_lasterror'); ?>
					<?php echo form_dropdown($this->router->class.'_feeds_fed_lasterror', array('' => '--', 0 => $this->lang->line('no'), 1 => $this->lang->line('yes')), set_value($this->router->class.'_feeds_fed_lasterror', $this->axipi_session->userdata($this->router->class.'_feeds_fed_lasterror')), 'id="feeds_fed_lasterror" class="select numeric"'); ?>
					</p>
				<?php } ?>
				<p>
				<button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--<?php echo $this->config->item('material-design/colors/background/button'); ?> mdl-color-text--<?php echo $this->config->item('material-design/colors/text/button'); ?>">
					<i class="material-icons md-24">search</i>
				</button>
				</p>
				<?php echo form_close(); ?>
			</li>
		</ul>
	</nav>
</div>

<main class="mdl-layout__content mdl-color--<?php echo $this->config->item('material-design/colors/background/layout'); ?>">
	<div class="mdl-grid">
		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title-highlight'); ?> mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title-highlight'); ?>">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">bookmark_border</i><?php echo $this->lang->line('feeds'); ?> (<?php echo $position; ?>)</h1>
			</div>
			<div class="mdl-card__actions mdl-card--border mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-actions'); ?>">
				<a id="tip_add" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>feeds/feedly"><i class="material-icons md-18">add</i></a></li>
				<?php if($feeds) { ?>
					<a id="tip_export" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>feeds/export"><i class="material-icons md-18">file_upload</i></a>
				<?php } ?>
			</div>
		</div>

		<?php if($feeds) { ?>
			<?php if($pagination) { ?>
				<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col paging">
					<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
						<?php echo $pagination; ?>
					</div>
				</div>
			<?php } ?>
			<?php foreach($feeds as $fed) { ?>
				<div<?php if($fed->fed_direction) { ?> dir="<?php echo $fed->fed_direction; ?>"<?php } ?> class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col">
					<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
						<h1 class="mdl-card__title-text"><a style="background-image:url(https://www.google.com/s2/favicons?domain=<?php echo $fed->fed_host; ?>&amp;alt=feed);" class="favicon mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="<?php echo base_url(); ?>feeds/read/<?php echo $fed->fed_id; ?>"><?php echo $fed->fed_title; ?></a></h1>
						<div class="mdl-card__subtitle-text">
							<?php if($fed->fed_url) { ?>
								<a class="mdl-navigation__link" href="<?php echo $fed->fed_url; ?>" target="_blank"><i class="material-icons md-16">open_in_new</i><?php echo $fed->fed_url; ?></a>
							<?php } ?>
						</div>
					</div>
					<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
						<?php if($fed->fed_lasterror) { ?>
							<p><?php echo $fed->fed_lasterror; ?></p>
							<p><a target="_blank" href="http://validator.w3.org/feed/check.cgi?url=<?php echo urlencode($fed->fed_link); ?>">Check on W3C Feed Validation Service</a></p>
						<?php } ?>
						<?php if($this->config->item('tags') && $fed->categories) { ?>
							<p><?php echo implode(', ', $fed->categories); ?></p>
						<?php } ?>
						<p><?php echo $fed->fed_description; ?></p>
					</div>
					<div class="mdl-card__actions mdl-card--border mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-actions'); ?>">
						<?php if($this->member->mbr_administrator == 1) { ?>
							<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>feeds/update/<?php echo $fed->fed_id; ?>"><i class="material-icons md-18">mode_edit</i></a>
						<?php } ?>
						<?php if($fed->subscribers == 0) { ?>
							<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>feeds/delete/<?php echo $fed->fed_id; ?>"><i class="material-icons md-18">delete</i></a>
						<?php } ?>
						<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon subscribe" href="<?php echo base_url(); ?>feeds/subscribe/<?php echo $fed->fed_id; ?>"><i class="material-icons md-18">bookmark_border</i></a>
					</div>
				</div>
			<?php } ?>
			<?php if($pagination) { ?>
				<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col paging">
					<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
						<?php echo $pagination; ?>
					</div>
				</div>
			<?php } ?>
		<?php } ?>
	</div>
</main>
