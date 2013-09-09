<article class="item <?php if($itm->history == 'read') { ?> read<?php } ?>" id="item_<?php echo $itm->itm_id; ?>">
	<ul class="actions">
		<li><a class="history" href="<?php echo base_url(); ?>home/history/toggle/<?php echo $itm->itm_id; ?>"><span class="unread"<?php if($itm->history == 'unread') { ?> style="display:none;"<?php } ?>><i class="icon icon-eye-close"></i><?php echo $this->lang->line('keep_unread'); ?></span><span class="read"<?php if($itm->history == 'read') { ?> style="display:none;"<?php } ?>><i class="icon icon-eye-open"></i><?php echo $this->lang->line('mark_as_read'); ?></span></a></li>
		<?php if($this->config->item('star')) { ?>
		<li><a class="star" href="<?php echo base_url(); ?>home/star/<?php echo $itm->itm_id; ?>"><span class="unstar"<?php if($itm->star == 0) { ?> style="display:none;"<?php } ?>><i class="icon icon-star"></i><?php echo $this->lang->line('unstar'); ?></span><span class="star"<?php if($itm->star == 1) { ?> style="display:none;"<?php } ?>><i class="icon icon-star-empty"></i><?php echo $this->lang->line('star'); ?></span></a></li>
		<?php } ?>
		<?php if($this->config->item('share')) { ?>
		<li><a class="share" href="<?php echo base_url(); ?>home/share/<?php echo $itm->itm_id; ?>"><span class="unshare"<?php if($itm->share == 0) { ?> style="display:none;"<?php } ?>><i class="icon icon-heart"></i><?php echo $this->lang->line('unshare'); ?></span><span class="share"<?php if($itm->share == 1) { ?> style="display:none;"<?php } ?>><i class="icon icon-heart-empty"></i><?php echo $this->lang->line('share'); ?></span></a></li>
		<?php } ?>
	</ul>
	<h2><a target="_blank" href="<?php echo $itm->itm_link; ?>"><i class="icon icon-file-text-alt"></i><?php echo $itm->itm_title; ?></a></h2>
	<ul class="item-details">
		<li><i class="icon icon-calendar"></i><?php echo $itm->explode_date; ?></li>
		<li><i class="icon icon-time"></i><?php echo $itm->explode_time; ?> (<span class="timeago" title="<?php echo $itm->itm_date; ?>"></span>)</li>
		<?php if($itm->itm_author) { ?><li class="hide-phone"><a class="author" data-itm_id="<?php echo $itm->itm_id; ?>" href="<?php echo base_url(); ?>home/items/author/<?php echo $itm->itm_id; ?>"><i class="icon icon-user"></i><?php echo $itm->itm_author; ?></a></li><?php } ?>
		<li><a class="from" data-sub_id="<?php echo $itm->sub->sub_id; ?>" href="<?php echo base_url(); ?>home/items/subscription/<?php echo $itm->sub->sub_id; ?>"><i class="icon icon-rss"></i><?php if($itm->sub->sub_title) { ?><?php echo $itm->sub->sub_title; ?><?php } else { ?><?php echo $itm->fed->fed_title; ?><?php } ?></a></li>
		<?php if($itm->sub->flr_id && $this->config->item('folders')) { ?><li class="hide-phone"><a class="folder" data-flr_id="<?php echo $itm->sub->flr_id; ?>" href="<?php echo base_url(); ?>home/items/folder/<?php echo $itm->sub->flr_id; ?>"><i class="icon icon-folder-close"></i><?php echo $itm->sub->flr_title; ?></a></li><?php } ?>
		<?php if($this->config->item('tags') && $itm->categories) { ?>
		<li class="block hide-phone"><i class="icon icon-tags"></i><?php echo implode(', ', $itm->categories); ?></li>
		<?php } ?>
	</ul>
	<div class="item-content">
		<?php echo $itm->itm_content; ?>
		<?php if($itm->itm_latitude && $itm->itm_longitude) { ?>
			<div class="item-geolocation">
				<p><i class="icon icon-map-marker"></i> <a target="_blank" href="http://maps.google.com/maps?q=<?php echo $itm->itm_latitude; ?>,<?php echo $itm->itm_longitude; ?>&oe=UTF-8&ie=UTF-8"><img src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo $itm->itm_latitude; ?>,<?php echo $itm->itm_longitude; ?>&markers=color:red|<?php echo $itm->itm_latitude; ?>,<?php echo $itm->itm_longitude; ?>&zoom=12&size=540x200&sensor=false" alt=""></a></p>
			</div>
		<?php } ?>
		<?php if($itm->enclosures) { ?>
			<div class="item-enclosures">
				<?php foreach($itm->enclosures as $enr) { ?>
				<?php if(stristr($enr->enr_type, 'image/')) { ?><p><i class="icon icon-picture"></i><?php if($enr->enr_length == 0 || $enr->enr_length <= 1048576) { ?><img src="<?php echo $enr->enr_link; ?>" alt=""><?php } else { ?><a target="_blank" href="<?php echo $enr->enr_link; ?>"><?php echo $enr->enr_link; ?><?php } ?></p><?php } ?>
				<?php if(stristr($enr->enr_type, 'audio/')) { ?><p><i class="icon icon-volume-up"></i><a target="_blank" href="<?php echo $enr->enr_link; ?>"><?php echo $enr->enr_link; ?></a></p><?php } ?>
				<?php if(stristr($enr->enr_type, 'video/')) { ?><p><i class="icon icon-youtube-play"></i><a target="_blank" href="<?php echo $enr->enr_link; ?>"><?php echo $enr->enr_link; ?></a></p><?php } ?>
				<?php } ?>
			</div>
		<?php } ?>
	</div>
	<div class="item-footer">
		<?php if($this->config->item('social')) { ?>
		<ul class="actions">
			<li><a class="link-item-like" href="#item-like-<?php echo $itm->itm_id; ?>" data-url="<?php echo urlencode($itm->itm_link); ?>"><i class="icon icon-thumbs-up-alt "></i><?php echo $this->lang->line('like'); ?></a></li>
			<li><a class="link-item-share" href="#item_<?php echo $itm->itm_id; ?>"><i class="icon icon-share"></i><?php echo $this->lang->line('share'); ?></a></li>
			<li class="item-share"><a target="_blank" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode($itm->itm_link); ?>"><i class="icon icon-facebook-sign"></i>Facebook</a></li>
			<li class="item-share"><a target="_blank" href="https://plus.google.com/share?url=<?php echo urlencode($itm->itm_link); ?>"><i class="icon icon-google-plus-sign"></i>Google</a></li>
			<li class="item-share"><a target="_blank" href="https://twitter.com/intent/tweet?source=webclient&amp;text=<?php echo urlencode($itm->itm_title.' '.$itm->itm_link); ?>"><i class="icon icon-twitter-sign"></i>Twitter</a></li> 
		</ul>
		<div class="item-like" id="item-like-<?php echo $itm->itm_id; ?>">
		</div>
		<?php } ?>
	</div>
</article>
