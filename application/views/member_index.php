	<nav>
		<ul class="actions">
			<li class="hide-phone"><a href="#" title="<?php echo $this->lang->line('title_shift_f'); ?>" class="fullscreen"><i class="icon icon-resize-full"></i><?php echo $this->lang->line('fullscreen'); ?></a></li>
			<li><a href="#" title="r" class="items_refresh"><i class="icon icon-refresh"></i><?php echo $this->lang->line('refresh'); ?></a></li>
			<?php if($this->input->cookie('items_display') == 'collapse') { ?>
				<li><a href="#" class="items_display"><span class="expand" title="2" style="display:inline-block;"><i class="icon icon-collapse"></i><?php echo $this->lang->line('expand'); ?></span><span class="collapse" title="1" style="display:none;"><i class="icon icon-collapse-top"></i><?php echo $this->lang->line('collapse'); ?></span></a></li>
			<?php } else { ?>
			<li><a href="#" class="items_display"><span class="expand" title="2"><i class="icon icon-collapse"></i><?php echo $this->lang->line('expand'); ?></span><span class="collapse" title="1"><i class="icon icon-collapse-top"></i><?php echo $this->lang->line('collapse'); ?></span></a></li>
			<?php } ?>
			<li><a href="#" class="item_up" id="item_up" title="<?php echo $this->lang->line('title_k'); ?>"><i class="icon icon-chevron-up"></i><?php echo $this->lang->line('up'); ?></a></li>
			<li><a href="#" class="item_down" id="item_down" title="<?php echo $this->lang->line('title_j'); ?>"><i class="icon icon-chevron-down"></i><?php echo $this->lang->line('down'); ?></a></li>
		</ul>
	</nav>
</header>
<div id="fullscreen_back">
	<ul class="actions">
		<li class="hide-phone"><a href="#" title="<?php echo $this->lang->line('title_shift_f'); ?>" class="fullscreen"><i class="icon icon-resize-small"></i><?php echo $this->lang->line('fullscreen'); ?></a></li>
	</ul>
</div>
<main>
	<section>
		<section>
		</section>
	</section>
</main>
<script type="text/javascript">
	var mbr_nickname = '<?php echo $member->mbr_nickname; ?>';
</script>
