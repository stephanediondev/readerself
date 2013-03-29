<div class="item <?php if($itm->history == 'read') { ?> read<?php } ?>" id="item_<?php echo $itm->itm_id; ?>">
	<ul class="actions">
		<li><a href="#" class="item-up" data-itm_id="item_<?php echo $itm->itm_id; ?>"><?php echo $this->lang->line('up'); ?></a></li>
		<li><a href="#" class="item-down" data-itm_id="item_<?php echo $itm->itm_id; ?>"><?php echo $this->lang->line('down'); ?></a></li>
		<li><a class="star" href="<?php echo base_url(); ?>home/star/<?php echo $itm->itm_id; ?>"><span class="unstar"<?php if($itm->star == 0) { ?> style="display:none;"<?php } ?>><?php echo $this->lang->line('unstar'); ?></span><span class="star"<?php if($itm->star == 1) { ?> style="display:none;"<?php } ?>><?php echo $this->lang->line('star'); ?></span></a></li>
		<li><a class="history" href="<?php echo base_url(); ?>home/history/toggle/<?php echo $itm->itm_id; ?>"><span class="unread"<?php if($itm->history == 'unread') { ?> style="display:none;"<?php } ?>><?php echo $this->lang->line('keep_unread'); ?></span><span class="read"<?php if($itm->history == 'read') { ?> style="display:none;"<?php } ?>><?php echo $this->lang->line('mark_as_read'); ?></span></a></li>
	</ul>
	<h1><a target="_blank" href="<?php echo $itm->itm_link; ?>"><?php echo $itm->itm_title; ?></a></h1>
	<p>
	<?php if($itm->tag_id) { ?> <?php echo $this->lang->line('in'); ?> <a class="tag" data-tag_id="<?php echo $itm->tag_id; ?>" href="<?php echo base_url(); ?>home/items/tag/<?php echo $itm->tag_id; ?>"><?php echo $itm->tag_title; ?></a><?php } ?>
	<?php echo $this->lang->line('from'); ?> <a class="from" data-sub_id="<?php echo $itm->sub_id; ?>" href="<?php echo base_url(); ?>home/items/sub/<?php echo $itm->sub_id; ?>"><?php echo $itm->fed_title; ?></a>
	<?php if($itm->itm_author) { ?> <?php echo $this->lang->line('by'); ?> <?php echo $itm->itm_author; ?><?php } ?>
	<?php echo $this->lang->line('on'); ?> <?php echo $itm->explode_date; ?>
	<?php echo $this->lang->line('at'); ?> <?php echo $itm->explode_time; ?> (<span class="timeago" title="<?php echo $itm->itm_date; ?>"></span>)
	</p>
	<div class=item_content">
		<?php echo $itm->itm_content; ?>
	</div>
	<div class="btn-toolbar detect-visible" data-itm_id="item_<?php echo $itm->itm_id; ?>">
		<!--<div class="btn-group dropup">-->
		<!--	<a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $this->lang->line('share'); ?>...</a>-->
		<!--	<ul class="dropdown-menu">-->
		<!--	<li><a target="_blank" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode($itm->itm_link); ?>">Facebook</a></li>-->
		<!--	<li><a target="_blank" href="https://plus.google.com/share?url=<?php echo urlencode($itm->itm_link); ?>">Google</a></li>-->
		<!--	<li><a target="_blank" href="https://twitter.com/intent/tweet?source=webclient&amp;text=<?php echo urlencode($itm->itm_link); ?>">Twitter</a></li>-->
		<!--	</ul>-->
		<!--</div> -->
	</div>
</div>
