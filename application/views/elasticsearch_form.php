	<nav>
		<ul class="actions">
			<?php if($to_index > 0) { ?>
				<li><a href="<?php echo base_url(); ?>elasticsearch"><i class="icon icon-bolt"></i>Index (<?php echo $to_index; ?>)</a></li>
			<?php } ?>
		</ul>
	</nav>
</header>
<main>
	<section>
		<section>

			<article class="title">
				<?php if($this->input->get('q')) { ?>
					<h2><i class="icon icon-search"></i>Elasticsearch (<?php echo $hits->total; ?>)</h2>
				<?php } else { ?>
					<h2<i class="icon icon-search"></i>Elasticsearch</h2>
				<?php } ?>
			</article>

			<?php echo form_open(current_url(), 'class="form-inline" method="get"'); ?>
			<p>
			<?php echo form_input('q', set_value('q', $this->input->get('q')), 'class="form-control required"'); ?> <?php echo form_submit('submit', $this->lang->line('search'), 'class="inputsubmit"'); ?>
			</p>
			<?php echo form_close(); ?>

			<?php if($this->input->get('q')) { ?>
				<?php if($hits->total > 0) { ?>

					<div class="paging">
						<?php foreach($pagination as $page => $from) { ?>
							<?php if($current_from == $from) { ?>
								<strong><?php echo $page; ?></strong>
							<?php } else { ?>
								<a href="<?php echo current_url(); ?>?q=<?php echo $this->input->get('q'); ?>&amp;from=<?php echo $from; ?>"><?php echo $page; ?></a>
							<?php } ?>
						<?php } ?>
					</div>

					<?php foreach($hits->hits as $hit) { ?>
						<article>
							<h2>
							<?php if(isset($hit->highlight->title[0]) == 1) { ?>
								<a href="<?php echo $hit->_source->link; ?>"><?php echo $hit->highlight->title[0]; ?></a>
							<?php } else { ?>
								<a href="<?php echo $hit->_source->link; ?>"><?php echo $hit->_source->title; ?></a>
							<?php } ?>
							</h2>
							<ul class="item-details">
								<?php list($date, $time) = explode(' ', $hit->_source->date); ?>
								<li class="item-details-date"><i class="icon icon-calendar"></i><?php echo $date; ?></li>
								<li class="item-details-time"><i class="icon icon-time"></i><?php echo $time; ?><span class="timeago_outter"> (<span class="timeago" title="<?php echo $hit->_source->date; ?>"></span>)</span></li>
							</ul>

							<div class="item-content">
								<p><em><?php echo $hit->_source->link; ?></em></p>
								<?php if(isset($hit->highlight->content[0]) == 1) { ?>
									<p><?php echo $hit->highlight->content[0]; ?></p>
								<?php } ?>
							</div>
						</article>
					<?php } ?>

					<div class="paging">
						<?php foreach($pagination as $page => $from) { ?>
							<?php if($current_from == $from) { ?>
								<strong><?php echo $page; ?></strong>
							<?php } else { ?>
								<a href="<?php echo current_url(); ?>?q=<?php echo $this->input->get('q'); ?>&amp;from=<?php echo $from; ?>"><?php echo $page; ?></a>
							<?php } ?>
						<?php } ?>
					</div>
				<?php } ?>
			<?php } ?>

		</section>
	</section>
</main>
