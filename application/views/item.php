<div class="item <?php if($itm->history == 'read') { ?> read<?php } ?>" id="item_<?php echo $itm->itm_id; ?>">
	<ul class="actions">
		<li><a class="history" href="<?php echo base_url(); ?>home/history/toggle/<?php echo $itm->itm_id; ?>"><span class="unread"<?php if($itm->history == 'unread') { ?> style="display:none;"<?php } ?>><i class="icon icon-eye-close"></i><?php echo $this->lang->line('keep_unread'); ?></span><span class="read"<?php if($itm->history == 'read') { ?> style="display:none;"<?php } ?>><i class="icon icon-eye-open"></i><?php echo $this->lang->line('mark_as_read'); ?></span></a></li>
		<li><a class="star" href="<?php echo base_url(); ?>home/star/<?php echo $itm->itm_id; ?>"><span class="unstar"<?php if($itm->star == 0) { ?> style="display:none;"<?php } ?>><i class="icon icon-star"></i><?php echo $this->lang->line('unstar'); ?></span><span class="star"<?php if($itm->star == 1) { ?> style="display:none;"<?php } ?>><i class="icon icon-star-empty"></i><?php echo $this->lang->line('star'); ?></span></a></li>
	</ul>
	<h2><a target="_blank" href="<?php echo $itm->itm_link; ?>"><?php echo $itm->itm_title; ?></a></h2>
	<ul class="item-details">
		<li><i class="icon icon-calendar"></i><?php echo $itm->explode_date; ?></li>
		<li><i class="icon icon-time"></i><?php echo $itm->explode_time; ?> (<span class="timeago" title="<?php echo $itm->itm_date; ?>"></span>)</li>
		<?php if($itm->itm_author) { ?><li class="hide-phone"><i class="icon icon-user"></i><?php echo $itm->itm_author; ?></li><?php } ?>
		<li class="hide-phone"><a class="from" data-sub_id="<?php echo $itm->sub->sub_id; ?>" href="<?php echo base_url(); ?>home/items/sub/<?php echo $itm->sub->sub_id; ?>"><i class="icon icon-rss"></i><?php if($itm->sub->sub_title) { ?><?php echo $itm->sub->sub_title; ?><?php } else { ?><?php echo $itm->fed->fed_title; ?><?php } ?></a></li>
		<li class="show-phone"><i class="icon icon-rss"></i><?php if($itm->sub->sub_title) { ?><?php echo $itm->sub->sub_title; ?><?php } else { ?><?php echo $itm->fed->fed_title; ?><?php } ?></li>
		<?php if($itm->sub->tag_id && $this->config->item('tags')) { ?><li class="hide-phone"><a class="tag" data-tag_id="<?php echo $itm->sub->tag_id; ?>" href="<?php echo base_url(); ?>home/items/tag/<?php echo $itm->sub->tag_id; ?>"><i class="icon icon-folder-close"></i><?php echo $itm->sub->tag_title; ?></a></li><?php } ?>
	</ul>
	<div class="item-content">
		<?php echo $itm->itm_content; ?>
		<?php if($itm->enclosures) { ?>
			<div class="item-enclosures">
				<?php foreach($itm->enclosures as $enr) { ?>
					<img src="<?php echo $enr->enr_link; ?>" alt="">
				<?php } ?>
			</div>
		<?php } ?>
	</div>
	<div class="item-footer">
		<?php if($this->config->item('social')) { ?>
		<ul class="actions">
			<li><a target="_blank" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode($itm->itm_link); ?>"><i class="icon icon-facebook"></i>Facebook</a></li>
			<li><a target="_blank" href="https://plus.google.com/share?url=<?php echo urlencode($itm->itm_link); ?>"><i class="icon icon-google-plus"></i>Google</a></li>
			<li><a target="_blank" href="https://twitter.com/intent/tweet?source=webclient&amp;text=<?php echo urlencode($itm->itm_link); ?>"><i class="icon icon-twitter"></i>Twitter</a></li>
		</ul>
		<?php } ?>
	</div>
</div>
