<?php if($mode == 'starred') { ?>
	<article id="introduction" class="title">
		<ul class="actions">
			<li><a href="<?php echo base_url(); ?>starred/import"><i class="icon icon-download-alt"></i><?php echo $this->lang->line('import'); ?></a></li>
		</ul>
		<h2><i class="icon icon-star"></i><?php echo $this->lang->line('starred_items'); ?> {<span id="intro-load-starred-items"></span>}</h2>
	</article>
<?php } ?>

<?php if($mode == 'shared') { ?>
	<article id="introduction" class="title">
		<ul class="actions">
		<?php if($this->member->mbr_nickname) { ?>
			<li><a href="<?php echo base_url(); ?>member/<?php echo $this->member->mbr_nickname; ?>"><i class="icon icon-unlock"></i><?php echo $this->lang->line('public_profile'); ?></a></li>
		<?php } else { ?>
			<li class="hide-phone"><a target="_blank" href="<?php echo base_url(); ?>share/<?php echo $this->member->token_share; ?>"><i class="icon icon-rss"></i>RSS</a></li>
		<?php } ?>
		</ul>
		<h2><i class="icon icon-heart"></i><?php echo $this->lang->line('shared_items'); ?> {<span id="intro-load-shared-items"></span>}</h2>
	</article>
<?php } ?>

<?php if($mode == 'feed') { ?>
	<article dir="<?php echo $is_feed->direction; ?>" id="introduction" class="title">
		<ul class="actions">
		<?php if($is_feed->subscribe == 1) { ?>
			<li><a class="priority" href="<?php echo base_url(); ?>subscriptions/priority/<?php echo $is_feed->sub_id; ?>"><span class="priority"<?php if($is_feed->sub_priority == 0) { ?> style="display:none;"<?php } ?>><i class="icon icon-flag"></i><?php echo $this->lang->line('not_priority'); ?></span><span class="not_priority"<?php if($is_feed->sub_priority == 1) { ?> style="display:none;"<?php } ?>><i class="icon icon-flag-alt"></i><?php echo $this->lang->line('priority'); ?></span></a></li>
		<?php } else { ?>
			<li><a href="<?php echo base_url(); ?>feeds/subscribe/<?php echo $is_feed->fed_id; ?>"><i class="icon icon-bookmark-empty"></i><?php echo $this->lang->line('subscribe'); ?></a></li>
		<?php } ?>
		</ul>
		<h2><span style="background-image:url(https://www.google.com/s2/favicons?domain=<?php echo $is_feed->fed_host; ?>&amp;alt=feed);" class="favicon"><?php echo $is_feed->fed_title; ?></span> (<span id="intro-load-feed-<?php echo $is_feed->fed_id; ?>-items">0</span>)</h2>
		<ul class="item-details">
		<?php if($is_feed->subscribe == 1 && $this->config->item('folders')) { ?>
			<?php if($is_feed->flr_id) { ?>
				<li><a class="folder" href="#load-folder-<?php echo $is_feed->flr_id; ?>-items"><i class="icon icon-folder-close"></i><?php echo $is_feed->flr_title; ?></a></li>
			<?php } else { ?>
				<li><a class="folder" href="#load-nofolder-items"><i class="icon icon-folder-close"></i><em><?php echo $this->lang->line('no_folder'); ?></em></a></li>
			<?php } ?>
		<?php } ?>
		<?php if($is_feed->fed_url) { ?>
			<li><a target="_blank" href="<?php echo $is_feed->fed_url; ?>"><i class="icon icon-external-link"></i><?php echo $is_feed->fed_url; ?></a></li>
		<?php } ?>
		<?php if($this->config->item('tags')) { ?>
			<?php if(count($is_feed->categories) > 0) { ?>
				<li class="block hide-phone"><i class="icon icon-tags"></i><?php echo implode(', ', $is_feed->categories); ?></li>
			<?php } ?>
		<?php } ?>
		</ul>
	</article>
<?php } ?>

<?php if($mode == 'member') { ?>
		<article dir="<?php echo $is_feed->direction; ?>" id="introduction" class="title<?php if($is_member->mbr_id == $this->member->mbr_id) { ?> item-selected<?php } ?>"><i class="icon icon-user"></i><?php echo $is_member->mbr_nickname; ?>
			<ul class="actions">
			<?php if($this->session->userdata('mbr_id')) { ?>
				<?php if($is_member->mbr_id != $this->member->mbr_id) { ?>
					<li><a class="follow" href="<?php echo base_url(); ?>members/follow/<?php echo $is_member->mbr_id; ?>"><span class="follow"<?php if($is_member->following == 0) { ?>style="display:none;"<?php } ?>><i class="icon icon-link"></i><?php echo $this->lang->line('unfollow'); ?></span><span class="unfollow"<?php if($is_member->following == 1) { ?>style="display:none;"<?php } ?>><i class="icon icon-unlink"></i><?php echo $this->lang->line('follow'); ?></span></a></li>
				<?php } ?>
			<?php } ?>
			<?php if($this->config->item('share_external')) { ?>
				<li><a target="_blank" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode(base_url().'member/'.$is_member->mbr_nickname); ?>"><i class="icon icon-share"></i>Facebook</a></li>
				<li><a target="_blank" href="https://plus.google.com/share?url=<?php echo urlencode(base_url().'member/'.$is_member->mbr_nickname); ?>"><i class="icon icon-share"></i>Google</a></li>
				<li><a target="_blank" href="https://twitter.com/intent/tweet?source=webclient&amp;text=<?php echo urlencode($is_member->mbr_nickname.' - '.$this->config->item('title').' <?php echo base_url(); ?>member/'.$is_member->mbr_nickname); ?>"><i class="icon icon-share"></i>Twitter</a></li>
			<?php } ?>
			<li class="hide-phone"><a href="<?php echo base_url(); ?>share/'.$is_member->token_share.'"><i class="icon icon-rss"></i><abbr title="Really Simple Syndication">RSS</abbr></a></li>
			</ul>
			<?php if($this->config->item('gravatar') && $is_member->mbr_gravatar) { ?>
				<p><img alt="" src="http://www.gravatar.com/avatar/<?php echo md5(strtolower($is_member->mbr_gravatar)); ?>?rating=<?php echo $this->config->item('gravatar_rating'); ?>&amp;size=<?php echo $this->config->item('gravatar_size'); ?>&amp;default=<?php echo $this->config->item('gravatar_default'); ?>">
			<?php } ?>

			<?php if($is_member->mbr_description) { ?>
				<p><?php echo strip_tags($is_member->mbr_description); ?></p>
			<?php } ?>
	</article>
<?php } ?>

<?php if($mode == 'geolocation') { ?>
	<article id="introduction" class="title">
		<ul class="actions">
			<li class="geolocation"><a href="'.base_url().'home/geolocation"><i class="icon icon-user"></i><?php echo $this->lang->line('get_geolocation'); ?></a></li>
		</ul>
		<h2><i class="icon icon-map-marker"></i><?php echo $this->lang->line('geolocation_items'); ?> (<span id="intro-load-geolocation-items"></span>)</h2>
		<?php if($this->session->userdata('latitude') && $this->session->userdata('longitude')) { ?>
			<ul class="item-details">
				<li><a target="_blank" href="http://maps.google.com/maps?q=<?php echo $this->session->userdata('latitude'); ?>,<?php echo $this->session->userdata('longitude'); ?>&oe=UTF-8&ie=UTF-8"><i class="icon icon-user"></i><?php echo $this->session->userdata('latitude'); ?>,<?php echo $this->session->userdata('longitude'); ?></a></li>
				<li class="block"><a target="_blank" href="http://maps.google.com/maps?q=<?php echo $this->session->userdata('latitude'); ?>,<?php echo $this->session->userdata('longitude'); ?>&oe=UTF-8&ie=UTF-8"><img src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo $this->session->userdata('latitude'); ?>,<?php echo $this->session->userdata('longitude'); ?>&markers=color:red|<?php echo $this->session->userdata('latitude'); ?>,<?php echo $this->session->userdata('longitude'); ?>&zoom=12&size=540x200&sensor=false" alt=""></a></li>
		</ul>
		<?php } ?>
	</article>
<?php } ?>
