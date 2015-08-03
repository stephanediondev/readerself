<div<?php if($itm->sub->sub_direction) { ?> dir="<?php echo $itm->sub->sub_direction; ?>"<?php } ?> class="mdl-card mdl-cell mdl-cell--12-col item<?php if($itm->history == 'read') { ?> read<?php } ?><?php if($this->input->get('items_display') == 'collapse' || $this->input->cookie('items_display') == 'collapse') { ?> collapse<?php } ?>" id="item_<?php echo $itm->itm_id; ?>">
	<div class="mdl-card__title">
		<h1 class="mdl-card__title-text"><a class="favicon mdl-color-text--grey-" style="background-image:url(https://www.google.com/s2/favicons?domain=<?php echo $itm->sub->fed_host; ?>&amp;alt=feed);" href="<?php echo $itm->itm_link; ?>"><?php echo $itm->itm_title; ?></a></h1>
		<div class="mdl-card__title-infos">
			<span class="mdl-navigation__link"><i class="material-icons md-16">access_time</i><span class="timeago" title="<?php echo $itm->itm_date; ?>"></span></span>
			<a class="mdl-navigation__link from" data-fed_id="<?php echo $itm->fed_id; ?>" data-fed_host="<?php echo $itm->sub->fed_host; ?>" data-direction="<?php echo $itm->sub->sub_direction; ?>" data-priority="<?php echo $itm->sub->priority; ?>" href="<?php echo base_url(); ?>items/get/feed/<?php echo $itm->fed_id; ?>"><i class="material-icons md-16">bookmark</i><?php if($itm->sub->sub_title) { ?><?php echo $itm->sub->sub_title; ?><?php } else { ?><?php echo $itm->sub->fed_title; ?><?php } ?></a>

			<?php if($this->config->item('folders')) { ?>
				<?php if($itm->sub->flr_id) { ?>
					<a class="mdl-navigation__link" href=""><i class="material-icons md-16">folder</i><?php echo $itm->sub->flr_title; ?></a>
				<?php } ?>
			<?php } ?>
			<?php if($itm->itm_author) { ?>
				<a class="mdl-navigation__link author" data-itm_id="<?php echo $itm->itm_id; ?>" href="<?php echo base_url(); ?>items/get/author/<?php echo $itm->itm_id; ?>"><i class="material-icons md-16">person</i><?php echo $itm->itm_author; ?></a>
			<?php } ?>
		</div>
	</div>
	<div class="mdl-card__supporting-text mdl-color-text--grey hide-collapse">
		<?php if($this->input->get('items_display') == 'collapse' || $this->input->cookie('items_display') == 'collapse') { ?>
		<?php } else if($this->input->get('items_display') == 'expand') { ?>
			<?php echo $this->load->view('item_expand', array('itm', $itm), TRUE); ?>
		<?php } ?>
	</div>
	<div class="mdl-card__actions mdl-card--border">
		<?php if($itm->case_member != 'public_profile') { ?>
			<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon history" href="<?php echo base_url(); ?>item/read/<?php echo $itm->itm_id; ?>" title="m"><?php if($itm->history == 'unread') { ?> <i class="material-icons md-18">panorama_fish_eye</i><?php } ?><?php if($itm->history == 'read') { ?><i class="material-icons md-18">check_circle</i><?php } ?></a>
		<?php } ?>
		<?php if($itm->case_member != 'following') { ?>
			<?php if($this->config->item('starred_items') && $itm->case_member != 'public_profile') { ?>
				<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon star" href="<?php echo base_url(); ?>item/star/<?php echo $itm->itm_id; ?>" title="s"><?php if($itm->star == 0) { ?><i class="material-icons md-18">star_border</i><?php } ?><?php if($itm->star == 1) { ?><i class="material-icons md-18">star</i><?php } ?></a>
			<?php } ?>
			<?php if($this->config->item('shared_items') && $itm->case_member != 'public_profile') { ?>
				<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon share" href="<?php echo base_url(); ?>item/share/<?php echo $itm->itm_id; ?>" title="<?php echo $this->lang->line('title_shift_s'); ?>"><?php if($itm->share == 0) { ?><i class="material-icons md-18">favorite_border</i><?php } ?><?php if($itm->share == 1) { ?><i class="material-icons md-18">favorite</i><?php } ?></a>
			<?php } ?>
		<?php } ?>
		<?php if($this->config->item('share_external')) { ?>
			<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="more_share_<?php echo $itm->itm_id; ?>">
				<i class="material-icons md-18">share</i>
			</button>
			<ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--top-left" for="more_share_<?php echo $itm->itm_id; ?>">
				<?php if($this->config->item('share_external_email') && $itm->case_member != 'public_profile') { ?>
					<li class="mdl-menu__item"><a class="modal_show" href="<?php echo base_url(); ?>item/email/<?php echo $itm->itm_id; ?>"><?php echo $this->lang->line('share_email'); ?></a></li>
				<?php } ?>
				<li class="mdl-menu__item"><a target="_blank" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode($itm->itm_link); ?>">Facebook</a></li>
				<li class="mdl-menu__item"><a target="_blank" href="https://plus.google.com/share?url=<?php echo urlencode($itm->itm_link); ?>">Google</a></li>
				<li class="mdl-menu__item"><a target="_blank" href="https://twitter.com/intent/tweet?source=webclient&amp;text=<?php echo urlencode($itm->itm_title.' '.$itm->itm_link); ?>">Twitter</a></li>
			</ul>
		<?php } ?>
		<?php if($this->config->item('readability_parser_key') && $itm->case_member != 'public_profile') { ?>
			<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon link-item-readability" href="<?php echo base_url(); ?>item/readability/<?php echo $itm->itm_id; ?>"><i class="material-icons md-18">get_app</i></a>
		<?php } ?>
	</div>
</div>
