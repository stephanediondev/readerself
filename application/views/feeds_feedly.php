	<nav>
		<ul class="actions">
			<?php if($feeds) { ?>
				<li><a href="<?php echo base_url(); ?>feeds"><i class="icon icon-step-backward"></i><?php echo $this->lang->line('back'); ?></a></li>
			<?php } ?>
		</ul>
	</nav>
</header>
<aside>
	<ul>
		<li>
			<?php echo form_open(current_url()); ?>
				<p>
				<?php echo form_label($this->lang->line('language'), 'feeds_feedly_language'); ?>
				<?php echo form_dropdown($this->router->class.'_feeds_feedly_language', $sources, set_value($this->router->class.'_feeds_feedly_language', $this->session->userdata($this->router->class.'_feeds_feedly_language')), 'id="feeds_feedly_language" class="select numeric"'); ?>
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

	<?php if($feeds) { ?>
		<?php $u = 1; ?>
		<?php foreach($feeds as $cat) { ?>
			<article class="title">
				<h2><a href="#subscriptions<?php echo $u; ?>"><i class="icon icon-rss"></i><?php echo $cat->label; ?> (<?php echo count($cat->subscriptions); ?>)</a></h2>
			</article>

			<div id="subscriptions<?php echo $u; ?>" class="subscriptions">
				<?php foreach($cat->subscriptions as $fed) { ?>
					<?php if(isset($fed->title) == 1 && isset($fed->id) == 1) { ?>
						<?php $fed->id = substr($fed->id, 5); ?>
						<?php $parse_url = parse_url($fed->id); ?>
						<article class="thumb">
							<ul class="actions">
								<li><a href="<?php echo base_url(); ?>subscriptions/create/?u=<?php echo $fed->id; ?>"><i class="icon icon-plus"></i><?php echo $this->lang->line('add'); ?></a></li>
							</ul>
							<h2<?php if(isset($parse_url['host']) == 1) { ?> style="background-image:url(https://www.google.com/s2/favicons?domain=<?php echo $parse_url['host']; ?>&amp;alt=feed);" class="favicon"<?php } ?>><?php echo $fed->title; ?></h2>
							<ul class="item-details">
								<li><i class="icon icon-group"></i><?php echo $fed->subscribers; ?></li>
								<li class="hide-phone"><a href="<?php echo $fed->id; ?>" target="_blank"><i class="icon icon-rss"></i><?php echo $fed->id; ?></a></li>
								<?php if($fed->website) { ?><li class="block"><a href="<?php echo $fed->website; ?>" target="_blank"><i class="icon icon-external-link"></i><?php echo $fed->website; ?></a></li><?php } ?>
							</ul>
							<div class="item-content">
							</div>
						</article>
					<?php } ?>
				<?php } ?>
			</div>
			<?php $u++; ?>
		<?php } ?>
	<?php } ?>
		</section>
	</section>
</main>
