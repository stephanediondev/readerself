</header>
<aside>
	<ul>
		<li>
			<?php echo form_open(current_url()); ?>
				<p>
				<?php echo form_label($this->lang->line('mbr_nickname'), 'members_mbr_nickname'); ?>
				<?php echo form_input($this->router->class.'_members_mbr_nickname', set_value($this->router->class.'_members_mbr_nickname', $this->session->userdata($this->router->class.'_members_mbr_nickname')), 'id="members_mbr_nickname" class="inputtext"'); ?>
				</p>
				<p>
				<button type="submit"><?php echo $this->lang->line('send'); ?></button>
				</p>
			<?php echo form_close(); ?>
		</li>
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
				<?php if($this->member->mbr_administrator == 1) { ?>
					<li><a href="<?php echo base_url(); ?>members/delete/<?php echo $mbr->mbr_id; ?>"><i class="icon icon-trash"></i><?php echo $this->lang->line('delete'); ?></a></li>
				<?php } ?>
				<?php if($mbr->mbr_nickname) { ?>
					<?php if($mbr->mbr_id != $this->member->mbr_id) { ?>
						<li><a class="follow" href="<?php echo base_url(); ?>members/follow/<?php echo $mbr->mbr_id; ?>"><span class="follow"<?php if($mbr->following == 0) { ?> style="display:none;"<?php } ?>><i class="icon icon-link"></i><?php echo $this->lang->line('unfollow'); ?></span><span class="unfollow"<?php if($mbr->following == 1) { ?> style="display:none;"<?php } ?>><i class="icon icon-unlink"></i><?php echo $this->lang->line('follow'); ?></span></a></li>
					<?php } ?>
					<li><a href="<?php echo base_url(); ?>member/<?php echo $mbr->mbr_nickname; ?>"><i class="icon icon-unlock"></i><?php echo $this->lang->line('public_profile'); ?></a></li>
				<?php } ?>
			</ul>
			<?php if($this->member->mbr_administrator == 1) { ?>
				<h2>
				<?php if($mbr->mbr_nickname) { ?>
					<a href="<?php echo base_url(); ?>member/<?php echo $mbr->mbr_nickname; ?>"><i class="icon icon-user"></i><?php echo $mbr->mbr_email; ?> / <?php echo $mbr->mbr_nickname; ?></a>
				<?php } else { ?>
					<i class="icon icon-user"></i><?php echo $mbr->mbr_email; ?>
				<?php } ?>
				</h2>
			<?php } else { ?>
				<h2><a href="<?php echo base_url(); ?>member/<?php echo $mbr->mbr_nickname; ?>"><i class="icon icon-user"></i><?php echo $mbr->mbr_nickname; ?></a></h2>
			<?php } ?>
			<ul class="item-details">
				<?php if($mbr->subscriptions_common) { ?>
				<li><i class="icon icon-bookmark"></i><?php echo $mbr->subscriptions_common; ?> subscription(s) in common</li>
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
