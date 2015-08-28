<main class="mdl-layout__content mdl-color--<?php echo $this->config->item('material-design/colors/background/layout'); ?>">
	<div class="mdl-grid">
		<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--4-col">
			<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title-highlight'); ?> mdl-color--<?php echo $this->config->item('material-design/colors/background/card-title-highlight'); ?>">
				<?php if($this->input->get('q')) { ?>
					<h1 class="mdl-card__title-text"><i class="material-icons md-18">search</i>Elasticsearch (<?php echo $hits->total; ?>)</h1>
				<?php } else { ?>
					<h1 class="mdl-card__title-text"><i class="material-icons md-18">search</i>Elasticsearch</h1>
				<?php } ?>
			</div>
			<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
				<?php echo form_open(current_url(), 'class="form-inline" method="get"'); ?>
				<p>
				<?php echo form_label('Search', 'q'); ?>
				<?php echo form_input('q', set_value('q', $this->input->get('q')), 'id="q" class="inputtext required"'); ?>
				</p>
				<p>
				<?php echo form_label('Sort by', 'sort_field'); ?>
				<?php echo form_dropdown('sort_field', $sort_field, set_value('sort_field', $this->input->get('sort_field', '_score')), 'id="sort_field" class="select required"'); ?>
				</p>
				<p>
				<?php echo form_label('Sort direction', 'sort_direction'); ?>
				<?php echo form_dropdown('sort_direction', $sort_direction, set_value('sort_direction', $this->input->get('sort_direction', 'desc')), 'id="sort_direction" class="select required"'); ?>
				</p>
				<p>
				<button type="submit" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--<?php echo $this->config->item('material-design/colors/background/button'); ?> mdl-color-text--<?php echo $this->config->item('material-design/colors/text/button'); ?>">
					<i class="material-icons md-24">search</i>
				</button>
				</p>
				<?php echo form_close(); ?>
			</div>
			<?php if($to_index > 0) { ?>
				<div class="mdl-card__actions mdl-card--border mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-actions'); ?>">
					<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>elasticsearch"><i class="material-icons md-18 mdl-badge" data-badge="<?php echo $to_index; ?>">add</i></a></li>
				</div>
			<?php } ?>
		</div>

		<?php if($this->input->get('q')) { ?>
			<?php if($hits->total > 0) { ?>
				<?php if($pagination) { ?>
					<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col paging">
						<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
							<?php foreach($pagination as $page => $from) { ?>
								<?php if($current_from == $from) { ?>
									<strong><?php echo $page; ?></strong>
								<?php } else { ?>
									<a href="<?php echo current_url(); ?>?q=<?php echo $this->input->get('q'); ?>&amp;sort_field=<?php echo $this->input->get('sort_field'); ?>&amp;sort_direction=<?php echo $this->input->get('sort_direction'); ?>&amp;from=<?php echo $from; ?>"><?php echo $page; ?></a>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
				<?php } ?>

				<?php foreach($hits->hits as $hit) { ?>
					<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col item" id="item_<?php echo $hit->_source->id; ?>">
						<div class="mdl-card__title mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>">
							<h1 class="mdl-card__title-text">
								<a target="_blank" class="title_link favicon mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>"<?php if($hit->_source->feed->host) { ?> style="background-image:url(https://www.google.com/s2/favicons?domain=<?php echo $hit->_source->feed->host; ?>&amp;alt=feed);"<?php } ?> href="<?php echo $hit->_source->link; ?>"><?php if(isset($hit->highlight->title[0]) == 1) { ?><?php echo $hit->highlight->title[0]; ?><?php } else { ?><?php echo $hit->_source->title; ?><?php } ?></a>
							</h1>
							<div class="mdl-card__subtitle-text">
								<span class="mdl-navigation__link mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>"><i class="material-icons md-16">access_time</i><span class="timeago" title="<?php echo $hit->_source->date; ?>"></span></span>
								<span class="mdl-navigation__link mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>"><i class="material-icons md-16">bookmark</i><?php echo $hit->_source->feed->title; ?></span>
								<?php if($hit->_score != '') { ?>
									<span class="mdl-navigation__link mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-title'); ?>"><i class="material-icons md-16">trending_up</i><?php echo $hit->_score; ?></span>
								<?php } ?>
							</div>
						</div>
						<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
							<div class="item-content-result">
								<?php if(isset($hit->highlight->content[0]) == 1) { ?>
									<p><?php echo $hit->highlight->content[0]; ?></p>
								<?php } ?>
							</div>
						</div>
						<div class="mdl-card__actions mdl-card--border mdl-color-text--<?php echo $this->config->item('material-design/colors/text/card-actions'); ?>">
							<?php if($this->config->item('share_external')) { ?>
								<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon sharedcount" data-itm_id="<?php echo $hit->_source->id; ?>" id="more_share_<?php echo $hit->_source->id; ?>">
									<i class="material-icons md-18">share</i>
								</button>
								<ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--top-left mdl-color--<?php echo $this->config->item('material-design/colors/background/menu'); ?>" for="more_share_<?php echo $hit->_source->id; ?>">
									<?php if($this->config->item('share_external_email') && $itm->case_member != 'public_profile') { ?>
										<li class="mdl-menu__item"><a class="share_email mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?>" data-itm_id="<?php echo $hit->_source->id; ?>" href="<?php echo base_url(); ?>item/email/<?php echo $hit->_source->id; ?>"><?php echo $this->lang->line('share_email'); ?></a></li>
									<?php } ?>
									<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?> share_evernote" target="_blank" href="https://www.evernote.com/clip.action?url=<?php echo urlencode($hit->_source->link); ?>&amp;title=<?php echo urlencode($hit->_source->title); ?>">Evernote (clip)</a></li>
									<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?> share_facebook" target="_blank" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode($hit->_source->link); ?>">Facebook</a></li>
									<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?> share_google" target="_blank" href="https://plus.google.com/share?url=<?php echo urlencode($hit->_source->link); ?>">Google+</a></li>
									<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?> share_instapaper" target="_blank" href="https://www.instapaper.com/e2?url=<?php echo urlencode($hit->_source->link); ?>&amp;title=<?php echo urlencode($hit->_source->title); ?>">Instapaper</a></li>
									<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?> share_linkedin" target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo urlencode($hit->_source->link); ?>">LinkedIn</a></li>
									<?php if($this->config->item('shaarli/enabled')) { ?>
										<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?> share_shaarli" target="_blank" href="<?php echo $this->config->item('shaarli/url'); ?>?post=<?php echo urlencode($hit->_source->link); ?>&amp;title=<?php echo urlencode($hit->_source->title); ?>">Shaarli</a></li>
									<?php } ?>
									<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?> share_twitter" target="_blank" href="https://twitter.com/intent/tweet?source=webclient&amp;text=<?php echo urlencode($hit->_source->title.' '.$hit->_source->link); ?>">Twitter</a></li>
									<?php if($this->config->item('wallabag/enabled')) { ?>
										<li class="mdl-menu__item"><a class="mdl-color-text--<?php echo $this->config->item('material-design/colors/text/link'); ?> share_wallabag" target="_blank" href="<?php echo $this->config->item('wallabag/url'); ?>?action=add&amp;autoclose=true&amp;url=<?php echo base64_encode($hit->_source->link); ?>">Wallabag</a></li>
									<?php } ?>
								</ul>
							<?php } ?>
							<?php if($this->config->item('readability_parser_key')) { ?>
								<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon link-item-readability" data-itm_id="<?php echo $hit->_source->id; ?>" href="<?php echo base_url(); ?>item/readability/<?php echo $hit->_source->id; ?>"><i class="material-icons md-18">file_download</i></a>
							<?php } ?>
							<?php if($this->config->item('evernote/enabled')) { ?>
								<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon link-item-evernote" data-itm_id="<?php echo $hit->_source->id; ?>" href="<?php echo base_url(); ?>evernote/create/<?php echo $hit->_source->id; ?>"><i class="material-icons md-18">note_add</i></a>
							<?php } ?>
						</div>
					</div>
				<?php } ?>

				<?php if($pagination) { ?>
					<div class="mdl-card mdl-shadow--2dp mdl-color--<?php echo $this->config->item('material-design/colors/background/card'); ?> mdl-cell mdl-cell--12-col paging">
						<div class="mdl-card__supporting-text mdl-color-text--<?php echo $this->config->item('material-design/colors/text/content'); ?>">
							<?php foreach($pagination as $page => $from) { ?>
								<?php if($current_from == $from) { ?>
									<strong><?php echo $page; ?></strong>
								<?php } else { ?>
									<a href="<?php echo current_url(); ?>?q=<?php echo $this->input->get('q'); ?>&amp;sort_field=<?php echo $this->input->get('sort_field'); ?>&amp;sort_direction=<?php echo $this->input->get('sort_direction'); ?>&amp;from=<?php echo $from; ?>"><?php echo $page; ?></a>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
			<?php } ?>
		<?php } ?>
	</div>
</main>
