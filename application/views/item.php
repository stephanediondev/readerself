<article<?php if($itm->sub->direction) { ?> dir="<?php echo $itm->sub->direction; ?>"<?php } ?> class="item<?php if($itm->history == 'read') { ?> read<?php } ?><?php if($this->input->get('items_display') == 'collapse' || $this->input->cookie('items_display') == 'collapse') { ?> collapse<?php } ?>" id="item_<?php echo $itm->itm_id; ?>">
	<ul class="actions">
		<?php if($mode != 'member') { ?>
			<li><a class="history" href="<?php echo base_url(); ?>item/read/<?php echo $itm->itm_id; ?>" title="m"><span class="unread"<?php if($itm->history == 'unread') { ?> style="display:none;"<?php } ?>><i class="icon icon-eye-close"></i><?php echo $this->lang->line('keep_unread'); ?></span><span class="read"<?php if($itm->history == 'read') { ?> style="display:none;"<?php } ?>><i class="icon icon-eye-open"></i><?php echo $this->lang->line('mark_as_read'); ?></span></a></li>
		<?php } ?>
		<?php if($this->config->item('star') && $mode != 'member') { ?>
			<li class="hide-collapse"><a class="star" href="<?php echo base_url(); ?>item/star/<?php echo $itm->itm_id; ?>" title="s"><span class="unstar"<?php if($itm->star == 0) { ?> style="display:none;"<?php } ?>><i class="icon icon-star"></i><?php echo $this->lang->line('unstar'); ?></span><span class="star"<?php if($itm->star == 1) { ?> style="display:none;"<?php } ?>><i class="icon icon-star-empty"></i><?php echo $this->lang->line('star'); ?></span></a></li>
		<?php } ?>
		<?php if($this->config->item('share') && $mode != 'member') { ?>
			<li class="hide-collapse"><a class="share" href="<?php echo base_url(); ?>item/share/<?php echo $itm->itm_id; ?>" title="<?php echo $this->lang->line('title_shift_s'); ?>"><span class="unshare"<?php if($itm->share == 0) { ?> style="display:none;"<?php } ?>><i class="icon icon-heart"></i><?php echo $this->lang->line('unshare'); ?></span><span class="share"<?php if($itm->share == 1) { ?> style="display:none;"<?php } ?>><i class="icon icon-heart-empty"></i><?php echo $this->lang->line('share'); ?></span></a></li>
		<?php } ?>
		<?php if($this->input->get('items_display') == 'collapse' || $this->input->cookie('items_display') == 'collapse') { ?>
			<li><a class="expand" href="<?php echo base_url(); ?>item/expand/<?php echo $itm->itm_id; ?>" title="<?php echo $this->lang->line('title_o'); ?>"><i class="icon icon-collapse"></i><?php echo $this->lang->line('expand'); ?></a></li>
			<li style="display:none;"><a class="collapse" href="#item_<?php echo $itm->itm_id; ?>" title="<?php echo $this->lang->line('title_o'); ?>"><i class="icon icon-collapse-top"></i><?php echo $this->lang->line('collapse'); ?></a></li>
		<?php } else if($this->input->get('items_display') == 'expand') { ?>
			<li style="display:none;"><a class="expand" href="<?php echo base_url(); ?>item/expand/<?php echo $itm->itm_id; ?>" title="<?php echo $this->lang->line('title_o'); ?>"><i class="icon icon-collapse"></i><?php echo $this->lang->line('expand'); ?></a></li>
			<li><a class="collapse" href="#item_<?php echo $itm->itm_id; ?>" title="<?php echo $this->lang->line('title_o'); ?>"><i class="icon icon-collapse-top"></i><?php echo $this->lang->line('collapse'); ?></a></li>
		<?php } ?>
	</ul>
	<h2><a target="_blank" href="<?php echo $itm->itm_link; ?>"><i class="icon icon-file-text-alt"></i><?php echo $itm->itm_title; ?></a></h2>
	<ul class="item-details">
		<li><i class="icon icon-calendar"></i><?php echo $itm->explode_date; ?></li>
		<li><i class="icon icon-time"></i><?php echo $itm->explode_time; ?> (<span class="timeago" title="<?php echo $itm->itm_date; ?>"></span>)</li>
		<?php if($mode != 'member') { ?>
			<?php if($itm->itm_author) { ?>
				<li class="hide-phone"><a class="author" data-itm_id="<?php echo $itm->itm_id; ?>" href="<?php echo base_url(); ?>items/get/author/<?php echo $itm->itm_id; ?>"><i class="icon icon-pencil"></i><?php echo $itm->itm_author; ?></a></li>
			<?php } ?>
			<li><a class="from" data-sub_id="<?php echo $itm->sub->sub_id; ?>" data-direction="<?php echo $itm->sub->direction; ?>" href="<?php echo base_url(); ?>items/get/subscription/<?php echo $itm->sub->sub_id; ?>"><i class="icon icon-rss"></i><?php echo $itm->sub->title; ?></a></li>
			<?php if($this->config->item('folders')) { ?>
				<?php if($itm->sub->flr_id) { ?>
					<li class="hide-phone"><a class="folder" href="#load-folder-<?php echo $itm->sub->flr_id; ?>-items"><i class="icon icon-folder-close"></i><?php echo $itm->sub->flr_title; ?></a></li>
				<?php } else { ?>
					<li class="hide-phone"><a class="folder" href="#load-nofolder-items"><i class="icon icon-folder-close"></i><em><?php echo $this->lang->line('no_folder'); ?></em></a></li>
				<?php } ?>
			<?php } ?>
			<?php if($this->config->item('tags') && $itm->categories) { ?>
				<li class="block hide-phone"><i class="icon icon-tags"></i><?php echo implode(', ', $itm->categories); ?></li>
			<?php } ?>
		<?php } else { ?>
			<?php if($itm->itm_author) { ?>
				<li class="hide-phone"><i class="icon icon-pencil"></i><?php echo $itm->itm_author; ?></li>
			<?php } ?>
			<li><i class="icon icon-rss"></i><?php echo $itm->sub->title; ?></li>
			<?php if($this->config->item('tags') && $itm->categories) { ?>
				<li class="block hide-phone"><i class="icon icon-tags"></i><?php echo implode(', ', $itm->categories); ?></li>
			<?php } ?>
		<?php } ?>
		<?php if($itm->foursquare) { ?>
			<li class="block hide-phone"><a target="_blank" href="https://foursquare.com/venue/<?php echo $itm->foursquare; ?>"><i class="icon icon-foursquare"></i>Foursquare</a></li>
		<?php } ?>
	</ul>
	<div class="item-content hide-collapse">
		<?php if($this->input->get('items_display') == 'collapse' || $this->input->cookie('items_display') == 'collapse') { ?>
		<?php } else if($this->input->get('items_display') == 'expand') { ?>
			<?php echo $this->load->view('item_expand', array('itm', $itm), TRUE); ?>
		<?php } ?>
	</div>
	<div class="item-footer hide-collapse">
		<ul class="actions">
		<?php if($this->config->item('readability_parser_key') && $mode != 'member') { ?>
			<li class="hide-phone"><a class="link-item-readability" href="<?php echo base_url(); ?>item/readability/<?php echo $itm->itm_id; ?>"><i class="icon icon-file-text"></i><?php echo $this->lang->line('readability'); ?></a></li>
		<?php } ?>
		<?php if($this->config->item('social')) { ?>
			<li><a class="link-item-like" href="#item-like-<?php echo $itm->itm_id; ?>" data-url="<?php echo urlencode($itm->itm_link); ?>"><i class="icon icon-thumbs-up-alt"></i><?php echo $this->lang->line('like'); ?></a></li>
			<li><a class="link-item-share" href="#item_<?php echo $itm->itm_id; ?>"><i class="icon icon-share"></i><?php echo $this->lang->line('share'); ?></a></li>
			<?php if($this->config->item('share_by_email') && $mode != 'member') { ?>
				<li class="hide item-share"><a class="modal_show" href="<?php echo base_url(); ?>item/email/<?php echo $itm->itm_id; ?>"><i class="icon icon-envelope"></i><?php echo $this->lang->line('share_email'); ?></a></li>
			<?php } ?>
			<li class="hide item-share"><a target="_blank" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode($itm->itm_link); ?>"><i class="icon icon-share"></i>Facebook</a></li>
			<li class="hide item-share"><a target="_blank" href="https://plus.google.com/share?url=<?php echo urlencode($itm->itm_link); ?>"><i class="icon icon-share"></i>Google</a></li>
			<li class="hide item-share"><a target="_blank" href="https://twitter.com/intent/tweet?source=webclient&amp;text=<?php echo urlencode($itm->itm_title.' '.$itm->itm_link); ?>"><i class="icon icon-share"></i>Twitter</a></li> 
		<?php } ?>
		</ul>
		<?php if($this->config->item('social')) { ?>
			<div class="item-like" id="item-like-<?php echo $itm->itm_id; ?>">
			</div>
		<?php } ?>
	</div>
</article>
