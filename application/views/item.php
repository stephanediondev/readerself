<div class="item <?php if($itm->history == 'read') { ?> read<?php } ?>" id="item_<?php echo $itm->itm_id; ?>">
	<ul class="actions">
		<li><a class="history" href="<?php echo base_url(); ?>home/history/toggle/<?php echo $itm->itm_id; ?>"><span class="unread"<?php if($itm->history == 'unread') { ?> style="display:none;"<?php } ?>><i class="icon-remove"></i><?php echo $this->lang->line('keep_unread'); ?></span><span class="read"<?php if($itm->history == 'read') { ?> style="display:none;"<?php } ?>><i class="icon-ok"></i><?php echo $this->lang->line('mark_as_read'); ?></span></a></li>
		<li><a class="star" href="<?php echo base_url(); ?>home/star/<?php echo $itm->itm_id; ?>"><span class="unstar"<?php if($itm->star == 0) { ?> style="display:none;"<?php } ?>><i class="icon-star"></i><?php echo $this->lang->line('unstar'); ?></span><span class="star"<?php if($itm->star == 1) { ?> style="display:none;"<?php } ?>><i class="icon-star-empty"></i><?php echo $this->lang->line('star'); ?></span></a></li>
		<li><a href="#" class="item-up" data-itm_id="item_<?php echo $itm->itm_id; ?>"><i class="icon-angle-up"></i><?php echo $this->lang->line('up'); ?></a></li>
		<li><a href="#" class="item-down" data-itm_id="item_<?php echo $itm->itm_id; ?>"><i class="icon-angle-down"></i><?php echo $this->lang->line('down'); ?></a></li>
	</ul>
	<h2><a target="_blank" href="<?php echo $itm->itm_link; ?>"><?php echo $itm->itm_title; ?></a></h2>
	<ul class="item-details">
		<li><i class="icon-calendar"></i><?php echo $itm->explode_date; ?></li>
		<li><i class="icon-time"></i><?php echo $itm->explode_time; ?> (<span class="timeago" title="<?php echo $itm->itm_date; ?>"></span>)</li>
		<?php if($itm->itm_author) { ?><li class="hide-phone"><i class="icon-user"></i><?php echo $itm->itm_author; ?></li><?php } ?>
		<li class="hide-phone"><a class="from" data-sub_id="<?php echo $itm->sub_id; ?>" href="<?php echo base_url(); ?>home/items/sub/<?php echo $itm->sub_id; ?>"><i class="icon-rss"></i><?php echo $itm->fed_title; ?></a></li>
		<li class="show-phone"><i class="icon-rss"></i><?php echo $itm->fed_title; ?></li>
		<?php if($itm->tag_id) { ?><li class="hide-phone"><a class="tag" data-tag_id="<?php echo $itm->tag_id; ?>" href="<?php echo base_url(); ?>home/items/tag/<?php echo $itm->tag_id; ?>"><i class="icon-tag"></i><?php echo $itm->tag_title; ?></a></li><?php } ?>
	</ul>
	<div class=item_content">
		<?php echo $itm->itm_content; ?>
	</div>
	<div class="detect-visible" data-itm_id="item_<?php echo $itm->itm_id; ?>">
		<ul class="actions">
			<li><a target="_blank" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode($itm->itm_link); ?>"><i class="icon-facebook"></i>Facebook</a></li>
			<li><a target="_blank" href="https://plus.google.com/share?url=<?php echo urlencode($itm->itm_link); ?>"><i class="icon-google-plus"></i>Google</a></li>
			<li><a target="_blank" href="https://twitter.com/intent/tweet?source=webclient&amp;text=<?php echo urlencode($itm->itm_link); ?>"><i class="icon-twitter"></i>Twitter</a></li>
		</ul>
	</div>
</div>
