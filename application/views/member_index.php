	<nav>
		<ul class="actions">
			<?php if($this->config->item('social')) { ?>
				<li class="hide-phone"><a target="_blank" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode(base_url().'member/'.$member->mbr_nickname); ?>"><i class="icon icon-share"></i>Facebook</a></li>
				<li class="hide-phone"><a target="_blank" href="https://plus.google.com/share?url=<?php echo urlencode(base_url().'member/'.$member->mbr_nickname); ?>"><i class="icon icon-share"></i>Google</a></li>
				<li class="hide-phone"><a target="_blank" href="https://twitter.com/intent/tweet?source=webclient&amp;text=<?php echo urlencode($member->mbr_nickname.' - '.$this->config->item('title').' '.base_url().'member/'.$member->mbr_nickname); ?>"><i class="icon icon-share"></i>Twitter</a></li> 
			<?php } ?>
			<li class="hide-phone"><a href="<?php echo base_url(); ?>share/<?php echo $member->token_share; ?>"><i class="icon icon-rss"></i>RSS</a></li>
			<?php if($this->input->cookie('items_display') == 'collapse') { ?>
				<li><a href="#" class="items_display" id="items_display"><span class="expand" title="2" style="display:inline-block;"><i class="icon icon-collapse"></i><?php echo $this->lang->line('expand'); ?></span><span class="collapse" title="1" style="display:none;"><i class="icon icon-collapse-top"></i><?php echo $this->lang->line('collapse'); ?></span></a></li>
			<?php } else { ?>
			<li><a href="#" class="items_display" id="items_display"><span class="expand" title="2"><i class="icon icon-collapse"></i><?php echo $this->lang->line('expand'); ?></span><span class="collapse" title="1"><i class="icon icon-collapse-top"></i><?php echo $this->lang->line('collapse'); ?></span></a></li>
			<?php } ?>
			<li><a href="#" class="item_up" id="item_up" title="<?php echo $this->lang->line('title_k'); ?>"><i class="icon icon-chevron-up"></i><?php echo $this->lang->line('up'); ?></a></li>
			<li><a href="#" class="item_down" id="item_down" title="<?php echo $this->lang->line('title_j'); ?>"><i class="icon icon-chevron-down"></i><?php echo $this->lang->line('down'); ?></a></li>
		</ul>
	</nav>
</header>
<main>
	<section>
		<section>
		</section>
	</section>
</main>
<script type="text/javascript">
	var mbr_nickname = '<?php echo $member->mbr_nickname; ?>';
</script>
