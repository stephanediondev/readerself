</header>
<aside>
	<ul>
		<li><label for="members_mbr_nickname"><i class="icon icon-search"></i><?php echo $this->lang->line('search'); ?></label></li>
		<li><?php echo form_open(current_url()); ?>
			<?php echo form_input($this->router->class.'_members_mbr_nickname', set_value($this->router->class.'_members_mbr_nickname', $this->session->userdata($this->router->class.'_members_mbr_nickname')), 'id="members_mbr_nickname" class="inputtext"'); ?>
			<?php echo form_close(); ?></li>
	</ul>
</aside>
<main>
	<section>
		<section>
		<article class="title">
			<h2><i class="icon icon-group"></i><?php echo $this->lang->line('members'); ?> (<?php echo $position; ?>)</h2>
		</article>
	<?php if($members) { ?>
		<?php foreach($members as $mbr) { ?>
		<article<?php if($mbr->mbr_id == $this->member->mbr_id) { ?> class="item-selected"<?php } ?>>
			<ul class="actions">
				<li><a href="<?php echo base_url(); ?>member/<?php echo $mbr->mbr_nickname; ?>"><i class="icon icon-unlock"></i><?php echo $this->lang->line('public_profile'); ?></a></li>
			</ul>
			<h2><a href="<?php echo base_url(); ?>member/<?php echo $mbr->mbr_nickname; ?>"><i class="icon icon-user"></i><?php echo $mbr->mbr_nickname; ?></a></h2>
			<ul class="item-details">
				<?php if($mbr->subscriptions_common) { ?>
				<li><i class="icon icon-rss"></i><?php echo $mbr->subscriptions_common; ?> subscription(s) in common</li>
				<?php } ?>
				<li><i class="icon icon-heart"></i><?php echo $mbr->shared_items; ?> shared item(s)</li>
			</ul>
			<div class="item-content">
				<?php if($this->config->item('gravatar') && $mbr->mbr_gravatar) { ?>
					<p><img alt="" src="http://www.gravatar.com/avatar/<?php echo md5(strtolower($mbr->mbr_gravatar)); ?>?rating=<?php echo $this->config->item('gravatar_rating'); ?>&amp;size=<?php echo $this->config->item('gravatar_size'); ?>&amp;default=<?php echo $this->config->item('gravatar_default'); ?>">
				<?php } ?>
				<?php if($mbr->mbr_description) { ?>
					<p><?php echo strip_tags($mbr->mbr_description); ?></p>
				<?php } ?>
			</div>
		</article>
		<?php } ?>
		<div class="paging">
			<?php echo $pagination; ?>
		</div>
	<?php } ?>
		</section>
	</section>
</main>
