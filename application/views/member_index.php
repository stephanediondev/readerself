	<nav>
		<ul class="actions">
			<?php if($this->config->item('social')) { ?>
				<li><a target="_blank" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode(base_url().'member/'.$member->mbr_nickname); ?>"><i class="icon icon-share"></i>Facebook</a></li>
				<li><a target="_blank" href="https://plus.google.com/share?url=<?php echo urlencode(base_url().'member/'.$member->mbr_nickname); ?>"><i class="icon icon-share"></i>Google</a></li>
				<li><a target="_blank" href="https://twitter.com/intent/tweet?source=webclient&amp;text=<?php echo urlencode($member->mbr_nickname.' - '.$this->config->item('title').' '.base_url().'member/'.$member->mbr_nickname); ?>"><i class="icon icon-share"></i>Twitter</a></li> 
			<?php } ?>
			<li><a href="<?php echo base_url(); ?>share/<?php echo $member->token_share; ?>"><i class="icon icon-rss"></i><?php echo $this->lang->line('shared_items'); ?></a></li>
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
