<?php if($mode == 'starred') { ?>
	<div class="mdl-card mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
		<div class="mdl-card__title mdl-color-text--white mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title'); ?>">
			<h1 class="mdl-card__title-text"><i class="material-icons md-18">star</i><?php echo $this->lang->line('starred_items'); ?> {<span id="intro-load-starred-items"></span>}</h1>
		</div>
		<div class="mdl-card__actions mdl-card--border">
			<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>starred/export"><i class="material-icons md-18">file_upload</i></a>
			<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>starred/import"><i class="material-icons md-18">file_download</i></a>
		</div>
	</div>
<?php } ?>

<?php if($mode == 'shared') { ?>
	<div class="mdl-card mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
		<div class="mdl-card__title mdl-color-text--white mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title'); ?>">
			<h1 class="mdl-card__title-text"><i class="material-icons md-18">favorite</i><?php echo $this->lang->line('shared_items'); ?> {<span id="intro-load-shared-items"></span>}</h1>
		</div>
		<div class="mdl-card__actions mdl-card--border">
			<?php if($this->member->mbr_nickname) { ?>
				<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>member/<?php echo $this->member->mbr_nickname; ?>"><i class="material-icons md-18">link</i></a>
			<?php } else { ?>
				<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" target="_blank" href="<?php echo base_url(); ?>share/<?php echo $this->member->token_share; ?>"><i class="material-icons md-18">publish</i></a>
			<?php } ?>
		</div>
	</div>
<?php } ?>

<?php if($mode == 'folder') { ?>
	<div<?php if($is_folder->flr_direction) { ?> dir="<?php echo $is_folder->flr_direction; ?>"<?php } ?> class="mdl-card mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col">
		<div class="mdl-card__title">
			<h1 class="mdl-card__title-text"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="<?php echo base_url(); ?>folders/read/<?php echo $is_folder->flr_id; ?>"><?php echo $is_folder->flr_title; ?></a></h1>
			<div class="mdl-card__title-infos">
				<span class="mdl-navigation__link"><i class="material-icons md-16">bookmark</i><?php echo $is_folder->subscriptions; ?> subscription(s)</span>
				<span class="mdl-navigation__link"><i class="material-icons md-16">star</i><?php echo $is_folder->starred_items; ?> starred item(s)</span>
				<span class="mdl-navigation__link"><i class="material-icons md-16">favorite</i><?php echo $is_folder->shared_items; ?> shared item(s)</span>
			</div>
		</div>
		<div class="mdl-card__actions mdl-card--border">
			<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>folders/update/<?php echo $is_folder->flr_id; ?>"><i class="material-icons md-18">mode_edit</i></a>
			<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>folders/delete/<?php echo $is_folder->flr_id; ?>"><i class="material-icons md-18">delete</i></a>
		</div>
	</div>

	<div class="mdl-card mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
		<div class="mdl-card__title mdl-color-text--white mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title'); ?>">
			<h1 class="mdl-card__title-text"><i class="material-icons md-18">folder</i> (<span id="intro-load-folder-<?php echo $is_folder->flr_id; ?>-items">0</span>)</h1>
		</div>
	</div>
<?php } ?>

<?php if($mode == 'feed') { ?>
	<div<?php if($is_feed->sub_direction) { ?> dir="<?php echo $is_feed->sub_direction; ?>"<?php } else if($is_feed->fed_direction) { ?> dir="<?php echo $is_feed->fed_direction; ?>"<?php } ?> class="mdl-card mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col">
		<div class="mdl-card__title">
			<h1 class="mdl-card__title-text"><a style="background-image:url(https://www.google.com/s2/favicons?domain=<?php echo $is_feed->fed_host; ?>&amp;alt=feed);" class="favicon mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="<?php echo base_url(); ?>subscriptions/read/<?php echo $is_feed->sub_id; ?>"><?php echo $is_feed->fed_title; ?><?php if($is_feed->sub_title) { ?> / <em><?php echo $is_feed->sub_title; ?></em><?php } ?></a></h1>
			<div class="mdl-card__title-infos">
				<?php if($is_feed->fed_url) { ?>
					<a class="mdl-navigation__link" href="<?php echo $is_feed->fed_url; ?>" target="_blank"><i class="material-icons md-16">open_in_new</i><?php echo $is_feed->fed_url; ?></a>
				<?php } ?>
				<?php if($this->config->item('folders')) { ?>
					<?php if($is_feed->flr_title) { ?><a class="mdl-navigation__link folder" href="#load-folder-<?php echo $is_feed->flr_id; ?>-items"><i class="material-icons md-16">folder</i><?php echo $is_feed->flr_title; ?></a><?php } ?>
				<?php } ?>
				<?php if($this->config->item('tags') && $is_feed->categories) { ?>
					<?php echo implode('', $is_feed->categories); ?>
				<?php } ?>
			</div>
		</div>
		<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
			<p><?php echo $is_feed->fed_description; ?></p>
		</div>
		<div class="mdl-card__actions mdl-card--border">
			<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon subscribe" href="<?php echo base_url(); ?>subscriptions/delete/<?php echo $is_feed->sub_id; ?>"><i class="material-icons md-18">bookmark</i></a>
			<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon priority" href="<?php echo base_url(); ?>subscriptions/priority/<?php echo $is_feed->sub_id; ?>"><?php if($is_feed->sub_priority == 0) { ?><i class="material-icons md-18">chat_bubble_outline</i><?php } ?><?php if($is_feed->sub_priority == 1) { ?><i class="material-icons md-18">announcement</i><?php } ?></a>
			<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>subscriptions/update/<?php echo $is_feed->sub_id; ?>"><i class="material-icons md-18">mode_edit</i></a>
		</div>
	</div>

	<div class="mdl-card mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
		<div class="mdl-card__title mdl-color-text--white mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title'); ?>">
			<h1 class="mdl-card__title-text"><i class="material-icons md-18">bookmark</i> (<span id="intro-load-feed-<?php echo $is_feed->fed_id; ?>-items">0</span>)</h1>
		</div>
	</div>
<?php } ?>

<?php if($mode == 'public_profile') { ?>
	<div class="mdl-card mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
		<div class="mdl-card__title mdl-color-text--white mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title'); ?>">
			<h1 class="mdl-card__title-text"><i class="material-icons md-18">person</i><?php echo $is_member->mbr_nickname; ?></h1>
		</div>
		<?php if($is_member->mbr_description) { ?>
			<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
				<p><?php echo strip_tags($is_member->mbr_description); ?></p>
			</div>
		<?php } ?>
		<div class="mdl-card__actions mdl-card--border">
			<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon geolocation" href="<?php echo base_url(); ?>share/<?php echo $is_member->token_share; ?>"><i class="material-icons md-18">code</i></a>
		</div>
	</div>
<?php } ?>

<?php if($mode == 'geolocation') { ?>
	<div class="mdl-card mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col">
		<div class="mdl-card__title mdl-color-text--white mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title'); ?>">
			<h1 class="mdl-card__title-text"><i class="material-icons md-18">place</i><?php echo $this->lang->line('geolocation_items'); ?> (<span id="intro-load-geolocation-items"></span>)</h1>
		</div>
		<?php if($this->axipi_session->userdata('latitude') && $this->axipi_session->userdata('longitude')) { ?>
			<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
				<p><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" target="_blank" href="http://maps.google.com/maps?q=<?php echo $this->axipi_session->userdata('latitude'); ?>,<?php echo $this->axipi_session->userdata('longitude'); ?>&oe=UTF-8&ie=UTF-8"><i class="material-icons md-18">place</i><?php echo $this->axipi_session->userdata('latitude'); ?>,<?php echo $this->axipi_session->userdata('longitude'); ?></a></p>
				<p><a target="_blank" href="http://maps.google.com/maps?q=<?php echo $this->axipi_session->userdata('latitude'); ?>,<?php echo $this->axipi_session->userdata('longitude'); ?>&oe=UTF-8&ie=UTF-8"><img src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo $this->axipi_session->userdata('latitude'); ?>,<?php echo $this->axipi_session->userdata('longitude'); ?>&markers=color:red|<?php echo $this->axipi_session->userdata('latitude'); ?>,<?php echo $this->axipi_session->userdata('longitude'); ?>&zoom=12&size=540x200&sensor=false" alt=""></a></p>
			</div>
		<?php } ?>
		<?php if($this->axipi_session->userdata('latitude') && $this->axipi_session->userdata('longitude')) { ?>
		<?php } else { ?>
			<div class="mdl-card__actions mdl-card--border">
				<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon geolocation" href="<?php echo base_url(); ?>home/geolocation"><i class="material-icons md-18">location_off</i></a>
			</div>
		<?php } ?>
	</div>
<?php } ?>
