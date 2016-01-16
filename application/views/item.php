<div<?php if($itm->sub->sub_direction) { ?> dir="<?php echo $itm->sub->sub_direction; ?>"<?php } ?> class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col item<?php if($itm->history == 'read') { ?> read<?php } ?><?php if($this->input->get('items_display') == 'collapse' || $this->input->cookie('items_display') == 'collapse') { ?> collapse<?php } else { ?> expand<?php } ?>" id="item_<?php echo $itm->itm_id; ?>">
	<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
		<h1 class="mdl-card__title-text">
			<a target="_blank" class="title_link favicon mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>"<?php if($itm->sub->fed_host) { ?> style="background-image:url(https://www.google.com/s2/favicons?domain=<?php echo $itm->sub->fed_host; ?>&amp;alt=feed);"<?php } ?> href="<?php echo $itm->itm_link; ?>"><?php echo $itm->itm_title; ?></a>
			<a class="expand mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="<?php echo base_url(); ?>item/expand/<?php echo $itm->itm_id; ?>" title="<?php echo $this->lang->line('title_o'); ?>"><i class="material-icons md-24">keyboard_arrow_up</i></a>
			<a class="collapse mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" href="#item_<?php echo $itm->itm_id; ?>" title="<?php echo $this->lang->line('title_o'); ?>"><i class="material-icons md-24">keyboard_arrow_down</i></a>
		</h1>
		<div class="mdl-card__subtitle-text">
			<span class="mdl-navigation__link mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>"><i class="material-icons md-16">access_time</i><span class="timeago" title="<?php echo $itm->itm_date; ?>"></span></span>
			<a class="mdl-navigation__link mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?> from" data-fed_id="<?php echo $itm->fed_id; ?>" data-fed_host="<?php echo $itm->sub->fed_host; ?>" data-direction="<?php echo $itm->sub->sub_direction; ?>" data-priority="<?php echo $itm->sub->priority; ?>" href="<?php echo base_url(); ?>items/get/feed/<?php echo $itm->fed_id; ?>"><i class="material-icons md-16">bookmark</i><?php if($itm->sub->sub_title) { ?><?php echo $itm->sub->sub_title; ?><?php } else { ?><?php echo $itm->sub->fed_title; ?><?php } ?></a>

			<?php if($this->config->item('folders')) { ?>
				<?php if($itm->sub->flr_id) { ?>
					<a class="mdl-navigation__link mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?> folder" href="#load-folder-<?php echo $itm->sub->flr_id; ?>-items"><i class="material-icons md-16">folder</i><?php echo $itm->sub->flr_title; ?></a>
				<?php } ?>
			<?php } ?>
			<?php if($itm->auh) { ?>
				<a class="mdl-navigation__link mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?> author" data-auh_id="<?php echo $itm->auh->auh_id; ?>" href="<?php echo base_url(); ?>items/get/author/<?php echo $itm->auh->auh_id; ?>"><i class="material-icons md-16">person</i><?php echo $itm->auh->auh_title; ?></a>
			<?php } ?>
			<?php if($this->config->item('tags') && $itm->categories) { ?>
				<?php echo implode('', $itm->categories); ?>
			<?php } ?>
		</div>
	</div>
	<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
		<?php if($this->input->get('items_display') == 'collapse' || $this->input->cookie('items_display') == 'collapse') { ?>
		<?php } else if($this->input->get('items_display') == 'expand') { ?>
			<?php echo $this->load->view('item_expand', array('itm', $itm), TRUE); ?>
		<?php } ?>

		<?php if($itm->sub->fed_link == 'https://github.com/readerself/readerself/releases.atom' && $this->member->mbr_administrator == 1) { ?>
			<?php $installed = file_exists('update/'.$itm->itm_title.'.txt'); ?>
			<?php if(!$installed) { ?>
				<p><a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--<?php echo $this->config->item('material-design/colors/background/button'); ?> mdl-color-text--<?php echo $this->config->item('material-design/colors/text/button'); ?>" href="<?php echo base_url(); ?>settings/update"><i class="material-icons md-18">done</i></a></p>
			<?php } ?>
		<?php } ?>

	</div>
	<div class="mdl-card__actions mdl-card--border mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-actions'); ?>">
		<?php if($itm->case_member != 'public_profile') { ?>
			<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon history" href="<?php echo base_url(); ?>item/read/<?php echo $itm->itm_id; ?>" title="m"><?php if($itm->history == 'unread') { ?> <i class="material-icons md-18">panorama_fish_eye</i><?php } ?><?php if($itm->history == 'read') { ?><i class="material-icons md-18">check_circle</i><?php } ?></a>
		<?php } ?>
		<?php if($this->config->item('starred_items') && $itm->case_member != 'public_profile') { ?>
			<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon star" href="<?php echo base_url(); ?>item/star/<?php echo $itm->itm_id; ?>" title="s"><?php if($itm->star == 0) { ?><i class="material-icons md-18">star_border</i><?php } ?><?php if($itm->star == 1) { ?><i class="material-icons md-18">star</i><?php } ?></a>
		<?php } ?>
		<?php if($this->config->item('shared_items') && $itm->case_member != 'public_profile') { ?>
			<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon share" href="<?php echo base_url(); ?>item/share/<?php echo $itm->itm_id; ?>" title="<?php echo $this->lang->line('title_shift_s'); ?>"><?php if($itm->share == 0) { ?><i class="material-icons md-18">favorite_border</i><?php } ?><?php if($itm->share == 1) { ?><i class="material-icons md-18">favorite</i><?php } ?></a>
		<?php } ?>
		<?php if($this->config->item('share_external')) { ?>
			<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon sharedcount" data-itm_id="<?php echo $itm->itm_id; ?>" id="more_share_<?php echo $itm->itm_id; ?>">
				<i class="material-icons md-18">share</i>
			</button>
			<ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--top-left mdl-color--<?php echo $this->config->item('material-design/colors/background/menu'); ?>" for="more_share_<?php echo $itm->itm_id; ?>">
				<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?> share_evernote" target="_blank" href="https://www.evernote.com/clip.action?url=<?php echo urlencode($itm->itm_link); ?>&amp;title=<?php echo urlencode($itm->itm_title); ?>">Evernote (clip)</a></li>
				<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?> share_facebook" target="_blank" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode($itm->itm_link); ?>">Facebook</a></li>
				<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?> share_googleplus" target="_blank" href="https://plus.google.com/share?url=<?php echo urlencode($itm->itm_link); ?>">Google+</a></li>
				<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?> share_googletranslate" target="_blank" href="http://translate.google.com/translate?u=<?php echo urlencode($itm->itm_link); ?>">Google Translate</a></li>
				<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?> share_instapaper" target="_blank" href="https://www.instapaper.com/e2?url=<?php echo urlencode($itm->itm_link); ?>&amp;title=<?php echo urlencode($itm->itm_title); ?>">Instapaper</a></li>
				<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?> share_linkedin" target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo urlencode($itm->itm_link); ?>">LinkedIn</a></li>
				<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?> share_pocket" target="_blank" href="https://getpocket.com/edit?url=<?php echo urlencode($itm->itm_link); ?>&amp;title=<?php echo urlencode($itm->itm_title); ?>">Pocket</a></li>
				<?php if($this->config->item('shaarli/enabled')) { ?>
					<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?> share_shaarli" target="_blank" href="<?php echo $this->config->item('shaarli/url'); ?>?post=<?php echo urlencode($itm->itm_link); ?>&amp;title=<?php echo urlencode($itm->itm_title); ?>">Shaarli</a></li>
				<?php } ?>
				<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?> share_tumblr" target="_blank" href="https://www.tumblr.com/widgets/share/tool/preview?shareSource=legacy&amp;url=<?php echo urlencode($itm->itm_link); ?>&amp;title=<?php echo urlencode($itm->itm_title); ?>">Tumblr</a></li>
				<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?> share_twitter" target="_blank" href="https://twitter.com/intent/tweet?source=webclient&amp;text=<?php echo urlencode($itm->itm_title.' '.$itm->itm_link); ?>">Twitter</a></li>
				<?php if($this->config->item('wallabag/enabled')) { ?>
					<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?> share_wallabag" target="_blank" href="<?php echo $this->config->item('wallabag/url'); ?>?action=add&amp;autoclose=true&amp;url=<?php echo base64_encode($itm->itm_link); ?>">Wallabag</a></li>
				<?php } ?>
			</ul>
		<?php } ?>
		<?php if($this->config->item('share_external_email') && $itm->case_member != 'public_profile') { ?>
			<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon share_email" data-itm_id="<?php echo $itm->itm_id; ?>" href="<?php echo base_url(); ?>item/email/<?php echo $itm->itm_id; ?>"><i class="material-icons md-18">mail_outline</i></a>
		<?php } ?>
		<?php if($this->config->item('readability_parser_key') && $itm->case_member != 'public_profile') { ?>
			<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon link-item-readability" data-itm_id="<?php echo $itm->itm_id; ?>" href="<?php echo base_url(); ?>item/readability/<?php echo $itm->itm_id; ?>"><i class="material-icons md-18">file_download</i></a>
		<?php } ?>
		<?php if($this->config->item('evernote/enabled')) { ?>
			<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon link-item-evernote" data-itm_id="<?php echo $itm->itm_id; ?>" href="<?php echo base_url(); ?>evernote/create/<?php echo $itm->itm_id; ?>"><i class="material-icons md-18">note_add</i></a>
		<?php } ?>
	</div>
</div>
