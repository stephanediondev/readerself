<div class="mdl-tooltip" for="tip_add"><?php echo $this->lang->line('add'); ?></div>
<div class="mdl-tooltip" for="tip_import"><?php echo $this->lang->line('import'); ?></div>
<div class="mdl-tooltip" for="tip_export"><?php echo $this->lang->line('export'); ?></div>

<div class="mdl-layout__drawer">
	<nav class="mdl-navigation">
		<ul>
			<li>
				<?php echo form_open(current_url()); ?>
				<p>
				<?php echo form_label($this->lang->line('title'), 'subscriptions_fed_title'); ?>
				<?php echo form_input($this->router->class.'_subscriptions_fed_title', set_value($this->router->class.'_subscriptions_fed_title', $this->session->userdata($this->router->class.'_subscriptions_fed_title')), 'id="subscriptions_fed_title" class="inputtext"'); ?>
				</p>
				<?php if($this->config->item('folders')) { ?>
					<p>
					<?php echo form_label($this->lang->line('folder'), 'subscriptions_flr_id'); ?>
					<?php echo form_dropdown($this->router->class.'_subscriptions_flr_id', $folders, set_value($this->router->class.'_subscriptions_flr_id', $this->session->userdata($this->router->class.'_subscriptions_flr_id')), 'id="subscriptions_flr_id" class="select numeric"'); ?>
					</p>
				<?php } ?>
				<p>
				<?php echo form_label($this->lang->line('priority'), 'subscriptions_sub_priority'); ?>
				<?php echo form_dropdown($this->router->class.'_subscriptions_sub_priority', array('' => '--', 0 => $this->lang->line('no'), 1 => $this->lang->line('yes')), set_value($this->router->class.'_subscriptions_sub_priority', $this->session->userdata($this->router->class.'_subscriptions_sub_priority')), 'id="subscriptions_sub_priority" class="select numeric"'); ?>
				</p>
				<?php if($errors > 0) { ?>
					<p>
					<?php echo form_label($this->lang->line('errors').' ('.$errors.')', 'subscriptions_fed_lasterror'); ?>
					<?php echo form_dropdown($this->router->class.'_subscriptions_fed_lasterror', array('' => '--', 0 => $this->lang->line('no'), 1 => $this->lang->line('yes')), set_value($this->router->class.'_subscriptions_fed_lasterror', $this->session->userdata($this->router->class.'_subscriptions_fed_lasterror')), 'id="subscriptions_fed_lasterror" class="select numeric"'); ?>
					</p>
				<?php } ?>
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
				<h1 class="mdl-card__title-text"><i class="material-icons md-18">bookmark</i><?php echo $this->lang->line('subscriptions'); ?> (<?php echo $position; ?>)</h1>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a id="tip_add" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>subscriptions/create"><i class="material-icons md-18">add</i></a>
				<a id="tip_import" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>subscriptions/import"><i class="material-icons md-18">file_download</i></a>
				<a id="tip_export" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>subscriptions/export"><i class="material-icons md-18">file_upload</i></a>
			</div>
		</div>

		<?php if($subscriptions) { ?>
			<?php if($pagination) { ?>
				<div class="mdl-card mdl-cell mdl-cell--12-col paging">
					<div class="mdl-card__supporting-text mdl-color-text--black">
						<?php echo $pagination; ?>
					</div>
				</div>
			<?php } ?>
			<?php foreach($subscriptions as $sub) { ?>
				<div<?php if($sub->sub_direction) { ?> dir="<?php echo $sub->sub_direction; ?>"<?php } else if($sub->fed_direction) { ?> dir="<?php echo $sub->fed_direction; ?>"<?php } ?> class="mdl-card mdl-cell mdl-cell--4-col">
					<div class="mdl-card__title">
						<h1 class="mdl-card__title-text"><a style="background-image:url(https://www.google.com/s2/favicons?domain=<?php echo $sub->fed_host; ?>&amp;alt=feed);" class="favicon" href="<?php echo base_url(); ?>subscriptions/read/<?php echo $sub->sub_id; ?>"><?php echo $sub->fed_title; ?><?php if($sub->sub_title) { ?> / <em><?php echo $sub->sub_title; ?></em><?php } ?></a></h1>
						<div class="mdl-card__title-infos">
							<?php if($sub->fed_url) { ?>
								<a class="mdl-navigation__link" href="<?php echo $sub->fed_url; ?>" target="_blank"><i class="material-icons md-16">open_in_new</i><?php echo $sub->fed_url; ?></a>
							<?php } ?>
							<?php if($this->config->item('folders')) { ?>
								<?php if($sub->flr_title) { ?><a class="mdl-navigation__link" href="<?php echo base_url(); ?>folders/read/<?php echo $sub->flr_id; ?>"><i class="material-icons md-16">folder</i><?php echo $sub->flr_title; ?></a><?php } ?>
							<?php } ?>
						</div>
					</div>
					<div class="mdl-card__supporting-text mdl-color-text--black">
						<?php if($sub->fed_lasterror) { ?>
							<p><?php echo $sub->fed_lasterror; ?></p>
						<?php } ?>
						<?php if($this->config->item('tags') && $sub->categories) { ?>
							<p><?php echo implode(', ', $sub->categories); ?></p>
						<?php } ?>
						<p><?php echo $sub->fed_description; ?></p>
					</div>
					<div class="mdl-card__actions mdl-card--border">
						<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" href="<?php echo base_url(); ?>subscriptions/update/<?php echo $sub->sub_id; ?>"><i class="material-icons md-18">mode_edit</i></a>
						<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon subscribe" href="<?php echo base_url(); ?>subscriptions/delete/<?php echo $sub->sub_id; ?>"><i class="material-icons md-18">delete</i></a>
						<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon priority" href="<?php echo base_url(); ?>subscriptions/priority/<?php echo $sub->sub_id; ?>"><?php if($sub->sub_priority == 0) { ?><i class="material-icons md-18">chat_bubble_outline</i><?php } ?><?php if($sub->sub_priority == 1) { ?><i class="material-icons md-18">announcement</i><?php } ?></a>
					</div>
				</div>
			<?php } ?>
			<?php if($pagination) { ?>
				<div class="mdl-card mdl-cell mdl-cell--12-col paging">
					<div class="mdl-card__supporting-text mdl-color-text--black">
						<?php echo $pagination; ?>
					</div>
				</div>
			<?php } ?>
		<?php } ?>
	</div>
</main>
