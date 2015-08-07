<div class="mdl-tooltip" for="tip_back"><?php echo $this->lang->line('back'); ?></div>

<div class="mdl-layout__drawer">
	<nav class="mdl-navigation">
		<ul>
			<li>
				<?php echo form_open(current_url()); ?>
				<p>
				<?php echo form_label($this->lang->line('language'), 'feeds_feedly_language'); ?>
				<?php echo form_dropdown($this->router->class.'_feeds_feedly_language', $sources, set_value($this->router->class.'_feeds_feedly_language', $this->axipi_session->userdata($this->router->class.'_feeds_feedly_language')), 'id="feeds_feedly_language" class="select numeric"'); ?>
				</p>
				<p>
				<button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--pink mdl-color-text--white">
					<i class="material-icons md-24">search</i>
				</button>
				</p>
				<?php echo form_close(); ?>
			</li>
		</ul>
	</nav>
</div>

<main class="mdl-layout__content mdl-color--grey-100">
	<div class="mdl-grid">
		<div class="mdl-card mdl-cell mdl-cell--12-col">
			<div class="mdl-card__title mdl-color-text--white mdl-color--teal">
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">bookmark_border</i><?php echo $this->lang->line('feeds'); ?></h1>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a id="tip_back" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>feeds"><i class="material-icons md-18">arrow_back</i></a>
			</div>
		</div>

		<?php if($feeds) { ?>
			<?php foreach($feeds as $cat) { ?>
				<div class="mdl-card mdl-cell mdl-cell--12-col">
					<div class="mdl-card__title mdl-color-text--white mdl-color--teal">
						<h1 class="mdl-card__title-text"><?php echo $cat->label; ?> (<?php echo count($cat->subscriptions); ?>)</h1>
					</div>
				</div>
	
				<?php foreach($cat->subscriptions as $fed) { ?>
					<?php if(isset($fed->title) == 1 && isset($fed->id) == 1) { ?>
						<?php $fed->id = substr($fed->id, 5); ?>
						<?php $parse_url = parse_url($fed->id); ?>
	
						<div class="mdl-card mdl-cell mdl-cell--3-col">
							<div class="mdl-card__title">
								<h1 class="mdl-card__title-text favicon"<?php if(isset($parse_url['host']) == 1) { ?> style="background-image:url(https://www.google.com/s2/favicons?domain=<?php echo $parse_url['host']; ?>&amp;alt=feed);"<?php } ?>><?php echo $fed->title; ?></h1>
								<div class="mdl-card__title-infos">
									<span class="mdl-navigation__link"><i class="material-icons md-16">people</i><?php echo $fed->subscribers; ?></span>
									<?php if($fed->website) { ?><a class="mdl-navigation__link" href="<?php echo $fed->website; ?>" target="_blank"><i class="material-icons md-16">open_in_new</i><?php echo $fed->website; ?></a><?php } ?>
								</div>
							</div>
							<div class="mdl-card__actions mdl-card--border">
								<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>subscriptions/create/?u=<?php echo $fed->id; ?>"><i class="material-icons md-18">add</i></a>
							</div>
						</div>
					<?php } ?>
				<?php } ?>
			<?php } ?>
		<?php } ?>
	</div>
</main>
